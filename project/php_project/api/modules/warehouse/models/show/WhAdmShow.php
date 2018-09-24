<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/13
 * Time: 下午 04:35
 */

namespace app\modules\warehouse\models\show;


use app\modules\common\models\BsDistrict;
use app\modules\warehouse\models\WhAdm;

class WhAdmShow extends WhAdm
{
    public function fields()
    {
        $fields = parent::fields();
        //关联多张表
        $fields['hrstaff_name'] = function () {
            return $this->hrStaff['staff_name'];
        };
        $fields['hrstaff_mobile'] = function () {
            return $this->hrStaff['staff_mobile'];
        };
        $fields['hrstaff_email'] = function () {
            return $this->hrStaff['staff_email'];
        };
        $fields['hrstaff_code'] = function () {
            return $this->hrStaff['staff_code'];
        };
        $fields['wh_code'] = function () {
            return $this->bsWh['wh_code'];
        };
        $fields['wh_id'] = function () {
            return $this->bsWh['wh_id'];
        };
        $fields['wh_name'] = function () {
            return $this->bsWh['wh_name'];
        };
        $fields['DISTRICT_ID'] = function () {
            return $this->bsWh['district_id'];
        };
        $fields['wh_addr'] = function () {
            return $this->bsWh['wh_addr'];
        };
        $fields['wh_state'] = function () {
            return $this->bsWh['wh_state'];
        };
        $fields['wh_statew'] = function () {
            if (($this->bsWh['wh_state']) == 'Y') {
                return "有效";
            }
            return "无效";
        };
        $fields['people']=function(){
            return $this->bsWh['people'];
        };
        $fields['company']=function(){
            if(($this->bsWh['company'])==0){
               return "";
            }else{
                return $this->bsWh['company'];
            }
        };
        $fields['wh_typew'] = function () {
            if (($this->bsWh['wh_type']) == '100881') {
                return "普通仓库";
            } else if (($this->bsWh['wh_type']) == '100882') {
                return "恒温恒湿仓库";
            } else if (($this->bsWh['wh_type']) == '3') {
                return "危化品仓库";
            } else if (($this->bsWh['wh_type']) == '100883') {
                return "贵重物品仓库";
            } else if (($this->bsWh['wh_type']) =='100884') {
                return "其它仓库";
            }
        };
        $fields['wh_type'] = function () {
            return $this->bsWh['wh_type'];
        };
        $fields['wh_levw'] = function () {
            if ($this->bsWh['wh_lev'] == '100893') {
                return "一级";
            } else if ($this->bsWh['wh_lev'] == '100894') {
                return "二级";
            } else if ($this->bsWh['wh_lev'] == '100895') {
                return "三级";
            } else if($this->bsWh['wh_lev'] == '100896'){
                return "其它";
            }
        };
//        $fields['wh_lev'] = function () {
//            return $this->bsWh['wh_lev'];
//        };
        $fields['wh_attrw'] = function () {
            if ($this->bsWh['wh_attr'] == 'Y') {
                return "自有";
            } else {
                return "外租";
            }
        };
        $fields['wh_attr'] = function () {
            return $this->bsWh['wh_attr'];
        };
        $fields['yn_deliv'] = function () {
            return $this->bsWh['yn_deliv'];
        };
        $fields['wh_yn'] = function () {
            if ($this->bsWh['wh_yn'] == 'Y') {
                return "是";
            } else {
                return "否";
            }
        };
        $fields['remarks'] = function () {
            return $this->bsWh['remarks'];
        };
        $fields['OPPER'] = function () {
            return $this->bsWh['opper'];
        };
        $fields['OPP_DATE'] = function () {
            return $this->bsWh['opp_date'];
        };
        $fields['NWER'] = function () {
            return $this->bsWh['nwer'];
        };
        $fields['NW_DATE'] = function () {
            return $this->bsWh['nw_date'];
        };
        $fields['wh_naturew'] = function () {
            if ($this->bsWh['wh_nature'] == 'Y') {
                return "保税";
            } else {
                return "非保税";
            }
        };
        $fields['wh_nature'] = function () {
            return $this->bsWh['wh_nature'];
        };
        $fields['opp_ip'] = function () {
            return $this->bsWh['opp_ip'];
        };
        //---end关联多张表字段-----//

        //--根据最后一阶地址代码获取全部地址--//
        $fields['address'] = function () {
            $address_id = $this->bsWh['district_id'];//最后一阶的地址代码
            $addr[] = $this->bsWh['wh_addr'];//详细地址
            while ($address_id > 0) {
                $addr_info = BsDistrict::findOne($address_id);
                $address_id = $addr_info->district_pid;
                $addr[] = $addr_info->district_name;
            }
            return implode(" ", array_reverse($addr));
        };
        //---------end-------------//

        //--获取地址（修改地址时使用）--//
        $fields['districtData'] = function () {
            $id = $this->bsWh['district_id'];//最后一阶的地址代码
            $districtId = [];
            $districtName = [];
            while ($id > 0) {
                $model = BsDistrict::findOne($id);
                $districtId[] = $model->district_id;
                $districtName[] = BsDistrict::find()->select('district_id,district_name')->where(['is_valid' => '1', 'district_pid' => $model->district_pid])->all();
                $id = $model->district_pid;
            }
            return [
                'districtId' => array_reverse($districtId),
                'districtName' => array_reverse($districtName),
            ];
        };
        //---------------end------------------//
        return $fields;
    }

}