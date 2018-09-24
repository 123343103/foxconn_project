<?php
/**
 * User: F1676624
 * Date: 2016/11/30
 */
use yii\helpers\Url;
?>

<div class="space-10"></div>
<table class="product-list">
    <thead>
    <tr>
        <th class="width-100">定价编号</th>
        <th class="width-70">交易单位</th>
        <th class="width-70">最小订购量</th>
        <th class="width-70">交易币别</th>
        <th class="width-90">采购价（未税）</th>
        <th class="width-90">商品单价（未税）</th>
        <th class="width-90">商品单价（含税）</th>
        <th class="width-80">商品定价下限（未税）</th>
        <th class="width-80">商品定价上限（未税）</th>
        <th class="width-200">数量区间</th>
        <th class="width-70">市场均价</th>
        <th class="width-100">价格有效日期</th>
        <th class="width-70">毛利润</th>
        <th class="width-70">毛利润率</th>
        <th class="width-70">稅前利润</th>
        <th class="width-70">稅前利润率</th>
        <th class="width-70">稅后利润</th>
        <th class="width-70">稅后利润率</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach ($priceList as $key => $val) { ?>
        <tr>
            <td><a href="<?=Url::to(['partno-price-confirm/index','id'=>$val->price_no])?>"><?= $val->price_no ?></a></td>
            <td><?= $val->unit ?></td>
            <td><?= $val->min_order ?></td>
            <td><?= $val->currency ?></td>
            <td><?= $val->buy_price == '-1' ? '面议' : $val->buy_price ?></td>
            <td><?= $val->buy_price == '-1' ? '面议' : $val->buy_price ?></td>
            <td><?= $val->buy_price == '-1' ? '面议' : $val->buy_price ?></td>
            <td><?= $val->ws_lower_price =='-1' ? '面议' : $val->ws_lower_price  ?></td>
            <td><?= $val->ws_upper_price == '-1' ? '面议' : $val->ws_upper_price ?></td>
            <td><?=$val->num_area?></td>
            <td><?= $val->market_price == '-1' ? '面议' :  $val->market_price ?></td>
            <td><?= $val->valid_date ?></td>
            <td><?= $val->gross_profit == '-1' ? '面议' : $val->gross_profit ?></td>
            <td><?= $val->gross_profit_margin == '-1' ? '面议' : $val->gross_profit_margin ?></td>
            <td><?= $val->pre_tax_profit == '-1' ? '面议' : $val->pre_tax_profit ?></td>
            <td><?= $val->pre_tax_profit_rate == '-1' ? '面议' : $val->pre_tax_profit_rate ?></td>
            <td><?= $val->after_tax_profit == '-1' ? '面议' : $val->after_tax_profit ?></td>
            <td><?= $val->after_tax_profit_margin == '-1' ? '面议' : $val->after_tax_profit_margin ?></td>
        </tr>
    <?php } ?>
    </tbody>
    </thead>
</table>
