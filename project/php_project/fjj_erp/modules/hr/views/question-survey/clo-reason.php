<?php
/**
 * Created by PhpStorm.
 * User: G0007903
 * Date: 2017/10/31
 */
use app\assets\JqueryUIAsset;  //ajax引用jQuery样
use yii\helpers\Url;
JqueryUIAsset::register($this);
?>
<div class="content">
    <?php \yii\widgets\ActiveForm::begin(["id"=>"form"]);?>
    <h1 class="head-first">系统提示</h1>
    <div class="tab-content">
    <label style="font-size: 13px"><span style="color: red;">*</span>请输入关闭原因:</label>
    </div>
    <div style="float: left;margin-bottom: 20px">
        <textarea class="easyui-validatebox" data-options="required:true" id="reason" rows="10" style="width: 500px" placeholder="最多输入200字" name="BsQstInvst[clo_reason]"></textarea>
    </div>
    <div style="text-align: center;float: left;width: 70%">
        <button id="submit" class="button-blue-big" type="submit">确定</button>
        <button id="cancel" class="button-white-big" type="button">取消</button>
    </div>
    <?php \yii\widgets\ActiveForm::end();?>
</div>
<script>
    $(function(){
//        ajaxSubmitForm("form");
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

