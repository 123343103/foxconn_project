<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/19
 * Time: 上午 11:25
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\BsInvWarnH;
use yii\helpers\Html;

class BsInvWarnShow extends BsInvWarnH
{
    public function fields()
    {
        $fields = parent::fields();
        //預警編號
//        $fields['inv_id'] = function () {
//            return $this->bsInvWarn['inv_id'];
//        };
        //倉庫名稱
        $fields['wh_name'] = function () {
            return $this->bsWh['wh_name'];
        };
        //操作人名稱
        $fields['user_name'] = function () {
            return $this->staff['staff_name'];
        };
        //状态
        $fields['so_type'] = function () {
            switch ($this->so_type) {
                case 1:
                    return "待提交";
                    break;
                case 10:
                    return "审核中";
                    break;
                case 20:
                    return "审核完成";
                    break;
                case 30:
                    return "驳回";
                    break;
            }
        };
//        $fields['OPP_DATE']=function(){
//            return date('Y-m-d',$this->OPP_DATE);
//        };


        return $fields;
    }

}