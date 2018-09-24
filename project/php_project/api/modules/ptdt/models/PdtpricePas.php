<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "pdtprice_pas".
 *
 * @property string $pk_id
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
 * @property string $CHECK_DATE
 * @property string $pas_date
 * @property string $limit_day
 * @property string $remarks
 * @property integer $status
 */
class PdtpricePas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdtprice_pas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_no'], 'required'],
            [['effective_date', 'expiration_date', 'pas_date'], 'safe'],
            [['buy_price', 'min_price', 'ws_upper_price', 'ws_lower_price', 'min_num', 'max_num', 'gross_profit', 'gross_profit_margin', 'pre_tax_profit', 'pre_tax_profit_rate', 'after_tax_profit', 'after_tax_profit_margin', 'upper_limit_profit', 'lower_limit_profit', 'upper_limit_profit_margin', 'lower_limit_profit_margin', 'pre_ws_lower_price', 'price_fd'], 'number'],
            [['status'], 'integer'],
            [['part_no', 'bu', 'material', 'unite'], 'string', 'max' => 50],
            [['area', 'currency', 'CHECK_DATE'], 'string', 'max' => 10],
            [['supplier_code', 'customer', 'payment_terms', 'trading_terms', 'min_order', 'num_area'], 'string', 'max' => 100],
            [['supplier_name_shot', 'supplier_name'], 'string', 'max' => 500],
            [['bs_model', 'quote_currency', 'quote_price', 'rmb_price', 'exchange_rate', 'delivery_address', 'model', 'limit_day'], 'string', 'max' => 20],
            [['flag'], 'string', 'max' => 2],
            [['remarks'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pk_id' => '主键',
            'part_no' => '料號',
            'area' => '廠區',
            'bu' => '事業處',
            'material' => '材質',
            'supplier_code' => '供應商代碼',
            'supplier_name_shot' => '供應商簡稱',
            'supplier_name' => '供應商全稱',
            'customer' => '客戶',
            'bs_model' => '購銷模式',
            'quote_currency' => '報價幣別',
            'quote_price' => '報價單價--及為採購價格-未稅',
            'rmb_price' => '人民幣單價',
            'exchange_rate' => '報價幣別對RMB匯率',
            'effective_date' => '生效日期',
            'expiration_date' => '失效日期',
            'delivery_address' => '交貨地點',
            'model' => '幾種',
            'payment_terms' => '付款條件',
            'trading_terms' => '交易條件',
            'unite' => '交易單位',
            'min_order' => '最小訂購量',
            'currency' => '交易幣別',
            'buy_price' => '採購價格',
            'min_price' => '底價',
            'ws_upper_price' => '商品未稅定價上限',
            'ws_lower_price' => '商品未稅定價下限',
            'min_num' => '數量區間最小值',
            'max_num' => '數量區間最大值',
            'gross_profit' => '毛利潤',
            'gross_profit_margin' => '毛利潤率',
            'pre_tax_profit' => '稅前利潤',
            'pre_tax_profit_rate' => '稅前利潤率',
            'after_tax_profit' => '稅後利潤',
            'after_tax_profit_margin' => '稅後利潤率',
            'flag' => '是否當前定價0 否 1是',
            'num_area' => '數量區間',
            'upper_limit_profit' => '利潤上限',
            'lower_limit_profit' => '利潤下限',
            'upper_limit_profit_margin' => '利潤率上限',
            'lower_limit_profit_margin' => '利潤率下限',
            'pre_ws_lower_price' => '原商品未稅定價下限',
            'price_fd' => '價格幅度',
            'CHECK_DATE' => '生效日期(yyyy/MM/dd)',
            'pas_date' => '創建時間',
            'limit_day' => 'Limit Day',
            'remarks' => '備註',
            'status' => '審核狀態（1：待提交；2：審核中；3：審核完成；4：驳回；5：终止；）',
        ];
    }
}
