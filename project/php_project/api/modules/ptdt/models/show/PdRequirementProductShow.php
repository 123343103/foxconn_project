<?php
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdRequirementProduct;

/**
 *  F3858995
 * 2016/11/11
 */
class PdRequirementProductShow extends PdRequirementProduct
{
    public $typeName;   //所有
    public $levelName;

    public function fields()
    {
        $fields = parent::fields();
        //商品分类名称
        $fields['typeName'] = function () {
            if(empty($this->product_type_id)){
                return '';
            }
            return [
                $this->productType->parent->parent->parent->parent->parent->category_sname,
                $this->productType->parent->parent->parent->parent->category_sname,
                $this->productType->parent->parent->parent->category_sname,
                $this->productType->parent->parent->category_sname,
                $this->productType->parent->category_sname,
                $this->productType->category_sname,
            ];
        };
        //查看页面商品类别
        $fields['typeStr'] = function () {
            if(empty($this->product_type_id)){
                return '';
            }
            return $this->productType->parent->parent->parent->parent->parent->category_sname."</span><span class=\"ml-10\">".$this->productType->parent->parent->parent->parent->category_sname."</span><span class=\"ml-10\">".$this->productType->parent->parent->parent->category_sname."</span><span class=\"ml-10\">".$this->productType->parent->parent->category_sname."</span><span class=\"ml-10\">".$this->productType->parent->category_sname."</span><span class=\"ml-10\">".$this->productType->category_sname
            ;
        };
        //商品分类的id
        $fields['typeId'] = function ()
        {
            if(empty($this->product_type_id)){
                return '';
            }
            return [
                $this->productType->parent->parent->parent->parent->parent->category_id,
                $this->productType->parent->parent->parent->parent->category_id,
                $this->productType->parent->parent->parent->category_id,
                $this->productType->parent->parent->category_id,
                $this->productType->parent->category_id,
                $this->productType->category_id,
            ];
        };
        //商品等级
        $fields['levelName'] = function () {
            if (isset($this->productLevel)) {
                return $this->productLevel->bsp_svalue;
            } else {
                return "";
            }
        };
        //开发中心
        $fields['developCenter'] =function ()
        {
            return $this->requirement->developCenter->organization_name;
        };
        //开发部
        $fields['developDepartment'] =function ()
        {
            return $this->requirement->developDepartment->organization_name;
        };
        //commodityType
        $fields['commodityName'] =function ()
        {
            return $this->requirement->commodityType->category_sname;
        };
        //建立人
        $fields['createBy'] = function () {
            return [
                "name"=>$this->requirement->creatorStaff['staff_name'],
                "code"=>$this->requirement->creatorStaff['staff_code'],
            ];
        };
        $fields['product_level_id'] = function(){
            return $this->product_level_id?$this->product_level_id:"";
        };
        $fields['product_unit'] = function(){
            return $this->product_unit?$this->product_unit:"";
        };
        $fields['product_brand'] = function(){
            return $this->product_brand?$this->product_brand:"";
        };
        return $fields;
    }

}