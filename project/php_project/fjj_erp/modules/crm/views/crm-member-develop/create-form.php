<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/30
 * Time: 9:48
 */
use yii\helpers\Url;
$this->title = '新增客户';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员开发任务列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '新增客户'];

?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <?= $this->render('_cust',[
        'downList'=>$downList,
        'type'=>$type
    ]) ?>

</div>