<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


$get = Yii::$app->request->get();
if (!isset($get['CrmCustomerApplySearch'])) {
    $get['CrmCustomerApplySearch'] = null;
}
?>
<style>
    .label-width {
        width: 100px;
    }

    .value-width {
        width: 100px;
    }
</style>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
]); ?>

<div class="search-div">
    <div class="mb-10">
        <div class="inline-block">
            <label class=" qlabel-align">客戶编号：</label>
            <input type="text" name="CrmCustomerApplySearch[cust_filernumber]" style="width: 157px;"
                   value="<?= $get['CrmCustomerApplySearch']['cust_filernumber'] ?>">
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align">客户名称：</label>
            <input type="text" name="CrmCustomerApplySearch[cust_sname]" style="width: 157px;"
                   value="<?= $get['CrmCustomerApplySearch']['cust_sname'] ?>">
        </div>
        <div class="inline-block ">
            <input type="hidden" name="CrmCustomerApplySearch[cust_type1]" value="1">
            <label class="label-width qlabel-align">客户类型：</label>
            <select name="CrmCustomerApplySearch[cust_type]" style="width: 157px;">
                <option value="">全部</option>
                <?php foreach ($downList['customerType'] as $key => $val) { ?>
                    <option
                        value="<?= $val['bsp_id'] ?>" <?= isset($get['CrmCustomerApplySearch']['cust_type']) && $get['CrmCustomerApplySearch']['cust_type'] == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class=" qlabel-align">营销区域：</label>
            <select name="CrmCustomerApplySearch[cust_salearea]" style="width: 157px;">
                <option value="">全部</option>
                <?php foreach ($downList['salearea'] as $key => $val) { ?>
                    <option
                        value="<?= $val['csarea_id'] ?>" <?= isset($get['CrmCustomerApplySearch']['cust_salearea']) && $get['CrmCustomerApplySearch']['cust_salearea'] == $val['csarea_id'] ? "selected" : null ?>><?= $val['csarea_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block">
            <label class="label-width qlabel-align">客户经理人：</label>
            <select name="CrmCustomerApplySearch[cust_manager]" style="width: 157px;">
                <option value="">全部</option>
                <?php if (isset($get['CrmCustomerApplySearch']['cust_manager'])) { ?>
                    <?php foreach ($downList['manager'] as $key => $value) { ?>
                        <option
                            value="<?= $value['staffName']['staff_name'] ?>" <?= $get['CrmCustomerApplySearch']['cust_manager'] == $value['staffName']['staff_name'] ? "selected" : null ?>><?= $value['staffName']['staff_name'] ?></option>
                    <?php } ?>
                <?php } else { ?>
                    <?php foreach ($downList['manager'] as $key => $value) { ?>
                        <option
                            value="<?= $value['staffName']['staff_name'] ?>" <?= $staffName == $value['staffName']['staff_name'] ? "selected" : null ?>><?= $value['staffName']['staff_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block ">
            <label class="label-width qlabel-align">状态：</label>
            <!--<input type="text" class="width-80 " name="CrmCustomerApplySearch[status]" value="<? /*= $get['CrmCustomerApplySearch']['status'] */ ?>">-->
            <select name="CrmCustomerApplySearch[status]" class="value-width qvalue-align" style="width: 157px;">
                <option value="">全部</option>
                <option
                    value="30" <?= isset($get['CrmCustomerApplySearch']['status']) && $get['CrmCustomerApplySearch']['status'] == '30' ? "selected" : null; ?>>
                    审核中
                </option>
                <option
                    value="40" <?= isset($get['CrmCustomerApplySearch']['status']) && $get['CrmCustomerApplySearch']['status'] == '40' ? "selected" : null; ?>>
                    审核完成
                </option>
                <option
                    value="50" <?= isset($get['CrmCustomerApplySearch']['status']) && $get['CrmCustomerApplySearch']['status'] == '50' ? "selected" : null; ?>>
                    驳回
                </option>
                <option
                    value="60" <?= isset($get['CrmCustomerApplySearch']['status']) && $get['CrmCustomerApplySearch']['status'] == '60' ? "selected" : null; ?>>
                    已取消
                </option>
            </select>
        </div>
        <div class="inline-block">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue', 'style' => 'margin-left:10px', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'style' => 'margin-left:10px', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
    </div>
</div>
<div class="space-10"></div>

<?php ActiveForm::end(); ?>

