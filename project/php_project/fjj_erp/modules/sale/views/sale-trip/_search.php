<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


$get = Yii::$app->request->get();
if(!isset($get['CrmCustomerInfoSearch'])){
    $get['CrmCustomerInfoSearch']=null;
}
?>

<div class="crm-customer-info-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100">数据类型</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_filernumber]" class="width-100"
                       value="<?= $get['CrmCustomerInfoSearch']['cust_filernumber'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100">申请编号</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="width-100" value="<?= $get['CrmCustomerInfoSearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100">出差申请人</label>
                <select name="CrmCustomerInfoSearch[cust_type]" class="width-100">
                    <option value="">---请选择---</option>
                   <!-- <?php /*foreach ($downList['customerType'] as $key => $val) {*/?>
                        <option value="<?/*=$val['bsp_id'] */?>" <?/*= isset($get['CrmCustomerInfoSearch']['cust_type'])&&$get['CrmCustomerInfoSearch']['cust_type']==$val['bsp_id']?"selected":null */?>><?/*= $val['bsp_svalue'] */?></option>
                    --><?php /*} */?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100">职位</label>
                <select name="CrmCustomerInfoSearch[cust_salearea]" class="width-100">
                    <option value="">---请选择---</option>
                    <?php /*foreach ($salearea as $key => $val) {*/?><!--
                        <option value="<?/*=$val['csarea_id'] */?>" <?/*= isset($get['CrmCustomerInfoSearch']['cust_salearea'])&&$get['CrmCustomerInfoSearch']['cust_salearea']==$val['csarea_id']?"selected":null */?>><?/*= $val['csarea_name'] */?></option>
                    --><?php /*} */?>
                </select>
            </div>
         </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100">状态</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_manager]" class="width-100" value="<?= $get['CrmCustomerInfoSearch']['cust_manager'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100">申请日期</label>
                <input type="text" name="CrmCustomerInfoSearch[create_by]" class="width-100" value="<?= $get['CrmCustomerInfoSearch']['create_by'] ?>">
            </div>
            <div class="inline-block ">
                <label class="width-100">报告日期</label>
                <input type="text" class="width-100 select-date" name="CrmCustomerInfoSearch[startDate]" value="<?= $get['CrmCustomerInfoSearch']['startDate'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-100', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
