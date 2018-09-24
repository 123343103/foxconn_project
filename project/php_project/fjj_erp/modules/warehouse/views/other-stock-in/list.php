<?php
/**
 * User: F1677929
 * Date: 2017/12/14
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='其他入库单列表';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search',['data'=>$data])?>
    <?=$this->render('_action')?>
    <div id="table1" style="width:100%;"></div>
    <div style="height:10px;"></div>
    <div id="table2" style="width:100%;"></div>
</div>
<script>
    //取消
    function cancelFun(id){
        $.fancybox({
            href:"<?=Url::to(['cancel-add'])?>?id="+id,
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
            idField: "invh_id",
            columns:[[
                {field:"ck",checkbox:true},
                {field:"invh_code",title:"入库单号",width:150,formatter:function(value,rowData){
                    return "<a onclick='event.stopPropagation();location.href=\"<?=Url::to(['view'])?>?id="+rowData.invh_id+"&flag="+rowData.inout_type+"\"'>"+value+"</a>";
                }},
                {field:"invh_status",title:"入库单状态",width:100},
                {field:"invh_aboutno",title:"关联单号",width:150},
                {field:"inout_flag_val",title:"单据类型",width:100},
                {field:"wh_name",title:"入仓仓库",width:100},
                {field:"invh_sendperson",title:"送货人",width:100},
                {field:"invh_reperson",title:"收货人",width:100},
                {field:"recive_date",title:"收货日期",width:100},
                {field:"create_by",title:"制单人",width:100},
                {field:"cdate",title:"制单时间",width:100},
                {field:"invh_id",title:"操作",width:60,formatter:function(value,rowData){
                    var str="<i>";
                    if(rowData.invh_status=='待提交' || rowData.invh_status=='驳回'){
                        str+="<a class='icon-minus-sign icon-large' title='取消入库' onclick='event.stopPropagation();cancelFun("+value+");'></a>";
                        str+="<a style='margin-left:15px;' class='icon-edit icon-large' title='修改' onclick='event.stopPropagation();location.href=\"<?=Url::to(['edit'])?>?id="+value+"\"'></a>";
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
                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                $("#cancel_btn").hide().next().hide();
                $("#put_away_btn").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#table1").datagrid("getRowIndex", rowData.invh_id);
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


                if(rowData.invh_status=='待提交' || rowData.invh_status=='驳回'){
                    $("#edit_btn").show().next().show();
                    $("#check_btn").show().next().show();
                    $("#cancel_btn").show().next().show();
                }
                if(rowData.invh_status=='待上架'){
                    $("#put_away_btn").show().next().show();
                }


                var columns=[];
                if(rowData.inout_type==2){//调拨
                    columns=[[
                        {field:"part_no",title:"料号",width:150},
                        {field:"pdt_name",title:"品名",width:200},
                        {field:"brand",title:"品牌",width:100},
                        {field:"tp_spec",title:"规格/型号",width:150},
                        {field:"ord_id",title:"批次",width:100},
                        {field:"invt_num",title:"现有库存量",width:100},
                        {field:"delivery_num",title:"调拨数量",width:100},
                        {field:"before_stno",title:"出仓储位",width:100},
                        {field:"unit",title:"单位",width:100},
                        {field:"real_quantity",title:"入库数量",width:100},
                        {field:"st_codes",title:"储位",width:200},
                        {field:"inout_time",title:"上架日期",width:100}
                    ]];
                }else{
                    if(rowData.inout_type==3){//移仓
                        columns=[[
                            {field:"part_no",title:"料号",width:150},
                            {field:"pdt_name",title:"品名",width:200},
                            {field:"brand",title:"品牌",width:100},
                            {field:"tp_spec",title:"规格/型号",width:150},
                            {field:"ord_id",title:"批次",width:100},
                            {field:"before_stno",title:"移仓前储位",width:100},
                            {field:"chwh_num",title:"移仓数量",width:100},
                            {field:"unit",title:"单位",width:100},
                            {field:"real_quantity",title:"入库数量",width:100},
                            {field:"st_codes",title:"储位",width:200},
                            {field:"inout_time",title:"上架日期",width:100}
                        ]];
                    }else{//新增
                        columns=[[
                            {field:"part_no",title:"料号",width:150},
                            {field:"pdt_name",title:"品名",width:200},
                            {field:"brand",title:"品牌",width:100},
                            {field:"tp_spec",title:"规格/型号",width:150},
                            {field:"batch_no",title:"收货批次",width:100},
                            {field:"in_quantity",title:"送货数量",width:100},
                            {field:"real_quantity",title:"预实收数量",width:100},
                            {field:"unit",title:"单位",width:100},
                            {field:"pack_type",title:"包装方式",width:100},
                            {field:"pack_num",title:"包装件数",width:100},
                            {field:"st_codes",title:"储位",width:200}
                        ]];
                    }
                }
                $("#table2").datagrid({
                    url:"<?=Url::to(['get-products'])?>",
                    queryParams:{
                        "id":rowData.invh_id,
                        "inout_type":rowData.inout_type
                    },
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


                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                    $("#put_away_btn").hide().next().hide();
                    $("#cancel_btn").show().next().show();
                    $.each(a1,function(i,n){
                        if(n.invh_status!='待提交' && n.invh_status!='驳回'){
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
                        var b = $("#table1").datagrid("getRowIndex", a[0].rcpg_id);
                        $('#table1').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;


                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                    $("#put_away_btn").hide().next().hide();
                    $("#cancel_btn").hide().next().hide();


                    $('#table1').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#table1').datagrid("unselectAll");
                    }
                }else{
                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                    $("#put_away_btn").hide().next().hide();
                    $("#cancel_btn").show().next().show();
                    $.each(a,function(i,n){
                        if(n.invh_status!='待提交' && n.invh_status!='驳回'){
                            $("#cancel_btn").hide().next().hide();
                            return false;
                        }
                    });
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#table1').datagrid("unselectAll");


                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                $("#put_away_btn").hide().next().hide();
                $("#cancel_btn").show().next().show();
                var a = $("#table1").datagrid("getChecked");
                $.each(a,function(i,n){
                    if(n.invh_status!='待提交' && n.invh_status!='驳回'){
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
                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                $("#put_away_btn").hide().next().hide();
                $("#cancel_btn").hide().next().hide();
            }
        });
    })
</script>