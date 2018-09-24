<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/24
 * Time: 11:37
 * 商机商品
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
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
        'id' => 'cust-oddsitem',
        'method' => 'post'
    ]) ?>
    <div>
        <?php if ($id != null && $oddsId == null) { ?>
            <h2 class="head-first">新增商机商品</h2>
        <?php } ?>
        <?php if ($id != null && $oddsId != null) { ?>
            <h2 class="head-first">修改商机商品</h2>
        <?php } ?>
        <div class="ml-100 mt-40">

            <div class="mb-10">
                <div class="inline-block">
                    <input type="hidden" name="cust_id" value="<?= $id ?>">
                    <label class="label-width label-align"><span class="red">*</span>商品类别：</label>
                    <select class="value-width easyui-validatebox" name="CrmCustOddsitem[category_id]" data-options="required:true">
                        <option value="">请选择...</option>
                        <?php foreach ($firmCategory as $key => $val) { ?>
                            <option value="<?= $val['catg_id'] ?>"<?= $result['category_id']==$val['catg_id']?"selected":null; ?>><?= $val['catg_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">品名：</label>
                    <input type="text" class="value-width value-align" name="CrmCustOddsitem[odds_sname]"
                           value="<?= $result['odds_sname'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">规格型号：</label>
                    <input type="text" class="value-width value-align" name="CrmCustOddsitem[odds_model]"
                           value="<?= $result['odds_model'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width label-align">品牌：</label>
                    <input type="text" class="value-width value-align" name="CrmCustOddsitem[brand]"
                           value="<?= $result['brand'] ?>" maxlength="20">
                </div>
            </div>

            <div class="mb-10">
                <div class="inline-block">
                    <label class="label-width vertical-top label-align">备注：</label>
                    <textarea class="value-width value-align" name="CrmCustOddsitem[remark]" cols="3" maxlength="200" placeholder="最多输入200个字"><?= $result['remark'] ?></textarea>
                </div>
            </div>

        </div>
        <div class="text-center">
            <?php if($id != null && $oddsId == null){ ?>
                <div class="mb-10 text-center">
                    <button class="button-blue-big" type="submit" id="cust-oddsitem-add">确定</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
            <?php if($id != null && $oddsId != null){ ?>
                <div class="mb-10 text-center">
                    <button class="button-blue-big" type="submit" id="cust-oddsitem-edit">修改</button>
                    <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $("#cust-oddsitem-add").one("click", function () {
        $("#cust-oddsitem").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-cust-oddsitem','id'=>$id]) ?>');
        return ajaxSubmitForm($("#cust-oddsitem"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#businessProduct").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $("#cust-oddsitem-edit").one("click", function () {
        $("#cust-oddsitem").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-cust-oddsitem','id'=>$oddsId]) ?>');
        return ajaxSubmitForm($("#cust-oddsitem"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#businessProduct").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
</script>