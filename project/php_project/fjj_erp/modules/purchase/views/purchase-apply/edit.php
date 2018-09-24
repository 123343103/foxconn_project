<?php


$this->title = '修改请购单';
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '请购单列表', 'url' => "index"];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="content">
   <?= $this->render('_formedit', ['model' => $model,'pdtmodel'=>$pdtmodel,
       'verify'=>$verify,'downList'=>$downList
   ]); ?>
</div>
