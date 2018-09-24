<?php
/**
 * User: F1677929
 * Date: 2017/11/28
 */
use yii\helpers\Url;
?>
<h1 class="head-first">选择料号</h1>
<div style="margin:0 15px">
    <div class="mb-10">
        <label style="width:40px;">类别：</label>
        <select id="pdt_category" style="width:200px;">
            <?php foreach($data['pdtCategory'] as $key=>$val){?>
                <option value="<?=$val['catg_id']?>"><?=$val['catg_name']?></option>
            <?php }?>
        </select>
        <input id="pdt_info" type="text" style="width:200px;margin-left:10px;" placeholder="请输入料号/品名">
        <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="table_date" style="width:100%;"></div>
    <div style="text-align:center;margin-top:10px;">
        <button type="button" class="button-blue" id="confirm_btn">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<script>
    $(function(){
        $("#table_date").datagrid({
            url:"<?=Url::to(['select-pno'])?>",
            queryParams:{
                "pdt_category":$("#pdt_category").val(),
                "pdt_info":$("#pdt_info").val()
            },
            rownumbers:true,
            method:"get",
            pagination:true,
            singleSelect:true,
            idField:"prt_pkid",
            columns:[[
                {field:"part_no",title:"料号",width:237},
                {field:"pdt_name",title:"品名",width:237},
                {field:"tp_spec",title:"规格",width:238}
            ]],
            onLoadSuccess:function(data){
                showEmpty($(this),data.total,0);
            },
            onDblClickRow:function(){
                $("#confirm_btn").click();
            }
        });

        //查询
        function loadData(){
            $("#table_date").datagrid('load',{
                "pdt_category":$("#pdt_category").val(),
                "pdt_info":$("#pdt_info").val()
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
            $("input").val('');
            $("select option:first").prop("selected",true);
            loadData();
        });

        //确定
        $("#confirm_btn").click(function(){
            var row=$("#table_date").datagrid("getSelected");
            if(row == null){
                layer.alert("请选择料号",{icon:2});
                return false;
            }
            parent.selectPnoCallback(row);
            parent.$.fancybox.close();
        });

        $("#pdt_category").change(function(){
            loadData();
        });
    });
</script>