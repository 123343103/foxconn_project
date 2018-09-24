<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 上午 10:02
 */

namespace app\modules\warehouse\controllers;

use yii;
use yii\helpers\Json;
use yii\helpers\Url;

/*
物流出货/物流进度查询
*/
use app\controllers\BaseController;

class LogisticsController extends BaseController
{
    private $_url = "warehouse/logistics/";  //对应api控制器URL

    //首页，出货的商品信息查询
    public function actionIndex()
    {
        $url=$this->findApiUrl().$this->_url."index";
        $queryParam = Yii::$app->request->queryParams;
//        dump($queryParam);
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        $crminforesult=$this->actionCrmInfo($queryParam['soh_code'],$queryParam['ORDERNO'],$queryParam['invh_code']);
       $crminfo= Json::decode($crminforesult);
        return  $this->render('index',[
            'param'=>$queryParam,
            'crminfo'=>$crminfo
        ]);
    }
    //物流进度信息
    public function actionLogInfo()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'warehouse/logistics/log-info';
        $url.='?'.http_build_query($params);
        return $this->findCurl()->get($url);
    }
//添加物流进度信息
    public function actionAdd()
    {
        $params=Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "add";
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['OrdLogisticLog']['CREATE_BY'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $result = Json::decode($curl->post($url));
            if ($result['status']) {
                return Json::encode(['msg' => "新增物流进度成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $result['msg'], "flag" => 0]);
            }
        }
        $data = Json::decode($this->findCurl()->get($url));
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('add',[
            'param'=>$params,
            'data'=>$data
            ]);
    }
    //修改物流进度信息
    public function actionUpdate($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->isPost) {
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $postData = Yii::$app->request->post();
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "修改物流进度信息成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        else {
            $model = $this->getModel($id);
            return $this->render("update", [
                'model' => $model
            ]);
        }
    }

//添加物流出货信息
    public function actionShipment(){
        $this->layout = '@app/views/layouts/ajax';
        $params=Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "shipment";
        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        }
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['OrdLogisticsShipment']['create_by'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $result = Json::decode($curl->post($url));
            if ($result['status']) {
                return Json::encode(['msg' => "新增物流出货成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $result['msg'], "flag" => 0]);
            }
        }
        $data = Json::decode($this->findCurl()->get($url));

        return $this->render('shipment',[
            'param'=>$params,
            'data'=>$data,
        ]);
    }
    //判断物流单号是否在物流出货表中存在
    public function actionOrderno($partno,$o_whdtid){
        $url = $this->findApiUrl() . $this->_url . "orderno?partno=".$partno."&o_whdtid=".$o_whdtid;
        return $this->findCurl()->get($url);
    }

    /*
    * 根据物流进度id获取进度信息
    */
    public function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('页面未找到');
        }
    }

    public function actionCrmInfo($orderno,$kdno,$invhcode)
    {
        $url = $this->findApiUrl().'warehouse/logistics/crm-info?orderno='.$orderno.'&kdno='.$kdno.'&o_whcode='.$invhcode;
        $info=$this->findCurl()->get($url);
        if(!empty($info)){
            return $info;
        }
        return "";
    }
}