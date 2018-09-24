<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_machine".
 *
 * @property string $l_prt_pkid
 * @property integer $out_year
 * @property integer $rentals
 * @property integer $rental_unit
 * @property string $currency
 * @property string $recency
 * @property integer $deposit
 * @property integer $stock_num
 * @property integer $yn
 */
class LMachine extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_machine';
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
            [['l_prt_pkid'], 'required'],
            [['l_prt_pkid', 'out_year', 'rentals', 'rental_unit', 'deposit', 'stock_num', 'yn'], 'integer'],
            [['currency'], 'string', 'max' => 10],
            [['recency'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_prt_pkid' => 'L Prt Pkid',
            'out_year' => 'Out Year',
            'rentals' => 'Rentals',
            'rental_unit' => 'Rental Unit',
            'currency' => 'Currency',
            'recency' => 'Recency',
            'deposit' => 'Deposit',
            'stock_num' => 'Stock Num',
            'yn' => 'Yn',
        ];
    }
}
