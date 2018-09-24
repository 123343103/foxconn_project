<?php
namespace app\modules\system\models\show;

use app\modules\common\models\BsTradConditions;

class TradConditionShow extends BsTradConditions
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['staffInfo'] = function () {
            if (empty($this->staffName)) {
                return '';
            }
            return [
                'staff_name' => $this->staffName->staff_name,
                'staff_code' => $this->staffName->staff_code,
            ];
        };
        return $fields;
    }
}