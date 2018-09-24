<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\sale\models\SaleOrderh;

class SaleOrderhShow extends SaleOrderh
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['status'] = function(){
            switch ($this->soh_status){
                case self::STATUS_CREATE:
                    return "已下单";
                break;
                case self::STATUS_OUT:
                    return "已出货";
                break;
                default:
                    return null;
            }
        };

        return $fields;
    }
}