<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\PdNegotiation */

$this->title = '新增谈判';
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判履历列表', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '新增谈判', 'url' => ""];
?>
<div class="pd-negotiation-create">
    <?= $this->render('_form', [
        'downList'=>$downList,
        'firmInfo'=>$firmInfo,
        'plan'=>$plan
    ]) ?>
</div>
