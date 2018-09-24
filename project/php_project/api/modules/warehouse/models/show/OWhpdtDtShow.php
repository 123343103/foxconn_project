<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/16
 * Time: 上午 11:32
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\OWhpdtDt;

class OWhpdtDtShow extends OWhpdtDt
{
    public function fields()
    {
        $fields = parent::fields();
        //重量
//        $fields['weight'] = function () {
//            $qty=$this->out_quantity;
//            $wei=$this->gross_weight;
//            return $wei*$qty;
//        };
        //出货数量
        $fields['o_whnum']=function (){
            return $this->o_whnum;
        };
        //商品名称
        $fields['pdt_name'] = function () {
            return $this->product['pdt_name'];
        };
        //料号iid
        $fields['prt_pkid'] = function () {
            return $this->partno['prt_pkid'];
        };
        //商品料号
        $fields['part_no'] = function () {
            return $this->partno['part_no'];
        };
        //订单号
        $fields['ord_no'] = function () {
            return $this->ordinfo['ord_no'];
        };
        //订单id
        $fields['ord_id'] = function () {
            return $this->ordinfo['ord_id'];
        };
        //运输方式
        $fields['transport'] = function () {
            return $this->orddt['transport'];
        };
        //数量
        $fields['sapl_quantity'] = function () {
            return $this->orddt['sapl_quantity'];
        };
        //出货id
        $fields['o_whid'] = function () {
            return $this->oWhpk['o_whid'];
        };
        //出货详情id
        $fields['o_whdtid'] = function () {
            return $this->o_whdtid;
        };
        //物流单号
        $fields['logistics_no'] = function () {
            return $this->oWhpk['logistics_no'];
        };
        $fields['os_name']=function (){
            return $this->ordStatus['os_name'];
        };

    return $fields;
    }
}