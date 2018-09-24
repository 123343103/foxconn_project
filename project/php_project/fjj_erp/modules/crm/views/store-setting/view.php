<?php
use yii\helpers\Url;
use \yii\helpers\Html;
$this->title = '销售点详情';

$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售点维护列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '销售点详情'];
?>
<style>
    .width-75{
        width: 90px;
    }
    .width-250{
        width:250px;
    }
    .width-200{
        width:200px;
    }
</style>
<div class="content">
    <h1 class="head-first">销售点详情</h1>
    <div class="border-bottom mb-10  pb-10">
        <?= Html::button('修改', ['class' => 'button-mody', 'onclick' => 'window.location.href=\'' . Url::to(["update","id"=>$model['sts_id']]) . '\'']) ?>
        <?= Html::button('切换列表', ['class' => 'button-change', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-75 ml-20">销售点代码<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["sts_code"] ?></label>
        <label class="label-width qlabel-align width-250">销售点名称<label>：</label></label>
        <label class="label-width text-left  width-200"><?= $model["sts_sname"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-75 ml-20">营销区域<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["saleArea"]["csarea_name"] ? $model["saleArea"]["csarea_name"] : "" ?></label>
        <label class="label-width qlabel-align width-250">KPI<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["kpi"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-75 ml-20">省长<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["sz"]["staff_name"] ? $model["sz"]["staff_name"] : null ?></label>
        <label class="label-width qlabel-align width-250">店长<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["dz"]["staff_name"] ? $model["dz"]["staff_name"] : $model["dz"]["staff_name"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-75 ml-20">店员数量<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["sts_count"] ?></label>
        <label class="label-width qlabel-align width-250">店员数量上限<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["sts_count_limit"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-75 ml-20">状态<label>：</label></label>
        <label class="label-width text-left width-200"><?= $model["status"] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-75 ml-20">详细地址<label>：</label></label>
        <label class="label-width text-left text-top" style="width:650px;line-height: 17px"><?= $address ?> <?= $model["sts_address"] ?></label>
    </div>
</div>
