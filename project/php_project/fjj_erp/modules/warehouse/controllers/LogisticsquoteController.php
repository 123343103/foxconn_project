<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/7/18
 * Time: 上午 09:22
 */

namespace app\modules\warehouse\controllers;

use Yii;
use app\controllers\BaseController;
use yii\helpers\Json;

class LogisticsquoteController extends  BaseController
{
    private $_url = "warehouse/logisticsquote/";  //对应api控制器URL
    public function actionIndex(){
        $url=$this->findApiUrl().$this->_url."index";
        $queryParam = Yii::$app->request->queryParams;
        $msg="";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        $dataProvider= Json::decode($dataProvider);
        if(!empty($dataProvider['message']))
        {
            $msg=$dataProvider['message'];
        }
        else {
            if (Yii::$app->request->isAjax) {
                return  $this->findCurl()->get($url);
            }
        }
        $District=$this->getDistrict();
        return $this->render('index',[
            'district'=>$District,
            'msg'=>substr($msg,2,18),
            'param'=>$queryParam,
            'trans'=> $this->GetTrans(),
            'transport'=>$this->actionParaCompanyNameList()//获取的运输方式

        ]);
    }
    //获取快递报价信息的头信息
    public function actionLqtExpressHead()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "lqt-express-head";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $result = $this->findCurl()->get($url);
        return $result;
    }
   //获取快递报价信息的详细信息
    public function actionLqtExpressDetail()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "lqt-express-detail";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $result = $this->findCurl()->get($url);
        return $result;
    }
    //获取陆运的头信息
    public function actionLandHead()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "land-head";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $result = $this->findCurl()->get($url);
        return $result;
    }
    //获取陆运报价的详情信息
    public function actionLandDetail()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "land-detail";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $result = $this->findCurl()->get($url);
        return $result;
    }
    /*获取所在地区一级地址*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . $this->_url . "district";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
//获取子类地址
    public function actionChild($id){
        $url = $this->findApiUrl() . $this->_url . "child?id=".$id;
        $info=$this->findCurl()->get($url);
        //dumpE($info);
        if(!empty($info)){
            return $info;
        }
        return "";
    }
//获取快递报价的详情
//    public function actionExpressDetail()
//    {
//        $params=Yii::$app->request->queryParams;
//        $url=$this->findApiUrl().'warehouse/logisticsquote/express-detail';
//        $url.='?'.http_build_query($params);
//        return $this->findCurl()->get($url);
//    }
    //获取陆运报价的详情
//    public function actionLandDetail()
//    {
//        $params=Yii::$app->request->queryParams;
//        $url=$this->findApiUrl().'warehouse/logisticsquote/land-detail';
//        $url.='?'.http_build_query($params);
//        return $this->findCurl()->get($url);
//    }

    public function GetTrans(){
        $url = $this->findApiUrl() . $this->_url . "get-trans";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    //获取运输模式
    public function actionParaCompanyNameList()
    {
        $url = $this->findApiUrl() . 'warehouse/logistics-company/para-company-name-list';
        return Json::decode($this->findCurl()->get($url));
    }
}