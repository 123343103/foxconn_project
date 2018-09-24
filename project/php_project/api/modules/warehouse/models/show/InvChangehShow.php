<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/10
 * Time: 下午 03:54
 */
namespace app\modules\warehouse\models\show;

use app\modules\hr\models\HrStaff;
use app\modules\warehouse\models\InvChangeh;
use app\modules\common\models\BsDistrict;
use yii\helpers\Html;

class InvChangehShow extends InvChangeh
{
    public function fields()
    {
        $fields = parent::fields();
        //报废单状态
        $fields['chh_status'] = function () {
            switch ($this->chh_status) {
                case InvChangeh::STATUS_ADD:
                    return '待提交';
                case InvChangeh::STATUS_WAIT:
                    return '审核中';
                case InvChangeh::STATUS_PENDING:
                    return '审核完成';
                case InvChangeh::STATUS_INMOVE:
                    return '已通知移仓';
                case InvChangeh::STATUS_PREPARE:
                    return '驳回';
                case InvChangeh::STATUS_MOVE:
                    return '已作废';
                default:
                    return '';
            }
        };
        $fields['o_status'] = function () {
            switch ($this->o_status) {
                case InvChangeh::STATUS_OUTWAIT:
                    return '待出库';
                case InvChangeh::STATUS_OUTACTION:
                    return '已出库';
                default:
                    return '';
            }
        };
        $fields['in_status'] = function () {
            switch ($this->in_status) {
                case InvChangeh::STATUS_INWAIT:
                    return '待入库';
                case InvChangeh::STATUS_INACTION:
                    return '已入库';
                default:
                    return '';
            }
        };
        $fields['status'] = function () {
            return $this->chh_status;
        };
        $fields['chh_typeName'] = function () {
            return $this->chhType['business_value'];
        };
        $fields['wh_name'] = function () {
            return $this->wh['wh_name'];
        };
        $fields['wh_code'] = function () {
            return $this->wh['wh_code'];
        };
        $fields['wh_attr'] = function () {
            return $this->wh['wh_attr'] == "Y" ? "自有" : "外租";
        };
        //出仓
        $fields['wh_name2'] = function () {
            return $this->wh2['wh_name'];
        };
        $fields['wh_code2'] = function () {
            return $this->wh2['wh_code'];
        };
        $fields['wh_attr2'] = function () {
            return $this->wh2['wh_attr'] == "Y" ? "自有" : "外租";
        };
        $fields['create_by'] = function () {
            return $this->staff['staff_name'];
        };
        $fields['review_by'] = function () {
            return $this->staffss['staff_name'];
        };
        $fields['staffCode'] = function () {
            return $this->staffCode['staff_code'];
        };
        $fields['organization'] = function () {
            return $this->organizationss['organization_name'];
        };
//        $fields['invChangeLInfo'] = function () {
//            return $this->invChangeLInfo;
//        };

        return $fields;
    }
}