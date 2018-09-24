<?php

$this->title = '修改客戶信息';
$this->params['homeLike'] = ['label' => '客户关系系统'];
$this->params['breadcrumbs'][] = ['label' => '招商会员开发列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '修改招商客户'];

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
