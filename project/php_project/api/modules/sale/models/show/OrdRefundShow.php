<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/20
 * Time: 9:48
 * 退款订单详细信息查询show
 */

namespace app\modules\sale\models\show;

use app\modules\sale\models\OrdRefund;

class OrdRefundShow extends OrdRefund{
    public function fields()
    {
        $fields = parent::fields();

        /*订单ID*/
        $fields['ord_id'] = function(){
            return !empty($this->ordInfo)?$this->ordInfo['ord_id']:'';
        };
        /*单据状态*/
        $fields['refund_status'] = function(){
            switch ($this->rfnd_status){
                case OrdRefund::STATUS_CANCLE_REFUND:
                    return '已取消';
                    break;
                case OrdRefund::STATUS_IN_REVIEW:
                    return '审核中';
                    break;
                case OrdRefund::STATUS_PASS_REVIEW:
                    return '审核完成';
                    break;
                case OrdRefund::STATUS_REJECT_REVIEW:
                    return '驳回';
                    break;
                case OrdRefund::STATUS_REFUND:
                    return '已退款';
                    break;
                default:
                    return '待提交';
            }
        };

        /*交易法人*/
        $fields['company_name'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->corporateCompany)?$this->ordInfo->corporateCompany["company_name"]:''):'';
        };

        /*订单类型*/
        $fields['ordType'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->ordType)?$this->ordInfo->ordType["business_value"]:''):'';
        };

        /*订单状态*/
        $fields['ordStatus'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->ordStatus)?$this->ordInfo->ordStatus["os_name"]:''):'';
        };

        /*下单日期*/
        $fields['ordDate'] = function(){
            return !empty($this->ordInfo)?$this->ordInfo['ord_date']:'';
        };

        /*客户名称*/
        $fields['custName'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->cust)?$this->ordInfo->cust["cust_sname"]:''):'';
        };

        /*客户代码*/
        $fields['custCode'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->cust)?$this->ordInfo->cust["cust_code"]:''):'';
        };

        /*公司电话*/
        $fields['custTel1'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->cust)?$this->ordInfo->cust["cust_tel1"]:''):'';
        };

        /*联系人*/
        $fields['cust_contacts'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->cust)?$this->ordInfo->cust["cust_contacts"]:''):'';
        };

        /*公司电话*/
        $fields['custTel2'] = function(){
            return !empty($this->ordInfo)?(!empty($this->ordInfo->cust)?$this->ordInfo->cust["cust_tel2"]:''):'';
        };

        /*订单负责人*/
        $fields['manager'] = function(){
            return !empty($this->manager)?$this->manager['staff_name']:'';
        };

        /*订单总金额*/
        $fields['req_tax_amount'] = function(){
            return !empty($this->ordInfo)?$this->ordInfo["req_tax_amount"]:'';
        };

        /*币别*/
        $fields['currency_mark'] = function(){
//            return !empty($this->ordInfo)?(!empty($this->ordInfo->ordValues->currency)?$this->ordInfo->ordValues->currency["bsp_svalue"]:''):'';
            switch ($this->ordInfo->currency["bsp_svalue"]){
                case 'RMB':
                    return "￥";
                    break;
                case 'UKD':
                    return "£";
                    break;
                case 'USD':
                    return "$";
                    break;
                case 'EUR':
                    return "€";
                    break;
                default:
                    return "";
            }
        };

        return $fields;
    }
}