<?php

use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmMember */
$this->title = '新增回访记录';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员回访记录','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '新增回访记录'];

?>
<div>
    <?php if(!empty($id)){ ?>
        <?= $this->render('_form',[
            'downList'=>$downList,
            'member'=>$member,
            'districtAll'=>$districtAll,
            'visitPerson'=>$visitPerson,
            'id'=>$id
        ]) ?>
    <?php }else{ ?>
        <?= $this->render('_form',[
            'downList'=>$downList,
            'visitPerson'=>$visitPerson,
        ]) ?>
    <?php } ?>

</div>
