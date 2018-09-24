<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/9
 * Time: 上午 09:26
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\LqtLand;

class LqtLandShow extends LqtLand
{
    public function fields()
    {
        $fields = parent::fields();
        //报价单号
        $fields['lqt_no'] = function () {
            return $this->lqtno['lqt_no'];
        };
        $fields['lqt_id'] = function () {
            return $this->lqt_id;
        };
        //报价币别
        $fields['currency'] = function () {
            return $this->currency;
        };
        //费用项目
        $fields['itemcname']=function (){
            return $this->itemcname;
        };
        //计费单位
        $fields['uom']=function (){
            return $this->uom;
        };

        //价格
        $fields['rate']=function (){
            return $this->rate;
        };
        //车种
        $fields['truckgroup']=function (){
            return $this->truckgroup;
        };
        //收费区间
        $fields['cost_range']=function (){
            return $this->minicharge."-".$this->maxcharge;
        };
        //车型
        $fields['trucktype']=function (){
            return $this->trucktype;
        };

        return $fields;
    }
}