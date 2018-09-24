<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2017/3/3
 * Time: 上午 11:17
 */
namespace app\modules\hr\controllers;

use Yii;
use app\controllers\BaseActiveController;
use app\modules\hr\models\HrStaffTitle;
use app\modules\hr\models\search\HrStaffTitleSearch;
use app\modules\hr\models\show\HrStaffTitleShow;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\helpers\Url;
class StaffTitleController extends BaseActiveController{
    public $modelClass = 'app\modules\hr\models\HrStaffTitle';
    /**
     * @inheritdoc
     */
    /*protected function verbs()
    {
        return [
            'create' => ['POST', 'GET'],
        ];
    }*/

    /*列表页面*/
    public function actionIndex()
    {
        $searchModel = new HrStaffTitleSearch();
        $queryParams=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

   public function actionCreate()
    {
        $model = new HrStaffTitle();
        /*$post = Yii::$app->request->post();*/
       $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load(Yii::$app->request->post());
           /* $model->title_status = HrStaffTitle::TITLE_STATUS_DEFAULT;*/
           if(!$model->save()){
                throw  new \Exception("新增失败");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();


    }

    public function actionUpdate($id){
        $model = $this->findModel($id);
        if(!$model){
            return $this->error();
        }
        if($model->load($post = Yii::$app->request->post()) && $model->save()){
            return $this->success();
        }
        return $this->error();

    }

    /*详情页面*/
    public function actionView($id)
    {
        return  HrStaffTitleShow::findOne($id);
    }

    /*删除页面*/
    public function actionDelete($id)
    {
        $model = $this->findModel($id)->delete();
        $model->title_status = HrStaffTitle::TITLE_STATUS_DEL;
        if ($model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    public function actionModels($id)
    {
        return $this->findModel($id);
    }

    protected function findModel($id)
    {
        if (($model = HrStaffTitle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}