<?php
/**
 * User: F1677929
 * Date: 2016/11/23
 */
namespace app\modules\ptdt\models\show;
use app\modules\ptdt\models\PdVisitResume;
use yii\bootstrap\Html;
//拜访履历主表显示
class PdVisitResumeShow extends PdVisitResume
{
    public function fields()
    {
        $fields=parent::fields();
        //厂商编号
//        $fields['firm_code']=function(){
//            return $this->firm['firm_code'];
//        };
//        //厂商全称
//        $fields['firm_sname']=function(){
//            return $this->firm['firm_sname'];
//        };
//        //厂商简称
//        $fields['firm_shortname']=function(){
//            return $this->firm['firm_shortname'];
//        };
//        //厂商类型
//        $fields['firmType']=function(){
//            return $this->firm['firmType']['bsp_svalue'];
//        };
//        //厂商来源
//        $fields['firmSource']=function(){
//            return $this->firm['firmSource']['bsp_svalue'];
//        };
//        //拜访状态
//        $fields['visitStatus']=function(){
//            switch($this->vih_status){
//                case self::VISIT_ING:
//                    return '拜访中';
//                    break;
//                case self::VISIT_COMPLETE:
//                    return '拜访完成';
//                    break;
//                default :
//                    return '';
//            }
//        };
//        //是否为集团供应商
//        $fields['groupSupplier']=function(){
//            switch($this->firm['firm_issupplier']){
//                case '1':
//                    return '是';
//                    break;
//                case '0':
//                    return '否';
//                    break;
//                default :
//                    return '';
//            }
//        };
//        //厂商品牌
//        $fields['firm_brand']=function(){
//            return $this->firm['firm_brand'];
//        };
//        //分级分类
//        $fields['oneCategory']=function(){
//            return $this->firm['categoryName'];
//        };
        return $fields;
    }
}