<?php
/**
 * User: F3859386
 * Date: 2016/12/6
 * Time: 19:31
 */

namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdNegotiationProduct;

class PdNegotiationProductShow extends PdNegotiationProduct
{
    public $typeName;   //所有
    public $levelName;
    public function fields(){
        $fields = parent::fields();
        //分级分类
        $fields['typeName'] = function () {
            if(!empty($this->productTypeOne)){
                return [
                    !empty($this->productTypeOne->category_sname)?$this->productTypeOne->category_sname:'',
                    !empty($this->productTypeTwo->category_sname)?$this->productTypeTwo->category_sname:'',
                    !empty($this->productTypeThree->category_sname)?$this->productTypeThree->category_sname:'',
                    !empty($this->productTypeFour->category_sname)?$this->productTypeFour->category_sname:'',
                    !empty($this->productTypeFive->category_sname)?$this->productTypeFive->category_sname:'',
                    !empty($this->productTypeSix->category_sname)?$this->productTypeSix->category_sname:'',
                ];
            }else{
                return null;
            }
        };
        //代理商品定位
        $fields['levelName'] = function(){
                return  !empty($this->productLevel->bsp_svalue)?$this->productLevel->bsp_svalue:'';
        };
        /*交易单位*/
        $fields['unit'] = function(){
            return !empty($this->productUnit->bsp_svalue)?$this->productUnit->bsp_svalue:'';
        };
        /*交易币别*/
        $fields['currency'] = function(){
            return !empty($this->currency->cur_sname)?$this->currency->cur_sname:'';
        };
        $fields['margin'] = function(){
            $arr = explode("-",$this->profit_margin);
            return $arr;
        };
        return $fields;
    }
}