<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/23
 * Time: 19:40
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmVisitRecord;

class CrmReturnVisitShow extends CrmVisitRecord
{
    public function fields()
    {
        $fields = parent::fields();
        /*公司名称*/
        $fields['cust_sname'] = function(){
            return isset($this->crmCustomer['cust_sname'])?$this->crmCustomer['cust_sname']:"";
        };
        $fields['cust_id'] = function(){
            return isset($this->crmCustomer['cust_id'])?$this->crmCustomer['cust_id']:"";
        };
        $fields['cust_filernumber'] = function(){
            return isset($this->crmCustomer['cust_filernumber'])?$this->crmCustomer['cust_filernumber']:"";
        };
        /*公司简称*/
        $fields['cust_shortname'] = function(){
            return isset($this->crmCustomer['cust_shortname'])?$this->crmCustomer['cust_shortname']:"";
        };
        /*联系人姓名*/
        $fields['cust_contacts'] = function(){
            return isset($this->crmCustomer['cust_contacts'])?$this->crmCustomer['cust_contacts']:"";
        };
        /*联系人职位*/
        $fields['cust_position'] = function(){
            return isset($this->crmCustomer['cust_position'])?$this->crmCustomer['cust_position']:"";
        };
        /*联系人手机*/
        $fields['cust_tel2'] = function(){
            return isset($this->crmCustomer['cust_tel2'])?$this->crmCustomer['cust_tel2']:"";
        };
        /*邮箱*/
        $fields['cust_email'] = function(){
            return isset($this->crmCustomer['cust_email'])?$this->crmCustomer['cust_email']:"";
        };
        /*经营范围说明*/
        $fields['member_businessarea'] = function(){
            return isset($this->crmCustomer['member_businessarea'])?$this->crmCustomer['member_businessarea']:"";
        };
        /*公司所在地区*/
        $fields['salearea'] = function(){
            return isset($this->salesArea['csarea_name'])?$this->salesArea['csarea_name']:"";
        };
        /*经营范围*/
        $fields['businessType'] = function(){
            return isset($this->crmCustomer->businessType['bsp_svalue'])?$this->crmCustomer->businessType['bsp_svalue']:"";
        };
        /*客户来源*/
        $fields['memberSource'] = function(){
            return isset($this->crmCustomer->custSource['bsp_svalue'])?$this->crmCustomer->custSource['bsp_svalue']:"";
        };
        /*潜在需求*/
        $fields['latDemand'] = function(){
            return isset($this->crmCustomer->latDemand['bsp_svalue'])?$this->crmCustomer->latDemand['bsp_svalue']:"";
        };
        /*需求类目*/
        $fields['categoryName']=function(){
            return isset($this->crmCustomer->productType['category_sname'])?$this->crmCustomer->productType['category_sname']:"";
        };
        /*公司属性*/
        $fields['compvirtue']=function(){
            return isset($this->crmCustomer->compvirtue['bsp_svalue'])?$this->crmCustomer->compvirtue['bsp_svalue']:"";
        };
        /*会员名*/
        $fields['member_name']=function(){
            return isset($this->crmCustomer['member_name'])?$this->crmCustomer['member_name']:"";
        };
        /*注册时间*/
        $fields['member_regtime']=function(){
            return isset($this->crmCustomer['member_regtime'])?date("Y-m-d", strtotime($this->crmCustomer['member_regtime'])):"";
        };
        /*会员等级*/
        $fields['memberLevel']=function(){
            return isset($this->crmCustomer->memberLevel['bsp_svalue'])?$this->crmCustomer->memberLevel['bsp_svalue']:"";
        };
        /*会员等级*/
        $fields['memberType']=function(){
            return isset($this->crmCustomer->memberType['bsp_svalue'])?$this->crmCustomer->memberType['bsp_svalue']:"";
        };
        /*注册网站*/
        $fields['regWeb']=function(){
            return isset($this->crmCustomer->regWeb['bsp_svalue'])?$this->crmCustomer->regWeb['bsp_svalue']:"";
        };
        /*转招商*/
        $fields['investment'] = function(){
            return $this->crmCustomer->status['investment_status']?"已转招商":"未转招商";
        };
        /*转销售*/
        $fields['sales'] = function(){
            return $this->crmCustomer->status['sale_status']?"已转销售":"未转销售";
        };

        return $fields;
    }
}