<?php
/**
 * User: F1677929
 * Date: 2017/11/30
 */
/* @var $this yii\web\View */
?>
<div class="mb-10">
    <label style="width:100px;">料号：</label>
    <input id="part_no" type="text" style="width:200px;">
    <label style="width:100px;">品名：</label>
    <input id="pdt_name" type="text" style="width:200px;">
</div>
<div class="mb-10">
    <label style="width:100px;">供应商代码：</label>
    <input id="spp_code" type="text" style="width:200px;">
    <label style="width:100px;">供应商名称：</label>
    <input id="spp_fname" type="text" style="width:200px;">
    <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
</div>
<script>
    $(function(){
        //查询
        function loadData(){
            $("#datagrid1").datagrid('load',{
                "flag":$(".table-head ul li[style='background-color: rgb(210, 210, 210);']").data("flag"),
                "part_no":$("#part_no").val(),
                "pdt_name":$("#pdt_name").val(),
                "spp_code":$("#spp_code").val(),
                "spp_fname":$("#spp_fname").val()
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
            $(".table-head ul li").css("background-color","white");
            $(".table-head ul li:first").css("background-color","#d2d2d2");
            loadData();
        });
    })
</script>