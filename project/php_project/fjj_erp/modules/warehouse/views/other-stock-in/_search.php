<?php
/**
 * User: F1677929
 * Date: 2017/12/14
 */
/* @var $this yii\web\View */
?>
<div class="mb-10">
    <label style="width:80px;">入库单号：</label>
    <input id="val1" type="text" style="width:150px;">
    <label style="width:80px;">入库单状态：</label>
    <select id="val2" style="width:150px;">
        <option value="">全部</option>
        <option value="1">待提交</option>
        <option value="2">审核中</option>
        <option value="3">驳回</option>
        <option value="4">已取消</option>
        <option value="5">待上架</option>
        <option value="6">已上架</option>
    </select>
    <label style="width:80px;">单据类型：</label>
    <select id="val3" style="width:150px;">
        <option value="">全部</option>
        <?php foreach($data['billType'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
    <label style="width:80px;">入仓仓库：</label>
    <select id="val4" style="width:150px;">
        <option value="">全部</option>
        <?php foreach($data['warehouse'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
</div>
<div class="mb-10">
    <label style="width:80px;">送货人：</label>
    <input id="val5" type="text" style="width:150px;">
    <label style="width:80px;">收货人：</label>
    <input id="val6" type="text" style="width:150px;">
    <label style="width:80px;">制单人：</label>
    <input id="val7" type="text" style="width:150px;">
    <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
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
                "val6":$("#val6").val(),
                "val7":$("#val7").val()
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