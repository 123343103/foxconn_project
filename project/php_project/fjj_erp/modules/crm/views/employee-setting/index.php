<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\classes\Menu;
$this->title = '销售员资料维护';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售员资料维护'];
?>
<div class="content">
    <?= $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam
    ]) ?>
    </br>
    <div class="table-head mb-10">
        <p class="head">销售员资料列表</p>
        <div class="float-right">
            <?= Menu::isAction('/crm/employee-setting/create')?
                "<a id='add'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
                :'' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/crm/employee-setting/update')?
                "<a id='edit' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                </a>"
                :'' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/crm/employee-setting/setstatus')?
               "<a id='setstatus' class='display-none'>
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>设置状态</p>
                    </div>
                </a>"
            :'' ?>
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
            idField: "id",
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
                        if(row.status=='禁用'){
//                            return '<a onclick="cancle(\'' + row.id + '\');" title="启用" class="icon-ok-circle icon-large" onclick=""></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a href="<?//=Url::to(['update'])?>//?id=' + row.code + '"><i class="icon-edit fs-18"></i></a>';
                            str+="<a class='icon-ok-circle icon-large' style='margin-right:30px;' title='启用' onclick='event.stopPropagation();enableAttr("+row.id+");'></a>";
                        }else {
                            str+="<a class='icon-remove-circle icon-large' style='margin-right:30px;' title='禁用' onclick='event.stopPropagation();disableAttr("+row.id+");'></a>";
                        }
                        if(row.status=='启用'){
                            str+="<a class='icon-edit icon-large' title='修改'  href='<?=Url::to(['update'])?>?id="+ row.code + "'></a>";
                        }
                    return str;

                }
                },
            ]],
            onSelect:function(rowIndex,rowData){
                var index = $("#data").datagrid("getRowIndex", rowData.id);
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
                        var b = $("#data").datagrid("getRowIndex", a[0].id);
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
//                $("#edit").hide();
//                $("#edit").prev().hide();
//                $(".setstatus").hide();
//                $(".setstatus").prev().hide();
            }
        });

        //新增销售点
        $("#add").on("click", function () {
            window.location.href = "<?= Url::to(['/crm/employee-setting/create']) ?>";
        });

        //修改销售点
        $("#edit").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一个销售点", {icon: 2, time: 5000});
            } else {
//                alert('跳到修改页面');
                window.location.href = "<?= Url::to(['update']) ?>?id=" + obj.code;
            }
        });

        // 导出
        $('#export').click(function () {
            var index = layer.confirm(
                "确定导出销售员信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['index', 'export' => '1', 'CrmEmployeeSearch[keyWord]' => !empty($queryParam) ? $queryParam['CrmEmployeeSearch']['keyWord'] : null, 'CrmEmployeeSearch[sarole_id]' => !empty($queryParam) ? $queryParam['CrmEmployeeSearch']['sarole_id'] : null, 'CrmEmployeeSearch[sts_id]' => !empty($queryParam) ? $queryParam['CrmEmployeeSearch']['sts_id'] : null])?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出销售员信息发生错误', {icon: 0})
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
            arr.push(obj[i].id);
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
    //删除销售员
//    function cancle(data=null){
//        var arr = [];
//        var id;
//        var url = "<?//=Url::to(['delete'])?>//";
//        if(data == null){
//            var obj = $("#data").datagrid("getChecked");
//            if(obj.length == 0){
//                layer.alert("请先选择一个销售员", {icon: 2, time: 5000});
//                return false;
//            }
//            for (var i = 0; i < obj.length; i++) {
//                arr.push(obj[i].code);
//            }
//            id = arr.join(',');
//        }else{
//            id = data;
//        }
//        data_delete(id,url);
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
