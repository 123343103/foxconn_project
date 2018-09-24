<?php
/**
 * User: F1677929
 * Date: 2017/12/26
 */
use yii\helpers\Url;
?>
<h1 class="head-first">储位信息</h1>
<div style="margin:0 15px">
    <div class="mb-10">
        <label style="width:60px;">分区码：</label>
        <input id="val1" type="text" style="width:100px;">
        <label style="width:60px;">货架码：</label>
        <input id="val2" type="text" style="width:100px;">
        <label style="width:60px;">储位码：</label>
        <input id="val3" type="text" style="width:100px;">
        <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="datagrid1" style="width:100%;"></div>
    <div id="selected_area" style="border:1px solid #cccccc;height:70px;margin:5px 0;padding:5px;overflow-y:auto;"></div>
    <div style="text-align:center;">
        <button type="button" class="button-blue" id="confirm_btn">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<script>
    //选中或取消选中时更新数据
    function updateSelectedVal(){
        var rows=$("#datagrid1").datagrid("getChecked");
        $("#selected_area").empty();
        $.each(rows,function(index,row){
            $("#selected_area").append("<div style='display:inline-block;line-height:20px;margin-right:10px;'><span>"+row.st_code+"</span><span class='remove_val' style='color:red;cursor:pointer;'>x</span></div>");
        });
    }

    //document ready
    $(function(){
        $("#datagrid1").datagrid({
            url:"<?=Url::to(['select-location','code'=>Yii::$app->request->get('code')])?>",
            rownumbers:true,
            method:"get",
            pagination:true,
            idField:"st_id",
            pageSize:5,
            pageList:[5,10,15],
            columns:[[
                {field:"ck",checkbox:true},
                {field:"wh_name",title:"仓库",width:134},
                {field:"part_code",title:"分区码",width:134},
                {field:"part_name",title:"区位名称",width:134},
                {field:"rack_code",title:"货架码",width:133},
                {field:"st_code",title:"储位码",width:133}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#datagrid1");
                showEmpty($(this),data.total,1);
            },
            onCheck:function(){
                updateSelectedVal();
            },
            onUncheck:function(){
                updateSelectedVal();
            },
            onCheckAll:function(){
                updateSelectedVal();
            },
            onUncheckAll:function(){
                updateSelectedVal();
            }
        });

        //查询
        function loadData(){
            $("#datagrid1").datagrid('load',{
                "val1":$("#val1").val(),
                "val2":$("#val2").val(),
                "val3":$("#val3").val()
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
            $("#datagrid1").datagrid("clearChecked");
        });

        //确定
        $("#confirm_btn").click(function(){
            var rows=$("#datagrid1").datagrid("getChecked");
            if(rows.length == 0){
                layer.alert("请至少选择一条数据",{icon:2});
                return false;
            }
            parent.selectLocationCallback(rows);
            parent.$.fancybox.close();
        });

        //移除
        $(document).on("click",".remove_val",function(){
            var removeCode=$(this).prev().text();
            $(this).parent().remove();
            var $datagrid1=$("#datagrid1");
            var rows1=$datagrid1.datagrid("getChecked");
            var rows2=$datagrid1.datagrid("getSelections");
            $.each(rows1,function(index,row){
                if(row.st_code == removeCode){
                    rows1.splice(index,1);
                    rows2.splice(index,1);
                    return false;
                }
            });
            var row3=$datagrid1.datagrid("getRows");
            $.each(row3,function(index,row){
                if(row.st_code == removeCode){
                    var rowIndex=$datagrid1.datagrid("getRowIndex",row.st_id);
                    $datagrid1.datagrid("uncheckRow",rowIndex);
                    return false;
                }
            });
        })
    });
</script>