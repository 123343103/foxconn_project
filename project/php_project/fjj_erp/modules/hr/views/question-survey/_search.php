<?php
/**
 * User: F3859386
 * Date: 2017/10/20
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    .ml_10{
        margin-left: 10px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div class="mb-10">
    <label class="qlabel-align">问卷主题:</label>
    <input style="width: 180px" class="qvalue-align" type="text" id="invst_subj">
    <label style="width:100px;">问卷类别：</label>
    <select style="width:150px;" id="invst_type">
        <option value="">请选择...</option>
        <?php foreach($data['invst_type'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
    <label class="qlabel-align ml_10">主办单位:</label>
    <input class="qvalue-align" type="text" id="organization_name">
    <button id="search" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    function loadData(){
        $("#data").datagrid('load',{
            "invst_subj":$("#invst_subj").val(),
            "invst_type":$("#invst_type").val(),
            "invst_state":$("#invst_state").val(),
            "yn_close":$("#yn_close").val(),
            "organization_name":$("#organization_name").val()
        }).datagrid('clearSelections').datagrid('clearChecked');
    }

    $(function() {
        //查询
        $("#search").click(function () {
            loadData();
        });
        $(document).keydown(function (event) {
            if (event.keyCode == 13) {
                loadData();
            }
        });

        //重置
        $("#reset").click(function () {
            $("#update").hide().next().hide();
            $("#cancel").hide().next().hide();
            $("#stop").hide().next().hide();
            $("#add-visit-record").hide().next().hide();
            $("input,select").val("");
            loadData();
        });
    });
</script>
