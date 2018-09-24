<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<div class="mb-10">
    <label style="width:90px;">集团供应商：</label>
    <select id="group_spp" style="width:180px;">
        <option value="">--请选择--</option>
        <option value="Y">是</option>
        <option value="N">否</option>
    </select>
    <label style="width:80px;">供应商分类：</label>
    <select id="spp_type" style="width:180px;">
        <option value="">--请选择--</option>
        <?php foreach($data['supType'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
    <label style="width:80px;">状态：</label>
    <select id="spp_status" style="width:180px;">
        <option value="">--请选择--</option>
        <option value="1">未提交</option>
        <option value="2">审核中</option>
        <option value="3">审核完成</option>
        <option value="4">驳回</option>
    </select>
    <?php if(\app\classes\Menu::isAction('/spp/supplier/get-supplier')){?>
    <button id="get_sup_btn" type="button" class="search-btn-blue" style="width:80px;margin-left:10px;">抓取数据</button>
    <?php }?>
</div>
<div class="mb-10">
    <label style="width:90px;">供应商名称：</label>
    <input id="spp_fname" type="text" style="width:180px;">
    <label style="width:80px;">新增人：</label>
    <input id="oper_id" type="text" style="width:180px;">
    <label style="width:80px;">新增时间：</label>
    <input id="start_time" type="text" style="width:80px;" class="Wdate" readonly="readonly">
    <label>至</label>
    <input id="end_time" type="text" style="width:81px;" class="Wdate" readonly="readonly">
    <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
</div>
<script>
    $(function(){
        //新增时间
        $("#start_time").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                maxDate:"#F{$dp.$D('end_time',{d:-1})}"
            });
        });
        $("#end_time").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                minDate:"#F{$dp.$D('start_time',{d:1})}"
            });
        });

        //查询
        function loadData(){
            $("#supplier_table").datagrid('load',{
                "group_spp":$("#group_spp").val(),
                "spp_type":$("#spp_type").val(),
                "spp_status":$("#spp_status").val(),
                "spp_fname":$("#spp_fname").val(),
                "oper_id":$("#oper_id").val(),
                "start_time":$("#start_time").val(),
                "end_time":$("#end_time").val()
            });
        }
        $("#query_btn").click(function(){
            loadData();
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                loadData();
            }
        });
        $("#reset_btn").click(function(){
            $("input,select").val('');
            loadData();
        });

        //抓取数据
        $("#get_sup_btn").click(function(){
            $.fancybox({
                href:"<?=Url::to(['get-supplier'])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:400,
                height:150
            });
        });
    })
</script>