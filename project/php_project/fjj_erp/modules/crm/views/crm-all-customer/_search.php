<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<div class="crm-customer-info-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">客户类型</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[cust_type]" class="width-120 qvalue-align" style="width:180px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['customerType'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_type'])&&$queryParam['CrmCustomerInfoSearch']['cust_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">客户属性</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[property]" class="width-120 qvalue-align" style="width: 180px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['property'] as $key => $val) { ?>
                        <option value="<?= $val ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['property'])&&$queryParam['CrmCustomerInfoSearch']['property']==$val?"selected":null ?> ><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block" >
                <label class="width-80 qlabel-align" style="width: 80px;">是否会员</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[cust_ismember]" class="width-120 qvalue-align" style="width: 176px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['cust_ismember'] as $key => $val) { ?>
                        <option value="<?= $val ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_ismember'])&&$queryParam['CrmCustomerInfoSearch']['cust_ismember']==$val?"selected":null ?> ><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">营销区域</label>
                <label>:</label>
                <select name="CrmCustomerInfoSearch[cust_salearea]" class="width-120 qvalue-align" style="width: 110px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['salearea'] as $key => $val) {?>
                        <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_salearea'])&&$queryParam['CrmCustomerInfoSearch']['cust_salearea']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">所在地区</label>
                <label>:</label>
                <select style="width: 120px;" id="custArea" name="CrmCustomerInfoSearch[cust_area]" class="qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['province'] as $key => $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_area'])&&$queryParam['CrmCustomerInfoSearch']['cust_area']==$val['district_id']?"selected":null ?> ><?= $val['district_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">客户名称</label>
                <label>:</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="width-110 qvalue-align" style="width: 105px;" value="<?= $queryParam['CrmCustomerInfoSearch']['cust_sname'] ?>">
            </div>



            <div class="inline-block">
                <label class="width-80 qlabel-align" style="width: 80px;">客户经理人</label>
                <label>:</label>
                <input type="text" name="CrmCustomerInfoSearch[custManager]" class="width-110 qvalue-align"  style="width: 107px;"  value="<?= $queryParam['CrmCustomerInfoSearch']['custManager'] ?>">
            </div>

            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-300', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow ml-50', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
