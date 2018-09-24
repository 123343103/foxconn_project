<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_purchasenotel".
 *
 * @property string $ponl_id
 * @property integer $origin_id
 * @property integer $p_bill_id
 * @property string $ponh_id
 * @property string $lbill_id
 * @property integer $category_id
 * @property integer $pdt_id
 * @property integer $comp_pdtid
 * @property string $bill_quantity
 * @property string $require_qty
 * @property string $apply_qty
 * @property string $require_date
 * @property string $sonl_remark
 */
class SalePurchasenotel extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_purchasenotel';
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
            [['ponl_id', 'origin_id', 'p_bill_id', 'ponh_id', 'lbill_id', 'category_id', 'pdt_id', 'comp_pdtid'], 'integer'],
            [['bill_quantity', 'require_qty', 'apply_qty'], 'number'],
            [['require_date'], 'safe'],
            [['sonl_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ponl_id' => 'Ponl ID',
            'origin_id' => '源单ID',
            'p_bill_id' => '上级单ID',
            'ponh_id' => '採購通知單主表id',
            'lbill_id' => '銷售訂單子表ID',
            'category_id' => '商品分類',
            'pdt_id' => '商品基礎ID',
            'comp_pdtid' => '公司商品ID',
            'bill_quantity' => '訂單總數',
            'require_qty' => '需求数量',
            'apply_qty' => '申请数量',
            'require_date' => '需求日期',
            'sonl_remark' => '備註說明',
        ];
    }
}
