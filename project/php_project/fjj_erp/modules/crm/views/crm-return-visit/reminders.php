<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/25
 * Time: 15:52
 */
use \yii\widgets\ActiveForm;
?>
<h1 class="head-first">提醒事项</h1>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-20">
        <label class="width-80">公司名称</label>
        <input class="width-200" type="text" value="<?= $model['cust_sname'] ?>" readonly="readonly">
        <label class="width-150">公司简称</label>
        <input class="width-200" type="text" value="<?= $model['cust_shortname'] ?>" readonly="readonly">
    </div>
    <div class="mb-20">
        <label class="width-80">联系人</label>
        <input class="width-140" type="text" value="<?= $model['cust_contacts'] ?>" readonly="readonly">
        <label class="width-80">手机号码</label>
        <input class="width-120" type="text" value="<?= $model['cust_tel2'] ?>" readonly="readonly">
        <label class="width-80">公司电话</label>
        <input class="width-120" type="text" value="<?= $model['cust_tel1'] ?>" readonly="readonly">
    </div>
    <div class="mb-20">

        <label class="width-80"><span class="red">*</span>提醒给</label>
        <select class="width-140 easyui-validatebox" name="CrmImessage[imesg_receiver]"
                data-options="required:'true'">
            <option value="">请选择...</option>
            <?php foreach ($employee as $key => $val) { ?>
                <option
                        value="<?= $val['staffName']['staff_id'] ?>"><?= $val['staffName']['staff_code'] ?>--<?= $val['staffName']['staff_name'] ?></option>
            <?php } ?>
        </select>
        <label class="width-80">开始时间</label>
        <input class="width-120 select-date-time easyui-validatebox start_time start_time_size" data-options="required:'true'" type="text" name="CrmImessage[imesg_btime]" value="" readonly="readonly">
        <label class="width-80">结束时间</label>
        <input class="width-120 select-date-time easyui-validatebox end_time end_time_size" data-options="required:'true'" type="text" name="CrmImessage[imesg_etime]" value="" readonly="readonly">
    </div>
    <div class="mb-20">
        <label class="width-80 vertical-top">提醒内容</label>
        <textarea style="width:556px;height:50px;" name="CrmImessage[imesg_notes]" class="easyui-validatebox" data-options="required:true,tipPosition:'bottom',validType:'maxLength[200]'"  maxlength="200"></textarea>
    </div>
    <div class="mb-20">
        <label class="width-80">状态</label>
        <select name="CrmImessage[imesg_status]" class="width-200 status easyui-validatebox" data-options="required:'true'">
            <option value="0">结束</option>
            <option value="1" selected="selected">激活</option>
        </select>
    </div>
    <div class="text-center">
        <button type="button" class="button-blue-big" id="sub">确定</button>
        <button class="button-white-big" onclick="close_select()" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>

    $(function(){
//        ajaxSubmitForm($("#add-form"));
        /*新增提醒ajax*/
        $("#sub").on('click',function(){
            if (!$('#add-form').form('validate')) {
                return false;
            }else{
                $.ajax({
                    url:'<?= \yii\helpers\Url::to(["/crm/crm-return-visit/reminders"]) ?>?id='+<?= $model['cust_id'] ?>,
                    type:'POST',
                    dataType:'json',
                    data:$("#add-form").serialize(),
                    success:function(data){
                        if(data.flag === 1){
                            layer.alert(data.msg,{icon:1},function(){
                                parent.$.fancybox.close();
                            });
                        }else{
                            layer.alert(data.msg,{icon:2})
                        }
                    }
                })
            }
        });
    })

</script>
