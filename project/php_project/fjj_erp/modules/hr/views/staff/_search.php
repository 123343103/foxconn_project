<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>
    .label-width{
        width: 80px;
    }
    .value-width{
        width: 120px;
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get'
    ]); ?>
    <div class="mb-10">
        <label for="staffsearch-staff_code" class="qlabel-align ">工号：</label>
        <input type="text" name="StaffSearch[staff_code]" class="value-align value-width" id="staffsearch-staff_code" value="<?= $search['staff_code'] ?>">
        <label for="staffsearch-staff_name" class="qlabel-width label-align">姓名：</label>
        <input type="text" name="StaffSearch[staff_name]" class="value-width value-align" id="staffsearch-staff_name" value="<?= $search['staff_name'] ?>">
        <label for="staffsearch-organization_code" class="qlabel-width label-align">单位：</label>
        <input type="text" name="StaffSearch[organization_name]" class="value-align value-width" id="staffsearch-organization_code" value="<?= $search['organization_name'] ?>">
        <label for="staffsearch-position" class="qlabel-width label-align">管理职：</label>
        <select name="StaffSearch[position]" style="width:100px;" id="staffsearch-position">
            <option value="">请选择</option>
            <?php foreach ($downList['staffTitle'] as $val) {?>
                <option value="<?=$val['title_id'] ?>" <?= $search['position']==$val['title_id']?"selected":null ?>><?= $val['title_name'] ?></option>
            <?php } ?>
        </select>
        <label style="width:60px;">员工状态：</label>
        <select style="width:100px;" name="StaffSearch[staff_status]">
            <option value="">请选择</option>
            <option value="10" <?=$search['staff_status']==10?"selected":""?>>在职</option>
            <option value="20" <?=$search['staff_status']==20?"selected":""?>>离职</option>
        </select>
    <?= Html::submitButton('查询', ['class' => 'search-btn-blue']) ?>
    <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["index"]).'\'']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="space-10"></div>
<div class="space-10"></div>
<div class="space-10"></div>
