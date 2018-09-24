<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2018/1/15
 * Time: 15:52
 */
use yii\helpers\Url;

$this->title = '比例设置列表';
//dumpE($this);
$this->params['homeLike'] = ['label' => '仓库物流管理'];
$this->params['breadcrumbs'][] = ['label' => '比例设置列表'];
?>
<style>
    .space-20 {
        width: 100%;
        height: 20px;
    }

    .add {
        height: 23px;
        width: 55px;
        float: left;
    }

    .f-l {
        float: left;
    }

    .space-10 {
        width: 100%;
        height: 10px;
    }
</style>
<div class="content">
    <?php echo $this->render('search', ['downlist' => $downlist]) ?>
    <div class="table-head">
        <p class="head">比例设置列表</p>
        <div style="float: right">
            <a id="add">
                <div class="add" style="float: left">
                    <p class="add-item-bgc f-l"></p>
                    <p style="font-size: 15px;margin-top: 3px">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;margin-top: 3px;">&nbsp;|&nbsp;</p>
            <a id="edit">
                <div style="height: 23px;float: left">
                    <p class="update-item-bgc" style="float: left;margin-top: 1px"></p>
                    <p style="font-size: 14px;margin-top: 3px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left">&nbsp;|&nbsp;</p>
            <a id="open">
                <div style="height: 23px;float: left">
                    <p class="setbcg10" style="float: left;margin-top: 2px"></p>
                    <p style="font-size: 14px;margin-top: 3px;">&nbsp;启用</p>
                </div>
            </a>
            <p style="float: left">&nbsp;|&nbsp;</p>
            <a id="cancel">
                <div style="height: 23px;float: left">
                    <p class="setbcg9" style="float: left;margin-top: 2px"></p>
                    <p style="font-size: 14px;margin-top: 3px;">&nbsp;禁用</p>
                </div>
            </a>
            <p style="float: left">&nbsp;|&nbsp;</p>
            <a id="return" href="<?= Url::to(['/index/index']) ?>">
                <div class="add" style="margin-top: 3px;margin-left: 4px">
                    <p class="return-item-bgc f-l"></p>
                    <p style="font-size: 15px">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="space-10"></div>
    <div class="table-content">
        <div id="data"></div>
    </div>
