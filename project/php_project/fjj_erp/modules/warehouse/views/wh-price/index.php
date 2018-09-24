<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/14
 * Time: 上午 11:00
 */

use app\classes\Menu;
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '仓库标准价格查询'];
$this->title = '仓库标准价格查询';
?>
<style>
    .num_input {

        display: none;
    }

    .num_width {
        width: 50%;
    }
</style>
<div class="content">
    <?= $this->render("_search", [
        "get" => Yii::$app->request->get(),
        'downList' => $downList]) ?>
    <div class="mb-10"></div>
    <div class="table-head">
        <p class="head">仓库列表</p>
        <div class="float-right">
            <?= Menu::isAction('/warehouse/wh-print/create') ? "<a id='create'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left">&nbsp;|&nbsp;</p>
            <?= Menu::isAction('/warehouse/wh-print/update') ? "<a id='update' style='display:none;'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>修改</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left" id="update1" style='display:none;'>&nbsp;|&nbsp;</p>

            <?= Menu::isAction('/warehouse/wh-print/update') ? "<a id='state' style='display:none;'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>启用</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left" id="state1" style='display:none;'>&nbsp;|&nbsp;</p>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>

        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
        <div class="mb-10"></div>
        <div id="price_list_title"></div>
        <div class="mb-10"></div>
        <div id="price_list" style="width:100%;"></div>
    </div>
