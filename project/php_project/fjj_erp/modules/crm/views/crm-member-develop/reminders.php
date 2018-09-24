<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/25
 * Time: 15:52
 */
use \yii\widgets\ActiveForm;
use app\assets\JeDateAsset;
use \yii\helpers\Url;
JeDateAsset::register($this);
if(!empty($reminder)){
    $this->title = '修改提醒消息';
    $type = '';
    if(strtotime($reminder['imesg_btime'])> time()){
        $type = 1;      //未开始
    }else if(strtotime($reminder['imesg_btime']) < time() && strtotime($reminder['imesg_etime']) >time()){
        $type = 2;      //进行中
    }
}else{
    $this->title = '新增提醒消息';
}
?>
<style>

    .mb-10{
        margin-bottom:10px;
    }
    .ml-15{
             margin-left:15px;
         }

    .label-width{
        width:80px;
    }

    .value-width{
        width:200px !important;
    }
    .ml-45{
        margin-left:45px;
    }
</style>
<h1 class="head-first"><?= $this->title ?></h1>
<div class="content ml-15">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10">
        <input type="hidden" name="CrmImessage[cust_id]" class="cust_id" value="<?= $model['cust_id'] ?>">
        <label class="label-width label-align">公司名称</label><label>:</label>
        <span class="value-width"><?= $model['cust_sname'] ?></span>
        <label class="label-width ml-45 label-align">公司简称</label><label>:</label>
        <span class="value-width"><?= $model['cust_shortname'] ?></span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">公司电话</label><label>:</label>
        <span class="value-width"><?= $model['cust_tel1'] ?></span>
        <label class="label-width ml-45 label-align">联系人</label><label>:</label>
        <span class="value-width"><?= $model['cust_contacts'] ?></span>
    </div>
    <?php if($type == 2){ ?>
        <div class="mb-10">
            <label class="label-width label-align">手机号码</label><label>:</label>
            <span class="value-width value-align"><?= $model['cust_tel2'] ?></span>
            <label class="label-width ml-45 label-align">提醒给</label><label>:</label>
            <input type="hidden" class="staff_id" value="<?= $reminder['imesg_receiver'] ?>">
            <span class="value-width value-align"><?= $reminder['receiver'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">开始日期</label><label>:</label>
            <input type="text"  name="CrmImessage[imesg_btime]" readonly="readonly" id="start_time" class="Wdate value-width value-align easyui-validatebox display-none" data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d %H:%m', maxDate: '#F{$dp.$D(\'end_time\');}' })" value="<?= date('Y-m-d H:i',strtotime($reminder['imesg_btime'])) ?>" />
            <span class="value-width value-align"><?= date('Y-m-d H:i',strtotime($reminder['imesg_btime'])) ?></span>
            <label class="ml-45 label-width label-align"><span class="red">*</span>结束日期</label><label>:</label>
            <input type="text" readonly="readonly" id="end_time" class="Wdate value-width value-align easyui-validatebox"  data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', minDate: '%y-%M-%d %H:%m,#F{$dp.$D(\'start_time\');}' })" name="CrmImessage[imesg_etime]" value="<?= date('Y-m-d H:i',strtotime($reminder['imesg_etime'])) ?>" />
        </div>
        <div class="mb-10">
            <label class="label-width label-align">提醒内容</label><label>:</label>
            <span style="width:535px;"><?= $reminder['imesg_notes'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">是否关闭</label><label>:</label>
            <input type="checkbox" value="2" style="display: inline-block;vertical-align: middle;margin-bottom: 2px; " name="CrmImessage[imesg_status]">
        </div>
        <div class="text-center">
            <button type="submit" class="button-blue-big" id="sub_update">确定</button>
            <button class="button-white-big" onclick="close_select()" type="button">返回</button>
        </div>
    <?php }else if($type==1){ ?>
        <div class="mb-10">
            <label class="label-width label-align">手机号码</label><label>:</label>
            <span class="value-width"><?= $model['cust_tel2'] ?></span>
            <label class="label-width ml-45 label-align">提醒给</label><label>:</label>
            <input type="hidden" class="staff_id" value="<?= $reminder['imesg_receiver'] ?>">
            <span class="value-width value-align"><?= $reminder['receiver'] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>开始日期</label><label>:</label>
            <input type="text" name="CrmImessage[imesg_btime]" readonly="readonly" id="start_time" class="Wdate value-width value-align easyui-validatebox" data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate');$('#start_time').validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d %H:%m', maxDate: '#F{$dp.$D(\'end_time\');}' })" value="<?= date('Y-m-d H:i',strtotime($reminder['imesg_btime'])) ?>" />
            <label class="ml-45 label-width label-align"><span class="red">*</span>结束日期</label><label>:</label>
            <input type="text" readonly="readonly" id="end_time" class="Wdate value-width value-align easyui-validatebox"  data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', minDate: '#F{$dp.$D(\'start_time\');}' })" name="CrmImessage[imesg_etime]" value="<?= date('Y-m-d H:i',strtotime($reminder['imesg_etime'])) ?>" />
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top"><span class="red">*</span>提醒内容</label><label class="vertical-top">:</label>
            <textarea style="width:535px;" name="CrmImessage[imesg_notes]" rows="3" class="easyui-validatebox value-align" data-options="required:'true',validType:'maxLength[200]'" maxlength="200" placeholder="最多输入200个字"><?= $reminder['imesg_notes'] ?></textarea>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">是否关闭</label><label>:</label>
            <input type="checkbox" value="2" style="display: inline-block;vertical-align: middle;margin-bottom: 2px; " name="CrmImessage[imesg_status]">
        </div>
        <div class="text-center">
            <button type="submit" class="button-blue-big" id="sub_unstart">确定</button>
            <button class="button-white-big" onclick="close_select()" type="button">返回</button>
        </div>
    <?php }else{ ?>
        <div class="mb-10">
            <label class="label-width label-align">手机号码</label><label>:</label>
            <span class="value-width"><?= $model['cust_tel2'] ?></span>
            <label class="label-width ml-45 label-align"><span class="red">*</span>提醒给</label><label>:</label>
            <select class="value-width staff_id value-align easyui-validatebox" name="CrmImessage[imesg_receiver]"
                    data-options="required:'true'">
                <option value="">请选择...</option>
                <?php foreach ($employee as $key => $val) { ?>
                    <option
                            value="<?= $val['staffName']['staff_id'] ?>" <?= Yii::$app->user->identity->staff_id == $val['staffName']['staff_id']?"selected":"" ?>><?= $val['staffName']['staff_code'] ?>--<?= $val['staffName']['staff_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>开始日期</label><label>:</label>
            <input type="text" name="CrmImessage[imesg_btime]" readonly="readonly" id="start_time" class="Wdate value-width value-align easyui-validatebox" data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm',minDate:'%y-%M-%d %H:%m', maxDate: '#F{$dp.$D(\'end_time\');}' })" />
            <label class="ml-45 label-width label-align"><span class="red">*</span>结束日期</label><label>:</label>
            <input type="text" readonly="readonly" id="end_time" class="Wdate value-width value-align easyui-validatebox"  data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate');$('#start_time').validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', minDate: '#F{$dp.$D(\'start_time\');}' })" name="CrmImessage[imesg_etime]" />
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top"><span class="red">*</span>提醒内容</label><label class="vertical-top">:</label>
            <textarea style="width:535px;" name="CrmImessage[imesg_notes]" rows="3" class="easyui-validatebox value-align" data-options="required:'true',validType:'maxLength[200]'" maxlength="200"></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="button-blue-big" id="sub">确定</button>
            <button class="button-white-big" onclick="close_select()" type="button">返回</button>
        </div>
    <?php } ?>

    <?php ActiveForm::end() ?>
</div>
<script>

    $(function(){

        /*新增提醒ajax*/

        ajaxSubmitForm($("#add-form"),'',function(data){
            if (data.flag === 1) {
                layer.alert(data.msg, {icon: 1}, function () {
                    parent.window.location.reload();
                });
            } else {
                layer.alert(data.msg, {icon: 2})
            }

        });
//        $("#sub").on('click',function(){
//            if (!$('#add-form').form('validate')) {
//                return false;
//            }else{
//                var code = $('.staff_id').val();
//                var start = Date.parse($("#start_time").val().replace(/-/g,"/"));
//                var end = Date.parse($("#end_time").val().replace(/-/g,"/"));
//                var id = $('.cust_id').val();
//                var childId = '<?//= $reminder["imesg_id"] ?>//';
//                var from = '<?//= $from ?>//';
//                $.ajax({
//                    type: "get",
//                    dataType: "json",
//                    async: false,
//                    data: {'id':id,'childId': childId,"code": code,'start':start,'end':end},
//                    url: "<?//=Url::to(['/crm/crm-member-develop/check-reminder-time']) ?>//",
//                    success: function (data) {
//                        if(data === '0'){
//                            $.ajax({
//                                url:'<?//= Url::to(["create-reminders"]) ?>//',
//                                type:'POST',
//                                dataType:'json',
//                                data:$("#add-form").serialize(),
//                                success:function(data){
//                                    if(data.flag === 1){
//                                        layer.alert(data.msg,{icon:1},function(){
////                                            parent.window.location.href= data.url;
//                                            parent.window.location.reload();
//                                        });
//                                    }else{
//                                        layer.alert(data.msg,{icon:2})
//                                    }
//                                }
//                            })
//                        }else{
//                            layer.alert("该时间段已有记录!", {icon: 2, time: 5000});
//                            return false;
//                        }
//                    }
//                })
//            }
//        });
//        $("#sub_update,#sub_unstart").on('click',function(){
//            if (!$('#add-form').form('validate')) {
//                return false;
//            }else{
//                var code = $('.staff_id').val();
//                var start = Date.parse($("#start_time").val().replace(/-/g,"/"));
//                var end = Date.parse($("#end_time").val().replace(/-/g,"/"));
//                var id = $('.cust_id').val();
//                var childId = '<?//= $reminder["imesg_id"] ?>//';
//                var from = '<?//= $from ?>//';
//                $.ajax({
//                    type: "get",
//                    dataType: "json",
//                    async: false,
//                    data: {'id':id,'childId': childId,"code": code,'start':start,'end':end},
//                    url: "<?//=Url::to(['/crm/crm-member-develop/check-reminder-time']) ?>//",
//                    success: function (data) {
//                        if(data === '0'){
//                            $.ajax({
//                                url:'<?//= Url::to(["update-reminders"]) ?>//?id='+childId,
//                                type:'POST',
//                                dataType:'json',
//                                data:$("#add-form").serialize(),
//                                success:function(data){
//                                    if(data.flag === 1){
//                                        layer.alert(data.msg,{icon:1},function(){
////                                            parent.window.location.href= data.url;
//                                            parent.window.location.reload();
//                                        });
//                                    }else{
//                                        layer.alert(data.msg,{icon:2})
//                                    }
//                                }
//                            })
//                        }else{
//                            layer.alert("该时间段已有记录!", {icon: 2, time: 5000});
//                            return false;
//                        }
//                    }
//                })
//            }
//        });
        $.extend($.fn.validatebox.defaults.rules, {
                timeCompare: {
                    validator: function () {
                        var start_time = $('#start_time').val().replace(/-/g,"/");
                        var end_time = $('#end_time').val().replace(/-/g,"/");
                        if (start_time === '' || end_time === '') {
                            return true;
                        }
                        var diff = Date.parse(end_time) - Date.parse(start_time);
                        var name = $(this).attr('id');
                        if (name === 'start_time') {
                            $.fn.validatebox.defaults.rules.timeCompare.message = '开始时间必须小于结束时间';
                        }
                        if (name === 'end_time') {
                            $.fn.validatebox.defaults.rules.timeCompare.message = '结束时间必须大于开始时间';
                        }
                        return diff > 0;
                    },
                    message: '时间错误'
                },
            }
        );

    })

</script>
