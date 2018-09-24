<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * User: F1676624
 * Date: 2017/6/19
 */
$this->title = '新增请购单';

$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '请购单列表', 'url' => "index"];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content">
    <?= $this->render('_form', ['downList' => $downList,'u'=>$u,'fr'=>$fr
    ]); ?>
</div>