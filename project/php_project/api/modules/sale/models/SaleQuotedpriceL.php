<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCategoryUnit;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\CategoryAttr;
use app\modules\warehouse\models\BsDeliverymethod;
use app\modules\warehouse\models\BsInvt;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\LBsInvt;
use Yii;

/**
 * This is the model class for table "sale_quotedprice_l".
 *
 * @property string $sapl_id
 * @property string $origin_id
 * @property string $p_bill_id
 * @property string $saph_id
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
 * @property string $consignment_date
 * @property string $request_date
 * @property string $gross_weight
 */
class SaleQuotedpriceL extends Common
{
    const STATUS_DELETE = 0;    // 删除
    const STATUS_CREATE = 10;   // 新增
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_quotedprice_l';
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
            [['origin_id', 'p_bill_id', 'sapl_id', 'saph_id', 'pdt_id', 'whs_id', 'origin_hid', 'distribution', 'transport', 'comp_pdtid'], 'integer'],
            [['sapl_quantity', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'out_quantity', 'cess', 'discount', 'invoice_quantity', 'cusorder_qty', 'freight', 'recede_quantity', 'suttle', 'pur_quantity', 'gross_weight'], 'number'],
            [['consignment_date', 'request_date'], 'safe'],
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
            'sapl_id' => 'ID',
            'origin_id' => '源单ID',
            'p_bill_id' => '上级单ID',
            'saph_id' => '主表 ID',
            'pdt_id' => '商品 ID',
            'sapl_quantity' => '数量',
            'uprice_ntax_o' => '單價(未税)-原幣',
            'uprice_tax_o' => '單價(含税)-原幣',
            'uprice_ntax_c' => '单价(未税)-本币',
            'uprice_tax_c' => '单价(含税)-本币',
            'tprice_ntax_o' => '总价(未税)-原币',
            'tprice_tax_o' => '总价(未税)-原币',
            'tprice_ntax_c' => '总价(未税)-本币',
            'tprice_tax_c' => '总价(含税)-本币',
            'pack_type' => '包装方式',
            'out_quantity' => '已出库数 出库单反填',
            'whs_id' => '发货仓库',
            'cess' => '税率',
            'is_largess' => '是否赠品',
            'discount' => '折扣率',
            'origin_hid' => '来源单',
            'invoice_quantity' => '已开票数 发票反填',
            'cusorder_qty' => '客户订量 客户订单反写数',
            'distribution' => '配送方式',
            'freight' => '运费',
            'transport' => '运输方式',
            'sapl_status' => '状态',
            'sapl_remark' => '备注',
            'comp_pdtid' => '公司商品ID 關聯公司商品信息表',
            'recede_quantity' => '退货数 退货单反填',
            'suttle' => '净重',
            'pur_quantity' => '采购数 采购单反填',
            'request_date' => '需求交期',
            'consignment_date' => '交期',
            'gross_weight' => '毛重',
        ];
    }

    /**
     * 关联商品表
     */
    public function getProducts()
    {
        return $this->hasOne(BsProduct::className(),['pdt_id'=>'pdt_id']);
    }
    /**
     * 关联商品类别表
     */
    public function getProductCtg()
    {
        return $this->hasOne(BsCategory::className(), ['category_id' => "bs_category_id"])->via('products');
    }
    /**
     * 关联运输方式表
     */
    public function getTransportMethod()
    {
        return $this->hasOne(BsTransport::className(), ['tran_id' => "transport"]);
    }
    /**
     * 关联配送方式表
     */
    public function getDeliveryMethod()
    {
        return $this->hasOne(BsDeliverymethod::className(), ['bdm_id' => "distribution"]);
    }
    /**
     * 关联库存表
     */
    public function getStock()
    {
        return $this->hasOne(LBsInvt::className(), ['pdt_id' => "pdt_id"]);
    }
    /**
     * 关联规格表
     */
    public function getSpecification()
    {
        return $this->hasOne(CategoryAttr::className(), ['CATEGORY_ATTR_ID' => "tp_spec"])->via('products');
    }
    /**
     * 关联出仓仓库
     */
    public function getWarehouse()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => "whs_id"]);
    }
    /**
     * 关联单位表
     */
    public function getUnit()
    {
        return $this->hasOne(BsCategoryUnit::className(), ['id' => "unit"])->via('products');
    }
}
