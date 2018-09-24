<?php

namespace app\modules\rpt\models\show;
use app\modules\rpt\models\RptCategory;

class RptTreeShow extends RptCategory
{
    public function fields(){
        $fields = parent::fields();

        // 模板信息
        $fields['template'] = function ()
        {
//            $rpt['rpt'] = $this->rpt;  // 报表信息
//            return array_merge($this->template,$rpt);
            return $this->template;
        };

        // 报表信息
        $fields['rpt'] = function ()
        {
            return $this->rpt;
        };
        return $fields;
    }
}