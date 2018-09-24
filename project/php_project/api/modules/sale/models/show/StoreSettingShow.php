<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 04:15
 */

namespace app\modules\sale\models\show;
use app\modules\crm\models\CrmStoresinfo;

class StoreSettingShow extends CrmStoresinfo
{
    public function fields(){
        $fields = parent::fields();

        // 获取销售点状态名
        $fields['status_name']=function(){
            return $this->status;
        };

        // 获取销售军区
        $fields['saleArea'] = function ()
        {
            return $this->area;
        };
        /*军区名称*/
        $fields['part_no'] = function(){
            return $this->area['csarea_name'];
        };

        // 省长
        $fields['sz'] = function ()
        {
            return $this->sz;
        };
        /*省长名*/
        $fields['sz_name'] = function(){
            return $this->sz['staff_name'];
        };

        // 店长
        $fields['dz'] = function ()
        {
            return $this->dz;
        };
        /*店长名*/
        $fields['dz_name'] = function(){
            return $this->dz['staff_name'];
        };
        /*状态*/
        $fields['status'] = function(){
            switch ($this->sts_status){
                case CrmStoresinfo::STATUS_NORMAL:
                    return '营业中';
                case CrmStoresinfo::STATUS_PREPARE:
                    return '筹备中';
                case CrmStoresinfo::STATUS_SHUTUPSTORE:
                    return '已歇业';
                case CrmStoresinfo::STATUS_PAUSE:
                    return '已暂停';
                case CrmStoresinfo::STATUS_CLOSE:
                    return '已关闭';
            }
        };

        // 行政区域
        $fields['addr_info'] = function ()
        {
            return $this->addr;
        };

        /*档案建立人*/
        $fields['createBy'] = function ()
        {
            return $this->createBy;
        };
        /*档案建立人名*/
        $fields['create_name'] = function ()
        {
            return $this->createBy['staff_name'];
        };

        /*档案修改人*/
        $fields['editBy'] = function ()
        {
            return $this->editBy;
        };
        /*档案修改人名*/
        $fields['update_name'] = function ()
        {
            return $this->editBy['staff_name'];
        };
        return $fields;
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->cdate = !empty($this->cdate)?date("Y-m-d", strtotime($this->cdate)):'';
        $this->edate = !empty($this->edate)?date("Y-m-d", strtotime($this->edate)):'';
    }
}