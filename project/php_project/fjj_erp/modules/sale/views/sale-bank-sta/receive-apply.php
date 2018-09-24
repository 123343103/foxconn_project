<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2018/1/8
 * Time: 下午 07:41
 */
use \yii\helpers\Url;

?>
<style>
    table.tb1 {
        border: 1px solid #000000;
        margin-top: 20px;
        margin-left: 40px;
        width: 670px;
        height: 380px;
    }

    div#div1 {
        height: auto;
        width: 469px;
        position: absolute;
        left: -1px;
        top: -1px;
        text-align: center;
        z-index: 1;
        /*margin-bottom: 40px;*/
    }

    table.tb2 {
        border: 1px solid #C0EBEF;
        width: 100%;
        height: auto;
    }

    .text-deal {
        word-break: break-all;
        padding-top: 5px;
    }
</style>
<div>
    <input type="hidden" value="<?= $rbo_id ?>" id="rbo_id">
    <table class="tb1">
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">流水号：</td>
            <td style="width: 70%;height: 30px;">
                <?= $data['TRANSID'] ?>
                <input type="hidden" value="<?= $data['TRANSID'] ?>" id="trandsid">
            </td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">银行：</td>
            <td style="width: 70%;height: 30px;"><?= $data['BNK_NME'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">对方账户：</td>
            <td style="width: 70%;height: 30px;"><?= $data['OPPNAME'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">发生时间：</td>
            <td style="width: 70%;height: 30px;"><?= $data['TRDATE'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">附言：</td>
            <td style="width: 70%;word-break: break-all"><?= $data['POSTSCRIPT'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">摘要：</td>
            <td style="width: 70%;word-break: break-all"><?= $data['INTERINFO'] ?></td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">收款金额：</td>
            <td style="width: 70%;height: 30px;" id="txnant"><?= $data['TXNAMT'] ?></td>
        </tr>
        <tr>
            <input type="hidden" value="" id="ord_pay_id">
            <td style="width: 30%;text-align: right;height: 41px;"><label class="red">*</label>订单编号：</td>
            <td style="width: 70%;height: 41px;">
                <div id="order_no" style="width: 370px;float: left;" class="text-deal"></div>
                <div style="width: 18%;float: left;height: 100%;position: relative;">
                    <button type="button" class="search-btn-blue" id="search" style="position: absolute;top: 20%;">查詢
                    </button>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 30%;text-align: right;height: 30px;">订单金额：</td>
            <td style="width: 70%;height: 30px;position: relative;">
                <span id="orderMoney"></span>
                <div id="div1">
                    <table id="data"></table>
                    <div style="border:1px solid #C0EBEF;background-color: white">
                        <div style="text-align: left;;margin-top:25px;margin-left: 5px;width: 100%;"><span>总金额：<span
                                    id="totalMoney">0.000</span></span>
                        </div>
                        <div style="text-align: left;margin-left: 5px"><label class="red" id="tipInfo"></label></div>

                        <div style="margin-top: 70px;">
                            <button type="button" class="button-blue-small" id="addOrder">添加</button>
                            <button type="button" class="button-white-small" id="cannel">取消</button>
                        </div>
                        <div style="height: 22px;"></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 30%;height: 95px;text-align: right">备注说明：</td>
            <td style="width: 70%;"><textarea
                    style="width: 95%;height: 80px;margin-left: 10px;" id="remark"></textarea></td>
        </tr>
    </table>

    <div style="text-align: center;margin-top: 20px;margin-bottom: 20px;">
        <button type="button" class="button-blue-big" onclick="Bind()">绑定</button>
        <button type="button" class="button-white-big" onclick="close_select()">取消</button>
    </div>
    <!--    <div id="ppt" style="width: 468px;"></div>-->
</div>
<script>
    $(document).ready(function () {
            $("#div1").hide();
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: false,
                method: "get",
                idField: "ord_pay_id",
                loadMsg: false,
                pagination: true,
                pageSize: 5,
                singleSelect: true,
                selectOnCheck: false,
                checkOnSelect: false,
                pageList: [5, 10, 20, 30, 40],
                columns: [[
                    {field: "ck", checkbox: true, width: 50},
                    {field: "ord_no", title: "订单号", width: 250},
                    {field: "pay_type", title: "订单金额", width: 180,formatter:function (value,row) {
                        if(value==1)
                        {
                            return row.stag_cost;
                        }
                        else {
                            return row.req_tax_amount;
                        }
                    }},
                ]],
                onLoadSuccess: function (data) {
//                datagridTip("#data");
                    showEmpty($(this), data.total, 0);
                    setMenuHeight();
                },
                onClickRow: function () {
                    $("#data").datagrid("unselectAll");
                },
                onCheck: function () {
                    var tr = $("#data").datagrid("getChecked");
                    var totalMoney = 0.000;
                    var txnant = parseFloat($("#txnant").text());
                    for (var i = 0; i < tr.length; i++) {
                        totalMoney = (totalMoney * 1000 + parseFloat(tr[i].req_tax_amount) * 1000) / 1000;
                    }
                    $("#totalMoney").text(totalMoney.toFixed(3));
                    $("#addOrder").attr("disabled", false);
                    if (totalMoney - txnant > 20) {
                        $("#tipInfo").text("提示:订单总金额-流水金额不能大于20!");
                        $("#addOrder").attr("disabled", true);
                    }
                    else {
                        $("#tipInfo").text("");
                        $("#addOrder").attr("disabled", false);
                    }
                },
                onUncheck: function () {
                    var tr = $("#data").datagrid("getChecked");
                    var totalMoney = 0.00;
                    var txnant = parseFloat($("#txnant").text());
                    for (var i = 0; i < tr.length; i++) {
                        totalMoney = (totalMoney * 1000 + parseFloat(tr[i].req_tax_amount) * 1000) / 1000;
                    }
                    $("#totalMoney").text(totalMoney.toFixed(3));
                    if (totalMoney - txnant > 20) {
                        $("#tipInfo").text("提示:订单总金额-流水金额不能大于20!");
                        $("#addOrder").attr("disabled", true);
                    }
                    else {
                        $("#tipInfo").text("");
                        $("#addOrder").attr("disabled", false);
                    }
                    if (tr.length == 0) {
                        $("#tipInfo").text("");
                    }
                },
                onCheckAll: function () {
                    var tr = $("#data").datagrid("getChecked");
                    var totalMoney = 0.00;
                    var txnant = parseFloat($("#txnant").text());
                    for (var i = 0; i < tr.length; i++) {
                        totalMoney = (totalMoney * 1000 + parseFloat(tr[i].req_tax_amount) * 1000) / 1000;
                    }
                    $("#totalMoney").text(totalMoney.toFixed(3));
                    if (totalMoney - txnant > 20) {
                        $("#tipInfo").text("提示:订单总金额-流水金额不能大于20!");
                        $("#addOrder").attr("disabled", true);
                    }
                    else {
                        $("#tipInfo").text("");
                        $("#addOrder").attr("disabled", false);
                    }
                },
                onUncheckAll: function () {
                    var tr = $("#data").datagrid("getChecked");
                    var totalMoney = 0.00;
                    var txnant = parseFloat($("#txnant").text());
                    for (var i = 0; i < tr.length; i++) {
                        totalMoney = (totalMoney * 1000 + parseFloat(tr[i].req_tax_amount) * 1000) / 1000;
                    }
                    $("#totalMoney").text(totalMoney.toFixed(3));
                    if (totalMoney - txnant > 20) {
                        $("#tipInfo").text("提示:订单总金额-流水金额不能大于20!");
                        $("#addOrder").attr("disabled", true);
                    }
                    else {
                        $("#tipInfo").text("");
                        $("#addOrder").attr("disabled", false);
                    }
                    if (tr.length == 0) {
                        $("#tipInfo").text("");
                    }
                }
            })
            //查询未付款订单(初始化)
            $("#search").click(function () {
                $("#div1").show();
                $("#data").datagrid("resize");
                var tr = $("#data").datagrid("getChecked");
                if (tr.length == 0) {
                    $("#tipInfo").text("");
                    $("#addOrder").attr("disabled", true);
                }
                else {
                    var totalMoney = 0.00;
                    var txnant = parseFloat($("#txnant").text());
                    for (var i = 0; i < tr.length; i++) {
                        totalMoney = (totalMoney * 1000 + parseFloat(tr[i].req_tax_amount) * 1000) / 1000;
                    }
                    $("#totalMoney").text(totalMoney.toFixed(3));
                    if (totalMoney - txnant > 20) {
                        $("#tipInfo").text("提示:订单总金额-流水金额不能大于20!");
                        $("#addOrder").attr("disabled", true);
                    }
                    else {
                        $("#tipInfo").text("");
                        $("#addOrder").attr("disabled", false);
                    }
                }
            })
            //查询未付款订单(取消)
            $("#cannel").click(function () {
                $("#div1").css("display", "none");
            })
            //查询未付款订单(添加)
            $("#addOrder").click(function () {
                var tr = $("#data").datagrid("getChecked");
                var trArr = [];
                var result = [];
                var orderno = "";
                var ord_pay_id = "";
                if (tr.length == 0) {
                    $("#order_no").text("");
                    alert("请选择订单后再继续操作!");
                    return false;
                }
                for (var i = 0; i < tr.length; i++) {
                    if (i == tr.length - 1) {
                        ord_pay_id += tr[i].ord_pay_id;
                    }
                    else {
                        ord_pay_id += tr[i].ord_pay_id + ";";
                    }
                    trArr.push(tr[i].ord_no);
                }
                result = trArr.distinct();
                for (var j = 0; j < result.length; j++) {
                    if (j == result.length - 1) {
                        orderno += result[j];
                    }
                    else {
                        orderno += result[j] + ";";
                    }
                }
                $("#order_no").text(orderno);
                if ($("#totalMoney").text() != 0.000) {
                    $("#orderMoney").text($("#totalMoney").text());
                }

                $("#ord_pay_id").val(ord_pay_id);
                $("#div1").hide();
            })
        }
    )
    function Bind() {
        var transid = $("#trandsid").val();
        var order_no = $("#order_no").text();
        var url = "<?=Url::to(['index'])?>";
        var remark = $("#remark").val();
        var rbo_id = $("#rbo_id").val();
        var ord_pay_id = $("#ord_pay_id").val();
        var ordMoney = $("#orderMoney").text();
        if (order_no == "") {
            alert("订单号不能为空!");
            return false;
        }
        window.location.href = "bind-bank-trans?transid=" + transid + "&orderlist=" + order_no + "&address=" + url + "&remark=" + remark  + "&rbo_id=" + rbo_id + "&ord_pay_id=" + ord_pay_id + "&ordMoney=" + ordMoney;
    }
    //訂單去重
    Array.prototype.distinct = function () {
        var arr = this,
            result = [],
            i,
            j,
            len = arr.length;
        for (i = 0; i < len; i++) {
            for (j = i + 1; j < len; j++) {
                if (arr[i] === arr[j]) {
                    j = ++i;
                }
            }
            result.push(arr[i]);
        }
        return result;
    }
</script>

