<?php
namespace app\modules\crm\models\show;

use app\modules\ptdt\models\PdRequirementProduct;

/**
 *  F3858995
 * 2016/11/11
 */
class PdRequirementProductShow extends PdRequirementProduct
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['customer_num'] = function(){
            return $this->requirement['customer_num'];
        };
        $fields['sale_pos'] = function(){
            return $this->requirement['sale_pos'];
        };
        $fields['code'] = function(){
            return $this->requirement['pdq_code'];
        };

        return $fields;
    }

}