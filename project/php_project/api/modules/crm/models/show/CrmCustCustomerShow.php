<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/28
 * Time: 15:19
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustCustomer;

class CrmCustCustomerShow extends CrmCustCustomer
{
    public function fields()
    {
        $fields = parent::fields();
        /*付款条件*/
        $fields['caluse'] = function(){
            return $this->caluseCode['bnt_sname'];
        };
        /*经营类型*/
        $fields['customer_type'] = function(){
            return $this->customerType?$this->customerType['bsp_svalue']:'';
        };

        return $fields;
    }
}