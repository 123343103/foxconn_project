<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/7
 * Time: 上午 09:16
 */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="head-first">选择客户</div>
<div style="margin-left:25px;margin-right:25px;">
    <div class="mt-20 mb-20">
        <input id="keywords" type="text" placeholder="请输入客户名称">
        <button id="search" class="button-blue">搜索</button>
        <a id="addCustomer" class="float-right">新增客户</a>
    </div>
    <div style="width:800px;" id="data"></div>
    <div class="mt-20 text-right">
        <button class="button button-blue ensure">确定</button>
        <button type="reset" class="button button-white cancel">取消</button>
    </div>
</div>

<script>
    $(function(){
        $("#data").datagrid({
            url:"<?=\yii\helpers\Url::to(['select-customer'])?>",
            pagination:true,
            method: "get",
            singleSelect:true,
            columns:[[
                {field:"ck",checkbox:true,width:50},
                {field:"cust_code",title:"客户编号",width:250},
                {field:"cust_sname",title:"客户简称",width:250},
                {field:"cust_shortname",title:"客户名称",width:250}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });

        $("#search").on("click",function(){
            $("#data").datagrid("load",{
                'CrmVisitInfoSearch[searchKeyword]':$("#keywords").val()
            });
        });

        $("#addCustomer").on("click",function(){
            parent.window.location.href="<?=\yii\helpers\Url::to(['crm-customer-info/create'])?>";
        });
        $(".ensure").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            parent.$("#custom_name").val(row.cust_sname).change();
            parent.$("#custom_id").val(row.cust_id);
            parent.$("#cust_tel1").val(row.cust_tel1);
            parent.$("#cust_fax").val(row.cust_fax);
            parent.$("#cust_sale").val(row.cust_area);
            parent.$("#cust_contacts").val(row.cust_contacts);
            parent.$("#cust_readress").val(row.cust_readress);
            parent.$("#cust_salearea").val(row.cust_salearea);
            parent.$.fancybox.close();
        });
        $(".cancel").on("click",function () {
            parent.$.fancybox.close();
        });
    });
</script>