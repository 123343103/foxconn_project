<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\classes\Menu;

?>
<style>
    .wd-tc-10 {
        width: 10px;
        text-align: center;
    }
</style>
<div class="table-head">
    <p class="head">招商会员列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-investment-customer/update') ?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update' class='display-none'>
                <div style='height: 23px;width: 55px;float: left;'>
                    <p class='update-item-bgc ' style='float: left'></p>
                    <p style='font-size: 14px;margin-top: 2px;'>&nbsp;修改</p>
                </div>
            </a>
            <span class='float-left display-none wd-tc-10'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-investment-customer/shop-info') ?
            "<a id='shopInfo' class='display-none'>
                    <div class='table-nav'>
                        <p class='setting6 float-left'></p>
                        <p class='nav-font'>新增店铺</p>
                    </div>
                </a><span class='float-left display-none wd-tc-10'>|</span>"
            : ''; ?>
        <?= Menu::isAction('/crm/crm-investment-customer/reminders') ?
//            Html::a("<span class='width-70 text-center ml--5'>提醒事项</span>", null, ['id' => 'reminders'])
            "<a id='reminders' class='display-none'>
                    <div style='height: 23px;width: 80px;float: left;'>
                        <p class='setbcg2 float-left'></p>
                        <p style='font-size: 14px;margin-top: 2px;'>提醒事项</p>
                    </div>
                </a><span class='float-left display-none wd-tc-10'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-investment-customer/visit-create') ?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<a id='add-visit-record' class='display-none'>
                    <div class='table-nav'>
                        <p class='setting1 float-left'></p>
                        <p class='nav-font'>新增拜访记录</p>
                    </div>
                </a><span class='float-left display-none wd-tc-10'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-investment-customer/send-message') ?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<div id='m1' class='float-left'>
                <a href='javascript:void(0)' class='text-center ml--5 width-90'>
                    <div class='table-nav'>
                        <p class='setbcg6 float-left'></p>
                        <p class='nav-font nav-font menu-one' id=\"m-send\">即时通讯</p>
                    </div>
                </a><span class='float-left wd-tc-10'>|</span>
              </div>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-investment-customer/export') ?
