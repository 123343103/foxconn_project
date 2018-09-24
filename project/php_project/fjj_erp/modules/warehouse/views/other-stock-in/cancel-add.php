<?php
/**
 * User: F1677929
 * Date: 2017/12/19
 */
use yii\widgets\ActiveForm;
?>
<div class="head-first">系统提示</div>
<?php ActiveForm::begin()?>
<div style="margin:0 15px 10px;">
    <div class="mb-10"><span style="color:red;">*</span>请输入取消原因：</div>
    <textarea style="width:400px;height:150px;" class="easyui-validatebox" data-options="required:true,validType:'maxLength[100]',tipPosition:'top'" placeholder="最多输入200个字" name="InWhpdt[can_reason]"></textarea>
</div>
<div style="text-align:center;">
    <button type="submit" class="button-blue">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        ajaxSubmitForm("form","",
            function(data){
                if(data.flag==1){
                    parent.$(".fancybox-overlay").hide();
                    parent.$(".fancybox-wrap").hide();
                    parent.layer.alert(data.msg,{
                        icon:1,
                        end:function(){
                            parent.$("#table1").datagrid('reload');
                        }
                    });
                }
                if(data.flag==0){
                    parent.layer.alert(data.msg,{icon:2});
                    $("button[type='submit']").prop("disabled",false);
                }
            }
        );
    })
</script>