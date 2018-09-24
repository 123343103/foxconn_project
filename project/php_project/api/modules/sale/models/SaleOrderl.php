<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_orderl".
 *
 * @property string $sol_id
 *  * @property integer $origin_id
 * @property integer $p_bill_id
 * @property string $soh_id
 * @property integer $pdt_id
 * @property string $sapl_quantity
 * @property string $uprice_ntax_o
 * @property string $uprice_tax_o
 * @property string $uprice_ntax_c
 * @property string $uprice_tax_c
 * @property string $tprice_ntax_o
 * @property string $tprice_tax_o
 * @property string $tprice_ntax_c
 * @property string $tprice_tax_c
 * @property string $pur_note_status
 * @property string $pur_note_qty
 * @property string $out_note_status
 * @property string $out_note_qty
 * @property string $pack_type
 * @property string $out_quantity
 * @property integer $whs_id
 * @property string $cess
 * @property string $is_largess
 * @property string $discount
 * @property string $origin_hid
 * @property string $invoice_quantity
 * @property string $cusorder_qty
 * @property integer $distribution
 * @property string $freight
 * @property integer $transport
 * @property string $sapl_status
 * @property string $sapl_remark
 * @property integer $comp_pdtid
 * @property string $recede_quantity
 * @property string $suttle
 * @property string $pur_quantity
 * @property string $request_date
 * @property string $consignment_date
 * @property string $gross_weight
 */
class SaleOrderl extends Common
{
    const STATUS_DELETE = 0;    // 删除
    const STATUS_CREATE = 10;   // 新增
    const NOTE_NO       = '1';    // 未发送
    const NOTE_PART     = '2';    // 部分发送
    const NOTE_ALL      = '3';    // 已发送
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_orderl';
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
            [['soh_id'], 'required'],
            [['origin_id', 'p_bill_id', 'soh_id', 'part_no', 'whs_id', 'origin_hid', 'distribution', 'transport', 'comp_pdtid'], 'integer'],
            [['sapl_quantity', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'pur_note_qty', 'out_note_qty', 'out_quantity', 'cess', 'discount', 'invoice_quantity', 'cusorder_qty', 'freight', 'recede_quantity', 'suttle', 'pur_quantity', 'gross_weight'], 'number'],
            [['request_date', 'consignment_date'], 'safe'],
            [['pur_note_status', 'out_note_status'], 'string', 'max' => 2],
            [['pack_type'], 'string', 'max' => 20],
            [['is_largess', 'sapl_status'], 'string', 'max' => 4],
            [['sapl_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sol_id' => 'Sol ID',
            'origin_id' => '源单ID',
            'p_bill_id' => '上级单ID',
            'soh_id' => '订单主表ID',
            'part_no' => '料号',
            'sapl_quantity' => '数量',
            'uprice_ntax_o' => '单价（未税）原币',
            'uprice_tax_o' => '单价（已税）原币',
            'uprice_ntax_c' => '单价（未税）本币',
            'uprice_tax_c' => '单价（已税）本币',
            'tprice_ntax_o' => '总价（未税）原币',
            'tprice_tax_o' => '总价（已税）原币',
            'tprice_ntax_c' => '总价（未税）本币',
            'tprice_tax_c' => '总价（已税）本币',
            'pur_note_status' => '通知出货状态 1:未发送通知  2：部分发送   3:已经发送通知',
            'pur_note_qty' => '采购通知数量',
            'out_note_status' => '通知出货状态 1:未发送通知  2：部分发送   3:已经发送通知',
            'out_note_qty' => '通知出货数量',
            'pack_type' => '包装方式',
            'out_quantity' => '已出库数',
            'whs_id' => '发货仓库',
            'cess' => '税率',
            'is_largess' => '是否赠品',
            'discount' => '折扣率',
            'origin_hid' => '来源单ID',
            'invoice_quantity' => '已开票数',
            'cusorder_qty' => '客户订量',
            'distribution' => '配送方式',
            'freight' => '运费',
            'transport' => '运输方式',
            'sapl_status' => '状态 默认10新增',
            'sapl_remark' => '备注',
            'comp_pdtid' => '公司商品ID',
            'recede_quantity' => '退货数',
            'suttle' => '净重',
            'pur_quantity' => '采购数量',
            'request_date' => '需求交期',
            'consignment_date' => '交期',
            'gross_weight' => '毛重',
        ];
    }


    /*
    * 根据订单ID获取订单子表相关信息
    */
    public static function getOrderDetailInfo($id)
    {
        return self::find()
            ->select([
                self::tableName().".part_no",
                self::tableName().".sapl_quantity",
                self::tableName().".gross_weight".'*'.self::tableName().'.sapl_quantity'.'as weight'
            ])
            ->where([
                self::tableName().'.soh_id' => $id
            ])
            ->asArray()
            ->one();
    }

    //获取订单信息
    public static function getOrderinfo($partno,$sohid)
    {
        return self::find()
            ->select([
                self::tableName().".soh_id",
                self::tableName().".sol_id",
                self::tableName().".freight"
            ])
            ->where([
                self::tableName().'.soh_id' => $sohid
            ])->where([
                self::tableName().'.part_no' => $partno
            ])
            ->asArray()
            ->one();
    }
}
