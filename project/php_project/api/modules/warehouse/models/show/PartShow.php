<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/12
 * Time: 下午 05:21
 */
namespace app\modules\warehouse\models\show;

use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsSt;

class PartShow extends BsPart
{
    public function fields()
    {
        $fields=parent::fields();
        $fields['wh_name']=function(){
            return $this->whCode['wh_name'];
        };
        $fields['bsSt']=function(){
            return $this->bsSt;
        };

        return $fields;
    }
}