<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/23
 * Time: 16:29
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;


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
        'id' => 'main-product',
        'method' => 'post'
    ]) ?>
    <div>
        <?php if ($id != null && $ccpId == null) { ?>
            <h2 class="head-first">添加主营产品</h2>
        <?php } ?>
        <?php if ($id != null && $ccpId != null) { ?>
            <h2 class="head-first">修改主营产品</h2>
        <?php } ?>
        <div class="ml-100">

            <div class="mb-10">
                <div class="inline-block">
                    <input type="hidden" name="cust_id" value="<?= $id ?>">
                    <label class="label-width label-align"><span class="red">*</span>主要产品：</label>
                    <input type="text" class="value-width value-align easyui-validatebox" data-options="required:true" name="CrmCustProduct[ccp_sname]"
                           value="<?= $result['ccp_sname'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">规格/型号：</label>
                    <input type="text" class="value-width value-align" name="CrmCustProduct[ccp_model]"
                           value="<?= $result['ccp_model'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">年产量：</label>
                    <input type="text" class="value-width value-align"  name="CrmCustProduct[ccp_annual]"
                           value="<?= $result['ccp_annual'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">品牌：</label>
                    <input type="text" class="value-width value-align" name="CrmCustProduct[ccp_brand]"
                           value="<?= $result['ccp_brand'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label style="vertical-align: top" class="label-width label-align">备注：</label>
                    <textarea style="height: 50px;" class="value-width value-align" name="CrmCustProduct[ccp_remark]" placeholder="最多输入200个字" maxlength="200" ><?= $result['ccp_remark'] ?></textarea>
                </div>
            </div>

        </div>
        <div class="text-center">
            <?php if($id != null && $ccpId == null){ ?>
                <div class="mb-20 text-center">
                    <button class="button-blue-big" type="submit" id="main-product-add">确定</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
            <?php if($id != null && $ccpId != null){ ?>
                <div class="mb-20 text-center">
                    <button class="button-blue-big" type="submit" id="main-product-edit">修改</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $("#main-product-add").one("click", function () {
        $("#main-product").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-main-product','id'=>$id]) ?>');
        return ajaxSubmitForm($("#main-product"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#mainProduct").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $("#main-product-edit").one("click", function () {
        $("#main-product").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-main-product','id'=>$ccpId]) ?>');
        return ajaxSubmitForm($("#main-product"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#mainProduct").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
</script>