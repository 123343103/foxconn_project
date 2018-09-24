<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/4
 * Time: 16:00
 */
use yii\helpers\Url;


$this->title = '修改报价单';
$this->params['homeLike'] = ['label' => '销售关联'];
$this->params['breadcrumbs'][] = ['label' => '报价单列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content">
    <h1 class="head-first" xmlns="http://www.w3.org/1999/html">
        <?= $this->title ?>
    </h1>
    <?= $this->render('_form',[
        'data'=>$data,
        'dt'=>$dt,
        'credits' => $credits,
        'downList' => $downList,
        'seller' => $seller,
        'id' => $id,
    ]) ?>

</div>