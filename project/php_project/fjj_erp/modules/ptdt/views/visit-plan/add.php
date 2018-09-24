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
$this->params['breadcrumbs'][] = ['label' => '新增厂商计划', 'url' => Url::to(['/ptdt/visit-plan/add'])];
$this->title = '新增厂商拜访计划';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">
        新增厂商拜访计划
    </h1>
    <?php if($id==''){ ?>
    <?= $this->render("_form", [
            'downList'=>$downList,
        'id'=>$id
    ]) ?>
    <?php }else{ ?>
        <?= $this->render("_form", [
            'downList'=>$downList,
            'firmMessage'=>$firmMessage,
            'id'=>$id,
        ]) ?>
    <?php } ?>
</div>



