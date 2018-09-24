<?php
/**
 * User: F1677929
 * Date: 2016/11/18
 */
/* @var $this yii\web\view */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '工管评鉴意见';
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
    <div class="mb-20">
        <label class="width-110">评鉴意见</label>
        <span><?= !empty($evaluateData['purchaseEvaluateData']) ? $evaluateData['purchaseEvaluateData']['evaluateResult'] : '<span class="red">采购还未评鉴</span>' ?></span>
    </div>
    <div class="mb-20">
        <label class="width-110">原因说明</label>
        <span><?= !empty($evaluateData['purchaseEvaluateData']) ? $evaluateData['purchaseEvaluateData']['cause_description'] : null ?></span>
    </div>
    <h2 class="head-second">工管意见</h2>
    <?php ActiveForm::begin(['id'=>'manage_form']); ?>
        <div class="mb-30">
            <label class="width-110">评鉴结果</label>
            <select name="PdFirmEvaluateResult[evaluate_result]" class="width-200" id="manage_evaluate_result">
                <option value="">请选择</option>
                <?php foreach ($evaluateData['purchaseManageEvaluateResultList'] as $key => $val) { ?>
                    <option value="<?= $key ?>" <?= isset($evaluateData['manageEvaluateData'])&&$evaluateData['manageEvaluateData']['evaluate_result']==$key ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            </select>
        </div>
    <?php ActiveForm::end(); ?>
    <div class="text-center">
        <button type="button" class="button-blue-big" id="confirm">确定</button>
        <button type="button" class="button-white-big ml-20" onclick="window.location.href='<?= Url::to(['index'])?>'">取消</button>
    </div>
</div>
<script>
    $(function () {
        //ajax提交表单
        ajaxSubmitForm($("#manage_form"));
        $("#confirm").click(function () {
            $("#manage_form").submit();
        });

        //验证
        $("#manage_evaluate_result").validatebox({
            required: true
        });
    })
</script>