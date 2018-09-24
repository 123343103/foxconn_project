<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_testcount_l".
 *
 * @property integer $satl_id
 * @property integer $sath_id
 * @property integer $saph_id
 * @property integer $pdt_id
 * @property integer $brand_id
 * @property integer $num
 * @property integer $MOQ
 * @property string $ws_origin_quoted_price
 * @property string $origin_quoted_price
 * @property string $ws_local_quoted_price
 * @property string $local_quoted_price
 * @property string $ws_origin_total_quoted_price
 * @property string $origin_total_quoted_price
 * @property string $ws_local_total_quoted_price
 * @property string $local_total_quoted_price
 * @property string $ws_local_lower_price
 * @property string $ws_origin_lower_price
 * @property string $ws_local_upper_price
 * @property string $ws_origin_upper_price
 * @property string $local_logistics_cost
 * @property string $origin_logistics_cost
 * @property string $local_lower_price
 * @property string $origin_lower_price
 * @property string $local_upper_price
 * @property string $origin_upper_price
 * @property string $profit_margin
 * @property integer $order_num
 * @property integer $status
 * @property string $remark
 */
class SaleTestcountChild extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_testcount_l';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sath_id', 'saph_id', 'pdt_id'], 'required'],
            [['sath_id', 'saph_id', 'pdt_id', 'brand_id', 'num', 'MOQ', 'order_num', 'status'], 'integer'],
            [['ws_origin_quoted_price', 'origin_quoted_price', 'ws_local_quoted_price', 'local_quoted_price', 'ws_origin_total_quoted_price', 'origin_total_quoted_price', 'ws_local_total_quoted_price', 'local_total_quoted_price', 'ws_local_lower_price', 'ws_origin_lower_price', 'ws_local_upper_price', 'ws_origin_upper_price', 'local_logistics_cost', 'origin_logistics_cost', 'local_lower_price', 'origin_lower_price', 'local_upper_price', 'origin_upper_price', 'profit_margin'], 'number'],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'satl_id' => 'Satl ID',
            'sath_id' => 'Sath ID',
            'saph_id' => 'Saph ID',
            'pdt_id' => 'Pdt ID',
            'brand_id' => 'Brand ID',
            'num' => 'Num',
            'MOQ' => 'Moq',
            'ws_origin_quoted_price' => 'Ws Origin Quoted Price',
            'origin_quoted_price' => 'Origin Quoted Price',
            'ws_local_quoted_price' => 'Ws Local Quoted Price',
            'local_quoted_price' => 'Local Quoted Price',
            'ws_origin_total_quoted_price' => 'Ws Origin Total Quoted Price',
            'origin_total_quoted_price' => 'Origin Total Quoted Price',
            'ws_local_total_quoted_price' => 'Ws Local Total Quoted Price',
            'local_total_quoted_price' => 'Local Total Quoted Price',
            'ws_local_lower_price' => 'Ws Local Lower Price',
            'ws_origin_lower_price' => 'Ws Origin Lower Price',
            'ws_local_upper_price' => 'Ws Local Upper Price',
            'ws_origin_upper_price' => 'Ws Origin Upper Price',
            'local_logistics_cost' => 'Local Logistics Cost',
            'origin_logistics_cost' => 'Origin Logistics Cost',
            'local_lower_price' => 'Local Lower Price',
            'origin_lower_price' => 'Origin Lower Price',
            'local_upper_price' => 'Local Upper Price',
            'origin_upper_price' => 'Origin Upper Price',
            'profit_margin' => 'Profit Margin',
            'order_num' => 'Order Num',
            'status' => 'Status',
            'remark' => 'Remark',
        ];
    }
}
