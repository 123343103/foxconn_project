<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

$this->title = '公司详情: ' . $model['company_name'];
?>
<style>
    .panel {
        width: 620px;
        padding: 0 20px;
    }
    .fancybox-wrap{
        top:  0px !important;
        left: 0px !important;
    }
    .width-100{
        width:100px;
    }
    .width-200{
        width:200px;
    }
    .width-500{
        width: 500px;
    }
</style>
<div class="create">
    <div class="pop-head">
        <p><?= Html::encode($this->title) ?></p>
    </div>
<!--    <h1 class="head-first">-->
<!--        货币详情-->
<!--    </h1>-->
    <div class="mt-20 mb-30">
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">上级公司</label><label>：</label>
            <span class="label-width text-left width-200"><?= $comName['company_name'] ?></span>
            <label class="label-width qlabel-align width-100 ">公司代码</label><label>：</label>
            <span class="label-width text-left width-200"><?= $model['company_code']?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">公司名称(法人)</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['company_name']?></span>
            <label class="label-width qlabel-align width-100">简称</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_shortname']?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">成立日期</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_createdate']?></span>
            <label class="label-width qlabel-align width-100">总部地点</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_mainaddress']?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">法定代表人</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_cman']?></span>
            <label class="label-width qlabel-align width-100">经营状态</label><label>：</label>
            <span class="label-width text-left width-200"><?=$comStatus?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">负责人</label><label>：</label>
            <span class="label-width text-left width-200"><?=$staffInfo['staff_code']?>&nbsp&nbsp<?=$staffInfo['staff_name']?></span>
            <label class="label-width qlabel-align width-100">联系方式</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_tel']?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">负组织机构代码</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_orgcode']?></span>
            <label class="label-width qlabel-align width-100">注册号</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_regcode']?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">公司性质</label><label>：</label>
            <span class="label-width text-left width-200"><?=$comType['bsp_svalue']?></span>
            <label class="label-width qlabel-align width-100">公司类型</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_type']?></span>
        </div><div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">营业额</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_count']?></span>
            <label class="label-width qlabel-align width-100">注册资本</label><label>：</label>
            <span class="label-width text-left width-200"><?=$model['comp_regcount']?></span>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100">企业地址</label><label>：</label>
            <span class="label-width text-left width-500 no-border vertical-center"><?= $comAddress?></span>
        </div>
    </div>
    <div class="space-40 "></div>
    <div class="text-center">
        <input type="hidden" id="compId" value="<?=$model['company_id']?>">
        <button class="button-blue-big" type="submit" id="submit" >修改</button>&nbsp;
        <button class="button-white-big ml-20 close">返回</button>
    </div>


</div>
<script>
    $(function () {
        $(".close").click(function(){
            parent.$.fancybox.close();
        });
        $("#submit").click(function () {
//            window.location.href="<?//=Url::to(['update'])?>//";
            var id = $('#compId').val();
            $.fancybox.open({
                href: '<?= Url::to(['update'])?>?id=' + id,
                type: 'iframe',
                padding : [],
                fitToView	: false,
                width		: 700,
                height		: 600,
                autoSize	: false,
                closeClick	: false,
                openEffect	: 'none',
                closeEffect	: 'none'
            });
        });
    });
</script>