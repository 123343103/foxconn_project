<?php

namespace app\modules\rpt\models\show;
use app\modules\rpt\models\RptTemplate;

class TemplateShow extends RptTemplate
{
    public function fields(){
        $fields = parent::fields();

        $field['status']=function(){
            switch ($this->rptt_status) {
                case self::READ_STATUS :
                    return '已读';
                    break;
                case self::UNREAD_STATUS :
                    return '未读';
                    break;
                default :
                    return null;
            }
        };
        return $fields;
    }


}