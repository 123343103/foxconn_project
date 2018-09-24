<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_inoutnotel".
 *
 * @property string $sonl_id
 * @property integer $origin_id
 * @property integer $p_bill_id
 * @property string $sonh_id
 * @property string $lbill_id
 * @property integer $category_id
 * @property integer $pdt_id
 * @property integer $comp_pdtid
 * @property string $bill_quantity
 * @property string $outnoti_qty
 * @property string $innoti_qty
 * @property string $quantity
 * @property string $pack_type
 * @property string $sonl_remark
 */
class SaleInoutnotel extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_inoutnotel';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sonl_id', 'origin_id', 'p_bill_id', 'sonh_id', 'lbill_id', 'category_id', 'pdt_id', 'comp_pdtid'], 'integer'],
            [['bill_quantity', 'outnoti_qty', 'innoti_qty', 'quantity'], 'number'],
            [['pack_type'], 'string', 'max' => 20],
            [['sonl_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sonl_id' => 'Sonl ID',
            'origin_id' => '源单ID',
            'p_bill_id' => '上级单ID',
            'sonh_id' => '出入貨通知單主表id',
            'lbill_id' => '采採購或銷售訂單子表ID',
            'category_id' => '商品分類',
            'pdt_id' => '商品基礎ID',
            'comp_pdtid' => '公司商品ID',
            'bill_quantity' => '訂單總數',
            'outnoti_qty' => '出貨通知數量',
            'innoti_qty' => '入庫通知數量',
            'quantity' => '件數',
            'pack_type' => '包裝方式',
            'sonl_remark' => '備註說明',
        ];
    }
}
