<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_sale_quotedprice_l".
 *
 * @property integer $sapl_id
 * @property integer $saph_id
 * @property string $part_no
 * @property string $brand
 * @property integer $num
 * @property integer $MOQ
 * @property string $remark
 * @property string $ws_origin_unit_price
 * @property string $origin_unit_price
 * @property string $ws_local_unit_price
 * @property string $local_unit_price
 * @property string $ws_origin_total_price
 * @property string $origin_total_price
 * @property string $ws_local_total_price
 * @property string $local_total_price
 * @property integer $order_num
 * @property integer $status
 */
class CrmSaleQuotedpriceChild extends Common
{
    const STATUS_DEL = '0';         //删除
    const STATUS_DEFAULT = '10';         //正常
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_sale_quotedprice_l';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['saph_id', 'part_no'], 'required'],
            [['saph_id', 'num', 'MOQ', 'order_num', 'status'], 'integer'],
            [['ws_origin_unit_price', 'origin_unit_price', 'ws_local_unit_price', 'local_unit_price', 'ws_origin_total_price', 'origin_total_price', 'ws_local_total_price', 'local_total_price'], 'number'],
            [['part_no', 'brand'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sapl_id' => '报价子表ID',
            'saph_id' => '报价主表ID',
            'part_no' => '商品ID',
            'brand' => '品牌',
            'num' => '数量',
            'MOQ' => 'Moq',
            'remark' => '备注',
            'ws_origin_unit_price' => '单价(未税)--原币',
            'origin_unit_price' => '单价(含税)--原币',
            'ws_local_unit_price' => '单价(未税)--本币',
            'local_unit_price' => '单价(含税)--本币',
            'ws_origin_total_price' => '总价(未税)--原币',
            'origin_total_price' => '总价(含税)--原币',
            'ws_local_total_price' => '总价(未税)--本币',
            'local_total_price' => '总价(含税)--本币',
            'order_num' => '客户定量',
            'status' => '状态',
        ];
    }

    public function getSaleQuotedprice(){
        return $this->hasOne(CrmSaleQuotedprice::className(),['saph_id'=>'saph_id']);
    }
}
