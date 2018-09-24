<?php
use yii\bootstrap\Html;

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '发布新商品','url'=>'index'];
$this->params['breadcrumbs'][] = ['label' => '填写商品信息'];
$this->title="填写商品信息";
?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?= $this->render("_form_1", [
        'model' =>$model,
        'options' =>$options
    ]) ?>

</div>
