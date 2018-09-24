<?php
/**
 * User: F1677929
 * Date: 2017/8/2
 */
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .mb-15{
        margin-bottom: 15px;
    }
    .width-48{
        width: 48px;
    }
    .width-150{
        width: 150px;
    }
.width-75{
    width: 75px;
}
</style>
<div style="margin-bottom: 10px">
<div class="mb-15">
    <label style="width:48px;">单据号</label>
    <input id="invh_code" type="text" class="width-150">
    <label class="width-75">出入库标志</label>
    <select id="inout_flag" class="width-150">
        <option value="">全部</option>
        <option value="I">入库</option>
        <option value="O">出库</option>
    </select>
    <label class="width-48">单据类型</label>
    <select id="inout_type" class="width-150">
        <option value="">全部</option>
        <option value="1">采购</option>
        <option value="2">调拨</option>
        <option value="3">移仓</option>
        <option value="wm01">新增</option>
    </select>
</div>
<div class="mb-15">
    <label class="width-48">单据状态</label>
    <select disabled="disabled"  id="invh_status" class="width-150">
        <option value="">全部</option>
        <option value="0">待出库</option>
        <option value="1">代收货</option>
        <option value="2">已收货</option>
        <option value="3">已出库</option>
        <option value="4">已取消</option>
        <option value="5">待提交</option>
        <option value="6">审核中</option>
        <option value="7">审核完成</option>
        <option value="8">驳回</option>
        <option value="9">已作废</option>
    </select>
    <select style="display: none" id="invh_statuss" class="width-150">
        <option value="">全部</option>
        <option value="1">待提交</option>
        <option value="2">审核中</option>
        <option value="3">驳回</option>
        <option value="4">已取消</option>
        <option value="5">待上架</option>
        <option value="6">已上架</option>
    </select>
    <label style="width:75px;">单据业务分类</label>
    <select id="business_code" class="width-150">
        <option value="">全部</option>
        <?php foreach($downlist['business_code'] as $key=>$val){?>
            <option value="<?=$val['business_code']?>"><?=$val['business_desc']?></option>
        <?php }?>
    </select>
    <label class="width-48">仓库类别</label>
    <select id="wh_type" class="width-150">
        <option value="">全部</option>
        <?php foreach($downlist['wh_type'] as $key=>$val){?>
            <option value="<?=$val['bsp_id']?>"><?=$val['bsp_svalue']?></option>
        <?php }?>
    </select>
</div>
<div class="mb-15">
    <label class="width-48">仓库名称</label>
    <select id="whs_id" class="width-150">
        <option value="">全部</option>
        <?php foreach($downlist['wh_name'] as $key=>$val){?>
            <option value="<?=$val['wh_id']?>"><?=$val['wh_name']?></option>
        <?php }?>
    </select>
    <label style="width:75px;">储位码</label>
    <input id="lor_id" type="text" class="width-150">
    <label class="width-48">商品信息</label>
    <input id="product_info" type="text" class="width-150">
    <label class="width-48">商品规格</label>
    <input id="ATTR_NAME" type="text" class="width-150">
</div>
<div class="mb-20">
    <label class="width-48">批次号</label>
    <input id="batch_no" type="text" class="width-150">
    <label style="width:75px;">制单人</label>
    <input id="create_by" type="text" class="width-150">
    <label class="width-48">单据日期</label>
    <input id="start_date" type="text" class="Wdate width-150" readonly="readonly">
    <span style="width:48px;text-align:center;">至</span>
    <input id="end_date" type="text" class="Wdate width-150" readonly="readonly">
    <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
    <button id="reset_btn" style="margin-left: 5px" type="button" class="reset-btn-yellow">重置</button>
