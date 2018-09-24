<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\assets\JeDateAsset::register($this);

?>

<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 qlabel-align" style="width: 80px;">客户经理人</label>
                <label>:</label>
                <input type="text" name="CrmVisitRecordChildSearch[staff_name]" class="width-120 qvalue-align"
                       style="width: 140px;" placeholder="姓名"
                       value="<?= $queryParam['CrmVisitRecordChildSearch']['staff_name'] ?>">
            </div>

            <div class="inline-block">
                <label class="width-100 qlabel-align" style="width: 80px;">组&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;织</label>
                <label>:</label>
                <select name="CrmVisitRecordChildSearch[organization]" class="width-120 qvalue-align"
                        style="width: 140px;">
                    <option value="">全部...</option>
                    <?php foreach ($downList['organization'] as $key => $val) { ?>

                        <option
                            value="<?= $val['organization_code'] ?>" <?= isset($queryParam['CrmVisitRecordChildSearch']['organization']) && $queryParam['CrmVisitRecordChildSearch']['organization'] == $val['organization_code'] ? "selected" : null ?>><?= $val['organization_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align"
                       style="width: 80px;">日&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;期</label>
                <label>:</label>
                <input type="text" id="txtStartDateFrom" class="Wdate" style="width: 90px;"
                       name="CrmVisitRecordChildSearch[start]"
                       value="<?= $queryParam['CrmVisitRecordChildSearch']['start'] ?>"
                       onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', maxDate: '#F{$dp.$D(\'txtStartDateTo\');}' })"/>
                <label class="no-after">至</label>
                <input type="text" id="txtStartDateTo" class="Wdate" style="width: 90px;"
                       name="CrmVisitRecordChildSearch[end]"
                       value="<?= $queryParam['CrmVisitRecordChildSearch']['end'] ?>"
                       onclick="WdatePicker({ onpicked: function () { }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'txtStartDateFrom\');}' })"/>


            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">销&nbsp;售&nbsp;区&nbsp;域</label>
                <label>:</label>
                <select name="CrmVisitRecordChildSearch[sale_area]" class="width-120 qvalue-align"
                        style="width: 140px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['salearea'] as $key => $val) { ?>
                        <option
                            value="<?= $val['csarea_id'] ?>" <?= isset($queryParam['CrmVisitRecordChildSearch']['sale_area']) && $queryParam['CrmVisitRecordChildSearch']['sale_area'] == $val['csarea_id'] ? "selected" : null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align" style="width: 80px;">所在销售点</label>
                <label>:</label>
                <select name="CrmVisitRecordChildSearch[sts_id]" class="width-120 qvalue-align" style="width: 140px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['store'] as $key => $val) { ?>
                        <option
                            value="<?= $val['sts_id'] ?>" <?= isset($queryParam['CrmVisitRecordChildSearch']['sts_id']) && $queryParam['CrmVisitRecordChildSearch']['sts_id'] == $val['sts_id'] ? "selected" : null ?>><?= $val['sts_sname'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align" style="width: 80px;">客户全称</label>
                <label>:</label>
                <input type="text" name="CrmVisitRecordChildSearch[cust_sname]" class="width-120 qvalue-align"
                       style="width: 200px;"
                       value="<?= $queryParam['CrmVisitRecordChildSearch']['cust_sname'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-10 sub', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-10"></div>
</div>

