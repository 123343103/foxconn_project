<?php
/**
 * User: F1677929
 * Date: 2017/3/30
 */
namespace app\modules\crm\models\show;
use app\modules\crm\models\CrmVisitRecordChild;
//客户拜访记录子表显示模型
class CrmVisitRecordChildShow extends CrmVisitRecordChild
{
    public function fields()
    {
        $field=parent::fields();

        $field['plan_name'] = function(){
            return $this->visitPlan['svp_code'];
        };

        //拜访人
        $field['staff_name'] = function(){
              return $this->staff['staff_name'];
        };

        //-- START F1678086 --
        //获取拜访人
        $field['visitPerson'] = function () {
            return !empty($this->visitPerson) ? $this->visitPerson->staff_name : null;
        };
        //获取客户信息
        $field['customerInfo'] = function () {
            if(empty($this->visitInfo->crmCustomer)){
                return null;
            }
            return [
                'customerId'    => $this->visitInfo->crmCustomer->cust_id,
//                'customerCode'  => $this->visitInfo->crmCustomer->cust_code,
                'customerName'  => $this->visitInfo->crmCustomer->cust_sname,
//                'customerType'  => $this->visitInfo->customerType->bsp_svalue,
//                'customerManager'=> $this->visitInfo->customerManager->staff_name,
//                'salesArea'     => $this->visitInfo->salesArea->csarea_name,
                'contactPerson' =>$this->visitInfo->crmCustomer->cust_contacts,
                'contactTel'    =>$this->visitInfo->crmCustomer->cust_tel2,
                'customerAddress' =>$this->visitInfo->crmCustomer->cust_adress,
            ];
        };
        $field['custId'] = function () {
            if(empty($this->visitInfo->crmCustomer)){
                return null;
            }
            return $this->visitInfo->crmCustomer?$this->visitInfo->crmCustomer->cust_id:'';
        };
        $field['cust_sname'] = function () {
            if(empty($this->visitInfo->crmCustomer)){
                return null;
            }
            return $this->visitInfo->crmCustomer?$this->visitInfo->crmCustomer->cust_sname:'';
        };
        //拜访类型名称
        $field['visitTypeName'] = function(){
            return !empty($this->visitType) ? $this->visitType->bsp_svalue : null;
        };
        $field['visitTypeId'] = function(){
            return !empty($this->visitType) ? $this->visitType->bsp_id : null;
        };
        $field['start_time'] = function(){
            return $this->start?date('Y-m-d H:i',strtotime($this->start)):'';
        } ;
        $field['end_time'] = function(){
            return $this->end?date('Y-m-d H:i',strtotime($this->end)):'';
        } ;
        //获取拜访用时
        $field['visitUseTime'] = function () {
            $time = strtotime($this->end) - strtotime($this->start);
            $day = floor($time/(3600*24));
            $remainder = $time%(3600*24);
            $hour = floor($remainder/3600);
            $remainder = $remainder%3600;
            $minute = floor($remainder/60);
            $remainder = $remainder%60;
//            return $day.'天'.$hour.'小时'.$minute.'分'.$remainder.'秒';
            return $day.'天';
        };
        //获取拜访计划
        $field['visitPlan'] = function () {
            return [
                'planCode' => !empty($this->planInfo) ? $this->planInfo->svp_code : '',
                'planId' => !empty($this->planInfo) ? $this->planInfo->svp_id : '',
                'title' => !empty($this->planInfo) ? $this->planInfo->title : '',
            ];
        };
        //获取创建人
        $field['createPerson'] = function () {
            return $this->createPerson ? $this->createPerson->staff_name : null;

        };
//        $field['contactPerson']=function(){
//            return [
//                "name"=>$this->visitInfo->contactPerson['ccper_name'],
//                "tel"=>$this->visitInfo->contactPerson['ccper_tel'],
//                "id"=>$this->visitInfo->contactPerson['ccper_id'],
//            ];
//        };
        //-- END F1678086 --
        return $field;
    }
}