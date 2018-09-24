<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/28
 * Time: 15:19
 */
namespace app\modules\system\models\show;

use app\modules\system\models\VerifyrecordChild;

class VerifyrecordChildShow extends VerifyrecordChild
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['verifyOrg'] = function(){
            if($this->userStaff == null){
                return null;
            }else{
                return $this->userStaff->staff['organization_code'];
            }
        };
        $fields['verifyName'] = function(){
            if($this->userStaff == null){
                return $this->item['title'];
            }else{
                return $this->userStaff->staff['staff_name'];
            }
        };
        $fields['verifyStatus'] = function(){
            switch ($this->vcoc_status){
                case self::STATUS_DEFAULT:
                    return "未达";
                break;
                case self::STATUS_CHECKIND:
                    return "待审";
                break;
                case self::STATUS_PASS:
                    return "审核通过";
                    break;
                case self::STATUS_OVER:
                    return "跳过未达";
                    break;
                default:
                    return "<span style='color:#f00;'>驳回</span>";
                break;
            }
        };

        return $fields;
    }
}