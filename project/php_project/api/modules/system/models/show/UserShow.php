<?php
/**
 * User: F3859386
 * Date: 2017/3/2
 * Time: 16:29
 */
namespace app\modules\system\models\show;

use app\models\User;
use app\modules\system\models\AuthAssignment;
use app\modules\system\models\AuthItem;

class UserShow extends User
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['staffInfo'] = function () {
            if (empty($this->staff)) {
                return '';
            }
            return [
                'staff_name' => $this->staff->staff_name,
                'staff_code' => $this->staff->staff_code,
                'staff_mobile' => $this->staff->staff_mobile,
                'staff_email' => $this->staff->staff_email,
                'other_contacts' => $this->staff->other_contacts
            ];
        };
        $fields['companyName'] = function () {
            return $this->company['company_name'];
        };
        $fields['bsp_svalue'] = function () {
            return $this->pubdata['bsp_svalue'];
        };
        $fields['roles'] = function () {
            $roles = "";
            $ass = AuthAssignment::find()->where(['user_id' => $this->user_id])->all();
            foreach ($ass as $k => $v) {
                $item = AuthItem::findone(['name' => $v->item_name]);
                if ($k == count($ass) - 1) {
                    $roles .= $item["title"];
                } else {

                    $roles .= $item["title"] . ",";
                }
            }
            return $roles;
        };
        return $fields;
    }
}