<?php
namespace app\modules\sale\models\show;
use app\modules\sale\models\SaleQuotedpriceH;

class SaleQuotedpriceHShow extends SaleQuotedpriceH
{
    public function fields()
    {
        $fields =  parent::fields();
        // 订单类型
        $fields['saph_type'] = function () {
            return $this->orderType['business_value'];
        };
        // 客户名称
        $fields['cust_sname'] = function () {
            return $this->customerInfo['cust_sname'];
        };
        // 客户代码
        $fields['cust_code'] = function () {
            return $this->customerInfo['cust_code'];
        };
        // 交易法人
        $fields['corporate'] = function () {
            return $this->corporateCompany['company_name'];
        };
        // 客户经理人
        $fields['customerManager'] = function () {
            return $this->customerManagerName['staff_name'];
        };
//        $fields['custInfo'] = function () {
//            return $this->customerInfo;
//        };

        $fields['createBy'] = function () {
            return $this->creatorStaff['staff_name'];
        };
        $fields['updateBy'] = function () {
            return $this->updateStaff['staff_name'];
        };


        $fields['saph_status'] = function () {
            switch ($this->saph_status) {
                case self::STATUS_CREATE:
                    return '待报价';
                    break;
                case self::STATUS_WAIT:
                    return '报价中';
                    break;
                case self::STATUS_CHECKING:
                    return '报价中';
                    break;
                case self::STATUS_FINISH:
                    return '已报价';
                    break;
                case self::STATUS_PREPARE:
                    return '报价驳回';
                    break;
                case self::STATUS_CANCEL:
                    return '已取消';
                    break;
                default;
                    return '';
            }
        };
//        $fields['applyno'] = function () {
//            return $this->customerApply['applyno'];
//        };
        return $fields;
    }
}