<?php
/**
 *  F3858995
 *  2016/10/13
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label'=>'系统平台设置'];
$this->params['breadcrumbs'][] =  ['label'=>'用戶组（角色）' ,'url' => Url::to(['role-index'])];
$this->params['breadcrumbs'][] = ['label'=>'角色详情'];
?>
<div class="content">
    <?= $this->render("_form",['model'=>$model,'authority'=>$authority,"permissins"=>$permissions])    ?>
</div>
