<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 下午 02:56
 */
use yii\helpers\Html;
$this->title = '修改岗位信息: ' . $model->title_name;
$this->params['homeLike'] = ['label'=>'人事资料','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'岗位信息'];
$this->params['breadcrumbs'][] =['label'=>'修改岗位信息'];
?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
        </h1>
    <?=$this->render('_form',[
        'model'=>$model,
    ])?>
</div>
