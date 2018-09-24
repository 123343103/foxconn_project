<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/21
 * Time: 上午 10:19
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\classes\Menu;

$this->params['homeLike'] = ['label' => '系统平台设置', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '用户信息列表'];
$this->title = '用户信息列表';
?>
<div class="content">
    <div class="user-search">
        <style>
            .width-70 {
                width: 70px;
            }

            .width-135 {
                width: 135px;
            }
        </style>
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get'
        ]); ?>

        <div class="mb-20">
            <label class="label-width qlabel-align width-70">账号</label><label>：</label>
            <input type="text" name="UserSearch[user_account]" class="width-135" value="<?= $search['user_account'] ?>">
            <label class="label-width qlabel-align width-60">用户姓名</label><label>：</label>
            <input type="text" name="UserSearch[staff_code]" class="width-135" value="<?= $search['staff_code'] ?>">
            <label class="label-width qlabel-align width-60">用户类型</label><label>：</label>
            <select id="user_type" class=" value-align" style="width: 135px;" name="UserSearch[user_type]">
                <option value="1">全部</option>
                <?php foreach ($data as $key=>$value){?>
                    <option value="<?= $value['bsp_id']?>" <?= isset($search['user_type']) && $search['user_type'] == $value['bsp_id'] ? "selected" : null ?>><?= $value['bsp_svalue']?></option>
                <?php }?>
            </select>
            <label class="label-width qlabel-align width-60">状态</label><label>：</label>
            <select id="user_status" class=" value-align" style="width: 135px;" name="UserSearch[user_status]">
                <option value="1">全部</option>
                <option value="10" <?= isset($search['user_status']) && $search['user_status'] == "10" ? "selected" : null ?>>是</option>
                <option value="20" <?= isset($search['user_status']) && $search['user_status'] == "20" ? "selected" : null ?>>否</option>
                <option value="0" <?= isset($search['user_status']) && $search['user_status'] == "0" ? "selected" : null ?>>离职</option>
            </select>

            <?= Html::submitButton('查询', ['class' => 'button-blue  search-btn-blue', 'style' => 'margin-left:30px']) ?>
            <?= Html::resetButton('重置', ['class' => 'button-blue reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . \yii\helpers\Url::to(["index"]) . '\'']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <div class="space-10"></div>
    </div>

    <div class="table-content">

        <div class="table-head">
            <p class="head">用户列表信息</p>
            <div class="float-right">
                <?= Menu::isAction('/system/user/reset-password') ?
                    "<a id='resetPwd'>
                    <div class='table-nav'>
                        <p class='setting10 float-left'></p>
                        <p class='nav-font'>还原密码</p>
                    </div>
                </a>"
                    : '' ?>
                <span style='float: left;'>&nbsp;|&nbsp;</span>
                <?= Menu::isAction('/system/user/create') ?
                    "<a id='create'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
                    : '' ?>
                <span style='float: left;'>&nbsp;|&nbsp;</span>
                <?= Menu::isAction('/system/user/update') ?
                    "<a id='update' >
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
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
        <div class="space-10"></div>

        <div id="data"></div>
    </div>
</div>
<script>
    $(function ($) {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "user_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "user_account", title: "用户账号", width: 120},
                {
                    field: "staff_name", title: "用户姓名", width: 120, formatter: function (val, row) {
                    return row.staffInfo.staff_name
                }
                },
                {field: "user_mobile", title: "手机", width: 120},
                {field: "user_email", title: "邮箱", width: 120},
                {field: "other_tel", title: "其他联系方式", width: 120},
                {field: "bsp_svalue", title: "用户类型", width: 120},
//                {field: "user_status", title: "是否封存", width: 120,formatter:function(val,row){
//
//                    if(val==20){
//                        return '是'
//                    }
//                    return '否'
//                }},
                {
                    field: "user_status", title: "是否有效", width: 120, formatter: function (val, row) {
                    if (row.user_status == 10) {
                        return '是';
                    }
                    else if (row.user_status == 20) {
                        return '否';
                    }
                    else {
                        return '离职';
                    }
                }
                },
//                {field: "roles", title: "权限角色", width: 200,formatter:function(val,row){
//                    if(row.is_supper==1){
//                        return ''
//                    }
//                    return row.roles;
//                }},

//                {field: "is_supper", title: "超级管理员", width: 120,formatter:function(val,row){
//
//                    if(val==1){
//                        return '是'
//                    }
//                    return '否'
//                }},

//                {field: "start_at", title: "有效期", width: 150,formatter:function(val,row){
//                    return val+'~'+row.end_at
//                }},
                {
                    field: "auth", title: "角色设置", width: 70, formatter: function (val, row) {
                    var str = "<i>";
                    str += "<a id='update-role' data-id='" + row['user_id'] + "' title='角色权限设置' class='icon-key icon-l'></a>";

                    str += "</i>";
                    return str;
                }
                },
                {
                    field: "auth1", title: "数据权限", width: 70, formatter: function (val, row) {
                    var str = "<i>";
                    str += "<a title='数据权限设置'; href='<?=Url::to(['update-department']);?>?id=" + row['user_id'] + "' class='icon-key icon-l'></a>";

                    str += "</i>";
                    return str;
                }
                },
                {
                    field: "auth2", title: "商品权限", width: 70, formatter: function (val, row) {
                    var str = "<i>";
                    str += "<a title='商品权限设置'; href='<?=Url::to(['update-commodity']);?>?id=" + row['user_id'] + " ' class='icon-key icon-l'></a>";

                    str += "</i>";
                    return str;
                }
                },
                {
                    field: "auth3", title: "仓库设置", width: 70, formatter: function (val, row) {
                    var str = "<i>";
                    str += "<a title='仓库设置' href='<?=Url::to(['update-wh']);?>?id=" + row['user_id'] + "' class='icon-key icon-l'></a>";

                    str += "</i>";
                    return str;
                }
                },
                {
                    field: "auth4", title: "厂区设置", width: 70, formatter: function (val, row) {
                    var str = "<i>";
                    str += "<a title='厂区设置' href='<?=Url::to(['update-factory']);?>?id=" + row['user_id'] + " ' class='icon-key icon-l'></a>";

                    str += "</i>";
                    return str;
                }
                }
            ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color', 'white');
                datagridTip("#data");
            }
        });
        //修改
        $("#update").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                return tips();
            } else {
//                var id = $("#data").datagrid("getSelected")['user_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + data['user_id'];
            }
        });
        //新增销售点
        $("#create").on("click", function () {
            window.location.href = "<?= Url::to(['/system/user-management/add']) ?>";
        });
        //还原密码
        $("#resetPwd").on("click", function () {
            var data = $("#data").datagrid("getSelected");
//            alert(data['user_id']);
            if (data == null) {
                return tips();
            } else {
                layer.confirm("确定还原此用户密码?",
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": data['user_id']},
                            'url': "<?= Url::to(['reset-password']); ?>",
                            'success': function (msg) {
                                if (msg.flag == 1) {
                                    layer.alert(msg.msg, {
                                        icon: 1, end: function () {
                                        }
                                    })
                                } else {
                                    layer.alert(msg.msg, {icon: 0})
                                }
                            }
                        });
                        return true;
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            }
        });

        //角色设置
        $(".content").delegate("#update-role", "click", function () {
            $('#update-role').fancybox({
                autoSize: true,
                fitToView: false,
                height: 560,
                width: 530,
                closeClick: true,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?=Url::to(['update-role'])?>?id=" + $(this).data("id")
            });
            //window.location.href = "<?=Url::to(['update'])?>?id=" + $(this).data("id");
        })

    });
    function tips() {
        return layer.alert("请点击选择一条用户记录", {icon: 2, time: 5000});
    }
</script>
