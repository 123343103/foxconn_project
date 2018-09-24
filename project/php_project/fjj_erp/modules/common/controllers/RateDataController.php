<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/21
 * Time: 下午 01:44
 */

namespace app\modules\common\controllers;


use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;

class RateDataController extends BaseController
{
    private $_url = "common/rate-data/";  //对应api控制器URL
 public function actionRateSelect()
 {
     $url = $this->findApiUrl() . $this->_url . "rate-select";
     $queryParam = Yii::$app->request->queryParams;
     if (!empty($queryParam)) {
         $url .= "?" . http_build_query($queryParam);
     }
     $dataProvider = $this->findCurl()->get($url);
     if (Yii::$app->request->isAjax) {
         return $dataProvider;
     }
     $this->layout="@app/views/layouts/ajax.php";
     return $this->render('rate-select',[
         'taxno'=>$queryParam
     ]);
 }
}