<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\sale\models\SaleCommissionRate;

class CommissionRateShow extends SaleCommissionRate
{
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}