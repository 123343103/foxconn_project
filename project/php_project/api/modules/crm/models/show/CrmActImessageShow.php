<?php

namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmActImessage;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "crm_act_imessage".
 *
 * @property integer $obj_id
 * @property integer $imesg_id
 * @property string $imesg_type
 * @property string $imesg_sentman
 * @property string $imesg_sentdate
 * @property string $imesg_subject
 * @property string $imesg_notes
 * @property string $imesg_status
 * @property string $imesg_remark
 */
class CrmActImessageShow extends CrmActImessage
{
    public function fields(){
        $fields=parent::fields();
        $fields["imesg_sentman"]=function(){
            return isset($this->creator->staff_name)?$this->creator->staff_name:"";
        };
        $fields["imesg_type"]=function(){
            return $this->imesg_type==1?"信息":"邮件";
        };
        return $fields;
    }
}
