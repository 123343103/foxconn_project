<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/2/23
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\common\models\BsExchangeRate;

class BsExchangeRateShow extends BsExchangeRate
{
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}