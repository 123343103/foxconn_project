<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\sale\models\SaleSalercost;

class SellerCostShow extends SaleSalercost
{
    public function fields(){
        $fields = parent::fields();

        // 获取店铺信息
        $fields['storeInfo'] = function ()
        {
            return $this->storeInfo;
        };

        // 从员工表获取销售员信息
        $fields['sellerInfo'] = function ()
        {
            return $this->sellerInfo;
        };
        return $fields;
    }
}