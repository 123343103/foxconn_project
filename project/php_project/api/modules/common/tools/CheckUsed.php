<?php
namespace app\modules\common\tools;

use app\modules\common\models\VActbsId;
use app\modules\common\models\VCsareaId;
use app\modules\common\models\VCustId;
use app\modules\common\models\VSroleId;
use app\modules\common\models\VStaffId;
use app\modules\common\models\VStsId;
use app\modules\common\models\VUserId;

class CheckUsed
{
    /**
     * 检查数据是否被引用
     * @param integer $id 要检查的数据id
     * @param string $name 要检查的数据名
     * @return array
     */
    function check($id,$name)
    {
        switch ($name) {
            case 'cust_id':
                $used = VCustId::isUsed($id);
                return $used;
                break;
            case 'staff_id':
                $used = VStaffId::isUsed($id);
                return $used;
                break;
            case 'user_id':
                $used = VUserId::isUsed($id);
                return $used;
                break;
            case 'csarea_id':
                $used = VCsareaId::isUsed($id);
                return $used;
                break;
            case 'sarole_id':
                $used = VSroleId::isUsed($id);
                return $used;
                break;
            case 'sts_id':
                $used = VStsId::isUsed($id);
                return $used;
                break;
            case 'actbs_id':
                $used = VActbsId::isUsed($id);
                return $used;
                break;
            default:
                $res['status'] = 0;
                $res['msg'] = '传入参数有误';
                return $res;
        }
    }
}