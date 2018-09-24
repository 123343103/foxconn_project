<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/9
 * Time: 上午 09:57
 */
namespace  app\modules\ptdt\models\search;

use app\classes\Trans;
use app\modules\common\models\BsProduct;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\data\ActiveDataProvider;

class PdPartnoPriceApplySearch extends PartnoPrice
{
    public $pdt_name;

    public function fields()
    {
        $fields = parent::fields();
        $fields['pdt_name'] = function () {
            $pdt_name=$this->product['pdt_name'];
            return $pdt_name?$pdt_name:"/";
        };
        $fields["price_type"]=function(){
            $options=BsProduct::options();
            return isset($options["price_type"][$this->price_type])?$options["price_type"][$this->price_type]:"/";
        };
        $fields["price_from"]=function(){
            $options=BsProduct::options();
            return isset($options["price_from"][$this->price_from])?$options["price_from"][$this->price_from]:"/";
        };
        $fields["pdt_level"]=function(){
            $options=BsProduct::options();
            return isset($options["pdt_level"][$this->pdt_level])?$options["pdt_level"][$this->pdt_level]:"/";
        };
        $fields['status']=function(){
            $options=BsProduct::options();
            return isset($options["status"][$this->status])?$options["status"][$this->status]:"/";
        };
        return $fields;
    }

    public function rules()
    {
        return [
            [['part_no','pdt_name','price_no', 'status', 'pdt_manager', 'price_type', 'price_from', 'iskz', 'isproxy', 'isonlinesell', 'risk_level', 'market_price', 'valid_date'
                , 'istitle', 'archrival', 'pdt_level', 'isto_xs', 'so_nbr', 'salearea', 'usefor', 'packagespc', 'isrelation', 'p_flag', 'supplier_code', 'expiration_date'
                , 'delivery_address', 'payment_terms', 'trading_terms', 'min_price', 'ws_upper_price', 'ws_lower_price', 'gross_profit', 'gross_profit_margin'
                , 'pre_tax_profit', 'pre_tax_profit_rate', 'after_tax_profit', 'after_tax_profit_margin', 'num_area', 'upper_limit_profit', 'lower_limit_profit'
                , 'upper_limit_profit_margin', 'lower_limit_profit_margin', 'filename', 'pre_ws_lower_price', 'price_fd', 'creatby', 'remark', 'verifydate', 'pasid'
                , 'creatdate', 'unit', 'min_order', 'currency', 'limit_day', 'buy_price', 'pasquno', 'brand', 'salearea_code', 'pre_verifydate', 'supplier_name_shot'
                , 'isvalid', 'no_xs_cause', 'tariff', 'customer_type'], 'safe']
        ];
    }


    public function search($params){
        $trans=new Trans();
        $model=PdPartnoPriceApplySearch::find();
        $dataProvider=new ActiveDataProvider([
            "query"=>$model,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);

        $model->andFilterWhere([
            self::tableName().".id"=>isset($params['id'])?$params['id']:"",
            self::tableName().".status"=>isset($params['status'])?$params['status']:"",
            self::tableName().".price_from"=>isset($params['price_from'])?$params['price_from']:"",
            self::tableName().".price_type"=>isset($params['price_type'])?$params['price_type']:"",
            self::tableName().".p_flag"=>isset($params['p_flag'])?$params['p_flag']:"",
            self::tableName().".pdt_level"=>isset($params['pdt_level'])?$params['pdt_level']:"",
        ]);
        if(isset($params['pdt_manager'])){
            $model->andFilterWhere([
                "or",
                [self::tableName().".pdt_manager"=>$params['pdt_manager']],
                [self::tableName().".pdt_manager"=>$trans->c2t($params['pdt_manager'])],
                [self::tableName().".pdt_manager"=>$trans->t2c($params['pdt_manager'])]
            ]);
        }
        $model->andFilterWhere(["like",self::tableName().".part_no",isset($params['pdt_no'])?$params['pdt_no']:""]);
        if(isset($params['pdt_name'])){
            $data=BsProduct::find()->select("pdt_no")->filterWhere([
                "or",
                ["like","pdt_name",$params['pdt_name']],
                ["like","pdt_name",$trans->t2c($params['pdt_name'])],
                ["like","pdt_name",$trans->c2t($params['pdt_name'])],
            ])->column();
            $model->andFilterWhere(["in","part_no",$data]);
        }
        return $dataProvider;
    }


}
