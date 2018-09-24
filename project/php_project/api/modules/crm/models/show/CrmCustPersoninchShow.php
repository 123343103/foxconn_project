<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/29
 * Time: 10:12
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustPersoninch;

class CrmCustPersoninchShow extends CrmCustPersoninch
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['saleArea'] = function(){
            return $this->saleArea['csarea_name'];
        };

        $fields['storeInfo'] = function(){
            return $this->storeInfo;
        };

        $fields['sts_name'] = function(){
            return $this->storeInfo?$this->storeInfo['sts_sname']:'';
        };

        $fields['manager_id'] = function(){
            return $this->manager['staff_id']?$this->manager['staff_id']:'';
        };
        $fields['manager_name'] = function(){
            return $this->manager['staff_name']?$this->manager['staff_name']:'';
        };
        $fields['manager_code'] = function(){
            return $this->manager['staff_code']?$this->manager['staff_code']:'';
        };
        $fields['manager'] = function(){
            return [
                'id'=>$this->manager['staff_id'],
                'code'=>$this->manager['staff_code'],
                'name'=>$this->manager['staff_name'],
            ];
        } ;
        $fields['sale_name'] = function(){
            return $this->sale['staff_name']?$this->sale['staff_name']:'';
        } ;
        $fields['district1'] = function(){
            return $this->district['district_name'];
        };
        $fields['district2'] = function(){
            return $this->district2['district_name'];
        };
        $fields['district3'] = function(){
            return $this->district3['district_name'];
        };
        $fields['district4'] = function(){
            return $this->district4['district_name'];
        };

        return $fields;
    }
}