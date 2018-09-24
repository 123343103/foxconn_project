<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/1
 * Time: 11:16
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmEmployee;
use app\modules\ptdt\models\BsCategory;

class CrmEmployeeShow extends CrmEmployee
{
    public function fields()
    {
        $fields = parent::fields();
        //销售点名称
        $fields['sts_sname'] = function(){
            return $this->storeInfo['sts_sname'];
        };
        //人力类型
        $fields['sarole_type'] = function(){
            return $this->saleRole?$this->saleRole->roleType['bsp_svalue']:'';
        };
        //人力类型
        $fields['sarole_type_id'] = function(){
            return $this->saleRole?$this->saleRole['sarole_type']:'';
        };
        //销售角色
        $fields['sale_role'] = function(){
            return $this->saleRole['sarole_sname'];
        };
        //所在营销区域id
        $fields['csarea_id'] = function(){
            return $this->area['csarea_id'];
        };
        //所在营销区域
        $fields['csarea'] = function(){
            return $this->area['csarea_name'];
        };
        //直属上司
        $fields['leader'] = function(){
            return $this->leader?$this->leader->staffName['staff_code'].$this->leader->staffName['staff_name']:'';
        };
        //上司角色
        $fields['lerole_sname'] = function(){
            return $this->leaderRole?$this->leaderRole['sarole_sname']:'';
        };
        //对应省长工号
        $fields['sz_code'] = function(){
            return $this->storeInfo?$this->storeInfo->sz['staff_code']:'';
        };
        //对应省长
        $fields['sz_name'] = function(){
            return $this->storeInfo?$this->storeInfo->sz['staff_name']:'';
        };
        //对应店长
        $fields['dz_code'] = function(){
            return $this->storeInfo?$this->storeInfo->dz['staff_code']:'';
        };
        //对应店长
        $fields['dz_name'] = function(){
            return $this->storeInfo?$this->storeInfo->dz['staff_name']:'';
        };
        //工号信息
        $fields['staff'] = function(){
            return [
                    'name'=>$this->staffName['staff_name'],
                    'tel'=>$this->staffName['staff_tel'],
                    'organization'=>$this->staffName['organization_code'],
                    'organization_name'=>$this->staffName?$this->staffName->organization['organization_name']:'',
                    'email'=>$this->staffName['staff_email'],
                    'job_level'=>$this->staffName['job_level'],
                    'id'=>$this->staffName['staff_id'],
                ];
        };
        //销售员状态
        $fields['status'] = function(){
            switch ($this->sale_status){
                case 10:
                    return '禁用';
                    break;
                case 20:
                    return '启用';
                    break;
            }
        };
        $fields['category'] = function(){
            $lists['category_id']=unserialize($this->category_id);
            if(!empty($lists['category_id'])){
                $list['category_id']=BsCategory::find()->select('catg_name')->where(['in','catg_no',$lists['category_id']])->all();//注意此处的查询语句
                $categoryName='';
                foreach($list['category_id'] as $row){
                    $categoryName.=$row['catg_name'].',';
                }
                $model['category_id']=rtrim($categoryName,',');
                return $model;
            }
            return null;
        };
        return $fields;
    }
    public function afterFind()
    {
        parent::afterFind();
        $this->create_at = date("Y-m-d", strtotime($this->create_at));
        $this->update_at = $this->update_at?date("Y-m-d", strtotime($this->update_at)):'';
    }
}