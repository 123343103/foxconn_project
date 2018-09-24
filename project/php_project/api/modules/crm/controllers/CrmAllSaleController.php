<?php

namespace app\modules\crm\controllers;

use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsIndustrytype;
use app\modules\crm\models\CrmCustCustomer;
use app\modules\crm\models\CrmCustDevice;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmCustProduct;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\crm\models\search\CrmEmployeeSearch;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmCustPersoninchShow;
use app\modules\crm\models\show\CrmEmployeeShow;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\CrmDistrictSalearea;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;

class CrmAllSaleController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCustomerInfo';
    public function actionIndex()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchAllSale(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    /**
     * @return mixed
     * 導出excel
     */
    public function actionExcel()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $data = $searchModel->export(Yii::$app->request->queryParams)->all();
        return $data;
    }

    /*下拉菜单*/
    public function actionDownList()
    {
        $downList['customerType'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_TYPE);       //客户类型
        $downList['salearea'] = CrmSalearea::getSalearea();//营销区域
        $downList['province'] = BsDistrict::getDisProvince();//所在地区（省）
        $downList['personinch'] = [
            CrmCustomerInfo::PERSONINCH_NO=>'未认领',
            CrmCustomerInfo::PERSONINCH_YES=>'已认领',
        ];  //客户属性
//        $downList['customerClass'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_CLASS);     //客户类别
//        $downList['companyProperty'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);  //公司属性
//        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);  //经营类型
//        $downList['custLevel'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_LEVEL);  //客户等级
//        $downList['latentDemand'] = BsPubdata::getList(BsPubdata::CRM_LATENT_DEMAND);//潜在需求
//        $downList['companyScale'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_SCALE);//公司规模
//        $downList['tradeCurrency'] = BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY); //币别
//        $downList['customerSource'] = BsPubdata::getList(BsPubdata::CRM_CUSTOMER_SOURCE);//客户来源
//        $downList['productType'] = BsCategory::getLevelOne();//需求类目
//        $downList['industryType'] = BsIndustrytype::getIndustryType();//行业类别
//        $downList['storeinfo'] = CrmStoresinfo::getStoreInfo();//销售点
//        $downList['manager'] = CrmEmployee::getManagerInfo();//客户经理人
//        $downList['allsales'] = CrmEmployee::getAllSales();//所有销售人员
//        $downList['invoiceType'] = BsPubdata::getList(BsPubdata::CRM_INVOICE_TYPE);  //发票类型
        return $downList;
    }

    /*客户经理人*/
    public function actionGetVisitManager($code)
    {
        return HrStaff::getStaffByIdCode($code);
    }

    /*所在地区一级省份*/
    public function actionDistrict()
    {
        return BsDistrict::getDisProvince();
    }

    public function actionGetAllDistrict($id){
        $fourLevel=$this->getLevel($id);
        if(empty($fourLevel)){
            return $fourLevel;
        }
        $threeLevelId=$fourLevel->district_pid;
        $threeLevel=$this->getLevel($threeLevelId);
        $twoLevelId=$threeLevel->district_pid;
        $twoLevel=$this->getLevel($twoLevelId);
        $oneLevelId=$twoLevel->district_pid;
        $oneLevel=$this->getLevel($oneLevelId);
        $zeroLevelId=$oneLevel->district_pid;
        return [
            'oneLevel'=>BsDistrict::getChildByParentId($zeroLevelId),
            'oneLevelId'=>$oneLevelId,
            'twoLevel'=>BsDistrict::getChildByParentId($oneLevelId),
            'twoLevelId'=>$twoLevelId,
            'threeLevel'=>BsDistrict::getChildByParentId($twoLevelId),
            'threeLevelId'=>$threeLevelId,
            'fourLevel'=>BsDistrict::getChildByParentId($threeLevelId),
            'fourLevelId'=>$id,
        ];
    }

    /**
     * @param $id
     * 查询当前登录人信息
     */
    public function actionStaff($id){
        $model = HrStaff::findOne($id);
        return $model;
    }

    /**
     * @param $id
     * 是否显示全部销售角色
     */
    public function actionRoles($code,$isSuper){
        $employee = CrmEmployee::find()->select(['sarole_id','sale_area'])->where(['and',['staff_code'=>$code],['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT]])->one();
        $model = CrmSaleRoles::find()->where(['sarole_id'=>$employee['sarole_id']])->one();
        if($model['vdef1'] == 1 || $isSuper == '1'){
            $roles = CrmSaleRoles::find()->where(['=','sarole_status',CrmSaleRoles::STATUS_DEFAULT])->all();
            $saleareas = CrmSalearea::find()->where(['=','csarea_status',CrmSalearea::STATUS_DEFAULT])->all();
            $store = CrmStoresinfo::find()->where(['!=','sts_status',CrmStoresinfo::STATUS_DELETE])->all();

        }else{
            $roles[] = $model;
            $saleareas = CrmSalearea::find()->where(['=','csarea_status',CrmSalearea::STATUS_DEFAULT])->all();
            $salearea = CrmSalearea::find()->where(['and',['=','csarea_status',CrmSalearea::STATUS_DEFAULT],['csarea_id'=>$employee['sale_area']]])->one();
            $store = CrmStoresinfo::find()->where(['and',['!=','sts_status',CrmStoresinfo::STATUS_DELETE],['csarea_id'=>$salearea['csarea_id']]])->all();
        }
        $result['role'] = $roles;
        $result['salearea'] = $saleareas;
        $result['ss'] = !empty($salearea['csarea_id'])?$salearea['csarea_id']:'';
        $result['rr'] = $model;
        $result['store'] = $store;
        return $result;
    }

    /**
     * @param $code
     * @return array|\yii\db\ActiveRecord[]
     * 根据当前登录人查询下级人员
     */
    public function actionPerson($code,$isSuper){
        if($isSuper == '0'){
            $employee = CrmEmployee::find()->where(['and',['staff_code'=>$code],['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT]])->one();
            $model = CrmEmployeeShow::find()->where(['and',['leaderrole_id'=>$employee['sarole_id']],['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT]])->all();
            if(empty($model)){
                $model[]=CrmEmployeeShow::find()->where(['and',['staff_code'=>$code],['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT]])->one();
            }
        }else{
            $model = CrmEmployeeShow::find()->where(['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT])->all();
        }
        return $model;
    }
    /**
     * @param $id
     * @return mixed
     * 根据角色显示人员
     */
    public function actionGetRolePerson($id){
        $model = CrmEmployeeShow::find()->where(['and',['leaderrole_id'=>$id],['=','sale_status',CrmEmployee::SALE_STATUS_DEFAULT]])->all();
        return $model;
    }
    /**
     * @param $id
     * @return mixed
     * 根据人员显示营销区域及销售点
     */
    public function actionGetSaleStore($id){
        $hr = HrStaff::findOne($id);
        $model = CrmEmployee::find()->where(['staff_code'=>$hr['staff_code']])->one();
        return $model;
    }
}
