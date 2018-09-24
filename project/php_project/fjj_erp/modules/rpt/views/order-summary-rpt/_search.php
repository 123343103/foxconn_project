<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
$queryParam = Yii::$app->request->queryParams['OrdSumRptSearch'];

?>

<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => [Yii::$app->controller->action->id],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">营销区域               </label><label>：</label>
               <select name="OrdSumRptSearch[csarea_name]"                     class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList['saleArea'] as                         $key => $val) {?>
                        <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['csarea_name'])&& $queryParam['csarea_name']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">订单状态</label><label>：</label>
                <select name="OrdSumRptSearch[ord_status]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList["ordStatus"] as $key => $val) { ?>
                        <option
                            value="<?= $val['os_id'] ?>" <?= isset($queryParam['ord_status']) && $queryParam['ord_status'] == $val['os_id'] ? "selected" : null ?>><?= $val['os_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">销售中心</label><label>：</label>
                <select name="OrdSumRptSearch[sts_sname]" class="value-width qvalue-align">
                    <option value="">全部</option>
                    <?php foreach ($downList['store'] as $key => $val) { ?>
                        <option
                            value="<?= $val['sts_id'] ?>" <?= isset($queryParam['sts_sname']) && $queryParam['sts_sname'] == $val['sts_id'] ? "selected" : null ?>><?= $val['sts_sname'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">销售人员</label><label>：</label>
                <input type="text" name="OrdSumRptSearch[staff_name]" class="value-width qvalue-align"
                       value="<?= $queryParam['staff_name'] ?>">
</div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">客户</label><label>：</label>
                    <input type="text" name="OrdSumRptSearch[cust_shortname]" class="value-width qvalue-align"
                           value="<?= $queryParam['cust_shortname'] ?>">
                </div>
                <div class="inline-block">
                    <label class="label-width qlabel-align">订单号</label><label>：</label>
                    <input type="text" name="OrdSumRptSearch[ord_no]" class="value-width qvalue-align"
                           value="<?= $queryParam['ord_no'] ?>">
                </div>
            </div>
                <div class="space-10"></div>
                <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width qlabel-align">订单日期</label><label>：</label>
                    <input type="text" name="OrdSumRptSearch[start_date]" class="value-width qvalue-align" id="start_time"
                           onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate: '#F{$dp.$D(\'end_time\');}'})"
                           value="<?= $queryParam['start_date'] ?>">
                    <label>至</label><label>：</label>
                    <input type="text" class="value-width qvalue-align" name="OrdSumRptSearch[end_date]" id="end_time"
                           onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '#F{$dp.$D(\'start_time\');}'})"
                           value="<?= $queryParam['end_date'] ?>">
                </div>

            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-25', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-25', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to([Yii::$app->controller->action->id]) . '\'']) ?>
                </div>
        </div>
    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>

