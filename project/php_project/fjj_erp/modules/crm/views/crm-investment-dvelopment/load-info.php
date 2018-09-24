<?php
/**
 * User: F3859386
 * Date: 2017/2/27
 * Time: 10:34
 */
use yii\helpers\Url;

?>
<style>
    .fancybox-skin {
        padding: 0 0 15px 0 !important;
    }

    .datagrid-view .datagrid-editable-input {
        width: 100% !important;
        height: 100% !important;
    }
</style>
<div class="space-10"></div>
<div id="tabs" class="easyui-tabs" style="height:auto;">
    <div title="拜访记录">
        <div id="record"></div>
    </div>
    <?= ($ctype != 3) ?
        '<div title="开店信息">
        <div id="shop-info"></div>
    </div>' : "" ?>
    <div title="提醒事项">
        <div id="reminder"></div>
    </div>
    <div title="通讯记录">
        <div id="message"></div>
    </div>
    <div title="其他联系人">
        <div id="contacts"></div>
    </div>
</div>
<script>
    var id = '<?= $id ?>';
    $(function () {

        $("#tabs").tabs({
            tabPosition: 'top',
            height: 'auto'
        });
        $("#record").datagrid({
            url: "<?= Url::to(['load-record']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "sil_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                <?= $columns['visit'] ?>
                {
                    field: "action", width: 150, title: "操作", formatter: function (value, row, index) {
                    if (index + 1 == 1) {
                        return "<a class='ml-20 delete-visit-record' data-id='" + row.sil_id + "'><i class='icon-minus-sign fs-15' title='删除'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class='ml-20 edit-visit-record' data-id='" + row.sil_id + "'  title='修改'><i class='icon-edit fs-15'></i></a>"
                    }

                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this), data.total, 0);
            }
        });


        $("#shop-info").datagrid({
            url: "<?= Url::to(['load-shop']);?>?id=" + id,
            rownumbers: true,
            idField: "shop_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            method: "get",
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                <?= $columns['shop'] ?>
                {
                    field: "action", width: 150, title: "操作", formatter: function (value, row, index) {
                    return '<a onclick="delete_shop_info(' + row.shop_id + ')" class="ml-10"><i class="icon-minus-sign fs-15"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;' +
                        '<a onclick="edit_shop_info(' + row.shop_id + ')"> <i class="icon-edit fs-15"></i></a>';
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#shop-info');
                showEmpty($(this), data.total, 0);
            }
        });


        $("#reminder").datagrid({
            url: "<?= Url::to(['load-reminders']);?>?id=" + id,
            rownumbers: true,
            idField: "imesg_id",
            method: "get",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                <?= $columns['reminder'] ?>
                {
                    field: "action", title: "操作", width: 100, formatter: function (val, row) {
                    if (row.imesg_status == '1') {
                        var cur_time = '<?= date("Y-m-d H:i:s", time()) ?>';
                        if (row.imesg_btime < cur_time && row.imesg_etime > cur_time) {
                            return '<a id="reminder_update" onclick="reminder_update(' + row.imesg_id + ')"><i class="icon-edit fs-18"></i></a>';
                        } else if (row.imesg_btime > cur_time) {
                            return '<a onclick="reminder(' + row.imesg_id + ')"><i class="icon-minus-sign fs-15"></i></a> &nbsp;&nbsp; <a id="reminder_update" onclick="reminder_update(' + row.imesg_id + ')"><i class="icon-edit fs-15"></i></a>';
                        }
                    }
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#reminder');
                showEmpty($(this), data.total, 0);
            }
        });

        $("#message").datagrid({
            url: "<?= Url::to(['load-message']);?>?id=" + id,
            rownumbers: true,
            idField: "imesg_id",
            method: "get",
            loadMsg: false,
            pagination: true,
            singleSelect: false,
            columns: [[
                <?= $columns['message'] ?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#message');
                showEmpty($(this), data.total, 0);
            }
        });

        $("#contacts").datagrid({
            url: "<?= Url::to(['load-contacts']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "ccper_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                {
                    field: 'ccper_name',
                    width: 150,
                    title: '<span class="hiden redStar" style="color:#f00;">*</span>姓名',
                    editor: {type: 'validatebox', options: {required: true, validType: 'length[0,15]'}, width: '100%'}
                },
                {
                    field: 'ccper_post',
                    width: 150,
                    title: '职位',
                    editor: {type: 'validatebox', options: {validType: 'length[0,15]'}}
                },
                {
                    field: 'ccper_mail',
                    width: 150,
                    title: '<span class="hiden redStar" style="color:#f00;">*</span>电子邮箱',
                    editor: {type: 'validatebox', options: {required: true, validType: ['email', 'length[0,20]']}}
                },
                {
                    field: 'ccper_mobile',
                    width: 150,
                    title: '<span class="hiden redStar" style="color:#f00;">*</span>电话(手机)',
                    editor: {type: 'validatebox', options: {required: true, validType: ['tel_mobile', length[0, 20]]}}
                },
                {
                    field: 'ccper_remark',
                    width: 155,
                    title: '备注',
                    editor: {type: 'validatebox', options: {validType: 'length[0,180]'}}
                },
                {
                    field: 'action', title: '操作', width: 200, align: 'center',
                    formatter: function (value, row, index) {
                        if (row.editing) {
                            var s = '<a href="javascript:void(0)" onclick="saverow(this)">保存</a> ';
                            var c = '<a href="javascript:void(0)" onclick="cancelrow(this)">取消</a>';
                            return s + c;
                        } else {
                            var e = '<a href="javascript:void(0)" onclick="editrow(this)">修改</a> ';
                            var d = '<a href="javascript:void(0)" onclick="deleterow(this,' + row.ccper_id + ')">删除</a>';
                            return e + d;
                        }
                    }
                }
            ]],
            onBeforeEdit: function (index, row) {           //当用户开始编辑一行时触发
                $(".redStar").show();
                row.editing = true;
                $(this).datagrid('refreshRow', index);  //刷新一行
            },
            onAfterEdit: function (index, row, changes) {    //当用户完成编辑一行时触发
                row.editing = false;
                $(this).datagrid('refreshRow', index);
                $.ajax({            //异步更改联系人数据
                    url: '<?= Url::to(["update-contacts"]) ?>?id=' + row.ccper_id,
                    type: 'POST',
                    dataType: 'json',
                    data: row,
                    async: false,
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
                    }
                })
            },
            onCancelEdit: function (index, row) {
                $(".redStar").hide();
                row.editing = false;
                $(this).datagrid('refreshRow', index);
            },
            onLoadSuccess: function (data) {
                datagridTip('#contacts');
                showEmpty($(this), data.total, 0);
            },
        });
    });

    function getRowIndex(target) {
        var tr = $(target).closest('tr.datagrid-row');
        return parseInt(tr.attr('datagrid-row-index'));
    }
    function editrow(target) {
        $('#contacts').datagrid('beginEdit', getRowIndex(target));
    }
    function deleterow(target, id) {

        layer.confirm("确定删除此联系人吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": id},
                    url: "<?= Url::to(['delete-contacts']) ?>",
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
    function saverow(target) {
        $('#contacts').datagrid('endEdit', getRowIndex(target));
    }
    function cancelrow(target) {
        $('#contacts').datagrid('cancelEdit', getRowIndex(target));
    }

    function reminder_close(id) {
        var url = "<?= Url::to(['/crm/crm-member/close-reminders']) ?>";
        reminderClose(id, url);
    }
    function reminder(id) {
        layer.confirm("确定要删除这条记录吗?",
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
                    url: "<?=\yii\helpers\Url::to(['delete-reminders']) ?>",
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
    /**
     *
     * 修改提醒事项
     * @param id
     */
    function reminder_update(id) {
        $("#reminder_update").fancybox({
            padding: [],
            fitToView: false,
            width: 730,
            height: 450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['update-reminders']) ?>?id=" + id
        });
    }

    $("#tabs").delegate(".edit-visit-record", "click", function () {
        var a = $("#data").datagrid("getSelected");
        window.location.href = "<?=Url::to(['visit-update'])?>?id=" + a.cust_id + "&childId=" + $(this).data("id") + "&ctype=3";
    })
    //修改拜访记录
    //    $("#edit-visit-record").on('click',function(){
    //        var a = $("#data").datagrid("getSelected");
    //        var b = $("#data").datagrid("getChecked");
    //        if(b.length == 0 && a == null &&　b.length != 1){
    //            layer.alert("请点击选择一条数据!",{icon:2,time:5000});
    //            return false;
    //        }
    //        var record = $("#record").datagrid('getSelected');
    //        if(record == null) {
    //            layer.alert("请点击选择一条子表信息!", {icon: 2, time: 5000});
    //            return false;
    //        }
    //        if(record['datagrid_columns_index'] == null){
    //            layer.alert("只能修改最新一条！", {icon:2,time:5000});
    //            return false;
    //        }
    //        if(a != null) {
    //            window.location.href = "<?//=Url::to(['/crm/crm-member-develop/visit-update'])?>//?id=" + a.cust_id + "&childId=" + record.sil_id + "&ctype=3";
    //
    //        }
    //        if(b.length == 1){
    //            $.each(b, function (index, val) {
    //                id = val.cust_id;
    //            });
    //            window.location.href = "<?//=Url::to(['/crm/crm-member-develop/visit-update'])?>//?id=" + id + "&childId=" + record.sil_id+ "&ctype=3";
    //        }
    //    });
    $("#tabs").delegate(".delete-visit-record", "click", function () {
        var record = $("#record").datagrid('getSelected');
        layer.confirm("确定删除这条拜访记录吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": record.sih_id, "childId": record.sil_id},
                    url: "<?= Url::to(['delete']) ?>",
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
    })

    //删除拜访记录
    //    $("#delete-visit-record").click(function(){
    //        var data = $("#data").datagrid("getSelected");
    //        if(data == null){
    //            layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
    //            return false;
    //        }
    //        var record = $("#record").datagrid("getSelected");
    //        if(record == null){
    //            layer.alert("请点击选择一条拜访记录信息!", {icon: 2, time: 5000});
    //            return false;
    //        }
    //        if(record['datagrid_columns_index'] == null){
    //            layer.alert("只能删除最新一条！", {icon:2,time:5000});
    //            return false;
    //        }
    //
    //        if(record.sil_id == null){
    //            layer.alert("请点击选择记录信息!", {icon: 2, time: 5000});
    //            return false;
    //        }
    //        layer.confirm("确定删除这条拜访记录吗?",
    //            {
    //                btn:['确定', '取消'],
    //                icon:2
    //            },
    //            function () {
    //                $.ajax({
    //                    type: "get",
    //                    dataType: "json",
    //                    data: {"id": record.sih_id,"childId": record.sil_id},
    //                    url: "<?//= Url::to(['/crm/crm-return-visit/delete']) ?>//",
    //                    success: function (msg) {
    //                        if( msg.flag === 1){
    //                            layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
    //                        }else{
    //                            layer.alert(msg.msg,{icon:2})
    //                        }
    //                    },
    //                    error :function(msg){
    //                        layer.alert(msg.msg,{icon:2})
    //                    }
    //                })
    //            },
    //            function () {
    //                layer.closeAll();
    //            }
    //        )
    //    });
    //修改店铺信息
    function edit_shop_info(shop_id) {
        $.fancybox({
            href: "<?=Url::to(['shop-edit'])?>?id=" + shop_id,
            padding: [],
            fitToView: false,
            width: 750,
            height: 450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    }
    function delete_shop_info(id) {
        layer.confirm("确定要删除这条店铺信息吗?",
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
                    url: "<?=\yii\helpers\Url::to(['delete-shop']) ?>",
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
</script>
