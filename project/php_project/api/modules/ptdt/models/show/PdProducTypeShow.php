<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/29
 * Time: 上午 08:20
 */

namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdProductType;

class PdProducTypeShow extends PdProductType
{
    public $createBy;
    public $updateBy;

    public function fields()
    {
        $fields = parent::fields();
        $fields['createBy'] = function () {
            return [
                "name"=>$this->buildStaff['staff_name'],
                "code"=>$this->buildStaff['staff_code'],
            ];
        };
        $fields['updateBy'] = function () {
            return [
                "name"=>$this->updateStaff['staff_name'],
                "code"=>$this->updateStaff['staff_code'],
            ];
        };

        return $fields;
    }

}