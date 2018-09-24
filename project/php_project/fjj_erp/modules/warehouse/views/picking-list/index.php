<?php
use yii\helpers\Url;
use app\classes\Menu;

$this->title = '拣货单列表';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '拣货单列表'];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'whattrinfo'=>$whattrinfo,
        'options' => $options,
        'queryParam' => $queryParam,
    ]); ?>
    <div class="space-20"></div>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
        <div id="order_child_title"></div>
        <div id="order_child" style="width:100%;"></div>
    </div>
</div>
<script>
//    var isCheck = false; //是否点击复选框
//    var isSelect = false; //是否点击单条
//    var onlyOne = false; //是否只选中单个复选框
//    var $childTableTitle = $("#order_child_title");
    $(function () {
        var $childTableTitle = $("#child_table_title");
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "pck_pkid",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: "ck", checkbox: true},
                <?= $columns ?>
                {field:'pck_pkid',title:'操作',width:100,formatter:function(value,rowData){
                    var str="";
                    if(rowData.status == '待拣货'){
                        str+="<a class='icon-minus-sign icon-large' style='margin-right:15px;' title='取消' onclick='event.stopPropagation();cancelpick("+value+");'></a>";
                    }
                    return str;
                }}
            ]],
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.pck_pkid);
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
                if(rowData.status == '待拣货'){
                    $("#maintenance-pick").show();
                    $("#maintenance-pick").next().show();
                    $("#cancel-pick").show();
                    $("#cancel-pick").next().show();
                }else if(rowData.status=='已拣货'){
                    $("#out-pick").show();
                    $("#out-pick").next().show();
                }
                else {
                    $("#out-pick").hide();
                    $("#out-pick").next().hide();
                    $("#maintenance-pick").hide();
                    $("#maintenance-pick").next().hide();
                    $("#cancel-pick").hide();
                    $("#cancel-pick").next().hide();
                }
//                var notify = $("#data").datagrid("getSelected");
                $childTableTitle.addClass("table-head mb-5 mt-20").html("<p>商品信息</p>").show().next().show();
//                var id = notify['pck_pkid'];
                $("#order_child").datagrid({
                    url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>?id=" + rowData.pck_pkid,
                    rownumbers: true,
                    method: "get",
                    idField: "pck_dt_pkid",
                    singleSelect: true,
                    pagination: true,
                    pageSize: 10,
                    pageList: [10, 20, 30],
                    columns: [[
                        <?= $child_columns ?>
                    ]],
                    onLoadSuccess: function (data) {
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
//                        $("#order_child").datagrid('clearSelections');
                        datagridTip("#order_child");

                    },
                });
            },
            onCheck: function (rowIndex, rowData) {  //checkbox 选中事件
                //设置选中事件，清除之前单行选择
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        if(rowData.status == '待拣货'){
                            $("#maintenance-pick").show();
                            $("#maintenance-pick").next().show();
                            $("#cancel-pick").show();
                            $("#cancel-pick").next().show();
                        }else if(rowData.status=='已拣货'){
                            $("#out-pick").show();
                            $("#out-pick").next().show();
                        }
                        else {
                            $("#out-pick").hide();
                            $("#out-pick").next().hide();
                            $("#maintenance-pick").hide();
                            $("#maintenance-pick").next().hide();
                            $("#cancel-pick").hide();
                            $("#cancel-pick").next().hide();
                        }
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#out-pick").hide();
                    $("#out-pick").next().hide();
                    $("#maintenance-pick").hide();
                    $("#maintenance-pick").next().hide();
                    $("#cancel-pick").hide();
                    $("#cancel-pick").next().hide();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        if(rowData.status == '待拣货'){
                            $("#maintenance-pick").show();
                            $("#maintenance-pick").next().show();
                            $("#cancel-pick").show();
                            $("#cancel-pick").next().show();
                        }else if(rowData.status=='已拣货'){
                            $("#out-pick").show();
                            $("#out-pick").next().show();
                        }
                        else {
                            $("#out-pick").hide();
                            $("#out-pick").next().hide();
                            $("#maintenance-pick").hide();
                            $("#maintenance-pick").next().hide();
                            $("#cancel-pick").hide();
                            $("#cancel-pick").next().hide();
                        }
                        var b = $("#data").datagrid("getRowIndex", a[0].pck_pkid);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#out-pick").hide();
                    $("#out-pick").next().hide();
                    $("#maintenance-pick").hide();
                    $("#maintenance-pick").next().hide();
                    $("#cancel-pick").hide();
                    $("#cancel-pick").next().hide();
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {  //checkbox 全选中事件
                //设置选中事件，清除之前单行选择
                $("#data").datagrid("unselectAll");
                $("#out-pick").hide();
                $("#out-pick").next().hide();
                $("#maintenance-pick").hide();
                $("#maintenance-pick").next().hide();
                $("#cancel-pick").hide();
                $("#cancel-pick").next().hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#out-pick").hide();
                $("#out-pick").next().hide();
                $("#maintenance-pick").hide();
                $("#maintenance-pick").next().hide();
                $("#cancel-pick").hide();
                $("#cancel-pick").next().hide();
                $("#order_child_title").hide().next().hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#order_child_title").hide().next().hide();
            },
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#data").datagrid('clearSelections').datagrid('clearChecked');
            },
        });
        // 点击取消通知按钮
        $('#cancel-pick').click(function(){
            var a = $("#data").datagrid("getSelected");
            cancelpick(a.pck_pkid);
        });
        //导出
        $('#export').click(function () {
            var index = layer.confirm("确定导出拣货单信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export']) . '?' . http_build_query(Yii::$app->request->queryParams) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出拣货单信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });
        $("#out-pick").click(function () {
            var a = $("#data").datagrid("getSelected");
            var url = "<?=Url::to(['out-pick'])?>";
             layer.confirm("确定生成出库单?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"pck_pkid": a.pck_pkid},
                        url: url,
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
//                                        $("#out-pick").next().hide();
//                                        $("#maintenance-pick").hide();
//                                        $("#maintenance-pick").next().hide();
//                                        $("#cancel-pick").hide();
//                                        $("#cancel-pick").next().hide();
                                        parent.$("#edit").hide().next().hide();
                                        parent.$("#maintenance-pick").hide().next().hide();
                                        parent.$("#cancel-pick").hide().next().hide();
                                        parent.$("#data").datagrid('clearChecked');
                                        parent.$("#data").datagrid('clearSelections');
                                        $('#data').datagrid('reload');
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
//                    if (window.location.href = "<?//= Url::to(['out-pick'])?>//?pck_pkid="+a.pck_pkid) {
//                        layer.closeAll();
//                    } else {
//                        layer.alert('出库发生错误', {icon: 0})
//                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        })


    })
    // 取消通知
    function cancelpick(id) {
        $.fancybox({
            type:"iframe",
            padding:0,
            width:550,
            height:400,
            href:"<?=Url::to(['cancel-pick'])?>?id=" + id,
        });
    }
    //拣货数量维护
    $("#maintenance-pick").on("click",function () {
        var a = $("#data").datagrid("getSelected");
        $.fancybox({
            type:"iframe",
            padding:0,
            width:900,
            height:600,
            href:"<?=Url::to(['maintenance-pick'])?>?id=" +a.pck_pkid,
        });
    })
</script>

