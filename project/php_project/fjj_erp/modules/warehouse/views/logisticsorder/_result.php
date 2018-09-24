<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/11
 * Time: 下午 02:39
 */
use yii\helpers\Url;
?>
<div id="data">
</div>
<div id="check_in_info_tab"></div>
<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "ord_lg_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            pageSize: 10,
            pageList: [10, 20, 30],
            columns: [[
                {field: "ck", checkbox: true},
                {field: "lg_no", title: "物流订单号", width: 150,formatter:function (value,rowData,rowIndex) {
                    return '<a href="<?=Url::to(['/warehouse/logisticsorder/view'])?>?id='+rowData.ord_lg_id+'">'+rowData.lg_no+'</a>';
                }},
                {field: "start_address", title: "起运地", width: 84},
                {field: "destination", title: "目的地", width: 84},
                {field: "TRANSMODE", title: "运输模式", width: 84},
                {field: "crter", title: "开立人员", width: 84},
                {field: "crt_date", title: "开立日期", width: 84},
                {field: "check_status", title: "单据状态", width: 80},
                {field: "log_copy", title: "物流进度", width: 100,
                    formatter: function (value, rowData, rowIndex) {
                    if(rowData.log_copy!=null)
                    {
                        return '<a class="logcopy" href="javascript:void(0);" onclick="loginfo($(this))">' + rowData.log_copy + '</a>'
                            +'<input type="hidden" class="lgno" value="'+rowData.lg_no+'">';
                    }
                    }},
                {field: "Fetch_date", title: "取件时间", width: 120},
                {field: "rcpt_date", title: "客户签收时间", width: 120},
                {field: "TRANSTYPE", title: "配送时效", width: 50}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
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
                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                }
            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.ord_lg_id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
                if (rowData.check_status == '开立') {
                    $("#edit_btn").show().next().show();
                    $("#check_btn").show().next().show();
                }
                else if (rowData.check_status == '驳回') {
                    $("#check_btn").show().next().show();
                    $("#edit_btn").hide().next().hide();
                }
                else {
                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].ord_lg_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;

                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                    $('#supplier_table').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#supplier_table').datagrid("unselectAll");
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#edit_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
                var a = $("#supplier_table").datagrid("getChecked");
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#edit_btn").hide().next().hide();
                $("#delete_btn").hide().next().hide();
                $("#check_btn").hide().next().hide();
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

    });
function loginfo(obj) {
    var lgno=obj.siblings('.lgno').val();
    $("#check_in_info_tab").datagrid({
        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['log-info'])?>?lgno=" + lgno,
        method: "get",
        idField: "orderno",
        singleSelect: true,
        pagination: true,
        pageSize: 10,
        pageList: [10, 20, 30],
        selectOnCheck: false,
        checkOnSelect: true,
        columns: [[
            {field: "orderno", title: "物流单号", width: 200},
            {field: "forwardcode", title: "承运商代码", width: 84},
            {field: "expressno", title: "快递单号", width: 84},
            {field: "station", title: "站点", width: 84},
            {field: "onwaystatus", title: "在途状态", width: 100},
            {field: "onwaystatus_date", title: "状态发生时间", width: 154},
            {field: "delivery_man", title: "送货人", width: 200},
            {field: "remark", title: "状态详情", width: 84},
            {field: "carrierno", title: "配送车编号", width: 84},
            {field: "ord_no", title: "订单号", width: 100},
            {field: "create_by", title: "创建人", width: 84},
            {field: "createdate", title: "创建时间", width: 100}

        ]],
        onLoadSuccess: function (data) {
            showEmpty($(this), data.total, 1);
            setMenuHeight();
            datagridTip("#check_in_info_tab");
        }
    });
}
</script>

