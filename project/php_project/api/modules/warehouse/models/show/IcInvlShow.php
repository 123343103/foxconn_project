<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/23
 * Time: 上午 11:01
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\IcInvl;

class IcInvlShow extends IcInvl
{
    public function fields()
    {
        $fields = parent::fields();
        //商品数量
//        $fields['quantity'] = function () {
//            return $this->saleOrderl['quantity'];
//        };
        //重量
        $fields['weight'] = function () {
            $qty=$this->out_quantity;
            $wei=$this->gross_weight;
            return $wei*$qty;
        };
        //商品数量
        $fields['outqty']=function (){
            return (int)$this->out_quantity;
        };
        //出货数量
        $fields['realqty']=function (){
            return (int)$this->real_oquantity;
        };
        //商品名称
        $fields['productname'] = function () {
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
        //订单状态
//        $fields['soh_status'] = function () {
//            //return $this->saleOrderh['soh_status'];
//            return "已出货";
//        };
        //订单号
        $fields['soh_code'] = function () {
            return $this->saleOrderh['saph_code'];
        };
        //订单id
        $fields['soh_id'] = function () {
            return $this->saleOrderh['soh_id'];
        };
        //订单详情id
        $fields['sol_id'] = function () {
            return $this->saleOrderl['sol_id'];
        };
        //运输方式
        $fields['tranmode'] = function () {
            return $this->bsTr['tran_sname'];
        };
        //出货id
        $fields['invh_id'] = function () {
            return $this->icInvh['invh_id'];
        };
        //出货详情id
        $fields['invl_id'] = function () {
            return $this->invl_id;
        };
        //物流单号
        $fields['logistics_no'] = function () {
            return $this->icInvh['logistics_no'];
        };
        return $fields;
    }
}