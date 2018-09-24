<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/11
 * Time: 上午 09:23
 */
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\bootstrap\Html;

class SelectProductController extends BaseController
{
    private $_url = 'ptdt/select-product/';
    //获取商品
    public function actionSelectProduct()
    {
        $url=$this->findApiUrl().$this->_url."product-data";
        if(Yii::$app->request->isAjax){
//            $params=\Yii::$app->request->queryParams;
//            $url=$this->findApiUrl().$this->_url."product-data".http_build_query($params);
//            $url=$this->findApiUrl().'spp/supplier/select-purpdt';
            $url.='?'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
//        $url=$this->findApiUrl().$this->_url."options";
//        $options=Json::decode($this->findCurl()->get($url));
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render("select-product");
    }
}