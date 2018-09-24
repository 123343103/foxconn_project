<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/28
 * Time: 15:19
 */
namespace app\modules\system\models\show;

use app\modules\system\models\Verifyrecord;
use app\modules\system\models\VerifyrecordChild;

class VerifyrecordShow extends Verifyrecord
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['child'] = function(){
            return $this->verifyChild;
        };
//        $fields['current'] = function(){
//            return $this->current;
//        };
        $fields['businessType'] = function(){
            return $this->businessType['business_type_desc'];
        };
        $fields['applyPerson'] = function(){
            return [
                'applyName' => $this->staff['staff_name'],
                'applyOrg' => $this->organization['organization_name'],
            ];
        };

        $fields['lastVerify'] = function(){
            $last=$this->lastVerify;
            if($last == null){
                return null;
            }else if($last['ver_acc_id'] == null){
                return $last->item['title'];
            }else{
                return $last->userStaff->staff['staff_name'];
            }
        };
        $fields['lastTime'] = function(){
            return $this->lastVerify['vcoc_datetime'];
        };
        $fields['currentVerify'] = function(){
            if($this->currentVerify == null){
                return null;
            }else if($this->currentVerify['ver_acc_id'] == null){
                return $this->currentVerify->item['title'];
            }else{
                return $this->currentVerify->userStaff->staff['staff_name'];
            }
        };
        $fields['nextVerify'] = function(){
            $next=$this->nextVerify;
            if($next == null){
                return null;
            }else if($next['ver_acc_id'] == null){
                return $next->item['title'];
            }else{
                return $next->userStaff->staff['staff_name'];
            }
        };
        return $fields;
    }
}