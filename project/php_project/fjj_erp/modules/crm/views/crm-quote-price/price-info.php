<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/1/10
 * Time: 下午 03:58
 */
?>
<table class="table" style="width:3000px;">
<tr style="background:#D9F0FF;">
    <td>价格编号</td>
    <td>商品名称</td>
    <td>商品类型</td>
    <td>付款条件</td>
    <td>交货条件</td>
    <td>供应商代码</td>
    <td>供应商简称</td>
    <td>交货地点</td>
    <td>价格区间</td>
    <td>原商品定价下限（未税）</td>
    <td>价格幅度</td>
    <td>原定价日期</td>
    <td>底价（未税）</td>
    <td>商品定价下限（未税）</td>
    <td>商品定价上限（未税）</td>
    <td>利润下限</td>
    <td>利润上限</td>
    <td>利润率下限（%）</td>
    <td>利润率上限（%）</td>
    <td>毛利润</td>
    <td>毛利润率(%)</td>
    <td>税前利润</td>
    <td>税前利润率（%）</td>
    <td>税后利润</td>
    <td>税后利润率（%）</td>
</tr>
<?php foreach($price_list as $info){ ?>
    <tr>
        <td><?=$info['price_no'];?></td>
        <td><?=$info['pdt_name'];?></td>
        <td><?=$info['type_1'];?></td>
        <td><?=$info['payment_terms'];?></td>
        <td><?=$info['trading_terms'];?></td>
        <td><?=$info['supplier_code'];?></td>
        <td><?=$info['supplier_name_shot'];?></td>
        <td><?=$info['delivery_address'];?></td>
        <td><?=$info['num_area'];?></td>
        <td><?=$info['pre_ws_lower_price'];?></td>
        <td><?=$info['price_fd'];?></td>
        <td>原定价日期</td>
        <td><?=$info['min_price'];?></td>
        <td><?=$info['ws_lower_price'];?></td>
        <td><?=$info['ws_upper_price'];?></td>
        <td><?=$info['lower_limit_profit'];?></td>
        <td><?=$info['upper_limit_profit'];?></td>
        <td><?=$info['lower_limit_profit_margin'];?></td>
        <td><?=$info['upper_limit_profit_margin'];?></td>
        <td><?=$info['gross_profit'];?></td>
        <td><?=$info['gross_profit_margin'];?></td>
        <td><?=$info['pre_tax_profit'];?></td>
        <td><?=$info['pre_tax_profit_rate'];?></td>
        <td><?=$info['after_tax_profit'];?></td>
        <td><?=$info['after_tax_profit_margin'];?></td>
    </tr>
<?php } ?>
</table>

