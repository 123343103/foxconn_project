<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/28
 * Time: 上午 10:24
 */
namespace app\modules\ptdt\models\show;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\PartnoPrice;
use yii\data\ActiveDataProvider;
class BsProductShow extends BsProduct {

    public $type_1;
    public $type_2;
    public $type_3;
    public $type_4;
    public $type_5;
    public $type_6;

    public function fields()
    {
        $fields = parent::fields();
        $fields['type_1'] = function () {
            return isset($this->productType->parent->parent->parent->parent->parent->category_sname)?$this->productType->parent->parent->parent->parent->parent->category_sname:"";
        };
        $fields['type_2'] = function () {
            return isset($this->productType->parent->parent->parent->parent->category_sname)?$this->productType->parent->parent->parent->parent->category_sname:"";
        };
        $fields['type_3'] = function () {
            return isset($this->productType->parent->parent->parent->category_sname)?$this->productType->parent->parent->parent->category_sname:"";
        };
        $fields['type_4'] = function () {
            return isset($this->productType->parent->parent->category_sname)?$this->productType->parent->parent->category_sname:"";
        };
        $fields['type_5'] = function () {
            return isset($this->productType->parent->category_sname)?$this->productType->parent->category_sname:"";
        };
        $fields['type_6'] = function () {
            return isset($this->productType->category_sname)?$this->productType->category_sname:"";
        };
        $fields['company_name']=function(){
            return isset($this->company->company_name)?$this->company->company_name:"";
        };
        $fields['brand_name']=function(){
            return isset($this->brand->BRAND_NAME_CN)?$this->brand->BRAND_NAME_CN:"";
        };
        $fields["tp_spec"]=function(){
            return $this->attr->ATTR_NAME;
        };
        $fields['status']=function(){
            switch($this->status){
                case '0':
                    return "封存";break;
                case 1:
                    return "正常";break;
                default:
                    return "/";
                    break;
            }
        };
        $fields["pdt_level"]=function(){
            switch($this->pdt_level){
                case 1:
                    return "高";break;
                case 2:
                    return "中";break;
                case 3:
                    return "低";break;
                default:
                    return "/";break;
            }
        };
        $fields["risk_level"]=function(){
            switch($this->risk_level){
                case 0:
                    return "高";break;
                case 1:
                    return "中";break;
                case 2:
                    return "低";break;
                default:
                    return "/";break;
            }
        };
        $fields["price_info"]=function(){
            return PartnoPrice::findOne(["part_no"=>$this->pdt_no,"status"=>4]);
        };
        return $fields;
    }
}