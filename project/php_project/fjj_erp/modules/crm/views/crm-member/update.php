<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmMember */


$this->title = '修改会员信息';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改会员信息'];
?>
<div class="content">

    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">会员编号:<?= $model['cust_filernumber'] ?></span>
    </h1>
    <?= $this->render('_form', [
        'model'=>$model,
        'downList'=>$downList,
        'districtAll'=>$districtAll,
    ]) ?>

</div>
