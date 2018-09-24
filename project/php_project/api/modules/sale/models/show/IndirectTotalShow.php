<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\sale\models\SaleSalercost;

class IndirectTotalShow extends SaleSalercost
{
    public function fields(){
//        $fields = parent::fields();
        $fields['indirectTotal'] = function(){
            return $this->indirectTotal;
        };
//
        return $fields;
    }
}