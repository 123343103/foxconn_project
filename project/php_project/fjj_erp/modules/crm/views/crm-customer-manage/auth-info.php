<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\widgets\upload\UploadAsset::register($this);
?>
<style>
    .label-width{
        width:180px;
    }
    .value-width{
        width:300px;
    }
</style>

<h2 class="head-first">认证信息</h2>
<?php $form = ActiveForm::begin([
    'id' => 'add-form',
    'method' => 'post'
]) ?>
<div class="auth">
    <div class="mb-10">
        <input type="hidden" id="ynspp" value="<?= $crmcertf['yn_spp'] ?>">
        <label class="label-width " for="">是否供应商：</label>
        <input type="radio" value="1" id="radyes" name="CrmC[yn_spp]" <?= $crmcertf['yn_spp'] == 1 ? "checked=checked" : null; ?>>

        <span class="vertical-middle">是</span>
        <input type="radio" value="0" id="radno" name="CrmC[yn_spp]" style="margin-left: 50px;" <?= $crmcertf['yn_spp'] == 0 ? "checked=checked" : null; ?>>

        <span class="vertical-middle">否</span>
        <span style="margin-left: 40px; <?= $crmcertf['yn_spp'] == '0' || empty($crmcertf) ?'display:none':'' ?>"  id="custcode">
                <label class="lable-width label-align " for=""><span class="red">*</span>供应商代码：</label>
            <?php if($crmcertf['yn_spp'] == 1){ ?>
                <input class=" value-align easyui-validatebox" id="sppno" data-options="required:true"
                       style="width: 140px;"
                       type="text" name="CrmC[spp_no]"
                       value="<?= $crmcertf['spp_no'] ?>" maxlength="30">
            <?php }else{ ?>
                <input class=" value-align" id="sppno"
                       style="width: 140px;"
                       type="text" name="CrmC[spp_no]"
                       value="<?= $crmcertf['spp_no'] ?>" maxlength="30">
            <?php } ?>
            </span>
    </div>
    <div class="mb-10">
        <input type="hidden" id="crtftype" value="<?= $crmcertf['crtf_type'] ?>">
        <label class="label-width label-align" for="">证件类型：</label>
        <input type="radio" value="0" id="old"
               checked="checked" <?= $crmcertf['crtf_type'] == 0 ? "checked=checked" : null; ?>
               name="CrmC[crtf_type]">
        <span class="vertical-middle">旧版三证</span>
        <input type="radio" value="1" id="new"
               style="margin-left: 14px;" <?= $crmcertf['crtf_type'] == 1 ? "checked=checked" : null; ?>
               name="CrmC[crtf_type]">
        <span class="vertical-middle">新版三证合一</span>
        <span style="margin-left: 40px;color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
    </div>
    <div class="inline-block mb-10">
        <label class="label-width label-align" for="">税籍编码/统一社会信用代码：</label>
        <?php if($crmcertf['crtf_type'] == 0){ ?>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'taxCodeOld'"  id="taxcode" type="text" name="CrmCustomerInfo[cust_tax_code]"
                   value="<?= $model['cust_tax_code'] ?>" maxlength="20" >
        <?php }else{ ?>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'taxCodeNew'"  id="taxcode" type="text" name="CrmCustomerInfo[cust_tax_code]"
                   value="<?= $model['cust_tax_code'] ?>" maxlength="20" >
        <?php } ?>
    </div>
    <div id="oldthreecer" <?=$crmcertf['crtf_type']?"class='display-none'":""?> >
        <div class="mb-10">
            <label class="label-width label-align" id="business">公司营业执照：</label>
            <input type="text" readonly="readonly"
                   name="CrmC[o_license]" maxlength="120" id="license_name"
                   value="<?= $crmcertf['crtf_type'] == 0 ? $crmcertf['o_license'] :'' ?>" style="width: 300px;">
            <input type="file" style="width: 70px;" multiple="multiple" name="upfiles-lic" id="upfiles-lic"
                   class="up-btn" onchange="license(this)"/>
        </div>
        <div class="mb-10" id="tax">
            <label class="label-width label-align">税务登记证：</label>
            <input type="text" readonly="readonly"
                   name="CrmC[o_reg]" maxlength="120" id="tax_name"
                   value="<?= $crmcertf['o_reg'] ?>" style="width: 300px;">
            <input type="file" style="width: 70px;" multiple="multiple" name="upfiles-tax" id="upfiles-tax" class="up-btn"
                   onchange="reg(this)"/>
        </div>
    </div>
    <div id="newthreecer" <?=$crmcertf['crtf_type']?"":"class='display-none'"?> >
        <div class="mb-10">
            <label class="label-width label-align" id="business">公司三证合一：</label>
            <input type="text" readonly="readonly"
                   name="CrmC[o_license_new]" maxlength="120" id="license_name_new"
                   value="<?= $crmcertf['crtf_type'] == 1 ? $crmcertf['o_license'] :'' ?>" style="width: 300px;">
            <input type="file" style="width: 70px;" multiple="multiple" name="upfiles-lic" id="upfiles-lic"
                   class="up-btn" onchange="licenseNew(this)"/>
        </div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">一般纳税人资格证：</label>
        <input style="width: 300px;" type="text" readonly="readonly"
               name="CrmC[o_cerft]" maxlength="120" id="org_name"
               value="<?= $crmcertf['o_cerft'] ?>">
        <input type="file" style="width: 70px;" multiple="multiple" name="upfiles-org" id="upfiles-org" class="up-btn"
               onchange="cerft(this)"/>
    </div>
    <div class="mb-10">
        <label class="label-width label-align vertical-top">备注：</label>
        <textarea rows="3" name="CrmC[marks]" style="width: 375px;" id="remark"
                  placeholder="最多输入200个字"
                  maxlength="200"><?= $crmcertf['marks'] ?></textarea>
    </div>

    <div class="text-center mt-40">
        <button type="submit" class="button-blue-big sub">确认</button>
        <button type="button" class="button-white-big" onclick="close_select()">取消</button>
    </div>

