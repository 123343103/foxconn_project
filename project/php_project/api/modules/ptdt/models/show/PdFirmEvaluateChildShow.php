<?php
/**
 * User: F1677929
 * Date: 2016/11/23
 */
namespace app\modules\ptdt\models\show;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\PdFirmEvaluateChild;
use app\modules\ptdt\models\PdFirmEvaluateResult;
/**
 * 厂商评鉴子表显示
 */
class PdFirmEvaluateChildShow extends PdFirmEvaluateChild
{
    public function fields()
    {
        $fields = parent::fields();
        //评鉴信息列表数据
        $fields['evaluateDepartment'] = function () {
            switch ($this->evaluateResult->evaluate_department) {
                case PdFirmEvaluateResult::FIRM :
                    return '厂商评鉴';
                    break;
                case PdFirmEvaluateResult::PROCUREMENT :
                    return '采购评鉴';
                    break;
                case PdFirmEvaluateResult::MANAGEMENT :
                    return '工管评鉴';
                    break;
                default :
                    return null;
            }
        };
        $fields['evaluatePerson'] = function () {
            return HrStaff::findOne($this->evaluateResult->create_by)->staff_name;
        };
        $fields['evaluateResult'] = function () {
            switch ($this->evaluateResult->evaluate_result) {
                case PdFirmEvaluateResult::PASS :
                    return '通过';
                    break;
                case PdFirmEvaluateResult::NO_PASS :
                    return '不通过';
                    break;
                case PdFirmEvaluateResult::EVALUATE_PASS :
                    return '评鉴通过';
                    break;
                case PdFirmEvaluateResult::CANCEL_ADD :
                    return '取消新增';
                    break;
                case PdFirmEvaluateResult::PLAN_TUTOR :
                    return '安排辅导';
                    break;
                case PdFirmEvaluateResult::IMPROVE_REVIEW :
                    return '改善后复查';
                    break;
                default :
                    return '';
            }
        };
        $fields['waitEvaluateDepartment'] = function () {
            if ($this->firmEvaluate && $this->firmEvaluate->evaluate_result==PdFirmEvaluateResult::PASS) {
                if ($this->purchaseEvaluate) {
                    if ($this->purchaseEvaluate->evaluate_result == PdFirmEvaluateResult::EVALUATE_PASS) {
                        if ($this->manageEvaluate) {
                            $waitEvaluateDepartment = '无';
                        } else {
                            $waitEvaluateDepartment = '工管评鉴';
                        }
                    } else {
                        $waitEvaluateDepartment = '无';
                    }
                } else {
                    if ($this->manageEvaluate) {
                        if ($this->manageEvaluate->evaluate_result == PdFirmEvaluateResult::EVALUATE_PASS) {
                            if ($this->purchaseEvaluate) {
                                $waitEvaluateDepartment = '无';
                            } else {
                                $waitEvaluateDepartment = '采购评鉴';
                            }
                        } else {
                            $waitEvaluateDepartment = '无';
                        }
                    } else {
                        $waitEvaluateDepartment = '采购评鉴,工管评鉴';
                    }
                }
            } else {
                $waitEvaluateDepartment = '无';
            }
            return $waitEvaluateDepartment;
        };
        return $fields;
    }
}