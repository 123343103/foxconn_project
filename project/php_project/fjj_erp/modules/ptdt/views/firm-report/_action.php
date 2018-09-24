<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/9/22
 * Time: 19:49
 */

use yii\helpers\Url;
use \yii\bootstrap\Html;
use \app\classes\Menu;
?>
<style>
    #m-data{
        width:95px !important;
    }
</style>
<div class="table-head">
    <p class="head">厂商呈报信息主表</p>
    <div class="float-right">

        <?= Menu::isAction('/ptdt/firm-report/add')?
            "<a id='add' href='". Url::to(['add']) ."'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>"
            :'' ?>
        <?= Menu::isAction('/ptdt/firm-report/update')?
            "<a id='edit' class='display-none overflow-auto'>
                <p class=\"float-left\">&nbsp;|&nbsp;</p>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>修改</p>
                </div>
            </a>"
            :'' ?>
        <?= Menu::isAction('/ptdt/firm-report/view')?
            "<a id='view' class='display-none'>
        <p class=\"float-left\">&nbsp;|&nbsp;</p>

                <div class='table-nav'>
                    <p class='details-item-bgc float-left'></p>
                    <p class='nav-font'>详情</p>
                </div>
            </a>"
            :'' ?>
        <?= Menu::isAction('/ptdt/firm-report/delete')?
            "<a id='delete' class='display-none'>
        <p class=\"float-left\">&nbsp;|&nbsp;</p>

                <div class='table-nav'>
                    <p class='delete-item-bgc float-left'></p>
                    <p class='nav-font'>删除</p>
                </div>
            </a>"
            :'' ?>
        <?= Menu::isAction('/ptdt/firm-report/check')?
            "<a id='check' class='display-none'>
        <p class=\"float-left\">&nbsp;|&nbsp;</p>

                <div class='table-nav'>
                    <p class='submit-item-bgc float-left'></p>
                    <p class='nav-font'>送审</p>
                </div>
            </a>"
            :'' ?>
        <?= Menu::isAction('/ptdt/firm-report/analysis')?
            "<a id='analysis' class='display-none'>
        <p class=\"float-left\">&nbsp;|&nbsp;</p>

                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>呈报分析表</p>
                </div>
            </a>"
            :'' ?>
        <a id='m-send' class='menu-one display-none'>
            <p class="float-left">&nbsp;|&nbsp;</p>
            <div class='table-nav'>
                <p class='add-item-bgc float-left'></p>
                <p class='nav-font'>其他业务申请</p>
            </div>
        </a>
        <a href="<?= Url::to(['/index/index']) ?>" style="float:right;margin-top: 3px;">
            <p class="float-left">&nbsp;|&nbsp;</p>
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>
<div id="m-data">
    <?= Menu::isAction('/ptdt/firm-evaluate-apply/add')?'<div class="display-none status_50"><a onclick="criticism()" class="menu-span"><span>评鉴申请</span></a></div>':'' ?>
    <div class="display-none status_50"><a class="menu-span"><span>认证申请</span></a></div>
    <div><a href="" class="menu-span"><span>料号申请</span></a></div>
</div>
<script>
    $(function(){
        $('#m-send').menubutton({
            menu: '#m-data',
            hasDownArrow:false,
        });
        $('.menu-one').removeClass("l-btn l-btn-small l-btn-plain");
        $('.menu-one').find("span").removeClass("l-btn-left l-btn-text");
    })

    /**
     * 评鉴申请
     */
    function criticism(){
        var a = $("#data").datagrid('getSelected');
        if (a == null) {
            layer.alert("请点击选择一条厂商信息!", {icon: 2, time: 5000});
        }else {
            var id = $("#data").datagrid("getSelected")['firm_id'];
            window.location.href = "<?=Url::to(['/ptdt/firm-evaluate-apply/add'])?>?id=" + id;
        }
    }
</script>

