<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/5
 * Time: 8:41
 * 报价单查询模型
 */

namespace app\modules\sale\models\show;

use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsDistrict;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\sale\models\PriceInfo;

class PriceInfoShow extends PriceInfo{

    public function fields()
    {
        $fields =  parent::fields();

        /*订单来源*/
        $fields['origin_svalue'] = function(){
            return !empty($this->origin)?$this->origin->bsp_svalue:'';
        };
        /*订单类型*/
        $fields['business_value'] = function(){
            return !empty($this->businessType)?$this->businessType->business_value:'';
        };
        /*客户信息*/
        $fields['customer'] = function(){
            return [
                'cust_id'  => !empty($this->priceCust)?$this->priceCust->cust_id:'',
                'cust_sname' => !empty($this->priceCust)?$this->priceCust->cust_sname:'',
                'cust_apply' => !empty($this->priceCust)?(!empty($this->priceCust->creditApply->credit_amount)?$this->priceCust->creditApply->credit_amount:''):''
            ];
        };

        /*交易法人*/
        $fields['company'] = function(){
            return !empty($this->company)?$this->company->company_name:'';
        };

        /*交易模式*/
        $fields['trade_mode_sname'] = function(){
            return !empty($this->tradeMode)?$this->tradeMode->tac_sname:'';
        };

        /*交易币别*/
        $fields['currency_name'] = function(){
            return !empty($this->currency)?$this->currency->bsp_svalue:'';
        };

        /*单价价格信息表*/
        $fields['priceValue'] = function(){
            return [
                'pay_type_name' => !empty($this->payType)?$this->payType->bsp_svalue:'',    /*支付类型名称*/
                'pac_code' => !empty($this->payment)?$this->payment->pac_code:'',    /*付款方式编码*/
                'pac_sname' => !empty($this->payment)?$this->payment->pac_sname:'',    /*付款方式名称*/
            ];
        };

        /*发票类型*/
        $fields['invoice_type_name'] = function(){
            return !empty($this->invoiceType)?$this->invoiceType->bsp_svalue:'';
        };
        /*发票抬头地址*/
        $fields['invoice_Title_Addr'] = function () {
            $address_id = $this->invoice_Title_AreaID;
            $addr[] = $this->invoice_Title_Addr;
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            $addr = array_reverse($addr);
            $str = implode('',$addr);
            return $str;
        };
        /*发票邮寄地址*/
        $fields['invoice_Address'] = function () {
            $address_id = $this->distinct_id;
            $addr[] = $this->invoice_Address;
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            $addr = array_reverse($addr);
            $str = implode('',$addr);
            return $str;
        };
        /*收获地址*/
        $fields['receipt_Address'] = function () {
            $address_id = $this->receipt_areaid;
            $addr[] = $this->receipt_Address;
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            $addr = array_reverse($addr);
            $str = implode('',$addr);
            return $str;
        };
//
        /*发票抬头地址*/
//        $fields['invoice_title_addr'] = function(){
//            return !empty($this->addrTitle)?$this->addrTitle->detail_addr:'';
//        };
//
//        /*发票邮寄地址*/
//        $fields['invoice_post_addr'] = function(){
//            return !empty($this->addrPost)?$this->addrPost->detail_addr:'';
//        };
//        /*收货人信息*/
//        $fields['rvc_addr'] = function(){
//            return [
//                'addr_man' => !empty($this->rvcAddr)?$this->rvcAddr->addr_man:'',
//                'distinct_id' => !empty($this->rvcAddr)?$this->rvcAddr->distinct_id:'',
//                'detail_addr' => !empty($this->rvcAddr)?$this->rvcAddr->detail_addr:'',
//                'addr_pho' => !empty($this->rvcAddr)?$this->rvcAddr->addr_pho.'/'.$this->rvcAddr->addr_tel:'',
//            ];
//        };
//
//        /*附件信息*/
        $fields['files'] = function(){
            return $this->priceFiles;
        };
//
//        /*付款信息*/
        $fields['pay'] = function(){
            return $this->pricePays;
        };
//
//        /*付款方式 -- 额度类型*/
        $fields['credit_maintain'] = function(){
            $arr = [];
            foreach ($this->pricePays as $key => $val){
                $credit = CrmCreditMaintain::find()->select(['id','credit_name'])->where(['id'=>$val['credit_id']])->one();
                $arr[$key] = $credit;
            };
            return $arr;
        };
//
//        /*销售人员*/
        $fields['seller'] = function(){
            return !empty($this->hrStaff)?$this->hrStaff['staff_code']:'';
        };
//
//        /*币别*/
        $fields['currency_mark'] = function(){
//            return !empty($this->ordInfo)?(!empty($this->ordInfo->ordValues->currency)?$this->ordInfo->ordValues->currency["bsp_svalue"]:''):'';
            switch ($this->currency["bsp_svalue"]){
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