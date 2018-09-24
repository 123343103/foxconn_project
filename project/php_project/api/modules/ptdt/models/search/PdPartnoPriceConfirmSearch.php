<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/9
 * Time: 上午 09:57
 */
namespace  app\modules\ptdt\models\search;

use app\classes\Trans;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsProduct;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTradConditions;
use app\modules\ptdt\models\FpPas;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\data\ActiveDataProvider;

class PdPartnoPriceConfirmSearch extends PartnoPrice
{
    public $pdt_name;
    public function fields()
    {
        $fields = parent::fields();
        $fields['pdt_name'] = function () {
            return $this->product['pdt_name'];
        };
        $fields["price_type"]=function(){
            $price_type=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->price_type])->scalar();
            return $price_type?$price_type:"";
        };
        $fields["price_from"]=function(){
            $price_from=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->price_from])->scalar();
            return $price_from?$price_from:"";
        };
        $fields["pdt_level"]=function(){;
            $pdt_level=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->pdt_level])->scalar();
            return $pdt_level?$pdt_level:"";
        };
        $fields["pdt_manager"]=function(){
            $pdt_manager=$this->product["pdt_manager"];
            return $pdt_manager?$pdt_manager:"";
        };
        $fields['currency']=function(){
            $currency=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->currency])->scalar();
            return $currency?$currency:"";
        };
        $fields["tp_spec"]=function(){
            return isset($this->product->attr->ATTR_NAME)?$this->product->attr->ATTR_NAME:"";
        };
        $fields['type_6']=function(){
            return isset($this->product->productType->category_sname)?$this->product->productType->category_sname:"";
        };
        $fields['type_5']=function(){
            return isset($this->product->productType->parent->category_sname)?$this->product->productType->parent->category_sname:"";
        };
        $fields['type_4']=function(){
            return isset($this->product->productType->parent->parent->category_sname)?$this->product->productType->parent->parent->category_sname:"";
        };
        $fields['type_3']=function(){
            return isset($this->product->productType->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->category_sname:"";
        };
        $fields['type_2']=function(){
            return isset($this->product->productType->parent->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->parent->category_sname:"";
        };
        $fields['type_1']=function(){
            return isset($this->product->productType->parent->parent->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->parent->parent->category_sname:"";
        };
        $fields["brand"]=function(){
            return isset($this->product->brand->BRAND_NAME_CN)?$this->product->brand->BRAND_NAME_CN:"";
        };
        $fields["trading_terms"]=function(){
            $trading_terms=BsTradConditions::find()->select("tcc_sname")->where(["tcc_id"=>$this->trading_terms])->scalar();
            return $trading_terms?$trading_terms:"/";
        };
        $fields["payment_terms"]=function(){
            $payment_terms=BsPayCondition::find()->select("pat_sname")->where(["pat_id"=>$this->payment_terms])->scalar();
            return $payment_terms?$payment_terms:"/";
        };
        $fields['unit']=function(){
            $pasInfo=FpPas::findOne(["part_no"=>$this->part_no]);
            $unit=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$pasInfo['unit']])->scalar();
            return $unit?$unit:"";
        };
        $fields['status']=function(){
            switch($this->status){
                case 0:
                    return "未定价";break;
                case 1:
                    return "发起定价";break;
                case 2:
                    return "商品开发维护";break;
                case 3:
                    return "审核中";break;
                case 4:
                    return "已定价";break;
                case 5:
                    return "被驳回";break;
                case 6:
                    return "已逾期";break;
                case 7:
                    return "重新定价";break;
                default:
                    return "未定价";break;
            }
        };
        $fields['isrelation']=function(){
            return $this->isrelation?$this->isrelation:"/";
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
        $model=PdPartnoPriceConfirmSearch::find();
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

        $type_1=isset($params["type_1"])?$params["type_1"]:"";
        $type_2=isset($params["type_2"])?$params["type_2"]:"";
        $type_3=isset($params["type_3"])?$params["type_3"]:"";
        $type_4=isset($params["type_4"])?$params["type_4"]:"";
        $type_5=isset($params["type_5"])?$params["type_5"]:"";
        $type=array($type_1,$type_2,$type_3,$type_4,$type_5);
        $type=array_filter($type);
        $type=array_pop($type);
        $model->andFilterWhere(["like",BsProduct::tableName().".bs_category_id",$type]);
        $model->andFilterWhere(["in",self::tableName().".part_no",empty($params['part_no'])?"":explode(',',$params['part_no'])]);

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
