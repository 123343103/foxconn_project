<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\sale\models\SaleDetailsTemp;

class SaleDetailsTempShow extends SaleDetailsTemp
{
    public function fields(){
        $fields = parent::fields();
        $fields['storeInfo'] = function (){
            return $this->storeInfo;
        };
        return $fields;
    }
}