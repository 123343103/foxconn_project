<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/21
 * Time: 下午 04:23
 */
namespace app\modules\sale\controllers;
use \app\modules\sale\models\SaleCostList;
use app\modules\sale\models\SaleCostType;
use app\modules\sale\models\search\SaleCostListSearch;
use yii;
use app\controllers\BaseActiveController;

class SaleCostCategoryController extends BaseActiveController {

    public $modelClass ='app\modules\sale\models\SaleCostList';

    public function actionIndex(){
        $search = new SaleCostListSearch();
        $dataProvider =  $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionCreate(){
        $model = new SaleCostList();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        } else {
            return $this->error();
        }
    }
    public function actionUpdate($id){
        $model = $this->getModel($id);
        if ($model->load($post = Yii::$app->request->post()) ){
            if ($model->save()){
                return $this->success();
            }else{
                return $this->error();
            }
        }else {
            $list[] = $model;
            return $list;
        }
    }
    public function actionView($id){
        return $this->render('view', [
            'firm' => $this->getModel($id),
        ]);

    }
    public function actionDelete($id){
        $model = $this->getModel($id);
        if ($model){
            $result = SaleCostList::findOne(['stcl_id'=>$id])->delete();
            if ($result){
                return $this->success();
            }else{
                return $this->error();
            }
        }else{
            return $this->error();
        }
    }
    public function actionCostType(){
        return SaleCostType::find()->all();
    }
    public function actionCostTypeName($id){
        return SaleCostType::find()->where(['scost_id'=>$id])->one();
    }

    public function getModel($id){
        $model = SaleCostList::findOne($id);
        return $model;
    }
    public function actionModels($id){
        $model = SaleCostList::getOne($id);
        return $model;
    }

}