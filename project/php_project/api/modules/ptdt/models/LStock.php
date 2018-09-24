<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_stock".
 *
 * @property string $l_stock_pkid
 * @property string $l_prt_pkid
 * @property integer $item
 * @property string $min_qty
 * @property string $max_qty
 * @property integer $stock_time
 * @property string $stock_Unit
 * @property integer $yn
 */
class LStock extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_stock';
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
            [['l_prt_pkid', 'item', 'stock_time', 'yn'], 'integer'],
            [['min_qty', 'max_qty'], 'number'],
            [['stock_Unit'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_stock_pkid' => 'L Stock Pkid',
            'l_prt_pkid' => 'L Prt Pkid',
            'item' => 'Item',
            'min_qty' => 'Min Qty',
            'max_qty' => 'Max Qty',
            'stock_time' => 'Stock Time',
            'stock_Unit' => 'Stock  Unit',
            'yn' => 'Yn',
        ];
    }
}