</div>
<script>
    $(function () {
        $("#edit").hide().next().hide();
        $("#open").hide().next().hide();
        $("#cancel").hide().next().hide();
        var id;
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "ratio_no",
            loadMsg: "加载数据请稍后...",
            selectOnCheck: false,
            checkOnSelect: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: 'ck', checkbox: true},
                {field: 'ratio_no', title: '编号', width: 110},
                {field: 'bsp_svalue', title: '单据类型', width: 90},
                {
                    field: 'upp_num', title: '比例上限', width: 80
                },
                {
                    field: 'low_num', title: '比例下限', width: 80
                },
                {field: 'remark', title: '备注', width: 150},
                {field: 'yn', title: '状态', width: 65},
                {field: 'staff_name', title: '操作人', width: 70},
                {field: 'opp_date', title: '操作时间', width: 140},
                {
                    field: 'oper',
                    title: '操作',
                    width: 70,
                    align: 'center',
                    formatter: function (value, rowData, rowIndex) {
                        var str = "<i style='margin-left: 6px'>";
                        if (rowData.yn == '启用') {
                            str += "<a class='operate1 icon-remove-circle  icon-large' title='禁用'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                            str += "<a class='edit icon-edit icon-large'  title='修改'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                            str += "</i>";
                            return str;
                        }
                        else {
                            str += "<a class='operate2 icon-ok-circle  icon-large' title='启用'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                            str += "<a class='edit icon-edit icon-large'  title='修改'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                            str += "</i>";
                            return str;
                        }

                    }
                }
            ]],
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                datagridTip('#data');
                $('#data').datagrid('resize');
                showEmpty($(this), data.total, 1);
            },
            onCheck: function (rowIndex, rowData) {
                var aa = new Array();
                var _check = $("#data").datagrid("getChecked");
                if (_check.length == 1) {
                    if (_check[0]['yn'] == '禁用') {
                        $("#open").show().next().show();
                        $("#edit").show().next().show();
                        $("#cancel").hide().next().hide();
                    } else {
                        $("#open").hide().next().hide();
                        $("#cancel").show().next().show();
                        $("#edit").show().next().show();
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                }
                else {
                    if (_check[0]['yn'] == '禁用') {
                        $("#open").show().next().show();
                        $("#edit").hide().next().hide();
                        $("#cancel").hide().next().hide();
                    } else {
                        $("#open").hide().next().hide();
                        $("#cancel").show().next().show();
                        $("#edit").hide().next().hide();
                    }
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 0) {
                    $("#edit").hide().next().hide();
                    $("#open").hide().next().hide();
                    $("#cancel").hide().next().hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                } else {
                    if (a1.length == 1) {
                        if (a1[0].yn == '禁用') {
                            $("#open").show().next().show();
                            $("#edit").show().next().show();
                            $("#cancel").hide().next().hide();
                        } else {
                            $("#open").hide().next().hide();
                            $("#cancel").show().next().show();
                            $("#edit").show().next().show();
                        }
                        var b = $("#data").datagrid("getRowIndex", a1[0].ratio_no);
                        $('#data').datagrid('selectRow', b);
                    }
                    else {
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#edit").hide().next().hide();
                $("#open").hide().next().hide();
                $("#cancel").hide().next().hide();
            },
            onUnselectAll: function (rowIndex, rowData) {
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#edit").hide().next().hide();
                $("#open").hide().next().hide();
                $("#cancel").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.ratio_no);
                var oderh = $("#data").datagrid("getSelected");
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', rowIndex);
            }
        });

        //新增
        $('#add').click(function () {
            var url = "<?=Url::to(['create'])?>";
            $.fancybox({
                href: url,
                type: "iframe",
                fitToView: true,
                closeClick: false,
                padding: 0,
                openEffect: 'none',
                closeEffect: 'none',
                autoSize: false,
                width: 500,
                height: 350
            });
        });
        //操作栏修改
        $(".content").delegate(".edit", "click", function () {
            var row = $("#data").datagrid('getSelected');
            var id = row.ratio_no;
            var url = "<?=Url::to(['update'])?>?id=" + id;
            $.fancybox({
                href: url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 500,
                height:350
            });
        });
        //修改
        $('#edit').click(function () {
            var row = $("#data").datagrid('getChecked');
            var id = row[0].ratio_no;
            var url = "<?=Url::to(['update'])?>?id=" + id;
            $.fancybox({
                href: url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 500,
                height: 350
            });
        });
        //禁用
        $("#cancel").on("click", function () {
            var dd = "";
            var rows = $("#data").datagrid('getChecked');
            for (var i = 0; i < rows.length; i++) {
                if (i == rows.length - 1) {
                    dd += rows[i]['ratio_no'];
                }
                else {
                    dd += rows[i]['ratio_no'] + ",";
                }
            }
            var index = layer.confirm("确定将此数据禁用?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id": dd},
                        url: "<?=Url::to(['/warehouse/set-proportion/cancel']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        $("#data").datagrid('load', {
                                            "no": $("#no").val(),
                                            "type": $("#type").val(),
                                            "status": $("#status").val()
                                        }).datagrid('clearSelections').datagrid('clearChecked');
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
        });
       //操作栏禁用
        $(".content").delegate(".operate1", "click", function () {
            var dd = "";
            var rows = $("#data").datagrid('getChecked');
            for (var i = 0; i < rows.length; i++) {
                if (i == rows.length - 1) {
                    dd += rows[i]['ratio_no'];
                }
                else {
                    dd += rows[i]['ratio_no'] + ",";
                }
            }
            var index = layer.confirm("确定将此数据禁用?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id": dd},
                        url: "<?=Url::to(['/warehouse/set-proportion/cancel']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        $("#data").datagrid('load', {
                                            "no": $("#no").val(),
                                            "type": $("#type").val(),
                                            "status": $("#status").val()
                                        }).datagrid('clearSelections').datagrid('clearChecked');
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
        });
        //启用
        $("#open").on("click", function () {
            var dd = "";
            var rows = $("#data").datagrid('getChecked');
            for (var i = 0; i < rows.length; i++) {
                if (i == rows.length - 1) {
                    dd += rows[i]['ratio_no'];
                }
                else {
                    dd += rows[i]['ratio_no'] + ",";
                }
            }
            var index = layer.confirm("确定将此数据启用?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id": dd},
                        url: "<?=Url::to(['/warehouse/set-proportion/open']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        $("#data").datagrid('load', {
                                            "no": $("#no").val(),
                                            "type": $("#type").val(),
                                            "status": $("#status").val()
                                        }).datagrid('clearSelections').datagrid('clearChecked');
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
        });
        //操作栏启用
        $(".content").delegate(".operate2", "click", function () {
            var dd = "";
            var rows = $("#data").datagrid('getChecked');
            for (var i = 0; i < rows.length; i++) {
                if (i == rows.length - 1) {
                    dd += rows[i]['ratio_no'];
                }
                else {
                    dd += rows[i]['ratio_no'] + ",";
                }
            }
            var index = layer.confirm("确定将此数据启用?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id": dd},
                        url: "<?=Url::to(['/warehouse/set-proportion/open']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        $("#data").datagrid('load', {
                                            "no": $("#no").val(),
                                            "type": $("#type").val(),
                                            "status": $("#status").val()
                                        }).datagrid('clearSelections').datagrid('clearChecked');
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
        });
    })
</script>