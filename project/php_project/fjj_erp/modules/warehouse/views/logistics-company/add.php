<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/10
 * Time: 上午 11:17
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '新增物流公司信息';
$this->params['homeLike'] = ['label' => '倉儲物流管理', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '倉儲物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '新增物流公司信息', 'url' => ""];
?>
<style>
    .search-div {
        width: 990px;
    }

    .back-100 {
        width: 100%;
        height: 30px;
        background: #438EB8;
    }

    .table-heads {
        /*width: 990px;*/
        height: 30px;
    }

    .table-heads p {
        font-size: 16px;
        float: left;
        color: #fff;
        font-weight: bold;
        text-indent: 1em;
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
            'id' => 'add-form',
            'enableAjaxValidation' => true,
        ]
    ); ?>
    <div class="table-heads back-100 mt-10">
        <p class="head mt-5">新增物流公司信息</p>
    </div>
    <div style="font-size: 14px;">
        <div class="mb-20 mt-10">
            <label class="width-100 ml-50"><span class="red">*</span>公司中文名</label>
            <input type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" name="HrStaff[staff_code]">
            <input id="log_cmp_name" class="width-200 easyui-validatebox" data-options="required:true" data-attr="log_cmp_name" value="" name="BsLogCmp[log_cmp_name]"/>
            <label class="width-100">公司英文名</label>
            <input id="log_cmp_EN" type="text" value="" class="width-200" data-attr="log_cmp_EN" name="BsLogCmp[log_cmp_EN]"/>
            <label class="width-100"><span class="red">*</span>承运代码</label>
            <input id="log_code" type="text" value="" class="width-200 easyui-validatebox" data-options="required:true"
                   data-attr="log_code" name="BsLogCmp[log_code]"/>
            <div class="help-block"></div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50"><span class="red">*</span>运输方式</label>
            <div class="display-style width-200 mt-10" >
                <div style="font-size: 14px;">
                    <?php foreach ($companyname as $key=>$val){?>
                        <input type="radio" name="BsLogCmp[log_mode]" value="<?php echo $val['para_code']?>" ><?php echo $val['para_name']?>
                    <?php }?>
                </div>
            </div>
            <label class="width-100">公司地址</label>
            <input id="log_addr" type="text" value="" class="width-510 " data-attr="log_addr" name="BsLogCmp[log_addr]"/>
            <div class="help-block">
            </div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">负责人</label>
            <input id="log_char" type="text" value="" class="width-200 " data-attr="log_char" name="BsLogCmp[log_char]"/>
            <label class="width-100">电话</label>
            <input id="log_char_phone" type="text" value="" class="width-200 " data-attr="log_char_phone" name="BsLogCmp[log_char_phone]"/>
            <label class="width-100">e-mail</label>
            <input id="log_char_mail" type="text" value="" class="width-200 " data-attr="log_char_mail" name="BsLogCmp[log_char_mail]"/>
            <div class="help-block">
            </div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50"><span class="red">*</span>联系人</label>
            <input id="log_cont" type="text" value="" class="width-200 easyui-validatebox" data-options="required:true"
                   data-attr="log_cont" name="BsLogCmp[log_cont]"/>
            <label class="width-100"><span class="red">*</span>电话</label>
            <input id="log_cont_pho" type="text" value="" class="width-200 easyui-validatebox" data-options="required:true"
                   data-attr="log_cont_pho" name="BsLogCmp[log_cont_pho]"/>
            <label class="width-100">e-mail</label>
            <input id="log_cont_mail" type="text" value="" class="width-200 " data-attr="log_cont_mail" name="BsLogCmp[log_cont_mail]"/>
            <div class="help-block">
            </div>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50"><span class="red">*</span>经营范围</label>
            <input id="log_scope" type="text" value="" class="width-510 easyui-validatebox" data-options="required:true"
                   data-attr="log_scope" name="BsLogCmp[log_scope]"/>
            <label class="width-100" for="BsLogCmp-log_type"><span class="red">*</span>公司类型</label>
            <select id="BsLogCmp-log_type" class="width-200 easyui-validatebox" data-options="required:true" data-attr="log_type" name="BsLogCmp[log_type]">
                <option value="">请选择...</option>
                <?php foreach ($companytype as $key => $val) { ?>
                    <option
                        value="<?= $val['para_code'] ?>" ><?= $val['para_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mt-20">
            <label class="width-100 ml-50">公司网址</label>
            <input id="log_url" type="text" value="" class="width-200" data-attr="log_url" name="BsLogCmp[log_url]"/>
            <label class="width-100">备注</label>
            <input id="remarks" type="text" value="" class="width-510" data-attr="remarks" name="BsLogCmp[remarks]"/>
        </div>
    </div>
    <div class="space-40 ml-50"></div>
    <button class="button-blue-big ml-320" type="submit" id="sub">提交</button>
    <button class="button-white-big ml-20" type="button" onclick="window.location.href = '<?=Url::to(["index"])?>';">返回</button>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(document).ready(function() {
//        var log_mode="";
        ajaxSubmitForm("#add-form");
//        ajaxSubmitForm("#add-form",function (){
//            $("input[type='radio']").each(function () {
//                if($(this).prop("checked"))
//                {
//                    log_mode=$(this).val();
//                }
//            });
//            if(log_mode=="")
//            {
//                layer.alert("请选择运输类型!",{icon:2});
//                return false;
//            }
//            if($("#BsLogCmp-log_type").val()==null||$("#BsLogCmp-log_type").val()=="")
//            {
//                layer.alert("请选择公司类型!",{icon:2});
//                return false;
//            }
//            return true;
//        });
    });
</script>
