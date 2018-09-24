<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/23
 * Time: 8:43
 */
namespace app\modules\ptdt\models\show;

use app\modules\common\models\BsDistrict;
use app\modules\ptdt\models\PdFirm;
use yii\bootstrap\Html;

class PdFirmShow extends PdFirm
{
    public $bsPubdata;
    public $category;
    public function fields()
    {
        $fields = parent::fields();
        $fields['firmSource']=function(){
            if ($this->firm_source!=''){
                return $this->firmSource->bsp_svalue;
            }else{
                return '';
            }
        };
        $fields['firmType'] = function (){
            if ($this->firm_type!=''){
                return $this->firmType->bsp_svalue;
            }else{
                return '';
            }
        };
        $fields['firmPosition'] = function (){
            if ($this->firm_position!=''){
                return $this->firmPosition->bsp_svalue;
            }else{
                return '';
            }
        };
        $fields['createBy'] = function () {
            return [
                "name"=>$this->creatorStaff['staff_name'],
                "code"=>$this->creatorStaff['staff_code'],
                'mail'=>$this->creatorStaff['staff_email'],
                'mobile'=>$this->creatorStaff['staff_mobile'],
                'organization_code'=>$this->creatorStaff['organization_code'],
            ];
        };
        $fields['issupplier']=function (){
            return $this->firm_issupplier==1?'是':'否';
        };
        $fields['category']=function(){
            return $this->CategoryName;
        };
        $fields['firmReport']=function(){
            if(isset($this->firmReport)){
                return $this->firmReport;
            }else{
                return null;
            }
        };
        $fields['firm_stu']=function(){
            switch ($this->firm_status) {
                case '10':
                    return '新增厂商';
                case '20':
                    return '拜访中';
                case '30':
                    return '拜访完成';
                case '40':
                    return '谈判中';
                case '50':
                    return '谈判完成';
                case '60':
                    return '呈报中';
                case '70':
                    return '开发完成';
                default:
                    return '';
            }
        };
        //厂商地址-F1677929
        $fields['firmAddress']=function(){
            $districtId=[];
            $districtName=[];
            $fullAddress=[];
            while((int)$this->firm_district_id>0){
                $model=BsDistrict::findOne($this->firm_district_id);
                $districtId[]=$model->district_id;
                $fullAddress[]=$model->district_name;
                $districtName[]=BsDistrict::find()->select('district_id,district_name')->where(['is_valid'=>'1','district_pid'=>$model->district_pid])->all();
                $this->firm_district_id=$model->district_pid;
            }
            return [
                'districtId'=>array_reverse($districtId),
                'districtName'=>array_reverse($districtName),
                'fullAddress'=>implode('',array_reverse($fullAddress)).$this->firm_compaddress
            ];
        };
        $fields['firm_sname']=function (){
            return $this->firm_sname;
        };
        $fields['firm_shortname']=function (){
            return $this->firm_shortname;
        };
        $fields['firm_ename']=function (){
            return $this->firm_ename;
        };
        $fields['firm_eshortname']=function (){
            return $this->firm_eshortname;
        };
        $fields['firm_brand']=function (){
            return $this->firm_brand;
        };
        $fields['firm_compaddress']=function (){
            return $this->firm_compaddress;
        };
        return $fields;
    }
}