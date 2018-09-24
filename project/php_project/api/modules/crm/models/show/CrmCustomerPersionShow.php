<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/26
 * Time: 11:33
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustomerPersion;
use yii\helpers\Html;

class CrmCustomerPersionShow extends CrmCustomerPersion
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['birthPlace'] = function(){
            return $this->area['district_name'];
        };

        $fields['isPost'] = function(){
            switch ($this->ccper_ispost){
                case self::POST_ON:
                    return "在职";
                    break;
                case self::POST_LEAVE:
                    return "离职";
                    break;
                default:
                    return "/";
            }
        };

        $fields['shareholder'] = function(){
            switch($this->ccper_isshareholder){
                case self::SHAREHOLDER_NO:
                    return "否";
                    break;
                case self::SHAREHOLDER_YES:
                    return "是";
                    break;
                default:
                    return "/";
            }
        };
        $fields['sex'] = function(){
            switch($this->ccper_sex){
                case '1':
                    return "男";
                    break;
//                case '2':
                case '0':
                    return "女";
                    break;
                default:
                    return "/";
            }
        };
        $fields['ccper_fax'] = function(){
            return $this->ccper_fax;
        };
        $fields['ccper_deparment'] = function(){
            return $this->ccper_deparment;
        };
        $fields['ccper_wechat'] = function(){
            return $this->ccper_wechat;
        };

        return $fields;
    }
}
