<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '新增销售员';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售员资料列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '新增销售员'];

?>
<div>

    <?= $this->render('_form', [
        'category' => $category,
        'status' => $status,
        'downList' => $downList,
    ]) ?>
</div>