</div>
</div>
<script>
    $(function(){
        //button查询
        $("#query_btn").click(function(){
            $("#product_table").datagrid('load',{
                "invh_code":$("#invh_code").val(),
                "inout_flag":$("#inout_flag").val(),
                "start_date":$("#start_date").val(),
                "end_date":$("#end_date").val(),
                "business_code":$("#business_code").val(),
                "inout_type":$("#inout_type").val(),
                "wh_type":$("#wh_type").val(),
                "whs_id":$("#whs_id").val(),
                "lor_id":$("#lor_id").val(),
                "product_info":$("#product_info").val(),
                "ATTR_NAME":$("#ATTR_NAME").val(),
                "batch_no":$("#batch_no").val(),
                "create_by":$("#create_by").val(),
                "invh_status":$("#invh_status").val()
            });
        });

        //enter键查询
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $("#product_table").datagrid('load',{
                    "invh_code":$("#invh_code").val(),
                    "inout_flag":$("#inout_flag").val(),
                    "invh_date_start":$("#invh_date_start").val(),
                    "invh_date_end":$("#invh_date_end").val(),
                    "business_code":$("#business_code").val(),
                    "inout_type":$("#inout_type").val(),
                    "wh_type":$("#wh_type").val(),
                    "whs_id":$("#whs_id").val(),
                    "lor_id":$("#lor_id").val(),
                    "product_info":$("#product_info").val(),
                    "ATTR_NAME":$("#ATTR_NAME").val(),
                    "batch_no":$("#batch_no").val(),
                    "create_by":$("#create_by").val(),
                    "invh_status":$("#invh_status").val()
                });
            }
        });

        $("#inout_flag").change(function () {
            if ($("#inout_flag").val()!="") {
                if ($("#inout_flag").val() == "I") {
                    $("#invh_status").show();
                    $("#invh_statuss").hide();
                    $("#invh_status").removeAttr("disabled");
                } else
                {
                    $("#invh_status").hide();
                    $("#invh_statuss").show();
                    $("#invh_statuss").removeAttr("disabled");
                }
            }
            else {
                $("#invh_status").attr("disabled","disabled");
                $("#invh_statuss").attr("disabled","disabled")
            }
        });
        //重置
        $("#reset_btn").click(function(){
            $("input,select").val('');
            $("#product_table").datagrid('load',{
                "invh_code":"",
                "inout_flag":"",
                "invh_date_start":"",
                "invh_date_end":"",
                "business_code":"",
                "inout_type":"",
                "wh_type":"",
                "whs_id":"",
                "lor_id":"",
                "product_info":"",
                "ATTR_NAME":"",
                "batch_no":"",
                "create_by":"",
                "invh_status":""
            });
            $("#inout_type").html("<option value=''>请选择</option>");
            $("#whs_id").html("<option value=''>请选择</option>");
            $("#lor_id").html("<option value=''>请选择</option>");
        });

        //获取单据类型
        $("#business_code").change(function(){
            $("#inout_type").html("<option value=''>请选择</option>");
            var code=$(this).val();
            if(code==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-order-type'])?>",
                data:{'code':code},
                dataType:'json',
                success:function(data){
                    $.each(data,function(i,n){
                        $("#inout_type").append("<option value='"+i+"'>"+n+"</option>");
                    })
                }
            })
        });

        //获取仓库名称
        $("#wh_type").change(function(){
            $("#whs_id").html("<option value=''>请选择</option>");
            $("#lor_id").html("<option value=''>请选择</option>");
            var id=$(this).val();
            if(id==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-warehouse'])?>",
                data:{'id':id},
                dataType:'json',
                success:function(data){
                    $.each(data,function(i,n){
                        $("#whs_id").append("<option value='"+i+"'>"+n+"</option>");
                    })
                }
            })
        });

        //获取储位信息
        $("#whs_id").change(function(){
            $("#lor_id").html("<option value=''>请选择</option>");
            var id=$(this).val();
            if(id==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-storage-location'])?>",
                data:{'id':id},
                dataType:'json',
                success:function(data){
                    $.each(data,function(i,n){
                        $("#lor_id").append("<option value='"+i+"'>"+n+"</option>");
                    })
                }
            })
        });
    });
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
//        $("#search").click(function () {
//            $("#edit_btn").hide().next().hide();
//            $("#censorship_btn").hide().next().hide();
//            $("#cancel_btn").hide().next().hide();
//            loadData();
//        });
//        $(document).keydown(function (event) {
//            if (event.keyCode == 13) {
//                loadData();
//            }
//        });

        //重置
//        $("#reset").click(function () {
//            $("#edit_btn").hide().next().hide();
//            $("#censorship_btn").hide().next().hide();
//            $("#cancel_btn").hide().next().hide();
//            $("input,select").val("");
//            loadData();
//        });
    });
</script>
