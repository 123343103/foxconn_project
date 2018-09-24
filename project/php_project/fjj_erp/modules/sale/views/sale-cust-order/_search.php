<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
$actionId = Yii::$app->controller->action->id;
?>
<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">需求单号</label><label>：</label>
                <input type="text" name="ReqInfoSearch[saph_code]" class="value-width qvalue-align"
                       value="<?= $queryParam['saph_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">需求单状态</label><label>：</label>
                <select name="ReqInfoSearch[saph_status]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["status"] as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($queryParam['saph_status']) && $queryParam['saph_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">客户名称</label><label>：</label>
                <input type="text" name="ReqInfoSearch[cust_sname]" class="value-width qvalue-align"
                       value="<?= $queryParam['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">客户代码</label><label>：</label>
                <input type="text" name="ReqInfoSearch[applyno]" class="value-width qvalue-align"
                       value="<?= $queryParam['applyno'] ?>">
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">订单类型</label><label>：</label>
                <select name="ReqInfoSearch[saph_type]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["orderType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['saph_type']) && $queryParam['saph_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">付款方式</label><label>：</label>
                <select name="ReqInfoSearch[pac_id]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <option
                        value="credit-amount" <?= isset($queryParam['pac_id']) && $queryParam['pac_id'] == "credit-amount" ? "selected" : null ?>>
                        信用额度支付
                    </option>
                    <?php foreach ($downList["payType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= isset($queryParam['pac_id']) && $queryParam['pac_id'] == $val["bsp_id"] ? "selected" : null ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">下单时间</label><label>：</label>
                <input type="text" name="ReqInfoSearch[start_date]" class="width-100 select-date" id="start_time"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}' })"
                       onfocus="this.blur();"
                       value="<?= $queryParam['start_date'] ?>">
                <label>至</label><label>：</label>
                <input type="text" class="width-100 select-date" name="ReqInfoSearch[end_date]" id="end_time"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}' })"
                       onfocus="this.blur();"
                       value="<?= $queryParam['end_date'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-30 search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-20"></div>
</div>
