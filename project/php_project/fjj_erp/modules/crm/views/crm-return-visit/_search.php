<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    label:after{
        content: ':';
        padding:0 4px ;
    }
    .width-100{
        width:100px;
    }
    .width-150{
        width:150px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-30{
        width:100%;
        height:30px;
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
                <label class="width-100 qlabel-align">公司名称</label>
                <input type="text" name="CrmVisitRecordSearch[cust_sname]" class="width-150 qvalue-align"
                       value="<?= $queryParam['CrmVisitRecordSearch']['cust_sname'] ?>">
            </div>

            <div class="inline-block">
                <label class="width-100 qlabel-align" for="">经营模式</label>
                <select class="width-150 qvalue-align" name="CrmVisitRecordSearch[cust_businesstype]">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['managementType'] as $key => $val) { ?>
                        <option
                            value="<?= $val['bsp_id'] ?>" <?= $queryParam['CrmVisitRecordSearch']['cust_businesstype'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">客户来源</label>
                <select name="CrmVisitRecordSearch[member_source]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['customerSource'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmVisitRecordSearch']['member_source'])&&$queryParam['CrmVisitRecordSearch']['member_source']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 qlabel-align">潜在需求</label>
                <select name="CrmVisitRecordSearch[member_reqflag]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['latentDemand'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmVisitRecordSearch']['member_reqflag'])&&$queryParam['CrmVisitRecordSearch']['member_reqflag']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">公司所在地区</label>
                <select name="CrmVisitRecordSearch[cust_salearea]" class="width-150 qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['saleArea'] as $key => $val) {?>
                        <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['CrmVisitRecordSearch']['cust_salearea'])&&$queryParam['CrmVisitRecordSearch']['cust_salearea']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="width-100 qlabel-align">姓名</label>
                <input type="text" name="CrmVisitRecordSearch[cust_contacts]" class="width-150 qvalue-align" value="<?= $queryParam['CrmVisitRecordSearch']['cust_contacts'] ?>">
            </div>
                <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-20', 'type' => 'submit']) ?>
                <?= Html::button('重置', ['class' => 'reset-btn-yellow ml-20', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>

        </div>

    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
