<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<h1 class="head-first">商品下架</h1>
<div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-20">
        <label class="label-width label-align">料号：</label>
        <input type="text" class="value-width value-align" name="partno" value="<?=\Yii::$app->request->get('partno')?>" disabled="true">
    </div>
    <div class="mb-20" style="float: left;width: 20%;">
        <label class="label-width label-align"><span class="red">*</span>下架原因：</label>
    </div>
    <div class="mb-10 reason-list" style="float: left;width: 80%">
        <?php foreach($data as $reason){ ?>
            <label><input type="radio" class="reason-item" name="BsPartno[rs_id]" value="<?=$reason['rs_id']?>"><?=$reason['rs_mark']?></label>
        <?php } ?>
        <label><input type="radio"  class="reason-item">其他原因</label>
        <input class="width-200 _disply" type="text" name="BsPartno[other_reason]" value="" style="display: none">
    </div>
    <div class="mb-10">
        <label class="label-width label-align">附件：</label>
        <input id="off_file" type="file" name="file">
    </div>
    <div class="text-center">
        <button class="button-blue-big" type="submit" >保存</button>&nbsp;
        <button class="button-white-big ml-20 close" type="button">取消</button>
    </div>
    <?php ActiveForm::end()?>
</div>
<style type="text/css">
    .reason-list label{
        display: block;
        text-align: left;
    }
    .reason-list input{
        margin-right: 0.5em;
    }
    .reason-list label:after{
        content: "";
        height: 0px;
        line-height: 0px;
    }
    .label-width{
        width:100px;
    }
    .value-width{
        width:200px;
    }
</style>
<script>
    $(function(){
        ajaxSubmitForm($("#add-form"),function(){
            if($(".reason-item:checked").size()==0){
                layer.alert("请选择下架原因",{icon:2});
                return false;
            }
            return true;
        },function(data){
            var id =data.l_prt_pkid;
            var url = "<?=Url::to(['index'], true)?>";
            var staff="<?=\Yii::$app->user->identity->staff->staff_id?>";
            $.fancybox.close();
            $.ajax({
                type:"get",
                url:"<?=\yii\helpers\Url::to(['get-bus-type','code'=>'pdtdowmsel'])?>",
                success:function(data){
                    var type=data;
                    parent.$.fancybox({
                        href: "<?=Url::to(['reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url+"&staff="+staff,
                        type: "iframe",
                        padding: 0,
                        autoSize: false,
                        width: 750,
                        height: 480
                    });
                }
            });
        });
    });

    $(".close").click(function () {
        parent.$.fancybox.close();
    });

    $(".reason-item").click(function(){
        $(".reason-item").prop("checked",false);
        $(this).prop("checked",true);
        $("._disply").css("display","none");
        $("._disply").validatebox({
            required:false
        });
    });

    $(".reason-item").last().click(function(){
        $(".reason-item").prop("checked",false);
        $(this).prop("checked",true);
        $("._disply").css("display","block");
        $("._disply").validatebox({
            required:true
        });
    });
</script>
