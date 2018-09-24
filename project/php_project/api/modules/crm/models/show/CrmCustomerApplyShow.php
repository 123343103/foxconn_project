<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/12/16
 * Time: 上午 09:50
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustomerApply;

class CrmCustomerApplyShow extends CrmCustomerApply {
    //public $custLevel;
        public function fields(){
            $fields = parent::fields();
            $fields['applyperson'] = function(){
                return $this->applyStaff['staff_name'];
            };
            $fields['customer'] = function (){
              return
                     $this->custCustomer;
            };
            $fields['cust_code'] = function (){
              return !empty($this->custCustomer->cust_code)?$this->custCustomer->cust_code:"";
            };
            $fields['cust_filernumber'] = function (){
                return $this->custCustomer['cust_filernumber'];
            };
            $fields['cust_sname'] = function(){
                return $this->custCustomer['cust_sname'];
            } ;
            $fields['cust_shortname'] = function (){
                return $this->custCustomer['cust_shortname'];
            };
            $fields['cust_contacts'] = function (){
              return $this->custCustomer['cust_contacts'];
            };
            $fields['cust_tel2'] = function (){
                return $this->custCustomer['cust_tel2'];
            };
            $fields['custLevel'] = function (){
                return !empty($this->custLevel1)?$this->custLevel1->custLevel['bsp_svalue']:'';
            };
            $fields['custType'] = function (){
                return !empty($this->custType)?$this->custType->custType['bsp_svalue']:'';
            };
            $fields['custArea'] = function (){
                return !empty($this->custArea)?$this->custArea->area['district_name']:'';
            };
//            $fields['custSalearea'] = function (){
//                return !empty($this->saleArea)?$this->saleArea->saleArea['csarea_name']:'';
//            };
            $fields['saleArea'] = function(){
                return $this->saleArea['csarea_name'];
            };
            $fields['cust_manager'] = function(){
                $data=[];
                foreach ($this->personinch as $per){
                    $data[]=$per->manager["staff_name"];
                }
                $data=array_filter($data);
                return implode(",",$data);
//                return $this->manager['staff_name'];
            };
            $fields['contactPerson'] = function (){
                return $this->contactPerson;
            };
            $fields['status'] = function (){
                switch ($this->status) {
                    case '10':
                        return '新增';
                    case '20':
                        return '待提交';
                    case '30':
                        return '审核中';
                    case '40':
                        return '审核完成';
                    case '50':
                        return '驳回';
                    case '60':
                        return '已取消';
                    default:
                        return '';
                }
            };

        return $fields;
    }
}