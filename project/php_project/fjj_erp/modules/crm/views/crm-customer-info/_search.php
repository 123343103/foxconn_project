<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<div class="crm-customer-info-search" >

    <style>
        .qlabel-width{
            width: 80px;
        }
        .qvalue-width{
            width:120px;
        }
    </style>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="qvalue-width qlabel-align">客户编号/客户代码：</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_filernumber]" class="qvalue-width qvalue-align"
                       value="<?= $queryParam['CrmCustomerInfoSearch']['cust_filernumber'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户名称：</label>
                <input type="text" name="CrmCustomerInfoSearch[cust_sname]" class="qvalue-width qvalue-align" value="<?= $queryParam['CrmCustomerInfoSearch']['cust_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户类型：</label>
                <select name="CrmCustomerInfoSearch[cust_type]" class="qvalue-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['customerType'] as $key => $val) {?>
                        <option value="<?=$val['bsp_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_type'])&&$queryParam['CrmCustomerInfoSearch']['cust_type']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">营销区域：</label>
                <select name="CrmCustomerInfoSearch[cust_salearea]" class="qvalue-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['salearea'] as $key => $val) {?>
                        <option value="<?=$val['csarea_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['cust_salearea'])&&$queryParam['CrmCustomerInfoSearch']['cust_salearea']==$val['csarea_id']?"selected":null ?>><?= $val['csarea_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
         </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="qvalue-width qlabel-align">客户经理人：</label>
<!--                <input type="text" name="CrmCustomerInfoSearch[custManager]" class="qvalue-width qvalue-align" value="--><?//= $queryParam['CrmCustomerInfoSearch']['custManager'] ?><!--">-->
                <select name="CrmCustomerInfoSearch[custManager]" class="qvalue-width qvalue-align">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['manager'] as $key => $val) {?>
                        <option value="<?=$val['staff_id'] ?>" <?= isset($queryParam['CrmCustomerInfoSearch']['custManager'])&&$queryParam['CrmCustomerInfoSearch']['custManager']==$val['staff_id']?"selected":null ?>><?= $val['staff_name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="inline-block">
                <label class="qlabel-width qlabel-align">认领状态：</label>
                <select name="CrmCustomerInfoSearch[personinch_status]" class="qvalue-width qvalue-align">
                    <option value="">请选择...</option>
                    <option value="0" <?= isset($queryParam['CrmCustomerInfoSearch']['personinch_status'])&&$queryParam['CrmCustomerInfoSearch']['personinch_status'] == '0' ? "selected":null; ?>>未认领</option>
                    <option value="10" <?= isset($queryParam['CrmCustomerInfoSearch']['personinch_status'])&&$queryParam['CrmCustomerInfoSearch']['personinch_status'] == '10' ? "selected":null; ?>>已认领</option>
                </select>
            </div>
            <div class="inline-block">
                <label class="qlabel-width qlabel-align">客户状态：</label>
                <select name="CrmCustomerInfoSearch[sale_status]" class="qvalue-width qvalue-align">
                    <option value="">请选择...</option>
                    <option value="10" <?= isset($queryParam['CrmCustomerInfoSearch']['sale_status'])&&$queryParam['CrmCustomerInfoSearch']['sale_status'] == '10' ? "selected":null; ?>>正常</option>
                    <option value="0" <?= isset($queryParam['CrmCustomerInfoSearch']['sale_status'])&&$queryParam['CrmCustomerInfoSearch']['sale_status'] == '0' ? "selected":null; ?>>已删除</option>
                </select>
            </div>
<!--            <div class="inline-block">-->
<!--                <label class="width-80">档案建立人</label>-->
<!--                <input type="text" name="CrmCustomerInfoSearch[create_by]" class="width-100" value="--><?//= $queryParam['CrmCustomerInfoSearch']['create_by'] ?><!--">-->
<!--            </div>-->
<!--            <div class="inline-block ">-->
<!--                <label class="width-70">建档时间</label>-->
<!--                <input type="text" class="width-80 select-date" name="CrmCustomerInfoSearch[startDate]" value="--><?//= $queryParam['CrmCustomerInfoSearch']['startDate'] ?><!--">-->
<!--            </div>-->
<!--            <div class="inline-block">-->
<!--                <label class="no-after">~</label>-->
<!--                <input type="text"class="width-80 select-date" name="CrmCustomerInfoSearch[endDate]" value="--><?//= $queryParam['CrmCustomerInfoSearch']['endDate'] ?><!--">-->
<!--            </div>-->
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'type' => 'submit','style'=>'margin-left:20px;']) ?>
            <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-10"></div>
    <div class="space-10"></div>
    <div class="space-10"></div>
</div>
