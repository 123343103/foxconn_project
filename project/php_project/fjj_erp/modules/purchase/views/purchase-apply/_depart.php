<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/11/18
 * Time: 上午 08:46
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);

?>
<style>
    .datagrid-view{margin-left: 8px;}
    .datagrid-cell-c1-organization_code > div{ margin-left: 200px !important;}
</style>
<div class="head-first">部门选择</div>
<div>
    <div class="mb-10 overflow-auto" style="padding: 0 20px">
        <form action="<?= Url::to(['select-depart']) ?>" method="get" id="depart_form" class="float-left">
            <input type="text" name="keyWord" placeholder="部门代码/名称"
                   value="<?= $queryParam['keyWord'] ?>"
                   id="seacher">
            <?= \yii\helpers\Html::button('查询', ['class' => 'search-btn-blue', 'style' => 'margin-left:10px;', 'id' => 'img']) ?>
            <?= \yii\helpers\Html::button('重置', ['class' => 'reset-btn-yellow', 'style' => 'margin-left-10', 'onclick' => 'window.location.href="' . Url::to(['select-depart']) . '"']) ?>
        </form>
    </div>
    <div id="data" style="width:100%; margin-left: 8px;"></div>
</div>

<div class="space-10"></div>

<div class="text-center">
    <button class="button-blue-big" id="confirm">确&nbsp;定</button>
    <button class="button-white-big" onclick="close_select()">返回</button>
</div>
<script>
    $(function () {
        //搜索
        $("#img").on("click", function () {
            $("#depart_form").submit();
        });

        //显示数据
        $("#data").datagrid({
            rownumbers: true,
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
//            rownumbers: true,
            fitColumns:true,
            method: "get",
            idField: "organization_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect:true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "organization_id", title: "部门id",width: 120,hidden:true},
                {field: "organization_code", title: "部门代码",width: 120},
                {field: "organization_name", title: "部门名称",width: 120},
            ]],
            onDblClickRow: function () {
                reload();
            },
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 1);
            }
        });

        //确定
        $("#confirm").on("click", function () {
            reload();
        });

        function reload() {
//            var radio = $("#data").datagrid("getSelected");
            var obj = $("#data").datagrid('getSelected');
            if (obj==null) {
                parent.layer.alert("请选择部门信息", {icon: 2, time: 5000});
            }
            else {
                parent.$("#_organization_id").val(obj.organization_id);
                parent.$("#_organization_name").val(obj.organization_name);
                parent.$("#_organization_name").attr("data-options","false");
                parent.$("#_organization_name").removeClass("validatebox-invalid");
                parent.$.fancybox.close();
            }
        }

//        $("#seacher").focus(function () {
//            $("#seacher").attr("placeholder", "");
//        })
//        $("#seacher").blur(function () {
//            $("#seacher").attr("placeholder", "模糊查询");
//        })

    })
</script>