<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/30
 * Time: 上午 09:12
 */
namespace app\modules\warehouse\models\show;

use app\modules\warehouse\models\BsVeh;

class BsVehShow extends BsVeh
{
    public function fields()
    {
        $fields=parent::fields();
        $fields['log_cmp_name']=function(){
            return $this->logCode['log_cmp_name'];
        };
        return $fields;
    }
}