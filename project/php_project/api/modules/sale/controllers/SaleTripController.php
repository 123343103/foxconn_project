<?php

namespace app\modules\sale\controllers;

use app\controllers\BaseActiveController;
use app\modules\sale\models\SaleDetails;
use app\modules\sale\models\search\SaleDetailsSearch;
use yii;
use app\modules\common\models\BsDistrict;

class SaleTripController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\SaleTripapply';
    public function actionIndex()
    {
        return 'saletripcontroller api';
    }
    /**
     * 获取一级地址
     * @return array|yii\db\ActiveRecord[]
     */
    public function actionDistrictLevelOne(){
        return BsDistrict::getDisLeveOne();
    }

}
