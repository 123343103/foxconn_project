<?php
/**
 * User: F1677929
 * Date: 2016/11/18
 */
/* @var $this yii\web\view */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '采购评鉴意见';
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '厂商评鉴列表', 'url' => Url::to(['firm-evaluate/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first"><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_com', [
        'firmData' => $evaluateData['firmData'],
        'evaluateChildData' => $evaluateData['evaluateChildData'],
        'firmEvaluateData' => $evaluateData['firmEvaluateData'],
    ]) ?>
    <h2 class="head-second">采购意见</h2>
    <?php ActiveForm::begin(['id'=>'purchase_form']); ?>
    <div class="mb-20">
        <label class="width-110">评鉴结果</label>
        <select name="PdFirmEvaluateResult[evaluate_result]" class="width-200" id="purchase_evaluate_result">
            <option value="">请选择</option>
            <?php foreach ($evaluateData['purchaseManageEvaluateResultList'] as $key => $val) { ?>
                <option value="<?= $key ?>" <?= isset($evaluateData['purchaseEvaluateData'])&&$evaluateData['purchaseEvaluateData']['evaluate_result']==$key ? "selected" : null ?>><?= $val ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-110 vertical-top">原因说明</label>
        <textarea name="PdFirmEvaluateResult[cause_description]" style="width: 825px; height: 50px;"><?= !empty($evaluateData['purchaseEvaluateData']) ? $evaluateData['purchaseEvaluateData']['cause_description'] : null ?></textarea>
    </div>
    <?php ActiveForm::end(); ?>
    <h2 class="head-second">工管意见</h2>
    <div class="mb-30">
        <label class="width-110">评鉴意见</label>
        <span><?= !empty($evaluateData['manageEvaluateData']) ? $evaluateData['manageEvaluateData']['evaluateResult'] : '<span class="red">工管还未评鉴</span>' ?></span>
    </div>
    <div class="text-center">
        <button type="button" class="button-blue-big" id="confirm">确定</button>
        <button type="button" class="button-white-big ml-20" onclick="window.location.href='<?= Url::to(['index'])?>'">取消</button>
    </div>
</div>
<script>
    $(function () {
        //ajax提交表单
        ajaxSubmitForm($("#purchase_form"));
        $("#confirm").click(function () {
            $("#purchase_form").submit();
        });

        //验证
        $("#purchase_evaluate_result").validatebox({
            required: true
        });
    })
</script>