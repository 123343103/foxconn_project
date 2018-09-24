<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/6
 * Time: 下午 03:15
 */
\app\assets\JqueryUIAsset::register($this);
?>
<h2 class="head-first"> 查看价格</h2>
<div class="content">
    <div id="price-data" style="width:800px;">
        <table class="table">
            <thead>
            <th width="200">最小值</th>
            <th width="200">最大值</th>
            <th width="200">价格</th>
            <th width="200">币别</th>
            </thead>
            <tbody>
            <?php foreach ($data["price"] as $price){ ?>
                <tr>
                    <td><?=$price["minqty"]?></td>
                    <td><?=$price["maxqty"]?></td>
                    <td><?=$price["price"]==-1?"面议":$price["price"]?></td>
                    <td><?=$price["currency_name"]?></td>
                </tr>
            <?php } ?>
            <?php if(count($data["price"])==0){ ?>
                <tr>
                    <td colspan="4">没有相关数据！</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="space-10"></div>
        <div style="text-align: center;">
            <button class="button-white" onclick="$.fancybox.close()">关闭</button>
        </div>
    </div>
</div>

