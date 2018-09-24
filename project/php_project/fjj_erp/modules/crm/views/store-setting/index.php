<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\classes\Menu;
$this->title = '销售点维护列表';
$this->params['homeLike'] = ['label'=>'客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售点维护列表'];
?>
<div class="content">
    <?= $this->render('_search', [
        'status' => $storeStatus,
        'saleArea' => $saleArea,
    ]) ?>
    </br>
    <div class="table-head mb-10">
        <p class="head">销售点列表</p>
        <div class="float-right">
            <?= Menu::isAction('/crm/store-setting/create') ?
                "<a id='add'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
                : '' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/crm/store-setting/update') ?
             "<a id='edit' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
             </a>"
             : '' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/crm/store-setting/setstatus') ?
            "<a  id='setstatus' class='display-none'>
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
            idField: "sts_id",
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
                    return "<a class='icon-edit icon-large' title='修改'  href='<?=Url::to(['update'])?>?id="+ row.sts_id + "'></a>";
                }
                },
            ]],
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
            onSelect:function(rowIndex,rowData){
                var index = $("#data").datagrid("getRowIndex", rowData.sts_id);
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
                var status=rowData["status"];
                $("#edit").show();
                $("#edit").prev().show();
                $("#setstatus").show();
                $("#setstatus").prev().show();
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].sts_id);
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
                showEmpty($(this),data.total,1);
            }
        });

        //新增销售点
        $("#add").on("click", function () {
            window.location.href = "<?= Url::to(['/crm/store-setting/create']) ?>";
        });

        //查看销售点
        $("#view").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一个销售点", {icon:2,time:5000});
            } else {
                window.location.href = "<?= Url::to(['view']) ?>?id=" + obj.sts_id;
            }
        });
        //设置状态
        $("#setstatus").on("click",function () {
            var arr = [];
            var id;
            var obj = $("#data").datagrid("getChecked");
            for (var i = 0; i < obj.length; i++) {
                arr.push(obj[i].sts_id);
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
        //修改销售点
        $("#edit").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一个销售点", {icon:2,time:5000});
            } else {
                window.location.href = "<?= Url::to(['update']) ?>?id=" + obj.sts_id;
            }
        });

        // 导出
        $('#export').click(function() {
            var index = layer.confirm(
                "确定导出销售点信息?",
                {
                    btn:['確定', '取消'],
                    icon:2
                },
                function () {
                    if(window.location.href="<?= Url::to(['index', 'export' => '1','StoreSettingSearch[sts_code]'=>!empty($queryParam) ?$queryParam['StoreSettingSearch']['sts_code']:null,'StoreSettingSearch[sts_sname]'=>!empty($queryParam) ?$queryParam['StoreSettingSearch']['sts_sname']:null,'StoreSettingSearch[csarea_id]'=>!empty($queryParam) ?$queryParam['StoreSettingSearch']['csarea_id']:null,'StoreSettingSearch[sts_status]'=>!empty($queryParam) ?$queryParam['StoreSettingSearch']['sts_status']:null])?>"){
                        layer.closeAll();
                    }else{
                        layer.alert('导出销售点信息发生错误',{icon:0})
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
//    function cancle(data=null){
//        var arr = [];
//        var id;
//        var url = "<?//=Url::to(['delete-store'])?>//";
//        if(data == null){
//            var obj = $("#data").datagrid("getChecked");
//            if(obj.length == 0){
//                layer.alert("请先选择一个销售员", {icon: 2, time: 5000});
//                return false;
//            }
//            for (var i = 0; i < obj.length; i++) {
//                arr.push(obj[i].sts_id);
//            }
//            id = arr.join(',');
//        }else{
//            id = data;
//        }
//        data_delete(id,url);
//    };
</script>
