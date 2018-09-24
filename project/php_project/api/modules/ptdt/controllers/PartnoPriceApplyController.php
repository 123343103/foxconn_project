<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/12/6
 * Time: 下午 02:28
 */

namespace app\modules\ptdt\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsProduct;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\search\BsProductSearch;
use app\modules\ptdt\models\search\PdPartnoPriceApplySearch;
use app\modules\ptdt\models\show\FpPartNoShow;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\data\ActiveDataProvider;
//定价申请控制器
class PartnoPriceApplyController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PartNoPriceShow';

    //定价申请列表
    public function actionIndex(){
        $searchModel=new PdPartnoPriceApplySearch();
        $dataProvider=$searchModel->search(\Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //定价申请修改
    public function actionEdit($id){
     $model = PartnoPrice::findOne($id);
        if(!$model){
            return $this->error();
        }
        $list["PartnoPrice"]=\Yii::$app->request->post();
        $model->load($list);
        if($model->validate() && $model->save()){
            return $this->success();
        }
            return $this->error();
    }



    //料号选择
    public function actionPartnoSelect(){
        $model=BsProductSearch::find();
        $params=\Yii::$app->request->queryParams;
        $part_no=isset($params['pdt_no'])?$params['pdt_no']:"";
        $model->andFilterWhere(["pdt_no"=>$part_no]);
        $dataProvider=new ActiveDataProvider([
            "query"=>$model,
            "pagination"=>[
                "pageSize"=>5
            ]
        ]);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //定价申请新增
    public function actionCreate(){
        $model=new PartnoPrice();
        $params=\Yii::$app->request->post();
        $list['PartnoPrice']=$params;
        $model->load($list);
        if($model->validate() && $model->save()){
            return $this->success();
        }else{
            return $this->error($model->getErrors());
        }

    }

    //定价申请删除
    public function actionDelete($id){
        $idArr=explode(",",$id);
        if(PartnoPrice::deleteAll(["id"=>$idArr])){
            return $this->success();
        }else{
            return $this->error();
        }
    }



    //定价申请详情数据
    public function actionModels($id)
    {
        $model = PartnoPriceShow::findOne($id);
        return $model;

    }

    //下拉列表数据
    public function actionGetDownList(){
        return BsProduct::options([""=>"请选择"]);
    }

}