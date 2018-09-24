<?php
/**
 * User: F1677929
 * Date: 2017/6/16
 */
use yii\helpers\Url;
?>
<div class="mb-15">
    <label class="width-100">编号</label>
    <input id="main_code" type="text" class="width-200">
    <label class="width-100">活动类型</label>
    <select id="active_type" class="width-200">
        <option value="">请选择</option>
        <?php foreach($data['activeType'] as $key=>$val){?>
            <option value="<?=$val['acttype_id']?>"><?=$val['acttype_name']?></option>
        <?php }?>
    </select>
    <label class="width-100">活动名称</label>
    <select id="active_name" class="width-200">
        <option value="">请选择</option>
    </select>
</div>
<div class="mb-15">
    <label class="width-100">活动方式</label>
    <select id="active_way" class="width-200">
        <option value="">请选择</option>
        <?php foreach($data['activeWay'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
    <label class="width-100">活动状态</label>
    <select id="active_status" class="width-200">
        <option value="">请选择</option>
        <?php foreach($data['activeStatus'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
    <label class="width-100">月份</label>
    <select id="active_month" class="width-200">
        <option value="">请选择</option>
        <?php foreach($data['activeMonth'] as $key=>$val){?>
            <option value="<?=$key?>"><?=$val?></option>
        <?php }?>
    </select>
</div>
<div class="mb-20">
    <label class="width-100">活动时间</label>
    <input id="active_start_time" type="text" style="width:90px;" class="select-date" readonly="readonly">
    <span>至</span>
    <input id="active_end_time" type="text" style="width:91px;" class="select-date" readonly="readonly">
    <label class="width-100">活动负责人</label>
    <input id="active_duty" type="text" class="width-200">
    <button id="query_btn" type="button" class="button-blue" style="margin-left:42px;">查询</button>
    <button id="reset_btn" type="button" class="button-white">重置</button>
</div>
<script>
    $(function(){
        //jquery变量
        var $countMain=$("#count_main");

        //查询
        $("#query_btn").click(function(){
            $countMain.datagrid('load',{
                "main_code":$("#main_code").val(),
                "active_month":$("#active_month").val(),
                "active_type":$("#active_type").val(),
                "active_name":$("#active_name").val(),
                "active_way":$("#active_way").val(),
                "active_status":$("#active_status").val(),
                "active_duty":$("#active_duty").val(),
                "active_start_time":$("#active_start_time").val(),
                "active_end_time":$("#active_end_time").val()
            }).datagrid('clearSelections');
            parent.$("#count_child_title").hide().next().hide();
        });
        $(document).keydown(function(event){
            if(event.keyCode==13){
                $countMain.datagrid('load',{
                    "main_code":$("#main_code").val(),
                    "active_month":$("#active_month").val(),
                    "active_type":$("#active_type").val(),
                    "active_name":$("#active_name").val(),
                    "active_way":$("#active_way").val(),
                    "active_status":$("#active_status").val(),
                    "active_duty":$("#active_duty").val(),
                    "active_start_time":$("#active_start_time").val(),
                    "active_end_time":$("#active_end_time").val()
                }).datagrid('clearSelections');
                parent.$("#count_child_title").hide().next().hide();
            }
        });

        //重置
        $("#reset_btn").click(function(){
            $("input,select").val('').validatebox();
            $countMain.datagrid('load',{
                "main_code":'',
                "active_month":'',
                "active_type":'',
                "active_name":'',
                "active_way":'',
                "active_status":'',
                "active_duty":'',
                "active_start_time":'',
                "active_end_time":''
            }).datagrid('clearSelections');
            parent.$("#count_child_title").hide();
            parent.$("#count_child_title").next().hide();
        });

        //活动类型
        $("#active_type").change(function(){
            $("#active_name").html("<option value=''>请选择</option>");
            var typeId=$(this).val();
            if(typeId==''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['/crm/crm-active-apply/get-active-name'])?>",
                data:{"typeId":typeId},
                dataType:"json",
                success:function(data){
                    $.each(data,function(i,n){
                        $("#active_name").append("<option value='"+n.actbs_id+"'>"+n.actbs_name+"</option>");
                    })
                }
            })
        });
    })
</script>