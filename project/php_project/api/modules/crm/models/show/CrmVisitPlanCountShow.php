<?php
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmVisitPlan;
use yii\helpers\Url;

class CrmVisitPlanCountShow extends CrmVisitPlan{
    public function fields(){
        $field = parent::fields();
        $field['title'] = function () {
            return $this->countPlan;
        };
        return $field;
    }
}