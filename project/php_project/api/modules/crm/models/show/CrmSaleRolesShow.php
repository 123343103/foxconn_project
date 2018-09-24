<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\crm\models\show;
use app\modules\crm\models\CrmSaleRoles;

class CrmSaleRolesShow extends CrmSaleRoles
{
    public function fields(){
        $fields = parent::fields();
        /*档案建立人信息*/
        $fields['createBy'] = function(){
            return  $this->buildStaff['staff_name'];

        };
        $fields['updateBy'] = function(){
            return  $this->updateStaff['staff_name'];

        };
        $fields['roleType'] = function(){
            $roleStatus = [20 => "启用",10 => "禁用"];
            return  $this->roleType["bsp_svalue"];

        };
        $fields['statuas'] = function(){
            $roleStatus = [20 => "启用",10 => "禁用"];
            return  $roleStatus[$this->sarole_status];

        };
        return $fields;
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->cdate = $this->cdate?date("Y-m-d", strtotime($this->cdate)):'';
        $this->udate = $this->udate?date("Y-m-d", strtotime($this->udate)):'';
    }
}