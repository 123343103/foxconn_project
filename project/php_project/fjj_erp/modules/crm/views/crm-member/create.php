<?php

use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmMember */

$this->title = '新增会员资料';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '新增会员'];

?>
<div class="content">
        <h1 class="head-first">
            <?= $this->title; ?>
        </h1>
    <?= $this->render('_form',[
        'downList'=>$downList,
    ]) ?>

</div>
