<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */

$this->title = '新增交易方式';
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '平台交易相关设置', 'url' => \yii\helpers\Url::to(['/system/transaction/index'])];
$this->params['breadcrumbs'][] = ['label'=>'交易方式列表', 'url' => \yii\helpers\Url::to(['/system/transaction/transaction-index'])];
$this->params['breadcrumbs'][] = ['label'=>'新增交易方式'];
?>
<div class="content">
    <h1 class="head-first">
        新增交易方式
    </h1>
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
//        'staffName'=>$staffName,
//        'createAt'=>$createAt
    ]) ?>

</div>
