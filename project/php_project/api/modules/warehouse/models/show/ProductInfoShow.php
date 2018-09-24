<?php
namespace app\modules\warehouse\models\show;

use app\modules\warehouse\models\BsInvt;

class ProductInfoShow extends BsInvt{
    public function fields(){
        $fields = parent::fields();
        return $fields;
    }
}