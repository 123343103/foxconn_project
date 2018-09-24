<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/22
 * Time: 下午 03:08
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\OrdLogisticsShipment;

class OrdLogisticsShipmentShow extends OrdLogisticsShipment
{
    public function fields()
    {
        $fields = parent::fields();
//        //物流单号
//        $fields['orderno'] = function () {
//            return $this->ordLogisticLog['orderno'];
//        };
//        //承运商代码
//        $fields['forwardcode'] = function () {
//            return $this->ordLogisticLog['FORWARDCODE'];
//        };
//        //快递单号
//        $fields['expressno'] = function () {
//            return $this->ordLogisticLog['EXPRESSNO'];
//        };
//        //站点
//        $fields['station'] = function () {
//            return $this->ordLogisticLog['STATION'];
//        };
//        //在途状态
//        $fields['onwaystatus'] = function () {
//            return $this->ordLogisticLog['ONWAYSTATUS'];
//        };
//        //状态发生时间
//        $fields['onwaystatus_date'] = function () {
//            return $this->ordLogisticLog['ONWAYSTATUS_DATE'];
//        };
//        //送货人员
//        $fields['delivery_man'] = function () {
//            return $this->ordLogisticLog['DELIVERY_MAN'];
//        };
//        //状态详情
//        $fields['remark'] = function () {
//            return $this->ordLogisticLog['REMARK'];
//        };
//        //配送车编号
//        $fields['carrierno'] = function () {
//            return $this->ordLogisticLog['CARRIERNO'];
//        };
//        //创建人
//        $fields['create_by'] = function () {
//            return $this->ordLogisticLog['CREATE_BY'];
//        };
//        //创建时间
//        $fields['createdate'] = function () {
//            return $this->ordLogisticLog['CREATEDATE'];
//        };
//        //订单号
//        $fields['soh_code'] = function () {
//            return $this->saleOrderh['soh_code'];
//        };
        return $fields;
    }
}