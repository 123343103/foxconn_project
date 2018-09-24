<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/22
 * Time: 11:20
 */
$record['day'] = isset($spendTime[0])?$spendTime[0]:null;
$record['hours'] = isset($spendTime[1])?$spendTime[1]:null;
$record['minutes'] = isset($spendTime[2])?$spendTime[2]:null;
?>
<style>
    .fancybox-wrap{
        top:  0px !important;
        left: 0px !important;
    }
</style>
<div>
    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'visit-info',
    ]) ?>
    <?php if ($id != null && $silId == null) { ?>
        <h2 class="head-first">添加拜访记录</h2>
    <?php } ?>
    <?php if ($id != null && $silId != null) { ?>
        <h2 class="head-first">修改拜访记录</h2>
    <?php } ?>
    <div class="mt-20 ml-30">
        <label class="width-100">客户名称</label>
        <input type="hidden" id="cust_id" name="CrmVisitRecord[cust_id]" value="<?= $id ?>"/>
        <input type="text" id="cust_name" name="cust_sname" class="cust_name easyui-validatebox validateboxs" data-options="required:true" value="<?= $model['cust_sname'] ?>" readonly="readonly">
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">联系人</label>
        <input type="text" class="cust_contact"
               value="<?= $model['cust_contacts'] ?>" maxlength="20"/>
        <label class="width-100">联系电话</label>
        <input type="text" class="cust_tel easyui-validatebox" data-options="validType:'mobile'"
               value="<?= $model['cust_tel2'] ?>" maxlength="20"/>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">公司地址</label>
        <input type="text" class="width-400 company_place" readonly="readonly" value="<?= $model['district'][0]['district_name'] ?><?= $model['district'][1]['district_name'] ?><?= $model['district'][2]['district_name'] ?><?= $model['district'][3]['district_name'] ?><?= $model['cust_adress'] ?>"/>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">拜访人</label>
        <input type="text" data-options="required:true,validType:'staffCode',delay:10000000" name="CrmVisitRecordChild[sil_staff_code]"  value="<?= $record['sil_staff_code']  ?>"
               class="validateboxss easyui-validatebox staff_code" maxlength="20"/><span class="staff_name"> <?= $record['staff_name']  ?></span>
        <label class="width-100">拜访类型</label>
        <select name="CrmVisitRecordChild[sil_type]" class="width-150 validateboxss easyui-validatebox sil_type"
                data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['visitType'] as $val) { ?>
                <option value="<?= $val['bsp_id'] ?>" <?= $record['sil_type']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">抵达时间</label>
        <input type="text" class="width-130 select-date-time startDate validateboxss easyui-validatebox start_time_size" data-options="required:true" id="arriveDate" name="arriveDate" value="<?= $record['start']  ?>" readonly="readonly"/>
        <label class="width-140">离开时间</label>
        <input type="text" class="width-130 select-date-time endDate validateboxss easyui-validatebox end_time_size" data-options="required:true" id="leaveDate" name="leaveDate" value="<?= $record['end']  ?>" readonly="readonly"/>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">拜访用时</label>
        <input type="text" class="width-30 text-center use_day" id="day-1" name="day-1"  value="<?= $record['day']  ?>" readonly="readonly"/> 天
        <input type="text" class="width-30 text-center use_hour" id="hours-1" name="hours-1"  value="<?= $record['hours']  ?>" readonly="readonly"/> 时
        <input type="text" class="width-30 text-center use_minute" id="minutes-1" name="minutes-1" value="<?= $record['minutes']  ?>" readonly="readonly"/> 分
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">拜访总结</label>
        <textarea type="text" rows="3" class="width-500 text-top validateboxss easyui-validatebox conclus" data-options="required:true" name="CrmVisitRecordChild[sil_interview_conclus]" maxlength="20"><?= $record['sil_interview_conclus']  ?></textarea>
    </div>
    <div class="mt-20 ml-30">
        <label class="width-100">关联拜访计划</label>
        <input type="hidden" name="CrmVisitRecordChild[svp_plan_id]" id="svp_id"  value="<?= $record['svp_plan_id']  ?>"/>
        <input type="text" class="width-130 validateboxss easyui-validatebox" id="svp_code"  value="<?= $record['plan_name']  ?>" placeholder="请点击关联拜访计划"/>
        <a id="select_plan">选择关联拜访计划</a>
    </div>
    <div class="space-20"></div>
    <div class="mb-20 text-center">
        <?php if($id != null && $silId == null){ ?>
            <div class="mb-20 text-center">
                <button class="button-blue-big" type="submit" id="visit-record-add">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php } ?>
        <?php if($id != null && $silId != null){ ?>
            <div class="mb-20 text-center">
                <button class="button-blue-big" type="submit" id="visit-record-edit">确定</button>
                <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
            </div>
        <?php } ?>
    </div>
    <?php $form->end(); ?>
</div>
<script>
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
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

        $("#visit-record-add").one("click", function () {
//            $('.validateboxs').validatebox('remove');
            $('.validateboxss').validatebox('reduce');
            $("#visit-info").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-info','id'=>$id]) ?>');
            return ajaxSubmitForm($("#visit-info"));
        });
        $("#visit-record-edit").one("click", function () {
//            $('.validateboxs').validatebox('remove');
            $('.validateboxss').validatebox('reduce');
            $("#visit-info").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/edit-info','id'=>$silId]) ?>');
            return ajaxSubmitForm($("#visit-info"));
        });

        $(".close").click(function () {
            parent.$.fancybox.close();
        });
        //选择供应商窗口
        $("#select_plan").click(function(){
            var customerId=$("#cust_id").val();
            if(customerId==''){
                layer.alert('请先选择客户信息！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=\yii\helpers\Url::to(['/crm/crm-visit-record/select-plan'])?>?customerId="+customerId,
                padding: [],
                fitToView: false,
                width: 800,
                height: 530,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe'
            });
        });
        //开始时间结束时间比较大小-郭文聪
        $(".start_time_size,.end_time_size").change(function(){
            var startTime=$(".start_time_size").val();
            var endTime=$(".end_time_size").val();
            if(startTime==''||endTime==''){
                return false;
            }
            var sum=new Date(endTime.replace(/-/g,"/")).getTime() - new Date(startTime.replace(/-/g,"/")).getTime();
            console.log(sum);
            if(sum>0){
                var day=Math.floor(sum/(24*3600*1000));
                var remainder=sum%(24*3600*1000);
                var hour=Math.floor(remainder/(3600*1000));
                remainder=remainder%(3600*1000);
                var minute=Math.floor(remainder/(60*1000));
                $(".use_day").val(day);
                $(".use_hour").val(hour);
                $(".use_minute").val(minute);
            }else{
                $(".use_day").val('');
                $(".use_hour").val('');
                $(".use_minute").val('');
            }
            $(".start_time_size,.end_time_size").validatebox({validType:'startEndSize['+sum+']'});
        });
    });
    function dataTime(startDate, endDate, type) {
        var date3 = new Date(endDate).getTime() - new Date(startDate).getTime();   //时间差的毫秒数
        //计算出相差天数
        var days = Math.floor(date3 / (24 * 3600 * 1000))
        //计算出小时数
        var leave1 = date3 % (24 * 3600 * 1000)
        var hours = Math.floor(leave1 / (3600 * 1000))
        //计算天数后剩余的毫秒数
        var leave2 = leave1 % (3600 * 1000)
        //计算相差分钟数
        var minutes = Math.floor(leave2 / (60 * 1000))
        if (type == 1) {
            $("#day").val(days);
            $("#hours").val(hours);
            $("#minutes").val(minutes);
        } else {
            $("#day-1").val(days);
            $("#hours-1").val(hours);
            $("#minutes-1").val(minutes);
        }
    }
</script>
