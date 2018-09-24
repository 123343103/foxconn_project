<?php

$this->title = '修改客户信息';
$this->params['homeLike'] = ['label' => '客户关系系统'];
$this->params['breadcrumbs'][] = ['label' => '招商会员列表', 'url' => ['list']];
$this->params['breadcrumbs'][] = ['label' => '修改客户信息'];

?>
<div>
    <?= $this->render('_form',[
        'model'=>$model,
        'downList'=>$downList,
        'district'=>$district,
        'districtAll2'=>$districtAll2,
        'districtAll3'=>$districtAll3,
    ]) ?>
</div>
