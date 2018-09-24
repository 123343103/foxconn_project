<?php

use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '库存预警信息查询'];
$this->title = '库存预警信息查询';
?>
<div class="content">
    <?= $this->render("_search", [
        'get' => \Yii::$app->request->get(),
        'downList' => $downList,
    ]); ?>

    <div class="space-30"></div>
    <div class="table-content">
        <div class="table-head">
            <p class="head">库存预警信息列表</p>
            <div class="float-right">
                <a id="add">
                    <div style="height: 23px;width: 50px;float: left;">
                        <p class="add-item-bgc " style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">新增</p>
                    </div>
                </a>
                <p style="float: left;" id="add1"> | </p>
                <a id="edit">
                    <div style="height: 23px;width: 50px;float: left;">
                        <p class="update-item-bgc " style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">修改</p>
                    </div>
                </a>
                <p style="float: left;" id="edit1"> | </p>
                <a id="delete" onclick="deleteInv(1)">
                    <div style="height: 23px;width: 50px;float: left;">
                        <p class="delete-row-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">删除</p>
                    </div>
                </a>
                <p style="float: left;" id="delete1"> | </p>
                <a id="check">
                    <div style="height: 23px;width: 50px;float: left;">
                        <p class="submit-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">送审</p>
                    </div>
                </a>
                <p style="float: left;" id="check1"> | </p>

                <a id="back">
                    <div style="height: 23px;width: 50px;float: left">
                        <p class="return-item-bgc" style="float: left;"></p>
                        <p style="font-size: 14px;margin-top: 2px;">返回</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="space-10"></div>
        <div id="data" style="width: 990px;"></div>
        <div class="space-10"></div>
        <p style="display: none;font-size: 14px;margin-bottom: 10px;" id='waringinfo'>商品信息列表</p>
        <div id="show-childe">
            <div id="load-content"></div>
        </div>

    </div>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        $("#edit").hide();
        $("#delete").hide();
        $("#check").hide();
        $("#edit1").hide();
        $("#delete1").hide();
        $("#check1").hide();
        "use strict";
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "inv_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                {field: 'ck', checkbox: true, align: 'left'},
                <?=$colnums?>
                {
                    field: "hhhhh", title: "操作", width: "10%", formatter: function (value, row, index) {
                    if (row.so_type != "审核中" && row.so_type != "审核完成") {
                        var delete1 = '<a href="#" onclick="deleteInv(0)"><i class="icon-trash icon-l" title="删除"></i>&nbsp;&nbsp;</a>';
                        var updata = '<a href="#" onclick="editW(' + index + ')"><i class="icon-edit icon-l" title="修改"></i>&nbsp;&nbsp;</a>';
                        return delete1 + updata;
                    }
                }
                },
            ]],
            onLoadSuccess: function (data) {
//                datagridTip($("#data"));
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                showEmpty($(this), data.total, 1);
            },
            onCheckAll: function (rowIndex, rowData) {  //checkbox 全选中事件
                //设置选中事件，清除之前单行选择
                var a = $("#data").datagrid("getChecked");
                var a2 = true;
                for (var i = 0; i < a.length; i++) {
                    if (a[i].so_type == "审核完成" || a[i].so_type == "审核中") {
                        a2 = false;
                        break;
                    }
                }
                if (a2 == false) {
                    $("#edit").hide();
                    $("#delete").hide();
                    $("#check").hide();
                    $("#edit1").hide();
                    $("#delete1").hide();
                    $("#check1").hide();
                }
                else {
                    $("#edit").show();
                    $("#delete").show();
                    $("#check").show();
                    $("#edit1").show();
                    $("#delete1").show();
                    $("#check1").show();
                }
                $("#data").datagrid("unselectAll");
                $('#show-childe').hide();
                $("#waringinfo").hide();
            },
            onCheck: function (rowIndex, rowData) {  //checkbox 选中事件
                var a1 = $("#data").datagrid("getChecked");
                var a2 = true;
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
                    $('#show-childe').hide();
                    $("#waringinfo").hide();
                }
                for (var i = 0; i < a1.length; i++) {
                    if (a1[i].so_type == "审核完成" || a1[i].so_type == "审核中") {
                        a2 = false;
                        break;
                    }
                }
                if (a2 == true) {
                    $("#edit").show();
                    $("#delete").show();
                    $("#check").show();
                    $("#edit1").show();
                    $("#delete1").show();
                    $("#check1").show();
                }
                else {
                    $("#edit").hide();
                    $("#delete").hide();
                    $("#check").hide();
                    $("#edit1").hide();
                    $("#delete1").hide();
                    $("#check1").hide();
                }
                //设置选中事件，清除之前单行选择
