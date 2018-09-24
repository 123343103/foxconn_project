<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\common\models\search\BsCompanySearch;
use yii\helpers\Json;
use yii\helpers\Url;
//产品报价控制器
class CrmQuotePriceController extends BaseController
{

    private $_url = "crm/crm-quote-price/";

    public function actionIndex(){
        if(\Yii::$app->request->isAjax){
            $url=$this->findApiUrl().$this->_url."index";
            $params=\Yii::$app->request->queryParams;
            $url.="?".http_build_query($params);
            $curl=$this->findCurl();
            print_r($curl->get($url));die();
        }
        return $this->render("index");
    }

    //添加报价
    public function actionCreate()
    {
        if(\Yii::$app->request->isPost){
            $url=$this->findApiUrl().$this->_url."create";
            $params=\Yii::$app->request->bodyParams;
            $curl=$this->findCurl();
            $curl->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $res=Json::decode($curl->post($url));
            if($res["status"]==1){
                return Json::encode(["msg"=>"新增报价成功","flag"=>1,"url"=>Url::to(['create'])]);
            }else{
                return Json::encode(["msg"=>"新增报价失败","flag"=>0]);
            }
        }else{
            $dropdown_list["currency"]=\app\modules\common\models\BsCurrency::find()->select("cur_id,cur_sname")->all();
            $dropdown_list["pay_type"]=\app\modules\common\models\BsPayment::find()->select("pac_id,pac_sname")->all();
            $dropdown_list["pay_cond"]=\app\modules\common\models\BsPayCondition::find()->select("pat_id,pat_sname")->all();
            $dropdown_list["dev_cond"]=\app\modules\common\models\BsDevcon::find()->select("dec_id,dec_sname")->all();
            return $this->render("create",[
                "dropdown_list"=>$dropdown_list
            ]);
        }
    }

    //弹出层选择客户
    public function actionSelectCustomer(){
        $url=$this->findApiUrl().$this->_url."select-customer";
        $params=\Yii::$app->request->queryParams;
        if(!empty($params)){
            $url.="?".http_build_query($params);
        }
        $res=$this->findCurl()->get($url);
        if(\Yii::$app->request->isAjax){
            return $res;
        }else{
            return $this->renderAjax("select-customer");
        }
    }

    //弹出层选择公司
    public function actionSelectCorp(){
            $model=new BsCompanySearch();
            $dataProvider=$model->search(\Yii::$app->request->get());
            if(\Yii::$app->request->isAjax){
                return Json::encode($dataProvider);
            }else{
                return $this->renderAjax("select-corp");
            }
    }

    //弹出层选择产品
    public function actionProductList(){
        $url=$this->findApiUrl().$this->_url."select-product";
        $params=\Yii::$app->request->queryParams;
        if(!empty($params)){
            $url.="?".http_build_query($params);
        }
        $res=$this->findCurl()->get($url);
        if(\Yii::$app->request->isAjax){
            return $res;
        }
    }

    //弹出层商品定价信息
    public function actionPriceInfo($id){
        $url=$this->findApiUrl().$this->_url."price-info?id=".$id;
        $price_list=Json::decode($this->findCurl()->get($url));
        if(\Yii::$app->request->isAjax){
            return $this->renderPartial("price-info",[
                "price_list"=>$price_list
            ]);
        }
    }


}
