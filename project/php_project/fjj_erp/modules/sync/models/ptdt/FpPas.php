<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "fp_pas".
 *
 * @property string $part_no
 * @property string $area
 * @property string $bu
 * @property string $material
 * @property string $supplier_code
 * @property string $supplier_name_shot
 * @property string $supplier_name
 * @property string $customer
 * @property string $bs_model
 * @property string $quote_currency
 * @property string $quote_price
 * @property string $rmb_price
 * @property string $exchange_rate
 * @property string $effective_date
 * @property string $expiration_date
 * @property string $delivery_address
 * @property string $model
 * @property string $payment_terms
 * @property string $trading_terms
 * @property string $unite
 * @property string $min_order
 * @property string $currency
 * @property string $buy_price
 * @property string $min_price
 * @property string $ws_upper_price
 * @property string $ws_lower_price
 * @property string $min_num
 * @property string $max_num
 * @property string $gross_profit
 * @property string $gross_profit_margin
 * @property string $pre_tax_profit
 * @property string $pre_tax_profit_rate
 * @property string $after_tax_profit
 * @property string $after_tax_profit_margin
 * @property string $flag
 * @property string $num_area
 * @property string $upper_limit_profit
 * @property string $lower_limit_profit
 * @property string $upper_limit_profit_margin
 * @property string $lower_limit_profit_margin
 * @property string $pre_ws_lower_price
 * @property string $price_fd
 */
class FpPas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fp_pas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->controller->module->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_no'], 'required'],
            [['effective_date', 'expiration_date'], 'safe'],
            [['buy_price', 'min_price', 'ws_upper_price', 'ws_lower_price', 'min_num', 'max_num', 'gross_profit', 'gross_profit_margin', 'pre_tax_profit', 'pre_tax_profit_rate', 'after_tax_profit', 'after_tax_profit_margin', 'upper_limit_profit', 'lower_limit_profit', 'upper_limit_profit_margin', 'lower_limit_profit_margin', 'pre_ws_lower_price', 'price_fd'], 'number'],
            [['part_no', 'bu', 'material', 'unite'], 'string', 'max' => 50],
            [['area', 'currency'], 'string', 'max' => 10],
            [['supplier_code', 'customer', 'payment_terms', 'trading_terms', 'min_order', 'num_area'], 'string', 'max' => 100],
            [['supplier_name_shot', 'supplier_name'], 'string', 'max' => 500],
            [['bs_model', 'quote_currency', 'quote_price', 'rmb_price', 'exchange_rate', 'delivery_address', 'model'], 'string', 'max' => 20],
            [['flag'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_no' => 'Part No',
            'area' => 'Area',
            'bu' => 'Bu',
            'material' => 'Material',
            'supplier_code' => 'Supplier Code',
            'supplier_name_shot' => 'Supplier Name Shot',
            'supplier_name' => 'Supplier Name',
            'customer' => 'Customer',
            'bs_model' => 'Bs Model',
            'quote_currency' => 'Quote Currency',
            'quote_price' => 'Quote Price',
            'rmb_price' => 'Rmb Price',
            'exchange_rate' => 'Exchange Rate',
            'effective_date' => 'Effective Date',
            'expiration_date' => 'Expiration Date',
            'delivery_address' => 'Delivery Address',
            'model' => 'Model',
            'payment_terms' => 'Payment Terms',
            'trading_terms' => 'Trading Terms',
            'unite' => 'Unite',
            'min_order' => 'Min Order',
            'currency' => 'Currency',
            'buy_price' => 'Buy Price',
            'min_price' => 'Min Price',
            'ws_upper_price' => 'Ws Upper Price',
            'ws_lower_price' => 'Ws Lower Price',
            'min_num' => 'Min Num',
            'max_num' => 'Max Num',
            'gross_profit' => 'Gross Profit',
            'gross_profit_margin' => 'Gross Profit Margin',
            'pre_tax_profit' => 'Pre Tax Profit',
            'pre_tax_profit_rate' => 'Pre Tax Profit Rate',
            'after_tax_profit' => 'After Tax Profit',
            'after_tax_profit_margin' => 'After Tax Profit Margin',
            'flag' => 'Flag',
            'num_area' => 'Num Area',
            'upper_limit_profit' => 'Upper Limit Profit',
            'lower_limit_profit' => 'Lower Limit Profit',
            'upper_limit_profit_margin' => 'Upper Limit Profit Margin',
            'lower_limit_profit_margin' => 'Lower Limit Profit Margin',
            'pre_ws_lower_price' => 'Pre Ws Lower Price',
            'price_fd' => 'Price Fd',
        ];
    }
}
