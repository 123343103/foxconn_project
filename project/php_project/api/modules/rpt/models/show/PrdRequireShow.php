<?php

namespace app\modules\rpt\models\show;
use app\modules\ptdt\models\PdRequirement;

class PrdRequireShow extends PdRequirement
{
    protected $aa;
    protected $name;
    protected $shuliang;
    public function fields(){
        $fields = parent::fields();
        $fields['aa'] = function ()
        {
            return $this->aa;
        };
        $fields['name'] = function ()
        {
            return $this->name;
        };
        $fields['shuliang'] = function ()
        {
            return $this->shuliang;
        };
        return $fields;
    }
}