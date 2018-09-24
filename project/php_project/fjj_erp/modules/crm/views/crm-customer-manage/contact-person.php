<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/22
 * Time: 15:58
 */
use \yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .label-width{
        width:100px;
    }
    .value-width{
        width: 200px;
    }
    select.disabled,input.disabled,textarea.disabled{
        background:#ccc;
    }
</style>
<!--增加联系人-->
<div>
    <?php $form = ActiveForm::begin([
        'id'=>'contact-info',
        'method' => 'post'
    ]) ?>
    <?php if ($id != null && $ccperId == null) { ?>
        <h2 class="head-first">添加联系人</h2>
    <?php }else if($id != null && $ccperId != null){ ?>
        <h2 class="head-first">修改联系人</h2>
    <?php } ?>

    <div>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align"><span class="red">*</span>姓名：</label>
                <input class="value-width value-align easyui-validatebox" data-options="required:true" type="text" name="CrmCustomerPersion[ccper_name]" value="<?= $result['ccper_name'] ?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align"><span class="red">*</span>性别：</label>
                <select name="CrmCustomerPersion[ccper_sex]" id="" class="value-width value-align easyui-validatebox" data-options="required:true">
                    <option value="">请选择...</option>
                    <option value="1" <?= $result['ccper_sex'] ==1?"selected='selected'":null; ?>>--男--</option>
                    <option value="0" <?= $result['ccper_sex'] ==0?"selected='selected'":null; ?>>--女--</option>
                </select>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align"><span class="red">*</span>职务：</label>
                <input class="value-width value-align easyui-validatebox" data-options="required:true" type="text" name="CrmCustomerPersion[ccper_post]" value="<?= $result['ccper_post'] ?>"  maxlength="20">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align">部门：</label>
                <input class="value-width value-align ccper_deparment" type="text" name="CrmCustomerPersion[ccper_deparment]" value="<?= $result['ccper_deparment'] ?>" maxlength="20">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align">生日：</label>
                <input class="value-width value-align select-date " type="text" name="CrmCustomerPersion[ccper_birthday]" value="<?= $result['ccper_birthday'] ?>" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',maxDate:'%y-%M-%d' })">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">是否主要联系人：</label>
                <?=\yii\helpers\Html::dropDownList("CrmCustomerPersion[ccper_ismain]",$result['ccper_ismain']==1?1:0,["1"=>"是","0"=>"否"],["class"=>"value-width value-align"]);?>
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align">座机：</label>
                <input class="value-width value-align easyui-validatebox" data-options="validType:'telphone'" type="text" name="CrmCustomerPersion[ccper_tel]" value="<?= $result['ccper_tel'] ?>" placeholder="请输入:xxxx-xxxxxxxx" maxlength="20">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align"><span class="red">*</span>手机：</label>
                <input class="value-width value-align easyui-validatebox" data-options="required:true,validType:'mobile'" type="text" name="CrmCustomerPersion[ccper_mobile]" value="<?= $result['ccper_mobile'] ?>" placeholder="请输入:136xxxxxxxx" maxlength="20">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align">传真：</label>
                <input class="value-width value-align easyui-validatebox" data-options="validType:'telphone'" type="text" name="CrmCustomerPersion[ccper_fax]" value="<?= $result['ccper_fax'] ?>" placeholder="请输入:xxxx-xxxxxxxx" maxlength="20">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align">邮箱：</label>
                <input class="value-width value-align easyui-validatebox" data-options="validType:'email'" type="text" name="CrmCustomerPersion[ccper_mail]" value="<?= $result['ccper_mail'] ?>" placeholder="请输入:xxx@xxx.com" maxlength="20">
            </div>
        </div>

        <div class="mb-10">
            <div class="inline-block">
                <label for="" class="label-width label-align">QQ：</label>
                <input class="value-width value-align" type="text" name="CrmCustomerPersion[ccper_qq]" value="<?= $result['ccper_qq'] ?>" maxlength="20">
            </div>
            <div class="inline-block">
                <label for="" class="label-width label-align">微信：</label>
                <input class="value-width value-align" type="text" name="CrmCustomerPersion[ccper_wechat]" value="<?= $result['ccper_wechat']; ?>" maxlength="20">
            </div>
        </div>

    </div>
    <div class="text-center mt-40">
        <?php if($id != null && $ccperId == null){ ?>
            <button type="submit" class="button-blue-big" id="contact-add">确认</button>
            <button type="button" class="button-white-big"  onclick="close_select()">取消</button>
        <?php }else if($id != null && $ccperId != null){ ?>
            <?php if($type == '1'){ ?>
<!--                <button type="submit" class="button-blue-big" id="contact-edit">确定</button>-->
                <button type="button" class="button-white-big"  onclick="close_select()">取消</button>
            <?php } ?>
            <?php if($type == '2'){ ?>
            <button type="submit" class="button-blue-big" id="contact-edit">修改</button>
            <button type="button" class="button-white-big"  onclick="close_select()">取消</button>
            <?php } ?>
        <?php } ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $("#contact-add").one("click", function () {
//            $('.validateboxs').validatebox('remove');
        $("#contact-info").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/contact-create','id'=>$id]) ?>');
        return ajaxSubmitForm($("#contact-info"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#contact").datagrid("reload");
            parent.$.fancybox.close();
        });
    });
    $("#contact-edit-ensure").click(function(){
        $("select,input,textarea").prop("disabled",false);
        $("#contact-edit").show();
        $(this).hide();
    });
    $("#contact-edit").one("click", function () {
//            $('.validateboxs').validatebox('remove');
        $("#contact-info").attr('action','<?= \yii\helpers\Url::to(['/crm/crm-customer-manage/contact-update','id'=>$ccperId]) ?>');
        return ajaxSubmitForm($("#contact-info"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$("#contact").datagrid("reload");
            parent.$.fancybox.close();
        });
    });

    $(function(){
        if("<?=$type?>"==1){
            $("select,input,textarea").prop("disabled",true).addClass("disabled");
        }
    });
</script>
