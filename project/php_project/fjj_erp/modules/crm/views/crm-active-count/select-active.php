<?php
/**
 * User: F1677929
 * Date: 2016/11/3
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="head-first">选择活动名称</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <label class="width-60">关键词</label>
        <input id="keyword" type="text" class="width-200">
        <button id="query_btn" type="button" class="button-blue">查询</button>
        <button id="reset_btn" type="button" class="button-white">重置</button>
        <button class="button-blue float-right" style="width:75px;" onclick="parent.location.href='<?=Url::to(['/crm/crm-active-name/add'])?>?flag=count';">新增活动</button>
    </div>
    <div id="active_data" style="width:100%;"></div>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_btn">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function(){
        //jquery变量
        var $activeData=$("#active_data");

        //活动数据
        $activeData.datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"actbs_id",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10],
            columns:[[
                {field:"actbs_name",title:"活动名称",width:294},
                {field:"activeWay",title:"活动方式",width:100},
                {field:"actbs_start_time",title:"开始时间",width:150,formatter:function(value,row,index){
                    return value?value.substr(0,16):'';
                }},
                {field:"actbs_end_time",title:"结束时间",width:150,formatter:function(value,row,index){
                    return value?value.substr(0,16):'';
                }}
            ]],
            onLoadSuccess:function(data){
                datagridTip($activeData);
                if(data.total==0){
                    showEmpty($(this),data.total,0);
                }
            }
        });

        //确定
        $("#confirm_btn").click(function(){
            var obj=$activeData.datagrid('getSelected');
            if(obj==null){
                parent.layer.alert('请选择活动！',{icon:2,time:5000});
                return false;
            }
            for(x in obj){
                if(typeof obj[x]=="string"){
                    obj[x]=obj[x].decode();
                }
            }
            parent.$("#actbs_id").val(obj.actbs_id);
            parent.$("#actbs_name").val(obj.actbs_name).change();
            parent.$("#activeWay").val(obj.activeWay);
            parent.$("#acttype_name").val(obj.acttype_name);
            parent.$("#activeMonth").val(obj.activeMonth);
            parent.$("#actbs_start_time").val(obj.actbs_start_time);
            parent.$("#actbs_end_time").val(obj.actbs_end_time);
            parent.$("#activeStatus").val(obj.activeStatus);
            parent.$("#actbs_active_url").val(obj.actbs_active_url);
            parent.$("#actbs_pm").val(obj.actbs_pm);
            parent.$("#actbs_cost").val(obj.actbs_cost);
            parent.$("#actbs_roi").val(obj.actbs_roi);
            parent.$("#activeDutyPerson").val(obj.activeDutyPerson);
            parent.$("#actbs_city").val(obj.actbs_city);
            parent.$("#industryType").val(obj.industryType);
            parent.$("#actbs_organizers").val(obj.actbs_organizers);
            parent.$("#activeAddress").val(obj.activeAddress);
            parent.$("#actbs_official_url").val(obj.actbs_official_url);
            parent.$("#joinPurpose").val(obj.joinPurpose);
            parent.$("#actbs_exhibit").val(obj.actbs_exhibit);
            parent.$("#actbs_intro").val(obj.actbs_intro);
            parent.$("#maintainPerson").val(obj.maintainPerson);
            parent.$("#actbs_maintain_time").val(obj.actbs_maintain_time);
            parent.$("#actbs_maintain_ip").val(obj.actbs_maintain_ip);
            if(obj.activeWay=='线上'){
                parent.$("#online_div").show();
                parent.$("#offline_div").hide();
            }
            if(obj.activeWay=='线下'){
                parent.$("#online_div").hide();
                parent.$("#offline_div").show();
            }
            parent.$.fancybox.close();
        });

        //查询
        $("#query_btn").click(function(){
            $activeData.datagrid('load',{
                "keyword":$("#keyword").val()
            }).datagrid('clearSelections');
        });
        $(document).keydown(function(even){
            if(even.keyCode==13){
                $activeData.datagrid('load',{
                    "keyword":$("#keyword").val()
                }).datagrid('clearSelections');
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("#keyword").val('');
            $activeData.datagrid('load',{
                "keyword":''
            }).datagrid('clearSelections');
        });
    })
</script>