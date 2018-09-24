<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 下午 02:55
 */
use yii\widgets\DetailView;
$this->params['homeLike'] = ['label' => '人事资料'];
$this->params['breadcrumbs'][] = ['label' => '岗位信息'];
$this->params['breadcrumbs'][] = ['label' => '岗位描述'];
$this->title = '岗位信息详情'.$model[title_name];
?>
<div class="content">
    <h1 class="head-first">
        岗位信息
    </h1>
    <div class="mb-30">
        <div class="mb-10">
            <label class="width-110">岗位名称</label>
            <span class="width-200"><?= $model['title_name']?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-110">岗位编号</label>
            <span class="width-200"><?= $model['title_code']?></span>

        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-110">岗位描述</label>
            <span class="width-200"><?= $model['title_description']?></span>
        </div>
        <div class="space-40 "></div>
        <div class="mb-10">
            <button type="button" class="button-blue ml-350 float-right"  onClick="javascript :history.back(-1);">
                返回
            </button>
        </div>
    </div>
</div>