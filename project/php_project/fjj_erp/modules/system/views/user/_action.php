<?php
/**
 * User: F3859386
 * Date: 2016/9/13
 * Time: 10:09
 */
use yii\helpers\Html;
use yii\helpers\Url;
use app\classes\Menu;

?>
<div class="table-head">
    <p class="head">用户列表信息</p>
    <div class="float-right">
        <?= Menu::isAction('/system/user/reset-password') ?
            "<a id='resetPwd'>
                    <div class='table-nav'>
                        <p class='setting10 float-left'></p>
                        <p class='nav-font'>重置密码</p>
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
        <span  style='float: left;'>&nbsp;|&nbsp;</span>
        <?= Menu::isAction('/system/user/delete') ?
            "<a  id='deletion' >
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>删除</p>
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
<script>
    $(function ($) {
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
            window.location.href = "<?= Url::to(['/system/user/create']) ?>";
        });
        $("#resetPwd").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                return tips();
            } else {
                layer.confirm("确定要重置此用户密码?",
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

        /*删除方法*/
        $("#deletion").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                return tips();
            } else {
                layer.confirm("确定要删除此用户?",
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
                            'url': "<?= Url::to(['delete']); ?>",
                            'success': function (msg) {
                                if (msg.flag == 1) {
                                    layer.alert(msg.msg, {
                                        icon: 1, end: function () {
                                            parent.$("#data").datagrid('reload');
                                        }
                                    });
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
        })
    })
    function tips() {
        return layer.alert("请点击选择一条用户记录", {icon: 2, time: 5000});
    }
</script>