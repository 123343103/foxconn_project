<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2016/12/7
 * Time: 15:07
 */
namespace app\modules\app\models\show;

use app\modules\ptdt\models\PdAccompany;

class AccompanyShow extends  PdAccompany
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['staffInfo']=function()
        {
            return [
                'staffCode' => $this->staffAccompany->staff_code,
                'staffName' => $this->staffAccompany->staff_name,
                'staffTitle' => !empty($this->staffAccompany->title)?$this->staffAccompany->title->title_name:'',
                'staffMobile' => $this->staffAccompany->staff_mobile,
                'staffJob' => $this->staffAccompany->job_task,
            ];
        };


        return $fields;
    }
}