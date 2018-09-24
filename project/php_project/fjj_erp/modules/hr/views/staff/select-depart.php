<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\assets\TreeAsset;

TreeAsset::register($this);
?>
<div class="head-first">选择部门</div>
<div style="margin: 0 20px 20px;">
    <div class="content">
        <div id="tree"></div>
    </div>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        var tree = [
            <?= $tree ?>
        ];
        $('#tree').treeview({
            data: tree,         // data is not optional
            levels: 2,
            selected: false,
            enableLinks: true,
            highlightSelected: false,
            searchResultBackColor: "#DEDEDE",
        });
        $('#treeview-disabled').treeview({
            data: tree
        });
        //最后一次触发时间
        var lastSelectTime = null;
        $('#tree').on('click', '.list-group-item', function (event) {
            var time = new Date().getTime();
            var t = time - lastSelectTime;
            if (t < 300) {
                var organizationName = $(this).find("#organizationName").html();
                var organization_code = $(this).find("#organization_code").html();
                parent.$("#hrstaff-organization_name").val(organizationName);
                parent.$("#hrstaff-organization_code").val(organization_code);
                $.parser.parse(parent.$("#add-form"));
                parent.$.fancybox.close();
            } else {
                $(event.target).find(".expand-icon").eq(0).click();
            }
            lastSelectTime = new Date().getTime();
        });
    });
</script>