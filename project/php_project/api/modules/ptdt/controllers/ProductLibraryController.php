<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/25
 * Time: 下午 02:54
 */

namespace app\modules\ptdt\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\FpBsCategory;
use app\modules\ptdt\models\search\BsProductSearch;
use app\modules\ptdt\models\show\BsProductShow;
use yii\data\ActiveDataProvider;
use yii;

class ProductLibraryController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\BsPartno';

    /*
     * 生成表格页数据
     */
    public function actionIndex()
    {
        $model = new BsProductSearch();
        $dataProvider = $model->search(yii::$app->request->queryParams);
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
        $model=BsProduct::findOne(["pdt_no"=>$id]);
        $list['BsProduct'] = \Yii::$app->request->post();
        if (\Yii::$app->request->isPost) {
            $cat_id=Yii::$app->request->post("type_6")
                | Yii::$app->request->post("type_5")
                | Yii::$app->request->post("type_4")
                | Yii::$app->request->post("type_3")
                | Yii::$app->request->post("type_2")
                | Yii::$app->request->post("type_1");
            $list['BsProduct']['bs_category_id']=$cat_id;
            $model->load($list);
            if ($model->validate() && $model->save()) {
                return $this->success();
            } else {
             return $this->error();
            }
        }
    }

    /**
     *获取模型
     */
    public function actionGetModel($id)
    {
        return BsProductShow::findOne(["pdt_no"=>$id]);
    }

    /**
     * 一阶分类
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionProductTypes()
    {
        return BsCategory::getLevelOne();
//        return FpBsCategory::getLevelOne();
    }

    /**
     * 获取大类子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionProductTypesChildren($id)
    {
        return FpBsCategory::getChildrenByParentId($id);
    }

    /**
     * 获取大类子类
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionTypesOption($id)
    {
        return FpBsCategory::getTypeOption($id);
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

    function actionDistrictLevel($id){
        $tree=[];
        $path=[];
        while($id>0){
            $addr_info=BsDistrict::findOne($id);
            $path[]=$addr_info->district_id;
            $id=$addr_info->district_pid;
            $parent=BsDistrict::findAll(["district_pid"=>$addr_info->district_pid]);
            $tree[]=$parent;
        }
        $tree=array_reverse($tree);
        $path=array_reverse($path);
        return [
            'oneLevel'=>isset($tree[0])?$tree[0]:"",
            'oneLevelId'=>isset($path[0])?$path[0]:"",
            'twoLevel'=>isset($tree[1])?$tree[1]:"",
            'twoLevelId'=>isset($path[1])?$path[1]:"",
            'threeLevel'=>isset($tree[2])?$tree[2]:"",
            'threeLevelId'=>isset($path[2])?$path[2]:"",
            'fourLevel'=>isset($tree[3])?$tree[3]:"",
            'fourLevelId'=>isset($path[3])?$path[3]:""
        ];
    }
}