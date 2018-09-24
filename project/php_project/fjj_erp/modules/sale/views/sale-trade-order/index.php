<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2016/2/13
 * Time: 14:49
 */
use yii\helpers\Url;

$this->title = '交易订单查询';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '交易订单列表'];
?>
<style>
    .label-width {
        width: 80px;
    }

    .space-20 {
        height: 20px;
    }

    .width-100 {
        width: 100px;
    }

    .value-width {
        width: 150px;
    }

    .width-80 {
        width: 80px;
    }

    .width-130 {
        width: 130px;
    }

    .ml-25 {
        margin-left: 25px;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $search,
    ]); ?>
    <div class="space-20"></div>
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
    <script>
        var id;
        var flag = '';//子表选中标志

        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "ord_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                checkOnSelect: false,
                selectOnCheck: false,
                columns: [[
                    {field: "", checkbox: true, width: 200},
                    <?= $columns ?>
                    {
                        field: "action", title: "操作", width: 150, formatter: function (val, row) {
                        if (row.saph_status == '订单改价完成' || row.saph_status == '订单取消支付' || row.saph_status == '订单改价取消') {
                            return '<a onclick="cancel(' + row.ord_id + ')">取消订单</a>'
                        }else if (row.saph_status == '订单已付款' || row.saph_status == '部份已付款') {
                            if(row.is_rfnd =='否' || row.is_rfnd =='取消退款'){
                                return '<a onclick="cancel(' + row.ord_id + ')">取消订单</a>&nbsp;&nbsp;<a href="<?= Url::to(['/sale/ord-refund/create?id=']) ?>' + row.ord_id + '">退款处理</a>'
                            }
                            if(row.is_rfnd =='已退款' || row.is_rfnd =='审核通过'){
                                return '<a onclick="cancel(' + row.ord_id + ')">取消订单</a>&nbsp;&nbsp;<span>已退款处理</span>'
                            }
                            if(row.is_rfnd =='退款审核中'){
                                return '<a onclick="cancel(' + row.ord_id + ')">取消订单</a>&nbsp;&nbsp;<span>退款处理审核中</span>'
                            }

                        } else if ((row.saph_status == '部分已通知出货' || row.saph_status == '订单已出货' || row.saph_status == '订单备货中' || row.saph_status == '订单已收货' || (row.saph_status == '订单已取消' && row.yn_pay==1)) && (row.is_rfnd =='否' || row.is_rfnd =='取消退款')) {
                            return '<a href="<?= Url::to(['/sale/ord-refund/create?id=']) ?>' + row.ord_id + '">退款处理</a>'
                        }else if(row.is_rfnd =='已退款' || row.is_rfnd =='审核通过'){
                            return '<span>已退款处理</span>'
                        }else if(row.is_rfnd =='退款审核中' || row.is_rfnd =='退款审核中'){
                            return '<span>退款处理审核中</span>'
                        }
                    }
                    }
                ]],
                onSelect: function (rowIndex, rowData) {
                    var index = $("#data").datagrid("getRowIndex", rowData.ord_id);
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

                    $("#order_child_title").addClass("table-head mb-5 mt-20").html("<p class='head'>商品信息</p>");
                    $("#order_child_title2").addClass("table-head mb-5 mt-20 red").html("<p class='head' style='float: right'>总运费（含税）：<span style=\'color:#FF6600\'>￥ " + parseFloat(oderh["bill_freight"]).toFixed(2) + '</span>&nbsp;&nbsp;&nbsp;' + "商品总金额（含税）：<span style=\'color:#FF6600\'>￥" + parseFloat(oderh["bill_oamount"]).toFixed(2) + '</span>&nbsp;&nbsp;&nbsp;' + "订单总金额（含税）：<span style=\'color:#FF6600\'>￥" + (parseFloat(oderh["bill_freight"]) + parseFloat(oderh["bill_oamount"])).toFixed(2) + "</span></p>");
                    var id = oderh['ord_id'];
                    $("#order_child").datagrid({
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>?id=" + id,
                        rownumbers: true,
                        method: "get",
                        idField: "ord_dt_id",
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
                    if(rowData.saph_status == '订单已付款' || rowData.saph_status == '部分已通知出货' || rowData.saph_status == '部分已付款'){
                        $("#purchase-note-bak").show();
                        $("#purchase-note-bak").next().show();
                    }else{
                        $("#purchase-note-bak").hide();
                        $("#purchase-note-bak").next().hide();
                    }
                    if (rowData.saph_status == '订单已提交' || rowData.saph_status == '订单改价取消' || rowData.saph_status == '订单取消支付') {
                        $("#reprice").show();//订单改价
                        $("#reprice").next().show();
                    }else {
                        $("#reprice").hide();//订单改价
                        $("#reprice").next().hide();
                    }
                    if (rowData.saph_status == '订单改价完成' || rowData.saph_status == '订单取消支付' || rowData.saph_status == '订单已付款' || rowData.saph_status == '部份已付款' || rowData.saph_status == '订单改价取消') {
                        $("#cancel").show();//订单取消
                        $("#cancel").next().show();
                    }else {
                        $("#cancel").hide();//订单取消
                        $("#cancel").next().hide();
                    }
                    if (rowData.saph_status == '订单改价驳回') {
                        $("#reprice-cancel").show();//订单改价
                        $("#reprice-cancel").next().show();
                    }else {
                        $("#reprice-cancel").hide();//订单改价
                        $("#reprice-cancel").next().hide();
                    }
                    if (rowData.saph_status == '订单已付款' || rowData.saph_status == '部份已付款'||rowData.saph_status == '部分已通知出货') {
                        $("#out-note").show();//出库通知
                        $("#out-note").next().show();
                    }else {
                        $("#out-note").hide();//出库通知
                        $("#out-note").next().hide();
                    }
                    if (rowData.saph_status == '订单已收货') {
                        $("#fapiao").show();//发票申请
                        $("#fapiao").next().show();
                    }else {
                        $("#fapiao").hide();//发票申请
                        $("#fapiao").next().hide();
                    }
                        if (rowData.pac_sname == '信用额度支付' && (rowData.saph_status == '订单已提交' || rowData.saph_status == '订单改价取消'  || rowData.saph_status == '订单改价完成' )) {
                            $("#pay").show();//支付确认
                            $("#pay").next().show();
                        } else {
                            $("#pay").hide();//支付确认
                            $("#pay").next().hide();
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

                        $('#data').datagrid("unselectAll");
                        $("#cancel").show();//订单取消
                        $("#cancel").next().show();
                        $("#pay").hide();//支付确认
                        $("#pay").next().hide();
                        $("#purchase-note-bak").hide();
                        $("#purchase-note-bak").next().hide();
                    }
                    return false;
                }
                , onUncheck: function (rowIndex, rowData) {
                    var a = $("#data").datagrid("getChecked");
                    if (a.length == 1) {
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            isSelect = false;
                            onlyOne = true;
                            var b = $("#data").datagrid("getRowIndex", a[0].ord_id);
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
                        }
                    }
                },
                onCheckAll: function (rowIndex, rowData) {
                    $("#cancel").show();//订单取消
                    $("#cancel").next().show();
                    $("#pay").hide();//支付确认
                    $("#pay").next().hide();
                    $('#data').datagrid("unselectAll");
                },
                onUncheckAll: function (rowIndex, rowData) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#cancel").hide();//订单取消
                    $("#cancel").next().hide();
                    $("#pay").hide();//支付确认
                    $("#pay").next().hide();
                },
                onUnselectAll: function (rowIndex, rowData) {
                    $("#load-content").hide();
                    $("#cancel").hide();//订单取消
                    $("#cancel").next().hide();
                    $("#pay").hide();//支付确认
                    $("#pay").next().hide();
                    $("#purchase-note-bak").hide();
                    $("#purchase-note-bak").next().hide();
                },
                onLoadSuccess: function (data) {
                    $('#data').datagrid("clearSelections");
                    $('#data').datagrid("clearChecked");
                    setMenuHeight();
                    datagridTip('#data');
                    showEmpty($(this), data.total, 1, 1);
                }
            });

            $('#export').click(function () {
                var index = layer.confirm("确定导出订单信息?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?= Url::to(['index', 'export' => 1]) . '&' . http_build_query(Yii::$app->request->queryParams) ?>") {
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

            // 退款
//            $("#refund").click(function () {
//                var row = $("#data").datagrid("getSelected");
//                if (row == null) {
//                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
//                    return false;
//                }
//                var id = row.ord_id;
//                window.location.href = '<?//= Url::to(['refund?id='])?>//' + id;
//            });
            // 退款
            $("#reprice").click(function () {
                var row = $("#data").datagrid("getSelected");
                if (row == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                var id = row.ord_id;
                window.location.href = '<?= Url::to(['reprice?id='])?>' + id;
            });

            // 出货通知
            $("#out-note").click(function () {
                var a = $("#data").datagrid("getSelected");
                if (a == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                $.fancybox({
                    href: "<?=Url::to(['out-note?id='])?>" + a.ord_id,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 520,
                    'onCancel': function () {
                        layer.alert("该订单没有可发送的通知!", {icon: 2, time: 5000});
                    },
                });
            });
            // 请购
            $("#purchase-note-bak").click(function () {
                var a = $("#data").datagrid("getSelected");
                if (a == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                window.location.href = "<?=Url::to(['/purchase/purchase-apply/specific-form?id='])?>" + a.ord_id;
            });
            // 批量取消
            $("#cancel").on("click", function () {
                cancelId = '';
                var checked = $("#data").datagrid("getChecked");
                var selected = $("#data").datagrid("getSelected");
                if (checked.length == 0 && selected == null) {
                    layer.alert("请点击选择交易订单！", {icon: 2, time: 3000});
                    return false;
                }
                if (checked != []) {
                    for (var i = 0; i < checked.length; i++) {
                        cancelId += checked[i].ord_id + ',';
                    }
                }
                console.log(selected);
                if (selected != null) {
                    cancelId = selected.ord_id;
                }
                cancel(cancelId);
            });
            // 批量取消改价
            $("#reprice-cancel").on("click", function () {
                var cancel_id = '';
                var checked = $("#data").datagrid("getChecked");
                var selected = $("#data").datagrid("getSelected");
                if (checked.length == 0 && selected == null) {
                    layer.alert("请点击选择交易订单！", {icon: 2, time: 3000});
                    return false;
                }
                if (checked != []) {
                    for (var i = 0; i < checked.length; i++) {
                        cancel_id += checked[i].ord_id + ',';
                    }
                }
                if (selected != null) {
                    cancel_id = selected.ord_id;
                }
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {
                        "custId": cancel_id,
                        "type": 12
                    },
                    url: "<?=Url::to(['reprice-cancel'])?>" + "?id=" + cancel_id,
                    success: function (data) {
                        if (data.status == 1) {
                            layer.alert(data.msg, {
                                icon: 1, end: function () {
                                    window.location.reload();
                                }
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                });
            });
            // 支付确认
            $("#pay").on("click", function () {
                var selected = $("#data").datagrid("getSelected");
                if (selected == null) {
                    layer.alert("请点击选择交易订单！", {icon: 2, time: 3000});
                    return false;
                }
                var payId = selected.ord_id;
                $.fancybox({
                    href: "<?=Url::to(['pay-box'])?>" + "?id=" + payId,
                    type: "iframe",
                    padding: 0,
                    width: 540,
                    height: 350,
                });
//                $.ajax({
//                    type: "get",
//                    dataType: "json",
//                    async: false,
//                    data: {
//                        "custId": id,
//                        "type": 12
//                    },
//                    url: "<?//=Url::to(['order-pay'])?>//" + "?id=" + payId,
//                    success: function (data) {
//                        if (data.flag == 1) {
//                            layer.alert(data.msg, {
//                                icon: 1, end: function () {
//                                    window.location.reload();
//                                }
//                            });
//                        } else {
//                            layer.alert(data.msg, {icon: 2});
//                        }
//                    }
//                });
            });
        });

        // 取消
        function cancel(id) {
            cancelId = id;
            $.fancybox({
                href: "<?=Url::to(['cancel-box'])?>" + "?id=" + id,
                type: "iframe",
                padding: 0,
                width: 500,
                height: 300,
            });
        }
    </script>