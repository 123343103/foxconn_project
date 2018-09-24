<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\classes\Menu;

$this->title = '营销区域设置';

$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '营销区域设置'];
?>
<div class="content">
    <div class="space-20"></div>
    <?= $this->render('_search', [
        'Status' => $Status,
        'saleArea' => $saleArea,
        'queryParam' => $queryParam
    ]) ?>
    <div class="space-30"></div>
    <div class="table-head mb-10">
        <p class="head">营销区域列表</p>
        <div class="float-right">
            <?= Menu::isAction('/crm/area-setting/create') ?
                "<a id='add'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
                : '' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/crm/area-setting/update') ?
            "<a id='edit' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
            </a>"
                : '' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/crm/area-setting/setstatus') ?
            "<a id='setstatus' class='display-none'>
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>设置状态</p>
                    </div>
            </a>"
                : '' ?>
            <span style='float: left;'>&nbsp;|&nbsp;</span>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="data"></div>
</div>

<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        //严格模式
        "use strict";

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "csarea_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            pageSize: 10,
            pageList: [10, 20, 30],
            columns: [[
                {field: "ck", checkbox: true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        var str="";
                    if(row.status=="禁用"){
                        str+="<a class='icon-ok-circle icon-large' style='margin-right:30px;' title='启用' onclick='event.stopPropagation();enableAttr("+row.csarea_id+");'></a>";
                    }else {
                        str+="<a class='icon-remove-circle icon-large' style='margin-right:30px;' title='禁用' onclick='event.stopPropagation();disableAttr("+row.csarea_id+");'></a>";
                    }
                    if(row.status=='启用'){
                        str+="<a class='icon-edit icon-large' title='修改'  href='<?=Url::to(['update'])?>?id="+ row.csarea_id + "'></a>";
                    }
                    return str;
//                    return '<a href="<?//=Url::to(['update'])?>//?id=' + row.csarea_id + '"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a onclick="cancle(\'' + row.csarea_id + '\');"><i class="icon-minus-sign fs-18"></i></a>';
                }
                },
            ]],
            onSelect:function(rowIndex,rowData){
                var index = $("#data").datagrid("getRowIndex", rowData.csarea_id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
                if(rowData.status=='启用'){
                    $("#edit").show();
                    $("#edit").prev().show();
                }else{
                    $("#edit").hide();
                    $("#edit").prev().hide();
                }
                $("#setstatus").show();
                $("#setstatus").prev().show();
            },
            onCheck:function(rowIndex,rowData){
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
                    $("#edit").hide();
                    $("#edit").prev().hide();

                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].csarea_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#edit").hide();
                    $("#edit").prev().hide();
                    $("#setstatus").hide();
                    $("#setstatus").prev().hide();
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
            },

            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#setstatus").show();
                $("#setstatus").prev().show();
                $("#edit").hide();
                $("#edit").prev().hide();
            },
            onUnselectAll: function (rowIndex, rowData) {

            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#setstatus").hide();
                $("#setstatus").prev().hide();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onLoadSuccess: function (data) {
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            }
        });

        //新增销售区域
        $("#add").on("click", function () {
            //                //调用fancybox弹出层
            window.location.href = "<?= Url::to(['create']) ?>";
        });

        //修改销售区域
        $("#edit").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一个销售点", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?= Url::to(['update']) ?>?id=" + obj.csarea_id;
            }
        });

        // 导出
        $('#export').click(function () {
            var index = layer.confirm(
                "确定导出营销区域信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['index', 'export' => '1', 'SaleAreaSearch[csarea_code]' => !empty($queryParam) ? $queryParam['SaleAreaSearch']['csarea_code'] : null, 'SaleAreaSearch[csarea_name]' => !empty($queryParam) ? $queryParam['SaleAreaSearch']['csarea_name'] : null, 'SaleAreaSearch[csarea_status]' => !empty($queryParam) ? $queryParam['SaleAreaSearch']['csarea_status'] : null])?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出销售区域信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });

        //关闭
        $("#close").on("click", function () {
            window.location.href = "<?= Url::to(['/index/index']) ?>";
        })
    })
    $("#setstatus").on("click",function () {
        var arr = [];
        var id;
        var obj = $("#data").datagrid("getChecked");
        for (var i = 0; i < obj.length; i++) {
            arr.push(obj[i].csarea_id);
        }
        id = arr.join(',');
        $("#setstatus").fancybox({
            padding: [],
            fitToView: true,
            width: 540,
            height: 300,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['setstatus'])?>?id="+id
        });
    })
    function getstatus(id) {

    }
//    function cancle(data=null){
//        var arr = [];
//        var id;
//        var url = "<?//=Url::to(['delete'])?>//";
//        if(data == null){
//            var obj = $("#data").datagrid("getChecked");
//            if(obj.length == 0){
//                layer.alert("请先选择一个销售区域", {icon: 2, time: 5000});
//                return false;
//            }
//            for (var i = 0; i < obj.length; i++) {
//                arr.push(obj[i].csarea_id);
//            }
//            id = arr.join(',');
//        }else{
//            id = data;
//        }
//        layer.confirm('确定要删除该数据吗?',
//            {
//                btn: ['确定', '取消'],
//                icon: 2
//            },
//            function () {
//                $.ajax({
//                    type: "get",
//                    dataType: "json",
//                    async: false,
//                    data: {"id": id},
//                    url: url,
//                    success: function (msg) {
//                        if (msg.flag === 1) {
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
//            },
//            function () {
//                layer.closeAll();
//            }
//        )
//    };
    //启用状态
    function enableAttr(id){
        layer.confirm('确定将此数据启用吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['enable-attr'])?>",
                    data:{"id":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#edit").hide().prev().hide();
                                $("#setstatus").hide().prev().hide();
                                $("#data").datagrid('clearChecked');
                                $("#data").datagrid("reload");
                            });
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    }
                })
            },
            layer.closeAll()
        )
    }
    //禁用状态
    function disableAttr(id){
        layer.confirm('确定将此数据禁用吗？',{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['disable-attr'])?>",
                    data:{"id":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                layer.closeAll();
                                $("#edit").hide().prev().hide();
                                $("#setstatus").hide().prev().hide();
                                $("#data").datagrid('clearChecked');
                                parent.$("#data").datagrid('clearChecked');
                                $("#data").datagrid("reload");
                            });
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    }
                })
            },
            layer.closeAll()
        )
    }
</script>
