<?php
/**
 * User: F1677929
 * Date: 2016/11/11
 */
/* @var $this yii\web\view */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '新增厂商评鉴';
$this->params['homeLike'] = ['label'=>'供应商管理','url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '厂商评鉴列表', 'url' => Url::to(['firm-evaluate/index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first"><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'synthesisLevel' => $addData['synthesisLevel'],
        'firmEvaluateResultList' => $addData['firmEvaluateResultList'],
        'evaluatePersonInfo' => $addData['evaluatePersonInfo'],
        'firmInfo' => $addData['firmInfo'],
    ]) ?>
</div>
