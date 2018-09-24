<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "fp_price".
 *
 * @property string $﻿PART_NO
 * @property string $category_id
 * @property string $PDT_MANAGER
 * @property string $PDT_NAME
 * @property string $TP_SPEC
 * @property string $BRAND
 * @property string $TRADING_TERMS
 * @property string $DELIVERY_ADDRESS
 * @property string $PAYMENT_TERMS
 * @property string $LIMIT_DAY
 * @property integer $ISKZ
 * @property string $UNIT
 * @property string $MIN_ORDER
 * @property string $CURRENCY
 * @property string $MIN_PRICE
 * @property string $WS_UPPER_PRICE
 * @property string $WS_LOWER_PRICE
 * @property string $NUM_AREA
 * @property string $MARKET_PRICE
 * @property string $VALID_DATE
 * @property integer $ISPROXY
 * @property integer $ISONLINESELL
 * @property integer $RISK_LEVEL
 * @property integer $ISTITLE
 * @property string $ARCHRIVAL
 * @property integer $PDT_LEVEL
 * @property string $SUPPLIER_CODE
 * @property string $FRESH_DATE
 */
class FpPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fp_price';
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
            [['ISKZ', 'ISPROXY', 'ISONLINESELL', 'RISK_LEVEL', 'ISTITLE', 'PDT_LEVEL'], 'integer'],
            [['MIN_PRICE', 'WS_UPPER_PRICE', 'WS_LOWER_PRICE', 'MARKET_PRICE'], 'number'],
            [['VALID_DATE'], 'safe'],
            [['PART_NO'], 'string', 'max' => 30],
            [['category_id', 'PDT_MANAGER', 'UNIT', 'CURRENCY'], 'string', 'max' => 20],
            [['PDT_NAME', 'TP_SPEC'], 'string', 'max' => 1000],
            [['BRAND', 'TRADING_TERMS'], 'string', 'max' => 50],
            [['DELIVERY_ADDRESS', 'PAYMENT_TERMS', 'LIMIT_DAY', 'MIN_ORDER', 'SUPPLIER_CODE'], 'string', 'max' => 100],
            [['NUM_AREA', 'FRESH_DATE'], 'string', 'max' => 255],
            [['ARCHRIVAL'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PART_NO' => '﻿ Part  No',
            'category_id' => 'Category ID',
            'PDT_MANAGER' => 'Pdt  Manager',
            'PDT_NAME' => 'Pdt  Name',
            'TP_SPEC' => 'Tp  Spec',
            'BRAND' => 'Brand',
            'TRADING_TERMS' => 'Trading  Terms',
            'DELIVERY_ADDRESS' => 'Delivery  Address',
            'PAYMENT_TERMS' => 'Payment  Terms',
            'LIMIT_DAY' => 'Limit  Day',
            'ISKZ' => 'Iskz',
            'UNIT' => 'Unit',
            'MIN_ORDER' => 'Min  Order',
            'CURRENCY' => 'Currency',
            'MIN_PRICE' => 'Min  Price',
            'WS_UPPER_PRICE' => 'Ws  Upper  Price',
            'WS_LOWER_PRICE' => 'Ws  Lower  Price',
            'NUM_AREA' => 'Num  Area',
            'MARKET_PRICE' => 'Market  Price',
            'VALID_DATE' => 'Valid  Date',
            'ISPROXY' => 'Isproxy',
            'ISONLINESELL' => 'Isonlinesell',
            'RISK_LEVEL' => 'Risk  Level',
            'ISTITLE' => 'Istitle',
            'ARCHRIVAL' => 'Archrival',
            'PDT_LEVEL' => 'Pdt  Level',
            'SUPPLIER_CODE' => 'Supplier  Code',
            'FRESH_DATE' => 'Fresh  Date',
        ];
    }
}
