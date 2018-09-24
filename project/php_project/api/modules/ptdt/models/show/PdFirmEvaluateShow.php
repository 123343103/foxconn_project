<?php
/**
 * User: F1677929
 * Date: 2016/11/23
 */
namespace app\modules\ptdt\models\show;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\ptdt\models\PdFirmEvaluate;
/**
 * 厂商评鉴主表显示
 */
class PdFirmEvaluateShow extends PdFirmEvaluate
{
    public function fields()
    {
        $fields = parent::fields();
        //厂商信息
        $fields['firmInfo'] = function () {
            return PdFirmShow::findOne($this->firm_id);
        };
        //新增人
        $fields['createPerson'] = function () {
            return HrStaffShow::findOne($this->create_by);
        };
        //评鉴状态
        switch ($this->evaluate_status) {
            case self::EVALUATE_ING :
                $this->evaluate_status = '评鉴中';
                break;
            case self::EVALUATE_PASS :
                $this->evaluate_status = '评鉴通过';
                break;
            case self::EVALUATE_NO_PASS :
                $this->evaluate_status = '评鉴不通过';
                break;
            default :
                $this->evaluate_status = null;
        }
        return $fields;
    }
}