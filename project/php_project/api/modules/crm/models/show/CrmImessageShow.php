<?php

namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmImessage;
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
class CrmImessageShow extends CrmImessage
{
    public function fields()
    {
        $fields = parent::fields();
        $fields["sentman"] = function () {
            return isset($this->creator->staff_name) ? $this->creator['staff_name'] : "";
        };
        $fields["receiver"] = function () {
            return isset($this->receiver) ? $this->receiver['staff_name'] : "";
        };
        $fields["code"] = function () {
            return $this->receiver ? $this->receiver['staff_code'] : "";
        };
        $fields["type"] = function () {
            return $this->imesg_type == 1 ? "信息" : "邮件";
        };
        $fields["status"] = function () {
//            return $this->imesg_status==1?"激活":"无效";
            switch ($this->imesg_status) {
                case 1:
                    if ($this->imesg_btime > date('Y-m-d H:i', time())) {
                        return '未开始';
                    } else if ($this->imesg_btime <= date('Y-m-d H:i', time()) && $this->imesg_etime >= date('Y-m-d H:i', time())) {
                        return '进行中';
                    } else if ($this->imesg_etime < date('Y-m-d H:i', time())) {
                        return '已结束';
                    }
                    break;
                case 2:
                    return '已关闭';
                    break;
                default:
                    return '已删除';
            }
        };

        $fields["cust_sname"] = function () {
            return $this->customerInfo['cust_sname'] ? $this->customerInfo['cust_sname'] : "";
        };
        return $fields;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->imesg_sentdate = $this->imesg_sentdate ? date("Y-m-d", strtotime($this->imesg_sentdate)) : null;
        $this->imesg_btime = $this->imesg_btime ? date("Y-m-d H:i", strtotime($this->imesg_btime)) : null;
        $this->imesg_etime = $this->imesg_etime ? date("Y-m-d H:i", strtotime($this->imesg_etime)) : null;
    }
}
