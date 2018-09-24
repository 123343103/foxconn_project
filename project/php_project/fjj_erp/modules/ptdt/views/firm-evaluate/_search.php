<?php
/**
 * User: F1677929
 * Date: 2016/11/2
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$get = Yii::$app->request->get();
if (!isset($get['PdFirmEvaluateSearch'])) {
    $get['PdFirmEvaluateSearch'] = null;
}
?>
<?php ActiveForm::begin(['method' => 'get','action' => 'index']); ?>
<div class="mb-10">
    <label class="width-100">供应商名称</label>
    <input type="text" class="width-180" name="PdFirmEvaluateSearch[firmName]" value="<?= $get['PdFirmEvaluateSearch']['firmName'] ?>">
    <label class="width-100">集团供应商</label>
    <select class="width-180" name="PdFirmEvaluateSearch[groupSupplier]">
        <option value="">请选择</option>
        <?php foreach ($indexData['groupSupplier'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateSearch']['groupSupplier'])&&$get['PdFirmEvaluateSearch']['groupSupplier'] == $key ? "selected" : null ?> ><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">申请日期</label>
    <input type="text" class="width-80 select-date" name="PdFirmEvaluateSearch[startDate]" readonly="readonly" value="<?= $get['PdFirmEvaluateSearch']['startDate'] ?>">
    <label class="no-after">至</label>
    <input type="text" class="width-80 select-date" name="PdFirmEvaluateSearch[endDate]" readonly="readonly" value="<?= $get['PdFirmEvaluateSearch']['endDate'] ?>">
</div>
<div class="mb-30">
    <label class="width-100">商品类别</label>
    <select class="width-180" name="PdFirmEvaluateSearch[productType]">
        <option value="">请选择</option>
        <?php foreach ($indexData['productType'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateSearch']['productType'])&&$get['PdFirmEvaluateSearch']['productType'] == $key ? "selected" : null ?> ><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">供应商分类</label>
    <select class="width-180" name="PdFirmEvaluateSearch[firmType]">
        <option value="">请选择</option>
        <?php foreach ($indexData['firmType'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateSearch']['firmType'])&&$get['PdFirmEvaluateSearch']['firmType'] == $key ? "selected" : null ?> ><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">评鉴状态</label>
    <select class="width-180" name="PdFirmEvaluateSearch[evaluateStatus]">
        <option value="">请选择</option>
        <?php foreach ($indexData['evaluateStatus'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateSearch']['evaluateStatus'])&&$get['PdFirmEvaluateSearch']['evaluateStatus'] == $key ? "selected" : null ?> ><?= $val ?></option>
        <?php } ?>
    </select>
    <?= Html::submitButton('查询', ['class' => 'button-blue']) ?>
    <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
</div>
<?php ActiveForm::end(); ?>