</div>
<?php ActiveForm::end() ?>
<div id="spp_tpl" style="display: none;">
    <label class="lable-width label-align " for=""><span class="red">*</span>供应商代码：</label>
    <input class="value-align easyui-validatebox" data-options="required:true" id="sppno"
           style="width: 140px;"
           type="text" name="CrmC[spp_no]"
           value="<?= $crmcertf['spp_no'] ?>" maxlength="120">
</div>
<script>
    $(function(){
        ajaxSubmitForm($("#add-form"),'',function(res){
            if(res.flag==1){
                parent.layer.alert(res.msg,{icon:1});
                parent.window.location.reload();
            }else{
                parent.layer.alert(res.msg,{icon:2});
            }
        });

        $.extend($.fn.validatebox.defaults.rules, {
            taxCode: {
                validator: function (value, param) {
                    return /^[0-9a-zA-Z]{15,20}$/.test(value);
                },
                message: '必须为15-20位的数字,字母组合'
            },
            regCard: {
                validator: function (value, param) {
                    return /^[0-9a-zA-Z]*$/.test(value);
                },
                message: '只能输入字母或数字'
            }
        });


        //当是供应商时显示供应商代码
        $("#radyes").click(function () {
            $("#radyes").attr('checked', 'checked');
            $("#radno").attr('checked', false);
            $("#custcode").show();
            $('#sppno').validatebox({required:true,validType:'regCard'});
        });

        //当不是供应商时隐藏供应商代码
        $("#radno").click(function () {
            $("#radno").attr('checked', 'checked');
            $("#radyes").attr('checked', false);
            $("#custcode").css('display', 'none');
            $('#sppno').validatebox({required:false});
        });


        //当选择旧版三证时，隐藏公司三证合一证显示公司营业执照证和税务登记证
        $("#old").click(function () {
            $("#old").attr('checked', 'checked');
            $("#new").attr('checked', false);
//        $("#business").html("<span class='red'>*</span>公司营业执照证：");
            $("#tax").css('display', 'block');
            $("#tax_name").attr('class', 'easyui-validatebox width-550');
            $("#tax_name").attr('data-options', "required:'true'");
            $('#oldthreecer').removeClass('display-none');
            $('#newthreecer').addClass('display-none');
            $("#taxcode").validatebox({
                validType:'taxCodeOld'
            });
        });
        //当选择新版三证合一时，显示公司三证合一证隐藏公司营业执照证和税务登记证
        $("#new").click(function () {
            $("#new").attr('checked', 'checked');
            $("#old").attr('checked', false);
//        $("#business").html("<span class='red'>*</span>公司三证合一证：");
            $("#tax").css('display', 'none');
            $("#tax_name").removeAttr('class');
            $("#tax_name").removeAttr('data-options');
            $('#oldthreecer').addClass('display-none');
            $('#newthreecer').removeClass('display-none');
            $("#taxcode").validatebox({
                validType:'taxCodeNew'
            });
//        var len = $("#taxcode").val().length;
//        if (len < 17) {
//            alert("税籍编码不能小于17位");
//        }
//        $("#newthreeinone").css('display', 'block');
        });
        //当选择新版三证合一时，显示公司三证合一证隐藏公司营业执照证和税务登记证
    });
    function onfocustishi(val, title, id) {
        if (val == title) {
            $("#" + id).attr("placeholder", "");
        }
    }
    function blurtishi(val, title, id) {
        if (val == "") {
            $("#" + id).attr("placeholder", title);
        }
    }
    function change(obj) {
        var length = obj.files.length;
        //var span = obj.parentNode.previousSibling.previousSibling;
        var temp = "";
        for (var i = 0; i < obj.files.length; i++) {
            if (i == 0) {
//                console.log(obj);
//                console.log(obj.files[i].name);
                temp = obj.files[i].name;
            } else {
                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
            }
//            console.log(temp);
            return temp;
//            $("#LICENSE_NAME").val(temp);
        }
    }
    //三证合一和营业执照文件名
    function license(obj) {
        $("#license_name").val(change(obj));
//        $("#upfiles-lic").val(change(obj));
    }
    function licenseNew(obj) {
        $("#license_name_new").val(change(obj));
//        $("#upfiles-lic").val(change(obj));
    }
    //税务登记证文件名
    function reg(obj) {
        $("#tax_name").val(change(obj));
//        $("#upfiles-tax").val(change(obj));
    }
    //一般纳税人资格证文件名
    function cerft(obj) {
        $("#org_name").val(change(obj));
//        $("#upfiles-org").val(change(obj));
    }
</script>
