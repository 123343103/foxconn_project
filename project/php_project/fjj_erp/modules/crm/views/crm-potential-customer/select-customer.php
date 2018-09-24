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
        <input id="keywords" type="text">
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
            url:"<?=\yii\helpers\Url::to(['index'])?>",
            pagination:true,
            method: "get",
            singleSelect:true,
            columns:[[
                {field:"ck",checkbox:true,width:50},
                {field:"cust_filernumber",title:"客户编号",width:250},
                {field:"cust_sname",title:"客户简称",width:250},
                {field:"cust_shortname",title:"客户名称",width:250}
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,1);
            }
        });

        //潜在客户搜索
        $("#search").on("click",function(){
            $("#data").datagrid("load",{
                'keywords':$("#keywords").val()
            });
        });

        //新增客户
        $("#addCustomer").on("click",function(){
            parent.window.location.href="<?=\yii\helpers\Url::to(['crm-customer-info/create'])?>";
        });

        //确定添加
        $(".ensure").on("click",function(){
            var row=$("#data").datagrid("getSelected");
            for(x in row){
                if(typeof row[x]=="string"){
                    row[x]=row[x].decode();
                }
            }
            parent.customer=row;
            $("#cust_id",window.parent.document).val(row.cust_id);
            $("#cust_sname",window.parent.document).val(row.cust_sname);
            $("#cust_shortname",window.parent.document).val(row.cust_shortname);
            $("#cust_tel1",window.parent.document).val(row.cust_tel1);
            $("#cust_tel2",window.parent.document).val(row.cust_tel2);
            $("#cust_email",window.parent.document).val(row.cust_email);
            $("#cust_contacts",window.parent.document).val(row.cust_contacts);
            $("#cust_position",window.parent.document).val(row.cust_position);
            $("#cust_ismember",window.parent.document).val(row.cust_ismember);
            $("#cust_regdate",window.parent.document).val(row.cust_regdate);
            $("#member_type",window.parent.document).val(row.member_type);
            $("#member_name",window.parent.document).val(row.member_name);

            $("#visit_cust_id",window.parent.document).val(row.cust_id);
            $("#visit_cust_sname",window.parent.document).val(row.cust_snamae);
            parent.$.fancybox.close();
        });
        //关闭弹窗
        $(".cancel").on("click",function () {
            parent.$.fancybox.close();
        });
    });
</script>