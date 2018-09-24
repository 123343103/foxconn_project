<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
?>
<style>
    .table-head li {
        font-size:14px;
        border:1px solid grey;
        padding:2px 5px;
        cursor:pointer;
        float:left;
        margin-right:5px;
    }
    .table-head li:first-child {
        background-color:#d2d2d2;
    }
</style>
<div class="table-head">
    <ul style="display:inline-block;">
        <li data-flag="a">已核价资料</li>
        <li data-flag="b">审核中核价资料</li>
        <li data-flag="c">未核价资料</li>
        <li data-flag="d">需重核核价资料</li>
    </ul>
    <div class="float-right">
        <?php if(Menu::isAction('/ptdt/pno-bind-spp/add')){?>
            <a href="<?=Url::to(['add'])?>">
                <div style="height: 23px;float: left;">
                    <p class="add-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/ptdt/pno-bind-spp/edit')){?>
            <a id="edit_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="update-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/ptdt/pno-bind-spp/check')){?>
            <a id="check_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="submit-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <?php if(Menu::isAction('/ptdt/pno-bind-spp/stop')){?>
            <a id="stop_btn" style="display:none;">
                <div style="height: 23px;float: left;">
                    <p class="setbcg9" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;终止</p>
                </div>
            </a>
            <p style="float: left;display:none;">&nbsp;|&nbsp;</p>
        <?php }?>
        <a href="<?=Url::to(['/index/index'])?>">
            <div style="height: 23px;width: 55px;float: left">
                <p class="return-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function(){
        //修改
        $("#edit_btn").click(function(){
            var row=$("#datagrid1").datagrid("getSelected");
            location.href="<?=Url::to(['edit'])?>?id="+row.prt_spp_pkid;
        });

        //数据导入
        $("#import_btn").click(function(){
            $.fancybox({
                type:"iframe",
                href:"<?=Url::to(['import'])?>",
                padding:0,
                autoSize:false,
                width:500,
                height:200
            });
        });

        //审核状态切换
        $(".table-head ul li").click(function(){
            $(".table-head ul li").css("background-color","white");
            $(this).css("background-color","#d2d2d2");
//            $("#query_btn").click();
            $("#datagrid1").datagrid('load',{
                "flag":$(".table-head ul li[style='background-color: rgb(210, 210, 210);']").data("flag")
            });
        });
    })
</script>