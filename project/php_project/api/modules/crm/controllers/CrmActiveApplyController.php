<?php
/**
 * User: F1677929
 * Date: 2017/2/10
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActImessage;
use app\modules\crm\models\CrmActiveApply;
use app\modules\crm\models\CrmActiveCheckIn;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActivePay;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\search\CrmActImessageSearch;
use app\modules\crm\models\search\CrmActiveApplySearch;
use app\modules\crm\models\search\CrmActiveCheckInSearch;
use app\modules\crm\models\search\CrmActivePaySearch;
use app\modules\crm\models\show\CrmActiveApplyShow;
use Yii;
use app\modules\system\models\SysDisplayList;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Html;
//活动报名接口控制器
class CrmActiveApplyController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmCustomerInfo';

    //活动报名列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            $sql="select a.acttype_id,b.bsp_svalue from erp.crm_bs_acttype a left join erp.bs_pubdata b on b.bsp_id=a.acttype_name where a.acttype_status=10";
            $activeType=Yii::$app->db->createCommand($sql)->queryAll();
            if(!empty($activeType)){
                $activeType=array_column($activeType,'bsp_svalue','acttype_id');
            }
            return [
                'activeType'=>$activeType
            ];
        }
        $model=new CrmActiveApplySearch();
        $dataProvider=$model->searchApply($params);
        $rows=$dataProvider->getModels();
        if(!empty($rows)){
            foreach($rows as &$row){
                $row['actbs_start_time']=date('Y-m-d H:i',strtotime($row['actbs_start_time']));
                $row['actbs_end_time']=date('Y-m-d H:i',strtotime($row['actbs_end_time']));
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount,
        ];
    }

    //加载签到信息
    public function actionCheckInInfo()
    {
        $model=new CrmActiveCheckInSearch();
        $dataProvider=$model->searchCheckInInfo(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //加载缴费信息
    public function actionPayInfo()
    {
        $model=new CrmActivePaySearch();
        $dataProvider=$model->searchPayInfo(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //加载通讯记录
    public function actionMessageInfo()
    {
        $model=new CrmActImessageSearch();
        $dataProvider=$model->search(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //新增活动报名
    public function actionAdd()
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //客户信息表
                $customerInfoModel=CrmCustomerInfo::findOne($data['CrmCustomerInfo']['cust_id']);
                if(empty($customerInfoModel)){
                    $customerInfoModel=new CrmCustomerInfo();
                }
                $customerInfoModel->cust_contacts=$data['CrmActiveApply']['acth_name'];
                $customerInfoModel->cust_position=$data['CrmActiveApply']['acth_position'];
                $customerInfoModel->cust_tel2=$data['CrmActiveApply']['acth_phone'];
                $customerInfoModel->cust_email=$data['CrmActiveApply']['acth_email'];
                if(!($customerInfoModel->load($data)&&$customerInfoModel->save())){
                    throw new \Exception(json_encode($customerInfoModel->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //活动报名表
                $activeApplyModel=new CrmActiveApply();
                $activeApplyModel->cust_id=$customerInfoModel->cust_id;
                if(!($activeApplyModel->load($data)&&$activeApplyModel->save())){
                    $error=$activeApplyModel->getErrors();
                    if(!empty($error['cust_id'])){
                        throw new \Exception('该客户已报名！');
                    }
                    throw new \Exception(json_encode($activeApplyModel->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //客户状态表
                $customerStatusModel=CrmCustomerStatus::findOne($customerInfoModel->cust_id);
                if(empty($customerStatusModel)){
                    $customerStatusModel=new CrmCustomerStatus();
                    $customerStatusModel->customer_id=$customerInfoModel->cust_id;
                }
                $customerStatusModel->apply_status=CrmCustomerStatus::STATUS_DEFAULT;
                $customerStatusModel->potential_status=CrmCustomerStatus::STATUS_DEFAULT;
                if(!$customerStatusModel->save()){
                    throw new \Exception('客户状态表保存失败');
                }
                $transaction->commit();
                return $this->success($activeApplyModel->acth_code,$activeApplyModel->acth_id);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->getAddEditData();
    }

    //获取新增修改数据
    private function getAddEditData()
    {
        return [
            //活动类型
            'activeType'=>CrmActiveType::getActiveType(),
            //参与身份
            'joinIdentity'=>BsPubdata::getData(BsPubdata::CRM_JOIN_IDENTITY),
            //国家
            'country'=>BsDistrict::getChildByParentId(0),
            //经营模式
            'manageModel'=>BsPubdata::getData(BsPubdata::CRM_MANAGEMENT_TYPE),
            //公司类型
            'companyType'=>BsPubdata::getData(BsPubdata::CRM_CUSTOMER_TYPE),
            //注册货币
            'registerCurrency'=>BsPubdata::getData(BsPubdata::SUPPLIER_TRADE_CURRENCY),
            //客户来源
            'customerSource'=>BsPubdata::getData(BsPubdata::CRM_CUSTOMER_SOURCE),
            //潜在需求
            'potentialRequired'=>BsPubdata::getData(BsPubdata::CRM_LATENT_DEMAND),
            //需求类目
            'requiredType'=>BsCategory::getLevelOne(),
            //交易币种
            'TradingCurrency'=>BsPubdata::getData(BsPubdata::SUPPLIER_TRADE_CURRENCY),
        ];
    }

    //获取活动名称
    public function actionGetActiveName($typeId)
    {
        return CrmActiveName::find()->select(['actbs_id','actbs_name'])->where(['acttype_id'=>$typeId])->orderBy(['actbs_id'=>SORT_DESC])->all();
    }

    //获取活动时间
    public function actionGetActiveTime($nameId)
    {
        return CrmActiveName::findOne($nameId);
    }

    //获取客户信息
    public function actionGetCustomerInfo($name)
    {
        $customerInfo=CrmCustomerInfo::find()->where(['cust_sname'=>$name])->one();
        if(empty($customerInfo)){
            return $customerInfo;
        }
        return [
            'customerInfo'=>$customerInfo,
            'districtData'=>$this->getDistrict($customerInfo->cust_district_2),
        ];
    }

    //获取修改查看数据
    private function getEditViewData($id)
    {
        $data=(new Query())->select([
            CrmActiveApply::tableName().'.*',//活动报名表字段
            CrmActiveType::tableName().'.acttype_name',//类型名
            CrmActiveName::tableName().'.actbs_name',//活动名称
            CrmActiveName::tableName().'.actbs_start_time',//活动开始时间
            CrmActiveName::tableName().'.actbs_end_time',//活动结束时间
            'bp1.bsp_svalue AS joinIdentity',//参会身份
            CrmCustomerInfo::tableName().'.*',//客户表字段
            'bp2.bsp_svalue AS manageModel',//经营模式
            'bp3.bsp_svalue AS customerType',//公司类型
            'bp4.bsp_svalue AS registerCurrency',//注册货币
            'bp5.bsp_svalue AS customerSource',//客户来源
            'bp6.bsp_svalue AS potentialRequired',//潜在需求
            BsCategory::tableName().'.category_sname AS requiredType',//需求类目
            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.CrmCustomerInfo::tableName().'.cust_adress) AS customerAddress',//客户地址
        ])->from(CrmActiveApply::tableName())
            ->leftJoin(CrmActiveType::tableName(),CrmActiveType::tableName().'.acttype_id='.CrmActiveApply::tableName().'.acttype_id')
            ->leftJoin(CrmActiveName::tableName(),CrmActiveName::tableName().'.actbs_id='.CrmActiveApply::tableName().'.actbs_id')
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmActiveApply::tableName().'.acth_identity')
            ->leftJoin(CrmCustomerInfo::tableName(),CrmCustomerInfo::tableName().'.cust_id='.CrmActiveApply::tableName().'.cust_id')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.CrmCustomerInfo::tableName().'.cust_businesstype')
            ->leftJoin(BsPubdata::tableName().' bp3','bp3.bsp_id='.CrmCustomerInfo::tableName().'.cust_type')
            ->leftJoin(BsPubdata::tableName().' bp4','bp4.bsp_id='.CrmCustomerInfo::tableName().'.member_regcurr')
            ->leftJoin(BsPubdata::tableName().' bp5','bp5.bsp_id='.CrmCustomerInfo::tableName().'.member_source')
            ->leftJoin(BsPubdata::tableName().' bp6','bp6.bsp_id='.CrmCustomerInfo::tableName().'.member_reqflag')
            ->leftJoin(BsCategory::tableName(),BsCategory::tableName().'.category_id='.CrmCustomerInfo::tableName().'.member_reqitemclass')
            ->leftJoin(BsDistrict::tableName().' bd1','bd1.district_id='.CrmCustomerInfo::tableName().'.cust_district_2')
            ->leftJoin(BsDistrict::tableName().' bd2','bd1.district_pid=bd2.district_id')
            ->leftJoin(BsDistrict::tableName().' bd3','bd2.district_pid=bd3.district_id')
            ->leftJoin(BsDistrict::tableName().' bd4','bd3.district_pid=bd4.district_id')
            ->where([CrmActiveApply::tableName().'.acth_id'=>$id])
            ->andWhere([CrmActiveApply::tableName().'.acth_status'=>CrmActiveApply::DEFAULT_STATUS])
            ->one();
        return $data;
    }

    //修改活动报名
    public function actionEdit($id)
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //客户信息表
                $customerInfoModel=CrmCustomerInfo::findOne($data['CrmCustomerInfo']['cust_id']);
                $customerInfoModel->cust_contacts=$data['CrmActiveApply']['acth_name'];
                $customerInfoModel->cust_position=$data['CrmActiveApply']['acth_position'];
                $customerInfoModel->cust_tel2=$data['CrmActiveApply']['acth_phone'];
                $customerInfoModel->cust_email=$data['CrmActiveApply']['acth_email'];
                if(!($customerInfoModel->load($data)&&$customerInfoModel->save())){
                    throw new \Exception(json_encode($customerInfoModel->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //活动报名表
                $activeApplyModel=CrmActiveApply::findOne($id);
                $activeApplyModel->cust_id=$customerInfoModel->cust_id;
                if(!($activeApplyModel->load($data)&&$activeApplyModel->save())){
                    $error=$activeApplyModel->getErrors();
                    if(!empty($error['cust_id'])){
                        throw new \Exception('该客户已报名！');
                    }
                    throw new \Exception(json_encode($activeApplyModel->getErrors(),JSON_UNESCAPED_UNICODE));

                }
                //客户状态表
                $customerStatusModel=CrmCustomerStatus::findOne($customerInfoModel->cust_id);
                if(empty($customerStatusModel)){
                    $customerStatusModel=new CrmCustomerStatus();
                    $customerStatusModel->customer_id=$customerInfoModel->cust_id;
                }
                $customerStatusModel->apply_status=CrmCustomerStatus::STATUS_DEFAULT;
                $customerStatusModel->potential_status=CrmCustomerStatus::STATUS_DEFAULT;
                if(!$customerStatusModel->save()){
                    throw new \Exception('客户状态表保存失败');
                }
                $transaction->commit();
                return $this->success($activeApplyModel->acth_code,$activeApplyModel->acth_id);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $editViewData=$this->getEditViewData($id);
        return [
            'addEditData'=>$this->getAddEditData(),
            'editData'=>$editViewData,
            'activeName'=>CrmActiveName::find()->select(['actbs_id','actbs_name'])->where(['acttype_id'=>$editViewData['acttype_id']])->andWhere(['actbs_status'=>[CrmActiveName::ADD_STATUS,CrmActiveName::ALREADY_START]])->orderBy(['actbs_id'=>SORT_DESC])->all(),
            'districtData'=>$this->getDistrict($editViewData['cust_district_2']),
        ];
    }

    //查看活动报名
    public function actionView($id)
    {
        $data=$this->getEditViewData($id);
        if(!empty($data)){
            if($data['acth_ismeal']=='0'){
                $data['acth_ismeal']='否';
            }elseif($data['acth_ismeal']=='1'){
                $data['acth_ismeal']='是';
            }else{
                $data['acth_ismeal']='';
            }
            if($data['acth_ispay']=='0'){
                $data['acth_ispay']='否';
            }elseif($data['acth_ispay']=='1'){
                $data['acth_ispay']='是';
            }else{
                $data['acth_ispay']='';
            }
            if($data['acth_isbill']=='0'){
                $data['acth_isbill']='否';
            }elseif($data['acth_isbill']=='1'){
                $data['acth_isbill']='是';
            }else{
                $data['acth_isbill']='';
            }
        }
        $model=BsPubdata::findOne($data['acttype_name']);
        $data['acttype_name']=$model->bsp_svalue;
        return $data;
    }

    //删除活动报名
    public function actionDeleteApply($arrId)
    {
        $arrId=explode('-',$arrId);
        foreach($arrId as $id){
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //活动报名表
                $activeApplyModel=CrmActiveApply::findOne($id);
                $activeApplyModel->acth_status=CrmActiveApply::DELETE_STATUS;
                if(!$activeApplyModel->save()){
                    throw new \Exception('活动报名表保存失败！');
                }
                //签到信息表
                $checkInModel=CrmActiveCheckIn::findAll(['acth_id'=>$id]);
                if(!empty($checkInModel)){
                    foreach($checkInModel as $val){
                        $val->actcin_status=CrmActiveCheckIn::DELETE_STATUS;
                        if(!$val->save()){
                            throw new \Exception('活动签到表保存失败！');
                        }
                    }
                }
                //缴费信息表
                $payModel=CrmActivePay::findOne(['acth_id'=>$id]);
                if(!empty($payModel)){
                    $payModel->actpaym_status=CrmActivePay::DELETE_STATUS;
                    if(!$payModel->save()){
                        throw new \Exception('活动缴费表保存失败！');
                    }
                }
                $transaction->commit();
            }catch(\Exception $e){
                $transaction->rollBack();
            }
        }
        return $this->success();
    }

    //签到
    public function actionCheckIn($id)
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //已签到
                if($data['CrmActiveCheckIn']['actcin_ischeckin']=='1'){
                    $checkInPerson=CrmActiveCheckIn::find()->where(['acth_id'=>$id])->count();
                    if(CrmActiveCheckIn::deleteAll('acth_id='.$id)!=$checkInPerson){
                        throw new \Exception('签到人删除出错！');
                    }
                    foreach($data['checkInArr'] as $val){
                        if(!empty($val['CrmActiveCheckIn']['actcin_name'])){
                            $activeCheckInModel=new CrmActiveCheckIn();
                            $activeCheckInModel->acth_id=$id;
                            $activeCheckInModel->actcin_ischeckin='1';
                            if(!($activeCheckInModel->load($val)&&$activeCheckInModel->save())){
                                throw new \Exception('客户签到表保存失败！');
                            }
                        }
                    }
                    $activeApplyModel=CrmActiveApply::findOne($id);
                    $activeApplyModel->acth_ischeckin='1';
                    if(!$activeApplyModel->save()){
                        throw new \Exception('客户报名表保存失败！');
                    }
                }
                //未签到
                if($data['CrmActiveCheckIn']['actcin_ischeckin']=='0'){
                    $activeCheckInModel=CrmActiveCheckIn::findOne(['acth_id'=>$id]);
                    if(empty($activeCheckInModel)){
                        $activeCheckInModel=new CrmActiveCheckIn();
                        $activeCheckInModel->acth_id=$id;
                    }
                    if(!($activeCheckInModel->load($data)&&$activeCheckInModel->save())){
                        throw new \Exception('客户签到表保存失败！');
                    }
                }
                $transaction->commit();
                return $this->success(empty($activeApplyModel)?'':$activeApplyModel['acth_code']);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return [
            'applyData'=>CrmActiveApply::findOne($id),
            'checkInData'=>CrmActiveCheckIn::findAll(['acth_id'=>$id]),
        ];
    }

    //缴费
    public function actionPay($id)
    {
        $activeApplyModel=CrmActiveApply::findOne($id);
        $activePayModel=CrmActivePay::findOne(['acth_id'=>$id]);
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //活动缴费表
                if(empty($activePayModel)){
                    $activePayModel=new CrmActivePay();
                    $activePayModel->acth_id=$id;
                }
                if(!($activePayModel->load($data)&&$activePayModel->save())){
                    throw new \Exception('活动缴费表保存失败！');
                }
                //活动报名表
                $activeApplyModel->acth_payflag='1';
                if(!$activeApplyModel->save()){
                    throw new \Exception('活动报名表保存失败！');
                }
                $transaction->commit();
                return $this->success($activeApplyModel->acth_code);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return [
            'applyData'=>$activeApplyModel,
            'payData'=>$activePayModel,
        ];
    }

    //导出
    public function actionExport()
    {
        $model=new CrmActiveApplySearch();
        $dataProvider=$model->searchApply(Yii::$app->request->queryParams);
        return $dataProvider->getModels();
    }

    //导入
    public function actionImport($companyId,$createBy){
        $post = \Yii::$app->request->post();
        $succ = 0;
        $err = 0;
        $log=[];
        foreach ($post as $k => $v) {
            if ($k >= 0) {
                $trans=Yii::$app->db->beginTransaction();
                try{
                    //活动类型表
                    if(empty($v['B'])||empty($v['C'])){
                        throw new \Exception('活动类型和活动方式不可为空！');
                    }
                    $activeTypeModel=CrmActiveType::findOne(['acttype_name'=>$v['B']]);
                    if(empty($activeTypeModel)){
                        $activeTypeModel=new CrmActiveType();
                        $activeTypeModel->acttype_name=$v['B'];
                        $activeTypeModel->company_id=$companyId;
                        $activeTypeModel->create_by=$createBy;
                        switch($v['C']){
                            case '线上':
                                $activeTypeModel->acttype_way='100206';
                                break;
                            case '线下':
                                $activeTypeModel->acttype_way='100207';
                                break;
                            default:
                                throw new \Exception('活动方式填写错误！');
                        }
                        if(!$activeTypeModel->save()){
//                            throw new \Exception('活动类型表保存失败！');
                            $errors=$activeTypeModel->getErrors();
                            $str='';
                            foreach($errors as $key=>$val){
                                $str.=$key.implode(',',$val);
                            }
                            throw new \Exception($str);
                        }
                    }
                    //活动名称表
                    if(empty($v['D'])){
                        throw new \Exception('活动名称不可为空！');
                    }
                    $pattern='/^[1-9]\d{3}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])\s+(20|21|22|23|[0-1]\d):[0-5]\d$/';
                    if((!preg_match($pattern,$v['E']))||(!preg_match($pattern,$v['F']))){
                        throw new \Exception('活动时间格式不正确！');
                    }
                    if((strtotime($v['F'])-strtotime($v['E']))<0){
                        throw new \Exception('结束时间要大于开始时间！');
                    }
                    $activeNameModel=CrmActiveName::findOne(['acttype_id'=>$activeTypeModel->acttype_id,'actbs_name'=>$v['D']]);
                    if(empty($activeNameModel)){
                        $activeNameModel=new CrmActiveName();
                        $activeNameModel->acttype_id=$activeTypeModel->acttype_id;
                        $activeNameModel->actbs_name=$v['D'];
                        $activeNameModel->actbs_start_time=$v['E'];
                        $activeNameModel->actbs_end_time=$v['F'];
                        $activeNameModel->company_id=$companyId;
                        $activeNameModel->create_by=$createBy;
                        if(!$activeNameModel->save()){
//                            throw new \Exception('活动名称表保存失败！');
                            $errors=$activeNameModel->getErrors();
                            throw new \Exception(json_encode($errors));
                        }
                    }
                    //客户表
                    if(empty($v['M'])){
                        throw new \Exception('公司名称不可为空！');
                    }
                    $customerModel=CrmCustomerInfo::findOne(['cust_sname'=>$v['M']]);
                    if(empty($customerModel)){
                        $customerModel=new CrmCustomerInfo();
                        $customerModel->company_id=$companyId;
                        $customerModel->create_by=$createBy;
                        $customerModel->cust_sname=$v['M'];
                        $customerModel->cust_adress=$v['N'].$v['O'].$v['P'];
                        $customerModel->cust_contacts=$v['G'];
                        $customerModel->cust_position=$v['H'];
                        $customerModel->cust_tel2=$v['K'];
                        $customerModel->cust_email=$v['L'];
                        if(!$customerModel->save()){
                            throw new \Exception('客户表保存失败！');
                        }
                    }
                    //活动报名表
                    $activeApplyModel=CrmActiveApply::findOne(['acth_status'=>CrmActiveApply::DEFAULT_STATUS,'acttype_id'=>$activeTypeModel->acttype_id,'actbs_id'=>$activeNameModel->actbs_id,'cust_id'=>$customerModel->cust_id]);
                    if(!empty($activeApplyModel)){
                        throw new \Exception('该客户已报名！');
                    }
                    $activeApplyModel=new CrmActiveApply();
                    $activeApplyModel->company_id=$companyId;
                    $activeApplyModel->create_by=$createBy;
                    $activeApplyModel->acttype_id=$activeTypeModel->acttype_id;
                    $activeApplyModel->actbs_id=$activeNameModel->actbs_id;
                    $activeApplyModel->cust_id=$customerModel->cust_id;
                    switch($v['I']){
                        case '工程技术':
                            $activeApplyModel->acth_department='1';
                            break;
                        case '生产管理':
                            $activeApplyModel->acth_department='2';
                            break;
                        case '采购':
                            $activeApplyModel->acth_department='3';
                            break;
                        default:
                            $activeApplyModel->acth_department='';
                    }
                    switch($v['J']){
                        case '讲师':
                            $activeApplyModel->acth_identity='100177';
                            break;
                        case '听众':
                            $activeApplyModel->acth_identity='100178';
                            break;
                        case '赞助商':
                            $activeApplyModel->acth_identity='100179';
                            break;
                        default:
                            $activeApplyModel->acth_identity='';
                    }
                    $activeApplyModel->acth_name=$v['G'];
                    $activeApplyModel->acth_position=$v['H'];
                    $activeApplyModel->acth_phone=$v['K'];
                    $activeApplyModel->acth_email=$v['L'];
                    if(!$activeApplyModel->save()){
                        throw new \Exception('活动报名表保存失败！');
                    }
                    //客户状态表
                    $customerStatusModel=CrmCustomerStatus::findOne($customerModel->cust_id);
                    if(empty($customerStatusModel)){
                        $customerStatusModel=new CrmCustomerStatus();
                        $customerStatusModel->customer_id=$customerModel->cust_id;
                    }
                    $customerStatusModel->apply_status=CrmCustomerStatus::STATUS_DEFAULT;
                    $customerStatusModel->potential_status=CrmCustomerStatus::STATUS_DEFAULT;
                    if(!$customerStatusModel->save()){
                        throw new \Exception('客户状态表保存失败');
                    }
                    $succ++;
                    $trans->commit();
                }catch (\Exception $e){
                    $log[]=[
                        'file'=>basename(get_class()).":".$e->getLine(),
                        'message'=>$e->getMessage()
                    ];
                    $err++;
                    $trans->rollBack();
                }
            }
        }
        return ["succ"=>$succ,"error"=>$err,"log"=>$log];
    }

    //发送邮件
    public function actionSendEmail()
    {
        $params=\Yii::$app->request->post();
        $trans=\Yii::$app->db->beginTransaction();
        $customers=isset($params["customers"])?explode(",",$params["customers"]):"";
        if($customers){
            try{
                foreach($customers as $customer){
                    $model=new CrmActImessage();
                    $model->load($params);
                    $model->cust_id=$customer;
                    $model->imesg_sentdate=date("Y-m-d H:i:s");
                    if(!($model->validate() && $model->save())){
                        throw new \Exception(Json::encode($model->getErrors()));
                    }
                }
                $trans->commit();
                return $this->success();
            }catch(\Exception $e){
                $trans->rollBack();
                return $this->error($e->getMessage());
            }
        }else{
            return $this->error("no");
        }
    }

    //地址联动
    public function actionGetDistrict($id)
    {
        return BsDistrict::getChildByParentId($id);
    }

    //修改时获取地区
    public function getDistrict($id)
    {
        if(empty($id)){
            return [];
        }
        $districtId=[];
        $districtName=[];
        while($id>0){
            $model=BsDistrict::findOne($id);
            $districtId[]=$model->district_id;
            $districtName[]=BsDistrict::find()->select('district_id,district_name')->where(['is_valid'=>'1','district_pid'=>$model->district_pid])->all();
            $id=$model->district_pid;
        }
        return [
            'districtId'=>array_reverse($districtId),
            'districtName'=>array_reverse($districtName),
        ];
    }
}
