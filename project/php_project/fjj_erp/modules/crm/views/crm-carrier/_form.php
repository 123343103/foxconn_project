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
<style>
    label {
        line-height: normal;
        vertical-align: middle;
    }
</style>
<?php $form=ActiveForm::begin(['id'=>'carrier_form']);?>
<?php if(!empty($editData)){?>
<div class="mb-20">
    <label class="width-70 ml-50">编<span style="width:24px;"></span>号</label>
    <span><?=$editData['cc_code']?></span>
</div>
<?php }?>
<div class="mb-20">
    <label class="width-70 ml-50"><span class="red">*</span>载体名称</label>
    <input type="text" class="width-250 easyui-validatebox" data-options="required:true,validType:['length[0,50]','unique'],delay:1000000,validateOnBlur:true,tipPosition:'bottom'" data-act="<?=Url::to(['validate'])?>" data-attr="cc_name" data-id="<?=$editData['cc_id']?>" name="CrmCarrier[cc_name]" value="<?=$editData['cc_name']?>">
</div>
<div class="mb-20 add_readonly">
    <label class="width-70 ml-50"><span class="red">*</span>载<span style="width:24px;"></span>体</label>
    <select class="width-250 easyui-combobox easyui-validatebox" name="CrmCarrier[cc_carrier][]" data-options="required:true,tipPosition:'bottom'" multiple="multiple">
        <?php foreach($carrierType as $key=>$val){?>
            <option value="<?=$key?>" <?=!empty($editData['cc_carrier'])&&in_array($key,explode(',',$editData['cc_carrier']))?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
</div>
<div class="mb-20 add_readonly">
    <label class="width-70 ml-50"><span class="red">*</span>所属社群营销方式</label>
    <select class="width-250 easyui-combobox easyui-validatebox" name="CrmCarrier[cc_sale_way][]" data-options="required:true,tipPosition:'bottom'" multiple="multiple">
        <?php foreach($saleWay as $key=>$val){?>
            <option value="<?=$key?>" <?=!empty($editData['cc_sale_way'])&&in_array($key,explode(',',$editData['cc_sale_way']))?'selected':''?>><?=$val?></option>
        <?php }?>
    </select>
</div>
<div class="mb-30 add_readonly">
    <label class="width-70 ml-50"><span class="red">*</span>状<span style="width:24px;"></span>态</label>
    <select class="width-250 easyui-combobox" name="CrmCarrier[cc_status]">
        <?php foreach($carrierStatus as $key=>$val){?>
            <option value="<?=$key?>" <?=$editData['cc_status']==$key?'selected':''?>><?=$val?></option>
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
        ajaxSubmitForm($("#carrier_form"),'',function(data){
            if(data.flag==1){
                parent.$(".fancybox-overlay").hide();
                parent.layer.alert(data.msg,{
                    icon:1,
                    end:function(){
                        <?php if(empty($editData)){?>
                        parent.$("#carrier_table").datagrid('load').datagrid('clearSelections');
                        <?php }else{?>
                        parent.$("#carrier_table").datagrid('reload');
                        <?php }?>
                        parent.$.fancybox.close();
                    }
                });
            }
            if(data.flag==0){
                parent.layer.alert(data.msg,{icon:2});
                $("button[type='submit']").prop("disabled",false);
            }
        });

        //禁止easyui-combobox输入
        $(window).load(function(){
            $(".add_readonly").find("input:first").attr("readonly","readonly").css("background-color","white");
            $("input").mousemove(function(){
                this.title=this.value;
            });
        });
    })
</script>