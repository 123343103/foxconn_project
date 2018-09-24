<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * User: F1676624
 * Date: 2017/6/19
 */
$this->title = '新增客户需求单';

$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '客户需求单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content">
    <?= $this->render('_form', ['downList' => $downList, 'seller' => $seller
    ]); ?>
</div>