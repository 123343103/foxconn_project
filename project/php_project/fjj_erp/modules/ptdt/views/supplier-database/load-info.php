<?php
/**
 *
 * F3858995
 *  2016/9/26
 */

?>
<div style="padding:10px">
    <p class="head">联系信息</p>
    <div class="space-10"></div>
    <table class="table-small" style="width:980px;">
        <thead>
        <tr>
            <th>
                姓名
            </th>
            <th>
                性别
            </th>
            <th>
                职务
            </th>
            <th>
                电话
            </th>
            <th>
                手机
            </th>
            <th>
                邮箱
            </th>

        </tr>
        </thead>
        <tbody>
        <?php  foreach ($contacts as $key =>$val){   ?>
            <tr>
                <td><?= $val->vcper_name ?></td>
                <td><?= $val->vcper_sex ?></td>
                <td><?= $val->vcper_post ?></td>
                <td><?= $val->vcper_tel ?></td>
                <td><?= $val->vcper_mobile ?></td>
                <td><?= $val->vcper_mail ?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>

<div style="padding:10px">
    <p class="head">商品信息</p>
    <div class="space-10"></div>
    <table class="table-small" style="width:980px;">
        <thead>
        <tr>
            <th>
                主营商品
            </th>
            <th>
                商品优势与不足
            </th>
            <th>
                销售渠道与区域
            </th>
            <th>
                年销售量
            </th>
            <th>
                市场份额
            </th>
            <th>
                是否公开销售(Y/N)
            </th>
            <th>
                是否代理(Y/N)
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($product as $key=>$val){?>
            <tr>
                <td><?= $val->vmainl_product ?></td>
                <td><?= $val->vmainl_superiority ?></td>
                <td><?= $val->vmainl_salesway ?></td>
                <td><?= $val->vmainl_yqty ?></td>
                <td><?= $val->vmainl_marketshare ?></td>
                <td><?= $val->vmainl_isopensale ?></td>
                <td><?= $val->vmainl_isagent ?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>

