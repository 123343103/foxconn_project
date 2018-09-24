<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/2/24
 * Time: 下午 04:31
 */
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\common\models\BsTransaction */

$this->title = '销售管理系统';
$this->params['homeLike'] = ['label' => '基础数据设置'];
$this->params['breadcrumbs'][] = ['label' => '业务费用分类'];
$this->params['breadcrumbs'][] = ['label' => '业务费用详情'];
?>
<div class="content">
    <h1 class="head-first">
        业务费用详情
    </h1>
    <div class="mb-30">
        <div class="mb-10">
            <label class="width-100">费用代码</label>
            <span class="width-300"><?= $model->stcl_code ?></span>
            <label class="width-100 ">费用名称</label>
            <span class="width-300"><?= $model->stcl_sname?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">费用类型</label>
            <span class="width-300"><?=$typeName?></span>
            <label class="width-100">状态</label>
            <span class="width-300"><?=$stclStatus?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">描述</label>
            <span class="width-500 "><?= $model->stcl_description?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">备注</label>
            <span class="width-500 "><?= $model->stcl_remark?></span>
        </div>
    </div>
    <div class="space-40 "></div>
    <button type="button" class="button-white-big ml-320" id="submit">
        返回
    </button>
</div>
<script>
    $(function () {
        $("#submit").click(function () {
            window.location.href="<?=Url::to(['index'])?>";
        });
    });
</script>