<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/7
 * Time: 下午 06:19
 */

namespace app\modules\warehouse\models\show;
use app\modules\warehouse\models\BsWh;
use app\modules\common\models\BsDistrict;
use yii\helpers\Html;

class SetWarehouseShow extends BsWh
{
    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }
}