<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmSaleQuotedpriceChild;

class CrmSaleQuotedpriceChildShow extends CrmSaleQuotedpriceChild
{
    public function fields()
    {
        $fields = parent::fields();

//        $fields['code'] = function(){
//            return $this->saleQuotedprice['saph_no'];
//        };
        $fields['date'] = function(){
            return $this->saleQuotedprice['saph_date'];
        };
        return $fields;
    }
}