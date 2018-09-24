<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\ptdt\models\CrmCustomerInfo */
$this->title = '修改客戶信息';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客户列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改客戶信息'];
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
            <span class="head-code">客户编号：<?= $model['cust_filernumber'] ?></span>
    </h1>
    <?= $this->render('_form',[
        "crmcertf"=>$crmcertf,
        'model' => $model,
        'downList'=>$downList,
        'district'=>$district,
//        'personinch'=>$personinch,
        'districtAll2'=>$districtAll2,
        'districtAll3'=>$districtAll3,
        'districtAll4' => $districtAll4,
        'districtAll5' => $districtAll5,
        'isSuper'  => $isSuper
    ]) ?>
</div>
