<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\search\CrmCreditMaintainSearch;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrmCreditMaintainController implements the CRUD actions for CrmCreditMaintain model.
 */
class CrmCreditMaintainController extends BaseController
{
    private $_url = 'crm/crm-credit-maintain/'; //对应api

    /**
     * Lists all CrmCreditMaintain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val){
                $dataProvider['rows'][$key]['create_at'] = date("Y-m-d", strtotime($val['create_at']));
                $dataProvider['rows'][$key]['update_at'] = $val['update_at'] ? date("Y-m-d", strtotime($val['update_at'])) : '';
            }
            return Json::encode($dataProvider);
        }
        $columns = $this->getField('/crm/crm-credit-maintain/index');
        return $this->render('index', [
            'queryParam'=>$queryParam,
            'columns'=>$columns
        ]);
    }

    /**
     * Displays a single CrmCreditMaintain model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->getModel($id),
        ]);
    }

    /**
     * Creates a new CrmCreditMaintain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = '@app/views/layouts/ajax';
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $post['CrmCreditMaintain']['create_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('创建类型'.$post['CrmCreditMaintain']['credit_name'].'成功');
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "创建失败", "flag" => 0]);
            }
        }
        return $this->render('create');
    }

    /**
     * Updates an existing CrmCreditMaintain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $postData['CrmCreditMaintain']['update_by'] = Yii::$app->user->identity->staff_id;
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                SystemLog::addLog('修改类型'.$postData['CrmCreditMaintain']['credit_name'].'成功');
                return Json::encode(['msg' => "修改成功！", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "修改失败！", "flag" => 0]);
            }
        }
        $model = $this->getModel($id);
        return $this->render('update',[
            'model'=>$model
        ]);
    }

    /**
     * Deletes an existing CrmCreditMaintain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'delete?id=' . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status'] == 1) {
            SystemLog::addLog($result['data']['msg']);
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'].' 删除失败', "flag" => 0]);
        }
    }

    /**
     * Finds the CrmCreditMaintain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmCreditMaintain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function getModel($id){
        $url = $this->findApiUrl().$this->_url.'models?id='.$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}
