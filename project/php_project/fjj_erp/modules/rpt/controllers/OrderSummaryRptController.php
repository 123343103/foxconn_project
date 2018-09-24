<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;

class OrderSummaryRptController extends BaseController
{
    private $_url = "rpt/order-summary-rpt/"; //对应api

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
//        dumpE(Json::decode($this->findCurl()->get($url)));

        if (Yii::$app->request->isAjax) {
            $list = Json::decode($this->findCurl()->get($url));
            return Json::encode($list);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/rpt/order-summary-rpt/index");
        return $this->render('index',[
           'downList' => $downList,
            'queryParam' => $queryParam,
            'columns' => $columns,
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

