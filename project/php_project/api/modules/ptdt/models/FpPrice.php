<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "fp_price".
 *
 * @property string $part_no
 * @property string $category_id
 * @property string $pdt_manager
 * @property string $pdt_name
 * @property string $tp_spec
 * @property string $brand
 * @property string $trading_terms
 * @property string $delivery_address
 * @property string $payment_terms
 * @property string $limit_day
 * @property integer $iskz
 * @property string $unit
 * @property string $min_order
 * @property string $currency
 * @property string $min_price
 * @property string $ws_upper_price
 * @property string $ws_lower_price
 * @property string $num_area
 * @property string $market_price
 * @property string $valid_date
 * @property integer $isproxy
 * @property integer $isonlinesell
 * @property integer $risk_level
 * @property integer $istitle
 * @property string $archrival
 * @property integer $pdt_level
 * @property string $supplier_code
 * @property string $fresh_date
 * @property integer $status
 * @property string $verifydate
 * @property string $price_date
 */
class FpPrice extends \yii\db\ActiveRecord
{


    const STATUS_DEFAULT = 1; //有效
    const STATUS_DELETE = 0;    //删除

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdt.fp_price';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }


    public static function primaryKey(){
        return ['part_no'];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iskz', 'isproxy', 'isonlinesell', 'risk_level', 'istitle', 'pdt_level', 'status'], 'integer'],
            [['min_price', 'ws_upper_price', 'ws_lower_price', 'market_price'], 'number'],
            [['valid_date', 'price_date'], 'safe'],
            [['part_no'], 'string', 'max' => 30],
            [['category_id', 'pdt_manager', 'unit', 'currency'], 'string', 'max' => 20],
            [['pdt_name', 'tp_spec'], 'string', 'max' => 1000],
            [['brand', 'trading_terms'], 'string', 'max' => 50],
            [['delivery_address', 'payment_terms', 'limit_day', 'min_order', 'supplier_code'], 'string', 'max' => 100],
            [['num_area', 'fresh_date'], 'string', 'max' => 255],
            [['archrival'], 'string', 'max' => 200],
            [['verifydate'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_no' => 'Part No',
            'category_id' => 'Category ID',
            'pdt_manager' => 'Pdt Manager',
            'pdt_name' => 'Pdt Name',
            'tp_spec' => 'Tp Spec',
            'brand' => 'Brand',
            'trading_terms' => 'Trading Terms',
            'delivery_address' => 'Delivery Address',
            'payment_terms' => 'Payment Terms',
            'limit_day' => 'Limit Day',
            'iskz' => 'Iskz',
            'unit' => 'Unit',
            'min_order' => 'Min Order',
            'currency' => 'Currency',
            'min_price' => 'Min Price',
            'ws_upper_price' => 'Ws Upper Price',
            'ws_lower_price' => 'Ws Lower Price',
            'num_area' => 'Num Area',
            'market_price' => 'Market Price',
            'valid_date' => 'Valid Date',
            'isproxy' => 'Isproxy',
            'isonlinesell' => 'Isonlinesell',
            'risk_level' => 'Risk Level',
            'istitle' => 'Istitle',
            'archrival' => 'Archrival',
            'pdt_level' => 'Pdt Level',
            'supplier_code' => 'Supplier Code',
            'fresh_date' => 'Fresh Date',
            'status' => 'Status',
            'verifydate' => 'Verifydate',
            'price_date' => 'Price Date',
        ];
    }
}
