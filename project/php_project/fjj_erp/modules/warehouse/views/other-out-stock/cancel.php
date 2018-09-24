<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/19
 * Time: 下午 03:29
 */
use yii\widgets\ActiveForm;
?>
<h3 class="head-first">系统提示</h3>
<div class="content">
        <?php ActiveForm::begin() ?>
        <div class="content">
            <label for="">请输入取消原因</label>
            <textarea id="reason" name="reason"  style="width:300px;height:100px;outline: none;"></textarea>
            <div class="mt-20 mb-20 text-center">
                <button class="button-blue">确定</button>
                <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
            </div>
        </div>
        <?php ActiveForm::end() ?>
</div>
<script>
    $(function(){
        $("form").ajaxForm(function(res){
            var data=JSON.parse(res);
            var row=parent.$("#data").datagrid("getSelected");
            var index=parent.$("#data").datagrid("getRowIndex",row);
            parent.$.fancybox.close();
            if(data.flag==1){
                row.remarks=$("#reason").val();
                row.o_whstatus="<a class='tip' style='color:#cd0a0a'>已取消</a>";
                parent.$("#data").datagrid("refreshRow",index);
                parent.layer.alert(data.msg,{icon:1});
            }
            parent.layer.alert(data.msg,{icon:2});
        });
    });
</script>