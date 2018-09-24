<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2017/3/3
 * Time: 上午 11:19
 */
namespace app\modules\hr\models\show;
use app\modules\hr\models\HrStaffTitle;

class HrStaffTitleShow extends HrStaffTitle{
    public function fields(){
        $fields = parent::fields();

        return $fields;
    }

}