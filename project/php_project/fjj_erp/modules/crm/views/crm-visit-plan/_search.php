<?php
/* @var $this yii\web\View */
\app\assets\JeDateAsset::register($this);
?>
<style>
    .width-100{
        width:100px;
    }
    .width-200{
        width:200px;
    }
</style>
<div class="mb-10">
    <label class="width-100">客户名称：</label>
    <input id="cust_sname" type="text" class="width-200">
    <label class="width-100">状态：</label>
    <select id="svp_status" class="width-200">
        <option value="">全部</option>
        <option value="10">待实施</option>
        <option value="40">实施中</option>
        <option value="60">已结束</option>
        <option value="20">已实施</option>
        <option value="50">已终止</option>
        <option value="30">已取消</option>
    </select>
</div>
<div class="mb-10">
    <label class="width-100">拜访类型：</label>
    <select id="svp_type" class="width-200">
        <option value="">全部</option>
        <?php foreach ($downList['visitType'] as $key => $val) { ?>
            <option value="<?= $key ?>"><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-100">计划日期：</label>
    <input id="start" type="text" class="Wdate" style="width:90px;" readonly="readonly">
    <label>至</label>
    <input id="end" type="text" class="Wdate" style="width:91px;" readonly="readonly">
    <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
</div>
<script>
    //加载数据
    function loadData(){
        $("#data").datagrid('load',{
            "cust_sname":$("#cust_sname").val(),
            "svp_status":$("#svp_status").val(),
            "svp_type":$("#svp_type").val(),
            "start":$("#start").val(),
            "end":$("#end").val()
        }).datagrid('clearSelections').datagrid('clearChecked');
    }

    $(function(){
        //查询
        $("#query_btn").click(function(){
            loadData();
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                loadData();
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#update").hide().next().hide();
            $("#cancel").hide().next().hide();
            $("#stop").hide().next().hide();
            $("#add-visit-record").hide().next().hide();
            $("input,select").val("");
            loadData();
        });

        //计划日期
        $("#start").click(function(){
            WdatePicker({
                skin:"whyGreen",
                maxDate:"#F{$dp.$D('end',{d:-1})}"
            });
        });
        $("#end").click(function(){
            WdatePicker({
                skin:"whyGreen",
                minDate:"#F{$dp.$D('start',{d:1})}"
            });
        });
    })
</script>