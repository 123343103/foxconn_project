<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/24
 * Time: 上午 09:46
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseController;
use yii\helpers\Json;

class ProductStockQueryController extends BaseController{
    public $_url="/warehouse/product-stock-query/";
    public function actionIndex(){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."index?".http_build_query($params);
            $data=$this->findCurl()->get($url);
            return $data;
        }
        if(\Yii::$app->request->get('export')){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."index?".http_build_query($params);
            $data=Json::decode($this->findCurl()->get($url));
            $this->exportFiled($data["rows"]);
        }
        $url=$this->findApiUrl().$this->_url."options";
        $options=Json::decode($this->findCurl()->get($url));
        $columns=$this->getField();
        return $this->render("index",["options"=>$options,"columns"=>$columns]);
    }
}
?>