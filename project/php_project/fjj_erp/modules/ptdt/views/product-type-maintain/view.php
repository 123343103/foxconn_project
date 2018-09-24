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
            <span class="width-200 "><?=   $model['type_id'] ?></span>
            <label class="width-100 ml-100">级别</label>
            <span class="width-200 "><?=   $level_all[$model['type_level']] ?></span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-200">编码</label>
            <span class="width-200 "><?=   $model['type_no'] ?></span>
            <label class="width-100 ml-100">类别名</label>
            <span class="width-200 "><?=   $model['type_name'] ?></span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-200">状态</label>
            <span class="width-200 "><?=  $status[$model['status']] ?></span>
            <label class="width-100 ml-100">设备专区</label>
            <span class="width-200 "> <?=  $model['is_special'] == 1 ? '属于' : '不属于'; ?> </span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-200">关键词</label>
            <span class="width-200 "><?=  $model['type_keyword'] ?></span>
            <label class="width-100 ml-100">类别标题</label>
            <span class="width-200 "><?=  $model['type_title'] ?></span>
        </div>
        <div class="mb-20 ml-30">
            <label class="width-200">图片url</label>
            <span class="width-200 "><?=  $model['type_picture'] ?></span>
            <label class="width-100 ml-100">类别描述</label>
            <span class="width-200 "><?=  $model['type_description'] ?></span>
        </div>
        <h2 class="head-second">
            <p>创建信息</p>
        </h2>
        <div class="mb-10 ml-30">
            <label class="width-200 ">创建人</label>
            <span class="width-200 "><?= $model['createBy']['name'] ?></span>
            <label class="width-100 ml-100">创建日期</label>
            <span class="width-200 "><?=  $model['create_at'] ?></span>
        </div>
        <div class="mb-10 ml-30">
            <label class="width-200">更新人</label>
            <span class="width-200 "><?= $model['updateBy']['name'] ?></span>
            <label class="width-100 ml-100">更新时间</label>
            <span class="width-200 "><?=  $model['update_at'] ?></span>

        </div>
    </div>
    <div class="space-30"></div>
    <div class="width-200 margin-auto">
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <div class="space-30"></div>
</div>