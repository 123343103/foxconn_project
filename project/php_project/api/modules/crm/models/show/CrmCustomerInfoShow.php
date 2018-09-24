<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 14:48
 */
namespace app\modules\crm\models\show;

use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustomerStatus;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use yii\db\Query;
use yii\helpers\Html;

class CrmCustomerInfoShow extends CrmCustomerInfo
{
    public function fields()
    {
        $fields = parent::fields();
        //销售状态
        $fields['saleStatus'] = function () {
            return $this->status ? $this->status['sale_status'] : '';
        };
        /*转招商*/
        $fields['investment'] = function () {
            return $this->status['investment_status'] ? "已转招商" : "未转招商";
        };
        /*转销售*/
        $fields['sales'] = function () {
            return $this->status['sale_status'] ? "已转销售" : "未转销售";
        };
        /*会员开发状态*/
        $fields['develop_status'] = function () {
            switch (count($this->visitInfo)) {
                case 0:
                    return '待开发';
                    break;
                default:
                    return '开发中';
                    break;
            }
        };
        /*客户编码*/
        $fields['apply_code'] = function () {

            switch ($this->custApply['status']) {
                case 10:
                    return "";
                    break;
                case 20:
                    return "审核中";
                    break;
                case 30:
                    return "审核中";
                    break;
                case 40:
                    return $this->cust_code;
                    break;
                case 50:
                    return "驳回";
                    break;
                default:
                    return null;
            }

        };
        $fields['apply_status'] = function () {
            return $this->custApply['status'];
        };
        /*职位职能*/
        $fields['custFunction'] = function () {
            return $this->custFunction['bsp_svalue'];
        };
        //年营业额
        $fields['member_compsum_name'] = function () {
            return $this->memberCompsum['bsp_svalue'];
        };
        //年采购额
        $fields['cust_pruchaseqty_name'] = function () {
            return $this->custPruchaseqty['bsp_svalue'];
        };
        /*发票需求*/
        $fields['compReq'] = function () {
            return $this->compReq['bsp_svalue'] ? $this->compReq['bsp_svalue'] : "";
        };
        /*需求类目*/
        $fields['productType'] = function () {
            return $this->productType['catg_name'] ? $this->productType['catg_name'] : "";
        };
        /*档案建立人信息*/
        $fields['createBy'] = function () {
            return [
                "code" => $this->buildStaff['staff_code'],
            ];
        };

        $org_pid=isset($this->buildStaff->organization['organization_pid'])?$this->buildStaff->organization['organization_pid']:"";
        $org_name=isset($this->buildStaff->organization['organization_name'])?$this->buildStaff->organization['organization_name']:"";
        $res=[] && $res[]=$org_name;
        while($org_pid>0){
            $org=HrOrganization::findOne($org_pid);
            $org_pid=$org->organization_pid;
            $res[]=$org->organization_name;
        }
        is_array($res) && $res=array_reverse($res);
        $fields['creator_sale_center'] = function() use ($res){
            return isset($res[3])?$res[3]:"";
        };
        $fields['creator_sale_depart'] = function() use ($res){
            return isset($res[4])?$res[4]:"";
        };


        $fields['sih_id'] = function () {
            return $this->visitInfo['sih_id'] ? $this->visitInfo['sih_id'] : null;
        };
        $fields['createName'] = function () {
            return $this->buildStaff['staff_name'];
        };
        $fields['bsPubdata'] = function () {
            return [
                'memberSource' => $this->custSource['bsp_svalue'] ? $this->custSource['bsp_svalue'] : "",//会员来源
                'compscale' => $this->compscale['bsp_svalue'] ? $this->compscale['bsp_svalue'] : "",//公司规模
                'memberCurr' => $this->memberCurr ? $this->memberCurr['bsp_svalue'] : '',//交易货币
                'memberType' => $this->memberType ? $this->memberType['bsp_svalue'] : '', //会员类型
                'memberWeb' => $this->regWeb ? $this->regWeb['bsp_svalue'] : '', //注册网站
            ];
        };
        //会员类型
        $fields['memberType'] = function () {
            return $this->memberType ? $this->memberType['bsp_svalue'] : '';
        };
        //申请发票类型
        $fields['invoiceType'] = function () {
            return $this->invoiceType ? $this->invoiceType['bsp_svalue'] : '';
        };
        //具备发票类型
        $fields['invoice_title'] = function () {
            return $this->cust_sname;
        };
        //具备发票类型
        $fields['invoType'] = function () {
            return $this->invoType ? $this->invoType['bsp_svalue'] : '';
        };
        /*//网站类型*/
        $fields['regWeb'] = function () {
            return $this->regWeb ? $this->regWeb['bsp_svalue'] : '';
        };

        /*注册货币*/
        $fields['dealCurrency'] = function () {
            return $this->regCurrency['bsp_svalue'] ? $this->regCurrency['bsp_svalue'] : "";
        };
        $fields['visitFlag'] = function () {
            return $this->member_visitflag == '1' ? '已拜访' : '未拜访';
        };
        $fields['custType'] = function () {
            return $this->custType['bsp_svalue']; //客户类型
        };
        $fields['custLevel'] = function () {
            return $this->custLevel['bsp_svalue']; //客户等级
        };
        $fields['compvirtue'] = function () {
            return $this->compvirtue['bsp_svalue']; //公司属性
        };
        $fields['businessType'] = function () {
            return $this->businessType['bsp_svalue']; //经营类型
        };
        $fields['custClass'] = function () {
            return $this->custClass['bsp_svalue']; //客户类别
        };
        $fields['latDemand'] = function () {
            return $this->latDemand['bsp_svalue']; //潜在需求
        };
        $fields['custSource'] = function () {
            return $this->custSource['bsp_svalue']; //客户来源
        };
        $fields['regCurrency'] = function () {
            return $this->regCurrency['bsp_svalue']; //注册货币
        };
        $fields['compscale'] = function () {
            return $this->compscale['bsp_svalue']; //公司规模
        };

        /*行业类别*/
        $fields['industryType'] = function () {
            return $this->industryType['idt_sname'];
        };
        /*所在地区*/
        $fields['area'] = function () {
            return $this->area['district_name'];
        };
        /*所在军区*/
        $fields['saleArea'] = function () {
            return $this->saleArea['csarea_name'];
        };
        /*列表页主要联系人*/
//        $fields['contactPerson'] = function(){
//            return [
//                "name"=>$this->contactPerson['ccper_name'],
//                "tel"=>$this->contactPerson['ccper_tel'],
//                "id"=>$this->contactPerson['ccper_id'],
//            ];
//        };
        $fields['ccpername'] = function () {
            return $this->contactPerson['ccper_name'];
        };
        $fields['ccpertel'] = function () {
            return $this->contactPerson['ccper_tel'];
        };
        $fields['ccperid'] = function () {
            return $this->contactPerson['ccper_id'];
        };

        /*客户经理*/
        $fields['manager'] = function () {
//            $staffs=CrmCustPersoninch::find()->where(["cust_id"=>$this->cust_id])->select("ccpich_personid")->column();
//            $data=HrStaff::find()->select("staff_name")->where(["in","staff_id",$staffs])->column();
//            return implode(",",$data);
            $data=[];
            foreach ($this->personinch as $per){
                $data[]=$per->manager["staff_name"];
            }
            $data=array_filter($data);
            return implode(",",$data);
        };
        /*客户经理 加工号  F1678086*/
        $fields['manager_code'] = function () {
            $staffs=CrmCustPersoninch::find()->where(["cust_id"=>$this->cust_id])->select("ccpich_personid")->column();
//            $data=HrStaff::find()->select(["staff_name",'staff_id','concat_ws(",",staff_id,staff_name) staff'])->where(["in","staff_id",$staffs])->all();
            $data = (new Query())->select([
                'concat_ws(" ",hr.staff_code,hr.staff_name) staff',
                "staff_name",
                'staff_id',
            ])->from(HrStaff::tableName().' hr')
                ->where(["in","staff_id",$staffs])
                ->column();
//            return $data;
            return implode(",",$data);
        };
        $fields['manager_cid'] = function () {
            $staffs=CrmCustPersoninch::find()->where(["cust_id"=>$this->cust_id])->select("ccpich_personid")->column();
            $data=HrStaff::find()->select("staff_id")->where(["in","staff_id",$staffs])->column();
            return implode(",",$data);
        };


        $fields['manager_sale_center'] = function(){
            $data=[];
            foreach ($this->personinch as $per){
                $org_pid=isset($per->manager->organization['organization_pid'])?$per->manager->organization['organization_pid']:"";
                $org_name=isset($per->manager->organization['organization_name'])?$per->manager->organization['organization_name']:"";
                $res=[] && $res[]=$org_name;
                while($org_pid>0){
                    $org=HrOrganization::findOne($org_pid);
                    $org_pid=$org->organization_pid;
                    $res[]=$org->organization_name;
                }
                is_array($res) && $res=array_reverse($res);
                $data[]=isset($res[3])?$res[3]:"";
            }
            return implode(",",$data);
        };
        $fields['manager_sale_depart'] = function(){
            $data=[];
            foreach ($this->personinch as $per){
                $org_pid=isset($per->manager->organization['organization_pid'])?$per->manager->organization['organization_pid']:"";
                $org_name=isset($per->manager->organization['organization_name'])?$per->manager->organization['organization_name']:"";
                $res=[] && $res[]=$org_name;
                while($org_pid>0){
                    $org=HrOrganization::findOne($org_pid);
                    $org_pid=$org->organization_pid;
                    $res[]=$org->organization_name;
                }
                is_array($res) && $res=array_reverse($res);
                $data[]=isset($res[4])?$res[4]:"";
            }
            return implode(",",$data);
        };

        /*客户经理人ID*/
        $fields['manager_id'] = function () {
            return $this->manager['staff_id'];
        };
        /*修改,详情页主要联系人*/
        $fields['contactPersons'] = function () {
            return $this->contactPersons;
        };
        $fields['contacts'] = function () {
            return $this->contacts;
        };
        /*主要设备*/
        $fields['custDevice'] = function () {
            return $this->custDevice;
        };
        /*主要产品*/
        $fields['custProduct'] = function () {
            return $this->custProduct;
        };
        /*主要客户*/
        $fields['custCustomer'] = function () {
            return $this->custCustomer;
        };
        /*登记证*/
//        $fields['regName'] = function (){
//            return $this->regName;
//        };
        /*详细地址*/
        $fields['district'] = function () {
            return $this->district;
        };
        $fields['province'] = function () {
            $address_id = $this->cust_district_2;
            $addr[] = $this->cust_adress;
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            $addr = array_reverse($addr);
            return empty($addr[1]) ? "" : $addr[1];
        };
        $fields['city'] = function () {
            $address_id = $this->cust_district_2;
            $addr[] = $this->cust_adress;
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            $addr = array_reverse($addr);
            return empty($addr[2]) ? "" : $addr[2];
        };
        /*地址*/
        $fields['districts'] = function () {
            return $this->districts;
        };
        /*总公司地址*/
        $fields['districtCompany'] = function () {
            return $this->districtCompany;
        };
        /*发票抬头地址*/
        $fields['invoiceTitleDistrict'] = function () {
            return $this->invoiceTitleDistrict;
        };
        /*发票邮寄地址*/
        $fields['invoiceMailDistrict'] = function () {
            return $this->invoiceMailDistrict;
        };

        /*认领人*/
        $fields['allotman'] = function () {
            return $this->allotman ? $this->allotman : "";
        };
        //需求类目
        $fields['category_name'] = function () {
            return $this->productType['catg_name'] ? $this->productType['catg_name'] : "";
        };
        $fields['status'] = function () {
            if (!$this->personinch) {
                return "未认领";
            }
            if ($this->personinch_status == null) {
                return "未认领";
            } else {
                return "已认领";
            }
        };
        $fields['personinch'] = function () {
            if (!$this->personinch) {
                return "";
            }
            if ($this->personinch_status == 0) {
                return [
                    "status" => "未认领"
                ];
            } else {
                return [
                    "ccpichId" => empty($this->personinch[0]['ccpich_id'])?'':$this->personinch[0]['ccpich_id'],
                    "staffId" => empty($this->personinch[0]['ccpich_personid'])?'':$this->personinch[0]['ccpich_personid'],
                    "code" => empty($this->personinch[0]->manager)?'':$this->personinch[0]->manager['staff_code'],
                    "manager" => empty($this->personinch[0]->manager)?'':$this->personinch[0]->manager['staff_name'],
                    "status" => "已认领",
                ];
            }
        };
        //认领人
        $fields['claimPerson'] = function () {
            if (!empty($this->personinch['ccpich_status'])&&$this->personinch['ccpich_status'] == CrmCustPersoninch::STATUS_DEFAULT) {
                return $this->personinch['manager']['staff_name'];
            }
            return '';
        };
        //招商开发状态
        $fields['investmentStatus'] = function () {
            if (!isset($this->status->investment_status)) {
                return "";
            }
            switch ($this->status->investment_status) {
                case CrmCustomerStatus::INVESTMENT_UN:
                    return '未开发';
                case CrmCustomerStatus::INVESTMENT_IN:
                    return '开发中';
                case CrmCustomerStatus::INVESTMENT_SUCC:
                    return '开发成功';
                case CrmCustomerStatus::INVESTMENT_FAILURE:
                    return '开发失败';
            }
        };
        //帐信申请
        $fields['credit_apply'] = function () {
            return $this->creditApply ? $this->creditApply['credit_status'] : '';
        };

        //帐信申请
        $fields['credit_apply_status'] = function () {
            return $this->lcreditApply ? $this->lcreditApply['credit_status'] : '';
        };

        //客户地址
        $fields['address'] = function () {
            $address_id = $this->cust_district_2;
            $addr[] = $this->cust_adress;
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            return implode(" ", array_reverse($addr));
        };

        $fields['sih_id'] = function () {
            return $this->visitInfo['sih_id'];
        };

        //年营业额
        $fields['member_compsum'] = function () {
            return $this->member_compsum;
        };
        //年采购额
        $fields['cust_pruchaseqty'] = function () {
            return $this->cust_pruchaseqty;
        };

        /*状态*/
        $fields['sale_status'] = function () {
            if (!isset($this->status->sale_status)) {
                return "";
            }
            switch ($this->status->sale_status) {
                case CrmCustomerStatus::STATUS_DEL:
                    return '<span class="red">已删除</span>';
                case CrmCustomerStatus::STATUS_DEFAULT:
                    return '正常';
            }
        };

        return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->create_at = $this->create_at ? date("Y-m-d", strtotime($this->create_at)) : '';
        $this->update_at = $this->update_at ? date("Y-m-d", strtotime($this->update_at)) : '';
        $this->member_regtime = $this->member_regtime ? date("Y-m-d", strtotime($this->member_regtime)) : '';
        $this->member_certification = $this->member_certification ? date("Y-m-d", strtotime($this->member_certification)) : '';
    }
}