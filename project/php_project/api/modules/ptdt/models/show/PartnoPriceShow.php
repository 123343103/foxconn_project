<?php
/**
 * User: F1676624
 * Date: 2016/12/6
 */
namespace  app\modules\ptdt\models\show;

use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTradConditions;
use app\modules\crm\models\CrmSalearea;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\fpPartNo;
use app\modules\ptdt\models\fpPas;
use app\modules\hr\models\HrStaff;

class PartnoPriceShow extends PartnoPrice
{
    public $pdt_name;
    public $tp_spec;
    public $type_1;
    public $type_2;
    public $type_3;
    public $type_4;
    public $type_5;
    public $type_6;
    public function fields()
    {
        $fields = parent::fields();
        $fields['product'] = function () {
            return $this->product;
        };
        $fields["payment_terms"]=function(){
            $payment_terms=BsPayCondition::find()->select("pat_sname")->where(["pat_id"=>$this->payment_terms])->scalar();
            return $payment_terms?$payment_terms:"";
        };
        $fields["trading_terms"]=function(){
            $trading_terms=BsTradConditions::find()->select("tcc_sname")->where(["tcc_id"=>$this->trading_terms])->scalar();
            return $trading_terms?$trading_terms:"";
        };
        $fields['unit']=function(){
            $unit=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->unit])->scalar();
            return $unit?$unit:"";
        };
        $fields['currency']=function(){
            $currency=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->currency])->scalar();
            return $currency?$currency:"";
        };
        $fields["brand"]=function(){
            return $this->product->brand->BRAND_NAME_CN;
        };
        $fields["unit"]=function(){
            return $this->product->unite->unit_name?$this->product->unite->unit_name:"";
        };
        $fields["tp_spec"]=function(){
            return $this->product->attr->ATTR_NAME;
        };
        $fields["salearea"]=function(){
            if(!isset($this->product->sale_area)){
                return "";
            }
            if($this->product->sale_area==-1){
                return "全国";
            }
            if($this->product->sale_area==1){
                return "全国(不包含港澳台)";
            }
            $district=BsDistrict::findOne(["district_id"=>$this->product->sale_area]);
            return $district['district_name'];
        };
        $fields['type_6']=function(){
            return isset($this->product->productType->category_sname)?$this->product->productType->category_sname:"";
        };
        $fields['type_5']=function(){
            return isset($this->product->productType->parent->category_sname)?$this->product->productType->parent->category_sname:"";
        };
        $fields['type_4']=function(){
            return isset($this->product->productType->parent->parent->category_sname)?$this->product->productType->parent->parent->category_sname:"";
        };
        $fields['type_3']=function(){
            return isset($this->product->productType->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->category_sname:"";
        };
        $fields['type_2']=function(){
            return isset($this->product->productType->parent->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->parent->category_sname:"";
        };
        $fields['type_1']=function(){
            return isset($this->product->productType->parent->parent->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->parent->parent->category_sname:"";
        };
        $fields['pas']=function(){
            return $this->pas;
        };
        $fields['creator']=function(){
            return $this->creator;
        };
        return $fields;
    }


}