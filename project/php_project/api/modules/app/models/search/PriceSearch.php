<?php
/**
 * User: F1676624
 * Date: 2017/3/10
 */
namespace app\modules\app\models\search;

use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\PartnoPrice;
use yii\data\ActiveDataProvider;
use app\classes\Trans;

class PriceSearch extends PartnoPrice
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
            [['part_no', 'pdt_name', 'price_no', 'status', 'pdt_manager', 'price_type', 'price_from', 'iskz', 'isproxy', 'isonlinesell', 'risk_level', 'market_price', 'valid_date'
                , 'istitle', 'archrival', 'pdt_level', 'isto_xs', 'so_nbr', 'salearea', 'usefor', 'packagespc', 'isrelation', 'p_flag', 'supplier_code', 'expiration_date'
                , 'delivery_address', 'payment_terms', 'trading_terms', 'min_price', 'ws_upper_price', 'ws_lower_price', 'gross_profit', 'gross_profit_margin'
                , 'pre_tax_profit', 'pre_tax_profit_rate', 'after_tax_profit', 'after_tax_profit_margin', 'num_area', 'upper_limit_profit', 'lower_limit_profit'
                , 'upper_limit_profit_margin', 'lower_limit_profit_margin', 'filename', 'pre_ws_lower_price', 'price_fd', 'creatby', 'remark', 'verifydate', 'pasid'
                , 'creatdate', 'unit', 'min_order', 'currency', 'limit_day', 'buy_price', 'pasquno', 'brand', 'salearea_code', 'pre_verifydate', 'supplier_name_shot'
                , 'isvalid', 'no_xs_cause', 'tariff', 'customer_type'], 'safe']
        ];
    }


    public function search($params)
    {
        $query = PriceSearch::find();

        $content = $params['content'];
        //UTF8内简繁转换
        $go = new Trans;
        $content = $go->t2c($content);
        $ftcontent = $go->c2t($content);


        $query->joinWith('product');

        $query->andFilterWhere(['or',
            ['like', 'part_no', $content],
            ['like', 'valid_date', $content],
            ['like', 'archrival', $content],
            ['like', 'archrival', $ftcontent],
            ['like', 'usefor', $content],
            ['like', 'usefor', $ftcontent],
            ['like', 'expiration_date', $content],
            ['like', 'payment_terms', $content],
            ['like', 'payment_terms', $ftcontent],
            ['like', 'trading_terms', $content],
            ['like', 'trading_terms', $ftcontent],
            ['like', 'creatdate', $content],
            ['like', 'bs_product.pdt_name', $content],
            ['like', 'bs_product.pdt_name', $ftcontent]
        ]);

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10
            ]
        ]);
        return $dataProvider;
    }

    public function typeSearch($params)
    {
        $query = PriceSearch::find();

        $type = $params['type'];
        $brand = $params['brand'];
        //UTF8内简繁转换
        $go = new Trans;
        $brand = $go->t2c($brand);
        $ftbrand = $go->c2t($brand);

        $query->joinWith('product');

        $query->andFilterWhere(['or', ["like", BsProduct::tableName() . ".pdt_title",
            $brand], ["like", BsProduct::tableName() . ".pdt_title", $ftbrand]]);
        $query->andFilterWhere(["like", "part_no", $type]);

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10
            ]
        ]);
        return $dataProvider;
    }


}
