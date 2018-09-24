<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/21
 * Time: 14:29
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdMaterialCode;

class PdMaterialCodeShow extends PdMaterialCode{
    public $typeName;
    public function fields(){
        $fields = parent::fields();
        $fields['typeName'] = function () {
            return [
                $this->productTypeSix->parent->parent->parent->parent->parent->type_name,
                $this->productTypeSix->parent->parent->parent->parent->type_name,
                $this->productTypeSix->parent->parent->parent->type_name,
                $this->productTypeSix->parent->parent->type_name,
                $this->productTypeSix->parent->type_name,
                $this->productTypeSix->type_name,
            ];
        };
        return $fields;
    }
}