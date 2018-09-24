<?php
/**
 * User: F1677929
 * Date: 2016/11/2
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$get = Yii::$app->request->get();
if (!isset($get['PdFirmEvaluateApplySearch'])) {
    $get['PdFirmEvaluateApplySearch'] = null;
}
?>
<?php ActiveForm::begin(['method' => 'get','action' => 'index']); ?>
<div class="mb-10">
    <label class="width-100">厂商名称</label>
    <input type="text" class="width-200" name="PdFirmEvaluateApplySearch[firmName]" value="<?= $get['PdFirmEvaluateApplySearch']['firmName'] ?>">
    <label class="width-100">厂商定位</label>
    <select class="width-200" name="PdFirmEvaluateApplySearch[firmPosition]">
        <option value="">请选择</option>
        <?php foreach ($indexData['firmPosition'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateApplySearch']['firmPosition'])&&$get['PdFirmEvaluateApplySearch']['firmPosition'] == $key ? "selected" : null ?>><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">申请日期</label>
    <input type="text" class="width-100 select-date" name="PdFirmEvaluateApplySearch[startDate]" readonly="readonly" value="<?= $get['PdFirmEvaluateApplySearch']['startDate'] ?>">
    <label class="no-after">至</label>
    <input type="text" class="width-100 select-date" name="PdFirmEvaluateApplySearch[endDate]" readonly="readonly" value="<?= $get['PdFirmEvaluateApplySearch']['endDate'] ?>">
</div>
<div class="mb-30">
    <label class="width-100">评鉴类型</label>
    <select class="width-200" name="PdFirmEvaluateApplySearch[evaluateApplyType]">
        <option value="">请选择</option>
        <?php foreach ($indexData['evaluateApplyType'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateApplySearch']['evaluateApplyType'])&&$get['PdFirmEvaluateApplySearch']['evaluateApplyType'] == $key ? "selected" : null ?>><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">评鉴状态</label>
    <select class="width-200" name="PdFirmEvaluateApplySearch[evaluateApplyStatus]">
        <option value="">请选择</option>
        <?php foreach ($indexData['evaluateApplyStatus'] as $key => $val) { ?>
            <option value="<?= $key ?>" <?= isset($get['PdFirmEvaluateApplySearch']['evaluateApplyStatus'])&&$get['PdFirmEvaluateApplySearch']['evaluateApplyStatus'] == $key ? "selected" : null ?>><?= $val ?></option>
        <?php } ?>
    </select>
    <?= Html::submitButton('查询', ['class' => 'button-blue','style' => 'margin-left:104px;']) ?>
    <?= Html::button('重置', ['class' => 'button-blue', 'onclick'=>'window.location.href="'.Url::to(['index']).'"']) ?>
</div>
<?php ActiveForm::end(); ?>
