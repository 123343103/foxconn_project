<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/21
 * Time: 14:29
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdFirmReportChild;
use yii\helpers\Html;

class PdFirmReportChildShow extends PdFirmReportChild{
    public $analysis;
    public $authorize;
    public $firmSettlement;
    public $bsPubdata;
    public $productPerson;
    public $staffPerson;
    public $reception;
    public $firmConcluse;
    public $products;
    public $demand_id;
//    public $productsLevel;
    public function fields(){
        $fields = parent::fields();

        //厂商地位
        $fields['firmPosition'] = function(){
            return   $this->firmPosition?$this->firmPosition['bsp_svalue']:'';
        };
        //代理商品定位
        $fields['goodsLoction'] = function(){
            return $this->goodsLoction?$this->goodsLoction['bsp_svalue']:"";
        };
        //代理等级
        $fields['agentsGrade'] = function(){
            return $this->agentsGrade?$this->agentsGrade['bsp_svalue']:"";
        };
        //授权区域范围
        $fields['authorizeArea'] = function(){
            return $this->authorizeArea?$this->authorizeArea['bsp_svalue']:"";
        };
        //销售范围
        $fields['saleArea'] = function(){
            return $this->saleArea?$this->saleArea['bsp_svalue']:"";
        };

        //年度营业额
        $fields['pdna_annual_sales'] = function(){
            return $this->negotiationAnalysis?$this->negotiationAnalysis['pdna_annual_sales']:"";
        };
        //年度营业额
        $fields['pdna_annual_sales'] = function(){
            return $this->agentsAuthorize?$this->agentsAuthorize['pdaa_delivery_day']:"";
        };
        //授权时间
        $fields['time'] = function(){
            return $this->agentsAuthorize?$this->agentsAuthorize['pdaa_bdate'].'-'.$this->agentsAuthorize['pdaa_edate']:"";
        };
        $fields['bsPubdata'] = function (){
            return [
                "firmPosition" => $this->firmPosition['bsp_svalue'],
                "goodsLoction"   => $this->goodsLoction['bsp_svalue'],
                "agentsGrade" => $this->agentsGrade['bsp_svalue'],
                "authorizeArea" => $this->authorizeArea['bsp_svalue'],
                "saleArea" => $this->saleArea['bsp_svalue'],
                "firmService" => $this->firmService['bsp_svalue'],
                "firmDeliveryWay" => $this->firmDeliveryWay['bsp_svalue'],
                "cooperateDegree" => $this->cooperateDegree['bsp_svalue'],
            ];
        };
        $fields['analysis'] = function(){
            return [
                "pdna_annual_sales" => $this->negotiationAnalysis['pdna_annual_sales'],
                "pdna_position" =>$this->negotiationAnalysis['pdna_position'],
                "pdna_loction" => $this->negotiationAnalysis['pdna_loction'],
                "pdna_influence" => $this->negotiationAnalysis['pdna_influence'],
                "pdna_technology_service" => $this->negotiationAnalysis['pdna_technology_service'],
                "pdna_others" => $this->negotiationAnalysis['pdna_others'],
                "pdna_goods_certificate" =>$this->negotiationAnalysis['pdna_goods_certificate'],
                "pdna_cooperate_degree" => $this->negotiationAnalysis['pdna_cooperate_degree'],
                "pdna_customer_base" => $this->negotiationAnalysis['pdna_customer_base'],
                "pdna_market_share" => $this->negotiationAnalysis['pdna_market_share'],
                "sales_advantage" => $this->negotiationAnalysis['sales_advantage'],
                "profit_analysis" => $this->negotiationAnalysis['profit_analysis'],
                "pdna_demand_trends" => $this->negotiationAnalysis['pdna_demand_trends'],
                "value_fjj" => $this->negotiationAnalysis['value_fjj'],
                "value_frim" =>$this->negotiationAnalysis['value_frim'],
            ];
        };
        $fields['firmConcluse'] = function(){
            if(isset($this->concluse)){
                return $this->concluse->bsp_svalue;
            }else{
                return null;
            }
        };
        $fields['authorize'] = function(){
            if (isset($this->agentsAuthorize)) {
                return [
                    'pdaa_bdate' => $this->agentsAuthorize['pdaa_bdate'],
                    'pdaa_edate' => $this->agentsAuthorize['pdaa_edate'],
                    'pdaa_delivery_day' => $this->agentsAuthorize['pdaa_delivery_day'],
                    'pdaa_agents_grade' => $this->agentsAuthorize['pdaa_agents_grade'],
                    'pdaa_authorize_area' => $this->agentsAuthorize['pdaa_authorize_area'],
                    'pdaa_sale_area' => $this->agentsAuthorize['pdaa_sale_area'],
                    'pdaa_settlement' => $this->agentsAuthorize['pdaa_settlement'],
                    'pdaa_service' => $this->agentsAuthorize['pdaa_service'],
                    'pdaa_delivery_way' => $this->agentsAuthorize['pdaa_delivery_way'],
                ];
            } else {
                return null;
            }
        };
        $fields['firmSettlement'] = function(){
            if (isset($this->settlement)) {
                return $this->settlement['bnt_sname'];
            } else {
                return null;
            }
        };
        $fields['productPerson'] = function () {
            return [
                "code" => $this->pdProductPerson['staff_code'],
                "name" => $this->pdProductPerson['staff_name'],
                "job"  => $this->pdProductPerson['job_task'],
                "mobile"  => $this->pdProductPerson['staff_mobile'],
            ];
        };
        $fields['staffPerson'] = function (){
            return $this->staff;
        };
        $fields['products'] = function (){
            return $this->product;
        };
        $fields['reception'] = function(){
            return [
                "receSname" => $this->firmReception['rece_sname'],
                "recePosition" => $this->firmReception['rece_position'],
                "receMobile" => $this->firmReception['rece_mobile'],
            ];
        };

        $fields['requirement'] = function(){
            if($this->product != null){
                if($this->product[0]->demand_id != null){
                    return $this->product[0]->requirementProduct;
                }else{
                    return "";
                }
            }else{
                return "";
            }

        };
        return $fields;
    }
}