<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */

$this->title = '新增付款方式';
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '平台交易相关设置', 'url' => \yii\helpers\Url::to(['/system/transaction/index'])];
$this->params['breadcrumbs'][] = ['label'=>'付款方式列表'];
$this->params['breadcrumbs'][] = ['label'=>'新增付款方式'];
?>
<div class="content">
    <h1 class="head-first">
        新增付款方式
    </h1>
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
