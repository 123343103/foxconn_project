<?php
/**
 * User: F1677929
 * Date: 2016/12/19
 */
namespace app\modules\app\models\show;

use app\modules\crm\models\CrmVisitRecord;

/**
 * 拜访记录显示模型
 */
class VisitInfoShow extends CrmVisitRecord
{
    public function fields()
    {
        $field = parent::fields();
        //获取客户信息
        $field['customerInfo'] = function () {
            if (empty($this->crmCustomer)) {
                return null;
            }
            return [
                'customerId' => $this->crmCustomer->cust_id,
                'customerCode' => $this->crmCustomer->cust_code,
                'customerName' => $this->crmCustomer->cust_sname,
                'customerType' => isset($this->customerType->bsp_svalue) ? $this->customerType->bsp_svalue : '',
                'customerManager' => isset($this->customerManager->manager->staff_name) ? $this->customerManager->manager->staff_name : '',
                'salesArea' => isset($this->salesArea->csarea_name) ? $this->salesArea->csarea_name : '',
                'contactPerson' => isset($this->crmCustomer->cust_contacts) ? $this->crmCustomer->cust_contacts : '',
                'contactTel' => isset($this->crmCustomer->cust_tel2) ? $this->crmCustomer->cust_tel2 : '',
                'customerAddress' => isset($this->crmCustomer->district) ?
                    $this->crmCustomer->district[0]['district_name']
                    . $this->crmCustomer->district[1]['district_name']
                    . $this->crmCustomer->district[2]['district_name']
                    . $this->crmCustomer->district[3]['district_name']
                    . $this->crmCustomer->cust_adress : '',
            ];
        };
        return $field;
    }
}