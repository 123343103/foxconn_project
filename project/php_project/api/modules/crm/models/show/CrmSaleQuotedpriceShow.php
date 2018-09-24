<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmSaleQuotedprice;

class CrmSaleQuotedpriceShow extends CrmSaleQuotedprice
{
    public function fields()
    {
        $fields = parent::fields();


        return $fields;
    }
}