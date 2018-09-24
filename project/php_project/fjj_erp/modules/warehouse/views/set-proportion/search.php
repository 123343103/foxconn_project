<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2018/1/15
 * Time: 19:22
 */
use yii\widgets\ActiveForm;

?>
<style>
    .mb-10 {
        margin-bottom: 10px;
    }

    .width-200 {
        width: 200px;
    }
</style>
<div class="crm-customer-info-search">
    <?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]) ?>
    <div class="mb-10">
        <label class="label-align" style="width: 30px">编号:</label>
        <input type="text" class="value-align width-200" id="no">
<label class="label-align" style="width: 80px">单据类型:</label>
        <select id="type" class="value-align width-200">
            <option value="">全部</option>
            <?php foreach ($downlist['type'] as $key=>$val){?>
            <option value="<?=$val['bsp_id']?>"><?=$val['bsp_svalue']?></option>
        <?php }?>
        </select>
        <label class="label-align" style="width: 60px">状态:</label>
        <select id="status" class="value-align width-200">
            <option value="">全部</option>
            <option value="1" selected>启用</option>
            <option value="0">禁用</option>
        </select>
        <button id="search" type="button" class="search-btn-blue" style="margin-left:30px;">查询</button>
        <button id="reset" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    function loadData() {
        $("#data").datagrid('load', {
            "no": $("#no").val(),
            "type": $("#type").val(),
            "status": $("#status").val()
        }).datagrid('clearSelections').datagrid('clearChecked');
    }
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
        $("input,select").val("");
        $("#status").val("1");
        loadData();
    });
</script>