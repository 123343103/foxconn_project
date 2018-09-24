<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/2/13
 * Time: 14:49
 */
use yii\helpers\Url;

$actionId = Yii::$app->controller->action->id;
$this->title = '客户需求单列表';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '客戶需求单列表'];

$queryParam = Yii::$app->request->queryParams;
//dumpE($businessType);
?>
<style>
    .label-width {
        width: 80px;
    }

    .value-width {
        width: 130px;
    }

    .space-20 {
        height: 20px;
    }

    .width-100 {
        width: 100px;
    }

    .width-110 {
        width: 110px;
    }

    .text-no-next {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: inline-block;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $search,
    ]); ?>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
    <div id="load-content">
        <div id="order_child_title" style="height: 25px;margin-top: 5px"></div>
        <div id="order_child" style="width:100%;"></div>
        <div id="order_child_title2" style="height: 5px;margin-top: 5px"></div>
    </div>
</div>
<script>
    var id;
    var flag = '';//子表选中标志
    var cancelId = ''; // 要取消项的id 一个或多个 逗号隔开的字符串
    var actionId = "<?= $actionId ?>";

    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "req_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                {field: "", checkbox: true, width: 200},
                <?= $columns ?>
                {
                    field: "handle", title: "操作", width: "60", formatter: function (value, row, index) {
                    if (row.status == '待报价') { // 客户需求单操作
                        return '<span onclick="cancel(' + row.req_id + ')"><span class="open-close cursor-pointer" style="margin-left: 0px;margin-top: 3px;" title="取消"></span>&nbsp;&nbsp;</span> <a onclick="updateOne(' + row.req_id + ')"><i class="icon-edit icon-l cursor-pointer" title="修改"></i>&nbsp;&nbsp;</a>';
                    }
                }
                }
            ]],
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.req_id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                }
                isCheck = false;
                $('#data').datagrid('checkRow', index);
                $("#load-content").show();
                var oderh = $("#data").datagrid("getSelected");
//                $("#data").datagrid("uncheckAll");
//                $("#data").datagrid("checkRow",rowIndex);
                $("#order_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>商品信息</p>");
                $("#order_child_title2").addClass("table-head mb-5 mt-20 red").html("<p class='head' style='float: right'>总运费（含税）：<span style=\'color:#FF6600\'>￥ " + parseFloat(oderh["bill_freight"]).toFixed(2) + '</span>&nbsp;&nbsp;&nbsp;' + "商品总金额（含税）：<span style=\'color:#FF6600\'>￥" + parseFloat(oderh["bill_oamount"]).toFixed(2) + '</span>&nbsp;&nbsp;&nbsp;' + "订单总金额（含税）：<span style=\'color:#FF6600\'>￥" + (parseFloat(oderh["bill_freight"]) + parseFloat(oderh["bill_oamount"])).toFixed(2) + "</span></p>");
                var id = oderh['req_id'];
                $("#order_child").datagrid({
                    url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>" + "?id=" + id,
                    rownumbers: true,
                    method: "get",
                    idField: "req_id",
                    singleSelect: true,
                    pagination: true,
                    pageSize: 10,
                    pageList: [10, 20, 30],
                    columns: [[
                        <?= $child_columns ?>
                    ]],
                    onLoadSuccess: function (data) {
                        showEmpty($(this), data.total, 1, 1);
                        setMenuHeight();
                        $("#order_child").datagrid('clearSelections');
                        datagridTip("#order_child");
                    },
                    onSelect: function (rowIndex, rowData) {
                        if (rowData.sil_id == flag) {
                            $("#order_child").datagrid('clearSelections');
                            flag = '';
                        } else {
                            flag = rowData.sil_id;
                        }
                    }
                });
                if (rowData.status == '待报价') {
                    $("#quoted").show();
                    $("#quoted").next().show();
                    $("#update").show();
                    $("#update").next().show();
                    $("#cancel-order").show();
                    $("#cancel-order").next().show();
                }
                if (rowData.status == '已转报价') {
                    $("#quoted").hide();
                    $("#quoted").next().hide();
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#cancel-order").hide();
                    $("#cancel-order").next().hide();
                }
                if (rowData.status == '已取消') {
                    $("#quoted").hide();
                    $("#quoted").next().hide();
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#cancel-order").hide();
                    $("#cancel-order").next().hide();
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
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $("#quoted").hide();
                    $("#quoted").next().hide();
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#cancel-order").show();
                    $("#cancel-order").next().show();
                    $('#data').datagrid("unselectAll");
                }
                return false;
            }
            ,
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].req_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $("#quoted").hide();
                $("#quoted").next().hide();
                $("#update").hide();
                $("#update").next().hide();
                $("#cancel-order").show();
                $("#cancel-order").next().show();
                $('#data').datagrid("unselectAll");
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#load-content").hide();
            },
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this), data.total, 1, 1);
            }
        });
        $("#add").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>";
        });
        $("#update").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['req_id'];
                updateOne(id);
