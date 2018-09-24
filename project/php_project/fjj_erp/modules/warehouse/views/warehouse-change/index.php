<?php
/**
 * User: F1676624
 * Date: 2017/7/22
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;

$this->title = '仓库异动列表';

$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style>
    .table th {
        height: 26px;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'get' => $get,
        'downlist' => $downlist,
        'whname'=>$whname,
//        'statusType' => $statusType,
    ]); ?>
    <div class="space-20" style="height: 20px;"></div>
    <div class="table-content">
        <div class="table-head">
            <?php echo $this->render('_action'); ?>
        </div>
    </div>
    <div class="space-10"></div>
    <div style="clear: right;"></div>
    <div id="data"></div>
    <div id="load-content" class="overflow-auto"></div>
    <div id="order_child_title"></div>
    <div id="order_child" style="width:100%;"></div>
    <div id="order_child2" style="width:100%;"></div>
</div>
<div class="space-30"></div>
<script>
    $(function () {
        var id;
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
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
            columns: [[
                {field: 'ck', checkbox: true, align: 'left'},
                {field: "chh_code", width: 100, title: "异动单号"},
                {field: "chh_typeName", width: 80, title: "异动类型"},
                {field: "wh_name", width: 130, title: "出仓名称"},
                {field: "wh_name2", width: 145, title: "入仓名称"},
                {field: "chh_status", width: 80, title: "状态"},
                {field: "create_by", width: 80, title: "操作人"},
                {field: "create_at", width: 80, title: "操作日期"},
                {field: "review_by", width: 80, title: "确认人"},
                {field: "review_at", width: 80, title: "确认日期"},
                {
                    field: 'chh_id', title: '操作', width: 80, align:'center', formatter: function (value, rowData) {
                    var str = "<i>";
                    if (rowData.chh_status == "待提交" || rowData.chh_status == "驳回") {
                        <?php if(Menu::isAction('/warehouse/warehouse-change/update')){?>
                        str += "<a class='icon-check-minus  icon-large' style='margin-right:15px;' title='删除' onclick='event.stopPropagation();deleteStockIn(" + value + ");'></a>";
                        <?php }?>
                        <?php if(Menu::isAction('/warehouse/warehouse-change/delete')){?>
                        str += "<a class='icon-edit icon-large' style='margin-right:15px;' title='修改' onclick='location.href=\"<?=Url::to(['update'])?>?id=" + value + "\";event.stopPropagation();'></a>";
                        <?php }?>
                    }
                    return str;
                }
                }
            ]],
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);

                //每一个仓库代码详情
                $("#view").on("click", function () {
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
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
//                    $("#update,#delete,#view,.take_inch,.take_investment,.take_record,.take_sea,#apply").show();
                if (rowData.chh_status == '待提交' || rowData.chh_status == '驳回') {
                    $("#update").show();
                    $("#update").next().show();
                    $("#delete").show();
                    $("#delete").next().show();
                } else {
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#delete").hide();
                    $("#delete").next().hide();
                }
                if (rowData.chh_status == '审核中'||rowData.chh_status == '审核完成') {
                    $("#check").hide();
                    $("#check").next().hide();
                } else {
                    $("#check").show();
                    $("#check").next().show();
                }

                if(rowData.chh_status == '审核完成' && rowData.chh_typeName == '移仓管理'){
                    $("#check").hide();
                    $("#check").next().hide();
                    $("#inware_btn").show();
                    $("#inware_btn").next().show();
                }else {
                    $("#inware_btn").hide();
                    $("#inware_btn").next().hide();
                }

//                    $("#data").datagrid("uncheckAll");
                var oderh = $("#data").datagrid("getSelected");
                $("#order_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>商品信息</p>");
                var id = oderh['chh_id'];
                var type = oderh['chh_type'];
                if (type == 41) {
                    $("#order_child2").html("").hide();
                    $("#order_child").datagrid({
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                        rownumbers: true,
                        method: "get",
                        idField: "chl_id",
                        singleSelect: true,
                        pagination: true,
                        pageSize: 10,
                        pageList: [10, 20, 30],
                        columns: [[
                            {field: "pdt_no", title: "料号", width: 150},
                            {field: "pdt_name", title: "商品名称", width: 150},
                            {field: "tp_spec", title: "规格型号", width: 120},
                            {field: "st_id", title: "异动前储位", width: 150},
                            {field: "st_id2", title: "异动后储位", width: 150},
                            {field: "chl_num", title: "异动数量", width: 150},
                            {field: "unit", title: "单位", width: 86}
                        ]],
                        onLoadSuccess: function (data) {
                            showEmpty($(this), data.total, 0);
                            setMenuHeight();
                            $("#order_child").datagrid('clearSelections');
                            datagridTip("#order_child");
                        },
                        onSelect: function (rowIndex, rowData) {
//                            if (rowData.sil_id == flag) {
//                                $("#order_child").datagrid('clearSelections');
//                                flag = '';
//                            } else {
//                                flag = rowData.sil_id;
//                            }
                        }
                    });
                } else if (type == 42) {
                    $("#order_child_title").next().hide();
                    var table;
                    $("#order_child2").html("").show();
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                        success: function (msg) {
                            console.log(msg);
                            table = '<table class="table">' +
                                '<thead>' +
                                '<tr>' +
                                '<th class="width-40">序号</th>' +
                                '<th class="width-150">料号</th>' +
                                '<th class="width-120">商品名称</th>' +
                                '<th class="width-120">规格型号</th>' +
                                '<th class="width-120">仓库</th>' +
                                '<th class="width-120">储位</th>' +
                                '<th class="width-120">库存量</th>' +
                                '<th class="width-80">异动数量</th>' +
                                '<th class="width-80">异动后库存</th>' +
                                '<th class="width-80">单位</th>' +
                                '</tr>' +
                                '</thead>' +
                                '<tbody id="product_table">';
                            for (var i = 0; i < msg.length; i++) {
                                table += '<tr>' +
                                    '<td rowspan="2">异前<span class="xunhao">' + (i + 1) + '</span>' +
                                    '<div class="space-10"></div>异后<span class="xunhao">' + (i + 1) + '</span></td>' +
                                    '<td><p class="wd100  text-center">' + msg[i].pdt_no +
                                    '</p></td>' +
                                    '<td class="pdt_name"><p class="wd100">' + msg[i].pdt_name + '</p></td>' +
                                    '<td class="tp_spec"><p class="wd100">' + msg[i].tp_spec + '</p></td>' +
                                    '<td class="whhouse"><p class="wd100">' + msg[i].wh_id + '</p></td>' +
                                    '<td class="store1"><p class="wd100">' + msg[i].st_id + '</p></td>' +
                                    '<td class="L_invt_num"><p class="wd100">' + msg[i].before_num1 + '</p></td>' +
                                    '<td><p class=" wd100  text-center">' + msg[i].chl_num + '</p></td>' +
                                    '<td class=""><p class="wd100">' + (msg[i].before_num1 - msg[i].chl_num) + '</p></td>' +
                                    '<td class=""><p class="wd100">' + msg[i].unit + '</p></td>' +
                                    '</tr>' +
                                    '<tr>' +
                                    '<td><p class=" wd100  text-center">' + msg[i].part_no2 +
                                    '</p></td>' +
                                    '<td class="pdt_name"><p class="wd100">' + msg[i].pdt_name2 + '</p></td>' +
                                    '<td class="tp_spec"><p class="wd100">' + msg[i].tp_spec2 + '</p></td>' +
                                    '<td class="whhouse"><p class="wd100">' + msg[i].wh_id2 + '</p></td>' +
                                    '<td class="store1"><p class="wd100">' + msg[i].st_id2 + '</p></td>' +
                                    '<td class="L_invt_num"><p class="wd100">' + msg[i].before_num2 + '</p></td>' +
                                    '<td class=""><p class="wd100">' + msg[i].chl_num + '</p></td>' +
                                    '<td class=""><p class="wd100">' + (parseFloat(msg[i].before_num2) + parseFloat(msg[i].chl_num)) + '</p></td>' +
                                    '<td class="unit"><p class="wd100">' + msg[i].unit2 + '</p></td>' +
                                    '</tr>';
                            }
                            table += '</tbody></table>';
                            $("#order_child2").append(table);
                        },
                        error: function (msg) {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    });

                } else if (type == 43) {
                    $("#order_child2").html("").hide();
                    $("#order_child").datagrid({
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                        rownumbers: true,
                        method: "get",
                        idField: "chl_id",
                        singleSelect: true,
                        pagination: true,
                        pageSize: 10,
                        pageList: [10, 20, 30],
                        columns: [[
                            {field: "pdt_no", title: "料号", width: 150},
                            {field: "pdt_name", title: "商品名称", width: 150},
                            {field: "brand", title: "品牌", width: 150},
                            {field: "tp_spec", title: "规格型号", width: 120},
                            {field: "st_id", title: "移仓前储位", width: 75},
                            {field: "st_id2", title: "移仓后储位", width: 75},
                            {field: "chl_num", title: "移仓数量", width: 150},
                            {field: "unit", title: "单位", width: 86}
                        ]],
                        onLoadSuccess: function (data) {
                            showEmpty($(this), data.total, 0);
                            setMenuHeight();
                            $("#order_child").datagrid('clearSelections');
                            datagridTip("#order_child");
                        },
                        onSelect: function (rowIndex, rowData) {
//                            if (rowData.sil_id == flag) {
//                                $("#order_child").datagrid('clearSelections');
//                                flag = '';
//                            } else {
//                                flag = rowData.sil_id;
//                            }
                        }
                    });
                }
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                }
                else if(a1.length == 0){
                    $("#order_child_title").hide().next().hide();
                }
                else {
                    for (i=0;i<a1.length;i++){
                        if (a1[i]['chh_status']!="待提交" && a1[i]['chh_status']!="驳回"){
                            $("#update").hide().next().hide();
                            $("#check").hide().next().hide();
                            $("#delete").hide().next().hide();
                            $("#inware_btn").hide().next().hide();
                            break;
                        }
                    }
                    $("#order_child_title").hide().next().hide();
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].chh_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    $("#update").hide().next().hide();
                    $("#check").hide().next().hide();
                    $("#delete").hide().next().hide();
                    $("#inware_btn").hide().next().hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#order_child_title").hide().next().hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else{
                    var a1 = $("#data").datagrid("getChecked");
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#child-title").hide().next().hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            }
        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });
        $("#add").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>";
        });
        $("#update").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['chh_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        });
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条申请信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['chh_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        //删除
        $("#delete").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['chh_id'];
                var index = layer.confirm("确定要删除这条记录吗?",
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": id},
                            url: "<?=Url::to(['/warehouse/allocation/delete']) ?>",
                            success: function (msg) {
                                if (msg.flag === 1) {
                                    layer.alert(msg.msg, {
                                        icon: 1, end: function () {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    layer.alert(msg.msg, {icon: 2})
                                }
                            },
                            error: function (msg) {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            }
        });
        //导出
        $('#export').click(function () {
            var obj = $("#data").datagrid('getData');
            if (obj.total == 0) {
                layer.alert('不可导出！', {icon: 2, time: 5000});
                return false;
            }
            layer.confirm('确定导出吗？', {icon: 2},
                function () {
                    layer.closeAll();
                    window.location.href = "<?=Url::to(['export']) . '?' . http_build_query(Yii::$app->request->queryParams)?>";
                },
                layer.close()
            );
        });
        $("#check").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
            if (data['chh_status'] == 30) {
                layer.alert("正在审核中", {icon: 2, time: 5000});
                return false;
            }
            if (data['chh_status'] == 40) {
                layer.alert("已审核完成", {icon: 2, time: 5000});
                return false;
            }
            var id = data['chh_id'];
            var url = "<?=Url::to(['view'], true)?>?id=" + id;
            var type = data['chh_type'];
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });

        //移仓入库通知
        $("#inware_btn").on("click",function(){
            //var ck=new Array();
            var a = $("#data").datagrid("getChecked");
            if(a == null||a.length>1){
                layer.alert("请选择一条信息!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getChecked")[0]['chh_id'];
                var index = layer.confirm("确定生成入库通知?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": id},
                            url: "<?=Url::to(['in-ware']) ?>",
                            success: function (msg) {
                                if( msg.flag === 1){
                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                }else{
                                    layer.alert(msg.msg,{icon:2})
                                }
                            },
                            error :function(msg){
                                layer.alert(msg.msg,{icon:2})
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            }
        })


    });
    //输入id删除
    function deleteStockIn(id) {
        layer.confirm('确定删除吗？', {icon: 2},
            function () {
                $.ajax({
                    url: "<?=Url::to(['/warehouse/allocation/delete'])?>",
                    data: {
                        "id": id
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.flag == 1) {
//                            layer.alert(data.msg,{icon:1},function(){
//                                layer.closeAll();
//                                $("#main_table").datagrid('load').datagrid('clearSelections');
//                                $("#child_table_title").hide().next().hide();
//                            });\
                            layer.alert("删除成功！", {
                                icon: 1, end: function () {
                                    location.reload();
                                }
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                });
            },
            function () {
                layer.closeAll();
            }
        );
    }
</script>


