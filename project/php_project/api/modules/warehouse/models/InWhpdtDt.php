<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "in_whpdt_dt".
 *
 * @property string $invl_id
 * @property string $invh_id
 * @property string $detail_id
 * @property string $part_no
 * @property string $in_quantity
 * @property string $real_quantity
 * @property string $batch_no
 * @property string $bar_code
 * @property string $inout_time
 * @property string $st_codes
 * @property string $store_num
 * @property string $pack_type
 * @property string $pack_num
 * @property string $up_date
 */
class InWhpdtDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'in_whpdt_dt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invh_id', 'detail_id', 'pack_type'], 'integer'],
            [['in_quantity', 'real_quantity', 'pack_num'], 'number'],
            [['inout_time', 'up_date'], 'safe'],
            [['part_no', 'batch_no', 'bar_code'], 'string', 'max' => 20],
            [['st_codes', 'store_num'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invl_id' => 'PK_ID',
            'invh_id' => '入库ID',
            'detail_id' => '收貨單詳情ID',
            'part_no' => '商品料号',
            'in_quantity' => '应入库数量/送货数量',
            'real_quantity' => '实入库数量/预实收数量',
            'batch_no' => '批次',
            'bar_code' => '條碼',
            'inout_time' => '入库时间',
            'st_codes' => '储位码（多个储位以，分隔）',
            'store_num' => '存放数量（多个数量以，分隔）',
            'pack_type' => '包装方式(公共字典抓取)',
            'pack_num' => '包装数量',
            'up_date' => '上架时间',
        ];
    }
}
