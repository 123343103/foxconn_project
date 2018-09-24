<?php
$this->title = '修改盘点单信息';
$this->params['homeLike'] = ['label' => '仓库物流管理'];
$this->params['breadcrumbs'][] = ['label' => '盘点单列表', 'url' => "index"];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content">
    <?= $this->render('_formedit', ['model' => $model,'pdtmodel'=>$pdtmodel,
        'downList'=>$downList
    ]); ?>
</div>
