<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

\app\assets\JeDateAsset::register($this);
//$queryParam = Yii::$app->request->queryParams['ShipmentNotifySearch'];
?>
<style>
    .width-100{
        width:100px
    }
    .width-120{
        width:120px;
    }
    .width-70{
        width: 65px;
    }
</style>
<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align width-100">出货通知单号</label><label>：</label>
                <input type="text" name="note_no" class="width-120"
                       value="<?= $queryParam['note_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">交易法人</label><label>：</label>
                <select name="corporate" class="width-120">
                    <option value="">全部</option>
                    <?php foreach ($downList["corporate"] as $key => $val) { ?>
                        <option
                            value="<?= $val["company_id"] ?>" <?= isset($queryParam['corporate']) && $queryParam['corporate'] == $val["company_id"] ? "selected" : null ?>><?= $val["company_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">单据状态</label><label>：</label>
                <select name="status" class="width-120">
                    <option value="">全部</option>
                    <?php foreach ($downList["status"] as $key => $val) { ?>
                        <option
                            value="<?=$val["bsp_svalue"] ?>" <?= isset($queryParam['status']) && $queryParam['status'] == $val["bsp_svalue"] ? "selected" : null ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align width-100">关联订单号</label><label>：</label>
                <input type="text" name="ord_no" class="width-120"
                       value="<?= $queryParam['ord_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">客户名称</label><label>：</label>
                <input type="text" name="cust_sname" class="width-120"
                       value="<?= $queryParam['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">通知单日期</label><label>：</label>
                <input id="start" name="starttime" value="<?= $queryParam['starttime'] ?>" type="text" class="width-120 Wdate"
                       readonly="readonly">
                <label>至</label>
                <input id="end" name="endtime" value="<?= $queryParam['endtime'] ?>" type="text" class="width-120 Wdate"
                        readonly="readonly">
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue','style'=>'margin-left:20px']) ?>
            <?= Html::button('重置', ['class' => 'button-blue reset-btn-yellow', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        $("#start").click(function () {
            WdatePicker({
                skin: "whyGreen",
                maxDate: "#F{$dp.$D('end',{d:-1})}"
            });
        });
        $("#end").click(function () {
            WdatePicker({
                skin: "whyGreen",
                minDate: "#F{$dp.$D('start',{d:1})}"
            });
        });
    })
</script>
