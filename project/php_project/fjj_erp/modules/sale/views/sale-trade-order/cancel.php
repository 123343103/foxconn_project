<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    .width-450 {
        width: 450px;
    }
</style>
<h3 class="head-first" style="margin-bottom: 0">系统提示</h3>
<div class="content">
    <form id="cancel">
        <label for=""><span class="red">*</span>请输入取消原因</label><br/>
        <textarea id="reason" name="reason"
                  class="easyui-validatebox width-450"
                  data-options="required:'true',tipPosition:'top',validType:'maxLength[200]'" maxlength="200"
                  style="height:140px;outline: none;margin-bottom: 15px"></textarea>
        <div class="mt-20 text-center">
            <button class="button-blue sub" type="button">确定</button>
            <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </form>
</div>
<script>
    $(function () {
        $('.sub').click(function () {
            if (!$('#cancel').form('validate')) {
                return false;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"cancelId": parent.cancelId, "reason": $('#reason').val()},
                url: "<?=Url::to(['cancel'])?>",
                success: function (data) {
                    if (data.status == 1) {
                        parent.layer.alert(data.msg, {
                            icon: 1, end: function () {
                                parent.$.fancybox.close();
//                            parent.$('#data').datagrid('reload');
                                parent.window.location.reload();
                            }
                        });
                    } else {
                        parent.layer.alert(data.msg, {
                            icon: 1, end: function () {
                                parent.$.fancybox.close();
                                return false;
                            }
                        });
                    }
                },
                error: function (data) {
                    console.log('data2', data);
                }
            });
        })
    });
</script>