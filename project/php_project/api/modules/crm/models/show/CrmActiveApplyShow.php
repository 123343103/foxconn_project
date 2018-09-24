<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\show;
use app\modules\crm\models\CrmActiveApply;
use app\modules\crm\models\CrmActiveCheckIn;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActivePay;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\CrmCustomerInfo;
//活动报名显示模型
class CrmActiveApplyShow extends CrmActiveApply
{
    public function fields()
    {
        $fields=parent::fields();
        //活动类型
        $fields['active_type'] = function(){
            return $this->activeType['acttype_name'];
        };
        //活动名称
        $fields['active_name'] = function(){
            return $this->activeName['actbs_name'];
        };
        $fields['checkInInfo'] = function(){
            return $this->acth_ischeckin==1?'已签到':'否';
        };
        $fields['check_name'] = function(){
//            return $this->activeCheck;
            $arr = $this->activeCheck;
            $str = '';
            if(empty($arr)){
                return '';
            }
            foreach ($arr as $key => $val){
                if($val['actcin_phone'] == null){
                    $str .= $val['actcin_name'].',';
                }else{
                    $str .= $val['actcin_name'].'('.$val['actcin_phone'].')'.',';
                }
            }
            return rtrim($str,',');
        };
        return $fields;
    }
}