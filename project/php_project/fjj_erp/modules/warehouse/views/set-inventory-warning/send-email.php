<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/6/23
 * Time: 下午 04:58 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);
?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "send-email"]); ?>
<style>
    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
    textarea{
        width:100%;
    }
    .testDiv{
        bottom: 36px;
        resize: none;
        border: 1px solid #ccc;

    }
    .keyword{
        color:red;
    }
</style>
<div class="no-padding width-600 " style="height: 500px">
    <div class="view-plan">
        <h2 class="head-first">发邮件</h2>
        <div class="space-10"></div>
        <?php $form=\yii\widgets\ActiveForm::begin([
            'id'=>'email-form'
        ]);?>
        <div class="mb-20 ml-10 mr-10   ">
            <p class="mt-20 mb-10">已选择的邮箱</p>
            <textarea name="selectaddress" id="emailbox-has-add-email" style="height: 30px;"  readonly="readonly"><?= $model['staff_email'] ?></textarea>
            <p class="mt-20 mb-10">邮件主题</p>
            <textarea name="emailtheme" id=""  style="height: 30px;" readonly="readonly">库存预警</textarea>
            <p class="mt-20 mb-10">邮件内容</p>
<!--            <div class='testDiv' id="keyword" name="content" >-->
<!--                当现有库存高于上限的内容为：<br>-->
<!--                <span class="keyword">--><?php //if(!empty($numh)){?>
<!--                                    --><?php //foreach ($numh as $key=>$val){ ?>
<!--                                     --><?//=$val["wh_name"]?><!--料号为--><?//=$val["part_no"]?><!----><?//=$val["pdt_model"]?><!--规格为的商品已超上限--><?//=$val["invt_num"]-$val["up_nums"]?><!--;-->
<!--                                    --><?php //}?>
<!--                                --><?php //}?><!--</span>请尽快处理<br>-->
<!--                当现有库存低于下限的内容为：<br>-->
<!--                <span class="keyword">--><?php //if(!empty($numL)){?>
<!--                        --><?php //foreach ($numL as $key=>$val){ ?>
<!--                            --><?//=$val["wh_name"]?><!--料号为--><?//=$val["part_no"]?><!----><?//=$val["pdt_model"]?><!--规格为 的商品已低于下限--><?//=$val["down_nums"]-$val["invt_num"]?><!--;-->
<!--                        --><?php //}?>
<!--                    --><?php //}?><!--</span>请尽快处理-->
<!--            </div>-->
            <textarea name="content" id="" style="height: 100px;text-align: left" readonly="readonly" wrap="physical">当现有库存高于上限的内容为:<?php if(!empty($numh)){?><?php foreach ($numh as $key=>$val){ ?><?= $key+=1 ?>:<?=$val["wh_name"]?>料号为<?=$val["part_no"]?>规格为<?=$val["pdt_model"]?>的商品已超上限<?=$val["invt_num"]-$val["up_nums"]?><?php }?><?php }?>请尽快处理!
当现有库存低于下限的内容为：<?php if(!empty($numL)){?><?php foreach ($numL as $key=>$val){ ?><?= $key+=1 ?>:<?=$val["wh_name"]?>料号为<?=$val["part_no"]?>规格为<?=$val["pdt_model"]?> 的商品已低于下限<?=$val["down_nums"]-$val["invt_num"]?>;<?php }?><?php }?>请尽快处理!
            </textarea>
            <input type="text" value="<?=$numL?>" id="numL"  style="display: none"/>
            <input type="text" value="<?=$numh?>"  id="numh" style="display: none"/>
            <div class="text-center mt-20">

                <button type="submit" class="button-blue send" disabled="disabled">发送</button>
                <button type="button" class="button-white cancel">取消</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    $(function () {
        $(document).ready(function() {
            ajaxSubmitForm($("#email-form"));
        });
        var numL=$("#numL").val();
        var numh=$("#numh").val();
        if(numh.length>0||numL.length>0){
            $(".send").attr("disabled",false);
        }
    });


    $(".cancel").click(function(){
        parent.$.fancybox.close();
    });
</script>
