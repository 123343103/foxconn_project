<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/12/28
 * Time: 上午 10:40
 */
use yii\widgets\ActiveForm;
?>
<style>
    .red-border {
        border: 1px solid #ffa8a8;
    }
</style>
<h3 class="head-first">系统提示</h3>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="content">
        <input type="hidden" value="<?=$id?>" id="whpdtid">
        <label for=""><span style="color:red;" title="*">*</span>请输入取消原因</label>
        <textarea id="reason" name="reason" class="easyui-validatebox" data-options="required:'true'"  style="width:300px;height:100px;outline: none;"></textarea>
        <div style="height: 20px;"></div>
        <div class="text-center">
            <button class="button-blue" id="save">确定</button>
            <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function(){
        $("#save").click(function () {
            var reasons=$("#reason").val();
           var id= $("#whpdtid").val();
            if(reasons==""||reasons==null)
            {
                $("#reason").addClass('red-border');
                return false;
            }
            else {
                $("form").submit();
                $("#add-form").attr('action', '<?=\yii\helpers\Url::to(['cancel'])?>?id='+id);
            }
        });
        ajaxSubmitForm($("#add-form"), '', function (data) {
            parent.$.fancybox.close();
            if (data.flag == 1) {
//                row.remarks=$("#reason").val();
                parent.$("#data").datagrid('reload');
                parent.$("#again-shelves").show();
                parent.$("#generating-order").hide();
                parent.$("#cancel").hide();
                parent.$("#cost-apply").hide();
                parent.$("#view-order").hide();
                parent.$("#since").hide();
//                row.o_whstatus="<a class='tip' style='color:#cd0a0a'>已取消</a>";
//                parent.$("#data").datagrid("refreshRow",index);
                parent.layer.alert(data.msg, {icon: 1});
            }
        });
//        $("form").ajaxForm(function(res){
//            var data=JSON.parse(res);
//            else {
////            var row=parent.$("#data").datagrid("getSelected");
////            var index=parent.$("#data").datagrid("getRowIndex",row);
//                parent.$.fancybox.close();
//                if (data.flag == 1) {
////                row.remarks=$("#reason").val();
//                    parent.$("#data").datagrid('reload');
//                    parent.$("#again-shelves").show();
//                    parent.$("#generating-order").hide();
//                    parent.$("#cancel").hide();
//                    parent.$("#cost-apply").hide();
//                    parent.$("#view-order").hide();
//                    parent.$("#since").hide();
////                row.o_whstatus="<a class='tip' style='color:#cd0a0a'>已取消</a>";
////                parent.$("#data").datagrid("refreshRow",index);
//                    parent.layer.alert(data.msg, {icon: 1});
//                }
//                parent.layer.alert(data.msg, {icon: 2});
//            }
//        });
    });
</script>