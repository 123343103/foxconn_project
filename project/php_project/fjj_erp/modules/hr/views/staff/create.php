<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\Staff */

$this->params['homeLike'] = ['label'=>'人事信息','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'员工列表'];
$this->title = '新增信息';

?>
<div class="content">

    <h1 class="head-first">
        新增信息
    </h1>

    <?= $this->render('_form', [
        'downList' => $downList,
    ]) ?>

</div>
