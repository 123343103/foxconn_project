<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\show;
use app\modules\crm\models\CrmActivePay;
//活动缴费显示模型
class CrmActivePayShow extends CrmActivePay
{
    public function fields()
    {
        $fields=parent::fields();
        //是否开票
        $fields['isBill']=function(){
            switch($this->actpaym_isfp){
                case 0:
                    return '未开票';
                    break;
                case 1:
                    return '已开票';
                    break;
                default:
                    return '';
            }
        };
        return $fields;
    }
}