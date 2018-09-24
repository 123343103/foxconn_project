<?php
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .width-200{
        width: 130px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
</style>
<div class="crm-customer-info-search">
    <?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
    <div class="mb-10">
        <label class="label-align" style="width:70px;">盘点单号：</label>
        <input type="text" class="width-200" id="ivt_code">
        <label class="label-align" style="width:70px;">单据状态：</label>
        <select class="width-200" id="ivt_status">
            <option value="">全部</option>
            <option value="0">审核中</option>
            <option value="1">待提交</option>
            <option value="2">信息完善中</option>
            <option value="3">审核完成</option>
            <option value="4">驳回</option>
            <option value="5">单据已取消</option>
        </select>
        <label class="label-align" style="width:80px;">期别：</label>
        <select class="width-200" id="stage">
            <option value="">全部</option>
            <option value="1">第一期</option>
            <option value="2">第二期</option>
            <option value="3">第三期</option>
            <option value="4">第四期</option>
        </select>
        <label class="label-align" style="width:70px;">法人：</label>
        <select class="width-200" id="legal_code">
            <option value="">请选择...</option>
            <?php foreach($data['legal_code'] as $key=>$val){?>
                <option value="<?=$val['company_id']?>"><?=$val['company_name']?></option>
            <?php }?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-align" style="width:70px;">仓库名称：</label>
        <select class="width-200" id="wh_name">
            <option value="">请选择...</option>
            <?php foreach($data['wh_name'] as $key=>$val){?>
                <option value="<?=$val['wh_code']?>"><?=$val['wh_name']?></option>
            <?php }?>
        </select>
        <label class="label-align" style="width:70px;">仓库代码：</label>
        <input type="text" class="width-200" id="wh_code">
        <label style="width:80px;">盘点单日期：</label>
        <input id="start_date" type="text" class="Wdate width-200" readonly="readonly">
        <label>至</label>
        <input id="end_date" type="text" class="Wdate width-200" readonly="readonly">
        <button id="search" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function loadData(){
        $("#data").datagrid('load',{
            "ivt_code":$("#ivt_code").val(),
            "ivt_status":$("#ivt_status").val(),
            "stage":$("#stage").val(),
            "legal_code":$("#legal_code").val(),
            "wh_name":$("#wh_name").val(),
            "wh_code":$("#wh_code").val(),
            "start_date":$("#start_date").val(),
            "end_date":$("#end_date").val()
        }).datagrid('clearSelections').datagrid('clearChecked');
    }

    $(function() {
        //申请时间
        $("#start_date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                maxDate:"#F{$dp.$D('end_date',{d:-1})}"
            });
        });
        $("#end_date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                minDate:"#F{$dp.$D('start_date',{d:1})}"
            });
        });

        //查询
        $("#search").click(function () {
            $("#edit_btn").hide().next().hide();
            $("#censorship_btn").hide().next().hide();
            $("#cancel_btn").hide().next().hide();
            loadData();
        });
        $(document).keydown(function (event) {
            if (event.keyCode == 13) {
                loadData();
            }
        });

        //重置
        $("#reset").click(function () {
            $("#edit_btn").hide().next().hide();
            $("#censorship_btn").hide().next().hide();
            $("#cancel_btn").hide().next().hide();
            $("input,select").val("");
            loadData();
        });
    });
</script>