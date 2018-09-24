<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsPubdata;
use Yii;
use app\modules\common\models\BsCategory;
/**
 * F3859386
 * 2016.10.22
 * 厂商谈判商品模型
 *
 * @property integer $pdnp_id
 * @property integer $pdn_id
 * @property integer $pdh_id
 * @property integer $firm_id
 * @property integer $pdt_no
 * @property string $product_name
 * @property string $product_size
 * @property string $product_brand
 * @property string $delivery_terms
 * @property string $payment_terms
 * @property string $product_unit
 * @property string $currency_type
 * @property string $price_max
 * @property string $price_min
 * @property string $price_range
 * @property string $price_average
 * @property string $product_level
 * @property string $profit_margin
 * @property string $product_type_1
 * @property string $product_type_2
 * @property string $product_type_3
 * @property string $product_type_4
 * @property string $product_type_5
 * @property string $product_type_6
 * @property string $demand_id
 * @property string $product_requirement
 * @property string $product_process_requirement
 * @property string $product_quality_requirement
 * @property string $enviroment_requirement
 * @property string $use_machine
 * @property string $work_process
 * @property string $processe_request
 * @property string $pdnp_quantity
 * @property string $pdnp_price
 * @property string $pdnp_status
 * @property string $pdnp_remark
 */
class PdNegotiationProduct extends Common
{

    const STATUS_DEL=0;
    const STATUS_DEFAULT=10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_negotiation_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdnp_id', 'pdn_id','pdnc_id', 'pdh_id', 'firm_id', 'pdt_no'], 'integer'],
            [['product_name', 'product_size', 'product_brand', 'delivery_terms', 'payment_terms', 'product_unit', 'currency_type', 'price_range', 'profit_margin', 'product_type_1', 'product_type_2', 'product_type_3', 'product_type_4', 'product_type_5', 'product_type_6', 'demand_id','pdnp_status'], 'safe'],
            [['price_max', 'price_min', 'price_average', 'product_level', 'pdnp_quantity', 'pdnp_price'], 'safe'],
            [['product_requirement', 'product_process_requirement', 'product_quality_requirement', 'enviroment_requirement', 'use_machine', 'work_process', 'craft_requirement', 'pdnp_remark'], 'string', 'max' => 120],
            [['pdnp_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdnp_id' => 'Pdnp ID',
            'pdn_id' => 'Pdn ID',
            'pdh_id' => 'Pdh ID',
            'pdnc_id' => 'Pdnc ID',
            'firm_id' => 'Firm ID',
            'pdt_no' => 'Pdt No',
            'product_name' => '商品品名',
            'product_size' => '商品型号规格',
            'product_brand' => '商品品牌',
            'delivery_terms' => '交货条件',
            'payment_terms' => '付款条件',
            'product_unit' => '商品单位',
            'currency_type' => '商品单位',
            'price_max' => '价格上限',
            'price_min' => '价格上限',
            'price_range' => '价格上限',
            'price_average' => '市场均价',
            'product_level' => '理商品定位',
            'profit_margin' => '利润率',
            'product_type_1' => '商品大类',
            'product_type_2' => 'Product Type 2',
            'product_type_3' => 'Product Type 3',
            'product_type_4' => 'Product Type 4',
            'product_type_5' => 'Product Type 5',
            'product_type_6' => 'Product Type 6',
            'demand_id' => 'Demand ID',
            'product_requirement' => '商品要求',
            'product_process_requirement' => '制程要求',
            'product_quality_requirement' => '商品品质要求',
            'enviroment_requirement' => '环保要求',
            'use_machine' => '使用设备',
            'work_process' => '加工制程',
            'craft_requirement' => '工艺製程要求',
            'pdnp_quantity' => '数量',
            'pdnp_price' => '价格',
            'pdnp_status' => 'Pdnp Status',
            'pdnp_remark' => 'Pdnp Remark',
        ];
    }

    /**
     *关联商品分类
     */
    public function getProductType(){
        return $this->hasOne(PdProductType::className(),['type_id'=>'product_type_6'] );
    }

    //商品等级
    public function getProductLevel(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'product_level']);
    }
    /*交易单位*/
    public function getProductUnit(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'product_unit']);
    }

    /*交易币种*/
    public function getCurrency(){
        return $this->hasOne(BsCurrency::className(),['cur_id'=>'currency_type']);
    }
    /**
     * 商品大類
     */
    protected function getData($type){
        return $this->hasOne(BsCategory::className(),['category_id'=>$type]);
    }
    public function getProductTypeOne(){
        return $this->getData('product_type_1');
    }
    public function getProductTypeTwo(){
        return $this->getData('product_type_2');
    }
    public function getProductTypeThree(){
        return $this->getData('product_type_3');
    }
    public function getProductTypeFour(){
        return $this->getData('product_type_4');
    }
    public function getProductTypeFive(){
        return $this->getData('product_type_5');
    }
    public function getProductTypeSix(){
        return $this->getData('product_type_6');
    }
}
