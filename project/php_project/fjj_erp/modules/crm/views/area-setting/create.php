<?php

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = '新增营销区域';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '营销区域列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div>

    <?= $this->render('_form',[
        'status' => $Status,
        'districtAll' => $districtAll,
    ]) ?>
</div>
