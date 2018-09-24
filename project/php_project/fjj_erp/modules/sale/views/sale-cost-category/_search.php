<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


$get = Yii::$app->request->get();
if(!isset($get['SaleCostListSearch'])){
    $get['SaleCostListSearch']=null;
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
                <label class="width-100">费用代码</label>
                <input type="text" name="SaleCostListSearch[stcl_code]" class="width-100"
                       value="<?= $get['SaleCostListSearch']['stcl_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100">费用名称</label>
                <input type="text" name="SaleCostListSearch[stcl_sname]" class="width-100" value="<?= $get['SaleCostListSearch']['stcl_sname'] ?>">
            </div>
            <div class="inline-block">
                <label class="width-100">状态</label>
                <select name="SaleCostListSearch[stcl_status]" class="width-100">
                    <option value="">---请选择---</option>
                    <option value="1">有效</option>
                    <option value="0">无效</option>
                   <!-- <?php /*foreach ($downList['customerType'] as $key => $val) {*/?>
                        <option value="<?/*=$val['bsp_id'] */?>" <?/*= isset($get['CrmCustomerInfoSearch']['cust_type'])&&$get['CrmCustomerInfoSearch']['cust_type']==$val['bsp_id']?"selected":null */?>><?/*= $val['bsp_svalue'] */?></option>
                    --><?php /*} */?>
                </select>
            </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-100', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
    <div class="space-30"></div>
</div>
