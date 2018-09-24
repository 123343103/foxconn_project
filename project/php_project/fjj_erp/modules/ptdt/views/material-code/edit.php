<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/11/18
 * Time: 上午 09:28
 */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'料号信息管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '新增料号申请', 'url' => Url::to(['/ptdt/material-code/index'])];
$this->params['breadcrumbs'][] = ['label' => '修改料号信息', 'url' => Url::to(['/ptdt/material-code/edit','id'=>$planModel->m_id])];
?>
<?= $this->render("cre", [
    'planModel'=>$planModel,
    'brand' =>$brand,
    'res' => $res
]) ?>

