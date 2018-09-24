<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
?>
<h1 class="head-first">抓取数据</h1>
<?php ActiveForm::begin()?>
<div style="margin-bottom:20px;">
    <label style="width:120px;">供应商名称：</label>
    <input type="text" style="width:200px;" name="sup_name" placeholder="关键词/全称">
</div>
<div style="text-align:center;">
    <button type="submit" class="button-blue">抓取数据</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        ajaxSubmitForm("form",'',function(data){
            if(data.flag==1){
                parent.layer.alert(data.msg,{icon:1,end:function(){
                    parent.$("#supplier_table").datagrid("load");
                    parent.$.fancybox.close();
                }});
            }else{
                parent.layer.alert(data.msg,{icon:2});
            }
        });
    })
</script>