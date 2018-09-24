<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/2/10
 * Time: 下午 04:44
 */

namespace app\modules\sale\models\show;

use app\modules\sale\models\SaleDetailsSum;

class SaleDetailsSumShow extends SaleDetailsSum
{
    public function fields()
    {
        $fields = parent::fields();
        // 店铺信息
        $fields['storeInfo'] = function (){
            return $this->storeInfo;
        };
        // 角色信息
        $fields['roleInfo'] = function (){
            return $this->roleInfo;
        };
        // 提成4
        $fields['ticheng4'] = function (){
            return $this->ticheng4;
        };
        // 提成3
        $fields['ticheng3'] = function (){
            return $this->ticheng3;
        };
        // 提成2
        $fields['ticheng2'] = function (){
            return $this->ticheng2;
        };
        // 提成1
        $fields['ticheng1'] = function (){
            return $this->ticheng1;
        };
        return $fields;
    }
}