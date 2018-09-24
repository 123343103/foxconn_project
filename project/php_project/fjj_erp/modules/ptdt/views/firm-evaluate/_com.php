<?php
/**
 * User: F1677929
 * Date: 2016/11/18
 */
/* @var $this yii\web\view */
use yii\helpers\Url;
?>
<h2 class="head-second">厂商基本信息</h2>
<div class="mb-20">
    <label class="width-110">厂商全称</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_sname'] : null ?></span>
    <label class="width-110">简称</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_shortname'] : null ?></span>
    <label class="width-110">来源</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['bsPubdata']['firmSource'] : null ?></span>
</div>
<div class="mb-20">
    <label class="width-110">类型</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['bsPubdata']['firmType'] : null ?></span>
    <label class="width-110">地位</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['bsPubdata']['firmPosition'] : null ?></span>
    <label class="width-110">是否为集团供应商</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['issupplier'] : null ?></span>
</div>
<div class="mb-20">
    <label class="width-110">地址</label>
    <?= !empty($firmData) ? $firmData['firmAddress']['fullAddress'] : null ?>
</div>
<div class="mb-20">
    <label class="width-110">厂商负责人</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_compprincipal'] : null ?></span>
    <label class="width-110">联系电话</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_comptel'] : null ?></span>
    <label class="width-110">邮箱</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_compmail'] : null ?></span>
</div>
<div class="mb-20">
    <label class="width-110">厂商联系人</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_contaperson'] : null ?></span>
    <label class="width-110">联系电话</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_contaperson_tel'] : null ?></span>
    <label class="width-110">邮箱</label>
    <span class="width-200"><?= !empty($firmData) ? $firmData['firm_contaperson_mail'] : null ?></span>
</div>
<h2 class="head-second">评鉴信息</h2>
<div class="mb-20">
    <label class="width-110">评鉴申请日期</label>
    <?= !empty($evaluateChildData) ? $evaluateChildData['evaluate_date'] : null ?>
</div>
<div class="mb-20">
    <label class="width-110">评鉴理由</label>
    <?= !empty($evaluateChildData) ? $evaluateChildData['evaluate_reason'] : null ?>
</div>
<h2 class="head-second">附件信息</h2>
<h2 class="head-second">评鉴结果</h2>
<table class="table" style="width: 95%; margin: 0 auto 20px;">
    <thead>
        <tr>
            <td>通路与服务能力(30%)</td>
            <td>价格及交货能力(30%)</td>
            <td>经营与财务能力(20%)</td>
            <td>管理与创新能力(20%)</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><span>评鉴得分</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['passage_server_score'] : null ?></span></td>
            <td><span>评鉴得分</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['price_delivery_score'] : null ?></span></td>
            <td><span>评鉴得分</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['operate_finance_score'] : null ?></span></td>
            <td><span>评鉴得分</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['manage_innovate_score'] : null ?></span></td>
        </tr>
        <tr>
            <td><span>结果判定</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['passage_server_decide'] : null ?></span></td>
            <td><span>结果判定</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['price_delivery_decide'] : null ?></span></td>
            <td><span>结果判定</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['operate_finance_decide'] : null ?></span></td>
            <td><span>结果判定</span><span class="ml-10"><?= !empty($evaluateChildData) ? $evaluateChildData['manage_innovate_decide'] : null ?></span></td>
        </tr>
    </tbody>
</table>
<div class="mb-20">
    <label class="width-110">评鉴结果</label>
    <span class="width-200 red"><?= !empty($firmEvaluateData) ? $firmEvaluateData['evaluateResult'] : null ?></span>
    <label class="width-110">综合得分</label>
    <span class="width-200"><?= !empty($evaluateChildData) ? $evaluateChildData['evaluate_synthesis_score'] : null ?></span>
    <label class="width-110">综合等级</label>
    <span class="width-200"><?= !empty($evaluateChildData) ? $evaluateChildData['evaluate_level'] : null ?></span>
</div>
<div class="mb-20">
    <label class="width-110">评鉴人</label>
    <span class="width-200"><?= !empty($firmEvaluateData) ? $firmEvaluateData['evaluatePerson']['staff_name'] : null ?></span>
    <label class="width-110">评鉴部门</label>
    <span class="width-200"><?= !empty($firmEvaluateData) ? $firmEvaluateData['evaluatePerson']['organization'] : null ?></span>
</div>
