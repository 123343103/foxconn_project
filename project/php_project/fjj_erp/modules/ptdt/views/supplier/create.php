<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\PdNegotiation */

$this->title = '供应商申请';
$this->params['homeLike'] = ['label' => '供应商管理'];
$this->params['breadcrumbs'][] = ['label' => '供应商申请'];
//$this->params['breadcrumbs'][] = ['label' => '供应商申请'];

?>
<div class="pd-negotiation-update">
    <?= $this->render('_form', [
        'downList'=>$downList,
    ]) ?>

</div>