<?php
use \yii\helpers\Url;
use \yii\helpers\Html;
?>
<style>
    .width-100{
        width: 100px;
    }
    .width-140{
        width: 140px;
    }
    .width-350{
        width: 350px;
        line-height:25px;
    }
    .mb-15{
        margin-bottom:15px;
    }
</style>
<h1 class="head-first">销售角色详情</h1>
<div class="mb-10">
    <label class="label-width qlabel-align width-100">销售角色编码<label>：</label></label>
    <span class="width-140"><?= $model["sarole_code"] ?></span>
    <label class="width-100">销售角色名称<label>：</label></label>
    <span class="width-140"><?= $model["sarole_sname"] ?></span>
</div>
<div class="mb-10">
    <label class="label-width qlabel-align width-100">销售人力类型<label>：</label></label>
    <span class="width-140"><?= $model["roleType"] ?></span>
    <label class="width-100">角色提成系数<label>：</label></label>
    <span class="width-140"><?= $model["sarole_qty"] ?>%</span>
</div>
<div class="mb-10">
    <label class="label-width qlabel-align width-100">是否销售主管<label>：</label></label>
    <span class="width-140"><?= $model["vdef1"]=='1'?'是':'否'; ?></span>
    <label class="width-100">状态<label>：</label></label>
    <span class="width-140"><?= $model["statuas"] ?></span>
</div>
<div class="mb-10">
    <label class="label-width qlabel-align  width-100 vertical-top">描述<label>：</label></label>
    <span class="width-350"><?= $model["sarole_desription"] ?></span></div>
<div class="mb-10">
    <label class="label-width qlabel-align width-100 vertical-top">备注<label>：</label></label>
    <span class="width-350"><?= $model["sarole_remark"] ?></span>
</div>
<div class="mb-15 text-center" style="margin-top: 50px">
    <button  onclick="close_select()" class="button-white-big ml-20" type="button">返回</button>
</div>

