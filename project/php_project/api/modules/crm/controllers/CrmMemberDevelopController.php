<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmActImessageSearch;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\search\CrmImessageSearch;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmMemberShow;
use app\modules\crm\models\show\CrmVisitRecordChildShow;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * CrmMemberController implements the CRUD actions for CrmMember model.
 */
class CrmMemberDevelopController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmMemberDevelop';

    /**
     * @return mixed
     * 列表
     */
    public function actionIndex(){
        $searchModel = new CrmCustomerInfoSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->searchMemberDevelop($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return array
     * 新增
     */
    public function actionCreate(){
        $crmMember = new CrmCustomerInfo();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $crmMember->load($post);
            if(empty($post['adminId'])){
                $crmMember->personinch_status=CrmCustomerInfo::PERSONINCH_YES;
            }
            if (!$crmMember->save()) {
//                throw new \Exception("新增客户失败");
                $errors=$crmMember->getErrors();
                $str='';
                foreach($errors as $key=>$val){
                    $str.=$key.implode(',',$val);
                }
                throw new \Exception($str);
            }
            $custId = $crmMember->cust_id;
            /*状态*/
            $status = new CrmCustomerStatus();
            $status->customer_id = $custId;
            $status->potential_status = CrmCustomerStatus::STATUS_DEFAULT;
            if (!$status->save()) {
//                throw new \Exception("新增客户状态失败");
                $errors=$status->getErrors();
                $str='';
                foreach($errors as $key=>$val){
                    $str.=$key.implode(',',$val);
                }
                throw new \Exception($str);
            }
            //认领表
            if(empty($post['adminId'])){
                $claimModel=new CrmCustPersoninch();
                $claimModel->cust_id=$crmMember->cust_id;
                $claimModel->ccpich_stype=CrmCustPersoninch::PERSONINCH_SALES;
                $claimModel->ccpich_personid=$post['CrmCustomerInfo']['create_by'];
                $claimModel->ccpich_date=date('Y-m-d');
                if(!$claimModel->save()){
//                    throw  new \Exception("认领表保存失败！");
                    $errors=$claimModel->getErrors();
                    $str='';
                    foreach($errors as $key=>$val){
                        $str.=$key.implode(',',$val);
                    }
                    throw new \Exception($str);
                }
            }
        }catch (\Exception $e){
            file_put_contents("aa.txt",$e->getMessage(),FILE_APPEND);
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success($msg = null, $custId);
    }

    /**
     * @param $id
     * @return array
     * 修改
     */
    public function actionUpdate($id,$type=null,$ctype=null){
        $transaction = Yii::$app->db->beginTransaction();
        $contact = CrmCustomerInfo::findOne($id);
        $status = $this->getStatusModel($id);
        $post = Yii::$app->request->post();
        try{
            $contact->load($post);
            if(!$contact->save()){
                throw new \Exception("修改信息失败");
            }
            if($ctype == 1){
                if(isset($post["CrmCustomerPersion"])){
                    CrmCustomerPersion::deleteAll(["cust_id"=>$contact->primaryKey]);
                    for($x=0;$x<count($post["CrmCustomerPersion"]["ccper_name"]);$x++){
                        $personModel=new CrmCustomerPersion();
                        $attrs=array_combine(array_keys($post["CrmCustomerPersion"]),array_column($post["CrmCustomerPersion"],$x));
                        $attrs["cust_id"]=$contact->primaryKey;
                        $personModel->setAttributes($attrs);
                        if(!($personModel->validate() && $personModel->save())){
                            throw new \Exception("联系人信息保存失败");
                            break;
                        }
                    }
                }
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();
    }

    public function actionVisitCreate(){
        $child = new CrmVisitRecordChild();
        $post=Yii::$app->request->post();

        $model=CrmVisitRecord::find()->where(['cust_id'=>intval($post['CrmVisitRecord']['cust_id'])])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $model=!empty($model) ? $model : new CrmVisitRecord();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //招商客户改开发中
            if($post['ctype']==3){
                $statusModel = CrmCustomerStatus::find()->where(['customer_id'=>intval($post['CrmVisitRecord']['cust_id'])])->one();
                if($statusModel->investment_status==CrmCustomerStatus::INVESTMENT_UN){
                    $statusModel->investment_status=CrmCustomerStatus::INVESTMENT_IN;
                    $statusModel->save();
                }
                $type = CrmVisitRecordChild::TYPE_INVESTMENT;
            }else if($post['ctype']==6){
                $type = CrmVisitRecordChild::TYPE_INVESTMENT;
            }else{
                $type = CrmVisitRecordChild::TYPE_POTENTIAL;
            }
            $model->load($post);
            $model->save();
            $sihId=$model->sih_id;
            $firmId = $model->cust_id;
            $child->load($post);
            $child->sih_id=$sihId;
            $child->type = $type;
            $child->save();
            $silId = $child->sil_id;
            $firm = CrmCustomerInfo::getCustomerInfoOne($firmId);
            $firm->member_visitflag = CrmCustomerInfo::VISITFLAG_YES;
            $firm->save();
            $transaction->commit();
            $cid = array('id'=>$post['CrmVisitRecord']['cust_id'],'childId'=>$silId,'message'=>'拜访记录:新增客户"'.$firm['cust_sname'].'"拜访信息');
            return $this->success($msg=null,$cid);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
    /**
     * @param $id
     * @param $childId
     * @return array
     * 修改拜访记录
     */
    public function actionVisitUpdate($id,$childId){
        $child=CrmVisitRecordChild::find()->where(['sil_id'=>$childId])->andWhere(['<>','sil_status',CrmVisitRecordChild::STATUS_DELETE])->one();
        $model=CrmVisitRecord::find()->where(['cust_id'=>$id])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $post=Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load($post);
            $model->save();
            $custId = $model->cust_id;
            $child->load($post);
            $child->save();
            $firm = CrmCustomerInfo::getCustomerInfoOne($custId);
            $cid = array('id'=>$custId,'childId'=>$childId,'message'=>'拜访记录:修改客户"'.$firm['cust_sname'].'"拜访信息');
            $transaction->commit();
            return $this->success($msg=null,$cid);
        } catch (\Exception $e) {
            file_put_contents("data.txt",$e->getTraceAsString(),FILE_APPEND);
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $childId
     * @return array
     * 删除拜访记录
     */
    public function actionDelete($id,$childId){
        $model = $this->getModel($id);
        $firm = CrmCustomerInfo::find()->where(['cust_id'=>$model['cust_id']])->select('cust_sname')->one();
        $childModel = $this->getChildModel($childId);
        $childModel->sil_status = CrmVisitRecordChild::STATUS_DELETE;
        $result = $childModel->save();
        $childm = CrmVisitRecordChild::find()->where(['sih_id'=>$id])->andWhere(['!=','sil_status',CrmVisitRecordChild::STATUS_DELETE])->count();
        if($childm == 0){

            $mainModel=CrmVisitRecord::findOne($childModel->sih_id);
            $mainModel->sih_status=0;
            $mainModel->save();

            $firmm = CrmCustomerInfo::find()->where(['cust_id'=>$model['cust_id']])->one();
            $firmm->member_visitflag = CrmCustomerInfo::VISITFLAG_NO;
            $firmm->save();
        }
        if ($result) {
            return $this->success('','会员回访:删除'.$firm['cust_sname'].' 拜访记录');
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询拜访记录主表
     */
    protected function getModel($id)
    {
        if (($model = CrmVisitRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询拜访记录子表
     */
    protected function getChildModel($id)
    {
        if (($model = CrmVisitRecordChild::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return array
     * 提醒事项
     */
    public function actionReminders(){
        $model = new CrmImessage();
        $post = Yii::$app->request->post();
        $post['CrmImessage']['imesg_status'] = '1';
        $model->load($post);
        if ($result = $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }
    /**
     * @param $id
     * @return array
     * 转会员
     */
    public function actionTurnMember($id)
    {
        $model = $this->getStatusModel($id);
        $cust = CrmCustomerInfo::getCustomerInfoOne($id);
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            /*状态*/
            $model->load($post);
            $model->potential_status = CrmCustomerStatus::STATUS_DEL;
            $model->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            $inch = CrmCustPersoninch::find()->where(['and',['cust_id'=>$id],['ccpich_stype'=>CrmCustPersoninch::PERSONINCH_DEFAULT]])->one();
            if(!empty($inch)){
                $inch->delete();
            }
            if (!$model->save()) {
                throw new \Exception("转会员失败");
            }
            $cust->load($post);
            $cust->cust_ismember = CrmCustomerInfo::MEMBER_YES;
            foreach ($cust as $k=>$v) {
                $cust[$k] = html_entity_decode($v);
            }
            if (!$cust->save()) {
                throw new \Exception(current($cust->getFirstErrors()));
                throw new \Exception("完善会员信息失败");
            }
            $record = CrmVisitRecord::find()->where(['cust_id'=>$id])->select('sih_id')->one();
            if(!empty($record)){
                $recordChild = CrmVisitRecordChild::find()->where(['sih_id'=>$record['sih_id']])->andWhere(['type'=>CrmVisitRecordChild::TYPE_POTENTIAL])->select('sil_id')->asArray()->all();
                foreach ($recordChild as $k => $value){
                    $child = CrmVisitRecordChild::find()->where(['sil_id'=>$value])->one();
                    $child->type = CrmVisitRecordChild::TYPE_MEMBER;
                    $child->save();
                }
                $cust->member_visitflag = CrmCustomerInfo::VISITFLAG_YES;
                $cust->save();
            }

        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('','客户['.$cust["cust_sname"].']转会员');
    }
//    /**
//     * @param $id
//     * @return mixed
//     * 拜访记录等列表
//     */
//    public function actionLoadInformation(){
//        $result['reminder'] = $this->actionLoadReminder();
//        $result['record'] = $this->actionLoadRecord();
//        $result['message'] = $this->actionLoadMessage();
//        return $result;
//    }

    /**
     * @return mixed
     * 回访记录列表
     */
    public function actionLoadRecord(){
        $searchModel = new CrmVisitRecordChildSearch();
        $dataProvider = $searchModel->searchPentInfo(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    /**
     * @param $id
     * @return mixed
     * 提醒事项列表
     */
    public function actionLoadReminder(){
        $searchModel = new CrmImessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @param $code
     * @param $start
     * @param $end
     * @return int|string
     * 判断某个时间内是否有拜访记录
     */
    public function actionCheckTime($id,$childId,$code,$start,$end){
        $visit = CrmVisitRecord::find()->select(['sih_id'])->where(['cust_id'=>$id])->one();
        if(!isset($childId)){
            $child =
                CrmVisitRecordChild::find()
                    ->where(['sih_id'=>$visit['sih_id']])
                    ->andWhere(['sil_staff_code'=>$code])
                    ->andWhere(['=','sil_status',CrmVisitRecordChild::STATUS_DEFAULT])
                    ->andWhere(['or',['and',['<=','start',date('Y-m-d H:i:s', $start/1000)],['>=','end',date('Y-m-d H:i:s', $end/1000)]],['between','start',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)],['between','end',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)]])
                    ->count();
        }else{
            $child =
                CrmVisitRecordChild::find()
                    ->where(['and',['sih_id'=>$visit['sih_id']],['!=','sil_id',$childId]])
                    ->andWhere(['sil_staff_code'=>$code])
                    ->andWhere(['=','sil_status',CrmVisitRecordChild::STATUS_DEFAULT])
                    ->andWhere(['or',['and',['<=','start',date('Y-m-d H:i:s', $start/1000)],['>=','end',date('Y-m-d H:i:s', $end/1000)]],['between','start',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)],['between','end',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)]])
                    ->count();
        }

        return $child;
    }
    /**
     * @param $id
     * @param $code
     * @param $start
     * @param $end
     * @return int|string
     * 判断某个时间内是否有提醒事项
     */
    public function actionCheckReminderTime($id,$childId,$code,$start,$end){
        if(empty($childId)){
            $child =
                CrmImessage::find()
                    ->where(['and',['cust_id'=>$id],['imesg_status'=>CrmImessage::STATUS_DEFAULT]])
                    ->andWhere(['imesg_receiver'=>$code])
                    ->andWhere(['and',['<=','imesg_btime',date('Y-m-d H:i:s', $end/1000)],['>=','imesg_etime',date('Y-m-d H:i:s', $start/1000)]])
//                ->andWhere(['or',['and',['<=','start',date('Y-m-d H:i:s', $start/1000)],['>=','end',date('Y-m-d H:i:s', $end/1000)]],['between','start',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)],['between','end',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)]])
                    ->count();
        }else{
            $child =
                CrmImessage::find()
                    ->where(['and',['cust_id'=>$id],['!=','imesg_id',$childId],['imesg_status'=>CrmImessage::STATUS_DEFAULT]])
                    ->andWhere(['imesg_receiver'=>$code])
                    ->andWhere(['and',['<=','imesg_btime',date('Y-m-d H:i:s', $end/1000)],['>=','imesg_etime',date('Y-m-d H:i:s', $start/1000)]])
//                ->andWhere(['or',['and',['<=','start',date('Y-m-d H:i:s', $start/1000)],['>=','end',date('Y-m-d H:i:s', $end/1000)]],['between','start',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)],['between','end',date('Y-m-d H:i:s', $start/1000),date('Y-m-d H:i:s', $end/1000)]])
                    ->count();
        }

        return $child;
    }
    /**
     * @param $id
     * @param $attr
     * @param $val
     * @return string
     *
     */
//    public function actionValidateDevelop($id, $attr, $val)
//    {
////        $class = $this->modelClass;//默认使用moduleClass作为验证类
//        $model = CrmCustomerInfo::findOne($id);
//        if ($val === $model['cust_sname']) {
//            return '';
//        } else {
//            $model = CrmCustomerInfo::find()->joinWith('status')->where(['and', ['cust_sname' => $val], ['potential_status' => CrmCustomerStatus::STATUS_DEFAULT]])->one();
//            if (!empty($model)) {
//                return '客户已存在';
//            } else {
//                return '';
//            }
//        }
//    }

    /**
     * @param $id
     * @return mixed
     * 通讯记录
     */
    public function actionLoadMessage(){
        $searchModel = new CrmActImessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return mixed
     * 选择客户
     */
    public function actionSelectCustomer()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchSelectPoten(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;

    }
    /**
     * @param $id
     *@return array|null|\yii\db\ActiveRecord
     * 查询对应主表下子表条数
     */
    public function actionCountChild($id){
        return CrmVisitRecordChild::find()->where(['sih_id'=>$id])->andWhere(['and',['!=','sil_status',CrmVisitRecordChild::STATUS_DELETE],['=','type',CrmVisitRecordChild::TYPE_POTENTIAL]])->count();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询一条子表信息
     */
    public function actionVisitChild($id){
        return CrmVisitRecordChildShow::find()->where(['and',['sil_id'=>$id],['sil_status'=>'10']])
//            ->andWhere(['=','type',CrmVisitRecordChild::TYPE_POTENTIAL])
            ->one();
    }
    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询所有子表信息
     */
    public function actionAllVisitChild($id){
        return CrmVisitRecordChildShow::find()->where(['and',['sih_id'=>$id],['sil_status'=>'10']])
//            ->andWhere(['=','type',CrmVisitRecordChild::TYPE_POTENTIAL])
            ->orderBy('create_at DESC')->all();
    }

    public function actionModels($id)
    {
        $result = CrmCustomerInfoShow::getCustomerInfoOne($id);
        return $result;
    }

    protected function getStatusModel($id)
    {
        if (($model = CrmCustomerStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //抛至公海
    public function actionThrowSea($arrId)
    {
        $arrId=explode('-',$arrId);
        foreach($arrId as $id){
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //客户表
                $customerModel=CrmCustomerInfo::findOne($id);
                $customerModel->personinch_status=CrmCustomerInfo::PERSONINCH_NO;
                if(!$customerModel->save()){
                    throw new \Exception('客户表保存失败！');
                }
                //认领表
//                $personinchModel=CrmCustPersoninch::findOne(['cust_id'=>$id]);
//                $personinchModel->ccpich_status=CrmCustPersoninch::STATUS_DELETE;
//                if(!$personinchModel->save()){
//                    throw new \Exception('认领表保存失败！');
//                }
                $personinch = CrmCustPersoninch::find()->where(['cust_id'=>$id])->andWhere(['ccpich_stype'=>CrmCustPersoninch::PERSONINCH_DEFAULT])->one();
                $personinch->delete();
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
            }
        }
        return $this->success();
    }
}
