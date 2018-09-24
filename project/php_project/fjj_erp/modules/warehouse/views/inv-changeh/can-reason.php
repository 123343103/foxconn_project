<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2018/1/10
 * Time: 上午 11:30
 */
use app\assets\JqueryUIAsset;  //ajax引用jQuery样
JqueryUIAsset::register($this);
?>
<div class="content">
    <?php \yii\widgets\ActiveForm::begin(["id"=>"form"]);?>
    <h1 class="head-first">系统提示</h1>
    <div class="tab-content">
        <label style="font-size: 13px"><span style="color: red;">*</span>请输入作废原因:</label>
    </div>
    <div style="float: left;margin-bottom: 20px">
        <textarea maxlength="200"  class="easyui-validatebox" data-options="required:true" id="reason" rows="5" style="width: 459px" placeholder="最多输入200字" name="InvChangeh[chh_remark]"></textarea>
    </div>
    <div style="text-align: center;float: left;width: 100%">
        <button id="submit" class="button-blue-big" type="submit">确定</button>
        <button id="cancel" class="button-white-big" type="button">取消</button>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</div>
<script>
    $(function(){
        $("#cancel").click(function () {
            parent.$.fancybox.close();
        });
        ajaxSubmitForm($("#form"),'',function(data){
            if(data.status == 1){
                parent.layer.alert(data.msg,{icon:1,end: function () {
                    parent.$.fancybox.close();
                    parent.window.location.reload();
                }});
            } else {
                parent.layer.alert(data.msg, {
                    icon: 1, end: function () {
                        return false;
                    }
                });
            }
        })
    })
</script>