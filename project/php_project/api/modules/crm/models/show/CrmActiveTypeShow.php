<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\show;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveType;
use app\modules\hr\models\HrStaff;
//活动类型显示模型
class CrmActiveTypeShow extends CrmActiveType
{
    public function fields()
    {
        $fields=parent::fields();
        //创建人
//        $fields['createBy']=function(){
//            return HrStaff::findOne($this->create_by);
//        };
//        //修改人
//        $fields['updateBy']=function(){
//            return HrStaff::findOne($this->update_by);
//        };
//        //活动方式
//        $fields['activeWay']=function(){
//            return BsPubdata::findOne($this->acttype_way);
//        };
//        //状态
//        $fields['activeTypeStatus']=function(){
//            switch($this->acttype_status){
//                case 10:
//                    return '启用';
//                    break;
//                case 20:
//                    return '禁用';
//                    break;
//                default:
//                    return '';
//            }
//        };
        return $fields;
    }
}