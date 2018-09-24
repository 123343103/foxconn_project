<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/7/19
 * Time: 上午 08:28
 */

?>
<input type="hidden" id="trans" value="">
 <div id="Express" style="display: none;">
    <div  style="margin-top: 20px;">
        <span style="margin-left: 20px;font-size: 14px;">快递：</span><span style="font-size: 14px;" id="TransModel"></span>
    </div>
    <div id="data" class="mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto;margin-top: 5px;">
        <table class="table" style="width:1500px">
            <thead>
            <tr>
                <th>序号</th>
                <th>报价单号</th>
                <th>报价日期</th>
                <th>报价币别</th>
                <th>生效日期</th>
                <th>截止日期</th>
                <th>经管确认日期</th>
                <th>备注</th>
                <th>起运地</th>
                <th>目的地</th>
                <th>状态</th>
                <th>运输类型</th>
            </tr>
            </thead>
            <tbody id="expresshead">
            </tbody>
        </table>
    </div>
    <div id="Exdetails" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto; display: none;margin-top: 50px;">
        <table class="table" style="width:1500px">
            <thead>
            <tr>
                <th>项次</th>
                <th>计量单位</th>
                <th>首重重量</th>
                <th>首重价格</th>
                <th>续重重量</th>
                <th>区间下限</th>
                <th>区间上限</th>
                <th>续重价格</th>
                <th>最小收费</th>
                <th>最大收费</th>
                <th>配送时效</th>
                <th>生效日期</th>
                <th>截止日期</th>
                <th>状态</th>
            </tr>
            </thead>
            <tbody id="expressdetail">
            </tbody>
        </table>
    </div>
    </div>
    <div id="Land" style="display: none;">
    <div  style="margin-top: 20px;">
        <span style="margin-left: 20px;font-size: 14px;">陆运</span>
    </div>
    <div class="mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <table class="table" style="width:1500px;margin-top: -25px;">
            <thead>
            <tr>
            <tr>
                <th>序号</th>
                <th>报价单号</th>
                <th>承运方式</th>
                <th>报价状态</th>
                <th>时效</th>
                <th>币别</th>
                <th>BU类型</th>
                <th>发货地代码</th>
                <th>收货地代码</th>
                <th>报价时间</th>
                <th>生效时间</th>
                <th>失效时间</th>
                <th>起运地</th>
                <th>目的地</th>
                <th>快慢件</th>
            </tr>
            </tr>
            </thead>
            <tbody id="Landhead">
            </tbody>
        </table>
    </div>
    <div id="Ladetails" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto; display: none;margin-top: 50px;">
        <table class="table" style="width:1500px">
            <thead>
            <tr>
                <th>报价ID</th>
                <th>费用项目</th>
                <th>计量单位</th>
                <th>价格</th>
                <th>税率</th>
                <th>税种</th>
                <th>车种</th>
                <th>费用类型</th>
                <th>最小收费</th>
                <th>最大收费</th>
                <th>币别</th>
            </tr>
            </thead>
            <tbody id="landdetail">
            </tbody>
        </table>
    </div>
    </div>

<script>
</script>