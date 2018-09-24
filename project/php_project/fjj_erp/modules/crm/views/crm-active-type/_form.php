<?php
/**
 * User: F1677929
 * Date: 2017/3/9
 */
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JqueryUIAsset::register($this);
?>
<?php ActiveForm::begin(['id'=>'active_type_form']);?>
<?php if(!empty($editData)){?>
<div class="mb-20">
    <label class="width-120">编<span style="width:24px;"></span>号</label>
    <span><?=$editData['acttype_code']?></span>
</div>
<?php }?>
<div class="mb-20">
    <label class="width-120"><span class="red">*</span>活动类型</label>
    <select id="active_type" class="width-250 easyui-validatebox" data-options="required:true,tipPosition:'bottom'" name="CrmActiveType[acttype_name]">
        <option value="">请选择</option>
    </select>
</div>
<div class="mb-20">
    <label class="width-120"><span class="red">*</span>活动方式</label>
    <select class="width-250 easyui-validatebox" data-options="required:true,tipPosition:'bottom'" name="CrmActiveType[acttype_way]">
        <option value="">请选择</option>
        <?php foreach($addEditData['activeWay'] as $key=>$val){?>
            <option value="<?=$key?>" <?=$editData['acttype_way']==$key?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
</div>
<div class="mb-20">
    <label class="width-120 vertical-top">描<span style="width:24px;"></span>述</label>
    <textarea style="height:60px;width:250px;" class="easyui-validatebox" data-options="validType:'length[0,250]',tipPosition:'bottom'" name="CrmActiveType[acttype_description]"><?=$editData['acttype_description']?></textarea>
</div>
<div class="mb-30">
    <label class="width-120">状<span style="width:24px;"></span>态</label>
    <select class="width-250" name="CrmActiveType[acttype_status]">
        <?php foreach($addEditData['activeTypeStatus'] as $key=>$val){?>
            <option value="<?=$key?>" <?=$editData['acttype_status']==$key?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
</div>
<div class="text-center mb-20">
    <button class="button-blue mr-10" type="submit">保存</button>
    <button class="button-white" type="button" onclick="parent.$.fancybox.close();">取消</button>
</div>
<?php ActiveForm::end();?>
<script>
    $(function(){
        //ajax提交表单
        ajaxSubmitForm($("#active_type_form"),'',function(data){
            parent.$(".fancybox-overlay").hide();
            parent.layer.alert(data.msg,{
                icon:1,
                end:function(){
                    parent.location.reload();
                }
            });
        });

        //追加活动类型
        $.ajax({
            url:"<?=Url::to(['get-active-type'])?>",
            data:{"id":"<?=$editData['acttype_name']?>"},
            success:function(data){
                if(data.length>0){
                    var obj=JSON.parse(data);
                    $.each(obj,function(i,n){
                        var str="<option value='"+n.bsp_id+"'>"+n.bsp_svalue+"</option>";
                        $("#active_type").append(str);
                    });
                    $("#active_type").find("option[value='<?=$editData['acttype_name']?>']").prop("selected",true);
                    $("#active_type").validatebox();
                }
            }
        })
    })
</script>