<?php
/**
 * User: F3859386
 * Date: 2017/2/23
 * Time: 17:30
 */
use yii\helpers\Url;
use \app\classes\Menu;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = "招商会员开发列表";
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '招商会员开发列表'];
?>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'search' => $search
    ]); ?>
    <div class="space-20"></div>
    <?php echo $this->render('_action'); ?>
    <div class="space-10"></div>
    <div style="clear: right;"></div>
    <div id="data" class="main-table">
    </div>
    <div id="load-content"></div>

</div>
<style>
    .datagrid-menu > li > a {
        border-bottom: none;
        border-left: none;
    }
</style>
<script>
    var id;
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: "ck", checkbox: true, width: 200},
                <?= $columns ?>
                {
                    field: "name", title: "操作", width: 50, formatter: function (val, row) {
                    return '<a href="<?=Url::to(['update'])?>?id=' + row.cust_id + '"><i class="icon-edit fs-18"></i></a>'
                }
                }
            ]],
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
                }
                isCheck = false;
                $('#data').datagrid('checkRow', index);

//                var b =  $("#data").datagrid("getRowIndex",selectIndex[0].cust_id);
                $(".displayOrnot").show();
                $("#load-content").show();
                $("#update").show();
                $("#update").next().show();
                $("#shopInfo").show();
                $("#shopInfo").next().show();
                $("#reminders").show();
                $("#reminders").next().show();
                $("#add-visit-record").show();
                $("#add-visit-record").next().show();
//                $("#data").datagrid("uncheckAll");
//                $("#data").datagrid("checkRow",rowIndex);
//                $('.datagrid-menu .hide').removeClass('hide');
                $('#assignStaff,#switch_potential,#switch_sale').removeClass('display-none');
                var id = rowData['cust_id'];
                $('#load-content').load("<?=Url::to(['load-info']) ?>?id=" + id + "&ctype=3", function () {
                    setMenuHeight();
                });
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
                    $(".displayOrnot").hide();

                    $('#data').datagrid("unselectAll");
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#shopInfo").hide();
                    $("#shopInfo").next().hide();
                    $("#reminders").hide();
                    $("#reminders").next().hide();
                    $("#add-visit-record").hide();
                    $("#add-visit-record").next().hide();
                    $("#m1").show();
                    $('#assignStaff,#switch_potential,#switch_sale').addClass('display-none');
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
                    $(".displayOrnot").hide();
                    $("#update").hide();
                    $("#update").next().hide();
                    $("#shopInfo").hide();
                    $("#shopInfo").next().hide();
                    $("#reminders").hide();
                    $("#reminders").next().hide();
                    $("#add-visit-record").hide();
                    $("#add-visit-record").next().hide();
                    $("#m1").show();
                    $('#assignStaff,#switch_potential,#switch_sale').addClass('display-none');
                    $('#data').datagrid("unselectAll");
                    $("#load-content").hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#load-content").hide();
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $(".displayOrnot").hide();
                $("#update").hide();
                $("#update").next().hide();
                $("#shopInfo").hide();
                $("#shopInfo").next().hide();
                $("#reminders").hide();
                $("#reminders").next().hide();
                $("#add-visit-record").hide();
                $("#add-visit-record").next().hide();
                $('#assignStaff,#switch_potential,#switch_sale').addClass('display-none');
                $("#m1").show();
            },
            onUnselectAll: function (rowIndex, rowData) {
                $(".displayOrnot").hide();
                $("#load-content").hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $(".displayOrnot").hide();
                $("#m1").show();
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                $(".displayOrnot").hide();
                $("#update").hide();
                $("#update").next().hide();
                $("#shopInfo").hide();
                $("#shopInfo").next().hide();
                $("#reminders").hide();
                $("#reminders").next().hide();
                $("#add-visit-record").hide();
                $("#add-visit-record").next().hide();
                $('#assignStaff,#switch_potential,#switch_sale').addClass('display-none');
                $("#m1").show();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            }
        });

        $("#create").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>";
        });

        //更新
        $("#update").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?=Url::to(['update'])?>?id=" + data['cust_id'];
            }
        });

        //查看
        $("#view").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?=Url::to(['view'])?>?id=" + data['cust_id'];
            }
        });

