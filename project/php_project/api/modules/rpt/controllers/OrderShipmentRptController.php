<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmSalearea;
use app\modules\rpt\models\search\OrdShipRptSearch;
use yii;

class OrderShipmentRptController extends BaseActiveController
{
    public $modelClass = 'app\modules\rpt\models\OrdShipRpt';

    public function actionIndex()
    {
        $search = new OrdShipRptSearch();
        $dataProvider = $search->search(Yii::$app->request->queryParams);

        $model = $dataProvider->getModels();
        $list["rows"] = $model;

        $list["total"] = $dataProvider->totalCount;

        return $list;

    }



    public function actionDownList(){
        $downList['saleArea'] = CrmSalearea::getSalearea();//营销区域
        return $downList;

    }
}

