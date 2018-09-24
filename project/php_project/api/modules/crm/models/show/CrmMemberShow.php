<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCustomerInfo;
use yii\helpers\Html;

class CrmMemberShow extends CrmCustomerInfo
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['member_remark'] = function(){
            return $this->member_remark;
        };

        //年营业额
        $fields['member_compsum_name'] = function(){
            return $this->memberCompsum['bsp_svalue'];
        };
        //年采购额
        $fields['cust_pruchaseqty_name'] = function(){
            return $this->custPruchaseqty['bsp_svalue'];
        };

        $fields['sih_id'] = function(){
            return $this->visitInfo['sih_id']?$this->visitInfo['sih_id']:null;
        };

        /*注册网站*/
        $fields['regWeb'] = function(){
            return $this->regWeb['bsp_svalue']?$this->regWeb['bsp_svalue']:"";
        };
        /*会员类型*/
        $fields['memberType'] = function(){
            return $this->memberType['bsp_svalue']?$this->memberType['bsp_svalue']:"";
        };
        /*客户来源*/
        $fields['custSource'] = function(){
            return $this->custSource['bsp_svalue']?$this->custSource['bsp_svalue']:"";
        };
        /*发票需求*/
        $fields['compReq'] = function(){
            return $this->compReq['bsp_svalue']?$this->compReq['bsp_svalue']:"";
        };
        /*潜在需求*/
        $fields['latDemand'] = function(){
            return $this->latDemand['bsp_svalue']?$this->latDemand['bsp_svalue']:"";
        };
        /*需求类目*/
        $fields['productType'] = function(){
            return $this->productType['catg_name']?$this->productType['catg_name']:"";
        };
        /*会员等级*/
        $fields['memberLevel'] = function(){
            return $this->memberLevel['bsp_svalue']?$this->memberLevel['bsp_svalue']:"";
        };
        /*经营模式*/
        $fields['businessType'] = function(){
            return $this->businessType['bsp_svalue']?$this->businessType['bsp_svalue']:"";
        };
        /*注册货币*/
        $fields['regCurrency'] = function(){
            return $this->regCurrency['bsp_svalue']?$this->regCurrency['bsp_svalue']:"";
        };
        /*交易货币*/
        $fields['dealCurrency'] = function(){
            return $this->regCurrency['bsp_svalue']?$this->regCurrency['bsp_svalue']:"";
        };
        /*公司属性*/
        $fields['compvirtue'] = function(){
            return $this->compvirtue['bsp_svalue']?$this->compvirtue['bsp_svalue']:"";
        };

        $fields['visitFlag'] = function(){
            return $this->member_visitflag == '1'?'是':'否';
        };

        /*所在地区详细地址*/
        $fields['district'] = function (){
            return $this->district;
        };
        /*是否会员*/
        $fields['ismember'] = function(){
            return $this->cust_ismember == '1'?'是':'否';
        };
        /*转招商*/
        $fields['investment'] = function(){
            return $this->status['investment_status']?"已转招商":"未转招商";
        };
        /*转销售*/
        $fields['sales'] = function(){
            return $this->status['sale_status']?"已转销售":"未转销售";
        };
        return $fields;
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->create_at = date("Y-m-d", strtotime($this->create_at));
        $this->update_at = date("Y-m-d", strtotime($this->update_at));
        $this->member_regtime = $this->member_regtime?date("Y-m-d", strtotime($this->member_regtime)):null;
        $this->member_certification = $this->member_certification?date("Y-m-d", strtotime($this->member_certification)):null;
    }
}