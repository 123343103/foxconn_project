<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\crm\models\CrmEmployee;

class SellerShow extends CrmEmployee
{
    public function fields(){
        $fields = parent::fields();

        // 获取销售军区
        $fields['saleArea'] = function ()
        {
            return $this->area;
        };

        // 从员工表获取销售员信息
        $fields['sellerInfo'] = function ()
        {
            return $this->staffName;
        };

        return $fields;
    }
}