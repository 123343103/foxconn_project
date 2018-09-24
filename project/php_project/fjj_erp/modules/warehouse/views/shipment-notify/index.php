<?php
use yii\helpers\Url;
use app\classes\Menu;

    $this->title = '出货通知单列表';
    $this->params['homeLike'] = ['label' => '仓储物流管理'];
    $this->params['breadcrumbs'][] = ['label' => '出货通知单列表'];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
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
    <script>
        var id;
        var flag = '';//子表选中标志
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        var $childTableTitle = $("#order_child_title");
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "note_pkid",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                selectOnCheck: false,
                checkOnSelect: false,
                columns: [[
                    {field: "ck", checkbox: true},
                    <?= $columns ?>
                    {field:'note_pkid',title:'操作',width:100,formatter:function(value,rowData){
                        var str="";
                        if(rowData.status == '待处理'){
                            str+="<a class='icon-minus-sign icon-large' style='margin-right:15px;' title='取消出货' onclick='event.stopPropagation();notifyCancel("+value+");'></a>";
                        }
                        return str;
                    }}
                ]],
                onLoadSuccess: function (data) {
                    datagridTip("#data");
                    showEmpty($(this), data.total, 1);
                    setMenuHeight();
                    $("#data").datagrid('clearSelections').datagrid('clearChecked');
                },
                onSelect: function (rowIndex, rowData) {
                    var index = $("#data").datagrid("getRowIndex", rowData.note_pkid);
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
                    if(rowData.status=="待处理"){
                        $("#create-pick").show();
                        $("#create-pick").next().show();
                        $("#cancel-notify").show();
                        $("#cancel-notify").next().show();
                    }else {
                        $("#create-pick").hide();
                        $("#create-pick").next().hide();
                        $("#cancel-notify").hide();
                        $("#cancel-notify").next().hide();
                    }

//                    var notify = $("#data").datagrid("getSelected");
                    $childTableTitle.addClass("table-head mb-5 mt-20").html("<p>商品信息</p>").show().next().show();
//                    var id = notify['note_pkid'];
                    $("#order_child").datagrid({
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-product']) ?>?id=" +rowData.note_pkid,
                        rownumbers: true,
                        method: "get",
                        idField: "ord_id",
                        singleSelect: true,
                        pagination: true,
                        pageSize: 5,
                        pageList: [5, 10, 15],
                        columns: [[
                            <?= $child_columns ?>
                        ]],
                        onLoadSuccess: function (data) {
                            showEmpty($(this), data.total, 0);
                            setMenuHeight();
                            $("#order_child").datagrid('clearSelections');
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
                            if(rowData.status == '待处理'){
                                $("#create-pick").show();
                                $("#create-pick").next().show();
                                $("#cancel-notify").show();
                                $("#cancel-notify").next().show();
                            }else {
                                $("#create-pick").hide();
                                $("#create-pick").next().hide();
                                $("#cancel-notify").hide();
                                $("#cancel-notify").next().hide();
                            }
                            $('#data').datagrid('selectRow', rowIndex);
                        }
                    } else {
                        isCheck = true;
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#create-pick").hide();
                        $("#create-pick").next().hide();
                        $("#cancel-notify").hide();
                        $("#cancel-notify").next().hide();

                    }
                },
                onUncheck: function (rowIndex, rowData) {
                    var a = $("#data").datagrid("getChecked");
                    if (a.length == 1) {
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            isSelect = false;
                            onlyOne = true;
                            if(rowData.status == '待处理'){
                                $("#create-pick").show();
                                $("#create-pick").next().show();
                                $("#cancel-notify").show();
                                $("#cancel-notify").next().show();
                            }else {
                                $("#create-pick").hide();
                                $("#create-pick").next().hide();
                                $("#cancel-notify").hide();
                                $("#cancel-notify").next().hide();
                            }
                            var b = $("#data").datagrid("getRowIndex", a[0].note_pkid);
                            $('#data').datagrid('selectRow', b);
                        }
                    } else if (a.length == 0) {
                        isCheck = false;
                        isSelect = false;
                        onlyOne = false;
                        $("#create-pick").hide();
                        $("#create-pick").next().hide();
                        $("#cancel-notify").hide();
                        $("#cancel-notify").next().hide();
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
                    $("#create-pick").hide();
                    $("#create-pick").next().hide();
                    $("#cancel-notify").hide();
                    $("#cancel-notify").next().hide();
                },
                onUncheckAll: function (rowIndex, rowData) {
                    $("#create-pick").hide();
                    $("#create-pick").next().hide();
                    $("#cancel-notify").hide();
                    $("#cancel-notify").next().hide();
                    $("#order_child_title").hide().next().hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                },
                onUnselectAll: function (rowIndex, rowData) {
                    $("#order_child_title").hide().next().hide();
                },
            });

            $('#export').click(function () {
                var index = layer.confirm("确定导出订单信息?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?= Url::to(['export']) . '?' . http_build_query(Yii::$app->request->queryParams) ?>") {
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

            // 生成拣货单
            $('#create-pick').click(function(){
                var a = $("#data").datagrid("getSelected");
                $.fancybox({
                    type:"iframe",
                    padding:0,
                    width:650,
                    height:600,
                    href:"<?=Url::to(['create-pick'])?>?id=" + a.note_pkid,
                });
//                if (a == null) {
//                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
//                    return false;
//                }
//                $.ajax({
//                    type:'post',
//                    dataType:'json',
////                    data:$("#note-form").serialize(),
//                    url:"<?//= \yii\helpers\Url::to(['create-pick?id='])?>//" + a.sonh_id,
//                    success:function(data){
//                        if(data.status == 1){
//                            layer.alert("生成拣货单成功！",{icon:1,end: function () {
//                                parent.window.location.reload();
////                                $("#data").datagrid('reload');
//                            }});
//                        } else {
//                            layer.alert(data.msg,{icon:1,end: function () {
//
//                            }});
//                        }
//                    },
//                    error: function (data) {
//                    }
//                })
            })

            // 点击取消通知按钮
            $('#cancel-notify').click(function(){
                var a = $("#data").datagrid("getSelected");
                notifyCancel(a.note_pkid);
            })
        });
        // 取消通知
        function notifyCancel(id) {
            $.fancybox({
                type:"iframe",
                padding:0,
                width:550,
                height:400,
                href:"<?=Url::to(['cancel-notify'])?>?id=" + id,
            });
        }
    </script>