<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/9
 * Time: 上午 09:57
 */
namespace  app\modules\ptdt\models\search;

use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\data\ActiveDataProvider;

class PdPartnoPriceReviewSearch extends PartnoPrice
{
    public $pdt_name;
    public function fields()
    {
        $fields = parent::fields();
        $fields['pdt_name'] = function () {
            return $this->product['pdt_name'];
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
        $model=PdPartnoPriceReviewSearch::find();
        $dataProvider=new ActiveDataProvider([
            "query"=>$model,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);

        $valid_date_start=isset($params['valid_date_start'])?$params['valid_date_start']:"";
        $valid_date_end=isset($params['valid_date_end'])?$params['valid_date_end']:"";
        $model->andFilterWhere([">","valid_date",$valid_date_start]);
        $model->andFilterWhere(["<","valid_date",$valid_date_end]);

        $model->andFilterWhere([
            "status"=>isset($params['status'])?$params['status']:"",
            "price_from"=>isset($params['price_from'])?$params['price_from']:"",
            "p_flag"=>isset($params['p_flag'])?$params['p_flag']:"",
            "pdt_manager"=>isset($params['pdt_manager'])?$params['pdt_manager']:"",
            "pdt_level"=>isset($params['pdt_level'])?$params['pdt_level']:"",
        ]);
        $model->andFilterWhere(["supplier_name_shot"=>isset($params['supplier_name_shot'])?$params['supplier_name_shot']:""]);
        $model->andFilterWhere(["like","part_no",isset($params['part_no'])?$params['part_no']:""]);
        return $dataProvider;
    }


}
