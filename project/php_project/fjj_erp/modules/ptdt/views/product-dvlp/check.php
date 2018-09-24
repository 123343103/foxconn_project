<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/3/9
 * Time: 10:54
 */
$this->title = "商品开发需求审核";
$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发计划'];
$this->params['breadcrumbs'][] = ['label' => '商品开发需求审核'];
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title ?>
            <span class="head-code">编号：<?= $model->pdq_code ?></span>
    </h1>
    <h1 class="head-second">商品经理人</h1>
    <div class="mb-20">
        <label class="width-100">开发中心</label>
        <span class="width-200"><?= $model->developCenterName ?></span>
        <label class="width-100">开发部</label>
        <span class="width-200"><?= $model->developDepartmentName ?></span>
        <label class="width-100">商品经理人</label>
        <span class="width-200"><?= $model->productManagerName->name ?>/<?= $model->productManagerName->code ?></span>
    </div>
    <div class="mb-20">
        <label class="width-100">需求类型</label>
        <span class="width-200"><?= $model->pdqSourceTypeName ?></span>
        <label class="width-100">开发类型</label>
        <span class="width-200"><?= $model->developTypeName ?></span>
        <label class="width-100">Commodity</label>
        <span class="width-200"><?= $model->commodityName ?></span>
    </div>
    <h1 class="head-second">商品基本信息</h1>
    <div class="mb-20">
        <div class="overflow-auto">
        <table class="product-list">
            <thead>
            <tr>
                <th>行号</th>
                <th>商品名称</th>
                <th>商品规格型号</th>
                <th>商品定位</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
                <th >商品要求</th>
                <th>制程要求</th>
                <th>品质要求</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->products as $key => $val) { ?>
                <tr>
                    <td><?= $val->product_index ? $val->product_index : "" ?></td>
                    <td><?= $val->product_name ? $val->product_name : "" ?></td>
                    <td><?= $val->product_size ? $val->product_size : "" ?></td>
                    <td><?= $val->levelName ? $val->levelName : "" ?></td>
                    <td><?= $val->typeName[0] ? $val->typeName[0] : "" ?></td>
                    <td><?= $val->typeName[1] ? $val->typeName[1] : "" ?></td>
                    <td><?= $val->typeName[2] ? $val->typeName[2] : "" ?></td>
                    <td><?= $val->typeName[3] ? $val->typeName[3] : "" ?></td>
                    <td><?= $val->typeName[4] ? $val->typeName[4] : "" ?></td>
                    <td><?= $val->typeName[5] ? $val->typeName[5] : "" ?></td>
                    <td><?= $val->product_requirement ? $val->product_requirement : "" ?></td>
                    <td><?= $val->product_process_requirement ? $val->product_process_requirement : "" ?></td>
                    <td><?= $val->product_quality_requirement ? $val->product_quality_requirement : "" ?></td>
                    <td><?= $val->other_des ? $val->other_des : "" ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
    <h1 class="head-second text-center">签核记录</h1>
    <div class="mb-20">
        <table class="product-list" style="width:990px;">
            <thead>
            <tr>
                <th class="width-60">#</th>
                <th class="width-70">签核节点</th>
                <th class="width-60">签核人员</th>
                <th>签核日期</th>
                <th class="width-60">操作</th>
                <th>签核意见</th>
                <th>签核人IP</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($verify as $key=>$val){ ?>
                    <tr>
                        <th><?= $key+1 ?></th>
                        <th><?= $val['verifyOrg'] ?></th>
                        <th><?= $val['verifyName'] ?></th>
                        <th><?= $val['vcoc_datetime'] ?></th>
                        <th><?= $val['verifyStatus'] ?></th>
                        <th><?= $val['vcoc_remark'] ?></th>
                        <th><?= $val['vcoc_computeip'] ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="space-20"></div>
    <div class="margin-auto text-center">
<!--        <button class="button-blue-big">确&nbsp定</button>-->
        <button class="button-white-big ml-40" type="button" onclick="history.go(-1);">返回</button>
    </div>
</div>

