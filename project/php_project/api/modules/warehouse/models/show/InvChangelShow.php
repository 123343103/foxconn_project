<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/21
 * Time: 下午 05:00
 */
namespace app\modules\warehouse\models\show;

use app\modules\warehouse\models\InvChangel;

class InvChangelShow extends InvChangel
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['mode'] = function () {
            switch ($this->mode) {
                case 0:
                    return '垃圾回收';
                case 1:
                    return '销毁';
                case 2:
                    return '废料变卖';
                case 4:
                    return '低价转让';
            }
        };

        $fields['pdt_no'] = function () {
            return $this->material['part_no'];
        };
        $fields['pdt_name'] = function () {
            return $this->material['pdt_name'];
        };
        $fields['catg_name'] = function () {
            return $this->proType['catg_name'];
        };
        $fields['tp_spec'] = function () {
            return $this->material['tp_spec'];
        };
        $fields['unit'] = function () {
            return $this->material['unit'];
        };
         $fields['wh_name'] = function () {
             return $this->wh['wh_name'];
         };
        $fields['wh_name2'] = function () {
            return $this->whsecond['wh_name'];
        };
        $fields['st_code'] = function () {
            return $this->store['st_code'];
        };
        $fields['st_code2'] = function () {
            return $this->store2['st_code'];
        };
        $fields['catg_name'] = function () {
            return $this->proType['catg_name'];
        };
//        $fields['invChangeL'] = function (){
//            return $this->invChangeLInfo;
//        };
        return $fields;
    }
}