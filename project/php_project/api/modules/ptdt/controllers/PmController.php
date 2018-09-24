<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\ptdt\models\PdProductManager;
use app\modules\ptdt\models\search\PdProductManagerSearch;
use app\modules\hr\models\HrStaff;
use yii;
/**
 * F3858995
 *  2016/11/14
 */
class PmController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PdProductManager';

    /**
     * 列表数据
     * @return mixed
     */
    public function actionIndex()
    {
        $search = new PdProductManagerSearch();
        $dataProvider =  $search->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 新增
     * @return array
     */
    public function actionAdd(){
        $model = new PdProductManager();
        if( $model->load(Yii::$app->request->post())){
            $model->staff_code = strtoupper($model->staff_code);
            if($model->save()){
                return $this->success();
            }else{
                return $this->error();
            }
        }
        return $this->error();
    }

    /**
     * 更新
     * @param $id
     * @return array
     */
    public function actionEdit($id){
        $model = $this->getModel($id);
        if(!$model){
            return $this->error();
        }
        if($model->load($post = Yii::$app->request->post()) && $model->save()){
            return $this->success();
        }
        return $this->error();

    }

    /**
     * 删除
     * @param $id
     * @return array
     */
    public function actionDelete($id){
        if(PdProductManager::deleteAll(['pm_id'=>$id])>0){
            return $this->success();
        }
        return $this->error();
    }

    /**
     * 获取经理人类型
     * @return array
     */
//    public function actionGetLevelOption(){
//        return PdProductManager::$levelOption;
//    }

    /**
     * 获取商品经理人
     * @return array
     */
//    public function actionGetParentOption(){
//        return PdProductManager::getOptions();
//    }


    public function actionGetOptions($items=""){
        return PdProductManager::getOptions($items);
    }
    /**
     * 前端检查
     * @param $code
     * @return mixed
     */
    public function actionCheckPm($code){
        $code  = strtoupper($code);
        $pm = PdProductManager::find()->where(['staff_code'=>$code])->asArray()->one();
        $staff = HrStaff::getStaffByIdCode($code);
        $arr['pm'] = $pm;
        $arr['staff'] =$staff;
        return $arr;
    }

    /**
     * 返回model
     * @param $id
     * @return null|static
     */
    public function actionFindModel($id){
        return PdProductManager::findOne($id);
    }
    /**
     *获取模型
     */
    private function getModel($id){
        return PdProductManager::findOne($id);
    }


}