<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/22
 * Time: 下午 03:08
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\OrdLogisticLog;

class OrdLogisticLogShow extends OrdLogisticLog
{
    public function fields()
    {
        $fields = parent::fields();
        //物流单号
        $fields['orderno'] = function () {
            return $this->orderno;
        };
        //承运商代码
        $fields['forwardcode'] = function () {
            return $this->FORWARDCODE;
        };
        //快递单号
        $fields['expressno'] = function () {
            return $this->EXPRESSNO;
        };
        //站点
        $fields['station'] = function () {
            return $this->STATION;
        };
        //在途状态
        $fields['onwaystatus'] = function () {
            return $this->ONWAYSTATUS;
        };
        //状态发生时间
        $fields['onwaystatus_date'] = function () {
            return $this->ONWAYSTATUS_DATE;
        };
        //送货人员
        $fields['delivery_man'] = function () {
            return $this->DELIVERY_MAN;
        };
        //状态详情
        $fields['remark'] = function () {
            return $this->REMARK;
        };
        //配送车编号
        $fields['carrierno'] = function () {
            return $this->CARRIERNO;
        };
        //创建人
        $fields['create_by'] = function () {
            return $this->CREATE_BY;
        };
        //创建时间
        $fields['createdate'] = function () {
            return $this->CREATEDATE;
        };
        //订单号
        $fields['soh_code'] = function () {
            return $this->saleOrderh['saph_code'];
        };
        return $fields;
    }
}