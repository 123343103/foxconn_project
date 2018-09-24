<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/28
 * Time: 15:19
 */
namespace app\modules\crm\models\show;

use app\modules\common\models\BsDistrict;
use app\modules\crm\models\CrmDistrictSalearea;
use app\modules\crm\models\CrmSalearea;

class SaleAreaShow extends CrmSalearea
{
    public function fields()
    {
        $fields = parent::fields();
        /*公司名称*/
        $fields['creator'] = function(){
            return $this->buildStaff['staff_name'];
        };
        $fields['updateBy'] = function(){
            return $this->updateStaff['staff_name'];
        };
        /*省*/
        $fields['disProvice'] = function(){

            return $this->disProvice;
        };
        /*市*/
        $fields['disCity'] = function(){
            return $this->disCity;
        };
        /*所在地区*/
        $fields['dis'] = function(){
            $disArr=$this->dis;
            if(empty($disArr)){
                return '';
            }
            foreach ($disArr as $key => $value){
                $result = CrmDistrictSalearea::getDisCity($value['csarea_id'],$value['district_id']);
                $r = BsDistrict::find()->where(['district_id'=>$value['district_id']])->one();
                foreach ($result as $k=>$val){
                    $disA[$r['district_name']][$k]=$val['district_name'];
                }
            }
            foreach (array_keys($disA) as $c=>$a){
                $names[] = $a;
            }
            foreach ($names as $x=>$v){
                $str1= implode(',',$disA[$v]);
                if($str1 != null){
                    $arr2[] = $v.'('.$str1.')';
                }else{
                    $arr2[] = $v;
                }
            }
            $str2= implode(' ',$arr2);
            return $str2;
        };

        /*状态*/
        $fields['status'] = function(){
            switch ($this->csarea_status){
                case 10:
                    return '禁用';
                    break;
                case 20:
                    return '启用';
                    break;
                default:
                    return '';
            }
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