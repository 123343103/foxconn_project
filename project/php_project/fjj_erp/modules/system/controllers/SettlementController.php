<?php

namespace app\modules\system\controllers;

use app\modules\common\models\BsSettlement;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SystemLog;
use app\controllers\BaseController;
use yii\helpers\Json;
/**
 * SettlementController implements the CRUD actions for BsSettlement model.
 */
class SettlementController extends BaseController
{
    private $_url = 'system/settlement/';
    /**
     * Lists all BsSettlement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl().$this->_url."index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $data = $this->findCurl()->get($url);
        if(Yii::$app->request->isAjax){
            return $data;
        }
        return $this->render("index");
    }

    /**
     * Displays a single BsTradConditions model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $staff = HrStaff::find()->where(['staff_id'=>$model->create_by])->one();
        $staffName = $staff['staff_name'];
        return $this->render('view', [
            'model' => $model,
            'staffName'=>$staffName
        ]);
    }

    /**
     * Creates a new BsTradConditions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()){
            $postData = Yii::$app->request->post();
            $postData['BsSettlement']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['BsSettlement']['create_at'] = date("Y-m-d", time());
            $url = $this->findApiUrl() . $this->_url . "create" ;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->post($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "發生未知錯誤，新增失敗", "flag" => 0]);
            }
        }else{
            $model = new BsSettlement();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BsTradConditions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->getIsPost()){
            $postData = Yii::$app->request->post();
            $postData['BsSettlement']['update_by'] = Yii::$app->user->identity->staff_id;
            $postData['BsSettlement']['update_at'] = date("Y-m-d", time());
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "编辑成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "發生未知錯誤，编辑失敗", "flag" => 0]);
            }
        } else {
            $model = $this->findModel($id);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BsTradConditions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = $this->findCurl()->delete($url);
        if (json_decode($result)->status == 1) {
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失敗", "flag" => 0]);
        }
    }

    /**
     * Finds the BsTradConditions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BsTradConditions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    private function findModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "model?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url), false);
        if (!$model) {
            throw new yii\web\NotFoundHttpException("頁面未找到");
        }
        return $model;
    }
}
