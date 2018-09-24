<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 14:49
 */
use yii\helpers\Url;

$this->title = '客户代码申请列表';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客戶代码申请列表'];
?>
<div class="content">
    <input type="hidden" value="<?= $staffName ?>" id="staffname">
    <input type="hidden" value="<?= $result ?>" id="isSupper">
    <?php echo $this->render('_search', [
        'staffName' => $staffName,
        'district' => $district,
        'downList' => $downList,
        'custLevel' => $custLevel
    ]); ?>

    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
        </div>
    </div>
</div>
<script>
    var id;
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    var isSupper = $("#isSupper").val();
    var staffname = $("#staffname").val();

    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "capply_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: "ck", checkbox: true},
                <?= $columns ?>
                {
                    field: 'name', title: '操作', width: 100, formatter: function (val, row) {
                    if (row.status == "驳回" && (row.cust_manager == staffname || isSupper == true)) {
                        return '<a  onclick="temp(' + row.capply_id + ');" title="取消"><i class="icon-minus-sign  fs-18"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=Url::to(['customer-info'])?>?id=' + row.cust_id + '"  title="修改"><i class="icon-edit  fs-18"></i></a>';
                    }
                    else {

                        return '';
                    }
                }
                }
            ]],
            onSelect: function (rowIndex, rowData) {
                if (rowData.cust_id == null) {
                    $("#data").datagrid('unselectRow', 0);
                    return false;
                }
                var index = $("#data").datagrid("getRowIndex", rowData.capply_id);
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
//                    $("#update,#delete,#view,.take_inch,.take_investment,.take_record,.take_sea,#apply").show();
//                    $("#data").datagrid("uncheckAll");
            },
            onCheck: function (rowIndex, rowData) {
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
                }
                var staffname = $("#staffname").val();
                if (rowData.status == "驳回" && isSupper == true) {
                    for (var i = 0; i < a1.length; i++) {
                        if (a1[i].status != "驳回") {
                            a2 = false;
                            break;
                        }
                    }
                    if (a2 == true) {
                        $("#check").show();
                        $("#update").show();
                        $("#cannel").show();
                    }
                    else {
                        $("#check").hide();
                        $("#update").hide();
                        $("#cannel").hide();
                    }
                }
                else {
                    for (var i = 0; i < a1.length; i++) {
                        console.log(a1[i].cust_manager);

                        if (a1[i].status != "驳回" || a1[i].cust_manager.indexOf(staffname)<0) {
                            a2 = false;
                            break;
                        }
                    }
                    if (a2 == true) {
                        $("#check").show();
                        $("#update").show();
                        $("#cannel").show();
                    }
                    else {
                        $("#check").hide();
                        $("#update").hide();
                        $("#cannel").hide();
                    }

                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                var staffname = $("#staffname").val();
                var a2 = true;
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].capply_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                if (isSupper == true) {
                    if (a.length == 0) {
                        a2 = false;
                    }
                    else {
                        for (var i = 0; i < a.length; i++) {
                            if (a[i].status != "驳回") {
                                a2 = false;
                                break;

                            }
                        }
                    }
                    if (a2 == true) {
                        $("#check").show();
                        $("#update").show();
                        $("#cannel").show();
                    }
                    else {
                        $("#check").hide();
                        $("#update").hide();
                        $("#cannel").hide();
                    }
                }
                else {
                    if (a.length == 0) {
                        a2 = false;
                    }
                    else {
                        for (var i = 0; i < a.length; i++) {
                            if (a[i].status != "驳回" || a[i].cust_manager != staffname) {
                                a2 = false;
                                break;
                            }
                        }
                    }
                    if (a2 == true) {
                        $("#check").show();
                        $("#update").show();
                        $("#cannel").show();
                    }
                    else {
                        $("#check").hide();
                        $("#update").hide();
                        $("#cannel").hide();
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                var staffname = $("#staffname").val();
                var a2 = true;
                var a = $("#data").datagrid("getChecked");
                if (isSupper = true) {
                    for (var i = 0; i < a.length; i++) {
                        if (a[i].status != "驳回"
                        ) {
                            a2 = false;
                            break;
                        }
                    }

                }
                else {
                    for (var i = 0; i < a.length; i++) {
                        if (a[i].status != "驳回" || a[i].cust_manager != staffname) {
                            a2 = false;
                            break;
                        }
                    }

                }
                if (a2 == true) {
                    $("#check").show();
                    $("#update").show();
                    $("#cannel").show();
                }
                else {
                    $("#check").hide();
                    $("#update").hide();
                    $("#cannel").hide();
                }
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#check").hide();
                $("#update").hide();
                $("#cannel").hide();
            },
            onLoadSuccess: function (data) {
                $("#data").datagrid('clearSelections');
                $("#data").datagrid('clearChecked');
//                for (var i = 0; i < data.rows.length; i++) {
//                    //根据status值使复选框 不可用
//                    if (data.rows[i].status != "驳回") {
//                        $("input[type='checkbox']")[i + 1].disabled = true;
//                        $("input[type='checkbox']")[i + 1].checked = false;
//                        $('#data').datagrid('unselectRow', i);
//                    }
//                }
                setMenuHeight();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            },
//            onCheckAll: function (rows) {
//                $.each(rows, function (i, n) {
//                    if (n.status != "驳回") {
//                        $("input[type='checkbox']")[i + 1].checked = false;
//                        $('#data').datagrid('unselectRow', i);
//                    }
//                })
//            },
//            onClickRow: function (rowIndex, rowData) {
//                if (rowData.status != "驳回") {
//                    $('#data').datagrid('unselectRow', rowIndex);
//                }
//            }
        });
        $("#add").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>";
        });
        $("#update").on("click", function () {
            var a = $("#data").datagrid("getChecked");
            if (a.length != 1) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['cust_id'];
                window.location.href = "<?=Url::to(['customer-info'])?>?id=" + id;
            }
        });
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条申请信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['cust_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
        $("#delete").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['capply_id'];
                var index = layer.confirm("确定要删除这条记录吗?",
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": id},
                            url: "<?=Url::to(['delete']) ?>",
                            success: function (msg) {
                                if (msg.flag === 1) {
                                    layer.alert(msg.msg, {
                                        icon: 1, end: function () {
                                            location.reload();
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
            }
        });
        $('#export').click(function () {
            var index = layer.confirm("确定导出客户信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export', 'export' => '1', 'CrmCustomerApplySearch[cust_level]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_level'] : null, 'CrmCustomerApplySearch[cust_filernumber]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_filernumber'] : null, 'CrmCustomerApplySearch[cust_sname]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_sname'] : null, 'CrmCustomerApplySearch[cust_type]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_type'] : null, 'CrmCustomerApplySearch[cust_salearea]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_salearea'] : null, 'CrmCustomerApplySearch[applyperson]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['applyperson'] : null, 'CrmCustomerApplySearch[cust_manager]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_manager'] : null, 'CrmCustomerApplySearch[status]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['status'] : null, 'CrmCustomerApplySearch[cust_area]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_area'] : null, 'CrmCustomerApplySearch[cust_contacts]' => !empty($queryParam) ? $queryParam['CrmCustomerApplySearch']['cust_contacts'] : null])?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出客户信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });
        $("#cannel").on("click", function () {
            var data = $("#data").datagrid("getChecked");
            if (data.length == 0) {
                layer.alert("请至少选择一条取消信息", {icon: 2, time: 5000});
                return false;
            }
            ;
            var capplyid = '';
            for (var i = 0; i < data.length; i++) {
                capplyid += data[i].capply_id + '-';
            }
            $.fancybox({
                type: "iframe",
                width: 400,
                height: 250,
                autoSize: false,
                href: "<?=Url::to(['cannel'])?>?capply_id=" + capplyid,
                padding: 0
            });
        })
        $("#check").on("click", function () {
            var data = $("#data").datagrid("getChecked");
            if (data.length == 0) {
                layer.alert("请至少选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
//                layer.confirm("确定要送审这条记录吗",
//                    {
//                        btn:['确定', '取消'],
//                        icon:2
//                    },
//                    function () {
//                        $.ajax({
//                            type: "get",
//                            dataType: "json",
//                            data: {"id": data['capply_id']},
//                            url: "<?//=Url::to(['check']) ?>//",
//                            success: function (msg) {
//                                if (msg.flag === 1) {
//                                    layer.alert("送审成功", {
//                                        icon: 1, end: function () {
//                                            window.location.href = "<?//=Url::to(['view'])?>//?id="+data['cust_id'];
//                                        }
//                                    });
//                                }else{
//                                    layer.alert("送审失败", {icon: 2})
//                                }
//                            },
//                        })
//                    },
//                    function () {
//                        layer.closeAll();
//                    }
//                )
            var id = '';
            for (var i = 0; i < data.length; i++) {
                id += data[i].capply_id + '-';
            }
            var url = "<?=Url::to(['index'], true)?>";
            var type = "<?= $typeId ?>";
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/new-reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });
    })
    //取消代码申请
    function temp(capplyid) {
        $.fancybox({
            type: "iframe",
            width: 400,
            height: 250,
            autoSize: false,
            href: "<?=Url::to(['cannel'])?>?capply_id=" + capplyid,
            padding: 0
        });
    }
</script>