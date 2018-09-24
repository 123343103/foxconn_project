<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    label:after{
        content: ':';
        padding:0 4px;
    }
    .width-80{
        width:80px;
    }
    .width-150{
        width:150px;
    }
    .ml-20{
        margin-left: 20px;
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
                <label class="width-80 qlabel-align">公司名称</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="width-150 qvalue-align" value="<?= $queryParam['CrmCustomerInfoSearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align">联系人</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_contacts]" class="width-150 qvalue-align" value="<?= $queryParam['CrmCustomerInfoSearch']['cust_contacts'] ?>">
            </div>

            <div class="inline-block">
                <label class="width-80 qlabel-align">经营范围</label>
                <input type="text" name="CrmCustomerInfoSearch[member_businessarea]" class="width-150 qvalue-align" value="<?= $queryParam['CrmCustomerInfoSearch']['member_businessarea'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-80 qlabel-align">客户来源</label>
                <select name="CrmCustomerInfoSearch[member_source]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['customerSource'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['member_source'])&&$queryParam['CrmCustomerInfoSearch']['member_source']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-80 qlabel-align">潜在需求</label>
                <select name="CrmCustomerInfoSearch[member_reqflag]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['latentDemand'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['member_reqflag'])&&$queryParam['CrmCustomerInfoSearch']['member_reqflag']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="inline-block">
                <label class="width-80 qlabel-align">状态</label>
                <select name="CrmCustomerInfoSearch[member_visitflag]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <option value="0" <?= $queryParam['CrmCustomerInfoSearch']['member_visitflag']=="0"?"selected":null; ?>>未拜访</option>
                    <option value="1" <?= $queryParam['CrmCustomerInfoSearch']['member_visitflag']=="1"?"selected":null; ?>>已拜访</option>
                </select>
            </div>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-20', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
