<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApplySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-80 {
        width: 80px;
    }
    .width-100 {
        width: 100px;
    }
    .width-150 {
        width: 150px;
    }
    .ml-20 {
        margin-left: 20px;
    }
</style>
<div class="crm-credit-apply-search">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="search-div">
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align">申请编号</label>
                <label>:</label>
                <input type="text" name="CrmCreditApplySearch[credit_code]" class="width-150 qvalue-align" value="<?= $queryParam['CrmCreditApplySearch']['credit_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">单据状态</label>
                <label>:</label>
                <select name="CrmCreditApplySearch[credit_status]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['status'] as $key => $val) {?>
                        <option value="<?= $key ?>" <?= isset($queryParam['CrmCreditApplySearch']['credit_status'])&&$queryParam['CrmCreditApplySearch']['credit_status']==$key?"selected":null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">交易法人</label>
                <label>:</label>
                <select name="CrmCreditApplySearch[company_id]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['company'] as $key => $val) {?>
                        <option value="<?= $val['company_id'] ?>" <?= isset($queryParam['CrmCreditApplySearch']['company_id'])&&$queryParam['CrmCreditApplySearch']['company_id']==$val['company_id']?"selected":null ?>><?= $val['company_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10">

            <div class="inline-block">
                <label class="width-80 qlabel-align">客户代码</label>
                <label>:</label>
                <input type="text" name="CrmCreditApplySearch[cust_code]" class="width-150 qvalue-align"
                       value="<?= $queryParam['CrmCreditApplySearch']['cust_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">客户名称</label>
                <label>:</label>
                <input type="text" name="CrmCreditApplySearch[cust_sname]" class="width-150 qvalue-align" value="<?= $queryParam['CrmCreditApplySearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">申请账信类型</label>
                <label>:</label>
                <select name="CrmCreditApplySearch[credit_type]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['verifyType'] as $key => $val) {?>
                        <option value="<?= $val['business_type_id'] ?>" <?= isset($queryParam['CrmCreditApplySearch']['credit_type'])&&$queryParam['CrmCreditApplySearch']['credit_type']==$val['business_type_id']?"selected":null ?>><?= $val['business_value'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <?= Html::submitButton('查询', ['class' => 'ml-20 search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'button-blue reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
