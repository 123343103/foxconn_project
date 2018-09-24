<?php
/**
 *  F3858995
 * 2016/10/14
 */
use yii\helpers\Url;
use app\assets\TreeAsset;
TreeAsset::register($this);
$this->params['homeLike'] = ['label'=>'系统平台设置'];
$this->params['breadcrumbs'][] = ['label'=>'单据编码规则定义'];
$this->title = '单据编码规则';
?>
<style>
    .display-flex {
        display: flex;
    }
    .width-300 {
        width: 300px;
    }
    .width-665 {
        width: 665px;
    }
    .ml-20 {
        margin-left: 20px;
    }
</style>
<div class="content">
    <div class="display-flex">
        <div class="width-300 float-left">
            <div id="tree"></div>
        </div>
        <div  class="width-665 ml-20 float-left">
            <div id="_form"></div>
        </div>
    </div>
</div>
<script>
    $(function(){
        var tree = [
            <?= $tree ?>
        ];
        $('#tree').treeview({
            data: tree,         // data is not optional
            levels: 2,
            selected: true,
//        enableLinks: true,
//        highlightSelected: true,
            searchResultBackColor: "#DEDEDE",
            onNodeSelected: function(event, info) {
                $("#_form").html('');
                $("#_form").load("<?=Url::to(['/system/form-code/edit']) ?>?code=" + info.form_id+'&type=' + info.type);
            }
        });
    });
</script>