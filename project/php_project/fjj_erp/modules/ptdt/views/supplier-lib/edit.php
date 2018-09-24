<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\PdNegotiation */

$this->title = '编辑供应商';
$this->params['homeLike'] = ['label' => '供应商管理'];
$this->params['breadcrumbs'][] = ['label' => '供应商列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pd-negotiation-update">
    <?= $this->render('_form', [
        'model'=>$model,
        'materialList'=>$materialList,
        'mainList'=>$mainList,
        'persionList'=>$persionList,
        'downList'=>$downList,
    ]) ?>

</div>
