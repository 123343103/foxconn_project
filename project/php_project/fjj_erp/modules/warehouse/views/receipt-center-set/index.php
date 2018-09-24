<?php
/**
 * User: F1677929
 * Date: 2017/12/5
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='收货中心设置';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search')?>
    <?=$this->render('_action')?>
    <div id="table1" style="width:100%;"></div>
</div>
<script>
    //修改
    function editFun(id,event){
        event.stopPropagation();
        $.fancybox({
            href:"<?=Url::to(['edit'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:600,
            height:400
        });
    }

    //查看
    function viewFun(id,event){
        event.stopPropagation();
        $.fancybox({
            href:"<?=Url::to(['view'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:600,
            height:400
        });
    }

    //启用禁用操作
    function operationFun(id,flag){
        var msg="参数错误";
        if(flag=="enabled"){
            msg="确认启用此收货中心？";
        }
        if(flag=="disabled"){
            msg="确认禁用此收货中心？";
        }
        layer.confirm(msg,{icon:2},
            function(){
                if(msg=="参数错误"){
                    return false;
                }
                $.ajax({
                    url:"<?=Url::to(['operation'])?>",
                    data:{"id":id,"flag":flag},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1,end:function(){
                                $("#table1").datagrid('reload');
                            }});
                        }
                    }
                })
            },layer.closeAll()
        )
    }

    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    //jquery
    $(function(){
        $("#table1").datagrid({
            url:"<?=Url::to(['index'])?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            checkOnSelect: false,
            selectOnCheck: false,
            idField: "rcp_id",
            columns:[[
                {field:"ck",checkbox:true},
                {field:"rcp_no",title:"编码",width:150,formatter:function(value,rowData){
                    return "<a onclick='viewFun("+rowData.rcp_id+",event)'>"+value+"</a>";
                }},
                {field:"rcp_name",title:"收货中心名称",width:200},
                {field:"rcp_status",title:"状态",width:100},
                {field:"addr",title:"详细地址",width:250},
                {field:"contact",title:"联系人",width:100},
                {field:"contact_tel",title:"联系方式",width:100},
                {field:"contact_email",title:"邮箱",width:100},
                {field:"remarks",title:"备注",width:200},
                {field:"rcp_id",title:"操作",width:60,formatter:function(value,rowData){
                    var str="<i>";
                    if(rowData.rcp_status=='启用'){
                        str+="<a class='icon-check-minus icon-large' title='禁用' onclick='event.stopPropagation();operationFun("+value+",\"disabled\");'></a>";
                    }
                    if(rowData.rcp_status=='禁用'){
                        str+="<a class='icon-check-sign icon-large' title='启用' onclick='event.stopPropagation();operationFun("+value+",\"enabled\");'></a>";
                    }
                    str+="<a class='icon-edit icon-large' style='margin-left:15px;' title='修改' onclick='editFun("+value+",event)'></a>";
                    str+="</i>";
                    return str;
                }}
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#table1");
                showEmpty($(this),data.total,1);
                setMenuHeight();
                $("#table1").datagrid('clearSelections').datagrid('clearChecked');
                $("#edit_btn").hide().next().hide();
                $("#enabled_btn").hide().next().hide();
                $("#disabled_btn").hide().next().hide();
                if(data.total==0){
                    $("#export_btn").hide().next().hide();
                }else{
                    $("#export_btn").show().next().show();
                }
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#table1").datagrid("getRowIndex", rowData.rcp_id);
                $('#table1').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#table1").datagrid("getChecked");
                    onlyOne = true;
                    $('#table1').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                }
                isCheck = false;
                $('#table1').datagrid('checkRow', index);


                $("#edit_btn").show().next().show();
                if(rowData.rcp_status=='启用'){
                    $("#disabled_btn").show().next().show();
                }
                if(rowData.rcp_status=='禁用'){
                    $("#enabled_btn").show().next().show();
                }
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#table1").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#table1').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#table1').datagrid("unselectAll");


                    $("#edit_btn").hide().next().hide();
                    $("#enabled_btn").show().next().show();
                    $("#disabled_btn").show().next().show();
                    $.each(a1,function(i,n){
                        if(n.rcp_status=='启用'){
                            $("#enabled_btn").hide().next().hide();
                        }
                        if(n.rcp_status=='禁用'){
                            $("#disabled_btn").hide().next().hide();
                        }
                    });
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#table1").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#table1").datagrid("getRowIndex", a[0].rcp_id);
                        $('#table1').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;


                    $("#edit_btn").hide().next().hide();
                    $("#enabled_btn").hide().next().hide();
                    $("#disabled_btn").hide().next().hide();


                    $('#table1').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#table1').datagrid("unselectAll");
                    }
                }else{
                    $("#edit_btn").hide().next().hide();
                    $("#enabled_btn").show().next().show();
                    $("#disabled_btn").show().next().show();
                    $.each(a,function(i,n){
                        if(n.rcp_status=='启用'){
                            $("#enabled_btn").hide().next().hide();
                        }
                        if(n.rcp_status=='禁用'){
                            $("#disabled_btn").hide().next().hide();
                        }
                    });
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#table1').datagrid("unselectAll");


                $("#edit_btn").hide().next().hide();
                var a = $("#table1").datagrid("getChecked");
                $("#enabled_btn").show().next().show();
                $("#disabled_btn").show().next().show();
                $.each(a,function(i,n){
                    if(n.rcp_status=='启用'){
                        $("#enabled_btn").hide().next().hide();
                    }
                    if(n.rcp_status=='禁用'){
                        $("#disabled_btn").hide().next().hide();
                    }
                });
            },
            onUnselectAll: function (rowIndex, rowData) {

            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#edit_btn").hide().next().hide();
                $("#enabled_btn").hide().next().hide();
                $("#disabled_btn").hide().next().hide();
            }
        });
    })
</script>