<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/24
 * Time: 上午 10:51
 */
$this->title = '编辑业务费用';
//$this->params['breadcrumbs'][] = ['label' => 'Bs Transactions', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <h1 class="head-first">
        编辑业务费用
    </h1>
    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'saleCostTypeValue' => $saleCostTypeValue,
        'model' => $model,
        'stclStatus'=>$stclStatus,
//        'staffName'=>$staffName,
//        'createAt'=>$createAt
    ]) ?>

</div>