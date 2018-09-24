<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/21
 * Time: 14:29
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdSupplier;

class PdSupplierShow extends PdSupplier{
//    public $mainProduct;
//    public $authorize;
//    public $createBy;
//    public $supplierTypes;
////    public $supplierSources;
//    public $sourceType;
//    public $agentsLevel;
//    public $supplierSalarea;
//    public $authorizeArea;
//    public $supplierComptype;
//    public $devcon;
//    public $payCondition;
//    public $traceCurrency;
//    public $supplierStatus;

    public function fields(){
        $fields = parent::fields();
//        //拟购买商品
        $fields['material']= function () {
            return  $this->material;
        };
        //谈判分析信息
        $fields['authorize'] = function(){
            if (isset($this->agentsAuthorize)) {
                return [
                    'pdaa_agents_grade' => $this->agentsAuthorize->agentsGrade->bsp_svalue,
                    'pdaa_authorize_area' => $this->agentsAuthorize->authorizeArea->bsp_svalue,
                    'pdaa_date' => $this->agentsAuthorize->pdaa_bdate.'~'.$this->agentsAuthorize->pdaa_edate,
                    'pdaa_settlement' => $this->agentsAuthorize->settlement->bnt_sname,
                ];
            } else {
                return null;
            }
        };

        //获取状态
        $fields['supplierStatus']=function(){
            switch ($this->supplier_status){
                case static::STATUS_DEFAULT:
                    return '待评鉴';
                    break;
                case static::STATUS_APPLY:
                    return '待申请';
                    break;
                case static::STATUS_REVIEW:
                    return '待审核';
                    break;
                case static::STATUS_REJECT:
                    return '已驳回';
                    break;
                case static::STATUS_FINISH:
                    return '已完成';
                    break;
                case static::STATUS_DELETE:
                    return '删除';
                    break;
            }
        };
        //创建人姓名和工号
        $fields['createBy'] = function () {
            return [
                "name"=>$this->staff['staff_name'],
                "code"=>$this->staff['staff_code'],
            ];
        };
        //供应商类型
        $fields['supplierType'] = function(){
            return $this->supplierType['bsp_svalue'];
        };
        //供应商来源
        $fields['supplierSource'] = function () {
            return  $this->supplierSource['bsp_svalue'];
        };
        //商品类别
        $fields['transactType'] = function () {
            return  $this->transactType['type_name'];
        };
        //來源类别
        $fields['sourceType'] = function () {
            return $this->sourceType['bsp_svalue'];
        };
        //地位
        $fields['supplierPosition'] = function () {
            return $this->position['bsp_svalue'];
        };
        //代理等级
        $fields['agentsLevel'] = function(){
            return $this->agentsLevel['bsp_svalue'];
        };
        //销售范围
        $fields['supplierSalarea'] = function(){
              return $this->supplierSalarea['bsp_svalue'];
        };
        //授权区域
        $fields['authorizeArea'] = function (){
            return $this->authorizeArea['bsp_svalue'];
        };
        //新增类型
        $fields['supplierAddType'] = function(){
            return $this->supplierAddType['bsp_svalue'];
        };
        //交货条件
        $fields['devcon'] = function(){
            return $this->devcon['dec_sname'];
        };
//        //收款条件
//        $fields['payCondition'] = function(){
//            return $this->payCondition['pat_sname'];
//        };
        //付款条件
        $fields['payCondition'] = function(){
            return $this->payCondition['pat_sname'];
        };
        //交易币别
        $fields['traceCurrency'] = function(){
            return $this->traceCurrency['cur_sname'];
        };
//
//        //状态
//        $fields['supplierStatus'] =function () {
//            return $this->status;
//        };
        return $fields;
    }
}