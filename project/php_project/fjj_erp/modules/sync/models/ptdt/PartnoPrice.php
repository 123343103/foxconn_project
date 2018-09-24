<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/25
 * Time: 上午 10:49
 */

namespace app\modules\sync\models\ptdt;


use yii\db\ActiveRecord;

class PartnoPrice extends ActiveRecord
{
    public static function tableName()
    {
        return 'partno_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_no'], 'required'],
            [['price_no', 'status', 'price_type', 'price_from', 'iskz', 'isproxy', 'isonlinesell', 'risk_level', 'market_price', 'valid_date'
                , 'istitle', 'archrival','isto_xs', 'so_nbr', 'usefor', 'packagespc', 'isrelation', 'p_flag', 'expiration_date'
                , 'delivery_address', 'payment_terms', 'trading_terms', 'min_price', 'ws_upper_price', 'ws_lower_price', 'gross_profit', 'gross_profit_margin'
                , 'pre_tax_profit', 'pre_tax_profit_rate', 'after_tax_profit', 'after_tax_profit_margin', 'num_area', 'upper_limit_profit', 'lower_limit_profit'
                , 'upper_limit_profit_margin', 'lower_limit_profit_margin', 'pre_ws_lower_price', 'price_fd', 'creatby', 'remark', 'verifydate', 'pasid'
                , 'creatdate', 'min_order', 'currency', 'limit_day', 'buy_price', 'pasquno', 'pre_verifydate'
                , 'isvalid', 'no_xs_cause', 'tariff', 'customer_type','supplier_code','supplier_name_shot'], 'safe']
        ];
    }

}