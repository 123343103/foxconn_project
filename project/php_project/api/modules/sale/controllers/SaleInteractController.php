<?php

namespace app\modules\sale\controllers;

use app\controllers\BaseActiveController;
use app\modules\sale\models\SaleDetails;
use app\modules\sale\models\search\SaleDetailsSearch;
use yii;

class SaleInteractController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\SaleInerapply';
    public function actionIndex()
    {
        return 'saleinteractcontroller api';
    }

}
