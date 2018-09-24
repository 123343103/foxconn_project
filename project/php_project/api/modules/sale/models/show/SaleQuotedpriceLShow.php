<?php
namespace app\modules\sale\models\show;
use app\modules\common\models\BsCategory;
use app\modules\sale\models\SaleQuotedpriceL;

class SaleQuotedpriceLShow extends SaleQuotedpriceL
{
    public function fields()
    {
        $fields =  parent::fields();
        // 库存
        $fields['stock'] = function () {
            return $this->stock['invt_num'];
        };
        // 商品信息
        $fields['pdt_name'] = function () {
            return $this->products['pdt_name'];
        };
        $fields['pdt_no'] = function () {
            return $this->products['pdt_no'];
        };
        $fields['Specification'] = function () {
            return $this->specification['ATTR_NAME'];
        };
        // 产品重量  重量（净重）=产品重量*下单数量
        $fields['weight'] = function () {
            return $this->products['pdt_weight'];
        };
        // 单位
        $fields['unit_name'] = function () {
            return $this->unit['unit_name'];
        };
        // 还需采购
        $fields['require_purchase'] = function () {
            if ( $this->stock['invt_num']!=null && $this->sapl_quantity!=null ) {
                $num = $this->sapl_quantity - $this->stock['invt_num'];
                return $num>0 ? $num : 5;
            }
        };
        $fields['product'] = function () {
            return '料号: ' . $this->products['pdt_no'] . '</br>' . '品名: ' . $this->products['pdt_name'] . '</br>' . '规格: ' . $this->specification['ATTR_NAME'];
        };
        $fields["productCtg"]=function(){
            $result=[];
            $cat = BsCategory::findOne($this->productCtg['category_id']);
            if ($cat == null) {
                return null;
            }
            $result[] = $cat->category_sname;
            while($cat->p_category_id!='0'){
                $cat = BsCategory::findOne($cat->p_category_id);
                $result[] = $cat->category_sname;
            }
            krsort($result);
            return implode(">",$result);
        };
        // 运输方式
        $fields['transportMethod'] = function () {
            return $this->transportMethod;
        };
        // 配送方式
        $fields['deliveryMethod'] = function () {
            return $this->deliveryMethod;
        };
        // 出仓仓库
        $fields['warehouse'] = function () {
            return $this->warehouse['wh_name'];
        };
        return $fields;
    }
}