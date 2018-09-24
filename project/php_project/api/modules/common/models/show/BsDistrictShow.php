<?php
namespace app\modules\common\models\show;
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/7/19
 * Time: 下午 04:20
 */
use \app\modules\common\models\BsDistrict;

class BsDistrictShow extends BsDistrict
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['district_name'] = function(){
            return $this->district_name;
        };
        $fields['district_id'] = function(){
            return $this->district_id;
        };
        $fields['district_pid'] = function(){
            return $this->district_pid;
        };
        return $fields;
    }
}