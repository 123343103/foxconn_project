<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\Staff */
$this->title = '更新员工信息: ' . $model['staff_name'];
$this->params['homeLike'] = ['label'=>'人事信息','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'员工信息'];
//$this->title = '员工信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>
    <?= $this->render('_form', [
        'model' => $model,
        'downList'=>$downList
    ]) ?>

</div>
