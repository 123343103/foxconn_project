<?php
use app\classes\Menu;
use yii\helpers\Url;

$actionId = Yii::$app->controller->action->id;
?>
<div class="table-head mb-5">
    <p class="head">采购单列表</p>
    <div class="float-right">
        <a id="notice_btn">
            <div style="height: 23px;float: left;">
                <p class="details-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;生成收货通知单</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a id="edit_btn" class="display-none">
            <div style="height: 23px;float: left;">
                <p class="update-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a id="censorship_btn" class="display-none">
            <div style="height: 23px;float: left;">
                <p class="submit-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="cancel_btn" class="display-none">
            <div style="height: 23px;float: left;">
                <p class="setting11" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="export_btn">
            <div style="height: 23px;float: left;">
                <p class="export-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a href="<?=Url::to(['/index/index'])?>">
            <div style="height: 23px;float: left">
                <p class="return-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function () {
        //送审
        $("#censorship_btn").click(function () {
            var row = $("#data").datagrid("getSelected");
            var id = row.prch_id;
            var url = "<?=Url::to(['index'], true)?>";
            var type = 55;//审核单据类型
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480,
                afterClose: function () {
                    location.reload();
                }
            });
        });
    });
</script>


