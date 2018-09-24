<?php
/**
 * F3859386
 * 审核流程设定
 */
use app\assets\TreeAsset;
TreeAsset::register($this);
$this->title="审核流设置";
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '审核流设置'];
?>
<div class="content">
    <div  class="width-290 float-left">
        <div id="tree" class="width-290"></div>
    </div>
    <div class="width-700 float-left">
        <div class="ml-20 mb-20"> <div id="load"></div></div>
    </div>
    <div class="clear"></div> <!-- 添加空元素 清除浮动  使子元素撑开父元素高度 -->
</div>
<script>
    $(function () {
        ajaxSubmitForm($("#add-form"));
        var tree= [
            <?= $str ?>
        ];
        $('#tree').treeview({
            data: tree,         // data is not optional
            levels: 1,
            selected: true,
            searchResultBackColor: "#DEDEDE",
            onNodeSelected: function(event, data) {
                $("#load").html('');
                $("#load").load("<?=\yii\helpers\Url::to(['/system/review-rule/edit']) ?>?id=" + data.id);
                var speed=400;//滑动的速度
                $('body,html').animate({ scrollTop: 50 }, speed);
                return false;
            }
        });
});
</script>