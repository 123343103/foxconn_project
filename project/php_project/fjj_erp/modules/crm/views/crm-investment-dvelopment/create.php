<?php

$this->title = '新增客户';
$this->params['homeLike'] = ['label' => '客户关系系统'];
$this->params['breadcrumbs'][] = ['label' => '招商会员开发列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '新增客户'];

?>
<div>
    <?= $this->render('_form',[
        'model'=>$model,
        'downList'=>$downList,
    ]) ?>
</div>
