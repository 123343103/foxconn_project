<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/21
 * Time: 16:38
 */
?>
<div>
    <?php $form =\yii\widgets\ActiveForm::begin([
        'id'=>'visit-plan',
//        'action'=>['/crm/crm-customer-manage/create-visit','id'=>$id]
    ])?>
    <?php if($id != null && $svpId == null){ ?>
        <h2 class="head-first">添加拜访计划</h2>
    <?php } ?>
    <?php if($id != null && $svpId != null){ ?>
        <h2 class="head-first">修改拜访计划</h2>
    <?php } ?>
    <div class="mt-20 ml-30">
        <label class="width-100">客户名称</label>
        <input type="hidden" class="cust_id" name="CrmVisitPlan[cust_id]" value="<?= $model['cust_id'] ?>"/>
        <input type="text" id="cust_name" name="cust_sname"  class="cust_name easyui-validatebox validateboxs"  data-options="required:true" value="<?= $model['cust_sname'] ?>" readonly="readonly">
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">联系人</label>
        <input type="text" class="cust_contact" name="CrmVisitPlan[svp_contact_man]" value="<?= $model['cust_contacts'] ?>"  maxlength="20"/>
        <label class="width-100">联系电话</label>
        <input type="text" class="cust_tel easyui-validatebox" data-options="validType:'mobile'" name="CrmVisitPlan[svp_contact_tel]" value="<?= $model['cust_tel2'] ?>" maxlength="20"/>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">公司地址</label>
        <input type="text" class="width-400 company_place" readonly="readonly" value="<?= $model['district'][0]['district_name'] ?><?= $model['district'][1]['district_name'] ?><?= $model['district'][2]['district_name'] ?><?= $model['district'][3]['district_name'] ?><?= $model['cust_adress'] ?>"/>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">拜访人</label>
        <input type="text"  onblur="setStaffInfo(this)" class="easyui-validatebox validateboxs staff_code"  data-options="required:true" name="CrmVisitPlan[svp_staff_code]"  maxlength="20" value="<?= $visitPlan['svp_staff_code'] ?>"/><span class="staff_name"><?= $visitPlan['visitPerson'] ?></span>
        <label class="width-100">拜访类型</label>
        <select name="CrmVisitPlan[svp_type]" class="width-150 easyui-validatebox validateboxs svp_type"  data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach($downList['visitType'] as $val){ ?>
                <option value="<?= $val['bsp_id']?>" <?= isset($visitPlan['svp_type'])==$val['bsp_id'] ? "selected":null; ?>><?= $val['bsp_svalue']?></option>
            <?php  } ?>
        </select>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">开始时间</label>
        <input type="text"  class="width-130 select-date-time startDate easyui-validatebox validateboxs"  data-options="required:true" id="startDate" name="startDate" readonly="readonly" value="<?= $visitPlan['start'] ?>"/>
        <label class="width-140">结束时间</label>
        <input type="text" class="width-130 select-date-time endDate easyui-validatebox validateboxs"  data-options="required:true" id="endDate" name="endDate" readonly="readonly" value="<?= $visitPlan['end'] ?>"/>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">计划用时</label>
        <input type="text" class="width-30 text-center day" id="day" name="day" readonly="readonly" value="<?= $spendTime[0] ?>"/> 天
        <input type="text" class="width-30 text-center hours" id="hours" name="hours" readonly="readonly" value="<?= $spendTime[1] ?>"/> 时
        <input type="text" class="width-30 text-center minutes" id="minutes" name="minutes" readonly="readonly" value="<?= $spendTime[2] ?>"/> 分
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">计划内容</label>
        <textarea type="text" rows="3" class="width-500 text-top easyui-validatebox validateboxs svp_content"  data-options="required:true" name="CrmVisitPlan[svp_content]"  maxlength="200"><?= $visitPlan['svp_content'] ?></textarea>
    </div>
    <div class="space-20"></div>
    <?php if($id != null && $svpId == null){ ?>
            <div class="mb-20 text-center">
                <button class="button-blue-big" type="submit" id="visit-plan-add">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
    <?php } ?>
    <?php if($id != null && $svpId != null){ ?>
            <div class="mb-20 text-center">
                <button class="button-blue-big" type="submit" id="visit-plan-edit">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
    <?php } ?>
    <?php $form->end(); ?>
</div>
<script>
    function setStaffInfo(obj){
        var url="<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj,url);
    }

    $(function () {
        //验证规则
        $.extend($.fn.validatebox.methods, {
            remove: function (jq, newposition) {
                return jq.each(function () {
                    $(this).removeClass("validatebox-text validatebox-invalid").unbind('focus.validatebox').unbind('blur.validatebox');
                });
            },
            reduce: function (jq, newposition) {
                return jq.each(function () {
                    var opt = $(this).data().validatebox.options;
                    $(this).addClass("validatebox-text").validatebox(opt);
                });
            }
        });

        $("#visit-plan-add").on("click", function () {
            $('.validateboxss').validatebox('remove');
            $('.validateboxs').validatebox('reduce');
            $("#visit-plan").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-visit','id'=>$id]) ?>');
            return ajaxSubmitForm($("#visit-plan"));
        });
        $("#visit-plan-edit").on("click", function () {
            $('.validateboxss').validatebox('remove');
            $('.validateboxs').validatebox('reduce');
            $("#visit-plan").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/edit-plan','id'=>$svpId]) ?>');
            return ajaxSubmitForm($("#visit-plan"));
        });

        $(".select-date-time").click(function () {
            jeDate({
                dateCell: this,
                isToday:false,
                zIndex:8831,
                format: "YYYY-MM-DD hh:mm",
                skinCell: "jedatedeep",
                isTime: true,
                ishmsVal:true,
                //点击确认后
                okfun:function(elem, val) {
                    if($(elem).attr('id')=='startDate' || $(elem).attr('id')=='endDate'){
                        dataTime($("#startDate").val(),$("#endDate").val(),1)
                    }else{
                        dataTime($("#arriveDate").val(),$("#leaveDate").val(),2)
                    }
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun:function(elem, val) {
                    if($(elem).attr('id')=='startDate' || $(elem).attr('id')=='endDate'){
                        dataTime($("#startDate").val(),$("#endDate").val(),1)
                    }else{
                        dataTime($("#arriveDate").val(),$("#leaveDate").val(),2)
                    }
                },
                //选中日期后的回调, elem当前输入框ID, val当前选择的值
                clearfun:function(elem, val) {
                    dataTime(val,val)
                },
            })
        });
    })
    function dataTime(startDate,endDate,type){
        var date3 = new Date(endDate).getTime() - new Date(startDate).getTime();   //时间差的毫秒数
        //计算出相差天数
        var days=Math.floor(date3/(24*3600*1000))
        //计算出小时数
        var leave1=date3%(24*3600*1000)
        var hours=Math.floor(leave1/(3600*1000))
        //计算天数后剩余的毫秒数
        var leave2=leave1%(3600*1000)
        //计算相差分钟数
        var minutes=Math.floor(leave2/(60*1000))
        if(type == 1){
            $("#day").val(days);
            $("#hours").val(hours);
            $("#minutes").val(minutes);
        }else{
            $("#day-1").val(days);
            $("#hours-1").val(hours);
            $("#minutes-1").val(minutes);
        }
    }
</script>
