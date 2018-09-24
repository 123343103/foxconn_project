<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/16
 * Time: 9:18
 */
namespace app\modules\crm\controllers;


use app\modules\common\models\BsCategory;
use app\modules\common\models\BsProduct;
use app\modules\crm\models\CrmC;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustCustomer;
use app\modules\crm\models\CrmCustDevice;
use app\modules\crm\models\CrmCustLinkcomp;
use app\modules\crm\models\CrmCustOddsitem;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmCustProduct;
use app\modules\crm\models\CrmCustPurchase;
use app\modules\crm\models\CrmModuleSet;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmCustomerApplySearch;
use app\modules\crm\models\search\CrmCustomerPersionSearch;
use app\modules\crm\models\search\CrmCustCustomerSearch;
use app\modules\crm\models\search\CrmCustLinkcompSearch;
use app\modules\crm\models\search\CrmCustOddsitemSearch;
use app\modules\crm\models\search\CrmCustPersoninchSearch;
use app\modules\crm\models\search\CrmCustProductSearch;
use app\modules\crm\models\search\CrmCustProjectsSearch;
use app\modules\crm\models\search\CrmCustPurchaseSearch;
use app\modules\crm\models\search\CrmSaleQuotedpriceChildSearch;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\crm\models\search\CrmVisitRecordSearch;
use app\modules\crm\models\search\SaleInterapplySearch;
use app\modules\crm\models\search\SaleOrderhSearch;
use app\modules\crm\models\search\SaleOrderlSearch;
use app\modules\crm\models\search\SaleTripapplySearch;
use app\modules\crm\models\show\CrmCustomerPersionShow;
use app\modules\crm\models\show\CrmCustCustomerShow;
use app\modules\crm\models\show\CrmCustDeviceShow;
use app\modules\crm\models\show\CrmCustLinkcompShow;
use app\modules\crm\models\show\CrmCustOddsitemShow;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmCustPersoninchShow;
use app\modules\crm\models\show\CrmCustProductShow;
use app\modules\crm\models\show\CrmCustPurchaseShow;
use app\modules\crm\models\search\PdRequirementProductSearch;
use app\modules\crm\models\show\CrmEmployeeShow;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SysDisplayList;
use app\modules\system\models\SysDisplayListChild;
use Yii;
use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\search\CrmCustomerInfoSearch;
use app\modules\crm\models\search\CrmVisitPlanSearch;
use app\modules\crm\models\search\CrmCustDeviceSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

