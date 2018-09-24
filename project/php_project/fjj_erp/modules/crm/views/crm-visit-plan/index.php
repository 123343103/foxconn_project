<?php
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '拜访计划管理'];
$this->title = "拜访计划管理";
?>
<div class="content">
    <?= $this->render('_search', [
        'downList' => $downList,
        'search' => $search
    ]) ?>
    <div class="table-content">
        <?php echo $this->render('_action'); ?>
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
</div>
<script>
    function showcause(type, id, cause) {
        $.fancybox({
            type: "iframe",
            width: 400,
            height: 260,
            autoSize: false,
            href: "<?=Url::to(['cause'])?>?svp_id=" + id + "&type=" + type + "&cause=" + cause,
            padding: 0
        });
    }
    function editW(svp_id) {
        window.location.href = "<?= Url::to(['update']) ?>?id=" + svp_id;
    }

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
            idField: "svp_id",
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
                {field: "handle", title: "操作", width: "60", formatter: function (value, row, index) {

                    var update = '<a onclick="editW(' + row.svp_id + ')" class="icon-edit icon-large" title="修改"></a>';
                    if (row.status == "待实施") {
                        return '<a onclick="showcause(1,' + row.svp_id + ')" class="icon-minus-sign icon-large" title="取消计划" style="margin-right: 15px;"></a>' + update;
                    }
                    if (row.status == "实施中") {
                        return '<a onclick="showcause(2,' + row.svp_id + ')" class="icon-minus-sign-alt icon-large" title="终止计划" style="margin-right: 15px;"></a>' + update;
                    }
                }}
            ]],
            onLoadSuccess: function (data) {
                if(data.total === 0){
                    $("#export").hide().next().hide();
                }else{
                    $("#export").show().next().show();
                }
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#data").datagrid('clearSelections').datagrid('clearChecked');
                $("#update").hide().next().hide();
                $("#cancel").hide().next().hide();
                $("#add-visit-record").hide().next().hide();
                $("#stop").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#data").datagrid("getRowIndex", rowData.svp_id);
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

                var status = rowData['status'];
                if (status == "待实施") {
                    $("#update").show();
                    $("#cancel").show();
                    $("#add-visit-record").show().next().show();
                    $("#stop").hide();
                    $("#update1").show();
                    $("#cancel1").show();
                    $("#stop1").hide();
                }
                if (status == "实施中") {
                    $("#update").show().next().show();
                    $("#cancel").hide();
                    $("#add-visit-record").show();
                    $("#stop").show();
                    $("#cancel1").hide();
                    $("#add-visit-record1").show();
                    $("#stop1").show();
                }
                if (status == "已实施") {
                    $("#update").hide();
                    $("#cancel").hide();
                    $("#add-visit-record").hide();
                    $("#stop").hide();
                    $("#update1").hide();
                    $("#cancel1").hide();
                    $("#add-visit-record1").hide();
                    $("#stop1").hide();
                }
                if (status == "已取消" || status == "已终止" || status == "已结束") {
                    $("#update").hide();
                    $("#cancel").hide();
                    $("#add-visit-record").hide();
                    $("#stop").hide();
                    $("#update1").hide();
                    $("#cancel1").hide();
                    $("#add-visit-record1").hide();
                    $("#stop1").hide();
                }
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

                    $("#update").hide();
                    $("#update").next().hide();
                    $("#add-visit-record").hide().next().hide();
                    $.each(a1,function(i,n){
                        if(n.status !== '待实施'){
                            $("#cancel").hide().next().hide();
                        }
                        if(n.status !== '实施中'){
                            $("#stop").hide().next().hide();
                        }
                    });
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].svp_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#update").hide().next().hide();
                    $("#cancel").hide().next().hide();
                    $("#stop").hide().next().hide();
                    $("#add-visit-record").hide().next().hide();
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }else{
                    $.each(a,function(i,n){
                        if(n.status !== '待实施'){
                            $("#cancel").hide().next().hide();
                        }
                        if(n.status !== '实施中'){
                            $("#stop").hide().next().hide();
                        }
                    });
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#update").hide();
                $("#update").next().hide();
                $("#add-visit-record").hide();
                $("#add-visit-record").next().hide();
            },
            onUnselectAll: function (rowIndex, rowData) {

            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#add-visit-record").hide().next().hide();
            }
        });

        $("#create").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                window.location.href = "<?= Url::to(['create']) ?>";
                return false;
            }
            if(obj.customerManager == ''){
                layer.alert("该客户无客户经理人，不可新增拜访记录！", {icon: 2, time: 5000});
                return false;
            }
            window.location.href = "<?= Url::to(['create']) ?>?customerId=" + obj.cust_id;
        });
        $("#update").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一条拜访计划", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?= Url::to(['update']) ?>?id=" + obj.svp_id;
            }
        });
        //查看拜访计划
        $("#view").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请先选择一条拜访计划", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?= Url::to(['view']) ?>?id=" + obj.svp_id;
            }
        });

        //取消拜访计划
        $("#cancel").on("click", function () {
            var rows = $("#data").datagrid("getChecked");
            var ids = new Array();

            for (var x = 0; x < rows.length; x++) {
                ids.push(rows[x].svp_id);
                if (rows[x].status != "实施中" && rows[x].status != "待实施") {
                    layer.alert("状态为实施中或待实施的才能取消或终止计划", {icon: 2, time: 5000});
                    return false;
                }
                var so_type = rows[0].status;
                if (so_type != rows[x].status) {
                    layer.alert("状态不同", {icon: 2});
                    return false;
                }
            }
            if (ids.length < 1) {
                layer.alert("请先选择一条拜访计划", {icon: 2, time: 5000});
                return false;
            }
            $.fancybox({
                type: "iframe",
                width: 400,
                height: 250,
                autoSize: false,
                href: "<?=Url::to(['cause'])?>?svp_id=" + ids + "&type=1",
                padding: 0
            });
        });
        $("#stop").on("click", function () {
            var rows = $("#data").datagrid("getChecked");
            var ids = new Array();

            for (var x = 0; x < rows.length; x++) {
                ids.push(rows[x].svp_id);
                if (rows[x].status != "实施中" && rows[x].status != "待实施") {
                    layer.alert("状态为实施中或待实施的才能取消或终止计划", {icon: 2, time: 5000});
                    return false;
                }
                var so_type = rows[0].status;
                if (so_type != rows[x].status) {
                    layer.alert("状态不同", {icon: 2});
                    return false;
                }
            }
            if (ids.length < 1) {
                layer.alert("请先选择一条拜访计划", {icon: 2, time: 5000});
                return false;
            }
            $.fancybox({
                type: "iframe",
                width: 400,
                height: 250,
                autoSize: false,
                href: "<?=Url::to(['cause'])?>?svp_id=" + ids + "&type=2",
                padding: 0
            });

        });
        //导出
        $("#export").click(function () {
            var obj = $("#data").datagrid('getData');
            if (obj.total == 0) {
                layer.alert('不可导出！', {icon: 2, time: 5000});
                return false;
            }
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    var url="<?=Url::to(['index'])?>";
                    url+='?export=1';
                    url+='&cust_sname='+$("#cust_sname").val();
                    url+='&svp_status='+$("#svp_status").val();
                    url+='&svp_type='+$("#svp_type").val();
                    url+='&start='+$("#start").val();
                    url+='&end='+$("#end").val();
                    location.href=url;
                },
                layer.closeAll()
            );
        });
        //新增拜访记录
        $("#add-visit-record").on("click", function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert("请选择一条客户信息", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?= Url::to(['/crm/crm-visit-record/add']) ?>?customerId=" + obj.cust_id + '&planId=' + obj.svp_id;
            }
        });

        //关闭
        $("#close").on("click", function () {
            window.location.href = "<?= Url::to(['/index/index']) ?>";
        });

        //删除
        $("#delete_btn").click(function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                layer.alert('请选择计划！', {icon: 2, time: 5000});
                return false;
            }
            if (obj.status == '已实施') {
                layer.alert('计划已实施，不可删除！', {icon: 2, time: 5000});
                return false;
            }
            layer.confirm('确定删除吗？', {icon: 2},
                function () {
                    $.ajax({
                        url: "<?=Url::to(['delete-plan'])?>",
                        data: {"id": obj.svp_id},
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {icon: 1}, function () {
                                    $("#data").datagrid('reload').datagrid('clearSelections');
                                    layer.closeAll();
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2}, function () {
                                    layer.closeAll();
                                });
                            }
                        }
                    });
                },
                function () {
                    layer.closeAll();
                }
            );
        });

    });


</script>