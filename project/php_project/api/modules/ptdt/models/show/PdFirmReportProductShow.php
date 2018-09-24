<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/21
 * Time: 19:21
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdFirmReportProduct;

class PdFirmReportProductShow extends PdFirmReportProduct{
    public $typeName;   //所有
    public $levelName;
    public function fields(){
        $fields = parent::fields();
        //交易货币
        $fields['unit'] = function(){
            return $this->unit['bsp_svalue']?$this->unit['bsp_svalue']:'';
        };
        $fields['typeName'] = function () {
            if(!empty($this->productTypeOne)){
                return [
                    $this->productTypeOne->category_sname?$this->productTypeOne->category_sname:'',
                    $this->productTypeTwo->category_sname?$this->productTypeTwo->category_sname:'',
                    $this->productTypeThree->category_sname?$this->productTypeThree->category_sname:'',
                    $this->productTypeFour->category_sname?$this->productTypeFour->category_sname:'',
                    $this->productTypeFive->category_sname?$this->productTypeFive->category_sname:'',
                    $this->productTypeSix->category_sname?$this->productTypeSix->category_sname:'',
                ];
            }else{
                return null;
            }
        };
        $fields['levelName'] = function(){
            if (isset($this->productLevel)) {
                return  $this->productLevel->bsp_svalue;
            } else {
                return null;
            }
        };
        $fields['margin'] = function(){
          $arr = explode("-",$this->profit_margin);
            return $arr;
        };
        return $fields;
    }
}