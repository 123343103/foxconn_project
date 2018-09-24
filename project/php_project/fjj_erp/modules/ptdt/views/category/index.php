<?php
/**
 * User: F1676624
 * Date: 2016/10/24
 */
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '料号管理'];
$this->params['breadcrumbs'][] = ['label' => '料号大分类维护'];
?>
<?php $form = ActiveForm::begin(); ?>
<div class="content">

    <div class="space-30"></div>
    <div class="table-content">
        <div class="table-head">
            <p id="head"><?= $new_title ?></p>
            <p class="float-right">
                <a id="add"><span>新增</span></a>
                <a id="edit"><span>修改</span></a>
                <a id="view"><span>查看</span></a>
                <a id="delete"><span>刪除</a>
                <a id="change"><span class="width-90">废弃或激活</a>
            </p>
        </div>
        <div class="space-10"></div>
        <div id="data"></div>
        <div class="text-center pt-20 pb-20">
            <button id="checkNext" class="button-blue-big" type="button">查看下一级</button>
            <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
        </div>
    </div>
</div>
<?php $form = ActiveForm::end(); ?>
<script>
    $(function () {
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "category_id",
//          fitColumns:true,
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {field: 'category_id', title: 'ID', width: 130},
                {field: 'category_name', title: '类名', width: 130},
                {field: 'isvalid', title: '是否有效', width: 90},
                {field: 'center', title: '中心', width: 130},
                {field: 'min_margin', title: '最小利率', width: 130}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });
        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });
        $("#add").on("click", function () {
            var rows = $("#data");
            var id = rows[0].category_id;
            var mtitle = $("#head").html();
            var title = mtitle.replace(/&gt;/g, ">");
            window.location.href = "<?=Url::to(['add'])?>?id=" + id +"&title=" + title;
        });

        $("#view").on("click", function () {
            var rows = $("#data").datagrid("getSelections");
            var num = rows.length;
            if (num != 1) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = rows[0].category_id;
                var mtitle = $("#head").html();
                var title = mtitle.replace(/&gt;/g, ">");
                window.location.href = "<?=Url::to(['view'])?>?id=" + id + "&title=" + title;
            }
        });
        $("#edit").on("click", function () {
            var rows = $("#data").datagrid("getSelections");
            var num = rows.length;
            if (num != 1) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
//                 $.messager.alert('提示消息', '請點擊選擇一條需求單信息', '請點擊選擇一條需求單信息');
            } else {
                var id = rows[0].category_id;
                var mtitle = $("#head").html();
                var title = mtitle.replace(/&gt;/g, ">");
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id + "&title=" + title;
            }
        });
        $("#checkNext").on("click", function () {
            var rows = $("#data").datagrid("getSelections");
            var num = rows.length;
            var level = <?= $level?>;
            if (num != 1) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                if (level == 6) {
                    layer.alert("第六级，没有下级", {icon: 2, time: 5000});
                } else {
                    var id = rows[0].category_id;
                    var mtitle = $("#head").html();
                    var title = mtitle.replace(/&gt;/g, ">");
                    window.location.href = "<?=Url::to(['index'])?>?id=" + id + "&title=" + title;
                }
            }
        });


        $("#delete").on("click", function () {
            var rows = $("#data").datagrid("getSelections");
            var num = rows.length;
            var selectId = rows[0].category_id;
            if (num == 0) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                layer.confirm("确定要删除这条记录吗",
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": selectId},
                            url: "<?=Url::to(['/ptdt/category/delete']) ?>",
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
    });
</script>