//                window.location.href = "<?//=Url::to(['update'])?>//?id=" + id + '&action=' + "<?//= $actionId?>//";
            }
        });
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['req_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        $("#quoted").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['req_id'];
                window.location.href = "<?=Url::to(['to-quoted'])?>?id=" + id;
            }
        });
//        $("#delete").on("click", function () {
//            var a = $("#data").datagrid("getSelected");
//            if (a == null) {
//                layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
//            } else {
//                var id = $("#data").datagrid("getSelected")['req_id'];
//                var index = layer.confirm("確定要刪除這條記錄嗎?",
//                    {
//                        btn: ['確定', '取消'],
//                        icon: 2
//                    },
//                    function () {
//                        $.ajax({
//                            type: "get",
//                            dataType: "json",
//                            async: false,
//                            data: {"id": id},
//                            url: "<?//=Url::to(['delete']) ?>//",
//                            success: function (msg) {
//                                if (msg.flag === 1) {
//                                    layer.alert(msg.msg, {
//                                        icon: 1, end: function () {
//                                            location.reload();
//                                        }
//                                    });
//                                } else {
//                                    layer.alert(msg.msg, {icon: 2})
//                                }
//                            },
//                            error: function (msg) {
//                                layer.alert(msg.msg, {icon: 2})
//                            }
//                        })
//                    },
//                    function () {
//                        layer.closeAll();
//                    }
//                )
//            }
//        });
        $('#export').click(function () {
            var index = layer.confirm("确定导出订单信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to([Yii::$app->controller->action->id, 'export' => 1]) . '&' . http_build_query(Yii::$app->request->queryParams) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出订单信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });

        $("#check").on("click", function () {

            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
            if (data['saph_status'] == '审核中') {
                layer.alert("正在审核中", {icon: 2, time: 5000});
                return false;
            }
            if (data['saph_status'] == '已报价') {
                layer.alert("已审核完成", {icon: 2, time: 5000});
                return false;
            }
            var id = data['req_id'];
            var url = "<?=Url::to(['view'], true)?>?id=" + id;
            var orderType = data['saph_type'];
            // 单据审核业务类型
            var tpList = <?= $businessType ?>;
            for ($k in tpList) {
                if (orderType == tpList[$k]) {
                    var type = $k;
                    break;
                }
            }
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });

        // 取消送审 （需求变化没有取消送审功能了）
//            $("#cancel-check").on("click", function () {
//                var data = $("#data").datagrid("getSelected");
//                if (data == null) {
//                    layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
//                    return false;
//                }
//                if (data['saph_status'] == '已报价') {
//                    layer.alert("已审核完成,不能取消", {icon: 2, time: 5000});
//                    return false;
//                }
//                if (data['saph_status'] == '报价驳回') {
//                    layer.alert("报价已驳回,无需取消", {icon: 2, time: 5000});
//                    return false;
//                }
//                var id = data['req_id'];
//                var orderType = data['saph_type'];
//                // 单据审核业务类型
//                var tpList = <?//= $businessType ?>//;
//                for ($k in tpList) {
//                    if (orderType == tpList[$k]) {
//                        var type = $k;
//                        break;
//                    }
//                }
//                $.ajax({
//                    type: "get",
//                    dataType: "json",
//                    url: "<?//=Url::to(['/system/verify-record/cancel-check'])?>//?id=" + id + "&type=" + type,
//                    success: function (msg) {
//                        console.log(msg)
//                        if (msg.flag == 1) {
//                            layer.alert(msg.msg, {
//                                icon: 1, end: function () {
//                                    location.reload();
//                                }
//                            });
//                        } else {
//                            layer.alert(msg.msg, {icon: 2})
//                        }
//                    },
//                    error: function (msg) {
//                        layer.alert(msg.msg, {icon: 2})
//                    }
//                })
//            });

        // 批量取消
        $("#cancel-order").on("click", function () {
            cancelId = '';
            var checked = $("#data").datagrid("getChecked");
            var selected = $("#data").datagrid("getSelected");
            if (checked.length == 0 && selected == null) {
                layer.alert("请点击选择需求单！", {icon: 2, time: 3000});
                return false;
            }
            if (checked != []) {
                for (var i = 0; i < checked.length; i++) {
                    cancelId += checked[i].req_id + ',';
                }
            } else if (selected.req_id != "") {
                cancelId = selected.req_id;
            }
            cancel(cancelId);
        });
    });

    // 取消
    function cancel(id) {
        cancelId = id;
        $.fancybox({
            href: "<?=Url::to(['cancel-box'])?>",
            type: "iframe",
            padding: 0,
            width: 500,
            height: 300,
        });
    }

    // 更新
    function updateOne(id) {
        window.location.href = "<?=Url::to(['update'])?>?id=" + id + '&action=' + "<?= $actionId?>";
    }
</script>