<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/7
 * Time: 下午 01:59
 */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<h2 class="head-first">选择公司</h2>
<div style="margin-left:20px;margin-right:20px;">
    <div class="mt-20 mb-20">
        <input id="keywords" type="text" placeholder="请输入公司名称">
        <button class="button-blue" id="search">搜索</button>
    </div>
    <div id="data" style="width:600px;"></div>
    <div class="mt-20 text-right">
        <button class="button button-blue ensure">确定</button>
        <button class="button button-white cancel">取消</button>
    </div>
</div>


<script>
    $(function(){
        $("#data").datagrid({
            url:"<?=\yii\helpers\Url::to(['select-corp'])?>",
            method:"get",
            pagination:true,
            singleSelect:true,
            columns:[[
                {field:"company_id",title:"公司ID",width:300},
                {field:"company_name",title:"公司名称",width:300}
            ]]
        });

        $("#search").on("click",function(){
            $("#data").datagrid("load",{
                company_name:$("#keywords").val()
            });
        });

        $(".ensure").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            parent.$("#corp_name").val(row.company_name).change();
            parent.$("#corp_id").val(row.company_id);
            parent.$.fancybox.close();

        });

        $(".cancel").on("click",function () {
            parent.$.fancybox.close();
        });
    });
</script>
