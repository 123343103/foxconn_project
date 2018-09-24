<?php
namespace app\modules\warehouse\models\show;

use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\BsInvWarnH;


class  SetInventoryWarningShow extends BsInvWarnH{
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}