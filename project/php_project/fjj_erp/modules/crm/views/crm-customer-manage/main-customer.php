<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/24
 * Time: 9:05
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    .label-width{
        width:100px;
    }
    .value-width{
        width:200px;
    }
</style>
<div>
    <?php $form = ActiveForm::begin([
        'id' => 'main-customer',
        'method' => 'post'
    ]) ?>
    <div>
        <?php if ($id != null && $ccId == null) { ?>
            <h2 class="head-first">添加主要客户</h2>
        <?php } ?>
        <?php if ($id != null && $ccId != null) { ?>
            <h2 class="head-first">修改主要客户</h2>
        <?php } ?>
        <div>
            <div class="mb-10">
                <div class="inline-block">
                    <input type="hidden" name="cust_id" value="<?= $id ?>">
                    <label for="" class="label-width label-align"><span class="red">*</span>客户名称：</label>
                    <input type="text" class="value-width value-align easyui-validatebox" name="CrmCustCustomer[cc_customer_name]" value="<?= $result['cc_customer_name'] ?>" maxlength="20" data-options="required:true">
                </div>
                <div class="inline-block">
                    <label for="" class="label-width label-align">经营类型：</label>
                    <select class="value-width value-align" name="CrmCustCustomer[cc_customer_type]">
                        <option value="">请选择...</option>
                        <?php foreach ($downList['managementType'] as $key => $val) { ?>
                            <option value="<?= $val['bsp_id'] ?>" <?= $result['cc_customer_type'] == $val['bsp_id'] ? "selected" : null; ?>><?= $val['bsp_svalue'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <input type="hidden" name="cust_id" value="<?= $id ?>">
                    <label for="" class="label-width label-align">公司电话：</label>
                    <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'telphone'" name="CrmCustCustomer[cc_customer_tel]"
                           value="<?= $result['cc_customer_tel'] ?>" maxlength="20" placeholder="请输入：xxxx-xxxxxxxx">
                </div>
                <div class="inline-block">
                    <label for="" class="label-width label-align">占营收比率：</label>
                    <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'two_percent'" name="CrmCustCustomer[cc_customer_ratio]"
                           value="<?= $result['cc_customer_ratio'] ?>"  maxlength="20">&nbsp;%
                </div>
            </div>


            <div class="mb-10">
                <div class="inline-block">
                    <label for="" class="label-width label-align">负责人：</label>
                    <input type="text" class="value-width value-align" name="CrmCustCustomer[cc_customer_person]"
                           value="<?= $result['cc_customer_person'] ?>" maxlength="20">
                </div>
                <div class="inline-block">
                    <label for="" class="label-width label-align">联系方式：</label>
                    <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'mobile'" name="CrmCustCustomer[cc_customer_mobile]" value="<?= $result['cc_customer_mobile'] ?>" maxlength="20" placeholder="请输入：136xxxxxxxx">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label for="" class="label-width label-align vertical-top">备注：</label>
                    <textarea style="width:506px;" name="CrmCustCustomer[cc_customer_remark]" id="" cols="3"  maxlength="200" placeholder="最多输入200个字"><?= $result['cc_customer_remark'] ?></textarea>
                </div>
            </div>

        </div>
        <div class="text-center">
            <?php if($id != null && $ccId == null){ ?>
                <div class="mb-20 text-center">
                    <button class="button-blue-big" type="submit" id="main-customer-add">确定</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
            <?php if($id != null && $ccId != null){ ?>
                <div class="mb-20 text-center">
                    <button class="button-blue-big" type="submit" id="main-customer-edit">修改</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $("#main-customer-add").one("click", function () {
        $("#main-customer").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-main-customer','id'=>$id]) ?>');
        return ajaxSubmitForm($("#main-customer"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#mainCustomer").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $("#main-customer-edit").one("click", function () {
        $("#main-customer").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-main-customer','id'=>$ccId]) ?>');
        return ajaxSubmitForm($("#main-customer"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#mainCustomer").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
</script>