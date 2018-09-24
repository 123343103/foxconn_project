<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\JeDateAsset;
use \yii\helpers\Url;
JeDateAsset::register($this);
?>
<style>
    #search{
        position: relative;
    }
    .auto{
        width:200px;
        z-index: 1;
        position: absolute;
        top:25px;
        left:123px;
        background-color: white;
        height:200px;
        overflow: auto;
        /*float: left;*/
    }
    .highlight {
        background-color: #9ACCFB;
    }

    .label-width{
        width:80px;
    }
    .width-120{
        width:120px;
    }
    .width-175{
        width:175px;
    }
    .value-width{
        width:200px !important;
    }
    .ml-220{
        margin-left: 220px;
    }
</style>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-second">
        <a href="javascript:void(0)" class="ml-10">
            <i class="icon-caret-down"></i>
            会员信息
        </a>
    </h2>
    <div class="ml-10">
        <div class="mb-10">
            <?php if (\Yii::$app->controller->action->id == "create") { ?>
                <label class="label-width label-align"><span class="red">*</span>用户名</label><label>:</label>
                <input name="CrmCustomerInfo[member_name]" class="value-width value-align  easyui-validatebox remove" data-options="required:true,validType:'unique',delay:10000,validateOnBlur:true" data-act="<?=Url::to(['validate-name'])?>" data-attr="member_name" data-id="<?=$model['cust_id'];?>" type="text" value="<?= $model['member_name'] ?>" maxlength="20" placeholder="最多输入20个字" id="member_name">
                <label class="ml-220 label-width label-align"><span class="red">*</span>注册网站</label><label>:</label>
                <select class="value-width value-align easyui-validatebox remove" data-options="required:'true'" name="CrmCustomerInfo[member_regweb]" id="member_regweb">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                        <option
                                value="<?= $val['bsp_id'] ?>"<?= $model['member_regweb']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            <?php }else if(\Yii::$app->controller->action->id == "update"){ ?>
                <label class="label-width label-align vertical-top">用户名</label><label class="vertical-top">:</label>
                <label class="value-width value-align vertical-top"><?= $model['member_name'] ?></label>
                <label class="ml-220 label-width label-align vertical-top">注册网站</label><label class="vertical-top">:</label>
                <label class="value-width value-align vertical-top"><?= $model['regWeb'] ?></label>
            <?php } ?>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">会员类别</label><label>:</label>
            <select class="value-width value-align easyui-validatebox member_type display-none" type="text" name="CrmCustomerInfo[member_type]"
                    data-options="required:'true'">
                <?php foreach ($downList['memberType'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>"<?= ($model['member_type']==$val['bsp_id'])?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <span class="value-width value-align" id="memberType"><?= $model['memberType']?$model['memberType']:'普通会员' ?></span>
            <label class="ml-220 label-width label-align">注册时间</label><label>:</label>
            <input class="value-width value-align easyui-validatebox" data-options="required:true" type="hidden" name="CrmCustomerInfo[member_regtime]" value="<?=$model['member_regtime']?$model['member_regtime']:date("Y-m-d") ?>" readonly="readonly">
            <span><?=$model['member_regtime']?$model['member_regtime']:date("Y-m-d") ?></span>
        </div>
    </div>
    <h2 class="head-second">
        <a href="javascript:void(0)" class="ml-10">
            <i class="icon-caret-down"></i>
            客户基本信息
        </a>
    </h2>
    <div class="ml-10">
        <div class="mb-10" id="search">
            <input type="hidden" name="CrmCustomerInfo[cust_id]" id="cust_id" value="<?=$model['cust_id'];?>">
            <?php if (\Yii::$app->controller->action->id == "create") { ?>
                <label class="label-width label-align"><span class="red">*</span>公司名称</label><label>:</label>
                <input name="CrmCustomerInfo[cust_sname]" class="value-width value-align easyui-validatebox remove" data-options="required:true,validType:'unique',delay:10000,validateOnBlur:true" data-act="<?=Url::to(['validate-member'])?>" data-attr="cust_sname" data-id="<?=$model['cust_id'];?>"  type="text" value="<?= $model['cust_sname'] ?>" maxlength="50" id="cust_sname">
                <a class="label-width" id="select_customer"> 选择客户</a>
                <label style="margin-left: 169px;" class="label-width label-align"><span class="red">*</span>公司简称</label><label>:</label>
                <input class="value-width value-align easyui-validatebox remove" id="cust_shortname" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_shortname]" value="<?= $model['cust_shortname'] ?>" maxlength="10" placeholder="最多输入10个字">
            <?php }else if(\Yii::$app->controller->action->id == "update"){ ?>
                <label class="label-width label-align vertical-top">公司名称</label><label class="vertical-top">:</label>
                <label class="value-width value-align vertical-top"><?= $model['cust_sname'] ?></label>
                <label class="ml-220 label-width label-align vertical-top">公司简称</label><label class="vertical-top">:</label>
                <label class="value-width value-align vertical-top"><?= $model['cust_shortname'] ?></label>
            <?php } ?>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">公司电话</label><label>:</label>
            <input class="value-width value-align easyui-validatebox IsTel" type="text" name="CrmCustomerInfo[cust_tel1]" data-options="validType:'telphone'" value="<?= $model['cust_tel1'] ?>" maxlength="20" id="cust_tel1" placeholder="请输入:xxx-xxxxxxxx">
            <label class="ml-220 label-width label-align">邮编</label><label>:</label>
            <input class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'postcode'" type="text" name="CrmCustomerInfo[member_compzipcode]" value="<?= $model['member_compzipcode'] ?>" maxlength="6" id="member_compzipcode" placeholder="请输入:xxxxxx">
        </div>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>联系人</label><label>:</label>
            <input class="value-width value-align easyui-validatebox remove" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="15" id="cust_contacts">
            <label class="ml-220 label-width label-align">职位</label><label>:</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_position]" value="<?= $model['cust_position'] ?>" maxlength="15" id="cust_position">
        </div>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>联系方式</label><label>:</label>
            <input class="value-width value-align easyui-validatebox remove" data-options="required:'true',validType:'mobile'"  type="text" name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>" maxlength="15" id="cust_tel2" placeholder="请输入:xxx xxxx xxxx">
            <label class="ml-220 label-width label-align"><span class="red">*</span>邮箱</label><label>:</label>
            <input class="value-width value-align easyui-validatebox remove" data-options="required:'true',validType:'email'" type="text" name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>" maxlength="80" id="cust_email" placeholder="请输入:xxx@xxx.xxx">
        </div>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>详细地址</label><label>:</label>
            <select class="width-175 disName easyui-validatebox remove" data-options="required:'true'" id="disName_1">
                <option value="">国</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option
                            value="<?= $val['district_id'] ?>" <?=$val['district_id']==$districtAll['oneLevelId']?'selected':null?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-175 disName easyui-validatebox remove" data-options="required:'true'" id="disName_2">
                <option value="">省</option>
                <?php if(!empty($districtAll)){?>
                    <?php foreach($districtAll['twoLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['twoLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select class="width-175 disName easyui-validatebox remove" data-options="required:'true'" id="disName_3">
                <option value="">市</option>
                <?php if(!empty($districtAll)){?>
                    <?php foreach($districtAll['threeLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['threeLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <select style="width:172px;" class="disName easyui-validatebox remove" data-options="required:'true'" id="disName_4" name="CrmCustomerInfo[cust_district_2]">
                <option value="">区</option>
                <?php if(!empty($districtAll)){?>
                    <?php foreach($districtAll['fourLevel'] as $val){?>
                        <option value="<?=$val['district_id']?>" <?=$val['district_id']==$districtAll['fourLevelId']?'selected':null?>><?=$val['district_name']?></option>
                    <?php }?>
                <?php }?>
            </select>
            <input class="mt-5 easyui-validatebox remove" data-options="required:'true'" style="width:708px; margin-left: 86px;" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" maxlength="100" placeholder="请填写公司详细地址,例如街道名称、门牌号码、楼层等信息" id="cust_adress">
        </div>
    </div>
    <h2 class="head-second">
        <a href="javascript:void(0)" class="ml-10">
            <i class="icon-caret-down"></i>
            客户详细信息
        </a>
    </h2>
    <div class="ml-10">
        <div class="mb-10">
            <label class="label-width label-align">法人代表</label><label>:</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[cust_inchargeperson]" value="<?= $model['cust_inchargeperson'] ?>" maxlength="15" id="cust_inchargeperson">
            <label class="ml-220 label-width label-align">注册时间</label><label>:</label>
            <input class="value-width value-align Wdate" type="text" name="CrmCustomerInfo[cust_regdate]" value="<?= $model['cust_regdate'] ?>" id="cust_regdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd' })" onfocus="this.blur()">
        </div>
        <div class="mb-10">
            <label class="label-width label-align">注册资金</label><label>:</label>
            <input  class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'positive'" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?= !empty($model['cust_regfunds'])?floor($model['cust_regfunds']):'' ?>" maxlength="15" id="cust_regfunds">
            <label class="ml-220 label-width label-align">注册货币</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_regcurr]" id="member_regcurr">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= (!empty($model)?$model['member_regcurr']:'100091') ==$val['bsp_id']?" selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">公司类型</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_compvirtue]" id="cust_compvirtue">
                <option value="">请选择...</option>
                <?php foreach ($downList['property'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['cust_compvirtue']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="ml-220 label-width label-align">客户来源</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_source]" id="member_source">
                <option value="">请选择...</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_source']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">经营范围</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[cust_businesstype]" id="cust_businesstype">
                <option value="">请选择...</option>
                <?php foreach ($downList['managementType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['cust_businesstype']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="ml-220 label-width label-align">交易币种</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_curr]" id="member_curr">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= (!empty($model)?$model['member_curr']:'100091')==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">年营业额</label><label>:</label>
            <input class="width-120 easyui-validatebox Onlynum" data-options="validType:'positive'" type="text" name="CrmCustomerInfo[member_compsum]" value="<?= !empty($model['member_compsum'])?floor($model['member_compsum']):'' ?>" maxlength="15" id="member_compsum">
            <select name="CrmCustomerInfo[compsum_cur]" style="width:76px;" id="member_compsum_currency">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= (!empty($model)?$model['compsum_cur']:'100091') == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="ml-220 label-width label-align">年采购额</label><label>:</label>
            <input class="width-120 easyui-validatebox Onlynum" data-options="validType:'positive'" type="text" name="CrmCustomerInfo[cust_pruchaseqty]" value="<?= !empty($model['cust_pruchaseqty'])?floor($model['cust_pruchaseqty']):'' ?>" maxlength="15" id="cust_pruchaseqty">
            <select name="CrmCustomerInfo[pruchaseqty_cur]" style="width:76px;" id="pruchaseqty_cur">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= (!empty($model)?$model['pruchaseqty_cur']:'100091') == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">员工人数</label><label>:</label>
            <input class="value-width value-align easyui-validatebox Onlynum" data-options="validType:'number'" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15" id="cust_personqty">
            <label class="ml-220 label-width label-align">发票需求</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_compreq]" id="member_compreq">
                <option value="">请选择...</option>
                <?php foreach ($downList['InvoiceNeeds'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_compreq']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">潜在需求</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_reqflag]" id="member_reqflag">
                <option value="">请选择...</option>
                <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_reqflag']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="ml-220 label-width label-align">需求类目</label><label>:</label>
            <select class="value-width value-align" name="CrmCustomerInfo[member_reqitemclass]" id="member_reqitemclass">
                <option value="">请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['catg_id'] ?>"<?= $model['member_reqitemclass']==$val['catg_id']?"selected":null; ?>><?= $val['catg_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">需求类别</label><label>:</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[member_reqdesription]" value="<?= $model['member_reqdesription'] ?>" id="member_reqdesription">
            <label class="ml-220 label-width label-align">主要市场</label><label>:</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[member_marketing]" value="<?= $model['member_marketing'] ?>" maxlength="50" id="member_marketing">
        </div>
        <div class="mb-10">
            <label class="label-width label-align">主要客户</label><label>:</label>
            <input class="value-width value-align" type="text" name="CrmCustomerInfo[member_compcust]" value="<?= $model['member_compcust'] ?>" maxlength="50" id="member_compcust">
            <label class="ml-220 label-width label-align">主页</label><label>:</label>
            <input class="value-width value-align easyui-validatebox" data-options="validType:'www'" type="text" name="CrmCustomerInfo[member_compwebside]" value="<?= $model['member_compwebside'] ?>" maxlength="200" id="member_compwebside" placeholder="请输入 www.xxxx.xxx">
<!--            <span class="red surplus">--><?//= isset($model['member_compwebside'])?strlen($model['member_compwebside']):'0'; ?><!--/200</span>-->
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top">经营范围说明</label><label class="vertical-top">:</label>
            <textarea  class="easyui-validatebox" data-options="validType:'maxLength[200]'"  style="width:708px;" name="CrmCustomerInfo[member_businessarea]" id="member_businessarea" cols="5" rows="3" maxlength="200" placeholder="最多输入200个字"><?= $model['member_businessarea'] ?></textarea>
<!--            <span class="red surplus">--><?//= isset($model['member_businessarea'])?strlen($model['member_businessarea']):'0'; ?><!--/200</span>-->
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top">备注</label><label class="vertical-top">:</label>
            <textarea  class="easyui-validatebox" data-options="validType:'maxLength[200]'" placeholder="最多输入200个字" style="width:708px;" name="CrmCustomerInfo[member_remark]" id="member_remark" rows="3" maxlength="200"><?= $model['member_remark'] ?></textarea>
<!--            <span class="red surplus">--><?//= isset($model['member_remark'])?strlen($model['member_remark']):'0'; ?><!--/200</span>-->
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="button-blue-big sub">确定</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>

    <?php ActiveForm::end() ?>
<script>

    $(function(){
        $('#member_compwebside').attr('autocomplete','off');
        $(".IsTel").telphone();
        $(".Onlynum").numbervalid();

            ajaxSubmitForm($("#add-form"));
        /*div 收缩展开*/
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).find('i').toggleClass("icon-caret-right");
            $(this).find('i').toggleClass("icon-caret-down");
        });
        /*地址*/
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });
//        /*客户基本信息模糊搜索带出数据*/
//        var url = '<?//= Url::to(["select-com"]) ?>//';
//        var url2 = '<?//= Url::to(["select-com-one"]) ?>//';
//        var cust_sname = '#cust_sname';
//        searchCust(cust_sname,url,url2,function(term){
//            $('#cust_id').val(term.cust_id);
//            $('#cust_sname').val(term.cust_sname).validatebox("remove").validatebox({required:false});
//            $("#cust_shortname").val(term.cust_shortname).validatebox("remove").validatebox({required:false});
//            $("#cust_tel1").val(term.cust_tel1).validatebox("remove").validatebox({required:false});
//            $("#member_compzipcode").val(term.member_compzipcode).validatebox("remove").validatebox({required:false});
//            $("#cust_contacts").val(term.cust_contacts).validatebox("remove").validatebox({required:false});
//            $("#cust_position").val(term.cust_position);
//            $("#cust_inchargeperson").val(term.cust_inchargeperson);
//            $("#cust_regdate").val(term.cust_regdate);
//            $("#cust_regfunds").val(term.cust_regfunds);
//            $("#member_regcurr").val(term.member_regcurr);
//            $("#cust_compvirtue").val(term.cust_compvirtue);
//            $("#member_source").val(term.member_source);
//            $("#cust_businesstype").val(term.cust_businesstype);
//            $("#member_curr").val(term.member_curr);
//            $("#member_compsum").val(term.member_compsum);
//            $("#member_compsum_currency").val(term.compsum_cur);
//            $("#cust_pruchaseqty").val(term.cust_pruchaseqty);
//            $("#pruchaseqty_cur").val(term.pruchaseqty_cur);
//            $("#cust_personqty").val(term.cust_personqty);
//            $("#member_compreq").val(term.member_compreq);
//            $("#member_reqflag").val(term.member_reqflag);
//            $("#member_reqitemclass").val(term.member_reqitemclass);
//            $("#member_marketing").val(term.member_marketing);
//            $("#member_reqdesription").val(term.member_reqdesription);
//            $("#member_compcust").val(term.member_compcust);
//            $("#member_compwebside").val(term.member_compwebside);
//            $("#member_businessarea").html(term.member_businessarea);
//            $("#member_remark").html(term.member_remark);
//            $("#cust_tel2").val(term.cust_tel2).validatebox("remove").validatebox({required:false});
//            $("#cust_email").val(term.cust_email).validatebox("remove").validatebox({required:false});
//            $("#disName_1").html('<option value="' + term.country_id + '" >' + term.country_name + '</option>').validatebox("remove").validatebox({required:false});
//            $("#disName_2").html('<option value="' + term.provice_id + '" >' + term.provice_name + '</option>').validatebox("remove").validatebox({required:false});
//            $("#disName_3").html('<option value="' + term.city_id + '" >' + term.city_name + '</option>').validatebox("remove").validatebox({required:false});
//            $("#disName_4").html('<option value="' + term.cust_district_2 + '" >' + term.area_name + '</option>').validatebox("remove").validatebox({required:false});
//            $("#cust_adress").val(term.cust_adress);
//            if(term.crtf_pkid != null && term.YN == 1){
//                $('.member_type').val('100071');
//                $("#memberType").text(term.memberType);
//            }else{
//                $('.member_type').val('100070');
//                $("#memberType").text('普通会员');
//            }
//            if(term.aid != null && term.crtf_pkid == null || term.aid != null && term.crtf_pkid != null){
//                $('.member_type').val('100072');
//                $("#memberType").text(term.memberType);
//            }else{
//                $('.member_type').val('100070');
//                $("#memberType").text('普通会员');
//            }
//        });
        $('#cust_sname').blur(function(){
            var data = $('#cust_sname').val();
            var url = '<?= Url::to(["select-com-one"]) ?>';
            $.ajax({
                type: 'POST',
                url: url + '?data=' + data,
                dataType: 'json',
                success: function (term) {
                    if(term != false){
                        $('#cust_id').val(term.cust_id);
//                        $('#member_name').val(term.member_name);
                        $('#member_regweb').val(term.member_regweb);
                        $('#cust_sname').val(term.cust_sname);
                        $("#cust_shortname").val(term.cust_shortname);
                        $("#cust_tel1").val(term.cust_tel1);
                        $("#member_compzipcode").val(term.member_compzipcode);
                        $("#cust_contacts").val(term.cust_contacts);
                        $("#cust_position").val(term.cust_position);
                        $("#cust_inchargeperson").val(term.cust_inchargeperson);
                        $("#cust_regdate").val(term.cust_regdate);
                        $("#cust_regfunds").val(Math.round(term.cust_regfunds) || null);
                        $("#member_regcurr").val(term.member_regcurr);
                        $("#cust_compvirtue").val(term.cust_compvirtue);
                        $("#member_source").val(term.member_source);
                        $("#cust_businesstype").val(term.cust_businesstype);
                        $("#member_curr").val(term.member_curr);
                        $("#member_compsum").val(Math.round(term.member_compsum) || null);
                        $("#member_compsum_currency").val(term.compsum_cur);
                        $("#cust_pruchaseqty").val(term.cust_pruchaseqty || null);
                        $("#pruchaseqty_cur").val(Math.round(term.pruchaseqty_cur));
                        $("#cust_personqty").val(term.cust_personqty);
                        $("#member_compreq").val(term.member_compreq);
                        $("#member_reqflag").val(term.member_reqflag);
                        $("#member_reqitemclass").val(term.member_reqitemclass);
                        $("#member_marketing").val(term.member_marketing);
                        $("#member_reqdesription").val(term.member_reqdesription);
                        $("#member_compcust").val(term.member_compcust);
                        $("#member_compwebside").val(term.member_compwebside);
                        $("#member_businessarea").html(term.member_businessarea);
                        $("#member_remark").html(term.member_remark);
                        $("#cust_tel2").val(term.cust_tel2).validatebox("remove").validatebox({required:false});
                        $("#cust_email").val(term.cust_email).validatebox("remove").validatebox({required:false});
                        $("#disName_1").html('<option value="' + term.country_id + '" >' + term.country_name + '</option>').validatebox("remove").validatebox({required:false});
                        $("#disName_2").html('<option value="' + term.provice_id + '" >' + term.provice_name + '</option>').validatebox("remove").validatebox({required:false});
                        $("#disName_3").html('<option value="' + term.city_id + '" >' + term.city_name + '</option>').validatebox("remove").validatebox({required:false});
                        $("#disName_4").html('<option value="' + term.cust_district_2 + '" >' + term.area_name + '</option>').validatebox("remove").validatebox({required:false});
                        $("#cust_adress").val(term.cust_adress);
                        if (term.cust_code != null) {
                            $('.member_type').val('100071');
                            $("#memberType").text('认证会员');
                        }
                        if (term.credit_code != null) {
                            $('.member_type').val('100072');
                            $("#memberType").text('账信会员');
                        }
//                        if(term.crtf_pkid != null && term.yn == 1){
//                            $('.member_type').val('100071');
//                            $("#memberType").text(term.memberType);
//                        }
//                        if(term.aid != null && term.crtf_pkid == null || term.aid != null && term.crtf_pkid != null){
//                            $('.member_type').val('100072');
//                            $("#memberType").text(term.memberType);
//                        }
//                        if(!(term.crtf_pkid != null && term.yn == 1 || term.aid != null && term.crtf_pkid == null || term.aid != null && term.crtf_pkid != null)){
//                            $('.member_type').val('100070');
//                            $("#memberType").text('普通会员');
//                        }
                    }
                }
            })
        })

//选择客户弹出框
        $("#select_customer").fancybox({
            padding: [],
            fitToView: false,
            width: 670,
            height: 400,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: '<?= Url::to(["select-customer"]) ?>'
        });

    });


</script>
