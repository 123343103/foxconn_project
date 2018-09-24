<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

\app\assets\JeDateAsset::register($this);
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
                <label class="label-width qlabel-align width-100">拣货单号</label><label>：</label>
                <input type="text" name="pck_no" class="width-120"
                       value="<?= $queryParam['pck_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">拣货单状态</label><label>：</label>
                <select name="status" class="width-120">
                    <option value="">全部</option>
                    <option value="4" <?= isset($queryParam['status']) && $queryParam['status'] =="4"? "selected" : null ?>>待拣货</option>
                    <option value="1" <?= isset($queryParam['status']) && $queryParam['status'] =="1" ? "selected" : null ?>>已拣货</option>
                    <option value="2" <?= isset($queryParam['status']) && $queryParam['status'] =="2" ? "selected" : null ?>>已转出库单</option>
                    <option value="3" <?= isset($queryParam['status']) && $queryParam['status'] =="3" ? "selected" : null ?>>已取消</option>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">仓库名称</label><label>：</label>
                <select name="wh_id" class="width-120">
                    <option value="">全部</option>
                    <?php foreach ($options as $key => $val) { ?>
                        <option
                            value="<?= $val["wh_id"] ?>" <?= isset($queryParam['wh_id']) && $queryParam['wh_id'] == $val["wh_id"] ? "selected" : null ?>><?= $val["wh_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align width-100">仓库代码</label><label>：</label>
                <input type="text" name="wh_code" class="width-120"
                       value="<?= $queryParam['wh_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">仓库属性</label><label>：</label>
                <select name="wh_attr" class="width-120">
                    <option value="">全部</option>
                    <?php foreach ($whattrinfo as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= isset($queryParam['wh_attr']) && $queryParam['wh_attr'] == $val["bsp_id"] ? "selected" : null ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
<!--                <input type="text" name="wh_attr" class="width-120"-->
<!--                       value="--><?//= $queryParam['wh_attr'] ?><!--">-->
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align width-70">拣货单日期</label><label>：</label>
                <input id="start" name="start_date" value="<?= $queryParam['start_date'] ?>" type="text" class="width-120 Wdate"
                       readonly="readonly">
                <label>至</label>
                <input id="end" name="end_date" value="<?= $queryParam['end_date'] ?>" type="text" class="width-120 Wdate"
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
