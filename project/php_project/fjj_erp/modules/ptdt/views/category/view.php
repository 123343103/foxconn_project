<?php
/**
 * User: F1676624
 * Date: 2016/10/24
 */

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '料号管理'];
$this->params['breadcrumbs'][] = ['label' => '料号大分类维护'];
?>
<div class="content">
    <div class="mb-30">
        <h2 class="head-second">
            <p><?= $title ?></p>
        </h2>
        <div class="mb-10 ml-30">
            <label class="width-200">ID</label>
            <span class="width-200 "><?=   $model['category_id'] ?></span>
            <label class="width-100 ml-100">级别</label>
            <span class="width-200 "><?= $level_all[$model['category_level']] ?></span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-100 ml-100">类别名</label>
            <span class="width-200 "><?=   $model['category_name'] ?></span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-100 ml-100">是否有效</label>
            <span class="width-200 "> <?=  $model['isvalid'] == 1 ? '有效' : '无效'; ?> </span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-200">排序</label>
            <span class="width-200 "><?=  $model['orderby'] ?></span>
            <label class="width-100 ml-100">中心</label>
            <span class="width-200 "><?=  $model['center'] ?></span>
        </div>
        <div class="mb-20 ml-30">
            <label class="width-200">最小利率</label>
            <span class="width-200 "><?=  $model['min_margin'] ?></span>
        </div>

    <div class="space-30"></div>
    <div class="width-200 margin-auto">
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <div class="space-30"></div>
</div>