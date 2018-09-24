<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCategoryUnit;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\CategoryAttr;
use app\modules\warehouse\models\BsDeliverymethod;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\LBsInvt;

/**
 * This is the model class for table "req_dt".
 *
 * @property string $req_dt_id
 * @property string $req_id
 * @property string $prt_pkid
 * @property integer $is_gift
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
 * @property string $whs_id
 * @property string $cess
 * @property string $discount
 * @property string $distribution
 * @property string $tax_freight
 * @property string $freight
 * @property integer $transport
 * @property integer $sapl_status
 * @property string $suttle
 * @property string $gross_weight
 * @property string $request_date
 * @property string $consignment_date
 * @property string $sapl_remark
 *
 * @property ReqInfo $req
 */
class ReqDt extends Common
{
    const STATUS_DELETE = 0;    // 删除
    const STATUS_CREATE = 10;   // 新增

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_dt';
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
            [['req_id', 'prt_pkid', 'is_gift', 'whs_id', 'distribution', 'transport', 'sapl_status'], 'integer'],
            [['sapl_quantity', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'cess', 'discount', 'tax_freight', 'freight', 'suttle', 'gross_weight'], 'number'],
            [['request_date', 'consignment_date'], 'safe'],
            [['pack_type'], 'string', 'max' => 20],
            [['sapl_remark'], 'string', 'max' => 200],
            [['req_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReqInfo::className(), 'targetAttribute' => ['req_id' => 'req_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_dt_id' => 'Req Dt ID',
            'req_id' => 'Req ID',
            'prt_pkid' => 'Prt Pkid',
            'is_gift' => 'Is Gift',
            'sapl_quantity' => 'Sapl Quantity',
            'uprice_ntax_o' => 'Uprice Ntax O',
            'uprice_tax_o' => 'Uprice Tax O',
            'uprice_ntax_c' => 'Uprice Ntax C',
            'uprice_tax_c' => 'Uprice Tax C',
            'tprice_ntax_o' => 'Tprice Ntax O',
            'tprice_tax_o' => 'Tprice Tax O',
            'tprice_ntax_c' => 'Tprice Ntax C',
            'tprice_tax_c' => 'Tprice Tax C',
            'pack_type' => 'Pack Type',
            'whs_id' => 'Whs ID',
            'cess' => 'Cess',
            'discount' => 'Discount',
            'distribution' => 'Distribution',
            'tax_freight' => 'Tax Freight',
            'freight' => 'Freight',
            'transport' => 'Transport',
            'sapl_status' => 'Sapl Status',
            'suttle' => 'Suttle',
            'gross_weight' => 'Gross Weight',
            'request_date' => 'Request Date',
            'consignment_date' => 'Consignment Date',
            'sapl_remark' => 'Sapl Remark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReq()
    {
        return $this->hasOne(ReqInfo::className(), ['req_id' => 'req_id']);
    }

    /**
     * 关联商品表
     */
    public function getProducts()
    {
        return $this->hasOne(BsProduct::className(), ['pdt_id' => 'pdt_id']);
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
        return $this->hasOne(BsTransport::className(), ['tran_id' => 'transport']);
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
