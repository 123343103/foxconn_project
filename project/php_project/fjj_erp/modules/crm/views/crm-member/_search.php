<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    .label-width{
        width:80px;
    }
    .value-width{
        width:100px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-10 {
        width: 100%;
        height: 10px;
    }
    .space-30 {
        width: 100%;
        height: 30px;
    }
</style>
<div class="crm-customer-info-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">会员类型</label><label>:</label>
                <select name="CrmCustomerInfoSearch[member_type]" class="value-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['memberType'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['member_type'])&&$queryParam['CrmCustomerInfoSearch']['member_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">客户来源</label><label>:</label>
                <select name="CrmCustomerInfoSearch[member_source]" class="value-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['customerSource'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['member_source'])&&$queryParam['CrmCustomerInfoSearch']['member_source']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">注册网站</label><label>:</label>
                <select name="CrmCustomerInfoSearch[member_regweb]" class="value-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['regWeb'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['member_regweb'])&&$queryParam['CrmCustomerInfoSearch']['member_regweb']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">潜在需求</label><label>:</label>
                <select name="CrmCustomerInfoSearch[member_reqflag]" class="value-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['latentDemand'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['member_reqflag'])&&$queryParam['CrmCustomerInfoSearch']['member_reqflag']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width qlabel-align">回访状态</label><label>:</label>
                <select name="CrmCustomerInfoSearch[member_visitflag]" class="value-width qvalue-align">
                    <option value="">请选择...</option>
                    <option value="0" <?= $queryParam['CrmCustomerInfoSearch']['member_visitflag']=="0"?"selected":null; ?>>否</option>
                    <option value="1" <?= $queryParam['CrmCustomerInfoSearch']['member_visitflag']=="1"?"selected":null; ?>>是</option>
                </select>
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">公司名称</label><label>:</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                       value="<?= $queryParam['CrmCustomerInfoSearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">用户名</label><label>:</label>
                <input type="text" name="CrmCustomerInfoSearch[member_name]" class="value-width qvalue-align" value="<?= $queryParam['CrmCustomerInfoSearch']['member_name'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width qlabel-align">手机号码</label><label>:</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_tel2]" class="value-width qvalue-align" value="<?= $queryParam['CrmCustomerInfoSearch']['cust_tel2'] ?>">
            </div>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-20', 'type' => 'submit']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow ml-20', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
