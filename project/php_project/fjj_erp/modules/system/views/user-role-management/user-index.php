<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/20
 * Time: 上午 08:34
 */
use app\assets\MultiSelectAsset;
use yii\helpers\Url;
use app\classes\Menu;

MultiSelectAsset::register($this);

$this->params['homeLike'] = ['label' => '系统平台设置', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '用户角色设置'];
$this->title = "用户角色设置";
?>
<style>
    .ml-20 {
        margin-left: 10px;
    }

    .width-30 {
        width: 30px;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', ['search' => $search]); ?>
    <div class="table-head">
        <p class="head">用户信息列表</p>
        <div class="float-right">
            <?= Menu::isAction('/system/user-role-management/add-edit') ?
                "<a id='add'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>
            <span style='float: left;'>&nbsp;|&nbsp;</span> " : '' ?>
            <?= Menu::isAction('/system/user-role-management/add-edit') ?
            "<a id='edit'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>修改</p>
                </div>
            </a>
            <span style='float: left;'>&nbsp;|&nbsp;</span> " : '' ?>
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
<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "name",
            loadMsg: '加载中',
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: "role_no", title: "角色编码", width: 150},
                {field: "role_name", title: "角色名称", width: 210},
                {field: "role_des", title: "角色描述", width: 210},
                {
                    field: "role_state", title: "状态", width: 135, formatter: function (val) {
                    if (val == true) {
                        return '启用'
                    } else {
                        return '禁用'
                    }
                }
                },
                {
                    field: "created_at", title: "操作权限", width: 100, formatter: function (val, row) {
                    return "<a class='ml-20' href='<?=Url::to(["menu-auth"])?>?role_pkid=" + row.role_pkid + "'    title='操作权限设置'><i class='icon-key icon-l'></i></a>";
                }
                },
                {
                    field: "name", title: "操作", width: 100, formatter: function (val, row) {
                    if (row.role_state == 1) {
                        return "<a class='ml-20'  title='禁用' onclick='openclose(0," + row.role_pkid + ")'><i class='icon-eye-close icon-l'></i></a>";
                    } else {
                        return "<a class='ml-20'  title='启用' onclick='openclose(1," + row.role_pkid + ")'><i class='icon-eye-open icon-l'></i></a>";
                    }
                }
                },
            ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color', 'white');
                showEmpty($(this), data.total, 0);
            },
        });
        $("#add").click(function () {

            $.fancybox({
                href: "<?= Url::to(['/system/user-role-management/add-edit']) ?>?type=add",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 500,
                height: 400
            });
        });
        $("#edit").click(function () {
            //获取选中行
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                return layer.alert("请点击选择一条用户记录", {icon: 2, time: 5000});
            } else {
                var role_pkid = data['role_pkid'];
                $.fancybox({
                    href: "<?= Url::to(['/system/user-role-management/add-edit']) ?>?type=" + role_pkid,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 500,
                    height: 400
                });
            }
        });
    })
    function openclose(state, role_pkid) {
        var openclose = "";
        if (state == '0') {
            openclose = "确定将此数据禁用吗？";
        } else {
            openclose = "确定将此数据启用吗？";
        }
        layer.confirm(openclose, {
            btn: ['确定', '取消'],
            icon: 2
        }, function () {
            $.ajax({
                url: "<?=Url::to(['update-state'])?>",
                data: {
                    "role_pkid": role_pkid,
                },
                dataType: "json",
                success: function (data) {
//                console.log(data);
                    if (data.flag == 1) {
                        layer.alert(data.msg, {icon: 1});
                        $("#data").datagrid("reload", {
                            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                            onLoadSuccess: function () {
                            }
                        });
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            });
        }, function () {
            layer.closeAll();
        });
    }
</script>
