<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    .label-width {
        width: 80px;
    }
    .space-20 {
        width: 100%;
        height: 20px;
    }
    .value-width {
        width: 150px;
    }
</style>
<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['list'],
        'method' => 'get',
    ]); ?>

    <div class="search-div">
        <div class="mb-10">
            <label class="label-width qlabel-align">需求类目</label><label>：</label>
            <select name="CrmCustomerInfoSearch[member_reqitemclass]" class="value-width qvalue-align">
                <option value="">请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option
                        value="<?= $val['catg_id'] ?>"<?= $search['member_reqitemclass'] == $val['catg_id'] ? "selected" : null; ?>><?= $val['catg_name'] ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align">客户来源</label><label>：</label>
            <select name="CrmCustomerInfoSearch[member_source]" class="value-width qvalue-align">
                <option value="">请选择...</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= isset($search['member_source']) && $search['member_source'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align">分配状态</label><label>：</label>
            <select name="CrmCustomerInfoSearch[mchpdtype_status]" class="value-width qvalue-align">
                <option value="">请选择...</option>
                <option value="10" <?= $search['mchpdtype_status'] == "10" ? "selected" : null; ?>>已分配</option>
                <option value="0" <?= $search['mchpdtype_status'] == "0" ? "selected" : null; ?>>未分配</option>
            </select>
            <label class="label-width qlabel-align">公司名称</label><label>：</label>
            <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                   value="<?= $search['cust_sname'] ?>">
            <label class="label-width qlabel-align">分配人员</label><label>：</label>
            <input type="text" name="CrmCustomerInfoSearch[mchpdtype_sname]" class="value-width qvalue-align"
            <?= (Yii::$app->user->identity->is_supper == 1) ? "" : "" ?>"
            value="">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'style' => 'margin-left:50px', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow ml-40', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["list"]) . '\'']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
