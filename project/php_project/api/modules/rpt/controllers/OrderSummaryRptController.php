<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\ptdt\models\BsCategory;
use app\modules\rpt\models\search\OrdSumRptSearch;
use app\modules\sale\models\OrdStatus;
use yii;

class OrderSummaryRptController extends BaseActiveController
{
    public $modelClass = 'app\modules\rpt\models\OrdSumRpt';

    public function actionIndex()
    {
        $search = new OrdSumRptSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        $model = $dataProvider->getModels();
        $list["rows"] = $model;

        $list["total"] = $dataProvider->totalCount;
        return $list;
    }



    public function actionDownList(){
        $downList['saleArea'] = CrmSalearea::getSalearea();//营销区域
        $downList['ordStatus'] = OrdStatus::find()->select(['os_id', 'os_name'])->where(['yn' => 1])->all(); //订单状态
        $downList['store'] = CrmStoresinfo::find()->select(['sts_id','sts_sname'])->where(['!=','sts_status',CrmStoresinfo::STATUS_DELETE])->all();//销售点
        return $downList;

    }
}

