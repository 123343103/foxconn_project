<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/8/7
 * Time: 下午 04:54
 */

namespace app\modules\sale\models\show;


use app\modules\sale\models\BsBankInfo;

class BsBankInfoShow extends BsBankInfo
{
    public function fields()
    {

        $fields = parent::fields();
        // 订单
        $fields['order_no'] = function () {
            return $this->rBankOrders['order_no'];
        };
        $fields['ACCOUNTS'] = function () {

            return $this->BNK_NME . "-" . $this->ACCOUNTS;
        };

        return $fields;

    }
}