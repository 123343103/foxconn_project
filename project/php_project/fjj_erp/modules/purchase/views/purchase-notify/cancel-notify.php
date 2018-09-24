<?php
use yii\widgets\ActiveForm;

?>
<h3 class="head-first">系统提示</h3>
<div class="content">
    <?php ActiveForm::begin([
        "id"=>"cancel-form"
    ]) ?>
    <div class="content">
        <label for="">请输入取消原因</label>
        <textarea id="reason" name="reason" class="easyui-validatebox" data-options="validType:'length[0,80]'" style="width:300px;height:100px;outline: none;"></textarea>
        <div class="mt-20 mb-20 text-center">
            <button class="button-blue sub">确定</button>
            <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
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