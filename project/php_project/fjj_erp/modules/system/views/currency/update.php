<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */

$this->title = '修改货币类别';
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '平台交易相关设置', 'url' => \yii\helpers\Url::to(['/system/transaction/index'])];
$this->params['breadcrumbs'][] = ['label'=>'货币类别列表'];
$this->params['breadcrumbs'][] = ['label'=>'修改货币类别'];
?>
<div class="content">
    <h1 class="head-first">
        修改货币类别
    </h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
