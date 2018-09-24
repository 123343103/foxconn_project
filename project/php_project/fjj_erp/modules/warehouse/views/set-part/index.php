<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 上午 09:33
 */

use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '区位设置'];
$this->title = '区位设置';
?>
<style>
  .add{
      height: 23px;
      width: 55px;
      float: left;
  }
    .f-l{
        float: left;
    }
    .add1{
        font-size: 14px;
        margin-top: 2px;
    }
    .setc{
        height: 23px;
        width: 85px;
        float: left;
    }
</style>
<div class="content">
    <?= $this->render("_search", [
        'get' => \Yii::$app->request->get(),
        'downList' => $downList,
    ]); ?>
    <div class="space-20"></div>

    <div class="table-content">
        <div class="table-head">
            <p class="head" style="font-weight: bold">区位列表</p>
            <div class="float-right">
                <a id="add">
                    <div class="add">
                        <p class="add-item-bgc f-l"></p>
                        <p class="add1">&nbsp;新增</p>
                    </div>
                </a>
                <p class="f-l" style="display: none">&nbsp;|&nbsp;</p>
                <a id="update" style="display: none">
                    <div class="add">
                        <p class="update-item-bgc f-l"></p>
                        <p class="add1">&nbsp;修改</p>
                    </div>
                </a>
                <p class="f-l" style="display: none">&nbsp;|&nbsp;</p>
                <a id="setstart" style="display: none">
                    <div class="add">
                        <p class="submit-item-bgc f-l"></p>
                        <p class="add1" id="ol">&nbsp;启用</p>
                    </div>
                </a>
                <p class="f-l aa" style="display: none">&nbsp;|&nbsp;</p>
                <a id="setend" style="display:none">
                    <div class="add">
                        <p class="setbcg9 f-l"></p>
                        <p class="add1" id="ol">&nbsp;禁用</p>
                    </div>
                </a>
                <p class="f-l">&nbsp;|&nbsp;</p>

                <a id="return">
                    <div class="add">
                        <p class="return-item-bgc f-l"></p>
                        <p class="add1">&nbsp;返回</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="space-10"></div>
    <div class="mt-10">
        <div id="data"></div>
    </div>
</div>

