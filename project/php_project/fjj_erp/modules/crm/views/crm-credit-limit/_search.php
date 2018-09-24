<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApplySearch */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-80 {
        width: 80px;
    }
    .width-200 {
        width: 200px;
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
                <label class="qlabel-align width-80">客户代码</label>
                <label>:</label>
                <input type="text" name="CrmCreditLimitSearch[cust_code]" class="width-200 qvalue-align"
                       value="<?= $queryParam['CrmCreditLimitSearch']['cust_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align">客户名称</label>
                <label>:</label>
                <input type="text" name="CrmCreditLimitSearch[cust_sname]" class="width-200 qvalue-align" value="<?= $queryParam['CrmCreditLimitSearch']['cust_sname'] ?>">
            </div>

        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qlabel-align width-80">账信类型</label>
                <label>:</label>
                <select name="CrmCreditLimitSearch[credit_type]" class="width-200 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['verifyType'] as $key => $val) {?>
                        <option value="<?= $val['business_type_id'] ?>" <?= isset($queryParam['CrmCreditLimitSearch']['credit_type'])&&$queryParam['CrmCreditLimitSearch']['credit_type']==$val['business_type_id']?"selected":null ?>><?= $val['business_value'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align">有效期至</label>
                <label>:</label>
                <input type="text" class="select-date width-200 Wdate qvalue-align" id="start" name="CrmCreditLimitSearch[start]" value="<?= $queryParam['CrmCreditLimitSearch']['start'] ?>" onfocus="this.blur()">
                <label class="no-after">至</label>
                <input type="text" class="select-date width-200 Wdate qvalue-align" id="end" name="CrmCreditLimitSearch[end]" value="<?= $queryParam['CrmCreditLimitSearch']['end'] ?>"  onfocus="this.blur()">
            </div>

            <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(function(){

        $("#start").click(function(){
            WdatePicker({
                skin:"whyGreen",
                maxDate:"#F{$dp.$D('end',{d:-1})}"
            });
        });
        $("#end").click(function(){
            WdatePicker({
                skin:"whyGreen",
                minDate:"#F{$dp.$D('start',{d:1})}"
            });
        });
    })
</script>
