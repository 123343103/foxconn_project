<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCreditApply;

class CrmCreditLimitShow extends CrmCreditApply
{
    public function fields()
    {
        $fields = parent::fields();

        /*客户信息 --Start--*/
//        $fields['customer'] = function(){
//            return $this->customer;
//        };
        $fields["currency_type"]=function(){
            return $this->currencyType?$this->currencyType->bsp_svalue:'';
        };
        $fields['cust_sname'] = function(){
            return $this->customer['cust_sname'];//客户全称
        };
        $fields['cust_shortname'] = function(){
            return $this->customer['cust_shortname'];//客户简称
        };
        $fields['apply_code'] = function(){
            return $this->customer?$this->customer['cust_code']:'';//客户代码
        };
        $fields['csarea'] = function(){
            return [
                'csarea_id' => $this->customer?$this->customer->saleArea['csarea_id']:'',//军区ID
                'csarea_name' => $this->customer?$this->customer->saleArea['csarea_name']:'',//军区
            ];
        };
        $fields['cust_type'] = function(){
            return [
                'bsp_id' => $this->customer?$this->customer->custType['bsp_id']:'',//客户类型ID
                'bsp_svalue' => $this->customer?$this->customer->custType['bsp_svalue']:'',//客户类型
            ];
        };
        $fields['cust_level'] = function(){
            return [
                'bsp_id' => $this->customer?$this->customer->custLevel['bsp_id']:'',//客户等级ID
                'bsp_svalue' => $this->customer?$this->customer->custLevel['bsp_svalue']:'',//客户等级
            ];
        };
        $fields['cust_compvirtue'] = function(){
            return [
                'bsp_id' => $this->customer?$this->customer->compvirtue['bsp_id']:'',//公司属性ID
                'bsp_svalue' => $this->customer?$this->customer->compvirtue['bsp_svalue']:'',//公司属性
            ];
        };
        $fields['cust_tel1'] = function(){
            return $this->customer['cust_tel1'];//公司电话
        };
        $fields['company_name'] = function(){
            return $this->company['company_name'];//交易法人
        };
        $fields['cust_regfunds'] = function(){
            return $this->customer['cust_regfunds'];//注册资金
        };
        $fields['regcurr'] = function(){
            return !empty($this->customer)?$this->customer->regCurrency['bsp_svalue']:'';//注册货币
        };
        $fields['cust_parentcomp'] = function(){
            return $this->customer['cust_parentcomp'];//母公司
        };
        $fields['cust_tax_code'] = function(){
            return $this->customer['cust_tax_code'];//税籍编码
        };
        $fields['cust_contacts'] = function(){
            return $this->customer['cust_contacts'];//主要联系人
        };
        $fields['cust_tel2'] = function(){
            return $this->customer['cust_tel2'];//联系方式
        };
        $fields['credit_name'] = function(){
            return $this->creditPeople['staff_name']; //申请人
        };
        $fields['creditType'] = function(){
            return $this->creditType['business_value']; //账信类型
        };
        $fields['initialDay'] = function(){
            return $this->initialDay['bsp_svalue']; //起算日
        };
        $fields['paymentMethod'] = function(){
            return $this->paymentMethod['bsp_svalue']; //付款方式
        };
        $fields['payDay'] = function(){
            return $this->payDay['bsp_svalue']; //付款日
        };
        $fields['paymentClause'] = function(){
            return $this->paymentClause['bnt_sname']; //付款条件
        };
        //投资总额
        $fields['total_investment'] = function(){
            return $this->customer['total_investment'];
        };
        //投资总额币别
        $fields['total_investment_cur'] = function(){
            return $this->customer['total_investment_cur'];
        };
        $fields['total_investment_cur_name'] = function(){
            return $this->customer?$this->customer->totalinvestment['bsp_svalue']:'';
        };
        //实收资本
        $fields['official_receipts'] = function(){
            return $this->customer['official_receipts'];
        };
        //实收资本币别
        $fields['official_receipts_cur'] = function(){
            return $this->customer['official_receipts_cur'];
        };
        $fields['official_receipts_cur_name'] = function(){
            return $this->customer?$this->customer->officialReceipts['bsp_svalue']:'';
        };

        $fields['shareholding_ratio'] = function(){
            return $this->customer['shareholding_ratio'];//持股比例
        };
        $fields['member_businessarea'] = function(){
            return $this->customer['member_businessarea'];//经营范围
        };
        $fields['manager'] = function(){
            return [
                'staff_name' => $this->customer?$this->customer->manager['staff_name']:'',//客户经理人
                'staff_mobile' => $this->customer?$this->customer->manager['staff_mobile']:'',//客户经理人联系方式
            ];
        };
        $fields['contact'] = function(){
            return $this->custPerson?$this->custPerson:''; //客户联系方式
        };
        $fields['linkcomp'] = function(){
            return $this->linkComp?$this->linkComp:''; //子公司
        };
        $fields['custCustomer'] = function(){
            return $this->custCustomer?$this->custCustomer:''; //主要客户
        };
        $fields['supplier'] = function(){
            return $this->supplier?$this->supplier:''; //主要供应商
        };
        $fields['bank'] = function(){
            return $this->bank?$this->bank:''; //主要往来银行
        };

        //营业额
        $fields['turnover'] = function(){
            $a=[];
            foreach ($this->turnover as $k => $v){
                $a[$v['currency']][$v['year']] = $v['turnover'];
            }
            return $a;
        };

        /*账信额度*/
        $fields['limit'] = function(){
            $limit = [];
            if(!empty($this->creditLimit)){
                foreach ($this->creditLimit as $kl=>$vl){
                    $type = BsBusinessType::findOne($vl['credit_type']);
                    $limit[$kl]['limit_id'] = $vl['limit_id'];
                    $limit[$kl]['credit_type'] = $vl['credit_type'];
                    $limit[$kl]['creditType'] = $type['business_value'];
                    $limit[$kl]['credit_limit'] = $vl['credit_limit'];
                    $limit[$kl]['approval_limit'] = $vl['approval_limit'];
                    $limit[$kl]['validity_date'] = $vl['validity_date']?date("Y-m-d", strtotime($vl['validity_date'])):'';;
                }
                return $limit;
            }
        };

        /*客户签字档*/
        $fields['file1'] = function(){
            $file1 = [];
            if(!empty($this->creditFile1)){
                foreach ($this->creditFile1 as $key1=>$val1){
                    $file1[$key1]['date_file'] = substr($val1->file_new, 0, 6);
                    $file1[$key1]['file_old'] = $val1->file_old;
                    $file1[$key1]['file_new'] = $val1->file_new;
                }
                return $file1;
            }
        };
        $fields['file2'] = function(){
            $file2 = [];
            if(!empty($this->creditFile2)){
                foreach ($this->creditFile2 as $key2=>$val2){
                    $file2[$key2]['date_file'] = substr($val2->file_new, 0, 6);
                    $file2[$key2]['file_old'] = $val2->file_old;
                    $file2[$key2]['file_new'] = $val2->file_new;
                }
                return $file2;
            }
        };

        $fields['paymentType'] = function(){
            if($this->payment_type == '0'){
                return $this->paymentClause['bnt_sname'];
            }elseif($this->payment_type == '1'){
                return $this->paymentClause['bnt_sname'].'每月'.$this->payment_clause_day.'日';
            }
        };


//        $fields['limit'] = function(){
//            $limit = [];
//            if($this->credit_status == '10' || $this->credit_status == '20'){
//                foreach ($this->creditLimitNa as $key => $val){
//                    $limit[$key]['laid'] = $val->laid;/*额度类型id*/
//                    $limit[$key]['credit_limit'] = $val->credit_limit;/*申请额度*/
//                    $limit[$key]['approval_limit'] = $val->approval_limit;/*批复额度*/
//                    $limit[$key]['validity'] = $val->validity;/*有效期至*/
//                    $limit[$key]['remark'] = $val->remark;/*备注*/
//                    $limit[$key]['credit_type_id'] = $val->creditType->id;/*额度类型*/
//                    $limit[$key]['credit_type'] = $val->creditType->credit_name;/*额度类型*/
//                    $limit[$key]['initial_id'] = $val->initial->bsp_id;/*起算日*/
//                    $limit[$key]['initial'] = $val->initial->bsp_svalue;/*起算日*/
//                    $limit[$key]['pay_id'] = $val->pay->bsp_id;/*付款日*/
//                    $limit[$key]['pay'] = $val->pay->bsp_svalue;/*付款日*/
//                    $limit[$key]['pay_method_id'] = $val->payMethod->bsp_id;/*付款方式*/
//                    $limit[$key]['pay_method'] = $val->payMethod->bsp_svalue;/*付款方式*/
//                    $limit[$key]['pay_clause_id'] = $val->payClause->bnt_id;/*付款条件*/
//                    $limit[$key]['pay_clause'] = $val->payClause->bnt_sname;/*付款条件*/
//                }
//            }else if($this->credit_status == '30'){
//                foreach ($this->creditLimitYa as $key => $val){
//                    $limit[$key]['laid'] = $val->laid;/*额度类型id*/
//                    $limit[$key]['credit_limit'] = $val->credit_limit;/*申请额度*/
//                    $limit[$key]['approval_limit'] = $val->approval_limit;/*批复额度*/
//                    $limit[$key]['validity'] = $val->validity;/*有效期至*/
//                    $limit[$key]['remark'] = $val->remark;/*备注*/
//                    $limit[$key]['credit_type_id'] = $val->creditType->id;/*额度类型*/
//                    $limit[$key]['credit_type'] = $val->creditType->credit_name;/*额度类型*/
//                    $limit[$key]['initial_id'] = $val->initial->bsp_id;/*起算日*/
//                    $limit[$key]['initial'] = $val->initial->bsp_svalue;/*起算日*/
//                    $limit[$key]['pay_id'] = $val->pay->bsp_id;/*付款日*/
//                    $limit[$key]['pay'] = $val->pay->bsp_svalue;/*付款日*/
//                    $limit[$key]['pay_method_id'] = $val->payMethod->bsp_id;/*付款方式*/
//                    $limit[$key]['pay_method'] = $val->payMethod->bsp_svalue;/*付款方式*/
//                    $limit[$key]['pay_clause_id'] = $val->payClause->bnt_id;/*付款条件*/
//                    $limit[$key]['pay_clause'] = $val->payClause->bnt_sname;/*付款条件*/
//                }
//            }else{
//                foreach ($this->creditLimitRa as $key => $val){
//                    $limit[$key]['laid'] = $val->laid;/*额度类型id*/
//                    $limit[$key]['credit_limit'] = $val->credit_limit;/*申请额度*/
//                    $limit[$key]['approval_limit'] = $val->approval_limit;/*批复额度*/
//                    $limit[$key]['validity'] = $val->validity;/*有效期至*/
//                    $limit[$key]['remark'] = $val->remark;/*备注*/
//                    $limit[$key]['credit_type_id'] = $val->creditType->id;/*额度类型*/
//                    $limit[$key]['credit_type'] = $val->creditType->credit_name;/*额度类型*/
//                    $limit[$key]['initial_id'] = $val->initial->bsp_id;/*起算日*/
//                    $limit[$key]['initial'] = $val->initial->bsp_svalue;/*起算日*/
//                    $limit[$key]['pay_id'] = $val->pay->bsp_id;/*付款日*/
//                    $limit[$key]['pay'] = $val->pay->bsp_svalue;/*付款日*/
//                    $limit[$key]['pay_method_id'] = $val->payMethod->bsp_id;/*付款方式*/
//                    $limit[$key]['pay_method'] = $val->payMethod->bsp_svalue;/*付款方式*/
//                    $limit[$key]['pay_clause_id'] = $val->payClause->bnt_id;/*付款条件*/
//                    $limit[$key]['pay_clause'] = $val->payClause->bnt_sname;/*付款条件*/
//                }
//            }
//
//            return $limit;
//        };

        /*客户信息 --End--*/

        return $fields;
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->create_at = date("Y-m-d", strtotime($this->create_at));
        $this->update_at = date("Y-m-d", strtotime($this->update_at));
        $this->credit_date = $this->credit_date?date("Y-m-d", strtotime($this->credit_date)):'';
    }
}