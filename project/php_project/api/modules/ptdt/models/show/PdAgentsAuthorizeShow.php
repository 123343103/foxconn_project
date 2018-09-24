<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/23
 * Time: 8:44
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdAgentsAuthorize;

class PdAgentsAuthorizeShow extends PdAgentsAuthorize
{
    public $pdaaSettlement;
    public function fields()
    {
        $fields = parent::fields();
        //代理等级
        $fields['agentsGrade'] =function()
        {
            return $this->agentsGrade['bsp_svalue'];
        };
        //授权区域范围
        $fields['authorizeArea'] =function()
        {
            return $this->authorizeArea['bsp_svalue'];
        };
        //销售范围
        $fields['saleArea'] =function()
        {
            return $this->saleArea['bsp_svalue'];
        };
        //结算方式
        $fields['settlement'] =function()
        {
            return $this->settlement['bnt_sname'];
        };
        //物流配送
        $fields['deliveryWay'] =function()
        {
            return $this->deliveryWay['bsp_svalue'];
        };
        //售后服务
        $fields['service'] =function()
        {
            return $this->service['bsp_svalue'];
        };

        return $fields;
    }
}