<?php
/**
 * User: F1677929
 * Date: 2017/12/5
 */
?>
<div class="mb-10">
    <label style="width:50px;">编码：</label>
    <input id="rcp_no" type="text" style="width:200px;">
    <label style="width:100px;">收货中心名称：</label>
    <input id="rcp_name" type="text" style="width:200px;">
    <label style="width:60px;">联系人：</label>
    <input id="contact" type="text" style="width:200px;">
    <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
</div>
<script>
    $(function(){
        //查询
        function loadData(){
            $("#table1").datagrid('load',{
                "rcp_no":$("#rcp_no").val(),
                "rcp_name":$("#rcp_name").val(),
                "contact":$("#contact").val()
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