<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/15
 * Time: 上午 08:55
 */

namespace app\modules\warehouse\models\show;


use app\modules\common\models\BsCurrency;
use app\modules\warehouse\models\BsWhPrice;
use app\modules\warehouse\models\WhPricel;

class WhPricelShow extends WhPricel
{
    public function fields()
    {
        $fields = parent::fields();
        $fields['whpb_code'] = function () {
            return $this->bsWhPrice['whpb_code'];
        };
        $fields['whpb_sname'] = function () {
            return $this->bsWhPrice['whpb_sname'];
        };
        $fields['stcl_description'] = function () {
            return $this->bsWhPrice['stcl_description'];
        };
        $fields['cur_code'] = function () {
            return $this->bsCurrency['cur_code'];
        };
        return $fields;
    }
}