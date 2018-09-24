<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/13
 * Time: 14:49
 */
use app\classes\Menu;
use yii\helpers\Url;

$this->title = '销售客户列表';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客戶列表'];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam,
    ]); ?>
    <div class="space-20"></div>
    <?php echo $this->render('_action', ['e' => $e, 'isSuper' => $isSuper]); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data">
        </div>
    </div>
</div>
<script>
    $(function () {
        var isSuper = '<?= $isSuper ?>' == 0 ? false : true;
        var loginId = '<?= $loginId ?>';
        var username = '<?=\Yii::$app->user->identity->staff->staff_name?>';
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: '加载中...',
            pagination: true,
            singleSelect: true,         //只能选中一行
            selectOnCheck: false,        //选中复选框同时选中行
            checkOnSelect: false,        //选中行同时选中复选框
            columns: [[
                {field: "", checkbox: true},
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    if (row.manager) {
                        var managers = row.manager;
                        var managers = row.manager.split(",");
                        var managers = managers.filter(function (i) {
                            return i == username;
                        });
                    } else {
                        managers = [];
                    }
                    if (row.saleStatus == '10') {
                        if (((row.apply_status != 20 && row.apply_status != 30 && (managers.length > 0 || isSuper)))) {
                            if (row.apply_status == 40) {
                                return '<?= Menu::isAction('/crm/crm-customer-info/delete') ? "<a title=\'删除\' onclick=\"cancle_one(' + row.cust_id + ')\"><i class=\"icon-minus-sign fs-18\"></i></a>" : '<a onclick="return false;" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;' ?>&nbsp;&nbsp;' + '&nbsp;&nbsp;<?= Menu::isAction('/crm/crm-customer-info/update') ? "<a title=\'修改\' onclick=\"update_mes('+ row.cust_id + ',' + row.apply_status +')\"><i class=\"icon-edit fs-18\"></i></a>" : '&nbsp;&nbsp;<a onclick="return false;" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>' ?>';
                            } else {
                                return '<?= Menu::isAction('/crm/crm-customer-info/delete') ? "<a title=\'删除\' onclick=\"cancle_one(' + row.cust_id + ')\"><i class=\"icon-minus-sign fs-18\"></i></a>" : '<a onclick="return false;" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;' ?>&nbsp;&nbsp;' + '&nbsp;&nbsp;<?= Menu::isAction('/crm/crm-customer-info/update') ? "<a title=\'修改\' onclick=\"update_mes('+ row.cust_id + ',' + row.apply_status +')\"><i class=\"icon-edit fs-18\"></i></a>" : '&nbsp;&nbsp;<a onclick="return false;" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>' ?>';
                            }

                        } else {
                            return '<a title=\'删除\' onclick="return false;" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>' + '&nbsp;&nbsp;<a title=\'修改\' onclick="return false;" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;';
                        }
                    } else if ((row.saleStatus == 0 && managers.length > 0) || isSuper) {
                        return '<?= Menu::isAction('/crm/crm-customer-info/activation') ? "<a title=\'激活\' onclick=\"activaion_one('+ row.cust_id +')\"><i class=\"icon-ok-sign fs-18\"></i></a>&nbsp;" : "<a style=\"color:#ccc;\" onclick=\"return false\"><i class=\"icon-plus-sign fs-18\"></i></a>&nbsp;" ?>'
                    } else {
                        return '<a title=\'激活\' style="color:#ccc;" onclick="return false"><i class="icon-ok-sign fs-18"></i></a>&nbsp;'
                    }
                }
                },
            ]],
            /*F1678086 10/27 更改为批量操作按钮在多选时全部显示*/
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.cust_id);
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
                var managers;
                if (rowData.manager) {
                    managers = rowData.manager.split(",");
                    managers = managers.filter(function (i) {
                        return i == username;
                    });
                } else {
                    managers = [];
                }
                var event = arguments.callee.caller.caller.arguments[0];
                event && $("#data").datagrid("clearChecked");
                event && $("#data").datagrid("checkRow", rowIndex);
                if (isSuper || managers.length == 1) {
                    /*账信申请按钮*/
                    //                      if (rowData.apply_status == 40 && rowData.saleStatus == "10") {
                    if (rowData.apply_status == 40 && rowData.saleStatus == "10" && (rowData.credit_apply == 10 || rowData.credit_apply == '' || rowData.credit_apply == null)) {
                        $("#credit_apply").show();
                    } else {
                        $("#credit_apply").hide();
                    }
                    /*删除 修改*/
                    if (rowData.saleStatus == 10 && rowData.apply_status != 20 && rowData.apply_status != 30) {
                        $('#update,#delete,.take_inch,.take_assign').show();
                    } else {
                        $('#update,#delete,.take_inch,.take_assign').hide();
                    }
                    /*拜访*/
                    if (rowData.saleStatus == 10) {
                        $("#visit").show();
                    } else {
                        $('#visit').hide();
                    }
                    /*抛至公海,荐招商*/
                    if ((rowData.cust_code == '' || rowData.cust_code == null) && rowData.apply_status != 20 && rowData.apply_status != 30 && rowData.saleStatus == 10) {
                        $('.take_investment,.take_sea').show();
                    } else {
                        $('.take_investment,.take_sea').hide();
                    }
                    /*认领*/
//                    if (rowData.personinch_status == 10 && (rowData.cust_code != '' || rowData.cust_code != null) && rowData.apply_status != 20 && rowData.apply_status != 30 && rowData.saleStatus == 10) {
                    if (rowData.personinch_status == 10 && rowData.apply_status != 20 && rowData.apply_status != 30 && rowData.saleStatus == 10 && (rowData.cust_code == '' || rowData.cust_code == null)) {
                        $('#apply').show();
                    } else {
                        $('#apply').hide();
                    }
                    /*激活*/
                    if (rowData.saleStatus != "10") {
                        $("#activation").show();
                    } else {
                        $("#activation").hide();
                    }
                } else {
                    if (rowData.saleStatus == 10 && rowData.apply_status != 20 && rowData.apply_status != 30) {
                        $('.take_inch,.take_assign').show();
                    } else {
                        $('.take_inch,.take_assign').hide();
                    }
                }
            },
            onCheck: function (rowIndex, rowData) {
                var rows = $("#data").datagrid("getChecked");
                if (rows.length == 1) {
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
                    $('#update,#visit,#apply,#credit_apply').hide();
                    $('.take_inch,.take_assign,.take_investment,.take_sea,#delete').show();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].cust_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#credit_apply,#update,#delete,#visit,#apply,.take_inch,.take_assign,.take_sea,.take_investment").hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#delete,.take_investment,.take_sea,.take_inch,.take_assign").show();
                $("#update,#credit_apply,#apply,#visit").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#credit_apply,#update,#delete,#visit,#apply,.take_inch,.take_assign,.take_sea,.take_investment").hide();
            },
            onLoadSuccess: function (data) {
                $(this).datagrid('clearChecked');
                $(this).datagrid('clearSelections');
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            }
        });
        /*账信申请*/
        $("#credit_apply").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            } else {
                if (a.credit_apply == 10 || a.credit_apply == '' || a.credit_apply == null) {
                    window.location.href = "<?=Url::to(['/crm/crm-customer-info/credit-create'])?>" + "?id=" + a.cust_id;

                } else {
                    layer.alert('已在申请', {icon: 2});
                    return false;
                }
            }
        });
        /*新增*/
        $("#add").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>";
        });
        /*更新*/
        $("#update").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            } else {
                if (a.apply_status == 40) {
                    window.location.href = "<?=Url::to(['update'])?>" + "?id=" + a.cust_id + '&type=1';
                } else {
                    window.location.href = "<?=Url::to(['update'])?>" + "?id=" + a.cust_id;
                }
//                    if(a.manager_id== loginId || a.personinch_status == 0 ||  isSuper== 1){
//                        window.location.href = "<?//=Url::to(['update'])?>//"+"?id=" + a.cust_id;
//                    }else{
//                        layer.confirm('无法修改!',{icon:2,time:5000});
//                        return false;
//                    }
            }
        });
        /*详情*/
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            } else {
                window.location.href = "<?=Url::to(['view'])?>" + "?id=" + a.cust_id;
            }
        });

        /*申请客户代码*/
        $("#apply").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            } else {
                window.location.href = "<?=Url::to(['/crm/crm-customer-info/customer-info'])?>" + "?id=" + a.cust_id + "&status=10";
            }
        });
        /*新增拜访计划*/
        $("#visit-plan").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            } else {
                if (a.personinch_status == 0) {
                    layer.alert('请先认领', {icon: 2});
                    return false;
                }
                window.location.href = "<?= Url::to(['/crm/crm-visit-plan/create']) ?>" + "?customerId=" + a.cust_id;
            }
        });
        /*新增拜访记录*/
        $("#visit-record").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            } else {
                if (a.personinch_status == 0) {
                    layer.alert('请先认领', {icon: 2});
                    return false;
                }
//                window.location.href = "<?//=Url::to(['record-add'])?>//" + "?customerId=" + a.cust_id + "&planId=";
                window.location.href = "<?=Url::to(['/crm/crm-visit-record/add'])?>" + "?customerId=" + a.cust_id;
            }
        });
        /*导出客户信息*/
        $('#export').click(function () {
//              获取列表页数及显示条数
//            var options = $('#data').datagrid('getPager').data("pagination").options;
//            var pageNum = options.pageNumber; //显示条数
//            var pageSize = options.pageSize;  //页码
            var index = layer.confirm("确定导出客户信息?",
                {
                    btn: ['確定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export', 'CrmCustomerInfoSearch[cust_filernumber]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_filernumber'] : null, 'CrmCustomerInfoSearch[cust_sname]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_sname'] : null, 'CrmCustomerInfoSearch[cust_type]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_type'] : null, 'CrmCustomerInfoSearch[cust_salearea]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['cust_salearea'] : null, 'CrmCustomerInfoSearch[custManager]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['custManager'] : null, 'CrmCustomerInfoSearch[personinch_status]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['personinch_status'] : null, 'CrmCustomerInfoSearch[sale_status]' => !empty($queryParam) ? $queryParam['CrmCustomerInfoSearch']['sale_status'] : null])?>") {
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
    });
    /*修改客户信息*/
    function update_mes(id, status) {
        if (status == null) {
            window.location.href = "<?=Url::to(['update'])?>" + "?id=" + id;
        } else {
            window.location.href = "<?=Url::to(['update'])?>" + "?id=" + id + '&type=1';
        }
    }
    /*删除客户信息 -- 单条*/
    function cancle_one(id) {
        var url = "<?=Url::to(['delete'])?>";
        data_delete(id, url);
    }
    /*批量删除客户信息*/
    //    function cancle() {
    //        var isSuper = '<?//= $isSuper ?>//';
    //        var loginId = '<?//= $loginId ?>//';
    //        var arr = [];
    //        var arrName = [];
    //        var a = $("#data").datagrid("getChecked");
    //        var url = "<?//=Url::to(['delete'])?>//";
    //        for (var i = 0; i < a.length; i++) {
    //            if ((a[i].manager_id != loginId && a[i].personinch_status != 0 && isSuper != 1) || a[i].apply_status == '30' || a[i].apply_status == '20') {
    //                if (typeof(a[i]) != 'undefined') {
    //                    arr.push(a[i]);
    //                    arrName.push(a[i].cust_sname);
    //                }
    //            }
    //        }
    //        var result = [];
    //        for (var x = 0; x < a.length; x++) {
    //            var obj = a[x];
    //            var isSame = 0;
    //            for (var y = 0; y < arr.length; y++) {
    //                var ns = arr[y];
    //                if (ns.cust_id == obj.cust_id) {
    //                    isSame++;
    //                }
    //            }
    //            if (isSame == 0) {
    //                result.push(obj.cust_id);
    //            }
    //        }
    //        var id = result.join(',');
    //        var str = '';
    //        if (arr.length == 0) {
    //            str = '确定要删除客户吗?'
    //        } else {
    //            str = arr[0].cust_sname + '等客户无法删除!'
    //        }
    //        layer.confirm(str,
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
    //                                    $('#data').datagrid('reload');
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
    //    }
    function cancle() {
        var a = $("#data").datagrid("getChecked");
        var arr = [];
        var url = "<?=Url::to(['delete'])?>";
        for (var i = 0; i < a.length; i++) {
            arr.push(a[i].cust_id);
        }
        var id = arr.join(',');
        layer.confirm('确定删除?',
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
                    url: url,
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
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
            },
            function () {
                layer.closeAll();
            }
        )
    }
    /*激活客户 --单条*/
    function activaion_one(id) {
        var url = "<?=Url::to(['activation'])?>";
        layer.confirm('确定要激活此客户吗?',
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
                    url: url,
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
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
            },
            function () {
                layer.closeAll();
            }
        )
    }
    function activation() {
        var a = $("#data").datagrid("getChecked");
        var arr = [];
        var url = "<?=Url::to(['activation'])?>";
        for (var i = 0; i < a.length; i++) {
            arr.push(a[i].cust_id);
        }
        var id = arr.join(',');
        layer.confirm('确定激活?',
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
                    url: url,
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    $("#data").datagrid('reload');
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
</script>