<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 04:27
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\BsWhPrice;

class BsWhPriceShow extends BsWhPrice
{
    public function fields()
    {
        $fields = parent::fields();
        //创建人
        $fields['cname'] = function () {
            return $this->cHrStaff['staff_name'];
        };
        //修改人
        $fields['uname'] = function () {
            return $this->uHrStaff['staff_name'];
        };

        return $fields;
    }
}