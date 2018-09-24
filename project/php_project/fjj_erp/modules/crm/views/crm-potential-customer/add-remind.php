<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/16
 * Time: 下午 03:44
 */

use app\assets\JqueryUIAsset;
use yii\helpers\Url;
JqueryUIAsset::register($this);
?>
<?php $form=\yii\widgets\ActiveForm::begin([
        "action"=>["remind"],
        "id"=>"remind-form"
]);?>
<div id="emailbox">
    <h2 class="head-first">新增提醒</h2>
    <div style="padding:10px;">
        <input id="cust_id" type="hidden" name="CrmImessage[cust_id]" value="<?=$modle["cust_id"];?>">
        <input type="hidden" name="CrmImessage[imesg_sentman]" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
        <div class="mb-20">
            <label class="width-80" for="">公司名称</label>
            <input id="cust_sname" type="text" class="width-150" value="<?=$model["cust_sname"]?>" disabled>
            <label class="width-80" for="">公司简称</label>
            <input id="cust_shortname" type="text" class="width-150" value="<?=$model["cust_shortname"]?>" disabled>
        </div>
        <div class="mb-20">
            <label class="width-80" for="">联系人</label>
            <input id="cust_contacts" type="text" class="width-100" value="<?=$model["cust_contacts"];?>" disabled>
            <label class="width-80" for="">手机号码</label>
            <input id="cust_tel2" type="text" class="width-100" <?=$model["cust_tel2"];?> disabled>
            <label class="width-80" for="">公司电话</label>
            <input id="cust_tel1" type="text" class="width-100" <?=$model["cust_tel1"];?> disabled>
        </div>
        <div class="mb-20">
            <label class="width-80" for="">提醒给</label>
            <input name="CrmImessage[imesg_receiver]" type="text" class="width-60 easyui-validatebox" data-options="required:'true'" onblur="setStaffInfo(this)">
            <input class="staff_id" type="hidden" name="CrmImessage[imesg_receiver]" value="">
            <span class="width-60 staff_name"></span>
            <label class="width-80" for="">开始时间</label>
            <input name="CrmImessage[imesg_btime]" type="text" class="width-100 select-date easyui-validatebox" data-options="required:'true'">
            <label class="width-80" for="">结束时间</label>
            <input name="CrmImessage[imesg_etime]" type="text" class="width-100 select-date easyui-validatebox" data-options="required:'true'">
        </div>
        <div class="mb-20">
            <label style="vertical-align: top;" class="width-80" for="">提醒内容</label>
            <textarea name="CrmImessage[imesg_notes]" style="width:480px;height:100px;" maxlength="400" class="easyui-validatebox" data-options="required:'true'"></textarea>
        </div>
        <div class="mb-20">
            <label class="width-80" for="">状态</label>
            <select class="width-100" name="CrmImessage[imesg_status]" id="">
                <option value="1">激活</option>
                <option value="2">结束</option>
            </select>
        </div>
        <div class="text-center mt-20">
            <button type="submit" class="button-blue ensure">确定</button>
            <button type="button" class="button-white cancel">取消</button>
        </div>
    </div>
</div>
<?php $form->end();?>
<script>
    $(function(){

//        var row=parent.customer;
//        $("#cust_id").val(row.cust_id);
//        $("#cust_sname").val(row.cust_sname);
//        $("#cust_shortname").val(row.cust_shortname);
//        $("#cust_contacts").val(row.cust_contacts);
//        $("#cust_tel2").val(row.cust_tel2);
//        $("#cust_tel1").val(row.cust_tel1);





        //新增提醒ajax表单
        ajaxSubmitForm($("#remind-form"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$.fancybox.close();
        });

        //关闭弹窗
        $(".cancel").click(function(){
            parent.$.fancybox.close();
        });

    });

    //获取提醒人信息
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
</script>


<style type="text/css">
    textarea{
        width:100%;
    }
    button{
        font-size: 12px;
    }
    button:hover {
        cursor: pointer;
        border: 1px solid #0e0e0e;
    }
</style>