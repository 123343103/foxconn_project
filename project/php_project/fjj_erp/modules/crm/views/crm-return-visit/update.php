<?php
use yii\helpers\Url;
/* @var $model app\modules\crm\models\CrmMember */
$this->title = '修改回访记录';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员回访记录','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改回访记录'];

?>
<div>
    <?= $this->render('_form',[
        'downList' => $downList,
        'member' => $member,
        'districtAll' => $districtAll,
        'childCount' => $childCount,
        'child' => $child,
        'dateTime' => $dateTime,
        'id' => $id,
        'childId' => $childId,
        'sih'=>$sih,
    ]) ?>
</div>
