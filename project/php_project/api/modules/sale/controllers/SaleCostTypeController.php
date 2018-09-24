<?php

namespace app\modules\sale\controllers;

use  app\controllers\BaseActiveController;
use app\modules\sale\models\SaleCostType;
use app\modules\sale\models\SaleCostTypeL;
use \app\modules\sale\models\SaleCostList;
use app\modules\sale\models\search\SaleCostTypeSearch;
use yii;

class SaleCostTypeController extends BaseActiveController
{
    public $modelClass = 'app\modules\sale\models\SaleCostType';

    public function actionIndex()
    {
        $search = new SaleCostTypeSearch();
//        $costTypeL = new SaleCostTypeL();
        $dataProvider =  $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
//        dumpE($model);
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }
    public function actionCreate(){
        $model = new SaleCostType();
        if ($model->load(Yii::$app->request->post())){
            if ($model->save()){
                return $this->success();
            }else{
                return $this->error();
            }
        }else{
            return $this->error();
        }
    }
    public function actionCostList(){
        return SaleCostList::find()->where(['stcl_status'=>1])->all();//选取有效状态的费用分类
    }

    public function actionGetCostList($id){
        return SaleCostList::find()->where(['stcl_id'=>$id])->one();//根据id获取单条费用分类信息
    }

}
