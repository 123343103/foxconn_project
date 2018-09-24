<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<h1 class="head-first">
    <?php if(!empty($model)){ ?>
        修改客户信息
    <?php }else{ ?>
        新增客户信息
    <?php } ?>
</h1>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户基本信息</a>
    </h2>
    <div class="mb-10 ml-10">
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>公司名称</label>
            <input name="CrmCustomerInfo[cust_sname]" class="width-200  add-require easyui-validatebox"
                   data-options="required:true,validType:'unique',delay:10000"
                   data-act="<?= Url::to(['/crm/crm-member/validate']) ?>" data-attr="cust_sname"
                   data-id="<?= $model['cust_id']; ?>" type="text" value="<?= $model['cust_sname'] ?>" maxlength="50">
            <label class="width-140">公司简称</label>
            <input class="width-200 easyui-validatebox" type="text" name="CrmCustomerInfo[cust_shortname]"
                   value="<?= $model['cust_shortname'] ?>" maxlength="50">
        </div>
        <div class="mb-10">
            <label class="width-100">公司电话</label>
            <input class="width-200 easyui-validatebox" data-options="validType:'telphone'" type="text"
                   name="CrmCustomerInfo[cust_tel1]" value="<?= $model['cust_tel1'] ?>">
            <label class="width-140">邮编</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[member_compzipcode]"
                   value="<?= $model['member_compzipcode'] ?>" maxlength="20">
        </div>
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>详细地址</label>
            <select class="width-135 disName easyui-validatebox" data-options="required:'true'" id="disName_1">
                <option value="">请选择...</option>
                <?php foreach ($downList['country'] as $key => $val) { ?>
                    <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['oneLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                <?php } ?>
            </select>
            <select class="width-135 disName easyui-validatebox" data-options="required:'true'" id="disName_2">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['twoLevel'] as $val) { ?>
                        <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['twoLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-135 disName easyui-validatebox" data-options="required:'true'" id="disName_3">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['threeLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['threeLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <select class="width-132 disName easyui-validatebox" data-options="required:'true'" id="disName_4"
                    name="CrmCustomerInfo[cust_district_2]">
                <option value="">请选择...</option>
                <?php if (!empty($districtAll)) { ?>
                    <?php foreach ($districtAll['fourLevel'] as $val) { ?>
                        <option
                                value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $districtAll['fourLevelId'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            <input  style="margin-left: 104px;margin-top: 10px;" class="width-550 easyui-validatebox" data-options="required:'true'" type="text" name="CrmCustomerInfo[cust_adress]" value="<?= $model['cust_adress'] ?>" maxlength="100">
        </div>
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>联系人</label>
            <input class="width-200 easyui-validatebox" data-options="required:'true'" type="text"
                   name="CrmCustomerInfo[cust_contacts]" value="<?= $model['cust_contacts'] ?>" maxlength="20">
            <label class="width-140">职位</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[cust_position]"
                   value="<?= $model['cust_position'] ?>" maxlength="20">
        </div>
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>联系方式</label>
            <input class="width-200 easyui-validatebox" data-options="required:'true',validType:'mobile'" type="text"
                   name="CrmCustomerInfo[cust_tel2]" value="<?= $model['cust_tel2'] ?>">
            <label class="width-140"><span class="red">*</span>邮箱</label>
            <input class="width-200 easyui-validatebox" data-options="required:true,validType:'email'" type="text"
                   name="CrmCustomerInfo[cust_email]" value="<?= $model['cust_email'] ?>">
        </div>
        <div class="mb-10">
            <label class="width-100"><span class="red">*</span>用户名</label>
            <input name="CrmCustomerInfo[member_name]" class="width-200  easyui-validatebox" data-options="required:true,validType:'unique',delay:10000" data-act="<?=Url::to(['/crm/crm-member/validate'])?>" data-attr="member_name" data-id="<?=$model['cust_id'];?>" type="text" value="<?= $model['member_name'] ?>" maxlength="50">
            <label class="width-140">注册网站</label>
            <select class="width-200 easyui-validatebox add-require" data-options="required:'true'" name="CrmCustomerInfo[member_regweb]">
                <option value="">请选择...</option>
                <?php foreach ($downList['regWeb'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_regweb']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">注册时间</label>
            <input class="width-200 select-date easyui-validatebox" data-options="required:true" type="text" name="CrmCustomerInfo[member_regtime]" value="<?=$model['member_regtime']?$model['member_regtime']:date("Y-m-d") ?>" readonly="readonly">
            <label class="width-140"><span class="red">*</span>会员类别</label>
            <select class="width-200 easyui-validatebox add-require" type="text" name="CrmCustomerInfo[member_type]"
                    data-options="required:'true'">
                <option value="">请选择...</option>
                <?php foreach ($downList['memberType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= ($model['member_type']==$val['bsp_id'])?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">是否回访</label>
            <select name="CrmCustomerInfo[member_visitflag]" class="width-140 visit_flag" disabled="disabled">
                <option value="0" <?= $model['member_visitflag']=='0'?"selected":null; ?>>否</option>
                <option value="1" <?= $model['member_visitflag']=='1'?"selected":null; ?>>是</option>
            </select>
        </div>
    </div>

    <h2 class="head-three">
        <a href="javascript:void(0)" class="ml-10">
            客户详细信息
            <i class="icon-caret-down float-right font-arrows"></i>
        </a>
    </h2>
    <div class="ml-10 mb-10">
        <div class="mb-10">
            <label class="width-100">法人代表</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[cust_inchargeperson]" value="<?= $model['cust_inchargeperson'] ?>" maxlength="15" id="cust_inchargeperson">
            <label class="width-140">注册时间</label>
            <input class="width-200 select-date" type="text" name="CrmCustomerInfo[cust_regdate]" value="<?= $model['cust_regdate'] ?>" readonly="readonly" id="cust_regdate">
        </div>
        <div class="mb-10">
            <label class="width-100">注册资金</label>
            <input  class="width-200 easyui-validatebox" data-options="validType:'six_decimal'" type="text" name="CrmCustomerInfo[cust_regfunds]" value="<?= $model['cust_regfunds'] ?>" maxlength="15" id="cust_regfunds">
            <label class="width-140">注册货币</label>
            <select class="width-200" name="CrmCustomerInfo[member_regcurr]" id="member_regcurr">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_regcurr']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">公司类型</label>
            <select class="width-200" name="CrmCustomerInfo[cust_compvirtue]" id="cust_compvirtue">
                <option value="">请选择...</option>
                <?php foreach ($downList['property'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['cust_compvirtue']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">客户来源</label>
            <select class="width-200" name="CrmCustomerInfo[member_source]" id="member_source">
                <option value="">请选择...</option>
                <?php foreach ($downList['customerSource'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_source']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">经营范围</label>
            <select class="width-200" name="CrmCustomerInfo[cust_businesstype]" id="cust_businesstype">
                <option value="">请选择...</option>
                <?php foreach ($downList['managementType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['cust_businesstype']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">交易币种</label>
            <select class="width-200" name="CrmCustomerInfo[member_curr]" id="member_curr">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_curr']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">年营业额</label>
            <input class="width-115 easyui-validatebox" data-options="validType:'six_decimal'" type="text" name="CrmCustomerInfo[member_compsum]" value="<?= $model['member_compsum'] ?>" maxlength="15" id="member_compsum">
            <select name="CrmCustomerInfo[compsum_cur]" class="width-80" id="compsum_cur">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= $model['compsum_cur'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">年采购额</label>
            <input class="width-115 easyui-validatebox" data-options="validType:'six_decimal'" type="text" name="CrmCustomerInfo[cust_pruchaseqty]" value="<?= $model['cust_pruchaseqty'] ?>" maxlength="15" id="cust_pruchaseqty">
            <select name="CrmCustomerInfo[pruchaseqty_cur]" class="width-80" id="cust_pruchaseqty_currency">
                <option value="">请选择...</option>
                <?php foreach ($downList['tradeCurrency'] as $key => $val){ ?>
                    <option value="<?= $val['bsp_id'] ?>" <?= $model['pruchaseqty_cur'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">员工人数</label>
            <input class="width-200 easyui-validatebox" data-options="validType:'number'" type="text" name="CrmCustomerInfo[cust_personqty]" value="<?= $model['cust_personqty'] ?>" maxlength="15" id="cust_personqty">
            <label class="width-140">发票需求</label>
            <select class="width-200" name="CrmCustomerInfo[member_compreq]" id="member_compreq">
                <option value="">请选择...</option>
                <?php foreach ($downList['invoiceType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_compreq']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">潜在需求</label>
            <select class="width-200" name="CrmCustomerInfo[member_reqflag]" id="member_reqflag">
                <option value="">请选择...</option>
                <?php foreach ($downList['latentDemand'] as $key => $val) { ?>
                    <option
                            value="<?= $val['bsp_id'] ?>"<?= $model['member_reqflag']==$val['bsp_id']?"selected":null; ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <label class="width-140">需求类目</label>
            <select class="width-200" name="CrmCustomerInfo[member_reqitemclass]" id="member_reqitemclass">
                <option value="">请选择...</option>
                <?php foreach ($downList['productType'] as $key => $val) { ?>
                    <option
                            value="<?= $val['category_id'] ?>"<?= $model['member_reqitemclass']==$val['category_id']?"selected":null; ?>><?= $val['category_sname'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="mb-10">
            <label class="width-100">需求类别</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[member_reqdesription]" value="<?= $model['member_reqdesription'] ?>" id="member_reqdesription">
            <label class="width-140">主要市场</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[member_marketing]" value="<?= $model['member_marketing'] ?>" maxlength="50" id="member_marketing">
        </div>
        <div class="mb-10">
            <label class="width-100">主要客户</label>
            <input class="width-200" type="text" name="CrmCustomerInfo[member_compcust]" value="<?= $model['member_compcust'] ?>" maxlength="50" id="member_compcust">
            <label class="width-140">主页</label>
            <input class="width-200 easyui-validatebox" data-options="validType:'www'" type="text" name="CrmCustomerInfo[member_compwebside]" onkeyup="surplus(this,200);" value="<?= $model['member_compwebside'] ?>" maxlength="200" id="member_compwebside">
<!--            <span class="red surplus">--><?//= isset($model['member_compwebside'])?strlen($model['member_compwebside']):'0'; ?><!--/200</span>-->
        </div>
        <div class="mb-10">
            <label class="width-100 vertical-top">经营范围</label>
            <textarea class="width-550" onkeyup="surplus(this,200);" name="CrmCustomerInfo[member_businessarea]" id="member_businessarea" cols="5" rows="3" maxlength="200"><?= $model['member_businessarea'] ?></textarea>
<!--            <span class="red surplus">--><?//= isset($model['member_businessarea'])?strlen($model['member_businessarea']):'0'; ?><!--/200</span>-->
        </div>
        <div class="mb-10">
            <label class="width-100 vertical-top">备注</label>
            <textarea class="width-550" onkeyup="surplus(this,200);" name="CrmCustomerInfo[member_remark]" id="member_remark" rows="3" maxlength="200"><?= $model['member_remark'] ?></textarea>
<!--            <span class="red surplus">--><?//= isset($model['member_remark'])?strlen($model['member_remark']):'0'; ?><!--/200</span>-->
        </div>
    </div>

    <div class="text-center">
        <?php if(!empty($model)){ ?>
            <button type="submit" class="button-blue-big">确定</button>
        <?php }else{ ?>
            <button type="button" class="button-blue-big" id="sub">确定</button>
        <?php } ?>
        <button class="button-white-big" onclick="close_select()" type="button">取消</button>
    </div>

    <?php ActiveForm::end() ?>
</div>
<script>
    $(function(){
        <?php if(!empty($model)){ ?>
        ajaxSubmitForm($("#add-form"));
        <?php }else{ ?>
        $('#sub').click(function(){
            if (!$('#add-form').form('validate')) {
                return false;
            }
            $.ajax({
                type:'post',
                dataType:'json',
                data:$("#add-form").serialize(),
                url:"<?= \yii\helpers\Url::to(['create-customer']) ?>",
                success:function(data){
                    if(data.flag == 1){
                        layer.alert("添加成功!",{icon:1,end: function () {
                            parent.window.location.reload();
                        }});
                    }
                }
            })
        })
        <?php } ?>
        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select,$url,"select");
        });
    });
</script>
