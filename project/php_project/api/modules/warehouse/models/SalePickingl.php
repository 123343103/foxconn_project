<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_pickingl".
 *
 * @property string $sopl_id
 * @property string $soph_id
 * @property string $bill_type
 * @property string $origin_hid
 * @property string $origin_lid
 * @property integer $category_id
 * @property integer $pdt_id
 * @property integer $comp_pdtid
 * @property string $pick_quantity
 * @property string $pur_quantity
 * @property integer $lor_id
 * @property string $貨架號
 * @property string $batch_no
 * @property string $quantity
 * @property string $pack_type
 * @property string $p_bill_hid
 * @property string $p_bill_lid
 * @property string $sopl_remark
 */
class SalePickingl extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_pickingl';
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
            [['sopl_id', 'soph_id', 'origin_hid', 'origin_lid', 'category_id', 'pdt_id', 'comp_pdtid', 'lor_id', 'p_bill_hid', 'p_bill_lid'], 'integer'],
            [['pick_quantity', 'pur_quantity', 'quantity'], 'number'],
            [['bill_type', 'batch_no', 'pack_type'], 'string', 'max' => 20],
            [['貨架號'], 'string', 'max' => 10],
            [['sopl_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sopl_id' => 'Sopl ID',
            'soph_id' => '揀貨單主表id',
            'bill_type' => '源單?????型',
            'origin_hid' => '源單主表ID 關聯採購訂單主表',
            'origin_lid' => '源單子表ID 關聯採購訂單子表',
            'category_id' => '商品分類',
            'pdt_id' => '商品基礎ID',
            'comp_pdtid' => '公司商品ID',
            'pick_quantity' => '揀貨數量',
            'pur_quantity' => '訂單總數 關聯採購訂單子表(po_orderL)',
            'lor_id' => '庫位ID 關聯倉庫貨位信息表',
            '貨架號' => '貨架號',
            'batch_no' => '批號',
            'quantity' => '件數',
            'pack_type' => '包裝方式',
            'p_bill_hid' => '上級單主表ID 一般?通知?',
            'p_bill_lid' => '上級單子表ID',
            'sopl_remark' => '備註說明',
        ];
    }
}
