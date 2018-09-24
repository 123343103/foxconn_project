<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/14
 * Time: 下午 04:16
 */
?>
<style>
    .gsh-prd-content {
        position: relative;
        width: 1025px;
        margin: 0 auto;
        overflow: hidden;
    }
    </style>
<div style="margin-top: 30px;"  class="re-content">
    <h2 class="head-second color-1f7ed0">
        商品信息
    </h2>
    <div class="mb-10" style="margin-top: -10px;">
        <table class="table" style="width:990px;table-layout:fixed">
            <thead>
            <tr>
                <th width="200">料号</th>
                <th width="200">商品名称</th>
                <th width="200">品牌</th>
                <th width="150">规格型号</th>
                <th width="150">单位</th>
            </tr>
            </thead>
            <tbody id="pdtinfo">
            <tr class="pdttr">
                <td style="overflow:hidden;"><?=$data['part_no']?></td>
                <td style="overflow:hidden;"><?=$data['pdt_name']?></td>
                <td style="overflow:hidden;"><?=$data['brand_name_cn']?></td>
                <td style="overflow:hidden;"><?=$data['tp_spec']?></td>
                <td style="overflow:hidden;"><?=$data['unit']?></td>
            </tr>
            </tbody>
            </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        包装信息
    </h2>
    <div class="mb-10" style="margin-top: -10px;">
        <table class="table" style="width:990px;">
            <thead>
            <tr>
                <th width="200">长(CM)*宽(CM)*高(CM)</th>
                <th width="200">材积(CBM)</th>
                <th width="150">毛重(KG)</th>
                <th width="150">净重(KG)</th>
                <th width="150">数量</th>
                <th width="150">单位</th>
            </tr>
            </thead>
            <tbody id="prckinfo">
            <tr class="prcktr">
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        运费试算结果
    </h2>
    <div class="mb-10" style="margin-top: -10px; overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <table class="table" style="width:1500px;">
            <thead>
            <tr>
                <th rowspan="2" width="150">运输方式</th>
                <th rowspan="2" width="100">总材积(M³)</th>
                <th rowspan="2" width="100">总重量(KG)</th>
                <th rowspan="2" width="150">计费重量(KG)</th>
                <th rowspan="2" width="150">计费路径</th>
                <th colspan="3"  width="450">费用详情</th>
            </tr>
            <tr>
                <th width="150">含税价格</th>
                <th width="150">未税价格</th>
                <th width="150">前台显示价格</th>
            </tr>
            </thead>
            <tbody id="FeeResult">
            <tr data-tanstype="201">
                <td>标准快递</td>
                <td class="Volume"><span></span></td>
                <td class="Weight"><span></span></td>
                <td class="calweight"><span></span></td>
                <td class="path"><span></span></td>
                <td class="calculationFun"><span></span></td>
                <td class="NoTax"><span></span></td>
                <td class="frontdesk"><span></span></td>
            </tr>
            <tr data-tanstype="202">
                <td>经济快递</td>
                <td class="Volume"></td>
                <td class="Weight"></td>
                <td class="calweight"></td>
                <td class="path"></td>
                <td class="calculationFun"></td>
                <td class="NoTax"></td>
                <td class="frontdesk"></td>
            </tr>
            <tr data-tanstype="301">
                <td>普通陆运</td>
                <td class="Volume"><span></span></td>
                <td class="Weight"><span></span></td>
                <td class="calweight"><span></span></td>
                <td class="path"><span></span></td>
                <td class="calculationFun"><span></span></td>
                <td class="NoTax"><span></span></td>
                <td class="frontdesk"><span></span></td>
            </tr>
            <tr data-tanstype="302">
                <td>定日达陆运</td>
                <td class="Volume"><span></span></td>
                <td class="Weight"><span></span></td>
                <td class="calweight"><span></span></td>
                <td class="path"><span></span></td>
                <td class="calculationFun"><span></span></td>
                <td class="NoTax"><span></span></td>
                <td class="frontdesk"><span></span></td>
            </tr>
            <tr data-tanstype="203">
                <td>优速快递</td>
                <td class="Volume"><span></span></td>
                <td class="Weight"><span></span></td>
                <td class="calweight"><span></span></td>
                <td class="path"><span></span></td>
                <td class="calculationFun"><span></span></td>
                <td class="NoTax"><span></span></td>
                <td class="frontdesk"><span></span></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="gsh-prd-content"></div>
