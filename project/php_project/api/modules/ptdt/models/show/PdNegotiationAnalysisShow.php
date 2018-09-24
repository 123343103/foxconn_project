<?php
/**
 * User: F1678086
 * Date: 2016/11/22
 * Time: 20:25
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdNegotiationAnalysis;

class PdNegotiationAnalysisShow extends PdNegotiationAnalysis{

    public function fields()
    {
        $fields = parent::fields();
        $fields['bsPubdata'] = function (){
            return [
                //厂商配合度
                "cooperateDegree" => $this->degree['bsp_svalue'],
                //地位
                "position" => $this->position['bsp_svalue'],
                //定位
                "loction" => $this->loction['bsp_svalue'],

            ];
        };
        $fields['productType']=function()
        {
            return $this->productType['type_name'];
        };
        $fields['firm_name']=function()
        {
            return !empty($this->firm->firm_shortname)?$this->firm->firm_shortname:$this->firm->firm_sname;
        };
        return $fields;

    }
}