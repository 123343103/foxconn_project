<?php
/**
 * User: F1677929
 * Date: 2016/12/10
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\ptdt\models\PdFirmEvaluateApply;
use app\modules\ptdt\models\PdProductType;
use app\modules\ptdt\models\search\PdFirmEvaluateApplySearch;
use app\modules\ptdt\models\show\PdFirmShow;
use Yii;
/**
 * 厂商评鉴申请控制器
 */
class FirmEvaluateApplyController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PdFirmEvaluateApply';

    /**
     * 厂商评鉴申请列表
     */
    public function actionIndex()
    {
        $model = new PdFirmEvaluateApplySearch();
        $dataProvider = $model->searchEvaluateApply(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 获取列表数据
     */
    public function actionIndexData()
    {
        return [
            //获取厂商地位
            'firmPosition' => BsPubdata::getData(BsPubdata::FIRM_LEVEL),
            //获取评鉴类型
            'evaluateApplyType' => BsPubdata::getData(BsPubdata::SUPPLIER_EVALUATE_TYPE),
            //获取评鉴状态
            'evaluateApplyStatus' => PdFirmEvaluateApply::evaluateApplyStatus(),
        ];
    }

    /**
     * 获取评鉴申请数据
     */
    public function actionEvaluateApplyData($applicantId)
    {
        return [
            //申请人信息
            'applicantInfo' => HrStaffShow::findOne($applicantId),
            //获取评鉴类型
            'evaluateApplyType' => BsPubdata::getData(BsPubdata::SUPPLIER_EVALUATE_TYPE),
            //获取评鉴状态
            'avoidEvaluateCondition' => BsPubdata::getData(BsPubdata::SUPPLIER_AVOID_EVALUATE_CONDITION),
        ];
    }

    /**
     * 获取呈报通过的厂商
     */
    public function actionSelectFirm()
    {
        $model = new PdFirmEvaluateApplySearch();
        $dataProvider = $model->searchFirmReport(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 获取供应商数据
     */
    public function actionSupplierData()
    {
        return [
            //获取供应商新增类型
            'supplierAddType' => BsPubdata::getData(BsPubdata::PD_SUPPLIER_COMPTYPE),
            //获取供应商地位
            'supplierPosition' => BsPubdata::getData(BsPubdata::FIRM_LEVEL),
            //获取供应商类型
            'supplierType' => BsPubdata::getData(BsPubdata::FIRM_TYPE),
            //获取供应商来源
            'supplierSource' => BsPubdata::getData(BsPubdata::FIRM_SOURCE),
            //获取供应商分级分类
            'supplierGradeCategory' => PdProductType::getLevelOneValue(),
            //交易币别
            'tradeCurrency' => BsPubdata::getData(BsPubdata::SUPPLIER_TRADE_CURRENCY),
            //供应商地址-国家
            'country' => BsDistrict::getDisLeveOne(),
            //source类别
            'sourceType' => BsPubdata::getData(BsPubdata::PD_SOURCE_TYPE),
            //代理等级
            'agentLevel' => BsPubdata::getData(BsPubdata::PD_AGENTS_LEVEL),
            //授权区域范围
            'authorizationAreaScope' => BsPubdata::getData(BsPubdata::PD_AUTHORIZE_AREA),
            //销售范围
            'saleScope' => BsPubdata::getData(BsPubdata::PD_SALE_AREA),
            //售后服务
            'saleServer' => BsPubdata::getData(BsPubdata::PD_SERVICE),
            //物流配送
            'logisticsDelivery' => BsPubdata::getData(BsPubdata::PD_DELIVERY_WAY),
        ];
    }

    /**
     * 获取商品
     */
    public function actionAddProduct()
    {
        $model = new PdFirmEvaluateApplySearch();
        $dataProvider = $model->searchProduct(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $dataProvider;
    }

    /**
     * 获取商品数据
     */
    public function actionProductData()
    {
        return [
            //商品定位
            'productPosition' => BsPubdata::getData(BsPubdata::DP_PRODUCT_LEVEL),
            //一阶
            'oneCategory' => PdProductType::getLevelOneValue(),
        ];
    }

    public function actionFindFirm($id){
        return PdFirmShow::find()->where(['firm_id'=>$id])->one();
    }
}