<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/11/20
 * Time: 上午 09:20
 */

namespace app\modules\system\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;

class SystemOperationController extends BaseController{
    private $_url = "system/system-operation/";
     public function actionIndex(){
         $url = $this->findApiUrl() . $this->_url . 'index';
         $queryParam = Yii::$app->request->queryParams;
         if (!empty($queryParam)) {
             $url .= "?" . http_build_query($queryParam);
         }
         if (Yii::$app->request->isAjax) {
             $dataProvider = Json::decode($this->findCurl()->get($url));
             return Json::encode($dataProvider);
         }
         $columns = $this->getField('/system/system-operation/index');
         return $this->render("index",[
             'queryParam' => $queryParam,
             'columns' => $columns
         ]);
     }


     //新增
    public function actionCreate()
    {
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "create";
            $post["BsBtn"]["opper"]=Yii::$app->user->identity->staff->staff_id;//操作人
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
//                SystemLog::addLog('创建角色'.$post['CrmSaleRoles'  ]['sarole_sname'].'成功');
                return Json::encode(['msg' => "创建成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "创建失败", "flag" => 0]);
            }
        }else{
            return $this->render('create');
        }
    }

    public function actionUpdate($id){
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . 'update?id=' . $id;
            $postData["BsBtn"]["opper"]=Yii::$app->user->identity->staff->staff_id;//操作人
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $model=$this->getModel($id);
            return $this->render("update",[
                "model"=>$model,
            ]);
        }
    }
    public function getModel($id){
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }
}