<script>
    $(function () {
        "use strict";
        $("#update").hide().next().hide();
        $("#setstart").hide().next().hide();
        $("#setend").hide().next().hide();
        var id;
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "part_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                {field: 'ck', checkbox: true},
//                {field: "wh_code", title: "仓库代码", width: 180},
//                {field: "wh_name", title: "仓库名称", width: 180},
//                {
//                    field: "part_code", title: "区位码", width: 120,
//                },
//                {field: "part_name", title: "区位名称", width: 180},
//                {
//                    field: "YN", title: "状态", width: 100, formatter: function (value, row) {
//                    if (row.YN == "0") {
//                        return "<span>禁用</span>"
//                    } else {
//                        return "启用"
//                    }
//                }
//                },
//                {field: "remarks", title: "备注", width: 180},
//                {field: "OPPER", title: "创建人", width: 80},
//                {field: "OPP_DATE", title: "创建时间", width: 180},
                <?=$data['table']?>
                {field: "part_id", title: "操作", width:60, formatter: function (value, rowData, rowIndex) {
                    var str="";
                    if (rowData.YN == "启用") {
                        str+="<a class='operate icon-check-minus icon-large' title='禁用'></a>" +
                            "<input type='hidden' class='wh_codes' value='" + rowData.wh_code + "' >" +
                            "<input type='hidden' class='wh_yn' value='" + rowData.wh_state + "'>";
                    } else {
                        str+="<a class='operate icon-check-sign icon-large' title='启用'></a>" +
                            "<input type='hidden' class='wh_codes' value='" + rowData.wh_code + "' >" +
                            "<input type='hidden' class='wh_yn' value='" + rowData.wh_state + "'>";
                    }
                    str+="<a title='修改' class='icon-edit icon-large' onclick='editbtn("+ value +")' style='margin-left:15px;'></a>";
                    return str;
                }}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                //每一个仓库代码详情
                $(".viewitem").click(function () {
                    var id = $(this).html();
                    location.href="<?= Url::to(['view'])?>?id=" + id;
                    <?= Yii::$app->user->identity->staff_id ?>
                });
                //---end----
            },
            onCheck: function (rowIndex, rowData) {
                var aa = new Array();
                var _check = $("#data").datagrid("getChecked");
                if (_check.length == 1) {
                    if (_check[0]['YN'] == '禁用') {
                        $("#setstart").show().next().show();
                        $("#update").show().next().show();
                        $("#setend").hide().next().hide();
                    } else {
                        $("#setstart").hide().next().hide();
                        $("#setend").show().next().show();
                        $("#update").show().next().show();
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                }
                else {
                    if (_check[0]['YN'] == '禁用') {
                        $("#setstart").show().next().show();
                        $("#update").hide().next().hide();
                        $("#setend").hide().next().hide();
                    } else {
                        $("#setstart").hide().next().hide();
                        $("#setend").show().next().show();
                        $("#update").hide().next().hide();
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
                    $("#update").hide().next().hide();
                    $("#setstart").hide().next().hide();
                    $("#setend").hide().next().hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                } else {
                    if (a1.length == 1) {
                        if (a1[0].YN == '启用') {
                            $("#setstart").show().next().show();
                            $("#update").show().next().show();
                            $("#setend").hide().next().hide();
                        } else {
                            $("#setstart").hide().next().hide();
                            $("#setend").show().next().show();
                            $("#update").show().next().show();
                        }
                        var b = $("#data").datagrid("getRowIndex", a1[0]);
                        $('#data').datagrid('selectRow', b);
                    }
                    else {
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#update").hide().next().hide();
                $("#setstart").hide().next().hide();
                $("#setend").hide().next().hide();
            },
            onUnselectAll: function (rowIndex, rowData) {
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#update").hide().next().hide();
                $("#setstart").hide().next().hide();
                $("#setend").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#data").datagrid("getRowIndex", rowData.wh_code);
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

        //查看详情
//        $(".mt-10").delegate(".viweone","click", function () {
//            var row = $("#data").datagrid("getSelected");
//            $.fancybox({
//                type: "iframe",
//                width: 500,
//                height: 400,
//                autoSize: false,
//                margin:13,
//                padding:0,
//                href:"<?//=Url::to(['view'])?>//?part_id=" + row.part_id
//            })
//        });
        //批量启用
        $("#setstart").click(function () {
            var s=new Array();
            var row = $("#data").datagrid('getChecked');
            for(var i=0;i<row.length;i++)
            {
                s.push(row[i]['part_id'])
            }
            var openclose = "";
            var yn = row[0]['YN'];
           if(yn == "禁用")
           {
               openclose = "确定将此数据启用吗？";
           }
            layer.confirm(openclose, {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {part_id: s},
                    url: "<?=Url::to(['open-closess']) ?>",
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
        });

        //批量禁用
        $("#setend").click(function () {
            var s=new Array();
            var row = $("#data").datagrid('getChecked');
            for(var i=0;i<row.length;i++)
            {
                s.push(row[i]['part_id'])
            }
            var openclose = "";
            var yn = row[0]['YN'];
            if(yn == "启用")
            {
                openclose = "确定将此数据禁用？";
            }
            layer.confirm(openclose, {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {part_id: s},
                    url: "<?=Url::to(['closess']) ?>",
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
        });

        //操作
        $(".mt-10").delegate(".operate","click", function () {
            var row = $("#data").datagrid('getSelected');
            if (row) {
                var YN = row['YN'];
                var part_id = row['part_id'];
                var openclose = "";
                if (YN == "禁用") {
                    openclose = "确定将此数据启用吗？";
                } else {
                    openclose = "确定将此数据禁用？";
                }

                layer.confirm(openclose, {
                    btn: ['确定', '取消'],
                    icon: 2
                }, function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {part_id: part_id},
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
        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '显示{from}到{to},共{total}记录'
        });
        $("#add").on("click", function () {
            $.fancybox({
                type: "iframe",
                width: 600,
                height:500,
                autoSize: false,
                margin:13,
                padding:0,
                href: "<?=Url::to(['add-edit'])?>?part_id='add'",
            });
        });
        $("#update").on("click", function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var part_id = row['part_id'];
                $.fancybox({
                    type: "iframe",
                    width: 600,
                    heigth: 300,
                    autoSize: false,
                    href: "<?=Url::to(['add-edit'])?>?part_id=" + part_id,
                    padding: 0
                });
            }
        });

        $(".mt-10").delegate('.partcode','click',function () {
            var row = $("#data").datagrid("getSelected");
            $.fancybox({
                type: "iframe",
                width: 500,
                height: 400,
                autoSize: false,
                margin:13,
                padding:0,
                href:"<?=Url::to(['view'])?>?part_id=" + row.part_id
            })
        });


        //返回
        $("#return").on("click", function () {
            window.location.href = "<?=Url::to(['/index/index'])?>";
        })


    });

    function editbtn(value) {
//        alert(value);
        $.fancybox({
            type: "iframe",
            width: 600,
            heigth: 300,
            autoSize: false,
            href: "<?=Url::to(['add-edit'])?>?part_id=" + value,
            padding: 0
        });
    }

</script>
