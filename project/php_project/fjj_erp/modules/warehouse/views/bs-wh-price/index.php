<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 03:37
 */

use app\classes\Menu;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);

$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '费用名称列表'];
$this->title = '仓库费用名称设置';
?>
<div class="content">
    <?= $this->render("_search", [
        'get' => \Yii::$app->request->get()
    ]); ?>

    <div class="table-head">
        <p class="head">费用名称列表</p>
        <div class="float-right">
            <?= Menu::isAction('/warehouse/bs-wh-print/create') ? "<a id='create'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left">&nbsp;|&nbsp;</p>
            <?= Menu::isAction('/warehouse/bs-wh-print/update') ? "<a id='update' style='display:none;'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>修改</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left" id="update1" style='display:none;'>&nbsp;|&nbsp;</p>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>

        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
</div>
<script>
    $(function () {
        //严格模式
        "use strict";
        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "svp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            pageSize: 10,
            pageList: [10, 20, 30],
            columns: [[
                <?= $fields ?>
                {
                    field: "handle", title: "操作", width: "60", formatter: function (value, row, index) {
                    return '<a onclick="update(' + row.whpb_id + ')" class="icon-edit icon-large" title="修改"></a>';
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#data").datagrid('clearSelections').datagrid('clearChecked');
                $("#update").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#data").datagrid("getRowIndex", rowData.svp_id);
                $("#update").show();
                $("#update1").show();
            },
        });

        //更新
        $("#update").on("click", function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var whpb_id = row['whpb_id'];
                update(whpb_id);
            }
        });
        //添加
        $("#create").on("click", function () {
            $.fancybox({
                type: "iframe",
                width: 600,
                height: 400,
                autoSize: false,
                margin:13,
                padding:0,
                href: "<?=Url::to(['create'])?>",
            });
        });

    });
    function update(whpb_id) {
        $.fancybox({
            type: "iframe",
            width: 600,
            height: 400,
            autoSize: false,
            margin:0,
            padding:0,
            href: "<?=Url::to(['update'])?>?whpb_id=" + whpb_id,
        });
    }
</script>
