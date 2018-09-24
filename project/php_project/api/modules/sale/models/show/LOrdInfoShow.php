<?php
/**
 * Created by PhpStorm.
 * User: F3860942
 * Date: 2017/8/7
 * Time: 下午 04:54
 */

namespace app\modules\sale\models\show;


use app\modules\common\models\BsDistrict;
use app\modules\sale\models\LOrdInfo;

class LOrdInfoShow extends LOrdInfo
{
    public function fields()
    {

        $fields = parent::fields();
        // 订单
        $fields['invoice_type'] = function () {
            return $this->invoiceType["bsp_svalue"];
        };
        $fields['curr_code'] = function () {
            return $this->currency["bsp_svalue"];
        };
        $fields['files'] = function () {
            return $this->ordFiles;
        };
        $fields['trade_mode'] = function () {
            return $this->tradeMode["tac_sname"];
        };
        $fields['company_name'] = function () {
            return $this->corporateCompany["company_name"];
        };
        $fields['ordType'] = function () {
            return $this->ordType["business_value"];
        };
        $fields['ordStatus'] = function () {
            return $this->ordStatus['os_name'];     //订单状态
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
            $str = implode('', $addr);
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
            $str = implode('', $addr);
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
            $str = implode('', $addr);
            return $str;
        };
        /*客户信息*/
        $fields['customer'] = function () {
            return [
                'cust_id' => !empty($this->cust) ? $this->cust->cust_id : '',
                'cust_sname' => !empty($this->cust) ? $this->cust->cust_sname : '',
            ];
        };
        return $fields;

    }
}