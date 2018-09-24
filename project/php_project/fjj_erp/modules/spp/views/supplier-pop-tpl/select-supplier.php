<?php
/**
 * User: F1677929
 * Date: 2017/11/16
 */
use yii\helpers\Url;
$params=Yii::$app->request->queryParams;
?>
<h1 class="head-first">供应商信息</h1>
<div style="margin:0 15px">
    <div class="mb-10">
        <input id="keyword" type="text" style="width:200px;" placeholder="请输入供应商名称">
        <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="supplier_info" style="width:100%;"></div>
    <div id="supplier_area" style="border:1px solid #cccccc;height:70px;margin:5px 0;padding:5px;overflow-y:auto;"></div>
    <div style="text-align:center;">
        <button type="button" class="button-blue" id="confirm_btn">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<script>
    //选中或取消选中时更新供应商
    function updateSupplier(){
        var rows=$("#supplier_info").datagrid("getChecked");
        $("#supplier_area").empty();
        $.each(rows,function(index,row){
            $("#supplier_area").append("<div style='display:inline-block;line-height:20px;margin-right:10px;'><span>"+row.spp_code+"</span><span class='remove_sup' style='color:red;cursor:pointer;'>x</span></div>");
        });
    }

    //document ready
    $(function(){
        $("#supplier_info").datagrid({
            url:"<?=empty($params['url'])?Url::to(['select-supplier','filters'=>empty($params['filters'])?'':$params['filters']]):$params['url']?>",
            rownumbers:true,
            method:"get",
            pagination:true,
            idField:"<?=empty($params['idField'])?"spp_id":$params['idField']?>",
            columns:[[
                {field:"ck",checkbox:true},
                {field:"spp_code",title:"供应商代码",width:160},
                {field:"group_code",title:"集团代码",width:160},
                {field:"spp_fname",title:"供应商名称",width:200},
                {field:"spp_sname",title:"供应商简称",width:156}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#supplier_info");
                showEmpty($(this),data.total,1);
            },
            onCheck:function(){
                updateSupplier();
            },
            onUncheck:function(){
                updateSupplier();
            },
            onCheckAll:function(){
                updateSupplier();
            },
            onUncheckAll:function(){
                updateSupplier();
            }
        });

        //查询
        function loadData(){
            $("#supplier_info").datagrid('load',{
                "keyword":$("#keyword").val()
            });
        }
        $("#query_btn").click(function(){
            loadData();
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                loadData();
            }
        });
        $("#reset_btn").click(function(){
            $("input,select").val('');
            loadData();
            $("#supplier_info").datagrid("clearChecked");
        });

        //确定
        $("#confirm_btn").click(function(){
            var rows=$("#supplier_info").datagrid("getChecked");
            if(rows.length == 0){
                layer.alert("请至少选择一个供应商",{icon:2});
                return false;
            }
            parent.selectSupplierCallback(rows);
            parent.$.fancybox.close();
        });

        //移除
        $(document).on("click",".remove_sup",function(){
            var removeCode=$(this).prev().text();
            $(this).parent().remove();
            var $supplierInfo=$("#supplier_info");
            var rows1=$supplierInfo.datagrid("getChecked");
            var rows2=$supplierInfo.datagrid("getSelections");
            $.each(rows1,function(index,row){
                if(row.spp_code == removeCode){
                    rows1.splice(index,1);
                    rows2.splice(index,1);
                    return false;
                }
            });
            var row3=$supplierInfo.datagrid("getRows");
            $.each(row3,function(index,row){
                if(row.spp_code == removeCode){
                    var rowIndex=$supplierInfo.datagrid("getRowIndex",row.<?=empty($params['idField'])?"spp_id":$params['idField']?>);
                    $supplierInfo.datagrid("uncheckRow",rowIndex);
                    return false;
                }
            });
        })
    });
</script>