<?php
/**
 * User: F1676624
 * Date: 2017/2/8
 */

namespace app\modules\app\controllers;


use app\controllers\AppBaseController;
use app\modules\ptdt\models\FpPartNoo;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\BsCategory;
use app\modules\ptdt\models\show\FpPartNooShow;
use app\modules\app\models\show\ProductShow;
use app\modules\app\models\search\ProductSearch;
use yii;

class ProductLibraryController extends AppBaseController
{
    public $modelClass = 'app\modules\ptdt\models\search\ProductSearch';

    /*
     * 生成表格页数据
     */
    public function actionIndex()
    {
        $model = new ProductSearch();
        $dataProvider = $model->AppSearch(yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /*
     * 类别搜索生成表格页数据
     */
    public function actionIndexType()
    {
        $model = new ProductSearch();
        $dataProvider = $model->TypeSearch(yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     *查看商品详情
     */
    public function actionEdit($id)
    {
        $model = FpPartNoo::getModel($id);
        $list['FpPartNoo'] = Yii::$app->request->post();
        if (yii::$app->request->isPut) {
            $list['FpPartNoo'] = Yii::$app->request->post();
            $cat_id = Yii::$app->request->post("type_6")
                | Yii::$app->request->post("type_5")
                | Yii::$app->request->post("type_4")
                | Yii::$app->request->post("type_3")
                | Yii::$app->request->post("type_2")
                | Yii::$app->request->post("type_1");
            $list['FpPartNoo']['category_id'] = $cat_id;
            $model->load($list);
            if ($model->validate() && $model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        } else {
            return $model;
        }
    }

    /**
     *获取模型
     */
    public function actionGetModel($id)
    {
        return ProductShow::findOne(["pdt_no" => $id]);
    }

    /**
     * 一阶分类
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionProductTypes()
    {
        return BsCategory::getLevelOne();
    }

    /**
     * 获取大类子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionProductTypesChildren($id)
    {
        return BsCategory::getChildrenByParentId($id);
    }

    /**
     * 获取大类子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionTypesOption($id)
    {
        return BsCategory::getTypeOption($id);
    }

    /**
     * 获取商品价格信息
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionPrices($id)
    {
        return PartnoPrice::getPricesByPartNO($id);
    }
}