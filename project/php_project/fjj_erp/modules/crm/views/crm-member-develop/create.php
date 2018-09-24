<?php

use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmMember */
$this->title = '新增拜访';
$this->params['homeLike'] = ['label' => '客户关系系统'];
if($ctype==3){
    $this->params['breadcrumbs'][] = ['label' => '招商会员开发', 'url' => ['/crm/crm-investment-dvelopment/index']];
}elseif($ctype==4){
    $this->params['breadcrumbs'][] = ['label' => '潜在客户列表列表', 'url' => ['/crm/crm-potential-customer/index']];
}elseif($ctype==6){
    $this->params['breadcrumbs'][] = ['label' => '招商会员列表', 'url' => ['/crm/crm-investment-customer/list']];
}else{
    $this->params['breadcrumbs'][] = ['label' => '会员任务开发列表','url' => Url::to(['index'])];
}
$this->params['breadcrumbs'][] = ['label' => '新增拜访记录'];

?>
<div>
    <div class="content">
        <h1 class="head-first">
            <?= $this->title; ?>
        </h1>
    <?php if(!empty($id)){ ?>
        <?= $this->render('_form',[
            'downList'=>$downList,
            'member'=>$member,
            'districtAll'=>$districtAll,
            'visitPerson'=>$visitPerson,
            'id'=>$id,
            'ctype'=>$ctype
        ]) ?>
    <?php }else{ ?>
        <?= $this->render('_form',[
            'downList'=>$downList,
            'visitPerson'=>$visitPerson,
            'id'=>$id,
            'ctype'=>$ctype
        ]) ?>
    <?php } ?>

</div>
