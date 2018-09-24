<?php
/**
 * User: F3859386
 * Date: 2016/12/7
 * Time: 15:44
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdNegotiationChild;

class PdNegotiationChildShow extends PdNegotiationChild
{
    public function fields()
    {
        $fields = parent::fields();
        //谈判结论
        $fields['bsPubdata']=function(){
            return [
                    'concluse'=>$this->concluse->bsp_svalue
            ];
        };
//        //商品类别
//        $fields['ProductType']=function(){
//            return $this->concluse->bsp_svalue;
//        };

        $fields['productPerson'] = function(){
            return [
                'name' => $this->pdProductPerson['staff_name'],
                'title' => $this->pdProductPerson['job_task'],
                'mobile' => $this->pdProductPerson['staff_mobile'],
            ];
        };
        return $fields;
    }
}