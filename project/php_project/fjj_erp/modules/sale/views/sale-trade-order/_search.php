<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
$queryParam = Yii::$app->request->queryParams['OrdInfoSearch'];
?>

<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">订单编号</label><label>：</label>
                <input type="text" name="OrdInfoSearch[ord_no]" class="value-width qvalue-align"
                       value="<?= $queryParam['ord_no'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">订单状态</label><label>：</label>
                <select name="OrdInfoSearch[ord_status]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["status"] as $key => $val) { ?>
                        <option
                            value="<?= $val['os_id'] ?>" <?= isset($queryParam['ord_status']) && $queryParam['ord_status'] == $val['os_id'] ? "selected" : null ?>><?= $val['os_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">订单类型</label><label>：</label>
                <select name="OrdInfoSearch[ord_type]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["orderType"] as $key => $val) { ?>
                        <option
                            value="<?= $val["business_type_id"] ?>" <?= isset($queryParam['ord_type']) && $queryParam['ord_type'] == $val["business_type_id"] ? "selected" : null ?>><?= $val["business_value"] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">订单来源</label><label>：</label>
                <select name="OrdInfoSearch[origin_hid]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["orderFrom"] as $key => $val) { ?>
                        <option
                            value="<?= $val["bsp_id"] ?>" <?= isset($queryParam['origin_hid']) && $queryParam['origin_hid'] == $val["bsp_id"] ? "selected" : null ?>><?= $val["bsp_svalue"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">交易法人</label><label>：</label>
                <select name="OrdInfoSearch[corporate]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["corporate"] as $key => $val) { ?>
                        <option
                            value="<?= $val["company_id"] ?>" <?= isset($queryParam['corporate']) && $queryParam['corporate'] == $val["company_id"] ? "selected" : null ?>><?= $val["company_name"] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">客户名称</label><label>：</label>
                <input type="text" name="OrdInfoSearch[cust_sname]" class="value-width qvalue-align"
                       value="<?= $queryParam['cust_sname'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-25', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-25', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">客户代码</label><label>：</label>
                <input type="text" name="OrdInfoSearch[cust_code]" class="value-width qvalue-align"
                       value="<?= $queryParam['cust_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">付款方式</label><label>：</label>
                <select name="OrdInfoSearch[pac_id]" class="value-width qvalue-align">
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
                <input type="text" name="OrdInfoSearch[start_date]" class="value-width qvalue-align" id="start_time"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}'})"
                       value="<?= $queryParam['start_date'] ?>">
                <label>至</label><label>：</label>
                <input type="text" class="value-width qvalue-align" name="OrdInfoSearch[end_date]" id="end_time"
                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}'})"
                       value="<?= $queryParam['end_date'] ?>">
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
