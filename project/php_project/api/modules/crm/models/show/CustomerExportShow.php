<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 14:48
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustomerInfo;

class CustomerExportShow extends CrmCustomerInfo{
    public function fields(){
        $fields = parent::fields();

        $fields['create_by'] = function(){
            return $this->buildStaff['staff_name'];
        };

        $fields['cust_type'] = function(){
            return $this->custType['bsp_svalue'];
        };
        $fields['cust_class'] = function(){
            return $this->custClass['bsp_svalue'];
        };
        $fields['cust_compvirtue'] = function(){
            return $this->compvirtue['bsp_svalue'];
        };
        $fields['cust_businesstype'] = function(){
            return $this->businessType['bsp_svalue'];
        };
        $fields['cust_industrytype'] = function(){
            return $this->industryType['idt_sname'];
        };
        $fields['cust_manager'] = function(){
            return $this->manager['staff_name'];
        };
        $fields['cust_area'] = function(){
            return $this->area['district_name'];
        };
        $fields['cust_salearea'] = function(){
            return $this->saleArea['csarea_name'];
        };
        $fields['ccper_name'] = function(){
          return $this->contactPerson['ccper_name'];
        };
        $fields['ccper_tel'] = function(){
            return $this->contactPerson['ccper_tel'];
        };
        $fields['cust_status'] = function(){
            return $this->cust_status == 10?"正常":"无效";
        };

        return $fields;
    }
}