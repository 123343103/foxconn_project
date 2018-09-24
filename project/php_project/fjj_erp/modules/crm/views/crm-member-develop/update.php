<?php
use yii\helpers\Url;
/* @var $model app\modules\crm\models\CrmMember */
$this->title = '修改拜访信息';
$this->params['homeLike'] = ['label' => '客户关系系统'];
if($ctype==3){
    $this->params['breadcrumbs'][] = ['label' => '招商会员开发', 'url' => ['/crm/crm-investment-dvelopment/index']];
}elseif($ctype==4){
    $this->params['breadcrumbs'][] = ['label' => '潜在客户列表', 'url' => ['/crm/crm-potential-customer/list']];
}elseif($ctype==6){
    $this->params['breadcrumbs'][] = ['label' => '招商会员列表', 'url' => ['/crm/crm-investment-customer/list']];
}else{
    $this->params['breadcrumbs'][] = ['label' => '拜访记录','url' => Url::to(['index'])];
}
$this->params['breadcrumbs'][] = ['label' => '修改拜访记录'];

?>
<div>
    <div class="content">
        <h1 class="head-first">
            <?= $this->title; ?>
            <span class="head-code">档案编号:<?= $child['sil_code'] ?></span>
        </h1>
    <?= $this->render('_form',[
        'downList' => $downList,
        'member' => $member,
        'districtAll' => $districtAll,
        'childCount' => $childCount,
        'child' => $child,
        'dateTime' => $dateTime,
        'ctype'=>$ctype,
        'id' => $id,
        'childId' => $childId
    ]) ?>
</div>
