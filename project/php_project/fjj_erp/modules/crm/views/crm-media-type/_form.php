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
<?php ActiveForm::begin(['id'=>'media_type_form']);?>
<?php if(!empty($editData)){?>
<div class="mb-20">
    <label class="width-100">编<span style="width:24px;"></span>号</label>
    <span><?=$editData['cmt_code']?></span>
</div>
<?php }?>
<div class="mb-20">
    <label class="width-120"><span class="red">*</span>媒体类型</label>
    <input type="text" class="width-250 easyui-validatebox" data-options="required:true,validType:['length[0,50]','unique'],delay:1000000,validateOnBlur:true,tipPosition:'bottom'" data-act="<?=Url::to(['validate'])?>" data-attr="cmt_type" data-id="<?=$editData['cmt_id']?>" name="CrmMediaType[cmt_type]" value="<?=$editData['cmt_type']?>">
</div>
<div class="mb-20">
    <label class="width-120 vertical-top">简<span style="width:24px;"></span>介</label>
    <textarea style="height:60px;width:250px;" class="easyui-validatebox" data-options="validType:'length[0,200]',tipPosition:'bottom'" name="CrmMediaType[cmt_intro]"><?=$editData['cmt_intro']?></textarea>
</div>
<div class="mb-30">
    <label class="width-120">状<span style="width:24px;"></span>态</label>
    <select class="width-250" name="CrmMediaType[cmt_status]">
        <?php foreach($mediaStatus as $key=>$val){?>
            <option value="<?=$key?>" <?=$editData['cmt_status']==$key?'selected':''?>><?=$val?></option>
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
        ajaxSubmitForm($("#media_type_form"),'',function(data){
            parent.$(".fancybox-overlay").hide();
            parent.layer.alert(data.msg,{
                icon:1,
                end:function(){
                    parent.$("#media_table").datagrid('reload');
                    parent.$.fancybox.close();
                }
            });
        });
    })
</script>