<?php
/**
 * User: F1677929
 * Date: 2017/12/14
 */
/* @var $this yii\web\View */
\app\assets\JeDateAsset::register($this);
?>
<div class="mb-10">
    <label style="width:80px;">收货单号：</label>
    <input id="val1" type="text" style="width:180px;">
    <label style="width:90px;">单据状态：</label>
    <select id="val2" style="width:180px;">
        <option value="">全部</option>
        <option value="1">待入库</option>
        <option value="2">已入库</option>
        <option value="3">已取消</option>
    </select>
</div>
<div class="mb-10">
    <label style="width:80px;">单据类型：</label>
    <select id="val3" style="width:180px;">
        <option value="">全部</option>
        <option value="1">采购</option>
        <option value="2">调拨</option>
        <option value="3">移仓</option>
    </select>
    <label style="width:90px;">关联采购单号：</label>
    <input id="val4" type="text" style="width:180px;">
    <label style="width:80px;">收货单日期：</label>
    <input id="val5" type="text" style="width:90px;" readonly="readonly" class="Wdate" onclick="WdatePicker({skin:'whyGreen',maxDate:'#F{$dp.$D(\'val6\')}'})">
    <label>至</label>
    <input id="val6" type="text" style="width:91px;" readonly="readonly" class="Wdate" onclick="WdatePicker({skin:'whyGreen',minDate:'#F{$dp.$D(\'val5\')}'})">
    <button id="query_btn" type="button" class="search-btn-blue">查询</button>
    <button id="reset_btn" type="button" class="reset-btn-yellow">重置</button>
</div>
<script>
    $(function(){
        //查询
        function loadData(){
            $("#table1").datagrid('load',{
                "val1":$("#val1").val(),
                "val2":$("#val2").val(),
                "val3":$("#val3").val(),
                "val4":$("#val4").val(),
                "val5":$("#val5").val(),
                "val6":$("#val6").val()
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
    })
</script>