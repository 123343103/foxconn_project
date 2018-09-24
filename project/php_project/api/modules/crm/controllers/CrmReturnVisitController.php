<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/23
 * Time: 11:44
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\search\CrmImessageSearch;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\crm\models\search\CrmVisitRecordSearch;
use app\modules\crm\models\show\CrmImessageShow;
use app\modules\crm\models\show\CrmVisitRecordChildShow;
use app\modules\crm\models\show\CrmVisitRecordShow;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * CrmMemberController implements the CRUD actions for CrmMember model.
 */
class CrmReturnVisitController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmReturnVisit';

    public function actionIndex(){
        $searchModel = new CrmVisitRecordSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProviders = $searchModel->searchReturnVisit($queryParams);
        $model = $dataProviders->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProviders->totalCount;
        return $list;
    }
    /**
     * 新增回访记录
     */
    public function actionCreate()
    {
        $child = new CrmVisitRecordChild();
        $post=Yii::$app->request->post();
        $model=CrmVisitRecord::find()->where(['sih_id'=>intval($post['CrmVisitRecord']['sih_id'])])->one();
        $model=!empty($model) ? $model : new CrmVisitRecord();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load($post);
            $model->sih_status = CrmVisitRecord::STATUS_DEFAULT;
            $model->save();
            $sihId=$model->sih_id;
            $firmId = $model->cust_id;
            $child->load($post);
            $child->sih_id=$sihId;
            $child->type = '40';
            $child->save();
            $silId = $child->sil_id;
            $firm = CrmCustomerInfo::getCustomerInfoOne($firmId);
            $firm->member_visitflag=CrmCustomerInfo::VISITFLAG_YES;
            $firm->save();
            $transaction->commit();
            $cid = array('id'=>$sihId,'childId'=>$silId,'message'=>'会员回访:新增客户"'.$firm['cust_sname'].'"拜访信息');
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
    public function actionUpdate($id,$childId){
        $post=Yii::$app->request->post();
        $child=CrmVisitRecordChild::find()->where(['sil_id'=>$childId])->andWhere(['<>','sil_status',CrmVisitRecordChild::STATUS_DELETE])->one();
        $model=CrmVisitRecord::find()->where(['cust_id'=>intval($post['CrmVisitRecord']['cust_id'])])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $sih=CrmVisitRecord::find()->where(['sih_id'=>$id])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(!empty($model)){
                $child->load($post);
                $child->sih_id = $model['sih_id'];
                $child->save();
            }
            $model=!empty($model) ? $model : $sih;
            $model->load($post);
            $model->save();
            $sih_id = $model->sih_id;
            $child->load($post);
            $child->sih_id = $sih_id;
            $child->save();
            $firm = CrmCustomerInfo::getCustomerInfoOne($sih_id);
            $cid = array('id'=>$sih_id,'childId'=>$childId,'message'=>'会员回访:修改客户"'.$firm['cust_sname'].'"拜访信息');
            $transaction->commit();
            return $this->success($msg=null,$cid);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionDeleteCustomer($id){
        $data = explode(',',$id);
        $name = '';
        foreach ($data as $key => $val){
            $model = $this->getModel($val);
            $firm = CrmCustomerInfo::find()->where(['cust_id'=>$model['cust_id']])->select('cust_sname')->one();
            $model->sih_status = CrmVisitRecord::STATUS_DELETE;
            $name = $name.$firm['cust_sname'].',';
            $result = $model->save();
        }
        $cust_sname = trim($name,',');
        if ($result) {
            return $this->success('','删除会员['.$cust_sname.']信息');
        } else {
            return $this->error();
        }
    }
    /**
     * @param null $id
     * @param null $childId
     * @return array
     * 删除
     */
    public function actionDelete($id,$childId){
        $model = $this->getModel($id);
        $firm = CrmCustomerInfo::find()->where(['cust_id'=>$model['cust_id']])->one();
        $childModel = $this->getChildModel($childId);
        $childModel->sil_status = CrmVisitRecordChild::STATUS_DELETE;
        $result = $childModel->save();
        $childm = CrmVisitRecordChild::find()->where(['sih_id'=>$id])->andWhere(['!=','sil_status',CrmVisitRecordChild::STATUS_DELETE])->count();
        if($childm == 0){
            $model->sih_status = CrmVisitRecord::STATUS_DELETE;
            $model->save();
            $firm->member_visitflag = CrmCustomerInfo::VISITFLAG_NO;
            $firm->save();
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
     * 列表
     */
    public function actionLoadInformation(){
        $result['reminder'] = $this->actionLoadReminder();
        $result['record'] = $this->actionLoadRecord();
        return $result;
    }

    /**
     * @return mixed
     * 回访记录列表
     */
    public function actionLoadRecord(){
        $searchModel = new CrmVisitRecordChildSearch();
        $dataProvider = $searchModel->searchMemberInfo(Yii::$app->request->queryParams);
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
     * @return array
     * 提醒事项
     */
    public function actionReminders($id){
        $model = new CrmImessage();
        $post = Yii::$app->request->post();
        $model->load($post);
        $model->cust_id = $id;
        $cust = CrmCustomerInfo::getCustomerInfoOne($id);
        if ($result = $model->save()) {
            return $this->success('','新增客户['.$cust["cust_sname"].']提醒信息');
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array
     * 删除提醒事项
     */
    public function actionDeleteReminders($id){
        $childModel = $this->getReminderModel($id);
        $firm = CrmCustomerInfo::find()->where(['cust_id'=>$childModel['cust_id']])->select('cust_sname')->one();
        $childModel->imesg_status = CrmImessage::STATUS_DEL;
        if ($result = $childModel->save()) {
            return $this->success('','会员回访:删除'.$firm['cust_sname'].' 提醒事项');
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @param $code
     * @param $start
     * @param $end
     * @return int|string
     * 判断某个时间内是否有记录
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
     * @return mixed
     * 选择客户
     */
    public function actionSelectCustomer()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchSelectMember(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;

    }

    /**
     * @return mixed
     * 下拉列表
     */
    public function actionDownList(){
        //会员类别
        $downList['memberType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);
        //会员等级
        $downList['memberLevel'] = BsPubdata::getList(BsPubdata::CRM_MEMBER_LEVEL);
        //注册网站
        $downList['regWeb'] = BsPubdata::getList(BsPubdata::CRM_REGISTER_WEB);
        //客户来源
        $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);
        //经营模式
        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);
        //公司类型
        $downList['property'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);
        //币别
        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY);
        //潜在需求
        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);
        //需求类目
        $downList['productType']= BsCategory::getLevelOne();
        //所在国家
        $downList['country'] = BsDistrict::getDisLeveOne();
        //拜访类型
        $downList['visitType'] = BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);
        //公司所在地区
        $downList['saleArea'] = CrmSalearea::getSalearea();
        $downList['invoiceType'] = BsPubdata::getList(BsPubdata::CRM_INVOICE_TYPE);  //发票类型
        return $downList;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     * 提醒给xx
     */
    public function actionEmployee(){
        return CrmEmployee::find()->joinWith('staffName')->andWhere(['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT])->asArray()->all();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询主表信息
     */
    public function actionVisitInfo($id){
        return CrmVisitRecordShow::find()->where(['sih_id'=>$id])->one();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 根据客户Id查询主表
     */
    public function actionCustInfo($id){
        return CrmVisitRecordShow::find()->where(['cust_id'=>$id])->one();
    }

    /**
     * @param $id
     *@return array|null|\yii\db\ActiveRecord
     * 查询对应主表下子表条数
     */
    public function actionCountChild($id){
        return CrmVisitRecordChild::find()->where(['sih_id'=>$id])->andWhere(['and',['!=','sil_status',CrmVisitRecordChild::STATUS_DELETE]])->count();
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询一条子表信息
     */
    public function actionVisitChild($id){
        return CrmVisitRecordChildShow::find()->where(['and',['sil_id'=>$id],['sil_status'=>'10']])
//            ->andWhere(['=','type',CrmVisitRecordChild::TYPE_MEMBER])
            ->one();
    }
    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询所有子表信息
     */
    public function actionAllVisitChild($id){
        return CrmVisitRecordChildShow::find()->where(['and',['sih_id'=>$id],['sil_status'=>'10']])
//            ->andWhere(['=','type',CrmVisitRecordChild::TYPE_MEMBER])
            ->orderBy('create_at DESC')->all();
    }
    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 查询提醒事项信息
     */
    public function actionReminderOne($id){
        return CrmImessageShow::find()->where(['obj_id'=>$id])->one();
    }
    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询主表
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
     * 查询子表
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
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询提醒事项
     */
    protected function getReminderModel($id)
    {
        if (($model = CrmImessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}