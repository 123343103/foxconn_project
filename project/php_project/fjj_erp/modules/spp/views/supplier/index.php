<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='供应商列表';
$this->params['homeLike']=['label'=>'供应商管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search',['data'=>$data])?>
    <?=$this->render('_action')?>
    <div id="supplier_table" style="width:100%;"></div>
    <div class="easyui-tabs" style="margin-top:10px;display:none;">
        <div title="联系信息">
            <div id="cont_tab" style="width:100%;"></div>
        </div>
        <div title="主营商品">
            <div id="mpdt_tab" style="width:100%;"></div>
        </div>
        <div title="拟采购商品">
            <div id="purpdt_tab" style="width:100%;"></div>
        </div>
    </div>
</div>
<script>
    //删除供应商
    function deleteSpp(id){
        layer.confirm('确定删除吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['delete-supplier'])?>",
                    data:{"id":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1,end:function(){
                                $("#supplier_table").datagrid('reload');
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
    $(function(){
        //供应商表格
        $("#supplier_table").datagrid({
            url:"<?=Url::to(['index'])?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            checkOnSelect: false,
            selectOnCheck: false,
            idField: "spp_id",
            columns:[[
                {field:"ck",checkbox:true},
//                {field:"spp_code",title:"供应商编号",width:150},
//                {field:"group_code",title:"集团编号",width:150},
//                {field:"spp_fname",title:"供应商名称",width:250,formatter:function(value,rowData){
//                    return "<a onclick='event.stopPropagation();location.href=\"<?//=Url::to(['view'])?>//?id="+rowData.spp_id+"\"'>"+value+"</a>";
//                }},
//                {field:"sppType",title:"供应商分类",width:80},
//                {field:"sppSource",title:"供应商来源",width:80},
//                {field:"sourceType",title:"来源类别",width:80},
//                {field:"groupSpp",title:"集团供应商",width:80},
//                {field:"operator",title:"申请人",width:80},
//                {field:"oper_time",title:"申请时间",width:80},
//                {field:"sppStatus",title:"状态",width:80},
//                {field:"sale_turn",title:"预计年销售额",width:100},
//                {field:"sale_profit",title:"预计年销售利润",width:100},
                <?=$data['table1']?>
                {field:"spp_id",title:"操作",width:60,formatter:function(value,rowData){
                    var str="<i>";
                    if(rowData.sppStatus=='未提交' || rowData.sppStatus=='驳回'){
                        str+="<a class='icon-trash icon-large' title='删除' onclick='event.stopPropagation();deleteSpp("+value+");'></a>";
                        str+="<a class='icon-edit icon-large' style='margin-left:15px;' title='修改' onclick='event.stopPropagation();location.href=\"<?=Url::to(['edit'])?>?id="+value+"\"'></a>";
                    }
                    str+="</i>";
                    return str;
                }}
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#supplier_table");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#supplier_table").datagrid('clearSelections').datagrid('clearChecked');
                $("#edit_btn").hide().next().hide();
                $("#delete_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#supplier_table").datagrid("getRowIndex", rowData.spp_id);
                $('#supplier_table').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#supplier_table").datagrid("getChecked");
                    onlyOne = true;
                    $('#supplier_table').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                }
                isCheck = false;
                $('#supplier_table').datagrid('checkRow', index);


                if(rowData.sppStatus=='未提交' || rowData.sppStatus=='驳回'){
                    $("#edit_btn").show().next().show();
                    $("#delete_btn").show().next().show();
                    $("#check_btn").show().next().show();
                }


                $(".easyui-tabs").show().tabs("resize");
                //获取供应商联系人
                $("#cont_tab").datagrid({
                    url:"<?=Url::to(['get-contacts'])?>?id="+rowData.spp_id,
                    rownumbers:true,
                    method:"get",
                    singleSelect:true,
                    pagination:true,
                    columns:[[
                        {field:"name",title:"联系人",width:240},
                        {field:"mobile",title:"联系电话",width:240},
                        {field:"email",title:"邮箱",width:240},
                        {field:"fax",title:"传真",width:234}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#cont_tab");
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
                    }
                });
                //获取供应商主营商品
                $("#mpdt_tab").datagrid({
                    url:"<?=Url::to(['get-main-product'])?>?id="+rowData.spp_id,
                    rownumbers:true,
                    method:"get",
                    singleSelect:true,
                    pagination:true,
                    columns:[[
                        {field:"mian_pdt",title:"主营项目",width:200},
                        {field:"pdt_ad",title:"商品优势与不足",width:200},
                        {field:"pdt_sca",title:"销售渠道与区域",width:200},
                        {field:"sale_quan",title:"年销售量(单位)",width:100},
                        {field:"market_share",title:"市场份额(%)",width:100},
                        {field:"open_sale",title:"是否公开销售",width:100},
                        {field:"agency",title:"是否代理",width:100}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#mpdt_tab");
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
                    }
                });
                //获取供应商拟采购商品
                $("#purpdt_tab").datagrid({
                    url:"<?=Url::to(['get-purchase-product'])?>?id="+rowData.spp_id,
                    rownumbers:true,
                    method:"get",
                    singleSelect:true,
                    pagination:true,
                    columns:[[
                        {field:"part_no",title:"商品料号",width:200},
                        {field:"pdt_name",title:"商品名称",width:200},
                        {field:"tp_spec",title:"规格型号",width:200},
                        {field:"category",title:"商品类型",width:200},
                        {field:"unit",title:"单位",width:154}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#purpdt_tab");
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
                    }
                });
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#supplier_table").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#supplier_table').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#supplier_table').datagrid("unselectAll");


                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                    $.each(a1,function(i,n){
                        if(n.sppStatus=='审核中' || n.sppStatus=='审核完成'){
                            $("#delete_btn").hide().next().hide();
                        }
                    });
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#supplier_table").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#supplier_table").datagrid("getRowIndex", a[0].spp_id);
                        $('#supplier_table').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;


                    $("#edit_btn").hide().next().hide();
                    $("#delete_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();


                    $('#supplier_table').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#supplier_table').datagrid("unselectAll");
                    }
                }else{
                    $("#delete_btn").show().next().show();
                    $.each(a,function(i,n){
                        if(n.sppStatus=='审核中' || n.sppStatus=='审核完成'){
                            $("#delete_btn").hide().next().hide();
                        }
                    });
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#supplier_table').datagrid("unselectAll");


                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                var a = $("#supplier_table").datagrid("getChecked");
                $.each(a,function(i,n){
                    if(n.sppStatus=='审核中' || n.sppStatus=='审核完成'){
                        $("#delete_btn").hide().next().hide();
                    }
                });
            },
            onUnselectAll: function (rowIndex, rowData) {
                $(".easyui-tabs").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#edit_btn").hide().next().hide();
                $("#delete_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
            }
        });
    })
</script>