class CrmCustomerManageController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCustomerInfo';

    /**
     * @return mixed
     * 客户管理首页
     */
    public function actionIndex()
    {
        $searchModel = new CrmCustomerInfoSearch();
        $dataProvider = $searchModel->searchManage(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param null $id
     * @param $companyId
     * @return mixed
     * 拜访计划列表
     */
    public function actionVisitPlan($id=""){
        if(!$id){
            return [
                "rows"=>[],
                "total"=>0
            ];
        }
        $searchModel = new CrmVisitPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();
        $models=ArrayHelper::toArray($models);
        foreach ($models as &$model){
            $model["cust_sname"]=isset($model["customerInfo"]["customerName"])?$model["customerInfo"]["customerName"]:"";
            $model["cust_type"]=isset($model["customerInfo"]["customerType"])?$model["customerInfo"]["customerType"]:"";
            $model["cust_manager"]=isset($model["customerInfo"]["customerManager"])?$model["customerInfo"]["customerManager"]:"";
            $model["cust_contacts"]=isset($model["customerInfo"]["customerContacts"])?$model["customerInfo"]["customerContacts"]:"";
            $model["cust_tel2"]=isset($model["customerInfo"]["customerTel2"])?$model["customerInfo"]["customerTel2"]:"";
            $model["cust_salearea"]=isset($model["customerInfo"]["customerSaleArea"])?$model["customerInfo"]["customerSaleArea"]:"";
            $model["cust_cmp_address"]=isset($model["customerInfo"]["customerDistrict"])?$model["customerInfo"]["customerDistrict"]:"";
            unset($model["customerInfo"]);
        }
        $list['rows'] = !empty($models) ? $models : [];
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $companyId
     * @param null $id
     * @return mixed
     * 拜访记录列表
     */
    public function actionVisitRecord($id=""){
        if(!$id){
            return [
                "rows"=>[],
                "total"=>0
            ];
        }
        $params=\Yii::$app->request->queryParams;
        $visitModel=CrmVisitRecord::find()
            ->with(["crmCustomer","customerType","customerManager.manager","salesArea"])
            ->where(["not",CrmVisitRecord::tableName().".sih_status"=>0])
            ->andWhere([CrmVisitRecord::tableName().".cust_id"=>$id])
            ->asArray()
            ->one();
        $visitChildModel=CrmVisitRecordChild::find()
            ->select([
                CrmVisitRecordChild::tableName().".*",
                "s1.staff_name visitPerson",
                "s2.staff_name create_by",
                "plan.svp_code related_plan",
                "v.bsp_svalue visit_type"
            ])
            ->joinWith(["staff"=>function($query){
                return $query->alias("s1");
            },"createPerson"=>function($query){
                return $query->alias("s2");
            },'visitPlan'=>function($query){
                return $query->alias("plan");
            },'visitType'=>function($query){
                return $query->alias("v");
            }])
            ->where([
                "sih_id"=>$visitModel["sih_id"],
                "sil_status"=>10
            ])
            ->orderBy("create_at desc")
            ->asArray();
        $dataProvider=new ActiveDataProvider([
            "query"=>$visitChildModel,
            "pagination"=>[
                "page"=>$params["page"]-1,
                "pageSize"=>$params["rows"]
            ]
        ]);
        $list["rows"]=$dataProvider->getModels();
        foreach($list["rows"] as &$row){
            $row["cust_sname"]=isset($visitModel["crmCustomer"]["cust_sname"])?$visitModel["crmCustomer"]["cust_sname"]:"";
            $row["cust_type"]=isset($visitModel["customerType"]["bsp_svalue"])?$visitModel["customerType"]["bsp_svalue"]:"";
            $row["cust_manager"]=isset($visitModel["customerManager"]["manager"]["staff_name"])?$visitModel["customerManager"]["manager"]["staff_name"]:"";
            $row["cust_salearea"]=isset($visitModel["salesArea"]["csarea_name"])?$visitModel["salesArea"]["csarea_name"]:"";
            $row["cust_contacts"]=isset($visitModel["crmCustomer"]["cust_contacts"])?$visitModel["crmCustomer"]["cust_contacts"]:"";
            $row["cust_tel2"]=isset($visitModel["crmCustomer"]["cust_tel2"])?$visitModel["crmCustomer"]["cust_tel2"]:"";
        }
        $list["total"]=$dataProvider->totalCount;
        return $list;
    }

    /**
     * 最近交易订单
     * 未有订单数据就返回null。2017-11-01
     */
    public function actionLastSaleOrder($id=""){
//        if(!$id){
//            return [
//                "rows"=>[],
//                "total"=>0
//            ];
//        }
//        $searchModel = new SaleOrderhSearch();
//        $dataProvider = $searchModel->searchLast(Yii::$app->request->queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = !empty($model) ? $model : [];
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
        return [
            "rows"=>[],
            "total"=>0
        ];
    }

    /**
     * @return mixed
     * 我的申请
     */
    public function actionVerify($id="")
    {
        if(!$id){
            return [
                "rows"=>[],
                "total"=>0
            ];
        }
        $searchModel = new CrmCustomerApplySearch();
        $dataProvider = $searchModel->searchApply(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();
        $models=ArrayHelper::toArray($models);
        $models=array_map(function($model){
            $model["businessType"]="客户代码申请";
            return $model;
        },$models);
        $list['rows'] = !empty($models) ? $models : [];
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 报价信息表
     * 暂时没有报价信息，返回为空2017-11-01
     */
    public function actionLastSaleQuotedprice(){

//        $searchModel = new CrmSaleQuotedpriceChildSearch();
//        $dataProvider = $searchModel->searchLast(Yii::$app->request->queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = !empty($model) ? $model : [];
        $list['rows']=[];
//        $list["total"] = $dataProvider->totalCount;
        $list['total']=0;
        return $list;
    }

    public function actionResultList($id){
//        $resultList['index'] = $this->actionIndex($companyId,$managerId);
        $resultList['contactPerson'] = $this->actionContactPerson($id);
        $resultList['custDevice'] = $this->actionCustDevice($id);
        $resultList['mainProduct'] = $this->actionCustMainProduct($id);
        $resultList['custOddsitrm'] = $this->actionCheckCustOddsitem($id);
        $resultList['linkComp'] = $this->actionCustLinkComp($id);
        $resultList['custPersonInch'] = $this->actionCustPersonInch($id);
        $resultList['custPurchase'] = $this->actionCustPurchase($id);
        $resultList['mainCustomer'] = $this->actionCustMainCustomer($id);
        $resultList['saleOrder'] = $this->actionSaleOrder($id);
        $resultList['cooperationProduct'] = $this->actionCooperationProduct($id);
        $resultList['projectFollow'] = $this->actionProjectFollow($id);
        $resultList['quotedPrice'] = $this->actionSaleQuotedprice($id);
        $resultList['costInfo'] = $this->actionCostInfo($id);
        $resultList['crd'] = $this->actionRequirementProduct($id);
        return $resultList;
    }

    public function actionIndexList(){
        $indexList['lastSaleOrder'] = $this->actionLastSaleOrder();
        $indexList['lastQuotedPrice'] = $this->actionLastSaleQuotedprice();
        $indexList['visitPlan'] = $this->actionVisitPlan();
        $indexList['visitRecord'] = $this->actionVisitRecord();
        return $indexList;
    }


    /**
     * @param $id
     * @return mixed
     * 联系人列表
     */
    public function actionContactPerson($id){
        $searchModel = new CrmCustomerPersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 设备列表
     */
    public function actionCustDevice($id){
        $searchModel = new CrmCustDeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 主要产品列表
     */
    public function actionCustMainProduct($id){
        $searchModel = new CrmCustProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     *商机商品列表
     */
    public function actionCheckCustOddsitem($id){
        $searchModel = new CrmCustOddsitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     *关联公司
     */
    public function actionCustLinkComp($id){
        $searchModel = new CrmCustLinkcompSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $models = ArrayHelper::toArray($dataProvider->getModels());
        foreach($models as &$model){
            foreach($model as &$item){
                is_null($item) && $item="";
            }
            $model["total_investment"]=="" || $model["total_investment"]=substr($model["total_investment"],0,strpos($model["total_investment"],"."));
            $model["shareholding_ratio"]=="" || $model["shareholding_ratio"]=round($model["shareholding_ratio"],2);
        }
        $list['rows'] = $models;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     *认领信息
     */
    public function actionCustPersonInch($id){
        $searchModel = new CrmCustPersoninchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 采购列表
     */
    public function actionCustPurchase($id){
        $searchModel = new CrmCustPurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $models =ArrayHelper::toArray($dataProvider->getModels());
        foreach($models as &$model){
            foreach($model as &$item){
                is_null($item) && $item="";
            }
            $model["pruchasecost"] && $model["pruchasecost"]=substr($model["pruchasecost"],0,strpos($model["pruchasecost"],"."));
        }
        $list['rows'] = !empty($models) ? $models : [];
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * CRD/PRD列表
     * 暂时没有数据，返回null。2017-11-01
     */
    public function actionRequirementProduct($id=null){
//        if(!$id){
//            return [
//                "rows"=>[],
//                "total"=>0
//            ];
//        }
//        $searchModel = new PdRequirementProductSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
//        $model = $dataProvider->getModels();
//        $list['rows'] = !empty($model) ? $model : [];
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
        return [
            "rows"=>[],
            "total"=>0
        ];
    }

    /**
     * @param $id
     * @return mixed
     * 主要客户列表
     */
    public function actionCustMainCustomer($id){
        $searchModel = new CrmCustCustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = !empty($model) ? $model : [];
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    /**
     * @param $id
     * @return mixed
     * 订单信息表
     */
    public function actionSaleOrder($id){
//        $searchModel = new SaleOrderhSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
//        $model = $dataProvider->getModels();
//        $list['rows'] = !empty($model) ? $model : [];
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
        $list['rows'] = [];
        $list["total"] = 0;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 费用信息表
     */
    public function actionCostInfo($id){
        $searchModel = new SaleInterapplySearch();
        $dataProvider = $searchModel->search($id);
        $Tmodel = new SaleTripapplySearch();
        $data = $Tmodel->search($id);
        $model = $dataProvider->getModels();
        $Stmodel = $data->getModels();
        $list['rows']=$model;
        $list['total']=count($model);
        return $list;
//        $i=1;
//        if(empty($model)&&empty($Stmodel)){
//            $e=[];
//            $list['rows']=$e;
//            $list['total']=count($e);
//            return $list;
//        }else if(empty($model)&&!empty($Stmodel)){
//            foreach ($Stmodel as $key => $val){
//                $listAddress1[] = $val->district4->district_name.$val->district3->district_name.$val->district2->district_name.$val->district->district_name.$val->stah_place;
//                $listDate1[] = $val['stah_date'];
//                $listType1[] = $val->scost['scost_sname'];
//                $listCost1[] = $val['stah_costcount'];
//            }
//            $a = array_merge($listAddress1);
//            $b = array_merge($listDate1);
//            $c = array_merge($listType1);
//            $d = array_merge($listCost1);
//            foreach ($a as $key => $val){
//                $e[$key]['id']=$i;
//                $e[$key]['address']=$a[$key];
//                $e[$key]['date']=$b[$key];
//                $e[$key]['type']=$c[$key];
//                $e[$key]['cost']=$d[$key];
//                $i++;
//            }
//            $list['rows']=$e;
//            $list['total']=count($e);
//            return $list;
//        }else if(!empty($model)&&empty($Stmodel)){
//            foreach ($model as $key => $val){
//                $listAddress[] = $val->district4->district_name.$val->district3->district_name.$val->district2->district_name.$val->district->district_name.$val->siah_address;
//                $listDate[] = $val->siah_appdate;
//                $listType[] = $val->scost->scost_sname;
//                $listCost[] = $val->siah_cost;
//                $listShape[] = '';
//                $count[]=[$val->stah_partner1,$val->stah_partner2,$val->stah_partner3];
//                $listCount[] = count($count);
//            }
//            $a = array_merge($listAddress);
//            $b = array_merge($listDate);
//            $c = array_merge($listType);
//            $d = array_merge($listCost);
//            $f = array_merge($listShape);
//            $g = array_merge($listCount);
//            foreach ($a as $key => $val){
//                $e[$key]['id']=$i;
//                $e[$key]['address']=$a[$key];
//                $e[$key]['date']=$b[$key];
//                $e[$key]['type']=$c[$key];
//                $e[$key]['cost']=$d[$key];
//                $e[$key]['shape']=$f[$key];
//                $e[$key]['count']=$g[$key];
//                $i++;
//            }
//            $list['rows']=$e;
//            $list['total']=count($e);
//            return $list;
//        }else{
//            foreach ($model as $key => $val){
//                $listAddress[] = $val->district4->district_name.$val->district3->district_name.$val->district2->district_name.$val->district->district_name.$val->siah_address;
//                $listDate[] = $val->siah_appdate;
//                $listType[] = $val->scost->scost_sname;
//                $listCost[] = $val->siah_cost;
//                $listShape[] = $val->siah_shape;
//                $count[]=[$val->stah_partner1,$val->stah_partner2,$val->stah_partner3];
//                $listCount[] = count($count);
//            }
//            foreach ($Stmodel as $key => $val){
//                $listAddress1[] = $val->district4->district_name.$val->district3->district_name.$val->district2->district_name.$val->district->district_name.$val->stah_place;
//                $listDate1[] = $val->stah_date;
//                $listType1[] = $val->scost->scost_sname;
//                $listCost1[] = $val->stah_costcount;
//            }
//            $a = array_merge($listAddress,$listAddress1);
//            $b = array_merge($listDate,$listDate1);
//            $c = array_merge($listType,$listType1);
//            $d = array_merge($listCost,$listCost1);
//            $i=1;
//            foreach ($a as $key => $val){
//                $e[$key]['id']=$i;
//                $e[$key]['address']=$a[$key];
//                $e[$key]['date']=$b[$key];
//                $e[$key]['type']=$c[$key];
//                $e[$key]['cost']=$d[$key];
//                $i++;
//            }
//            $list['rows']=$e;
//            $list['total']=count($e);
//            return $list;
//        }
    }

    /**
     * 合作商品表
     */
    public function actionCooperationProduct($id){
//        $searchModel = new SaleOrderlSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
//        $model = $dataProvider->getModels();
//        $list['rows'] = !empty($model) ? $model : [];
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
        $list['rows'] = [];
        $list['total'] = 0;
        return $list;
    }

    /**
     * 项目跟进
     */
    public function actionProjectFollow($id){
        $searchModel = new CrmCustProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = !empty($model) ? $model : [];
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return mixed
     * 报价信息表
     */
    public function actionSaleQuotedprice($id){
        $searchModel = new CrmSaleQuotedpriceChildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);
        $model = $dataProvider->getModels();
        $list['rows'] = !empty($model) ? $model : [];
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    /**
     * @param $id
     * @return array
     * 客户基本信息更新
     */
    public function actionUpdateCustomer($id){
        $transaction = Yii::$app->db->beginTransaction();
        $crmCustomerInfo = $this->getModel($id);
        $post = Yii::$app->request->post();
        try{
            $crmCustomerInfo->load($post);
            if(!$crmCustomerInfo->save()){
                throw new \Exception("修改客户信息失败");
            }

            /*认领信息*/
            $managerId = isset($post['CrmCustomerInfo']['cust_manager'])?$post['CrmCustomerInfo']['cust_manager']:"";
            $ismember = isset($post['CrmCustomerInfo']['cust_ismember'])?$post['CrmCustomerInfo']['cust_ismember']:"";
            $status = CrmCustomerStatus::find()->where(['customer_id' => $id])->one();
            if ($ismember == '1') {
                $status->member_status = CrmCustomerStatus::STATUS_DEFAULT;
            } else {
                $status->member_status = CrmCustomerStatus::STATUS_DEL;
            }
            if (!$status->save()) {
                throw new \Exception("修改失败");
            }
            $personinch = CrmCustPersoninch::find()->where(['cust_id' => $id])->one();
            if ($personinch == null) {
                if ($managerId != null) {
                    $personinch = new CrmCustPersoninch();
                    $a = CrmEmployeeShow::find()->where(['staff_code' => isset($post['code'])?$post['code']:""])->one();
                    $personinch->ccpich_personid = $managerId;
                    $personinch->csarea_id = $a['sale_area'];
                    $personinch->sts_id = $a['sts_id'];
                    $personinch->cust_id = $id;
                    $personinch->ccpich_date = date('Y-m-d', time());
                    $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                    $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                    if (!$personinch->save()) {
                        throw new \Exception("新增认领信息失败");
                    }
                }
            } else {
                $arr = explode(',',$managerId);
                foreach ($arr as $key => $val) {
                    $code = HrStaff::find()->where(['staff_id' => $val])->one();
                    $hr = HrStaff::findOne($val);
                    $a = CrmEmployeeShow::find()->with(["area", "storeInfo"])->where(['staff_code' => $hr['staff_code']])->asArray()->one();
                    $person = CrmCustPersoninch::find()->where(['and', ['cust_id' => $id], ['ccpich_personid' => $val]])->one();
                    if (empty($person)) {
                        $personinch = new CrmCustPersoninch();
                    } else {
                        $personinch = $person;
                    }
                    $personinch->ccpich_personid = $val;
                    $personinch->ccpich_personid2 = $val;
                    $personinch->csarea_id = isset($a['area']['csarea_id']) ? $a['area']['csarea_id'] : "";
                    $personinch->sts_id = isset($a['storeInfo']['sts_id']) ? $a['storeInfo']['sts_id'] : "";
                    $personinch->cust_id = $id;
                    $personinch->ccpich_date = date('Y-m-d', time());
                    $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                    $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                    if (!$personinch->save()) {
                        throw new \Exception("修改认领信息失败");
                    }
                }
//                $a = CrmEmployeeShow::find()->where(['staff_code' => isset($post['code'])?$post['code']:""])->one();
//                $personinch->ccpich_personid = $managerId;
//                $personinch->csarea_id = $a['sale_area'];
//                $personinch->sts_id = $a['sts_id'];
//                $personinch->cust_id = $id;
//                $personinch->ccpich_date = date('Y-m-d', time());
//                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
//                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
//                if (!$personinch->save()) {
//                    throw new \Exception("修改认领信息失败");
//                }
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"');
    }


    /**
     * @param $id
     * @return array
     * 客户认证信息更新
     */
    public function actionAuthInfo($id){
        /*认证信息*/
        $trans=\Yii::$app->db->beginTransaction();
        $certfArr = isset($post['CrmC']) ? $post['CrmC'] : false;
        $post=\Yii::$app->request->post();
        try{
            $customer=CrmCustomerInfo::findOne($id);
            $customer->load($post);
            if(!($customer->validate() && $customer->save())){
                throw new \Exception("客户信息保存失败");
            }
            $crmC = CrmC::findOne(["cust_id"=>$id]);
            if(!$crmC){
                $crmC=new CrmC();
            }
            $crmC->load($post);
            if($post['CrmC']['crtf_type'] == 0){
                $post['CrmC']['o_license'] = $post['CrmC']['o_license'];
            }else{
                $post['CrmC']['o_license'] = $post['CrmC']['o_license_new'];
            }
            $crmC->cust_id = $customer->primaryKey;
            $crmC->o_license =$post['CrmC']['o_license'];
            if (!$crmC->save()) {
                throw  new \Exception("客户认证信息修改失败");
            }
            $trans->commit();
            return $this->success("客户认证信息修改成功");
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }


    /**
     * @param $id
     * @return array
     * 客户公司信息更新
     */
    public function actionCustomerCompany($id){
        $transaction = Yii::$app->db->beginTransaction();
        $crmCustomerInfo = $this->getModel($id);
        $post = Yii::$app->request->post();
        try{
            $crmCustomerInfo->load($post);
            $custRegname = $post['CrmCustomerInfo']['cust_regname'];
            $custRegnumber = $post['CrmCustomerInfo']['cust_regnumber'];
            $crmCustomerInfo->cust_regname = serialize($custRegname);
            $crmCustomerInfo->cust_regnumber = serialize($custRegnumber);
            if(!$crmCustomerInfo->save()){
                throw new \Exception(json_encode($crmCustomerInfo->getErrors(),JSON_UNESCAPED_UNICODE));
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"');
    }

    /**
     * @param $id
     * @return array
     * 新增联系人信息
     */
    public function actionContactCreate($id)
    {
        $model = new CrmCustomerPersion();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load($post);
            $model->cust_id = $id;
            if(!$model->save()){
                throw new \Exception("新增联系人失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"联系人');
    }

    /**
     * @param $id
     * @return array
     * 联系人信息更新
     */
    public function actionContactUpdate($id){
        $transaction = Yii::$app->db->beginTransaction();
        $contact = CrmCustomerPersion::find()->where(['ccper_id'=>$id])->one();
        $post = Yii::$app->request->post();
        try{
            $contact->load($post);
            if(!$contact->save()){
                throw new \Exception("修改联系人失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($contact['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"联系人');
    }

    /**
     * @param $id
     * @return array
     * 联系人删除
     */
    public function actionDeletePerson($id){
        $model = CrmCustomerPersion::findOne($id);
        $model->ccper_status = CrmCustomerPersion::STATUS_DELETE;
        $result=$model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除联系人"' . $model['ccper_name'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }
    /**
     * @return array
     * 新增拜访计划
     */
    public function actionCreateVisit($id)
    {
        $model=new CrmVisitPlan();
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"拜访计划');
    }

    /**
     * 更新拜访计划
     */
    public function actionEditPlan($id){
        $model=CrmVisitPlan::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"拜访计划');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 新增拜访记录
     */
    //新增拜访记录
    public function actionCreateInfo($id)
    {
        $child = new CrmVisitRecordChild();
        $post=Yii::$app->request->post();
        $model=CrmVisitRecord::find()->where(['cust_id'=>$id])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $model=!empty($model) ? $model : new CrmVisitRecord();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load($post);
            $model->save();
            $sihId=$model->sih_id;
            $child->load($post);
            $child->sih_id=$sihId;
            $child->save();
            $transaction->commit();
            $crmCustomerInfo = $this->getModel($id);
            return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"拜访记录');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 更新拜访记录
     */
    public function actionEditInfo($id){
        $child=CrmVisitRecordChild::find()->where(['sil_id'=>$id])->andWhere(['<>','sil_status',CrmVisitRecordChild::STATUS_DELETE])->one();
        $model=CrmVisitRecord::find()->where(['sih_id'=>$child->sih_id])->andWhere(['<>','sih_status',CrmVisitRecord::STATUS_DELETE])->one();
        $model=!empty($model)?$model:new CrmVisitRecord();
        $post=Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load($post);
            $model->save();
            $sihId=$model->sih_id;
            $child->load($post);
            $child->sih_id=$sihId;
            $child->save();
            $transaction->commit();
            $crmCustomerInfo = $this->getModel($model->cust_id);
            return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"拜访记录');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 新增设备
     */
    public function actionCreateDevice($id)
    {
        $model = new CrmCustDevice();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load($post);
            $model->cust_id = $id;
            if(!$model->save()){
                throw new \Exception("新增设备失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"设备信息');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 修改设备信息
     */
    public function actionUpdateDevice($id){
        $model=CrmCustDevice::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"设备信息');
    }

    /**
     * @param $id
     * @return array
     * 设备删除
     */
    public function actionDeleteDevice($id){
        $model = CrmCustDevice::findOne($id);
        $model->status = CrmCustDevice::STATUS_DELETE;
        $model->type=Html::decode($model->type);
        $model->brand=Html::decode($model->brand);
        $result=$model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除设备"' . $model['brand'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 新增主营产品
     */
    public function actionCreateMainProduct($id)
    {
        $model = new CrmCustProduct();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load($post);
            $model->cust_id = $id;
            if(!$model->save()){
                throw new \Exception("新增主营产品失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"主营产品');
    }


    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 修改主营产品信息
     */
    public function actionUpdateMainProduct($id){
        $model=CrmCustProduct::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"主要产品');
    }

    /**
     * @param $id
     * @return array
     * 设备删除
     */
    public function actionDeleteProduct($id){
        $model = CrmCustProduct::findOne($id);
        $model->status = CrmCustProduct::STATUS_DELETE;
        $result=$model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除主营商品"' . $model['ccp_sname'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 新增主要客户
     */
    public function actionCreateMainCustomer($id)
    {
        $model = new CrmCustCustomer();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load($post);
            $model->cust_id = $id;
            if(!$model->save()){
                throw new \Exception("新增主要客户失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"的主要客户');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 修改主要客户
     */
    public function actionUpdateMainCustomer($id){
        $model=CrmCustCustomer::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"的主要客户');
    }

    /**
     * @param $id
     * @return array
     * 删除主要客户
     */
    public function actionDeleteCustomer($id){
        $model =CrmCustCustomer::findOne($id);
        $model->status = CrmCustCustomer::STATUS_DELETE;
        $result = $model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除主要客户"' . $model['cc_customer_name'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }

    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 新增商机商品
     */
    public function actionCreateCustOddsitem($id)
    {
        $model = new CrmCustOddsitem();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load($post);
            $model->cust_id = $id;
//            return ActiveForm::validate($model);
            if(!$model->save()){
                throw new \Exception("新增商机商品失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"商机商品');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 修改商机商品
     */
    public function actionUpdateCustOddsitem($id){
        $model=CrmCustOddsitem::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"商机商品');
    }

    /**
     * @param $id
     * @return array
     * 商机商品删除
     */
    public function actionDeleteBusiness($id){
        $model = CrmCustOddsitem::findOne($id);
        $model->status = CrmCustOddsitem::STATUS_DELETE;
        $result=$model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除商机商品"' . $model['odds_sname'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 新增关联公司
     */
    public function actionCreateLinkComp($id)
    {
        $model = new CrmCustLinkcomp();
        $post=Yii::$app->request->post();
        $model->load($post);
        $model->cust_id = $id;
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"关联公司');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 修改关联公司
     */
    public function actionUpdateLinkComp($id){
        $model=CrmCustLinkcomp::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"关联公司');
    }

    public function actionDeleteLinkcomp($id){
        $model = CrmCustLinkcomp::findOne($id);
        $model->linc_status = CrmCustLinkcomp::STATUS_DELETE;
        $result=$model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除关联公司"' . $model['linc_name'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array
     * 新增采购信息
     */
    public function actionCreateCustPurchase($id)
    {
        $model = new CrmCustPurchase();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $model->load($post);
            $model->cust_id = $id;
            if(!$model->save()){
                throw new \Exception("新增采购信息失败");
            }
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $crmCustomerInfo = $this->getModel($id);
        return $this->success('','客户详情新增客户"'. $crmCustomerInfo['cust_sname'].'"采购信息');
    }
    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 修改采购信息
     */
    public function actionUpdateCustPurchase($id){
        $model=CrmCustPurchase::findOne($id);
        $post=Yii::$app->request->post();
        $model->load($post);
        $result=$model->save();
        if(!$result){
            return $this->error();
        }
        $crmCustomerInfo = $this->getModel($model['cust_id']);
        return $this->success('','客户详情修改客户"'. $crmCustomerInfo['cust_sname'].'"采购信息');
    }

    /**
     * @param $id
     * @return array
     * 采购删除
     */
    public function actionDeletePurchase($id){
        $model = CrmCustPurchase::findOne($id);
        $model->status = CrmCustPurchase::STATUS_DELETE;
        $result=$model->save();
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除采购"' . $model['itemname'] . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    /**
     * 修改认领信息
     */
    public function actionUpdatePersonInch($id,$status){
        if($status == '0'){
            $personinch = new CrmCustPersoninch();
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $crmCustomerInfo = $this->getModel($id);
                $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_YES;
                $personinch->ccpich_stype = CrmCustPersoninch::PERSONINCH_SALES;
                if (!$crmCustomerInfo->save()) {
                    throw new \Exception("新增认领信息失败");
                }
                $personinch->load(Yii::$app->request->post());
                $personinch->cust_id = $id;
                $personinch->ccpich_status = CrmCustPersoninch::STATUS_DEFAULT;
                if (!$personinch->save()) {
                    throw new \Exception("新增认领信息失败");
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            $transaction->commit();
            return $this->success('','新增客户"'. $crmCustomerInfo['cust_sname'] .'"认领信息');
        }else if($status == '10'){
            $personinch = CrmCustPersoninch::findOne($id);
            $crmCustomerInfo = $this->getModel($personinch['cust_id']);
            $personinch->load(Yii::$app->request->post());
            if ($personinch->save(false)) {
                return $this->success('','修改客户"'. $crmCustomerInfo['cust_sname'] .'"认领信息');
            } else {
                return $this->error("更新认领信息失败");
            }
        }
    }
    /*取消认领*/
    public function actionCanclePersonInch($id){
        $personinch = CrmCustPersoninch::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $crmCustomerInfo = $this->getModel($personinch['cust_id']);
            $crmCustomerInfo->personinch_status = CrmCustomerInfo::PERSONINCH_NO;
            if (!$crmCustomerInfo->save()) {
                throw new \Exception("取消认领信息失败");
            }
            $personinch->delete();
            $transaction->commit();
            return $this->success('','客户详情取消客户"'. $crmCustomerInfo['cust_sname'].'"认领信息');
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
    /*更新表格列*/
    public function actionUpdateSysList($id){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            $arr1 = $child=SysDisplayListChild::find()->select(['field_id'])->where(['ddi_sid'=>$id])->all();
            if(!empty($post['my-select'])){
                foreach($post['my-select'] as $val){
                    $child=SysDisplayListChild::find()->select(['field_id'])->where(['field_id'=> $val])->one();
                    $child->field_display= SysDisplayListChild::STATUS_DELETE;
                    if (!$child->save()) {
                        throw new \Exception("发生错误");
                    }
                    $arr2[] = $child;
                }
                foreach($arr1 as $key => $val){
                    foreach ($arr2 as $k => $v){
                        if($val['field_id'] == $v['field_id']){
                            unset($arr1[$key]);
                        }
                    }
                }
            }
            foreach ($arr1 as $s => $n){
                $m = SysDisplayListChild::find()->select(['field_id'])->where(['field_id'=> $n])->one();
                $m->field_display= SysDisplayListChild::STATUS_DEFAULT;
                if (!$m->save()) {
                    throw new \Exception("发生错误");
                }
            }
            $transaction->commit();
            return $this->success();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * 获取表格列
     */
    public function actionSysList($id){
        $result = SysDisplayListChild::find()->where(['ddi_sid'=>$id])->asArray()->all();
        return $result;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * 获取隐藏列
     */
    public function actionSysSelectList($id){
        $result = SysDisplayListChild::find()->where(['and',['ddi_sid'=>$id],['=','field_display',SysDisplayListChild::STATUS_DELETE]])->asArray()->all();
        return $result;
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     * 获取隐藏模块
     */
    public function actionGetModuleShow($id){
        $result = CrmModuleSet::find()->where(['and',['uid'=>$id],['=','display',CrmModuleSet::DISPLAY_SHOW]])->asArray()->all();
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * 修改模块显示
     */
    public function actionModule($id){
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $post = Yii::$app->request->post();
            CrmModuleSet::deleteAll(['uid'=>$id]);
            if(isset($post['crm']) && count($post['crm'])>0){
                foreach($post['crm'] as $k => $v){
                    $model = new CrmModuleSet();
                    $model->uid = $id;
                    $model->module = $v;
                    $model->display= CrmModuleSet::DISPLAY_SHOW;
                    if (!$model->save()) {
                        throw new \Exception("发生错误");
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        }catch (\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionModuleDelete($id,$name){
        $model = CrmModuleSet::find()->where(['and',['uid'=>$id],['module'=>$name]])->one();
        if(!empty($model)){
            $model->display = CrmModuleSet::DISPLAY_NONE;
        }else{
            $model = new CrmModuleSet();
            $model->display = CrmModuleSet::DISPLAY_NONE;
            $model->uid = $id;
            $model->module = $name;
        }
        if ($result = $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条主营产品信息
     */
    public function actionGetMainProductOne($id){
        $result = CrmCustProductShow::find()->where(['ccp_id'=>$id])->one();
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条主要客户信息
     */
    public function actionGetMainCustomerOne($id){
        $result = CrmCustCustomerShow::find()->where(['cc_customerid'=>$id])->one();
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条商机商品信息
     */
    public function actionGetCustOddsitemOne($id){
        $result = CrmCustOddsitemShow::find()->where(['odds_id'=>$id])->one();
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条联系人信息
     */
    public function actionContactEdit($id){
        $result = CrmCustomerPersionShow::find()->where(['ccper_id'=>$id])->one();
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条设备信息
     */
    public function actionGetCustDeviceOne($id){
        $result = CrmCustDeviceShow::find()->where(['custd_id'=>$id])->one();
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条关联公司信息
     */
    public function actionGetLinkCompOne($id){
        $result = CrmCustLinkcompShow::find()->where(['linc_id'=>$id])->asArray()->one();
        $result["total_investment"]=="" || $result["total_investment"]=substr($result["total_investment"],0,strpos($result["total_investment"],"."));
        $result["shareholding_ratio"]=="" || $result["shareholding_ratio"]=round($result["shareholding_ratio"],2);
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条认领信息
     */
    public function actionGetPersonInchOne($id){
        $result = CrmCustPersoninchShow::find()->where(['ccpich_id'=>$id])->one();
        return $result;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取一条采购信息
     */
    public function actionGetCustPurchaseOne($id){
        $result = CrmCustPurchaseShow::find()->where(['cpurch_id'=>$id])->asArray()->one();
        $result["pruchasecost"]=substr($result["pruchasecost"],0,strpos($result["pruchasecost"],"."));
        return $result;
    }
    /**
     * 获取分级分类信息
     */
    public function actionFirmCategory(){
        return BsCategory::getLevelOne();
    }


    /**
     * @return array|\yii\db\ActiveRecord[]
     * 关联商品信息表
     */
    public function actionGetBsProduct(){
        $result = BsProduct::getProductInfoOne();
        return $result;
    }

    public function actionModels($id){
        $result = CrmCustomerInfoShow::getCustomerInfoOne($id);
        return $result;
    }

    protected function getModel($id)
    {
        if (($model = CrmCustomerInfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}