//                $("#data").datagrid("unselectAll");
//                $('#load-content').empty();
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                var a2 = true;
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].inv_id);
                        $('#data').datagrid('selectRow', b);
                        $("#show-childe").show();
                        $("#load-content").datagrid({
                            url: "<?= Url::to(['new-load-waring']);?>?biw_h_pkid=" + a[0].biw_h_pkid,
                            rownumbers: true,
                            method: "get",
                            idField: "inv_id",
                            loadMsg: "加载数据请稍候。。。",
                            pagination: true,
                            pageSize: 5,
                            pageList: [5, 10, 15],
                            singleSelect: true,
                            checkOnSelect: false,
                            selectOnCheck: false,
                            columns: [[
                                <?= $colnumsChilde ?>
                            ]],
                            onLoadSuccess: function (data) {
                                datagridTip('#record');
                                showEmpty($(this), data.total, 0);
                            }
                        });
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#show-childe").hide();
                    $("#waringinfo").hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#load-content").hide();
                    }
                }
                if (a.length == 0) {
                    a2 = false;
                }
                else {
                    for (var i = 0; i < a.length; i++) {
                        if (a[i].so_type == "审核完成" || a[i].so_type == "审核中") {
                            a2 = false;
                            break;

                        }
                    }
                }
                if (a2 == true) {
                    $("#edit").show();
                    $("#delete").show();
                    $("#check").show();
                    $("#edit1").show();
                    $("#delete1").show();
                    $("#check1").show();
                }
                else {
                    $("#edit").hide();
                    $("#delete").hide();
                    $("#check").hide();
                    $("#edit1").hide();
                    $("#delete1").hide();
                    $("#check1").hide();
                }
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#show-childe").hide();
                $("#waringinfo").hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#edit").hide();
                $("#delete").hide();
                $("#check").hide();
                $("#edit1").hide();
                $("#delete1").hide();
                $("#check1").hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#data").datagrid("getRowIndex", rowData.inv_id);
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
                var biw_h_pkid = rowData['biw_h_pkid']
                var so_type = rowData['so_type'];
                //设置选中事件，清除之前多行选择
                $("#waringinfo").css("display", "block");
                $("#show-childe").show();
                $("#load-content").datagrid({
                    url: "<?= Url::to(['new-load-waring']);?>?biw_h_pkid=" + biw_h_pkid,
                    rownumbers: true,
                    method: "get",
                    idField: "inv_id",
                    loadMsg: "加载数据请稍候。。。",
                    pagination: true,
                    pageSize: 5,
                    pageList: [5, 10, 15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns: [[
                        <?= $colnumsChilde ?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip('#record');
                        showEmpty($(this), data.total, 0);
                    }
                });
                if (so_type == "审核中" || so_type == "审核完成") {
                    $("#edit").hide();
                    $("#delete").hide();
                    $("#check").hide();
                    $("#edit1").hide();
                    $("#delete1").hide();
                    $("#check1").hide();
                } else {
                    $("#edit").show();
                    $("#delete").show();
                    $("#check").show();
                    $("#edit1").show();
                    $("#delete1").show();
                    $("#check1").show();
                }
            }
        });


        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });
        //新增预警
        $("#add").on("click", function () {
            var typeid = $("#typeid").val();
            window.location.href = "<?=Url::to(['create'])?>";
        });

        //送审
        $("#check").on("click", function () {
            var row = $("#data").datagrid("getChecked");
            var id = '';
            var str = "预警编号为:";
            var isCheck = true;
            for (var i = 0; i < row.length; i++) {
                id += row[i]['biw_h_pkid'] + "-";
                if (i == row.length - 1) {
                    if (getTypeByWhID(row[i].wh_id)) {
                        str += row[i].inv_id;
                        isCheck = false;
                    }
                }
                else {
                    if (getTypeByWhID(row[i].wh_id)) {
                        str += row[i].inv_id + ",";
                        isCheck = false;
                    }
                }
            }
            if (isCheck == false) {
                str += "的仓库已有审核中的预警,请勿重复提交";
                layer.alert(str, {icon: 2});
                return false;
            }
            else {
                var url = "<?=Url::to(['index'])?>";
                $.fancybox({
                    href: "<?=Url::to(['reviewer'])?>?type=" + <?=$typeId?> +"&id=" + id + "&url=" + url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
            }
        });

        //修改预警信息
        $("#edit").on("click", function () {
            var row = $("#data").datagrid("getSelected");

            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var biw_h_pkid = row['biw_h_pkid'];
                var so_type = row['so_type'];
                if (so_type == "审核中" || so_type == "审核完成") {
                    layer.alert("审核中和审核完成的数据不能修改", {icon: 2});
                } else {
                    window.location.href = "<?=Url::to(['edit'])?>?biw_h_pkid=" + biw_h_pkid;

                }
            }
        });

        $("#back").on("click", function () {

            window.location.href = "<?=Url::to(['/index/index'])?>";

        });


    })
    ;

    function editW(index) {
        $('#dg').datagrid('selectRow', index);
        var row = $('#data').datagrid('getSelected');
        if (row) {

            var biw_h_pkid = row['biw_h_pkid'];
            var so_type = row['so_type'];
            if (so_type == "审核中" || so_type == "审核完成") {
                layer.alert("审核中和审核完成的数据不能修改", {icon: 2});
            } else {
                window.location.href = "<?=Url::to(['edit'])?>?biw_h_pkid=" + biw_h_pkid;

            }
        }

    }

    function deleteInv(index) {
        if (index == 1) {
            var data = $("#data").datagrid("getChecked");
        }
        else {
            var rows = $("#data").datagrid("getSelected");
            var data = [];
            if (rows != null) {
                data.push(rows);
            }
        }
        var ids = new Array();
        if (data.length > 0) {
            for (var x = 0; x < data.length; x++) {
                ids.push(data[x].biw_h_pkid);
            }
            layer.confirm("确定要删除选中的记录吗？", {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {id: ids.join()},
                    url: "<?=Url::to(['delete']) ?>",
                    success: function (res) {
                        if (res.flag == 1) {
//                                layer.closeAll();
                            layer.alert("删除成功", {icon: 2});
                            $("#data").datagrid("reload", {
                                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                onLoadSuccess: function () {
                                    $("#data").datagrid("clearChecked");
                                }
                            });
                        } else {
                            layer.alert(res.msg, {icon: 2})
                        }
                    },
                    error: function (res) {
                        layer.alert(res.msg, {icon: 2});
                    }
                });
            }, function () {
                layer.closeAll();
            });
        }
        else {
            if (index == 1) {
                layer.alert("请至少选择一条数据!", {icon: 2});
                return false;
            }
        }
    }

    //查询该仓库有没有在审核中的预警
    function getTypeByWhID(wh_id) {
        var tf = false
        $.ajax({
            type: 'POST',
            url: "<?= Url::to(["gettypebywhid"])?>",
            async: false,
            data: {
                wh_id: wh_id
            },
            success: function (data) {
                tf = data;
            },
            error: function (xhr, type) {
                alert("出现异常!");
            }
        });
        return tf;
    }

</script>


