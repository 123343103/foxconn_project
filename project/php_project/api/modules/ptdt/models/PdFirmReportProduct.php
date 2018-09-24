<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\ptdt\models\show\PdRequirementShow;
use Yii;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsCategory;
/**
 * This is the model class for table "pd_firm_report_product".
 *
 * @property integer $pfrd_id
 * @property integer $prfc_id
 * @property integer $pfr_id
 * @property integer $pdh_id
 * @property integer $firm_id
 * @property integer $pdt_no
 * @property string $product_unit
 * @property string $product_name
 * @property string $product_size
 * @property string $product_brand
 * @property string $delivery_terms
 * @property string $payment_terms
 * @property string $currency_type
 * @property string $product_price
 * @property string $price_max
 * @property string $price_min
 * @property string $price_range
 * @property string $price_average
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
 * @property string $use_device
 * @property string $finishing_process
 * @property string $process_requirements
 * @property string $pfrc_quantity
 * @property string $pfrc_price
 * @property string $pfrc_remark
 * @property string $product_level
 */
class PdFirmReportProduct extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_firm_report_product';
    }
    /*交易货币*/
    public function getUnit(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'product_unit']);
    }
    /**
     * 根據計畫ID獲取商品列表
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function  getProductById($id){
        return static::find()->joinWith("product")->where(['pd_firm_report_child.pfrc_id'=>$id])->all();
    }

    public function getProduct(){
        return $this->hasMany(PdFirmReportChild::className(),['pfrc_id'=>'pfrc_id']);
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
    /**
     * 商品定位
     */
    public function getProductLevel(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'product_level']);
    }

    public function getRequirementProduct(){
        return $this->hasOne(PdRequirementShow::className(),['pdq_id'=>'demand_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pfrc_id', 'pfr_id', 'pdh_id', 'firm_id', 'pdt_no'], 'integer'],
            [['product_unit', 'product_level'], 'string', 'max' => 255],
            [['product_name', 'product_size', 'product_brand', 'delivery_terms', 'payment_terms', 'currency_type', 'product_price', 'price_range', 'profit_margin', 'product_type_1', 'product_type_2', 'product_type_3', 'product_type_4', 'product_type_5', 'product_type_6', 'demand_id'], 'string', 'max' => 20],
            [['price_max', 'price_min', 'price_average', 'pfrc_quantity', 'pfrc_price'], 'string', 'max' => 19],
            [['product_requirement', 'product_process_requirement', 'product_quality_requirement', 'enviroment_requirement', 'use_device', 'finishing_process', 'process_requirements', 'pfrc_remark'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pfrd_id' => 'id',
            'pfrc_id' => '呈报子表',
            'pfr_id' => '談判主表ID',
            'pdh_id' => '關聯商品開發需求單ID',
            'firm_id' => '關聯廠商表ID',
            'pdt_no' => '商品料号',
            'product_unit' => '商品单位',
            'product_name' => '商品品名',
            'product_size' => '商品型號規格',
            'product_brand' => '商品品牌',
            'delivery_terms' => '交貨條件 ',
            'payment_terms' => '付款條件 ',
            'currency_type' => '交易幣別',
            'product_price' => '商品定价',
            'price_max' => '定价上限',
            'price_min' => '定价下限',
            'price_range' => '量價區間',
            'price_average' => '市場均價',
            'profit_margin' => '利潤率',
            'product_type_1' => '商品大類',
            'product_type_2' => '二階',
            'product_type_3' => '三階',
            'product_type_4' => '四',
            'product_type_5' => '五',
            'product_type_6' => '六',
            'demand_id' => '來源需求單',
            'product_requirement' => '商品要求',
            'product_process_requirement' => '制程要求',
            'product_quality_requirement' => '商品品質要求',
            'enviroment_requirement' => '環保要求',
            'use_device' => '使用设备',
            'finishing_process' => '加工制程',
            'process_requirements' => '工艺制程要求',
            'pfrc_quantity' => '數量',
            'pfrc_price' => '價格',
            'pfrc_remark' => '備註',
            'product_level' => '商品定位',
        ];
    }
}
