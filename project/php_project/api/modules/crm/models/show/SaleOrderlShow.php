<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\SaleOrderl;

class SaleOrderlShow extends SaleOrderl
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['productName'] = function(){
            return $this->product['pdt_name'];
        };

        $fields['productType'] = function(){
            return $this->category['type_name'];
        };

        $fields['saleName'] = function(){
            return $this->saleOrderh->saleDelegate->staffName['staff_name'];
        };

        return $fields;
    }
}