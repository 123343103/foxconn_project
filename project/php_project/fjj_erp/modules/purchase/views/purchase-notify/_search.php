<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .width-200{
        width: 180px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
</style>
<div class="crm-customer-info-search">
    <?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
    <div class="mb-10">
        <label class="label-align" style="width:76px;">单据类型：</label>
        <select class="width-200" id="req_dct">
            <option value="">请选择...</option>
            <?php foreach($data['req_dct'] as $key=>$val){?>
                <option value="<?=$val['bsp_id']?>"><?=$val['bsp_svalue']?></option>
            <?php }?>
        </select>
        <label style="width:80px;">采购单日期：</label>
        <input id="start_date" type="text" class="Wdate width-200" readonly="readonly">
        <label>至</label>
        <input id="end_date" type="text" class="Wdate width-200" readonly="readonly">
    </div>
    <div class="mb-10">
        <label class="label-align" style="width:76px;">采购单状态：</label>
        <select class="width-200" id="prch_status">
            <option value="">请选择...</option>
            <?php foreach($data['prch_status'] as $key=>$val){?>
                <option value="<?=$val[prch_id]?>"><?=$val[prch_name]?></option>
            <?php }?>
        </select>
        <label class="label-align" style="width:80px;">法人名称：</label>
        <select class="width-200" id="leg_id">
            <option value="">请选择...</option>
            <?php foreach($data['leg_id'] as $key=>$val){?>
                <option value="<?=$val[company_id]?>"><?=$val[company_name]?></option>
            <?php }?>
        </select>
<!--        <label class="label-align" style="width:90px;">是否三方交易：</label>-->
<!--        <select class="width-200" id="yn_three" data-options="required:true">-->
<!--            <option value="">请选择...</option>-->
<!--            <option value="1">是</option>-->
<!--            <option value="0">否</option>-->
<!--        </select>-->
<!--    </div>-->
<!--    <div class="mb-10">-->
<!--        <label class="label-align" style="width:76px;">收货中心：</label>-->
<!--        <select class="width-200" id="wh_addr">-->
<!--            <option value="">请选择...</option>-->
<!--            --><?php //foreach($data['wh_addr'] as $key=>$val){?>
<!--                <option value="--><?//=$val[wh_id]?><!--">--><?//=$val[wh_addr]?><!--</option>-->
<!--            --><?php //}?>
<!--        </select>-->
<!--        <label class="label-align" style="width:76px;">供应商：</label>-->
<!--        <select class="width-200" id="spp_fname">-->
<!--            <option value="">请选择...</option>-->
<!--            --><?php //foreach($data['spp_fname'] as $key=>$val){?>
<!--                <option value="--><?//=$val[spp_id]?><!--">--><?//=$val[spp_fname]?><!--</option>-->
<!--            --><?php //}?>
<!--        </select>-->
        <label class="label-align" style="width:90px;">采购单号：</label>
        <input type="text" class="width-200" id="prch_no">
        <button id="search" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function loadData(){
        $("#data").datagrid('load',{
            "req_dct":$("#req_dct").val(),
            "yn_three":$("#yn_three").val(),
            "wh_addr":$("#wh_addr").val(),
            "leg_id":$("#leg_id").val(),
            "prch_no":$("#prch_no").val(),
            "spp_fname":$("#spp_fname").val(),
             "prch_status":$("#prch_status").val(),
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
                maxDate:"#F{$dp.$D('end_date',{d:0})}"
            });
        });
        $("#end_date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                minDate:"#F{$dp.$D('start_date',{d:0})}"
            });
        });

        //查询
        $("#search").click(function () {
            $("#edit_btn").hide().next().hide();
            $("#censorship_btn").hide().next().hide();
            $("#cancel_btn").hide().next().hide();
            $("#notice_btn").hide().next().hide();
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
            $("#notice_btn").hide().next().hide();
            $("input,select").val("");
            loadData();
        });
    });
</script>