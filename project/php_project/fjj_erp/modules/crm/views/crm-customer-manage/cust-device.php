<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/23
 * Time: 8:55
 */
use \yii\widgets\ActiveForm;
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
        'id' => 'cust-device',
//        'action'=>['/crm/crm-customer-manage/create-visit','id'=>$id]
    ]) ?>
    <?php if ($id != null && $custdId == null) { ?>
        <h2 class="head-first">添加设备</h2>
    <?php }else if($id != null && $custdId != null){ ?>
        <h2 class="head-first">修改设备</h2>
    <?php } ?>

    <div class="mb-10">
        <input type="hidden" name="cust_id" value="<?= $id ?>">
        <div class="inline-block">
            <label class="label-width label-align"><span class="red">*</span>设备类型：</label>
            <input type="text" class="value-width value-align type easyui-validatebox" name="CrmCustDevice[type]" value="<?= $result['type'] ?>" data-options="required:true,tipPosition:'bottom'" maxlength="20"/>
        </div>
    </div>

<!--    <div class="mt-20 text-center">-->
<!--        <label class="width-60">设备数量</label>-->
<!--        <input type="text" class="nqty easyui-validatebox" data-options="validType:['number','maxLength[20]']" name="CrmCustDevice[nqty]" value="--><?//= $result['nqty'] ?><!--" maxlength="20" />-->
<!--    </div>-->

    <div class="mb-10">
        <input type="hidden" name="cust_id" value="<?= $id ?>">
        <div class="inline-block">
            <label class="label-width label-align">设备品牌：</label>
            <input type="text" class="value-width value-align brand" name="CrmCustDevice[brand]" value="<?= $result['brand'] ?>" maxlength="20"/>
        </div>
    </div>

    <?php if($id != null && $custdId == null){ ?>
        <div class="mb-20 text-center">
            <button class="button-blue-big" type="submit" id="cust-device-add">确定</button>
            <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
        </div>
    <?php } ?>
    <?php if($id != null && $custdId != null){ ?>
        <div class="mb-20 text-center">
            <button class="button-blue-big" type="submit" id="cust-device-edit">确定</button>
            <button class="button-white-big ml-20" onclick="close_select()" type="button">取消</button>
        </div>
    <?php } ?>
    <?php ActiveForm::end() ?>
</div>
<script>
    $("#cust-device-add").one("click", function () {
        $("#cust-device").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/create-device','id'=>$id]) ?>');
        return ajaxSubmitForm($("#cust-device"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#custDevice").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $("#cust-device-edit").one("click", function () {
        $("#cust-device").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/update-device','id'=>$custdId]) ?>');
        return ajaxSubmitForm($("#cust-device"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#custDevice").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
</script>
