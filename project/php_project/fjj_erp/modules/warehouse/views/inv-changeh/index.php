<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/10
 * Time: 下午 03:34
 */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '报废申请列表';

$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '报废申请列表'];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'get' => \Yii::$app->request->get(),
        'downlist' => $downlist,
        'queryParam' => $queryParam,
        'whname'=>$whname
//        'statusType' => $statusType,
    ]); ?>
    <div style="height: 20px;"></div>
    <div class="table-content">
        <div class="table-head">
            <p class="head">报废信息列表</p>
            <?php echo $this->render('_action');?>
            </div>
        </div>
        <div class="space-10"></div>
        <div style="clear: right;"></div>
        <div id="data"></div>
        <div id="load-content" class="overflow-auto"></div>
    <div id="order_child_title"></div>
    <div id="order_child" style="width:100%;"></div>
    </div>
    <div class="space-30"></div>
<script>
    $(function () {
        var id;
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
//        var aa=new Array();
        $("#update").hide().next().hide();
        $("#check").hide().next().hide();
        $("#delete").hide().next().hide();
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "chh_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
//            fitColumns: false,
            columns: [[
                {field: 'ck', checkbox: true},
//                {field: 'ck', checkbox: true, align: 'left'},
                {field: "chh_code",width:130, title: "报废单号"},
                {field: "chh_status",width:100, title: "报废单状态"},
                {field: "chh_typeName",width:150, title: "报废类别"},
                {field: "wh_name",width:130, title: "出仓仓库"},
                {field: "organization",width:150, title: "申请部门"},
                {field: "create_by",width:80, title: "申请人"},
                {field: "review_by",width:80, title: "制单人"},
                {field: "review_at",width:100, title: "制单时间"},
                {field:'oper',title:'操作',width:60,align:'center',formatter: function (value, rowData, rowIndex) {
                    var str="<i>";
                    if(rowData.chh_status=="待提交"||rowData.chh_status=="驳回") {
                        str += "<a class='operate icon-check-minus  icon-large' title='作废单据'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    }else{
                        str += "";
                    }
                    if(rowData.chh_status=="待提交"||rowData.chh_status=="驳回") {
                        str += "<a class='edit icon-edit icon-large'  title='修改'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    }else{
                        str += "";
                    }
                    str+="</i>";
                    return str;
                }}
            ]],
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
                //每一个仓库代码详情
                $("#view").on("click",function () {
                    var id = $(this).html();
                    $('.viewitem').fancybox({
                        autoSize: true,
                        fitToView: false,
                        height: 500,
                        width: 800,
                        closeClick: true,
                        openEffect: 'none',
                        closeEffect: 'none',
                        type: 'iframe',
                        href: "<?= Url::to(['view'])?>?id=" + id
                    });
                });
                //---end----
            },
            onCheck:function (rowIndex,rowData) {
                var aa=new Array();
                var _check=$("#data").datagrid("getChecked");
                if (_check.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        for(var i=0;i<_check.length;i++){
                            aa.push(_check[i]['chh_status']);
                        }
                        if(((aa.indexOf("审核中")!=-1)&&(aa.indexOf("审核完成")!=-1))||((aa.indexOf("审核中")!=-1)&&(aa.indexOf("审核完成")<0))||((aa.indexOf("审核中")<0)&&(aa.indexOf("审核完成")!=-1)))
                        {
                            $("#update").hide().next().hide();
                            $("#check").hide().next().hide();
                            $("#delete").hide().next().hide();
                        } else {
                            $("#update").show().next().show();
                            $("#check").show().next().show();
                            $("#delete").show().next().show();
                        }
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                }
                else {
                    $("#edit_btn").hide().next().hide();
                    $("#addmes_btn").hide().next().hide();
                    for(var k=0;k<_check.length;k++){
                        aa.push(_check[k]['chh_status']);
                    }
                    if(((aa.indexOf("审核中")!=-1)&&(aa.indexOf("审核完成")!=-1))||((aa.indexOf("审核中")!=-1)&&(aa.indexOf("审核完成")<0))||((aa.indexOf("审核中")<0)&&(aa.indexOf("审核完成")!=-1)))
                    {
                        $("#update").hide().next().hide();
                        $("#check").hide().next().hide();
                        $("#delete").hide().next().hide();
                    } else {
                        $("#update").show().next().show();
                        $("#check").show().next().show();
                        $("#delete").show().next().show();
                    }
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                }
            },
            onUncheck: function (rowIndex, rowData) {
             var a1 = $("#data").datagrid("getChecked");
                    if(a1.length==0)
                    {
                        $("#update").hide().next().hide();
                        $("#check").hide().next().hide();
                        $("#delete").hide().next().hide();
                        isCheck = false;
                        isSelect = false;
                        onlyOne = false;
                            $('#data').datagrid("unselectAll");
                    }else {
                        if (a1.length==1) {
                            for (var i = 0; i < a1.length; i++) {
                                if ((a1[i]['chh_status'] != "待提交") && (a1[i]['chh_status'] != "驳回")) {
                                    $("#update").hide().next().hide();
                                    $("#check").hide().next().hide();
                                    $("#delete").hide().next().hide();
//                            break;
                                }
                                else {
                                    $("#update").show().next().show();
                                    $("#check").show().next().show();
                                    $("#delete").show().next().show();
                                }
                            }
                               var b = $("#data").datagrid("getRowIndex", a1[0].chh_id);
                                $('#data').datagrid('selectRow', b);
                        }
                        else{
                            for (var j = 0; j < a1.length; j++) {
                                if ((a1[j]['chh_status'] != "待提交") && (a1[j]['chh_status'] != "驳回")) {
                                    $("#update").hide().next().hide();
                                    $("#check").hide().next().hide();
                                    $("#delete").hide().next().hide();
//                            break;
                                }
                                else {
                                    $("#update").show().next().show();
                                    $("#check").show().next().show();
                                    $("#delete").show().next().show();
                                }
                            }

                        }
                        }


//                    else if(a1.length==0)
//                    {
//                        $("#update").hide().next().hide();
//                        $("#check").hide().next().hide();
//                        $("#delete").hide().next().hide();
//                    }

            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#order_child_title").hide().next().hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#order_child_title").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.chh_id);
                var oderh = $("#data").datagrid("getSelected");
                $('#data').datagrid("uncheckAll");
                if(oderh.chh_status=="待提交"||oderh.chh_status=="驳回") {
                    $("#update").show().next().show();
                    $("#check").show().next().show();
                    $("#delete").show().next().show();
                }else {
                    $("#update").hide().next().hide();
                    $("#check").hide().next().hide();
                    $("#delete").hide().next().hide();
                }
                if (isCheck && !isSelect && !onlyOne) {
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', rowIndex);
                $("#order_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>报废品信息</p>");
                var id = oderh['chh_id'];
//                console.log(id);
                $("#order_child").datagrid({
                    url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                    rownumbers: true,
                    method: "get",
                    idField: "chl_id",
                    singleSelect: true,
                    pagination: true,
                    HtmlEncode:false,
                    pageSize: 10,
                    pageList: [10, 20, 30],
                    columns: [[
                        {field: "pdt_no", title: "料号", width: 150},
                        {field: "pdt_name", title: "品名", width: 150},
                        {field: "catg_name", title: "类别", width: 150},
                        {field: "tp_spec", title: "规格型号", width: 150},
                        {field: "chl_bach", title: "批次", width: 150},
                        {field: "unit", title: "单位", width: 100},
                        {field: "st_code", title: "当前储位", width: 80},
                        {field: "before_num1", title: "现有库存", width: 150},
                        {field: "chl_num", title: "报废数量", width: 150},
                        {field: "mode", title: "报废方式", width: 150},
                        {field: "chh_remark", title: "报废原因", width: 150},
                        {field: "wh_name2", title: "存放仓库", width: 150},
                        {field: "st_code2", title: "存放储位", width: 150},
                        {field: "uprice_tax_o", title: "资产原值(元)", width: 80},
                        {field: "deal_price", title: "处理价(元)", width: 150},
                    ]],
                    onLoadSuccess: function (data) {
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
                        $("#order_child").datagrid('clearSelections');
                        datagridTip("#order_child");
                    }
                });
            }
        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });
        $("#add").on("click",function(){
            window.location.href = "<?=Url::to(['create'])?>";
        });
        $("#update").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请点击选择一条客户信息!",{icon:2,time:5000});
            }else{
                var id = $("#data").datagrid("getSelected")['chh_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        });
        $("#view").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请点击选择一条申请信息!",{icon:2,time:5000});
            }else{
                var id = $("#data").datagrid("getSelected")['chh_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        $("#delete").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            var _id=a['chh_id'];
            var bb= a['chh_status'];
            if(bb=="待提交"||bb=="驳回")
            {
                $.fancybox({
                    href: "<?=Url::to(['can-reason'])?>?id=" + _id,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 500,
                    height: 300
                });
            }
        });
        $('#export').click(function () {
            var index = layer.confirm("确定导出报废列表信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['index', 'export' => '1'])?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出客户信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });
//        $("#check").on("click",function(){
//            var data = $("#data").datagrid("getSelected");
//            if (data == null) {
//                layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
//                return false;
//            }
//            if(data['chh_status'] == 30){
//                layer.alert("正在审核中", {icon: 2, time: 5000});
//                return false;
//            }
//            if(data['chh_status'] == 40){
//                layer.alert("已审核完成", {icon: 2, time: 5000});
//                return false;
//            }
//            var id=data['chh_id'];
//            var url="<?//=Url::to(['view'],true)?>//?id="+data['chh_id'];
//            var type=24;
//            $.fancybox({
//                href:"<?//=Url::to(['/system/verify-record/reviewer'])?>//?type="+type+"&id="+id+"&url="+url,
//                type:"iframe",
//                padding:0,
//                autoSize:false,
//                width:750,
//                height:480
//            });
//    });
        $("#check").on("click",function(){
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
            if(data['chh_status'] == '审核中'){
                layer.alert("正在审核中", {icon: 2, time: 5000});
                return false;
            }
            if(data['chh_status'] == '审核完成'){
                layer.alert("已审核完成", {icon: 2, time: 5000});
                return false;
            }
            var id=data['chh_id'];
            var url="<?=Url::to(['view'],true)?>?id="+data['chh_id'];
            var changeType = data['chh_typeName'];
//            var type;
            var tpList = <?= $businessType ?>;
            console.log(tpList);
            for (var $k in tpList) {
                if (changeType == tpList[$k]) {
                    var type = $k;
                    break;
                }
            }
//            switch (changeType) {
//                case '成品报废':
//                    type = 27;
//                    break;
//                case '半成品报废':
//                    type = 28;
//                    break;
//                case '原材料报废':
//                    type = 29;
//                    break;
//                case '边角料报废':
//                    type = 30;
//                    break;
//                default :
//                    return;
//            }
            console.log(type);
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });

        //作废
        $(".content").delegate(".operate","click", function () {
            var a = $("#data").datagrid("getSelected");
            var _id=a['chh_id'];
            var bb= a['chh_status'];
            if(bb=="待提交"||bb=="驳回")
            {
                $.fancybox({
                    href: "<?=Url::to(['can-reason'])?>?id=" + _id,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 500,
                    height: 300
                });
            }
        });


        //修改
        $(".content").delegate(".edit","click", function () {
            var a=$("#data").datagrid("getSelected");
            var _id=a['chh_id'];
                window.location.href = "<?=Url::to('update')?>?id=" + _id;
        });

    });
</script>


