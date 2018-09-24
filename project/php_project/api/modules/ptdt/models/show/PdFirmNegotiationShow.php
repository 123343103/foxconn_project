<?php
/**
 * User: F3859386
 * Date: 2016/12/1
 * Time: 20:04
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdNegotiation;
use yii\helpers\Html;

class PdFirmNegotiationShow extends PdNegotiation
{
    public function fields()
    {
        $fields = parent::fields();
        //关联厂商信息
        $fields['firm_sname']=function(){
            return $this->firm['firm_sname'];
        };
        $fields['firm_shortname']=function(){
            return $this->firm['firm_shortname'];
        };
        $fields['firm_salarea']=function(){
            return $this->firm->categoryName;
        };
        $fields['firm_brand']=function(){
            return $this->firm['firm_brand'];
        };
        $fields['firm_issupplier']=function(){
            return $this->firm['firm_issupplier']==1?'是':'否';
        };
        //公司来源
        $fields['firm_source']=function(){
            return $this->firm->firmSource['bsp_svalue'];
        };
        //类型
        $fields['firm_type']=function(){
            return $this->firm->firmType['bsp_svalue'];
        };
        //状态
        $fields['status']=function(){
                ;
            switch ($this->pdn_status){
                case static::STATUS_NEW:
                    return '新增';
                    break;
                case static::STATUS_DELETE:
                    return '删除';
                    break;
                case static::STATUS_HALF:
                    return '谈判中';
                    break;
                case static::STATUS_END:
                    return '谈判完成';
                    break;
            }
        };
        return $fields;
    }
}