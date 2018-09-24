<?php
/**
 * User: F1676624
 * Date: 2017/7/25
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\LBsInvtList;

class LBsInvtListShow extends LBsInvtList
{
    public function fields()
    {
        $fields = parent::fields();
        //商品id
        $fields['pdt_id'] = function () {
            return $this->product['pdt_id'];
        };
        $fields = parent::fields();
        //商品规格型号
        $fields['tp_spec'] = function () {
            return $this->material['tp_spec'];
        };
        //商品单位
        $fields['unit'] = function () {
            return $this->material['unit'];
        };
        //商品名称
        $fields['pdt_name'] = function () {
            return $this->material['pdt_name'];
        };
        //商品品牌
        $fields['pdt_brand'] = function () {
            return $this->material['brand'];
        };
        //商品料号
        $fields['pdt_no'] = function () {
            return $this->material['part_no'];
        };
        //仓库名称
        $fields['wh_name'] = function () {
            return $this->warehouse['wh_name'];
        };
        //储位名称
        $fields['st_code'] = function () {
            return $this->store['st_code'];
        };
        return $fields;
    }
}