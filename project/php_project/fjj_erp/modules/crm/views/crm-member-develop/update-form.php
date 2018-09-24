<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/30
 * Time: 9:48
 */
use yii\helpers\Url;
$this->title = '修改客户信息';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员开发任务列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改客户信息'];

?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">档案编号:<?= $model['cust_filernumber'] ?></span>
    </h1>
    <?= $this->render('_cust',[
        'member'=>$member,
        'model' => $model,
        'downList' => $downList,
        'districtAll' => $districtAll,
        'type'=>$type,
        'ctype'=>$ctype
    ]) ?>

</div>