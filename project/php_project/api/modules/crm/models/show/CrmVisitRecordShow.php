<?php
/**
 * User: F1677929
 * Date: 2017/3/30
 */
namespace app\modules\crm\models\show;
use app\modules\crm\models\CrmVisitRecord;
//客户拜访记录主表显示模型
class CrmVisitRecordShow extends CrmVisitRecord
{
    public function fields()
    {
        $field=parent::fields();

        //-START- F1678086
        //获取客户信息
        $field['customerInfo'] = function () {
            if(empty($this->crmCustomer)){
                return null;
            }
            return [
                'customerId'    => $this->crmCustomer->cust_id,
                'customerCode'  => $this->crmCustomer->cust_code,
                'customerName'  => $this->crmCustomer->cust_sname,
                'customerType'  => isset($this->customerType->bsp_svalue)?$this->customerType->bsp_svalue:'',
                'customerManager'  => isset($this->customerManager->manager->staff_name)?$this->customerManager->manager->staff_name:'',
                'salesArea'  => isset($this->salesArea->csarea_name)?$this->salesArea->csarea_name:'',
                'contactPerson'  => isset($this->crmCustomer->cust_contacts)?$this->crmCustomer->cust_contacts:'',
                'contactTel'  => isset($this->crmCustomer->cust_tel2)?$this->crmCustomer->cust_tel2:'',
            ];
        };
        //-END- F1678086
        return $field;
    }
}