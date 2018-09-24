<?php
/**
 * User: G0007903
 * Date: 2017/12/13
 */
$this->title = '新增盘点单';

$this->params['homeLike'] = ['label' => '仓库物流管理'];
$this->params['breadcrumbs'][] = ['label' => '盘点单列表', 'url' => "index"];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content">
    <?= $this->render('_form', ['downList' => $downList,'id'=>$id
    ]); ?>
</div>