<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/23
 * Time: 8:55
 */
use \yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<style>
    .label-width{
        width:150px;
    }
    .value-width{
        width:300px;
    }
</style>
<div>
    <?php $form = ActiveForm::begin([
        'id' => 'cust-purchase',
//        'action'=>['/crm/crm-customer-manage/create-visit','id'=>$id]
    ]) ?>
    <?php if ($id != null && $cpurchId == null) { ?>
        <h2 class="head-first">新增采购信息</h2>
    <?php }else if($id != null && $cpurchId != null){ ?>
        <h2 class="head-first">修改采购信息</h2>
    <?php } ?>
    <div>
        <div class="mb-10">
            <div class="inline-block">
                <input type="hidden" name="cust_id" value="<?= $id ?>">
                <label class="label-width"><span class="red">*</span>主要采购商品：</label>
                <input type="text"  name="CrmCustPurchase[itemname]" value="<?= $result['itemname'] ?>" maxlength="20" class="value-width easyui-validatebox" data-options="required:true,validType:'maxLength[20]'"/>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align">采购渠道：</label>
                <input type="text" name="CrmCustPurchase[pruchasetype]" value="<?= $result['pruchasetype'] ?>" maxlength="20" class="value-width easyui-validatebox" data-options="validType:'maxLength[20]'"/>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align">年采购额：</label>
                <span class="value-width">
                    <input type="text" name="CrmCustPurchase[pruchasecost]" value="<?= $result['pruchasecost'] ?>" maxlength="15" class="value-align easyui-validatebox" data-options="validType:'int'" style="width:230px;"/>
                    <?=Html::dropDownList("CrmCustPurchase[pruchaseqty_cur]",$model['member_curr']?$model['member_curr']:100091,array_combine(array_column($downList['tradeCurrency'],"bsp_id"),array_column($downList['tradeCurrency'],"bsp_svalue")),["class"=>"value-align","style"=>'width:66px;'])?>
                </span>
            </div>
        </div>
    </div>
    <div class="space-20 mt-20"></div>
    <?php if($id != null && $cpurchId == null){ ?>
        <div class="mb-20 text-center">
            <button class="button-blue-big" type="submit" id="cust-purchase-add">确定</button>
            <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
        </div>
    <?php } ?>
    <?php if($id != null && $cpurchId != null){ ?>
        <div class="mb-20 text-center">
            <button class="button-blue-big" type="submit" id="cust-purchase-edit">修改</button>
            <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
        </div>
    <?php } ?>
    <?php ActiveForm::end() ?>
</div>
<script>
    $("#cust-purchase-add").one("click", function () {
        $("#cust-purchase").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-cust-purchase','id'=>$id]) ?>');
        return ajaxSubmitForm($("#cust-purchase"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#purchase").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $("#cust-purchase-edit").one("click", function () {
        $("#cust-purchase").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-cust-purchase','id'=>$cpurchId]) ?>');
        return ajaxSubmitForm($("#cust-purchase"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#purchase").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $(document).ready(function(){
        $.extend($.fn.validatebox.defaults.rules, {
            maxLength: {
                validator: function(value, param){
                    return param[0] >= value.length;
                },
                message: '字符长度不超过超过{0}位.'
            }
        });
    })
</script>
