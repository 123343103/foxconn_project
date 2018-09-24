<?php
/**
 * User: F1677929
 * Date: 2016/11/28
 */
namespace app\modules\ptdt\models\show;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\show\HrStaffShow;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdFirmEvaluateApply;
/**
 * 厂商评鉴申请显示模型
 */
class PdFirmEvaluateApplyShow extends PdFirmEvaluateApply
{
    public function fields()
    {
        $fields = parent::fields();
        //处理厂商评鉴申请信息
        $this->apply_type = $this->evaluateApplyType->bsp_svalue;
        switch ($this->apply_status) {
            case self::CHECK_WAIT :
                $this->apply_status = '待审核';
                break;
            case self::CHECK_ING :
                $this->apply_status = '审核中';
                break;
            case self::CHECK_COMPLETE :
                $this->apply_status = '审核完成';
                break;
            case self::CHECK_REJECT :
                $this->apply_status = '被驳回';
                break;
            default :
                $this->apply_status = null;
         }
        //厂商信息
        $fields['firmInfo'] = function () {
            return PdFirmShow::findOne($this->firm_id);
        };
        //申请人信息
        $fields['applicantInfo'] = function () {
            return HrStaffShow::findOne($this->create_by);
        };
        return $fields;
    }
}