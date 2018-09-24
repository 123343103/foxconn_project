<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\show;
use app\modules\crm\models\CrmActiveCheckIn;
//活动签到显示模型
class CrmActiveCheckInShow extends CrmActiveCheckIn
{
    public function fields()
    {
        $fields=parent::fields();
        //是否签到
        $fields['isCheckIn']=function(){
            switch($this->actcin_ischeckin){
                case 0:
                    return '否';
                    break;
                case 1:
                    return '是';
                    break;
                default:
                    return '';
            }
        };
        return $fields;
    }
}