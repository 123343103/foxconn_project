<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/7
 * Time: 下午 01:55
 */
use yii\helpers\Url;
$this->title = '新增客户';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客户列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '新增客户'];
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <?= $this->render('_form',[
        'downList'=>$downList,
        'district'=>$district,
        'isSuper'  => $isSuper,
        'u'=>$u
    ]) ?>
</div>
