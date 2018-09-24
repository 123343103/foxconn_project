<?php
use yii\helpers\Url;
$this->title = '修改销售员';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售员资料列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改销售员信息'];
?>
<div>

    <?= $this->render('_form', [
        'model'=>$model,
        'status' => $status,
        'category' => $category,
        'downList'=>$downList,
        'store'=>$store
    ]) ?>

</div>
