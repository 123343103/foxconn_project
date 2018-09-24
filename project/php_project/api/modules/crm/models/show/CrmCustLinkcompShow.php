<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/28
 * Time: 15:19
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustLinkcomp;

class CrmCustLinkcompShow extends CrmCustLinkcomp
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['lincType'] = function(){
            return $this->lincType['bsp_svalue'];
        };

        $fields['investment_cur'] = function(){
            return $this->currency?$this->currency['bsp_svalue']:'';
        };

        $fields['district1'] = function(){
            return [
                'id'=>$this->district['district_id'],
                'name'=>$this->district['district_name'],
            ];
        };
        $fields['district2'] = function(){
            return [
                'id'=>$this->district2['district_id'],
                'name'=>$this->district2['district_name'],
            ];
        };
        $fields['district3'] = function(){
            return [
                'id'=>$this->district3['district_id'],
                'name'=>$this->district3['district_name'],
            ];
        };
        $fields['district4'] = function(){
            return [
                'id'=>$this->district4['district_id'],
                'name'=>$this->district4['district_name'],
            ];
        };
        $fields['address'] = function(){
            return $this->district4['district_name'].$this->district3['district_name'].$this->district2['district_name'].$this->district['district_name'].$this->linc_address;
        };
        $fields['short_address'] = function(){
            return $this->district4['district_name'].$this->district3['district_name'];
        };
        return $fields;
    }
}