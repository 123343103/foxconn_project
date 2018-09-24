<?php
/**
 * User: F1677929
 * Date: 2016/11/23
 */
namespace app\modules\ptdt\models\show;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\ptdt\models\PdFirmEvaluateResult;
/**
 * 厂商评鉴结果显示
 */
class PdFirmEvaluateResultShow extends PdFirmEvaluateResult
{
    public function fields()
    {
        $fields = parent::fields();
        //评鉴结果
        $fields['evaluateResult'] = function () {
            switch ($this->evaluate_result) {
                case self::PASS :
                    return '通过';
                    break;
                case self::NO_PASS :
                    return '不通过';
                    break;
                case self::EVALUATE_PASS :
                    return '评鉴通过';
                    break;
                case self::CANCEL_ADD :
                    return '取消新增';
                    break;
                case self::PLAN_TUTOR :
                    return '安排辅导';
                    break;
                case self::IMPROVE_REVIEW :
                    return '改善后复查';
                    break;
                default :
                    return null;
            }
        };
        //评鉴人信息
        $fields['evaluatePerson'] = function () {
            return HrStaffShow::findOne($this->create_by);
        };
        return $fields;
    }
}