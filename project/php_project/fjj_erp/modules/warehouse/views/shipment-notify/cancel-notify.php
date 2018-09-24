<?php
use yii\widgets\ActiveForm;

?>
<style>
    .mt-20{
        margin-top: 20px;
    }
</style>
<h3 class="head-first">系统提示</h3>
<div >
    <?php ActiveForm::begin([
        "id"=>"cancel-form"
    ]) ?>
    <div class="content"  style="margin-top: 5px;margin-left: 15px;padding-top: 5px;">
        <label for="" class="vertical-center"><span class="red">*</span>请输入取消原因:</label></br>
        <textarea id="reason" name="ShpNt[cancel_rs]" data-options="required:'true'" class="easyui-validatebox" data-options="validType:'length[0,100]'" style="width:320px;height:150px;outline: none;" maxlength="100"></textarea>
        <div class="mt-20 mb-20 text-center">
            <button id="submit" class="button-blue-big" type="submit">确定</button>
            <button id="cancel" class="button-white-big" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function(){
            ajaxSubmitForm($("#cancel-form"),'',function(data){
            if(data.status == 1){
                parent.layer.alert(data.msg,{icon:1,end: function () {
                    parent.$.fancybox.close();
//                    parent.$('#data').datagrid('reload');
                    parent.window.location.reload();
                }});
            } else {
                parent.layer.alert(data.msg,{icon:1,end: function () {
                    return false;
                }});
            }
        });
    });
</script>