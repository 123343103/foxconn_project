<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\OrdRefund */

$this->title = '新增退款单';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '退款列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">

    <h1 class="head-first" xmlns="http://www.w3.org/1999/html">
        <?= $this->title ?>
    </h1>

    <?= $this->render('_form', [
        'data' => $data,
        'dt' => $dt,
        'downList' => $downList,
        'id' => $id
    ]) ?>

</div>
