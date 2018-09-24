<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class OrderShipmentRptController extends BaseController
{
    private $_url = "rpt/order-shipment-rpt/"; //对应api

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
     //dumpE(Json::decode($this->findCurl()->get($url)));

        if (Yii::$app->request->isAjax) {
            $list = Json::decode($this->findCurl()->get($url));
            return Json::encode($list);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/rpt/order-shipment-rpt/index");

        $url=$this->findApiUrl()."crm/crm-potential-customer/claim-dropdown-list?staff_id=".\Yii::$app->user->identity->staff_id;
        \Yii::$app->user->identity->is_supper?$url.="&is_supper=1":"";
        $res=Json::decode($this->findCurl()->get($url));

        return $this->render('index',[
           'downList' => $downList,
            'queryParam' => $queryParam,
            'columns' => $columns,
            'department' => $res
        ]);


    }


    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        return $this->exportFiled($dataProvider['rows']);
    }
}

