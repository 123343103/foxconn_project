<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models\show;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveName;
use app\modules\hr\models\HrStaff;
//活动名称显示模型
class CrmActiveNameShow extends CrmActiveName
{
    public function fields()
    {
        $fields=parent::fields();
        //活动类型
//        $fields['activeType']=function(){
//            return CrmActiveTypeShow::findOne($this->acttype_id);
//        };
//        //创建人
//        $fields['createBy']=function(){
//            return HrStaff::findOne($this->create_by);
//        };
//        //修改人
//        $fields['updateBy']=function(){
//            return HrStaff::findOne($this->update_by);
//        };
//        //状态
//        $fields['activeNameStatus']=function(){
//            switch($this->actbs_status){
//                case 10:
//                    return '未开始';
//                    break;
//                case 20:
//                    return '已展开';
//                    break;
//                case 30:
//                    return '已结束';
//                    break;
//                default:
//                    return '';
//            }
//        };
//        //活动方式
//        $fields['activeWay']=function(){
//            return BsPubdata::find()->where(['bsp_id'=>$this->acrbs_way])->select('bsp_svalue')->one();
//        };
        return $fields;
    }
}