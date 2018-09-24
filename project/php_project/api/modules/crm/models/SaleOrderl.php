<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\PdProductType;
use app\modules\crm\models\SaleOrderh;
use Yii;

/**
 * This is the model class for table "sale_orderl".
 *
 * @property string $sol_id
 * @property string $soh_id
 * @property integer $pdt_id
 * @property integer $comp_pdtid
 * @property integer $category_id
 * @property string $brandid
 * @property string $quantity
 * @property string $ntax_uprice
 * @property string $tax_uprice
 * @property string $cess
 * @property string $ntax_oamount
 * @property string $tax_oamount
 * @property string $ntax_camount
 * @property string $tax_camount
 * @property integer $whs_id
 * @property string $is_largess
 * @property string $discount
 * @property string $consignment_date
 * @property string $out_quantity
 * @property string $invoice_quantity
 * @property string $recede_quantity
 * @property string $pur_quantity
 * @property string $origin_hid
 * @property string $origin_lid
 * @property string $suttle
 * @property string $gross_weight
 * @property string $pack_type
 * @property string $sol_status
 * @property string $sol_remark
 */
class SaleOrderl extends Common
{
    const STATUS_ORDERL_DEL = '0';      //已删除
    const STATUS_ORDERL_DEFAULT = '10';      //已下单
    const STATUS_ORDERL_OUT = '20';      //已出货
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_orderl';
    }
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
            [['sol_id'], 'required'],
            [['sol_id', 'soh_id', 'pdt_id', 'comp_pdtid', 'category_id', 'brandid', 'whs_id', 'distribution', 'transport', 'origin_hid', 'origin_lid'], 'integer'],
            [['quantity', 'ntax_uprice', 'tax_uprice', 'cess', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'freight', 'discount', 'out_quantity', 'invoice_quantity', 'recede_quantity', 'pur_quantity', 'suttle', 'gross_weight'], 'number'],
            [['consignment_date'], 'safe'],
            [['is_largess', 'sol_status'], 'string', 'max' => 4],
            [['pack_type'], 'string', 'max' => 20],
            [['sol_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sol_id' => 'Sol ID',
            'soh_id' => 'Soh ID',
            'pdt_id' => 'Pdt ID',
            'comp_pdtid' => 'Comp Pdtid',
            'category_id' => 'Category ID',
            'brandid' => 'Brandid',
            'quantity' => 'Quantity',
            'ntax_uprice' => 'Ntax Uprice',
            'tax_uprice' => 'Tax Uprice',
            'cess' => 'Cess',
            'uprice_ntax_o' => 'Uprice Ntax O',
            'uprice_tax_o' => 'Uprice Tax O',
            'uprice_ntax_c' => 'Uprice Ntax C',
            'uprice_tax_c' => 'Uprice Tax C',
            'tprice_ntax_o' => 'Tprice Ntax O',
            'tprice_tax_o' => 'Tprice Tax O',
            'tprice_ntax_c' => 'Tprice Ntax C',
            'tprice_tax_c' => 'Tprice Tax C',
            'whs_id' => 'Whs ID',
            'distribution' => 'Distribution',
            'transport' => 'Transport',
            'freight' => 'Freight',
            'is_largess' => 'Is Largess',
            'discount' => 'Discount',
            'consignment_date' => 'Consignment Date',
            'out_quantity' => 'Out Quantity',
            'invoice_quantity' => 'Invoice Quantity',
            'recede_quantity' => 'Recede Quantity',
            'pur_quantity' => 'Pur Quantity',
            'origin_hid' => 'Origin Hid',
            'origin_lid' => 'Origin Lid',
            'suttle' => 'Suttle',
            'gross_weight' => 'Gross Weight',
            'pack_type' => 'Pack Type',
            'sol_status' => 'Sol Status',
            'sol_remark' => 'Sol Remark',
        ];
    }
    /*关联订单主表*/
    public function getSaleOrderh(){
        return $this->hasOne(SaleOrderh::className(),['soh_id'=>'soh_id']);
    }

    public function getCount(){
        return $this->hasOne(SaleOrderl::className(),['soh_id'=>'soh_id'])->sum('quantity');
    }

    /*关联商品表*/
    public function getProduct(){
        return $this->hasOne(BsProduct::className(),['pdt_id'=>'pdt_id']);
    }
    /*商品分类*/
    public function getCategory(){
        return $this->hasOne(PdProductType::className(),['type_id'=>'category_id']);
    }
    /*
     * 根据订单ID获取订单子表相关信息
     */
    public static function getOrderDetailInfo($id)
    {
        return self::find()
            ->select([
                self::tableName().".pdt_id",
                self::tableName().".quantity",
                 self::tableName().".gross_weight".'*'.self::tableName().'.quantity'.'as weight'
            ])
            ->where([
                self::tableName().'.soh_id' => $id
            ])
            ->asArray()
            ->one();
    }

    //获取订单信息
    public static function getOrderinfo($pdtid,$sohid)
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
                self::tableName().'.pdt_id' => $pdtid
            ])
            ->asArray()
            ->one();
    }
}