</div>
<script>
    $(function () {
        $('#data').datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "whp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            pageSize: 10,
            pageList: [10, 20, 30],
            columns: [[
                {field: 'ck', checkbox: true},
                <?= $fields ?>
                {
                    field: "handle", title: "操作", width: "60", formatter: function (value, row, index) {
                    var str = "";
                    if (row.whp_status == "启用") {
                        str += "<i><a class='operate icon-check-minus icon-large' title='禁用' onclick='OpenClose(" + row.whp_id + ")'></a><i>";
                    } else {
                        str += "<i><a class='operate icon-check-sign icon-large' title='启用' onclick='OpenClose(" + row.whp_id + ")'></a></i>";
                    }
                    str += "<i><a title='修改' class='icon-edit icon-large' onclick='update(" + row.whp_id + ")' style='margin-left:15px;'></a></i>";
                    return str;
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#data").datagrid('clearSelections').datagrid('clearChecked');
                $("#update").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件

                $("#data").datagrid("checkRow",rowIndex);
                var index = $("#data").datagrid("getSelected");
                var whp_id = index['whp_id'];
                //获取状态
                var state = index['whp_status'];
                if (state == '禁用') {
                    $('#state').find('p:eq(1)').html('启用');
                } else {
                    $('#state').find('p:eq(1)').html('禁用');
                }
                $("#price_list_title").addClass("table-head mb-5 mt-20").html("<p class='head'>费用组合</p>");
                $("#update").show();
                $("#update1").show();
                $("#state").show();
                $("#state1").show();
                $("#price_list").datagrid({
                    url: "<?=Yii::$app->request->getHostInfo() . Url::to(['price-list']) ?>?whp_id=" + whp_id,
                    rownumbers: true,
                    method: "get",
                    idField: "whpl_id",
                    loadMsg: "加载中...",
                    checkOnSelect: false,
                    selectOnCheck: false,
                    pagination: true,
                    singleSelect: true,
                    columns: [[

                        {
                            field: "whpb_code", title: "费用代码", width: "150", formatter: function (value, row, index) {
                            return value + "<input type='text' value='" + row.whpl_id + "' style='display: none'>";
                        }
                        },
                        {
                            field: "whpb_sname", title: "费用名称", width: "200", formatter: function (value, row, index) {
                            return value + "<input type='text' value='" + row.whp_id + "' style='display: none'>";
                        }
                        },
                        {
                            field: "whpb_num", title: "费用标准", width: "200", formatter: function (value, row, index) {
                            var cur = "";
                            <?php foreach ($downList['BsCurrency'] as $key => $val) { ?>
                            if (<?= $val['cur_id'] ?>==row.whpb_curr
                            )
                            {
                                cur += "<option value = \"<?= $val['cur_id'] ?>\" selected=\"selected\"><?= $val['cur_code'] ?></option>";
                            }
                            else
                            {
                                cur += "<option value = \"<?= $val['cur_id'] ?>\"><?= $val['cur_code'] ?></option>";
                            }
                            <?php } ?>
                            return "<label class='num_label'>" + row.whpb_num + "</label> " + "<label class='num_label'>" + row.cur_code + '</label> <input class="num_input num_width" type="text" value="' + row.whpb_num + '"/><select class="num_input num_width">' + cur + "</select>";
                        }
                        },
                        {field: "stcl_description", title: "描述", width: "300"},
                        {
                            field: "handle", title: "操作", width: "100", formatter: function (value, row, index) {
                            if (row.count == 0) {
                                return '<a onclick="updatePrice(this)">修改</a><a onclick="submit(this)" class="num_input">确定</a><a style="margin-left: 10px;" onclick="deletePrice(this)">删除</a>'
                            } else {
                                return "";
                            }
                        }
                        }
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#price_list_title");
                        showEmpty($(this), data.total, 1);
                        setMenuHeight();
                        $("#price_list_title").datagrid('clearSelections').datagrid('clearChecked');

                    },
                })
            },

        });
        $('#create').click(function () {
            location.href = "<?=Url::to(['create'])?>";
        });
        $('#update').click(function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var whp_id = row['whp_id'];
                update(whp_id);
            }
//            location.href = "<?//=Url::to(['update'])?>//?whp_id=" + whp_id;
        });
        $('#state').click(function () {
            var rows = $("#data").datagrid("getChecked");
            if (rows.length == 0) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
                return false;
            }
            var WhpIdArry = new Array();
            $.each(rows, function (i) {
                WhpIdArry.push(rows[i].whp_id);
            });
            var whp_id = WhpIdArry.join(",");
            if (!whp_id) {
                layer.alert("请选择需要的记录", {icon: 2, time: 5000});
            } else {
                var openclose = $('#state').find('p:eq(1)').html();
                if (openclose = '禁用') {
                    openclose = '确定启用此仓库标准价格?'
                } else {
                    openclose = "确定禁用此仓库标准价格？";
                }
                layer.confirm(openclose, {
                    btn: ['确定', '取消'],
                    icon: 2
                }, function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {whp_id: whp_id},
                        url: "<?=Url::to(['open-close']) ?>",
                        success: function (res) {
                            if (res.flag == 1) {
                                layer.alert(res.msg, {icon: 2});
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
        })
    })

    function update(whp_id) {
        location.href = "<?=Url::to(['update'])?>?whp_id=" + whp_id;
    }

    function OpenClose(whp_id) {
        var row = $("#data").datagrid('getSelected');
        if (row) {
            var YN = row['whp_status'];
            var part_id = row['whp_id'];
            var openclose = "";
            if (YN == "禁用") {
                openclose = "确定启用此仓库标准价格？";
            } else {
                openclose = "确定禁用此仓库标准价格？";
            }

            layer.confirm(openclose, {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {whp_id: whp_id},
                    url: "<?=Url::to(['open-close']) ?>",
                    success: function (res) {
                        if (res.flag == 1) {
                            layer.alert(res.msg, {icon: 2});
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
    }

    //修改费用标准
    function updatePrice(obj) {
        var td = $(obj).parents("tr").find("td");
        td.eq(2).find('label').addClass("num_input");
        td.eq(2).find('select').removeClass("num_input");
        td.eq(2).find('input').removeClass("num_input");
        td.eq(4).find('a').removeClass("num_input");
        td.eq(4).find('a:first').addClass("num_input");

    }
    //修改后确认
    function submit(obj) {
        var td = $(obj).parents("tr").find("td");
        td.eq(2).find('label').removeClass("num_input");
        td.eq(2).find('select').addClass("num_input");
        td.eq(2).find('input').addClass("num_input");
        td.eq(4).find('a').removeClass("num_input");
        td.eq(4).find('a:eq(1)').addClass("num_input");
        var whpl_id = td.eq(0).find('input').val();
        var whp_id = td.eq(1).find('input').val();
        var whpb_num = td.eq(2).find('input').val();
        var whpb_curr = td.eq(2).find('select').val();
        $.get({
            url: "<?=Url::to(['update-price'])?>",
            data: {whpl_id: whpl_id, whpb_num: whpb_num, whpb_curr: whpb_curr},
            dataType: "JSON",
            success: function (res) {
                if (res.flag == 1) {
                    layer.alert(res.msg, {icon: 2});
                    $("#price_list").datagrid("reload", {
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['price-list']) ?>?whp_id=" + whp_id,
                    });
                } else {
                    layer.alert(res.msg, {icon: 2})
                }
            }
        })
    }
    //删除费用标准
    function deletePrice(obj) {
        var td = $(obj).parents("tr").find("td");
        var whpl_id = td.eq(0).find('input').val();
        var whp_id = td.eq(1).find('input').val();
        layer.confirm("确定删除该标准价格吗?", {
            btn: ['确定', '取消'],
            icon: 2
        }, function () {
            $.get({
                url: "<?=Url::to(['delete-price'])?>",
                data: {whpl_id: whpl_id},
                dataType: "JSON",
                success: function (res) {
                    if (res.flag == 1) {
                        layer.alert(res.msg, {icon: 2});
                        $("#price_list").datagrid("reload", {
                            url: "<?=Yii::$app->request->getHostInfo() . Url::to(['price-list']) ?>?whp_id=" + whp_id,
                        });
                    } else {
                        layer.alert(res.msg, {icon: 2})
                    }
                }
            })
        }, function () {
            layer.closeAll();
        });
    }
</script>
