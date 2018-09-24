<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsCategory;
use app\modules\ptdt\models\show\PdRequirementShow;
use Yii;
use app\modules\common\models\BsPubdata;

/**
 * 商品需求开发子表-商品表
 * F3858995
 * 2016.9.22
 * @property integer $product_id
 * @property integer $requirement_id
 * @property integer $product_index
 * @property integer $product_level_id
 * @property integer $product_main_type_id
 * @property integer $product_type_id
 * @property string $product_name
 * @property string $product_size
 * @property string $product_requirement
 * @property string $product_process_requirement
 * @property string $product_quality_requirement
 * @property string $other_des
 * @property integer $product_status
 * @property string $product_brand
 * @property string $material
 * @property string $product_unit
 * @property string $enviroment_requirement
 * @property string $use_performance_requirement
 * @property string $use_machine
 * @property string $craft_requirement
 * @property string $work_process
 * @property integer $quantity
 * @property double $price
 * @property string $vdef1
 * @property string $vdef2
 * @property string $vdef3
 * @property string $vdef4
 * @property string $vdef5
 */
class PdRequirementProduct extends Common
{

    public $type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_requirement_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_name','other_des','product_size','product_level_id','product_type_id','product_requirement','product_process_requirement','product_quality_requirement','requirement_id','product_unit'],'safe']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => '主键ID',
            'requirement_plan_id' => '需求计画ID',
            'product_index' => '序号',
            'product_level_id' => '商品定位ID',
            'product_main_type_id' => '商品大类ID',
            'product_type_id' => '商品分类ID',
            'product_name' => 'Product Name',
            'product_size' => '商品型号规格',
            'product_requirement' => 'Product Requirement',
            'product_process_requirement' => '制程要求',
            'product_quality_requirement' => '商品品质要求',
            'other_des' => '其他描述',
            'product_status' => '状态',
            'product_brand' => '商品品牌',
            'material' => '材料',
            'product_unit' => '单位',
            'enviroment_requirement' => '环保要求',
            'use_performance_requirement' => '用途与性能要求',
            'use_machine' => '使用设备',
            'craft_requirement' => '工艺制程要求',
            'work_process' => '加工製程',
            'quantity' => '数量',
            'price' => '价格',
            'vdef1' => '备用1',
            'vdef2' => '备用2',
            'vdef3' => 'Vdef3',
            'vdef4' => 'Vdef4',
            'vdef5' => 'Vdef5',
        ];
    }

    /**
     * 根据计画ID获取商品列表
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function  getProductByPlanId($id){
        return static::find()->joinWith("productType")->where(['requirement_id'=>$id])->orderBy('product_index')->all();
    }
    /**
     *关联商品类型
     */
    public function getProductType(){
        return $this->hasOne(BsCategory::className(),['category_id'=>"product_type_id"] );
    }

    //商品定位
    public function getProductLevel(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>"product_level_id"] );
    }

    //关联需求表
    public function getRequirement()
    {
        return $this->hasOne(PdRequirementShow::className(), ['pdq_id' => "requirement_id"]);
    }
}