//            Html::a("<span class='text-center ml--5'>回访</span>", null, ['id' => 'backVisit'])
            "<div class='float-left'>
                <a href='javascript:void(0)' class='text-center ml--5 width-90'>
                    <div class='table-nav width-80'>
                        <p class='setbcg5 float-left'></p>
                        <p class='nav-font  menu-one' id='m-deal'>数据处理</p>
                    </div>
                </a><span class='float-left wd-tc-10'>|</span>
              </div>"
            : '' ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
    <div id="m-data2" class="width-70 hiden">
        <div><a id="sendMessage" class="menu-span"><span>发信息</span></a></div>
        <div><a id="sendEmail" class="menu-span"><span>发邮件</span></a></div>
    </div>
    <div id="m-data" class="width-70 hiden">
        <div><a id="export" class="menu-span"><span>批量导出</span></a></div>
    </div>

    <div class="display-none">
        <div id="staff" style="width:400px; height:270px; overflow:auto">
            <div class="pop-head">
                <p>分配员工</p>
            </div>
            <div class="mt-20">
                <?php $form = ActiveForm::begin([
                    'action' => ['insert-excel'],
                ]); ?>
                <div class="ml-40">
                    <label>主营类目</label>
                    <select class="width-200" name="CrmCustomerInfo[member_reqitemclass]">
                        <option value="">请选择...</option>
                        <?php foreach ($downList['productType'] as $key => $val) { ?>
                            <option
                                value="<?= $val['category_id'] ?>"<?= $model['member_reqitemclass'] == $val['category_id'] ? "selected" : null; ?>><?= $val['category_sname'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="ml-40 mt-10">
                    <label>分配员工</label>
                    <input class="width-200 easyui-validatebox" data-options="required:'true'" type="text"
                           name="CrmCustomerInfo[cust_sname]" value="">
                </div>
                <div class="ml-40 mt-10">
                    <label>分配日期</label>
                    <input class="width-200 easyui-validatebox select-date" data-options="required:'true'" type="text"
                           name="CrmCustomerInfo[cust_sname]" value="">
                </div>
                <div class="text-center mt-10">
                    <button type="submit" class="button-blue-big">确定</button>
                    <button class="button-white-big" onclick="history.go(-1);" type="button">取消</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('#m-deal').menubutton({
                menu: '#m-data',
                hasDownArrow: false
            });
            $('#m-send').menubutton({
                menu: '#m-data2',
                hasDownArrow: false
            });
            $('.menu-one').removeClass("l-btn l-btn-small l-btn-plain");
            $('.menu-one').find("span").removeClass("l-btn-left l-btn-text");

            //创建
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

            /*提醒事项*/
            $("#reminders").on("click", function () {
                var data = $("#data").datagrid("getSelected");
                if (data == null) {
                    layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
                    return false
                }
                var url = '';
                if (data == null) {
                    url = "<?= Url::to(['reminders']) ?>?ctype=6"
                } else {
                    url = "<?= Url::to(['reminders']) ?>?id=" + data['cust_id'] + "&ctype=6"
                }
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

            //数据导入导出
            $("#importDiv").fancybox({
                padding: [],
                centerOnScroll: true,
                titlePosition: 'over',
                title: '数据导入'
            });


            /*信息导出 start*/
            $("#export").click(function () {
                layer.confirm("确定导出会员信息?",
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
                    return window.location.href = url + "?id=" + a.cust_id + "&ctype=6";
                }
                if (b.length == 1) {
                    $.each(b, function (index, val) {
                        id = val.cust_id;
                    });
                    window.location.href = url + "?id=" + id + "&ctype=6";
                }
            });

            //修改拜访记录
            $("#edit-visit-record").on('click', function () {
                var a = $("#data").datagrid("getSelected");
                var b = $("#data").datagrid("getChecked");
                if (b.length == 0 && a == null && b.length != 1) {
                    layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
                    return false;
                }
                var record = $("#record").datagrid('getSelected');
                if (record == null) {
                    layer.alert("请点击选择一条子表信息!", {icon: 2, time: 5000});
                    return false;
                }
                if (record['datagrid_columns_index'] == null) {
                    layer.alert("只能修改最新一条！", {icon: 2, time: 5000});
                    return false;
                }
                if (a != null) {
                    window.location.href = "<?=Url::to(['visit-update'])?>?id=" + a.cust_id + "&childId=" + record.sil_id + "&ctype=6";

                }
                if (b.length == 1) {
                    $.each(b, function (index, val) {
                        id = val.cust_id;
                    });
                    window.location.href = "<?=Url::to(['visit-update'])?>?id=" + id + "&childId=" + record.sil_id + "&ctype=6";
                }
            });

            //删除拜访记录
            $("#delete-visit-record").click(function () {
                var data = $("#data").datagrid("getSelected");
                if (data == null) {
                    layer.alert("请点击选择一条客户信息!", {icon: 2, time: 5000});
                    return false;
                }
                var record = $("#record").datagrid("getSelected");
                if (record == null) {
                    layer.alert("请点击选择一条拜访记录信息!", {icon: 2, time: 5000});
                    return false;
                }
                if (record.sil_id == null) {
                    layer.alert("请点击选择记录信息!", {icon: 2, time: 5000});
                    return false;
                }

                if (record['datagrid_columns_index'] == null) {
                    layer.alert("只能删除最新一条！", {icon: 2, time: 5000});
                    return false;
                }
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
            });
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
            //修改开店信息
            $("#shop_edit").click(function () {
                $.fancybox({
                    type: "iframe",
                    url: "<?=Url::to(['shop-edit'])?>"
                });
            });
        });
    </script>
