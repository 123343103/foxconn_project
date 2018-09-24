<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 下午 01:54
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\BsPart;

class BsPartShow extends BsPart
{
    public function fields()
    {
        $fields = parent::fields();
        //倉庫名稱
        $fields['wh_name'] = function () {
            return $this->whCode['wh_name'];
        };


        return $fields;
    }

}