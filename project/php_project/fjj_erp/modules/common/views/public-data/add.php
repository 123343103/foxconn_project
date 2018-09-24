<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\view */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JqueryUIAsset::register($this);
?>
<style>
    .label-width{width: 80px;}
</style>
<?php ActiveForm::begin(['id'=>'name_form'])?>
<h1 class="head-first">新增公共参数名称</h1>
<div class="ml-10">
    <div class="mb-10">
        <label class="label-width label-align">参数名称</label><label>:</label>
        <input type="text" class="width-200" readonly="readonly" name="BsPubdata[bsp_sname]" value="<?=$data['bsp_sname']?>">
    </div>
    <div class="mb-10">
        <label class="label-width label-align">参数代码</label>
        <input type="text" class="width-200" readonly="readonly" name="BsPubdata[bsp_stype]" value="<?=$data['bsp_stype']?>">
    </div>
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>参数值</label>
        <input type="text" class="width-200 easyui-validatebox" data-options="required:true,validType:'length[0,30]'" name="BsPubdata[bsp_svalue]">
    </div>
</div>
<div class="text-center">
    <button type="submit" class="button-blue mr-10">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        //ajax提交表单
        ajaxSubmitForm($("#name_form"),'',function(data){
            if (data.flag == 1) {
                parent.layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        parent.$("#view_data").datagrid('reload');
                        parent.$.fancybox.close();
                    }
                });
            }
            if (data.flag == 0) {
                if((typeof data.msg)=='object'){
                    parent.layer.alert(JSON.stringify(data.msg),{icon:2});
                }else{
                    parent.layer.alert(data.msg,{icon:2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });
    })
</script>