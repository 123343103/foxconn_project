<?php
/**
 * User: F1677929
 * Date: 2017/5/23
 */
\app\assets\JqueryUIAsset::register($this);
?>
<h1 class='head-first'>查看活动类型</h1>
<div class="mb-20">
    <span class="width-70 ml-40">编码：</span>
    <span class="width-300 vertical-center"><?=$data['acttype_code']?></span>
</div>
<div class="mb-20">
    <span class="width-70 ml-40">活动类型：</span>
    <span class="width-300 vertical-center"><?=$data['acttype_name']?></span>
</div>
<div class="mb-20">
    <span class="width-70 ml-40">活动方式：</span>
    <span class="width-300 vertical-center"><?=$data['activeWay']?></span>
</div>
<div class="mb-20">
    <span class="width-70 ml-40">描述：</span>
    <span class="width-300 vertical-center"><?=$data['acttype_description']?></span>
</div>
<div class="mb-20">
    <span class="width-70 ml-40">状态：</span>
    <span class="width-300 vertical-center"><?=$data['activeTypeStatus']?></span>
</div>
<div class="text-center">
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>

