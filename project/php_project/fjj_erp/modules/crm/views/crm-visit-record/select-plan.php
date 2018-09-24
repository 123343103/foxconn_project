<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="head-first">选择关联计划</div>
<div class="mb-10" style="margin-left:15px;margin-right:15px;">
    <div class="mb-10">
        <input id="keyword" type="text" style="width:200px;" placeholder="请输入查询信息">
        <button id="query_btn" type="button" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="plan_data" style="width:100%;"></div>
</div>
<div class="text-center" style="margin-bottom:20px;">
    <button type="button" class="button-blue" style="margin-right:20px;" id="confirm_plan">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    //加载数据
    function loadData(){
        $("#plan_data").datagrid('load',{
            "keyword":$("#keyword").val()
        });
    }

    $(function () {
        $("#plan_data").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"svp_id",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10],
            columns:[[
                {field:"svp_code",title:"档案编号",width:150},
                {field:"start",title:"开始时间",width:130,formatter: function (value,row,index) {
                    if (row.start) {
                        return row.start.substr(0,16) || '';
                    } else {
                        return '';
                    }
                }},
                {field:"end",title:"结束时间",width:130,formatter: function (value,row,index) {
                    if (row.start) {
                        return row.end.substr(0,16) || '';
                    } else {
                        return '';
                    }
                }},
                {field:"svp_content",title:"计划内容",width:294}
            ]],
            onLoadSuccess:function(data){
                datagridTip("#plan_data");
                showEmpty($(this),data.total,0);
            },
            onDblClickRow:function(){
                $("#confirm_plan").click();
            }
        });

        //确定
        $("#confirm_plan").click(function(){
            var obj=$("#plan_data").datagrid('getSelected');
            if(obj==null){
                parent.layer.alert('请选择计划！',{icon:2,time:5000});
                return false;
            }
            parent.$("#svp_id").val(obj.svp_id);
            parent.$("#svp_code").val(obj.svp_code).validatebox('validate');
            parent.$("#plan_st").text(obj.start);
            parent.$("#plan_et").text(obj.end);
            parent.$("#start_time").val(obj.start);
            parent.$("#end_time").val(obj.end);
            parent.$("#accompany_tbody tr").remove();
            $.each(obj.accompanyData,function(i,n){
                parent.$("#add_accompany").click();
                parent.$(".staff_id:last").val(n.staff_id);
                parent.$(".staff_code:last").val(n.staff_code);
                parent.$(".staff_name:last").text(n.staff_name);
                parent.$(".job_task:last").text(n.title_name);
                parent.$(".staff_mobile:last").val(n.acc_mobile );
            });
            parent.$.fancybox.close();
        });

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
            $("input,select").val('');
            loadData();
        });
    })
</script>