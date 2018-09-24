<?php
/**
 * User: F1677929
 * Date: 2017/12/16
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='商品收货通知列表';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search')?>
    <?=$this->render('_action')?>
    <div id="table1" style="width:100%;"></div>
    <div style="height:10px;"></div>
    <div id="table2" style="width:100%;"></div>
</div>
<script>
    //取消收货
    function cancelFun(id){
        $.fancybox({
            href:"<?=Url::to(['cancel-receipt'])?>?id="+id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:430,
            height:300
        });
    }

    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function(){
        $("#table1").datagrid({
            url:"<?=Url::to(['list'])?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            checkOnSelect: false,
            selectOnCheck: false,
            idField: "rcpnt_id",
            columns:[[
                {field:"ck",checkbox:true},
//                {field:"rcpnt_no",title:"收货通知单号",width:200,formatter:function(value,rowData){
//                    return "<a onclick='event.stopPropagation();location.href=\"<?//=Url::to(['view'])?>//?id="+rowData.rcpnt_id+"\"'>"+value+"</a>";
//                }},
                <?= $fields ?>
                {field:"rcpnt_id",title:"操作",width:60,formatter:function(value,rowData){
                    var str="<i>";
                    if(rowData.rcpnt_status=='待收货' && rowData.rcpnt_type=='采购'){
                        str+="<a class='icon-check-minus icon-large' title='取消收货' onclick='event.stopPropagation();cancelFun("+value+");'></a>";
                    }
                    str+="</i>";
                    return str;
                }}
            ]],
            onLoadSuccess: function (data) {
                if(data.total==0){
                    $("#export_btn").hide().next().hide();
                }else{
                    $("#export_btn").show().next().show();
                }
                datagridTip("#table1");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#table1").datagrid('clearSelections').datagrid('clearChecked');
                $("#generate_btn").hide().next().hide();
                $("#cancel_btn").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#table1").datagrid("getRowIndex", rowData.rcpnt_id);
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


                if(rowData.rcpnt_status=='待收货'){
                    $("#generate_btn").show().next().show();
                }
                if(rowData.rcpnt_status=='待收货' && rowData.rcpnt_type=='采购'){
                    $("#cancel_btn").show().next().show();
                }


                var columns=[];
                if(rowData.rcpnt_type=="采购"){
                    columns=[[
                        {field:"part_no",title:"料号",width:150},
                        {field:"pdt_name",title:"品名",width:200},
                        {field:"tp_spec",title:"规格型号",width:150},
                        {field:"brand",title:"品牌",width:100},
                        {field:"unit",title:"单位",width:100},
                        {field:"group_code",title:"供应商编码",width:200},
                        {field:"spp_fname",title:"供应商名称",width:200},
                        {field:"ord_num",title:"采购量",width:100},
                        {field:"delivery_num",title:"送货数量",width:100},
                        {field:"plan_date",title:"预计到货日期",width:100},
                        {field:"remarks",title:"备注",width:200}
                    ]];
                }
                if(rowData.rcpnt_type=="调拨"){
                    columns=[[
                        {field:"part_no",title:"料号",width:150},
                        {field:"pdt_name",title:"品名",width:200},
                        {field:"tp_spec",title:"规格型号",width:150},
                        {field:"brand",title:"品牌",width:100},
                        {field:"unit",title:"单位",width:100},
                        {field:"ord_id",title:"批次",width:100},
                        {field:"invt_num",title:"现有库存量",width:100},
                        {field:"delivery_num",title:"调拨数量",width:100},
                        {field:"before_stno",title:"出仓储位",width:100}
                    ]];
                }
                if(rowData.rcpnt_type=="移仓"){
                    columns=[[
                        {field:"part_no",title:"料号",width:150},
                        {field:"pdt_name",title:"品名",width:200},
                        {field:"tp_spec",title:"规格型号",width:150},
                        {field:"brand",title:"品牌",width:100},
                        {field:"unit",title:"单位",width:100},
                        {field:"ord_id",title:"批次",width:100},
                        {field:"before_stno",title:"移仓前储位",width:100},
                        {field:"chwh_num",title:"移仓数量",width:100}
                    ]];
                }
                $("#table2").datagrid({
                    url:"<?=Url::to(['get-pno'])?>?code="+rowData.rcpnt_no_val,
                    rownumbers:true,
                    method:"get",
                    singleSelect:true,
                    pagination:true,
                    columns:columns,
                    onLoadSuccess: function (data) {
                        datagridTip("#table2");
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
                    }
                });
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


                    $("#generate_btn").hide().next().hide();
                    $("#cancel_btn").show().next().show();
                    $.each(a1,function(i,n){
                        if(n.rcpnt_status=='已收货' || n.rcpnt_status=='已取消' || n.rcpnt_type=='调拨' || n.rcpnt_type=='移仓'){
                            $("#cancel_btn").hide().next().hide();
                            return false;
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
                        var b = $("#table1").datagrid("getRowIndex", a[0].rcpnt_id);
                        $('#table1').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;


                    $("#generate_btn").hide().next().hide();
                    $("#cancel_btn").hide().next().hide();


                    $('#table1').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#table1').datagrid("unselectAll");
                    }
                }else{
                    $("#generate_btn").hide().next().hide();
                    $("#cancel_btn").show().next().show();
                    $.each(a,function(i,n){
                        if(n.rcpnt_status=='已收货' || n.rcpnt_status=='已取消' || n.rcpnt_type=='调拨' || n.rcpnt_type=='移仓'){
                            $("#cancel_btn").hide().next().hide();
                            return false;
                        }
                    });
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#table1').datagrid("unselectAll");


                $("#generate_btn").hide().next().hide();
                $("#cancel_btn").show().next().show();
                var a = $("#table1").datagrid("getChecked");
                $.each(a,function(i,n){
                    if(n.rcpnt_status=='已收货' || n.rcpnt_status=='已取消' || n.rcpnt_type=='调拨' || n.rcpnt_type=='移仓'){
                        $("#cancel_btn").hide().next().hide();
                        return false;
                    }
                });
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#table2").parents(".datagrid").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#generate_btn").hide().next().hide();
                $("#cancel_btn").hide().next().hide();
            }
        });
    })
</script>