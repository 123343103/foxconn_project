<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

if (Yii::$app->user->identity->is_supper != 1) {
    $search['mchpdtype_sname'] = yii::$app->user->identity->staff->staff_name;
}
?>
<style>
    .label-width {
        width: 70px;
    }

    .space-20 {
        width: 100%;
        height: 20px;
    }

    .value-width {
        width: 100px;
    }
</style>
<div class="crm-customer-info-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div">
        <div class="mb-10">


            <label class="label-width qlabel-align">招商状态</label><label>：</label>
            <select name="CrmCustomerInfoSearch[investment_status]" class="value-width qvalue-align">
                <option value="">全部</option>
                <?php foreach ($downList['investmentStatus'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= $search['investment_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            </select>
            <label class="label-width qlabel-align">客户来源</label><label>：</label>
            <select name="CrmCustomerInfoSearch[member_source]" class="value-width qvalue-align">
                <option value="">全部</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= isset($search['member_source']) && $search['member_source'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>

            <label class="label-width qlabel-align" for="">经营类型</label><label>：</label>
            <select class="value-width qvalue-align" name="CrmCustomerInfoSearch[cust_businesstype]">
                <option value="">全部</option>
                <?php foreach ($downList['businessModel'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= $search['cust_businesstype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-10">

            <label class="label-width qlabel-align">分配状态</label><label>：</label>
            <select name="CrmCustomerInfoSearch[mchpdtype_status]" class="value-width qvalue-align">
                <option value="">全部</option>
                <option value="10" <?= $search['mchpdtype_status'] == "10" ? "selected" : null; ?>>已分配</option>
                <option value="0" <?= $search['mchpdtype_status'] == "0" ? "selected" : null; ?>>未分配</option>
            </select>
            <label class="label-width qlabel-align">公司名称</label><label>：</label>
            <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                   value="<?= $search['cust_sname'] ?>">
            <label class="label-width qlabel-align">联系人</label><label>：</label>
            <input type="text" name="CrmCustomerInfoSearch[cust_contacts]" class="value-width qvalue-align"
                   value="<?= $search['cust_contacts'] ?>">

            <label class="label-width qlabel-align">分配人员</label><label>：</label>
            <input type="text" name="CrmCustomerInfoSearch[mchpdtype_sname]" class="value-width qvalue-align"
                   style="margin-right: 18px"
                <?= (Yii::$app->user->identity->is_supper == 1) ? "" : "" ?>
                   value="">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset', 'id' => 'reset', 'onclick' => 'window.location.href=\'' . \yii\helpers\Url::to(["index"]) . '\'']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>

