<?php
/**
 * User: F1676624
 * Date: 2016/12/6
 */
namespace  app\modules\app\models\show;

use app\modules\ptdt\models\PartnoPrice;

class PriceShow extends PartnoPrice
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
        $fields['type_6']=function(){
            return  isset($this->product->productType->category_sname)?$this->product->productType->category_sname:"";
        };
        $fields['type_5']=function(){
            return  isset($this->product->productType->parent->category_sname)?$this->product->productType->parent->category_sname:"";
        };
        $fields['type_4']=function(){
            return  isset($this->product->productType->parent->parent->category_sname)?$this->product->productType->parent->parent->category_sname:"";
        };
        $fields['type_3']=function(){
            return  isset($this->product->productType->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->category_sname:"";
        };
        $fields['type_2']=function(){
            return  isset($this->product->productType->parent->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->parent->category_sname:"";
        };
        $fields['type_1']=function(){
            return  isset($this->product->productType->parent->parent->parent->parent->parent->category_sname)?$this->product->productType->parent->parent->parent->parent->parent->category_sname:"";
        };
        $fields['supplier']=function(){
            return  $this->pdSupplier;
        };
        $fields['creator']=function(){
            return $this->creator;
        };
        return $fields;
    }


}