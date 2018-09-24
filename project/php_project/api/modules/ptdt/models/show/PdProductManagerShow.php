<?php
namespace app\modules\ptdt\models\show;

use app\modules\common\models\BsCategory;
use app\modules\ptdt\models\PdProductManager;
use app\modules\ptdt\models\PdRequirementProduct;

/**
 * F3858995
 * 2016/11/14
 */
    class PdProductManagerShow extends PdProductManager
{
    public $pmName;
    public $parentName;
    public $type;
    public $staffId;

    public function fields(){
        $fields = parent::fields();
        $fields['pmName'] =function(){
            return $this->staff['staff_name'];
        };
        $fields['parentName'] = function (){
            if(isset($this->parent->staff)){
                return $this->parent->staff->staff_name;
            }else{
                return "/";
            }
        };
        $fields['position']=function(){
            return isset($this->staff->title->title_name)?$this->staff->title->title_name:"/";
        };
        $fields['type'] =function (){
            return self::$levelOption[$this->pm_level];
        };
        $fields['staffId'] =function (){
            return $this->staff['staff_id'];
        };
        $fields["staff_name"]=function(){
            return isset($this->staff->staff_name)?$this->staff->staff_name:"/";
        };
        $fields['updator']=function(){
            return isset($this->updator->staff_name)?$this->updator->staff_name:"/";
        };

//        //商品分类
        $fields['typeName'] = function ()
        {
            $catArr=explode(",",$this->category_id);
            $cats=BsCategory::find()->where(["in","catg_id",$catArr])->asArray()->all();
            $catNameArr=array_filter(array_column($cats,"catg_name"));
            if(!count($catNameArr)){
                return "/";
            }
            return implode(",",$catNameArr);
        };
//        //商品级别
//        $fields['productLevel']= function ()
//        {
//            return $this->productLevel->bsp_svalue;
//        };
        return $fields;
    }
}