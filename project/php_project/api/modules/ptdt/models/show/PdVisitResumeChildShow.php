<?php
/**
 * User: F1677929
 * Date: 2016/11/23
 */
namespace app\modules\ptdt\models\show;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\PdAccompany;
use app\modules\ptdt\models\PdReception;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\PdVisitResumeChild;
//拜访履历子表显示模型
class PdVisitResumeChildShow extends PdVisitResumeChild
{
    public function fields()
    {
        $fields = parent::fields();
        //拜访计划
        $fields['visitPlan'] = function () {
            return PdVisitPlan::findOne($this->visit_planID);
        };
        //接待人
        $fields['receptionPerson']=function(){
            return PdReception::findAll(['rece_type'=>PdReception::RECE_TYPE_VISIT,'l_id'=>$this->vil_id]);
        };
        //拜访人
        $fields['visitPerson']=function(){
            return HrStaff::findOne($this->vih_vis_person);
        };
        //陪同人
        $fields['accompanyPerson']=function(){
            return PdAccompanyShow::findAll(['vacc_type'=>PdAccompany::RESUME_ACCOMPANY_PERSON,'l_id'=>$this->vil_id]);
        };
        return $fields;
    }
}