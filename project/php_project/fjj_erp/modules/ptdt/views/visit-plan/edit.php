<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/9/12
 * Time: 11:27
 */
use yii\helpers\Url;
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '厂商计划列表', 'url' => Url::to(['/ptdt/visit-plan/index'])];
$this->params['breadcrumbs'][] = ['label' => '修改厂商计划', 'url' => Url::to(['/ptdt/visit-plan/edit','id'=>$model['pvp_planID']])];
$this->title = '修改厂商拜访计划';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">
        修改厂商拜访计划
        <div class="float-right mr-50">
            <label class="width-100 white">计划编号</label>
            <span class="white"><?= $model['pvp_plancode']  ?></span>
        </div>
    </h1>
    <?= $this->render("_form", [
        'model'=>$model,
        'downList'=>$downList,
        'id'=>$id
    ]) ?>
</div>
