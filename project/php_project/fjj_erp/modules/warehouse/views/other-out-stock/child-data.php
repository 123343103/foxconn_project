<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:32
 */
?>
<p class="head mt-20 mb-10">出口商品信息</p>
<div id="child-data"></div>
<style>
    body{
        background: #fff;
    }
</style>
<script>
    $(function(){
        $("#child-data").datagrid({
            rownumbers:true,
            columns:[[
                {field:"a","title":"料号",width:200},
                {field:"b","title":"品名",width:200},
                {field:"c","title":"规格/型号",width:200},
                {field:"d","title":"品牌",width:200},
                {field:"e","title":"库存数量",width:200},
                {field:"f","title":"需求出库数量",width:200},
                {field:"g","title":"出库数量",width:200},
                {field:"h","title":"单位",width:200}
            ]]
        });
    });
</script>
