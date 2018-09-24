<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="search-div">
    <div class="mb-10">
        <div class="inline-block field-pdfirmreportsearch-firm_sname">
            <label for="pdfirmreportsearch-firm_sname" class="width-100">公司全称/简称</label>
            <input type="text" name="PdFirmReportSearch[firm_sname]" class="width-170" id="pdfirmreportsearch-firm_sname"
                   value="<?= $queryParam['firm_sname'] ?>">
        </div>
        <div class="inline-block field-pdfirmreportsearch-firm_type">
            <label for="pdfirmreportsearch-firm_type" class="width-100">厂商类型</label>
            <select name="PdFirmReportSearch[firm_type]" class="width-170" id="pdfirmreportsearch-firm_type">
                <option value="">请选择...</option>
                <?php foreach ($downList['firmType'] as $key => $val) {?>
                    <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['firm_type'])&&$queryParam['firm_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="inline-block field-pdfirmreportsearch-firm_issupplier">
            <label for="pdfirmreportsearch-firm_issupplier" class="width-150">是否为集团供应商</label>
            <select name="PdFirmReportSearch[firm_issupplier]" class="width-170" id="pdfirmreportsearch-firm_issupplier">
                <option value="">请选择...</option>
                <option value="1" <?= isset($queryParam['firm_issupplier'])&&$queryParam['firm_issupplier']=='1'?"selected":null ?>>是</option>
                <option value="0" <?= isset($queryParam['firm_issupplier'])&&$queryParam['firm_issupplier']=='0'?"selected":null ?>>否</option>
            </select>

            <div class="help-block"></div>
        </div></div>
    <div class="mb-10">
        <div class="inline-block field-pdfirmreportsearch-firm_category_id">
            <label for="pdfirmreportsearch-firm_category_id" multiple="multiple" class="width-100 js-example-basic-multiple">分级分类</label>
            <select name="PdFirmReportSearch[firm_category_id]" class="width-170" id="pdfirmreportsearch-firm_category_id">
                <option value="">请选择...</option>
                <?php foreach ($downList['productTypes'] as $key => $val) {?>
                    <option value="<?=$key ?>" <?= isset($queryParam['firm_category_id'])&&$queryParam['firm_category_id']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>    <div class="inline-block field-pdfirmreportsearch-report_status">
            <label for="pdfirmreportsearch-report_status" class="width-100">呈报状态</label>
            <select name="PdFirmReportSearch[report_status]" class="width-170" id="pdfirmreportsearch-report_status">
                <option value="">请选择...</option>
                <option value="10" <?= isset($queryParam['report_status'])&&$queryParam['report_status']=='10'?"selected":null ?>>被驳回</option>
                <option value="20" <?= isset($queryParam['report_status'])&&$queryParam['report_status']=='20'?"selected":null ?>>新增呈报</option>
                <option value="40" <?= isset($queryParam['report_status'])&&$queryParam['report_status']=='40'?"selected":null ?>>待审核</option>
                <option value="50" <?= isset($queryParam['report_status'])&&$queryParam['report_status']=='50'?"selected":null ?>>呈报完成</option>
            </select>
            <div class="help-block"></div>
        </div>
        <?= Html::submitButton('查询', ['class' => 'button-blue ml-50', 'type' => 'submit']) ?>
        <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
    </div>

</div>

<?php ActiveForm::end(); ?>
<div class="space-30"></div>