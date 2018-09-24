<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\hr\models\HrStaff;
use Yii;

class CrmAllCustomerController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCustomerInfo';

    public function actionIndex()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);
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
        $downList['property'] = [
            '潜在客户',
            '招商客户',
            '销售客户'
        ];
        $downList['cust_ismember'] = [
            '是',
            '否'
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

    public function actionGetAllDistrict($id)
    {
        $fourLevel = $this->getLevel($id);
        if (empty($fourLevel)) {
            return $fourLevel;
        }
        $threeLevelId = $fourLevel->district_pid;
        $threeLevel = $this->getLevel($threeLevelId);
        $twoLevelId = $threeLevel->district_pid;
        $twoLevel = $this->getLevel($twoLevelId);
        $oneLevelId = $twoLevel->district_pid;
        $oneLevel = $this->getLevel($oneLevelId);
        $zeroLevelId = $oneLevel->district_pid;
        return [
            'oneLevel' => BsDistrict::getChildByParentId($zeroLevelId),
            'oneLevelId' => $oneLevelId,
            'twoLevel' => BsDistrict::getChildByParentId($oneLevelId),
            'twoLevelId' => $twoLevelId,
            'threeLevel' => BsDistrict::getChildByParentId($twoLevelId),
            'threeLevelId' => $threeLevelId,
            'fourLevel' => BsDistrict::getChildByParentId($threeLevelId),
            'fourLevelId' => $id,
        ];
    }


//    //获取一条等级数据
//    public function getLevel($id)
//    {
//        return BsDistrict::findOne($id);
//    }
//
//    public function actionGetDistrictSalearea($id)
//    {
//        return CrmDistrictSalearea::getDisSalearea($id);
//    }
//
//
//    public function actionIndustryType()
//    {
//        return BsIndustrytype::getIndustryType();
//    }
//
//    /*获取客户信息*/
//    public function actionGetCustOne($id)
//    {
//        $result = CrmCustomerInfoShow::getOneInfo($id, 'cust_id,cust_sname,cust_adress');
//        return $result;
//    }
//
//    public function actionModels($id)
//    {
//        $result = CrmCustomerInfoShow::getCustomerInfoOne($id);
//        return $result;
//    }
//
//    public function actionSelectCustomer()
//    {
//        $searchModel = new CrmCustomerInfoSearch();
//        $dataProvider = $searchModel->searchSelect(Yii::$app->request->queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
//
//    }
//
//    protected function getModel($id)
//    {
//        if (($model = CrmCustomerInfo::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }
//
//    protected function getStatusModel($id)
//    {
//        if (($model = CrmCustomerStatus::findOne($id)) !== null) {
//            return $model;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }
//
//    /*移动端客户列表简化数据*/
//    public function actionList($companyId)
//    {
//        $searchModel = new CrmCustomerInfo();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $companyId);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
//    }
}
