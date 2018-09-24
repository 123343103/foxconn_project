<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2017/2/24
 * Time: 上午 09:46
 */

namespace app\modules\app\controllers;

use app\controllers\AppBaseController;
use app\modules\app\models\search\PriceSearch;
use app\modules\app\models\show\PriceShow;
use app\modules\ptdt\models\FpBsCategory;

class PricesController extends AppBaseController
{
    public $modelClass = 'app\modules\ptdt\models\PartNoPriceShow';

    /*
     * 生成表格页数据
     */
    public function actionIndex()
    {
        $searchModel = new PriceSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /*
     * 按类型品牌查询生成表格页数据
     */
    public function actionIndexType()
    {
        $searchModel = new PriceSearch();
        $dataProvider = $searchModel->typeSearch(\Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     *获取模型
     */
    public function actionGetModel($id)
    {
        return PriceShow::find()->where(['price_no'=>$id])->one();
    }

    public function actionProductTypes()
    {
        return FpBsCategory::getLevelOne();
    }

}