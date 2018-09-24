<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/9
 * Time: 16:41
 */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程'];
$this->params['breadcrumbs'][] = ['label' => '厂商呈报列表'];
$this->params['breadcrumbs'][] = ['label' => '呈报分析表'];
$this->title = '呈报分析表';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">呈报分析表</h1>
    <div class="product-table overflow-auto">
        <table class="analyze-table">
            <tbody>
            <tr>
                <th colspan="<?= count($lists)+5 ?>">
                    <span id="product-analyze-table">产品优势分析表</span>
                </th>
            </tr>
            <tr>
                <td rowspan="2" class="width-100">分类</td>
                <td rowspan="2" class="width-150">项目</td>
                <td rowspan="2" class="width-250">内容</td>
                <td colspan="<?= count($lists)+5 ?>">具体描述</td>
            </tr>
            <tr class="pdna_firm">

            </tr>
            <tr class="pdna_influence">
                <td rowspan="4">供应商面</td>
                <td rowspan="4">厂商实力评估</td>
                <td>业界影响力/排名</td>

            </tr>
            <tr class="pdna_technology_service">
                <td>技术实力、技术服务</td>
            </tr>
            <tr class="pdna_cooperate_degree">
                <td>厂商配合度</td>
            </tr>
            <tr class="pdna_others">
                <td>其他</td>
            </tr>
            <tr class="pdna_demand_trends">
                <td rowspan="6">商品面</td>
                <td rowspan="4">商品与市场</td>
                <td>市场需求趋势</td>
            </tr>
            <tr class="pdna_goods_certificate">
                <td>商品认证/厂商认证</td>
            </tr>
            <tr class="pdna_customer_base">
                <td>客户群(By产业)</td>
            </tr>
            <tr class="pdna_market_share">
                <td>销量/市占率</td>
            </tr>
            <tr class="profit_analysis">
                <td rowspan="2">利润与价格</td>
                <td>利润分析</td>
            </tr>
            <tr class="sales_advantage">
                <td>价格优势</td>
            </tr>
            <tr class="value_fjj">
                <td rowspan="2">代理价值</td>
                <td colspan="2">富金机</td>
            </tr>
            <tr class="value_frim">
                <td colspan="2">厂商</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="space-20"></div>
    <div class="width-400 margin-auto">
        <button class="button-white-big ml-40" type="button" onclick="history.go(-1);">返&nbsp回</button>
    </div>
</div>
<script>
    <?php if(count($firmCompared) != 0){ ?>
    <?php foreach($lists as $key => $val){ ?>
    $(".pdna_firm").append("<td> <?php echo $val[$key]['firm']['firm_shortname']?$val[$key]['firm']['firm_shortname']:$val[$key]['firm']['firm_sname']; ?> </td>");
    $(".pdna_influence").append("<td> <?php echo $val[$key]['analysis']['pdna_influence']?$val[$key]['analysis']['pdna_influence']:"/"; ?> </td>");
    $(".pdna_technology_service").append("<td> <?php echo $val[$key]['analysis']['pdna_technology_service']?$val[$key]['analysis']['pdna_technology_service']:"/"; ?> </td>");
    $(".pdna_cooperate_degree").append("<td> <?php echo $val[$key]['analysis']['bsPubdata']['cooperateDegree']?$val[$key]['analysis']['bsPubdata']['cooperateDegree']:"/"; ?> </td>");
    $(".pdna_others").append("<td> <?php echo $val[$key]['analysis']['pdna_others']?$val[$key]['analysis']['pdna_others']:"/"; ?> </td>");
    $(".pdna_demand_trends").append("<td> <?php echo $val[$key]['analysis']['pdna_demand_trends']?$val[$key]['analysis']['pdna_demand_trends']:"/"; ?> </td>");
    $(".pdna_goods_certificate").append("<td> <?php echo $val[$key]['analysis']['pdna_goods_certificate']?$val[$key]['analysis']['pdna_goods_certificate']:"/"; ?> </td>");
    $(".pdna_customer_base").append("<td> <?php echo $val[$key]['analysis']['pdna_customer_base']?$val[$key]['analysis']['pdna_customer_base']:"/"; ?> </td>");
    $(".pdna_market_share").append("<td> <?php echo $val[$key]['analysis']['pdna_market_share']?$val[$key]['analysis']['pdna_market_share']:"/"; ?> </td>");
    $(".profit_analysis").append("<td> <?php echo $val[$key]['analysis']['profit_analysis']?$val[$key]['analysis']['profit_analysis']:"/"; ?> </td>");
    $(".sales_advantage").append("<td> <?php echo $val[$key]['analysis']['sales_advantage']?$val[$key]['analysis']['sales_advantage']:"/"; ?> </td>");
    $(".value_fjj").append("<td> <?php echo $val[$key]['analysis']['value_fjj']?$val[$key]['analysis']['value_fjj']:"/"; ?> </td>");
    $(".value_frim").append("<td> <?php echo $val[$key]['analysis']['value_frim']?$val[$key]['analysis']['value_frim']:'/'; ?> </td>");
    <?php } ?>
    <?php }else{ ?>
    $(".pdna_firm").append("<td> <?php echo $lists['firm']['firm_shortname']?$lists['firm']['firm_shortname']:$lists['firm']['firm_sname']; ?> </td>");
    $(".pdna_influence").append("<td> <?php echo $lists['analysis']['pdna_influence']?$lists['analysis']['pdna_influence']:"/"; ?> </td>");
    $(".pdna_technology_service").append("<td> <?php echo $lists['analysis']['pdna_technology_service']?$lists['analysis']['pdna_technology_service']:"/"; ?> </td>");
    $(".pdna_cooperate_degree").append("<td> <?php echo $lists['analysis']['bsPubdata']['cooperateDegree']?$lists['analysis']['bsPubdata']['cooperateDegree']:"/"; ?> </td>");
    $(".pdna_others").append("<td> <?php echo $lists['analysis']['pdna_others']?$lists['analysis']['pdna_others']:"/"; ?> </td>");
    $(".pdna_demand_trends").append("<td> <?php echo $lists['analysis']['pdna_demand_trends']?$lists['analysis']['pdna_demand_trends']:"/"; ?> </td>");
    $(".pdna_goods_certificate").append("<td> <?php echo $lists['analysis']['pdna_goods_certificate']?$lists['analysis']['pdna_goods_certificate']:"/"; ?> </td>");
    $(".pdna_customer_base").append("<td> <?php echo $lists['analysis']['pdna_customer_base']?$lists['analysis']['pdna_customer_base']:"/"; ?> </td>");
    $(".pdna_market_share").append("<td> <?php echo $lists['analysis']['pdna_market_share']?$lists['analysis']['pdna_market_share']:"/"; ?> </td>");
    $(".profit_analysis").append("<td> <?php echo $lists['analysis']['profit_analysis']?$lists['analysis']['profit_analysis']:"/"; ?> </td>");
    $(".sales_advantage").append("<td> <?php echo $lists['analysis']['sales_advantage']?$lists['analysis']['sales_advantage']:"/"; ?> </td>");
    $(".value_fjj").append("<td> <?php echo $lists['analysis']['value_fjj']?$lists['analysis']['value_fjj']:"/"; ?> </td>");
    $(".value_frim").append("<td> <?php echo $lists['analysis']['value_frim']?$lists['analysis']['value_frim']:'/'; ?> </td>");
    <?php } ?>
</script>
