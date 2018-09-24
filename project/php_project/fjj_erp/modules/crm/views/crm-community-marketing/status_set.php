<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/20
 * Time: 下午 02:32
 */
use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
JqueryUIAsset::register($this);
?>
    <?php ActiveForm::begin([
        "id"=>"status-form"
    ]); ?>
    <h3 class="head-first">状态设置</h3>
    <div class="mb-20">
        <label class="width-100" for="">操作人</label>
        <input type="text" class="width-200" value="<?=\Yii::$app->user->identity->staff->staff_name?>" disabled>
    </div>
    <div class="mb-20">
        <label class="width-100" for="">操作日期</label>
        <input type="text" class="width-200 select-date" value="<?=date('Y-m-d')?>" disabled>
    </div>
    <div class="mb-20">
        <label class="width-100" for="">状态</label>
        <?=Html::dropDownList("CrmCommunity[commu_status]",$model["commu_status"],$options["status"],["id"=>"commu_status","class"=>"width-200"])?>
    </div>
    <div class="mb-20 text-center">
        <button type="submit" class="button-blue">确定</button>
        <button type="button" onclick="parent.$.fancybox.close()" class="button-white ml-10">取消</button>
    </div>
    <?php ActiveForm::end(); ?>
<script>
    $(function(){
        $("#status-form").on("beforeSubmit", function () {
            if (!$(this).form('validate')) {
                return false;
            }
        });
        $("#status-form").ajaxForm(function(res){
            parent.$.fancybox.close();
            res=JSON.parse(res);
            if(res.flag==0){
                parent.layer.alert(res.msg,{icon:2});
                return ;
            }
            parent.layer.alert(res.msg,{icon:1});
            var row=parent.$("#data").datagrid("getSelected");
            var index=parent.$("#data").datagrid("getRowIndex",row);
            row.commu_status=$("#commu_status option:selected").text();
            parent.$("#data").datagrid("refreshRow",index);
        });
    });
</script>
