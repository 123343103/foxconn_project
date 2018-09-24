<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\sale\models\SaleDetails;

class SaleDetailsShow extends SaleDetails
{
    public function fields(){
        $fields = parent::fields();
//        $fields['custInfo'] = function(){
//            return $this->custInfo;
//        };
//        $fields['seller'] = function (){
//            return $this->staff;
//        };
        $fields['storeInfo'] = function (){
            return $this->storeInfo;
        };

        $fields['changeCost'] = function (){
            return $this->changeCost;
        };

        // 某销售员当期销单总金额
        $fields['amountSum'] = function () {
            return $this->amountSum;
        };

        // 某销售员当期销单总成本
        $fields['costSum'] = function () {
            return $this->costSum;
        };

        // 获取销售点状态名
        $fields['saleTypeName']=function(){
            switch ($this->sale_type) {
                case self::NEIGOUNEIXIAO :
                    return '内购内销';
                    break;
                case self::NEIGOUWAIXIAO :
                    return '内购外销';
                    break;
                case self::WAIGOUNEIXIAO :
                    return '外购内销';
                    break;
                case self::WAIGOUWAIXIAO :
                    return '外购外销';
                    break;
                default :
                    return null;
            }
        };

        return $fields;
    }
}