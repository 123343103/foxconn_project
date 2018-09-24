<?php
/**
 * User: F1677929
 * Date: 2016/11/18
 */
/* @var $this yii\web\view */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '查看厂商评鉴';
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '厂商评鉴列表', 'url' => Url::to(['firm-evaluate/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first"><?= Html::encode($this->title) ?></h1>
    <div class="mb-20">
        <label class="width-110">评鉴类型</label>
        <span><?= !empty($evaluateApplyData) ? $evaluateApplyData['apply_type'] : null ?></span>
        <a class="ml-10" id="load_apply">评鉴申请表</a>
    </div>
    <div style="display: none;" id="apply_table">
        <?= $this->render('load-apply', ['evaluateApplyData' => $evaluateApplyData]) ?>
    </div>
    <div class="mb-20">
        <label class="width-110">相关资料</label>
    </div>
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
    <div class="mb-30">
        <label class="width-110">评鉴意见</label>
        <span><?= !empty($evaluateData['manageEvaluateData']) ? $evaluateData['manageEvaluateData']['evaluateResult'] : '<span class="red">工管还未评鉴</span>' ?></span>
    </div>
    <div class="text-center">
        <button type="button" class="button-white-big" onclick="window.location.href='<?= Url::to(['index'])?>'">返回</button>
    </div>
</div>
<script>
    $(function () {
        //评鉴申请表
        $("#load_apply").fancybox({
            padding     : [],
            width		: 600,
            height		: 400,
            autoSize	: false,
            href        : '#apply_table'
        });
    })
</script>