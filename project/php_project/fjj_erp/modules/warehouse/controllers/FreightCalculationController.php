<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/14
 * Time: 下午 03:36
 */

namespace app\modules\warehouse\controllers;

use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;


class FreightCalculationController extends BaseController
{
    private $_url = "warehouse/freight-calculation/";  //对应api控制器URL
    public function actionIndex()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."index";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
//        dump($dataProvider);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        $district=$this->getDistrict();
        return  $this->render('index',[
            'param'=>$queryParam,
            'data'=>$dataProvider,
            'district'=>$district
        ]);
    }
    //获取料号信息
    public function actionPartInfo()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."index?part_no=".$queryParam['part_no'];
        return $this->findCurl()->get($url);
    }
    //获取发货地信息
    public function actionShipInfo()
    {
        $queryParam = Yii::$app->request->queryParams;
        //dump($queryParam['partno']);
        $url = $this->findApiUrl() . $this->_url . "ship-info?partno=".$queryParam['partno'];
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
    //运费试算结果
    public function actionFreight()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "freight";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $result = $this->findCurl()->get($url);
        return $result;
    }
}