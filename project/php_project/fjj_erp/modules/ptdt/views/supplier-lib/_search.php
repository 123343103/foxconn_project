<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$get = Yii::$app->request->get();
if (!isset($get['SupplierInfoSearch'])) {
    $get['SupplierInfoSearch'] = null;
}
?>
<?php ActiveForm::begin(['method' => 'get','action' => 'index']); ?>
<div class="mb-10">
    <label class="width-100">供应商名称</label>
    <input type="text" class="width-200" name="SupplierInfoSearch[supplier_sname]" value="<?= $get['SupplierInfoSearch']['supplier_sname'] ?>">
    <label class="width-100">品&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;牌</label>
    <input type="text" class="width-200" name="SupplierInfoSearch[supplier_brand]" value="<?= $get['SupplierInfoSearch']['supplier_brand'] ?>">
    <label class="width-100">供应商类型</label>
    <select class="width-200" name="SupplierInfoSearch[supplier_type]">
        <option value="">请选择</option>
        <?php foreach ($downList['supplierType'] as $key => $val) { ?>
            <option value="<?= $val['bsp_id'] ?>" <?= isset($get['SupplierInfoSearch']['supplier_type'])&&$get['SupplierInfoSearch']['supplier_type'] == $val['bsp_id'] ? "selected" : null ?> ><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-10">
    <label class="width-100">集团供应商</label>
    <select class="width-200" name="SupplierInfoSearch[supplier_issupplier]">
        <option value="">请选择</option>
        <option value="1" <?= isset($get['SupplierInfoSearch']['supplier_issupplier'])&&$get['SupplierInfoSearch']['supplier_issupplier'] == '1' ? "selected" : null ?>>是</option>
        <option value="0" <?= isset($get['SupplierInfoSearch']['supplier_issupplier'])&&$get['SupplierInfoSearch']['supplier_issupplier'] == '0' ? "selected" : null ?> >否</option>
    </select>
    <label class="width-100">供应商状态</label>
    <select class="width-200" name="SupplierInfoSearch[supplier_status]">
        <option value="">请选择</option>
        <?php foreach ($downList['status'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['SupplierInfoSearch']['supplier_status'])&&$get['SupplierInfoSearch']['supplier_status'] == $key ? "selected" : null ?> ><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">供应商来源</label>
    <select class="width-200" name="SupplierInfoSearch[supplier_source]">
        <option value="">请选择</option>
        <?php foreach ($downList['supplierSource'] as $key => $val) { ?>
            <option value="<?= $val['bsp_id'] ?>" <?= isset($get['SupplierInfoSearch']['supplier_source'])&&$get['SupplierInfoSearch']['supplier_source'] == $val['bsp_id'] ? "selected" : null ?> ><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-30 ml-300">
    <div class="width-480 inline-block"></div>
    <?= Html::submitButton('查询', ['class' => 'search-btn-blue']) ?>
    <?= Html::button('重置', ['class' => 'reset-btn-yellow', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
</div>
<?php ActiveForm::end(); ?>
