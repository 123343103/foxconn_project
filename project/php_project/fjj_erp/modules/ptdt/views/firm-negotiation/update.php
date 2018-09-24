<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\PdNegotiation */

$this->title = '修改谈判';
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判履历列表', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '修改谈判', 'url' => ""];
?>
<div class="pd-negotiation-update">
    <?= $this->render('_form', [
            'negotiation'=>$data['negotiation'],
            'child'=>$data['child'],
            'accompany'=>$data['accompany'],
            'analysis'=>$data['analysis'],
            'reception'=>$data['reception'],
            'authorize'=>$data['authorize'],
            'downList'=>$data['downList'],
            'productInfo'=>$data['productInfo'] ,
            'firmInfo'=>$firmInfo,
            'plan'=>$plan
    ]) ?>
</div>
