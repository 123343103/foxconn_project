<?php
/**
 * User: F1677929
 * Date: 2017/12/5
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<style>
    label{
        width:110px;
    }
    input{
        width:200px;
    }
    select{
        width:200px;
    }
</style>
<div class="head-first"><?=$this->title?></div>
<?php ActiveForm::begin();?>
<div class="mb-10">
    <?php if(empty($editData)){?>
        <label><span style="color:red;">*</span>收货中心名称：</label>
        <input type="text" class="easyui-validatebox" data-options="required:true,validType:['maxLength[30]','unique'],delay:1000000" onchange="$(this).validatebox('validate')" data-act="<?=Url::to(['validate'])?>" data-attr="rcp_name" name="BsReceipt[rcp_name]" maxlength="30">
    <?php }else{?>
        <label>收货中心名称：</label>
        <span style="width:400px;"><?=$editData['rcp_name']?></span>
    <?php }?>
</div>
<div class="mb-10">
    <label><span style="color:red;">*</span>联系人：</label>
    <input type="text" class="easyui-validatebox" data-options="required:true,validType:['maxLength[30]']" name="BsReceipt[contact]" maxlength="30" value="<?=$editData['contact']?>">
</div>
<div class="mb-10">
    <label><span style="color:red;">*</span>联系方式：</label>
    <input type="text" class="easyui-validatebox" data-options="required:true,validType:['mobile']" name="BsReceipt[contact_tel]" maxlength="30" value="<?=$editData['contact_tel']?>" placeholder="请输入136xxxxxxxx">
</div>
<div class="mb-10">
    <label><span style="color:red;">*</span>邮箱：</label>
    <input type="text" class="easyui-validatebox" data-options="required:true,validType:['maxLength[100]','email']" name="BsReceipt[contact_email]" maxlength="100" value="<?=$editData['contact_email']?>" placeholder="请输入xxx@xx.com">
</div>
<div class="mb-10">
    <label>状态：</label>
    <select name="BsReceipt[rcp_status]">
        <option value="Y" <?=$editData['rcp_status']=='Y'?"selected":""?>>启用</option>
        <option value="N" <?=$editData['rcp_status']=='N'?"selected":""?>>禁用</option>
    </select>
</div>
<div style="margin-bottom:3px;">
    <label><span style="color:red;">*</span>详细地址：</label>
    <select style="width:110px;" class="easyui-validatebox district_change" data-options="required:true,tipPosition:'bottom'">
        <option value="">-请选择-</option>
        <?php foreach($addData['country'] as $key=>$val){?>
            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][0]?'selected':''?>><?=$val['district_name']?></option>
        <?php }?>
    </select>
    <select style="width:110px;" class="easyui-validatebox district_change" data-options="required:true,tipPosition:'bottom'">
        <option value="">-请选择-</option>
        <?php foreach($editData['edit_addr']['districtName'][1] as $key=>$val){?>
            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][1]?'selected':''?>><?=$val['district_name']?></option>
        <?php }?>
    </select>
    <select style="width:110px;" class="easyui-validatebox district_change" data-options="required:true,tipPosition:'bottom'">
        <option value="">-请选择-</option>
        <?php foreach($editData['edit_addr']['districtName'][2] as $key=>$val){?>
            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][2]?'selected':''?>><?=$val['district_name']?></option>
        <?php }?>
    </select>
    <select style="width:110px;" name="BsReceipt[district_id]" class="easyui-validatebox district_change" data-options="required:true,tipPosition:'bottom'">
        <option value="">-请选择-</option>
        <?php foreach($editData['edit_addr']['districtName'][3] as $key=>$val){?>
            <option value="<?=$val['district_id']?>" <?=$val['district_id']==$editData['edit_addr']['districtId'][3]?'selected':''?>><?=$val['district_name']?></option>
        <?php }?>
    </select>
</div>
<div class="mb-10">
    <input type="text" style="width:451px;margin-left:113px;" name="BsReceipt[addr]" class="easyui-validatebox" data-options="required:true,validType:'maxLength[100]',tipPosition:'bottom'" value="<?=$editData['addr']?>" placeholder="填写详细地址" maxlength="100">
</div>
<div class="mb-10">
    <label style="vertical-align:top;">备注：</label>
    <textarea style="width:451px;height:70px;" class="easyui-validatebox" data-options="validType:'maxLength[200]',tipPosition:'bottom'" maxlength="200" name="BsReceipt[remarks]" placeholder="最多输入200个字"><?=$editData['remarks']?></textarea>
</div>
<div style="text-align:center;">
    <button class="button-blue" type="submit">确定</button>
    <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end();?>
<script>
    $(function(){
        //ajax提交表单
        ajaxSubmitForm("form","",
            function(data){
                if(data.flag==1){
                    parent.$(".fancybox-overlay").hide();
                    parent.$(".fancybox-wrap").hide();
                    parent.layer.alert(data.msg,{
                        icon:1,
                        end:function(){
                            <?php if(empty($editData)){?>
                            parent.$("#table1").datagrid('load');
                            <?php }else{?>
                            parent.$("#table1").datagrid('reload');
                            <?php }?>
                            parent.$.fancybox.close();
                        }
                    });
                }
                if(data.flag==0){
                    parent.layer.alert(data.msg,{icon:2});
                    $("button[type='submit']").prop("disabled",false);
                }
            }
        );

        //地址联动
        $(".district_change:not(:last)").change(function(){
            var $currSelect=$(this);
            $currSelect.nextAll("select").html("<option value=''>-请选择-</option>");
            if($currSelect.val()===''){
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-district'])?>",
                data:{"id":$currSelect.val()},
                dataType:"json",
                success:function(data){
                    $.each(data,function(i,n){
                        $currSelect.next().append("<option value='"+n.district_id+"'>"+n.district_name+"</option>");
                    })
                }
            });
        });
    })
</script>