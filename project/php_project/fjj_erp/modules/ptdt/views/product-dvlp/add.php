<?php
/**
 * 新增开发需求页面
 * F3858995
 * Date: 2016/9/18
 */

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发计划'];
$this->params['breadcrumbs'][] = ['label' => '新增商品开发需求'];
$this->title = '新增商品开发需求';/*BUG修正 增加title*/
?>

<div class="content">
    <h1 class="head-first">
        新增商品开发需求
    </h1>
    <?= $this->render("_form", [
        'downList'=>$downList,
        'reviews'=>$reviews
    ]) ?>
</div>