//            $("#switch_customer").click(function(){
//                 layer.confirm("确定要转会员吗?",
//                    {
//                        btn:['确定', '取消'],
//                        icon:2
//                    },function(){
//                        layer.closeAll('dialog');
//                        var a = $("#data").datagrid("getSelected");
//                        $.fancybox({
//                            width:700,
//                            height:450,
//                            autoSize:false,
//                            padding:0,
//                            type:"iframe",
//                            href:"<?//=Url::to(['/crm/crm-member-develop/turn-member'])?>//?id="+a.cust_id+"&from=/crm/crm-potential-customer/index"
//                        });
//                    }
//                )
//            });

        /*开店信息*/
        $("#shopInfo").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
                return false
            } else {
                if (data.cust_ismember == "否") {
                    layer.alert("请先填写注册网站!", {icon: 2, time: 5000});
                    return false
                }
            }
            $("#shopInfo").fancybox({
                href: "<?= Url::to(['shop-info']) ?>?id=" + data['cust_id'],
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
        });

        /*提醒事项*/
        $("#reminders").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
                return false
            }
            var url = "<?= Url::to(['reminders']) ?>?id=" + data['cust_id'];
            $("#reminders").fancybox({
                href: url,
                padding: [],
                fitToView: false,
                width: 730,
                height: 450,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            });
        });

//            //数据导入导出
//            $("#importDiv").fancybox({
//                padding: [],
//                centerOnScroll: true,
//                titlePosition: 'over',
//                title: '数据导入'
//            });
        //数据导入
        $("#importDiv").click(function () {
            $.fancybox({
                type: "iframe",
                href: "<?=Url::to(['import'])?>",
                padding: 0,
                autoSize: false,
                width: 500,
                height: 200
            });
        });

        /*分配*/
        $("#assignStaff").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
                return false
            }
            var url = "<?= Url::to(['assign-staff']) ?>?id=" + data['cust_id'] + '&class=' + data['reqitemclass'];
            $("#assignStaff").fancybox({
                padding: [],
                fitToView: false,
                width: 450,
                height: 250,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: url
            });
        });

        //抛至公海
        $("#switch_potential").click(function () {
            var obj = $("#data").datagrid('getChecked');
            if (obj.length == 0) {
                layer.alert('请选择客户！', {icon: 2, time: 5000});
                return false;
            }
            var arrId = '';
            $.each(obj, function (i, n) {
                arrId += n.cust_id + ',';
            });
            arrId = arrId.substr(0, arrId.length - 1);
            layer.confirm('确定抛至公海吗？', {icon: 2},
                function () {
                    $.ajax({
                        url: "<?=Url::to(['throw-sea']);?>",
                        data: {"arrId": arrId},
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                layer.alert("操作成功！", {icon: 1}, function () {
                                    layer.closeAll();
                                    $("#data").datagrid('reload').datagrid('clearSelections');
                                    $(".tabs-panels > .panel").hide();
                                });
                            } else {
                                layer.alert('操作失败！', {icon: 2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });

        $("#switch_sale").on("click", function () {
            var obj = $("#data").datagrid('getChecked');
            if (obj.length == 0) {
                layer.alert('请选择客户！', {icon: 2, time: 5000});
                return false;
            }
            var arrId = '';
            $.each(obj, function (i, n) {
                arrId += n.cust_id + ',';
            });
            arrId = arrId.substr(0, arrId.length - 1);
            layer.confirm("确定要转销售吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"str": arrId},
                        url: "<?=Url::to(['turn-sales']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
//                                            location.href = '<?//= Url::to(['index']) ?>//';
                                        layer.closeAll();
                                        $("#data").datagrid('reload').datagrid('clearSelections');
//                                            $(".tabs-panels > .panel").hide();
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
        });

        /*客户信息导出 start*/
        $("#export").click(function () {
            layer.confirm("确定导出客户信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    layer.closeAll();
                    window.location.href = "<?= Url::to(['export', 'CrmCustomerInfoSearch' => $search]) ?>"
                },
                function () {
                    layer.closeAll();
                }
            );
        });


        //发信息
        $("#sendMessage").click(function () {
            $.fancybox({
                width: 800,
                height: 550,
                autoSize: false,
                padding: 0,
                type: "iframe",
                href: "<?=Url::to(['send-message', 'type' => 1])?>"
            });
        });


        //发邮件
        $("#sendEmail").click(function () {
            $.fancybox({
                width: 800,
                height: 600,
                padding: 0,
                autoSize: false,
                type: "iframe",
                href: "<?=Url::to(['send-message', 'type' => 2])?>"
            });
        });

        //添加拜访记录
        $("#add-visit-record").click(function () {
            var a = $("#data").datagrid("getSelected");
            var b = $("#data").datagrid("getChecked");
            var url = "<?= Url::to(['visit-create']) ?>";
            if (b.length == 0 && a == null && b.length != 1) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                return false;
            }
            if (a != null) {
                return window.location.href = url + "?id=" + a.cust_id + "&ctype=3";
            }
            if (b.length == 1) {
                $.each(b, function (index, val) {
                    id = val.cust_id;
                });
                window.location.href = url + "?id=" + id + "&ctype=3";
            }
        });


    });
    $(".table-content").delegate(".update", "click", function () {
        window.location.href = "<?=Url::to(['update'])?>?id=" + $(this).data("id");
    })
</script>