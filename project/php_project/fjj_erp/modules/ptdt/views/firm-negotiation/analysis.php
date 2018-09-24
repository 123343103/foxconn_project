<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\PdNegotiation */
$this->title = '谈判分析';
$this->params['homeLike'] = ['label' => '商品开发'];
$this->params['breadcrumbs'][] = ['label' => '厂商谈判履历列表'];
$this->params['breadcrumbs'][] = ['label' => '谈判分析'];
?>
<div class="content">
    <h1 class="head-first">
        厂商信息谈判分析表
    </h1>
    <div class="clear">
        <div class="overflow-auto">
            <table class="analysis-table ">
                <tbody>
                <tr>
                    <th colspan="<?= count($list)+5 ?>">
                        <span >厂商谈判分析表</span>
                    </th>
                </tr>
                <tr>
                    <td rowspan="2"><span class="width-50">分类</span></td>
                    <td rowspan="2"><span class="width-80">项目</span></td>
                    <td rowspan="2"><span class="width-120">内容</span></td>
                    <td colspan="<?= count($list)?>" >具体描述</td>
                </tr>
                <tr style="height: 31px;" >
                    <?php foreach($list as $val){?>

                        <td rowspan="27">
                            <div class="analysis-row">
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['firm_name'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['bsPubdata']['position'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_annual_sales'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_influence'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_technology_service'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['bsPubdata']['cooperateDegree'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_others'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_pdtype'] ?>7
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['bsPubdata']['loction'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_demand_trends'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_goods_certificate'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_customer_base'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['pdna_market_share'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['profit_analysis'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  $val['analysis']['sales_advantage'] ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?= isset($val['authorize']['pdaa_agents_grade']) ? $val['authorize']['agentsGrade']:'/' ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  isset($val['authorize']['pdaa_authorize_area'])?$val['authorize']['authorizeArea']:'/' ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?= isset($val['authorize']['pdaa_sale_area'])?$val['authorize']['saleArea']:'/'?>
                                </div>
                                <div class="analysis-table-div">
                                    <?= isset($val['authorize']['pdaa_bdate'])?$val['authorize']['pdaa_bdate'].' ~':'/'?>
                                    <?= isset($val['authorize']['pdaa_edate'])?$val['authorize']['pdaa_edate']:''?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  isset($val['authorize']['settlement'])?$val['authorize']['settlement']:'/' ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  isset($val['authorize']['pdaa_delivery_day'])?$val['authorize']['pdaa_delivery_day']:'/' ?>
                                </div>
                                <div class="analysis-table-div">
                                    <?=  isset($val['authorize']['pdaa_delivery_way'])?$val['authorize']['pdaa_delivery_way']:'/' ?>
                                </div>
                                <div class="analysis-table-div-end">
                                    <?=  isset($val['authorize']['pdaa_service'])?$val['authorize']['pdaa_service']:'/' ?>
                                </div>
                            </div>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td rowspan="7" class="width-30">供应商面</td>
                    <td rowspan="7">厂商实力评估</td>
                </tr>
                <tr>
                    <td>厂商地位</td>

                </tr>
                <tr>
                    <td>年度营业额</td>
                </tr>
                <tr>
                    <td>业界影响力/排名</td>
                </tr>
                <tr>
                    <td>技术实力、技术服务</td>
                </tr>
                <tr>
                    <td>厂商配合度</td>
                </tr>
                <tr>
                    <td>其他</td>
                </tr>
                <tr>
                    <td rowspan="9">商品面</td>
                    <td rowspan="6">商品与市场</td>
                    <td >商品类别</td>
                    <!--                <td >利润与价格</td>-->
                </tr>
                <tr>
                    <td >商品定位</td>
                </tr>
                <tr>
                    <td>市场需求趋势</td>
                </tr>
                <tr>
                    <td>商品认证/厂商认证</td>
                </tr>
                <tr>
                    <td>客户群（By产业）</td>
                </tr>
                <tr>
                    <td>销量/市占率</td>
                </tr>
                <tr>
                    <td rowspan="3">利润与价格</td>
                </tr>
                <tr>
                    <td>利润分析</td>
                </tr>
                <tr>
                    <td>价格优势</td>
                </tr>
                <tr>
                    <td rowspan="9">谈判事项</td>
                    <td rowspan="6">代理授权项目</td>
                    <td >代理等级</td>
                    <!--                <td >利润与价格</td>-->
                </tr>
                <tr>
                    <td >授权区域范围</td>
                </tr>
                <tr>
                    <td>销售范围</td>
                </tr>
                <tr>
                    <td>授权日期</td>
                </tr>
                <tr>
                    <td>结算方式</td>
                </tr>
                <tr>
                    <td>交期</td>
                </tr>
                <tr>
                    <td rowspan="3">利润与价格</td>
                </tr>
                <tr>
                    <td>物流配送</td>
                </tr>
                <tr>
                    <td>售后服务</td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="space-40"></div>
        <div class="text-center mb-20">
            <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
        </div>
    </div>
</div>
