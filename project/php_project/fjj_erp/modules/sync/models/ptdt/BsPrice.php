<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "bs_price".
 *
 * @property string $prt_pkid
 * @property integer $item
 * @property string $minqty
 * @property string $maxqty
 * @property string $price
 * @property string $notax_price
 * @property string $currency
 * @property integer $price_flag
 */
class BsPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_price';
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
            [['prt_pkid', 'item', 'minqty', 'price'], 'required'],
            [['prt_pkid', 'item', 'price_flag'], 'integer'],
            [['minqty', 'maxqty', 'price', 'notax_price'], 'number'],
            [['currency'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_pkid' => 'Prt Pkid',
            'item' => 'Item',
            'minqty' => 'Minqty',
            'maxqty' => 'Maxqty',
            'price' => 'Price',
            'notax_price' => 'Notax Price',
            'currency' => 'Currency',
            'price_flag' => 'Price Flag',
        ];
    }
}
