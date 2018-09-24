<?php

namespace app\modules\rpt\models\show;
use app\modules\rpt\models\RptTemplate;

class TemplateParamShow extends RptTemplate
{
    public function fields(){
        $fields = parent::fields();

        // 报表信息
        $fields['params'] = function ()
        {
            return $this->params;
        };

        return $fields;
    }
}