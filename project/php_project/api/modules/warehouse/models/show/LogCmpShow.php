<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 10:04
 */
namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\BsLogCmp;

class LogCmpShow extends BsLogCmp
{
    public function fields()
    {
        $fields=parent::fields();
        $fields['para_name']=function(){
            return $this->logMode['para_name'];
        };
        $fields['log_type_name']=function(){
            return $this->logType['para_name'];
        };
        return $fields;
    }
}