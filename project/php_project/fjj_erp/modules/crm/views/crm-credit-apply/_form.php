<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
\app\assets\UploadAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\LCrmCreditApply */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-50 {
        width: 50px;
    }
    .width-60 {
        width: 60px;
    }
    .width-80 {
        width: 80px;
    }
    .width-100 {
        width: 100px;
    }
    .width-140 {
        width: 140px;
    }
    .width-150 {
        width: 150px;
    }
    .width-200 {
        width: 200px;
    }
    .width-240 {
        width: 240px;
    }

    .ml-10 {
        margin-left: 10px;
    }
    .ml-40 {
        margin-left: 40px;
    }
    .mt-10{
        margin-top:10px;
    }
    .select{
        background-color: #87CEFF !important;
    }
    thead>tr th{
        width:200px;
    }
    .head-three{
        margin-bottom: 10px;
    }
    .qfile{
        width:71px;
    }
    :root .qfile{
        width:55px\9;
    }
    .uploader-demo{
        float: left;
        margin:0 5px;
    }
    #fileList1,#fileList2,#filePicker2{
        float:right;
    }

    #fileList1 div,#fileList2 div{
        float:right;
        margin:0 5px;
    }
    #filePicker1{
        float:right;
    }
    .webuploader-pick{
        width:80px;
        height:25px;
        border-radius: 5px;
        background: #1e7fd0;
        text-align: center;
        line-height: 25px;
        color:#fff;
    }
    .file-item{
        padding:0 5px;
        height:25px;
        text-align: center;
        line-height: 25px;
    }
    .mess{
        height:25px;
        line-height:25px;
    }
    .file-border {
        border: 1px solid #6a6a6a;
        margin-top: 10px;
        margin-bottom: 10px;
        overflow: auto;
        padding: 10px 0;
    }
</style>
<?php $form = ActiveForm::begin([
        'id' => 'add-form',
        'options'=>['enctype'=>'multipart/form-data']
]); ?>
<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">所需信用额度</a>
</h2>
<div class="mb-10">
    <div class="mb-10">
        <input type="hidden" name="LCrmCreditApply[cust_id]" class="cust_id" value="<?= $model['cust_id'] ?>">
        <label class="width-140 label-align">客户代码</label>
        <label>：</label>
        <span class="value-align width-200"><?= $model['apply_code'] ?></span>
        <label class="width-240 label-align"><span class="red">*</span>申请账信类型</label>
        <label>：</label>
        <select name="LCrmCreditApply[credit_type]" class="width-200 value-align verify_type easyui-validatebox" data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['verifyType'] as $key => $val){ ?>
                <option value="<?= $val['business_type_id'] ?>" <?= !empty($apply['credit_type'])&&$apply['credit_type'] == $val['business_type_id']?'selected':null; ?>><?= $val['business_value'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width-140 label-align"><span class="red">*</span>币别</label>
        <label>：</label>
        <select name="LCrmCreditApply[currency]" class="width-200 value-align easyui-validatebox currency" data-options="required:true" disabled>
            <option value="">请选择...</option>
            <?php foreach ($downList['currency'] as $key => $val){ ?>
                <option value="<?= $val['bsp_id'] ?>" <?= !$apply['currency'] == null?($apply['currency'] == $val['bsp_id']?'selected':null):($val['bsp_id'] =='100091'?'selected':null) ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="width-240 label-align"><span class="red">*</span>起算日</label>
        <label>：</label>
        <select name="LCrmCreditApply[initial_day]" class="width-200 easyui-validatebox" data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['initial_day'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $apply['initial_day'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width-140 label-align"><span class="red">*</span>付款方式</label>
        <label>：</label>
        <select name="LCrmCreditApply[payment_method]" class="width-200 easyui-validatebox" data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['pay_method'] as $key => $val) { ?>
                <option
                        value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $apply['payment_method'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="width-240 label-align"><span class="red">*</span>付款条件</label>
        <label>：</label>
        <select name="LCrmCreditApply[payment_type]" class="width-100 easyui-validatebox payment_type" data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['settlement_type'] as $key => $val) { ?>
                <option value="<?= $key ?>" <?= $apply['payment_type'] == $key ? 'selected' : null ?>><?= $val ?></option>
            <?php } ?>
        </select>
        <select name="LCrmCreditApply[payment_clause]" style="width:96px;" class="easyui-validatebox payment_clause" data-options="required:true">
            <?php foreach ($downList['settlement'] as $key => $val) { ?>
                <option value="<?= $val['bnt_code'] ?>" <?= $val['bnt_code'] == $apply['payment_clause'] ? 'selected' : null ?>><?= $val['new_name'] ?></option>
            <?php } ?>
        </select>
        <span class="payment_clause_day_span">
            每月
            <select name="LCrmCreditApply[payment_clause_day]" class="width-100 easyui-validatebox payment_clause_day" data-options="required:true">
                <?php for($i=1;$i<=30;$i++){ ?>
                    <option value="<?= $i ?>" <?= $i == $apply['payment_clause_day'] ? 'selected' : null ?>><?= $i ?></option>
                <?php } ?>
            </select>
            日
        </span>

    </div>
    <div class="mb-10">
        <label class="width-140 label-align"><span class="red">*</span>付款日</label>
        <label>：</label>
        <select name="LCrmCreditApply[pay_day]" class="width-200 value-align easyui-validatebox" data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['pay_day'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $apply['pay_day'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="width-240 label-align"><span class="red">*</span>预估月交易额</label>
        <label>：</label>
        <input type="text" class="width-200 easyui-validatebox" data-options="required:true,validType:['decimal[17,2]','noZero']" name="LCrmCreditApply[volume_trade]" maxlength="20" value="<?= !empty($apply['volume_trade'])?bcsub($apply['volume_trade'],0,2):'' ?>">
    </div>
    <div class="mb-10 volume_trade">
    <?php if(isset($apply['limit'])){ ?>
        <?php foreach ($apply['limit'] as $kl=>$vl){ ?>
            <label class="<?= $kl != '0'?'width-240':'width-140' ?> label-align"><span class="red">*</span><?= $vl['creditType'] ?>申请额度</label>
            <label>：</label>
            <input type="hidden" name="LCrmCreditLimit[<?= $kl ?>][l_limit_id]" value="<?= $apply['limit'][$kl]['l_limit_id'] ?>">
            <input type="hidden" name="LCrmCreditLimit[<?= $kl ?>][credit_type]" value="<?= $apply['limit'][$kl]['credit_type'] ?>">
            <input type="text" class="width-200 value-align easyui-validatebox" data-options="required:true,validType:['decimal[17,2]','noZero']" name="LCrmCreditLimit[<?= $kl ?>][credit_limit]" value="<?= $vl['credit_limit'] ?>" maxlength="20">
        <?php } ?>
    <?php }else{ ?>
        <label class="width-140 label-align"><span class="red">*</span>申请额度</label>
        <label>：</label>
        <input type="hidden" name="LCrmCreditLimit[0][credit_type]" value="63">
        <input type="text" class="width-200 value-align easyui-validatebox" data-options="required:true,validType:['decimal[17,2]','noZero']" name="LCrmCreditLimit[0][credit_limit]" maxlength="20">
    <?php } ?>
    </div>

    <div class="mb-10">
        <label class="width-140 label-align vertical-top">备注</label>
        <label class="vertical-top">：</label>
        <textarea  style="width:662px;" name="LCrmCreditApply[apply_remark]" id="remark" cols="5" rows="3" maxlength="200" placeholder="最多输入200个字"><?= $apply['apply_remark'] ?></textarea>
    </div>
</div>

<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">客户信用相关信息</a>
</h2>
<div class="ml-10">
<div class="mb-10">
    <label class="width-140 label-align">公司全称</label>
    <label>：</label>
    <span class="width-200 value-align cust_name"><?= $model['cust_sname'] ?></span>
    <label class="width-240 label-align">客户简称</label>
    <label>：</label>
    <span class="width-200 value-align cust_shortname"><?= $model['cust_shortname'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">税籍编码</label>
    <label>：</label>
    <span class="width-200 value-align cust_tax_code"><?= $model['cust_tax_code'] ?></span>
    <label class="width-240 label-align">客户经理人</label>
    <label>：</label>
    <span class="width-200 value-align staff_name"><?= $model['staff_name'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">交易法人</label>
    <label>：</label>
    <span class="width-200 value-align legal_person"><?= $model['company_name'] ?></span>
    <label class="width-240 label-align">营销区域</label>
    <label>：</label>
    <span class="width-200 value-align cust_salearea"><?= $model['csarea_name'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">客户类型</label>
    <label>：</label>
    <span class="width-200 value-align cust_type"><?= $model['cust_type'] ?></span>
    <label class="width-240 label-align">客户等级</label>
    <label>：</label>
    <span class="width-200 value-align cust_level"><?= $model['cust_level'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">公司电话</label>
    <label>：</label>
    <span class="width-200 value-align cust_tel1"><?= $model['cust_tel1'] ?></span>
    <label class="width-240 label-align">主要联系人</label>
    <label>：</label>
    <span class="width-200 value-align cust_contacts"><?= $model['cust_contacts'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">联系电话</label>
    <label>：</label>
    <span class="width-200 value-align cust_tel2"><?= $model['cust_tel2'] ?></span>
    <label class="width-240 label-align" for="">注册资金</label>
    <label>：</label>
    <span class="width-200 value-align cust_regfunds"><?= $model['cust_regfunds'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">注册货币</label>
    <label>：</label>
    <span class="width-200 value-align member_regcurr"><?= $model['regcurr'] ?></span>
    <label class="width-240 label-align">实收资本</label>
    <label>：</label>
    <input type="text" name="CrmCustomerInfo[official_receipts]" class="width-100 value-align easyui-validatebox receipts" data-options="validType:'positive'" value="<?= $model['official_receipts'] ?>" maxlength="15">
    <select name="CrmCustomerInfo[official_receipts_cur]" class="value-align receipts_currency" style="width:96px;">
        <?php foreach ($downList['currency'] as $key => $val){ ?>
            <option value="<?= $val['bsp_id'] ?>" <?= !$model['official_receipts_cur'] == null?($model['official_receipts_cur'] == $val['bsp_id']?'selected':null):($val['bsp_id'] =='100091'?'selected':null) ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-10">
    <label class="width-140 label-align vertical-top"><span class="red">*</span>主要经营项目</label>
    <label class="vertical-top">：</label>
    <textarea rows="3" style="width:662px;"  name="CrmCustomerInfo[member_businessarea]" class="member_businessarea  value-align easyui-validatebox" data-options="required:true" maxlength="200"><?= $model['member_businessarea'] ?></textarea>
<!--    <span class="red surplus">--><?//= isset($model['member_businessarea'])?strlen($model['member_businessarea']):'0'; ?><!--/200</span>-->
</div>
<div class="mb-10">
    <label class="width-140 label-align">母公司</label>
    <label>：</label>
    <input class="width-200 value-align cust_parentcomp" type="text" name="CrmCustomerInfo[cust_parentcomp]" value="<?= $model['cust_parentcomp'] ?>" maxlength="120" placeholder="如存在,请务必填写">
    <label class="width-240 label-align total_investment_label" for="">投资总额</label>
    <label>：</label>
    <input class="width-100 value-align easyui-validatebox total_investment" data-options="validType:['positive']" type="text" name="CrmCustomerInfo[total_investment]" value="<?= $model['total_investment'] ?>" maxlength="15">
    <select name="CrmCustomerInfo[total_investment_cur]" class="value-align investment_currency" style="width:96px;">
        <?php foreach ($downList['currency'] as $key => $val){ ?>
            <option value="<?= $val['bsp_id'] ?>" <?= !$model['total_investment_cur'] == null?($model['total_investment_cur'] == $val['bsp_id']?'selected':null):($val['bsp_id'] =='100091'?'selected':null) ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-10">
    <label class="width-140 label-align shareholding_ratio_label" for="">持股比例</label>
    <label>：</label>
    <input class="width-200 value-align easyui-validatebox shareholding_ratio" data-options="validType:'two_percent'" type="text" name="CrmCustomerInfo[shareholding_ratio]" value="<?= !empty($model['shareholding_ratio'])?sprintf('%.2f',$model['shareholding_ratio']):'' ?>" maxlength="8">&nbsp;%
</div>
<h2 class="head-three">
    <span class="ml-10">客户联系方式</span>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('contacts_table')"></i>-->
</h2>
<div class="mb-10 overflow-auto">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th><span class="red">*</span>姓名</th>
            <th>电子邮箱</th>
            <th><span class="red">*</span>电话(手机)</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="contacts_table" class="select_table">
            <?php if(!empty($apply['contact'])){ ?>
                <?php foreach($apply['contact'] as $key1 => $val1){ ?>
                    <tr>
                        <td><?= $key1+1 ?></td>
                        <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustomerPersion[<?= $key1 ?>][ccper_name]" placeholder="请输入" maxlength="20" value="<?= $val1['ccper_name'] ?>"></td>
                        <td><input class="text-center easyui-validatebox" data-options="validType:'email'" type="text"  name="CrmCustomerPersion[<?= $key1 ?>][ccper_mail]" placeholder="请输入" maxlength="80" value="<?= $val1['ccper_mail'] ?>"></td>
                        <td><input class="text-center easyui-validatebox" data-options="validType:'tel_mobile'" type="text"  name="CrmCustomerPersion[<?= $key1 ?>][ccper_mobile]" placeholder="请输入" maxlength="20" value="<?= $val1['ccper_mobile'] ?>"></td>
                        <td><input class="text-center" type="text"  name="CrmCustomerPersion[<?= $key1 ?>][ccper_remark]" placeholder="请输入" maxlength="160" value="<?= $val1['ccper_remark'] ?>"></td>
                        <td>
                            <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'contacts_table')"><span class="icon-remove-sign"></span></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
    <a onclick="add_contacts()" class="float-right mt-10"><i class="icon-plus-sign ml-4"></i>添加行</a>
</div>
<h2 class="head-three">
    <span class="ml-10">营业额</span>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('turnover_table')"></i>-->
</h2>
<div class="mb-10 overflow-auto">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th><span class="red">*</span>币别</th>
            <th><span class="red">*</span><?= date('Y',time())-1 ?><input type="hidden" value="<?= date('Y',time())-1 ?>" name="CrmTurnover[year][]"></th>
            <th><?= date('Y',time())-2 ?><input type="hidden" value="<?= date('Y',time())-2 ?>" name="CrmTurnover[year][]"></th>
            <th><?= date('Y',time())-3 ?><input type="hidden" value="<?= date('Y',time())-3 ?>" name="CrmTurnover[year][]"></th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="turnover_table" class="select_table">
        <?php if(!empty($apply['turnover'])){ ?>
            <?php foreach($apply['turnover'] as $key2 => $val2){ ?>
                <tr>
                    <td>1</td>
                    <td>
                        <select name="CrmTurnover[ct][0][currency]" class="width-200 value-align easyui-validatebox"  data-options="required:true">
                            <?php foreach ($downList['currency'] as $k => $v){ ?>
                                <option value="<?= $v['bsp_id'] ?>" <?= !$key2 == null?($key2 == $v['bsp_id']?'selected':null):($v['bsp_id'] =='100091'?'selected':null) ?>><?= $v['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>
<!--                        <input class="text-center" type="text"  name="CrmTurnover[ct][--><?//= $a ?><!--][currency]" placeholder="请输入" maxlength="20" value="--><?//= $key ?><!--">-->
                    </td>
                    <td><input class="text-center easyui-validatebox" data-options="required:true,validType:['decimal[17,2]','noZero']" type="text"  name="CrmTurnover[ct][0][turnover][]" placeholder="请输入" maxlength="20" value="<?= $val2[date('Y',time())-1] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:['decimal[17,2]','noZero']" type="text"  name="CrmTurnover[ct][0][turnover][]" placeholder="请输入" maxlength="20" value="<?= $val2[date('Y',time())-2] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:['decimal[17,2]','noZero']" type="text"  name="CrmTurnover[ct][0][turnover][]" placeholder="请输入" maxlength="20" value="<?= $val2[date('Y',time())-3] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a>
                    </td>
                </tr>
            <?php   } ?>
        <?php }else{ ?>
            <tr>
                <td>1</td>
                <td>
                    <select name="CrmTurnover[ct][0][currency]" class="width-200 value-align easyui-validatebox"  data-options="required:true">
                        <?php foreach ($downList['currency'] as $k => $v){ ?>
                            <option value="<?= $v['bsp_id'] ?>" <?= $v['bsp_id'] =='100091'?'selected':null ?>><?= $v['bsp_svalue'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><input class="text-center easyui-validatebox" data-options="required:true,validType:['decimal[17,2]','noZero']" type="text"  name="CrmTurnover[ct][0][turnover][]" placeholder="请输入" maxlength="20" value=""></td>
                <td><input class="text-center easyui-validatebox" data-options="validType:['decimal[17,2]','noZero']" type="text"  name="CrmTurnover[ct][0][turnover][]" placeholder="请输入" maxlength="20" value=""></td>
                <td><input class="text-center easyui-validatebox" data-options="validType:['decimal[17,2]','noZero']" type="text"  name="CrmTurnover[ct][0][turnover][]" placeholder="请输入" maxlength="20" value=""></td>
                <td>
                    <a onclick="reset(this)"><span class="icon-refresh"></span></a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<!--    <a onclick="add_turnover()" class="float-right mt-10"><i class="icon-plus-sign ml-4"></i>添加行</a>-->
</div>
<h2 class="head-three">
    <span class="ml-10">子公司及关联公司</span>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('linkcomp_table')"></i>-->
</h2>
<div class="mb-10 overflow-auto">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th><span class="red">*</span>公司名称</th>
            <th><span class="red">*</span>关联性质</th>
            <th><span class="red">*</span>投资金额</th>
            <th><span class="red">*</span>持股比例(%)</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="linkcomp_table" class="select_table">
        <?php if(!empty($apply['linkcomp'])){ ?>
            <?php foreach($apply['linkcomp'] as $key3 => $val3){ ?>
                <tr>
                    <td><?= $key3+1 ?></td>
                    <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustLinkcomp[<?= $key3 ?>][linc_name]" placeholder="请输入" maxlength="20" value="<?= $val3['linc_name'] ?>"></td>
                    <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustLinkcomp[<?= $key3 ?>][relational_character]" placeholder="请输入" maxlength="20" value="<?= $val3['relational_character'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="required:true,validType:['decimal[17,2]','noZero']" type="text"  name="CrmCustLinkcomp[<?= $key3 ?>][total_investment]" placeholder="请输入" maxlength="20" value="<?= bcsub($val3['total_investment'],0,0) ?>"></td>
                    <td><input onchange="check(this)" class="text-center easyui-validatebox" data-options="required:true,validType:['two_percent','noZero']" type="text"  name="CrmCustLinkcomp[<?= $key3 ?>][shareholding_ratio]" placeholder="请输入" maxlength="160" value="<?= bcsub($val3['shareholding_ratio'],0,2) ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'linkcomp_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <a onclick="add_linkcomp()" class="float-right mt-10"><i class="icon-plus-sign ml-4"></i>添加行</a>
</div>
<h2 class="head-three">
    <span class="ml-10">主要客户</span>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('company_table')"></i>-->
</h2>
<div class="mb-10 overflow-auto">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th><span class="red">*</span>公司名称</th>
            <th><span class="red">*</span>占营收比率(%)</th>
            <th>电话</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="company_table" class="select_table">
        <?php if(!empty($apply['custCustomer'])){ ?>
            <?php foreach($apply['custCustomer'] as $key4 => $val4){ ?>
                <tr>
                    <td><?= $key4+1 ?></td>
                    <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustCustomer[<?= $key4 ?>][cc_customer_name]" placeholder="请输入" maxlength="20" value="<?= $val4['cc_customer_name'] ?>"></td>
                    <td><input onchange="check(this)" class="text-center easyui-validatebox" type="text" data-options="required:true,validType:['two_percent','noZero']"  name="CrmCustCustomer[<?= $key4 ?>][cc_customer_ratio]" placeholder="请输入" maxlength="20" value="<?= $val4['cc_customer_ratio'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'telphone'" type="text"  name="CrmCustCustomer[<?= $key4 ?>][cc_customer_tel]" placeholder="请输入" maxlength="20" value="<?= $val4['cc_customer_tel'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCustCustomer[<?= $key4 ?>][cc_customer_remark]" placeholder="请输入" maxlength="160" value="<?= $val4['cc_customer_remark'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'company_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <a onclick="add_company()" class="float-right mt-10"><i class="icon-plus-sign ml-4"></i>添加行</a>
</div>
<h2 class="head-three">
    <span class="ml-10">主要供应商</span>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('supplier_table')"></i>-->
</h2>
<div class="mb-10 overflow-auto">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th><span class="red">*</span>公司名称</th>
            <th><span class="red">*</span>付款条件</th>
            <th>电话</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="supplier_table" class="select_table">
        <?php if(!empty($apply['supplier'])){ ?>
            <?php foreach($apply['supplier'] as $key5 => $val5){ ?>
                <tr>
                    <td><?= $key5+1 ?></td>
                    <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustSupplier[<?= $key5 ?>][cc_customer_name]" placeholder="请输入" maxlength="20" value="<?= $val5['cc_customer_name'] ?>"></td>
                    <td>
                        <select name="CrmCustSupplier[<?= $key5 ?>][payment_clause]" class="width-200 value-align easyui-validatebox"  data-options="required:true">
                            <option>请选择...</option>
                            <?php foreach ($downList["settlement"] as $k => $v){ ?>
                                <option value="<?= $v['bnt_code'] ?>" <?= $val5['payment_clause'] == $v['bnt_code']?'selected':null; ?>><?= $v['bnt_sname'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'telphone'" type="text"  name="CrmCustSupplier[<?= $key5 ?>][cc_customer_tel]" placeholder="请输入" maxlength="20" value="<?= $val5['cc_customer_tel'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCustSupplier[<?= $key5 ?>][cc_customer_remark]" placeholder="请输入" maxlength="160" value="<?= $val5['cc_customer_remark'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'supplier_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <a onclick="add_supplier()" class="float-right mt-10"><i class="icon-plus-sign ml-4"></i>添加行</a>
</div>
<h2 class="head-three">
    <span class="ml-10">主要往来银行</span>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('bank_table')"></i>-->
</h2>
<div class="mb-10 overflow-auto">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th><span class="red">*</span>银行名称</th>
            <th><span class="red">*</span>账号</th>
            <th><span class="red">*</span>往来项目</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="bank_table" class="select_table">
        <?php if(!empty($apply['bank'])){ ?>
            <?php foreach($apply['bank'] as $key6 => $val6){ ?>
                <tr>
                    <td><?= $key6+1 ?></td>
                    <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCorrespondentBank[<?= $key6 ?>][bank_name]" placeholder="请输入" maxlength="50" value="<?= $val6['bank_name'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="required:true,validType:'int'" type="text"  name="CrmCorrespondentBank[<?= $key6 ?>][account_num]" placeholder="请输入" maxlength="50" value="<?= $val6['account_num'] ?>"></td>
                    <td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCorrespondentBank[<?= $key6 ?>][curremt_project]" placeholder="请输入" maxlength="80" value="<?= $val6['curremt_project'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCorrespondentBank[<?= $key6 ?>][remark]" placeholder="请输入" maxlength="200" value="<?= $val6['remark'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'bank_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <a onclick="add_bank()" class="float-right mt-10"><i class="icon-plus-sign ml-4"></i>添加行</a>
</div>
<h2 class="head-three">
    <span class="ml-10">附件</span>
</h2>
<div class="mb-10">
    <div class="inline-block width-890 mb-10">
        <label for="" class="width-100 label-align float-left"><span class="red">*</span>客户信息签字档</label>
        <?php foreach ($apply['file1'] as $key7 => $val7) { ?>
            <span style="text-align: center;" id="message_update_<?= $key7+100 ?>" class="mess">
                <a target="_blank" class="text-center width-150 color-w"
                           href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cca/credit/<?= $val7['date_file'] ?>/<?= $val7['file_new'] ?>"><?= $val7['file_old'] ?></a>
            </span>
        <?php } ?>
        <div class="uploader-demo">
            <div id="fileList1" class="uploader-list"></div>
            <div id="filePicker1">选择文件</div>
        </div>
        <span style="color:#999; height:25px;line-height:25px;margin-left:10px;clear:both;">格式：JPG、PNG、TIF、PDf、压缩档，文件最大为5M</span>
    </div>
    <div style="clear: both">
        <span style="color: #10AEFF;">其他附件：组织机构代码、最近三年度会计师审核通过的财务报表(资产负债表、利润表、现金流量表(若客户为上市公司提供股票代码即可))</span>
    </div>
    <div class="file-border">
        <div class="uploader-demo">
            <div id="fileList2" class="uploader-list" style="max-width:850px;"></div>
            <div id="filePicker2">选择文件</div>
        </div>
        <div style="height:10px;clear:both;"></div>
        <div style="padding-left:10px;">
<!--            <input type="file" multiple="multiple" onchange="change(this)" name="Upload[file][]" value=""  class="easyui-validatebox" data-options="required:true">-->
            <?php foreach ($apply['file2'] as $key8 => $val8) { ?>
            <span style="text-align: center;" id="upload_update_<?= $key8+100 ?>">
                <span style="color:#f00;" class="cancle" onclick="cancle('upload_update_<?= $key8+100 ?>')">X</span>
                <a target="_blank" class="text-center width-150 color-w"
                       href="<?=  Yii::$app->ftpPath['httpIP'] ?>/cca/credit/<?= $val8['date_file'] ?>/<?= $val8['file_new'] ?>"><?= $val8['file_old'] ?></a>
                <input type="hidden" name="upload[<?= $key8+100 ?>][file_new]" value="<?= $val8['file_new'] ?>">
                <input type="hidden" name="upload[<?= $key8+100 ?>][file_old]" value="<?= $val8['file_old'] ?>">
            </span>
            <?php } ?>

        </div>

    </div>
    <div>
        <span style="color:#999;">(文件格式为xls，xlsx，doc，docx，txt，png，jpg，tif，pdf，不分大小写，文件最大为5M)</span><br>
        <span style="color:#ff0000">如可附上3年度审计财务报表,批复额度可能性越高,额度越大</span>
    </div>
</div>
</div>
<div class="text-center">
    <button type="button" class="button-blue-big sub">保存</button>
    <button type="button" class="button-blue-big apply ml-40">提交</button>
    <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">返回</button>

</div>
<?php ActiveForm::end(); ?>
<script>
    var btnFlag = '';
    $(function(){
        var $list = $('#fileList1'), uploader;
        uploader = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?= Url::to("@web/Uploader.swf") ?>',
            // 文件接收服务端。
            server: '<?= Url::to("upload") ?>',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id:'#filePicker1',
                multiple:false},
            fileNumLimit:1
            // 只允许选择图片文件。
        });
        uploader.on('uploadError', function (file) {
            var $li = $('#' + file.id),
                $error = $li.find('div.error');
            // 避免重复创建
            if (!$error.length) {
                $error = $('<div class="error"></div>').appendTo($li);
            }
            $error.text('上传失败');
        });
        uploader.on("error",function(err){
            switch(err){
                case "Q_TYPE_DENIED":
                    alert("不允许的文件类型");
                    break;
            }
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on('uploadSuccess', function (file, res) {
            if(res.state=="SUCCESS") {
                var $li = $(
                        '<div id="' + file.id + '" class="file-item">' +
                        '<a>' +
                        '<div class="info">' + file.name + '</div>' +
                        '<input type="hidden" class="message_file_new" name="message[' + substr(file.id, "\_") + '][file_new]" value="' + res.title + '">' +
                        '<input type="hidden" name="message[' + substr(file.id, "\_") + '][file_old]" value="' + file.name + '">' +
                        '</div>'
                    ),
                    $img = $li.find('a');
                $list.html($li);
                uploader.makeThumb(file, function (error, href) {
                    $img.attr('href', href);
                });
                <?php if(\Yii::$app->controller->action->id == 'update'){ ?>
                    $('.mess').remove();
                <?php } ?>
            }else{
                alert('上传失败');
            }
        });


        var $list2 = $('#fileList2'), uploader2;
        uploader2 = WebUploader.create({
            // 选完文件后，是否自动上传。
            auto: true,
            // swf文件路径
            swf: '<?= Url::to("@web/Uploader.swf") ?>',
            // 文件接收服务端。
            server: '<?= Url::to("upload") ?>',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker2',
            // 只允许选择图片文件。
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader2.on('uploadSuccess', function (file, res) {
            if(res.state=="SUCCESS") {
//                $('#fileList2').css('max-width','600px');
                var $li2 = $(
                        '<div id="' + file.id + '" class="file-item thumbnail">' +
                        "<span style=\"color:#f00;\" class=\"cancle\" onclick=\"cancle('"+ file.id +"')\">X</span>" +
                        '<a>' +
                        '<div class="info">' + file.name + '</div>' +
                        '<input type="hidden" name="upload[' + substr(file.id, "\_") + '][file_new]" value="' + res.title + '">' +
                        '<input type="hidden" name="upload[' + substr(file.id, "\_") + '][file_old]" value="' + file.name + '">' +
                        '</div>'
                    ),
                $img2 = $li2.find('a');
                $list2.append($li2);
                uploader2.makeThumb(file, function (error, href) {
                    $img2.attr('href', href);
                });
            }else{
                alert('上传失败');
            }
        });

        uploader2.on('uploadError', function (file) {
            var $li2 = $('#' + file.id),
                $error = $li2.find('div.error');
            // 避免重复创建
            if (!$error.length) {
                $error = $('<div class="error"></div>').appendTo($li2);
            }
            $error.text('上传失败');
        });



        $.extend($.fn.validatebox.defaults.rules, {
            regFile: {
                validator: function(value,param){
                    return /.(jpg|png|tif|pdf|bmp|7z|rar|zip)$/.test(value);
                },
                message: '文件格式不正确'
            },
            regFile1: {
                validator: function(value,param){
                    return /.(xls|xlsx|doc|docx|txt|png|jpg|pdf)$/.test(value);
                },
                message: '文件格式不正确'
            }
        });
        payment();
        parentcomp();
        $('.cust_parentcomp').on('blur',function(){
            parentcomp();
        })
        $('.payment_type').on('change',function(){
            payment();
        })

        $('.shareholding_ratio').on('change',function () {
           if (($('.shareholding_ratio').val())==0 ||($('.shareholding_ratio').val())==100){
               layer.alert("只能输入0~100之间的数字",{icon:2});
               $('.shareholding_ratio').val("");
           }
        });

//        $('.propor1').on('change',function () {
//            alert($('.propor1').val());
//            if (($('.propor1').val())==0 ||($('.propor1').val())==100){
//                layer.alert("只能输入0~100之间的数字",{icon:2});
//                $('.propor1').val("");
//            }
//        });
        $('.verify_type').on('change',function(){
            var type = $('.verify_type').val();
            $('.volume_trade').html('');
            if(type === '63' || type === '64'){
                $.parser.parse($('.volume_trade').html(
                    '<label class="width-140 label-align"><span class="red">*</span>申请额度</label><label>&nbsp;：&nbsp;</label>'+
                    '<input type="hidden" name="LCrmCreditLimit[0][credit_type]" value="'+ type +'">'+
                    '<input type="text" class="width-200 value-align easyui-validatebox" data-options="required:true,validType:[\'decimal[17,2]\',\'noZero\']" name="LCrmCreditLimit[0][credit_limit]"  maxlength="20">'
                ))
            }
            if(type === '65'){
                $.parser.parse($('.volume_trade').html(
                    '<label class="width-140 label-align"><span class="red">*</span>申请额度</label><label>&nbsp;：&nbsp;</label>'+
                    '<input type="hidden" name="LCrmCreditLimit[0][credit_type]" value="'+ type +'">'+
                    '<input type="text" class="width-200 value-align easyui-validatebox" data-options="required:true,validType:[\'noZero\',\'decimal[17,2]\']" name="LCrmCreditLimit[0][credit_limit]"  maxlength="20">'
                ))
            }
            if(type === '66'){
                $.parser.parse($('.volume_trade').html(
                    '<label class="width-140 label-align"><span class="red">*</span>AP担保申请额度</label><label>&nbsp;：&nbsp;</label>'+
                    '<input type="hidden" name="LCrmCreditLimit[0][credit_type]" value="64">'+
                    '<input type="text" class="width-200 value-align easyui-validatebox" data-options="required:true,validType:[\'noZero\',\'decimal[17,2]\']" name="LCrmCreditLimit[0][credit_limit]"  maxlength="20">&nbsp;'+
                    '<label class="width-240 label-align"><span class="red">*</span>无担保申请额度</label><label>&nbsp;：&nbsp;</label>'+
                    '<input type="hidden" name="LCrmCreditLimit[1][credit_type]" value="65">'+
                    '<input type="text" class="width-200 value-align easyui-validatebox" data-options="required:true,validType:[\'noZero\',\'decimal[17,2]\']" name="LCrmCreditLimit[1][credit_limit]"  maxlength="20">'
                ))
            }
        });
//        $(".head-three").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });
        <?php if(\Yii::$app->controller->action->id == "credit-create"){ ?>
            $('.sub').click(function(){
                if($('#fileList1').find('div').length<=0){
                    layer.alert('请选择签字档',{icon:2,time:5000});return false;
                }
                $('.currency').attr('disabled',false);
//                $('#add-form').attr('action','<?//= Url::to(["/crm/crm-customer-info/credit-create"]) ?>//');
                if (!$('#add-form').form('validate')) {
                    $("button[type='button']").prop("disabled", false);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url:"<?= Url::to(['credit-create','id'=>$id]) ?>",
                    data:$('#add-form').serialize(),// 序列化表单值
                    async: false,
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.flag == 1) {
                            layer.alert(data.msg, {
                                icon: 1,
                                end: function () {
                                    window.location.href = data.url;
                                }
                            });
                        }
                    }
                });
            });
            $('.apply').click(function(){
                if($('#fileList1').find('div').length<=0){
                    layer.alert('请选择签字档',{icon:2,time:5000});return false;
                }
                $('.currency').attr('disabled',false);
//                $('#add-form').attr('action','<?//= Url::to(["/crm/crm-customer-info/credit-create", "is_apply" => "1"]) ?>//');
                if (!$('#add-form').form('validate')) {
                    $("button[type='button']").prop("disabled", false);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url:"<?= Url::to(['credit-create','id'=>$id,'is_apply'=>'1']) ?>",
                    data:$('#add-form').serialize(),// 序列化表单值
                    async: false,
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.flag == 1) {
                            layer.alert(data.msg, {
                                icon: 1,
                                end: function () {
                                    window.location.href = data.url;
                                }
                            });
                        }
                    }
                });
            });
        $("button[type='button']").click(function () {
            btnFlag = $(this).text();
        });
//        ajaxSubmitForm($("#add-form"));

    <?php }else{ ?>
            $('.sub').click(function(){
//                var upfile = $('.message_file_new');
//                if(upfile.length<=0){
//                    layer.alert('请选择签字档',{icon:2,time:5000});return false;
//                }
                $('.currency').attr('disabled',false);
                $('#add-form').attr('action','<?= Url::to(["/crm/crm-credit-apply/update",'id'=>$apply["l_credit_id"]]) ?>');
                if (!$('#add-form').form('validate')) {
                    $("button[type='button']").prop("disabled", false);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url:"<?= Url::to(['update','id'=>$apply["l_credit_id"]]) ?>",
                    data:$('#add-form').serialize(),// 序列化表单值
                    async: false,
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.flag == 1) {
                            layer.alert(data.msg, {
                                icon: 1,
                                end: function () {
                                    window.location.href = data.url;
                                }
                            });
                        }
                    }
                });
            });
            $('.apply').click(function(){
                $('.currency').attr('disabled',false);
//                $('#add-form').attr('action', '<?//= Url::to(["/crm/crm-credit-apply/update", "is_apply" => "1", "id" => $apply["l_credit_id"]]) ?>//');
                if (!$('#add-form').form('validate')) {
                    $("button[type='button']").prop("disabled", false);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url:"<?= Url::to(['update','id'=>$apply["l_credit_id"],'is_apply'=>'1']) ?>",
                    data:$('#add-form').serialize(),// 序列化表单值
                    async: false,
                    success: function(data) {
                        var data = JSON.parse(data);
                        if (data.flag == 1) {
                            layer.alert(data.msg, {
                                icon: 1,
                                end: function () {
                                    window.location.href = data.url;
                                }
                            });
                        }
                    }
                });
            });
//        ajaxSubmitForm($("#add-form"));


    <?php } ?>
        /*实时监听 计算总额度*/
        $(document).on("input propertychange", function() {
            var sum = 0;
            $('.money').each(function(){
                sum += $(this).val()*1;
            })
            $('.total_amount').val(sum);
        });
        /*选中表格行*/
//        $('.select_table').on('click','tr',function(){
//            $(this).toggleClass('select');
//        });
        //选择客户弹出框
        $("#select_customer").fancybox({
            padding: [],
            fitToView: false,
            width: 800,
            height: 570,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
        $(document).on('click','.select_date',function () {
            jeDate({
                dateCell: this,
                zIndex:8831,
                format: "YYYY-MM-DD",
                skinCell: "jedatedeep",
                isTime: false,
                okfun:function(elem) {
                    $(elem).change();
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun:function(elem) {
                    $(elem).change();
                }
            })
        })
    })

    function parentcomp(){
        var cust_parentcomp = $('.cust_parentcomp').val();
        if(cust_parentcomp == ''){
            $('.total_investment').attr('readonly','readonly');
            $('.shareholding_ratio').attr('readonly','readonly');
            $('.total_investment').validatebox({required:false}).val('');
            $('.shareholding_ratio').validatebox({required:false}).val('');
            $('.total_investment_label').html('投资总额');
            $('.shareholding_ratio_label').html('持股比例');
        }else{
            $('.total_investment').validatebox({required:true,validType:['positive']});
            $('.total_investment').removeAttr('readonly')
            $('.shareholding_ratio').validatebox({required:true,validType:['two_percent']});
            $('.shareholding_ratio').removeAttr('readonly');
            $('.total_investment_label').html('<span class="red">*</span>投资总额');
            $('.shareholding_ratio_label').html('<span class="red">*</span>持股比例');
        }
    }

    function payment(){
        var payment_type = $('.payment_type').val();
        if(payment_type == 0){
            $('.payment_clause_day').attr('disabled',true);
            $('.payment_clause_day_span').hide();
        }else{
            $('.payment_clause_day').attr('disabled',false);
            $('.payment_clause_day_span').show();
        }
        $.ajax({
            type: "get",
            dataType: "json",
            data: {"id": payment_type},
            url:'<?= Url::to(["/crm/crm-credit-apply/settlement"]) ?>',
            success:function(data){
                $('.payment_clause').html('');
                for(var $i=0;$i < data.length;$i++){
                    $(".payment_clause").append('<option value="'+ data[$i].bnt_code +'">'+ data[$i].new_name +'</option>')
                }
            }
        })
    }
    /*联系人新增*/
    function add_contacts() {
        var a = $("#contacts_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#contacts_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustomerPersion[' + a + '][ccper_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'email\'" type="text"  name="CrmCustomerPersion[' + a + '][ccper_mail]" placeholder="请输入" maxlength="80"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true,validType:\'tel_mobile\'" type="text"  name="CrmCustomerPersion[' + a + '][ccper_mobile]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center" type="text"  name="CrmCustomerPersion[' + a + '][ccper_remark]" placeholder="请输入" maxlength="160"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'contacts_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }

    /*营业额新增*/
    function add_turnover() {
        var a = $("#turnover_table tr").length;
        var b = a;
        b += 1;
        if(b>1) return false;
        var obj = $("#turnover_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><select name="CrmTurnover[ct][' + a + '][currency]" class="width-200 value-align easyui-validatebox" data-options="required:true">' +
            <?php foreach ($downList["currency"] as $key => $val){ ?>
            '<option value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == '100091'?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>'+
            <?php } ?>
            ' </select></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true,validType:\'price\'" type="text"  name="CrmTurnover[ct][' + a + '][turnover][]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'price\'" type="text"  name="CrmTurnover[ct][' + a + '][turnover][]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'price\'" type="text"  name="CrmTurnover[ct][' + a + '][turnover][]" placeholder="请输入" maxlength="20"></td>' +
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }

    /*子公司新增*/
    function add_linkcomp() {
        var a = $("#linkcomp_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#linkcomp_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCustLinkcomp[' + a + '][linc_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCustLinkcomp[' + a + '][relational_character]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true,validType:\'positive\'" type="text"  name="CrmCustLinkcomp[' + a + '][total_investment]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input onchange="check(this)" class=" text-center easyui-validatebox" data-options="required:true,validType:\'two_percent\'" type="text"  name="CrmCustLinkcomp[' + a + '][shareholding_ratio]" placeholder="请输入" maxlength="160"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'linkcomp_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }

    /*主要客户新增*/
    function add_company() {
        var a = $("#company_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#company_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input onchange="check(this)" class="text-center easyui-validatebox" type="text" data-options="required:true,validType:\'float\'"  name="CrmCustCustomer[' + a + '][cc_customer_ratio]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'telphone\'" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_tel]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_remark]" placeholder="请输入" maxlength="160"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'company_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }

    /*主要供应商新增*/
    function add_supplier() {
        var a = $("#supplier_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#supplier_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCustSupplier[' + a + '][cc_customer_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><select name="CrmCustSupplier[' + a + '][payment_clause]" class="width-200 value-align easyui-validatebox" data-options="required:true">' +
            '<option value="" >请选择...</option>'+
            <?php foreach ($downList["settlement"] as $key => $val){ ?>
            '<option value="<?= $val['bnt_code'] ?>" <?= $model['payment_clause'] == $val['bnt_code']?'selected':null; ?>><?= $val['bnt_sname'] ?></option>'+
            <?php } ?>
            ' </select></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'telphone\'" type="text"  name="CrmCustSupplier[' + a + '][cc_customer_tel]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center" type="text"  name="CrmCustSupplier[' + a + '][cc_customer_remark]" placeholder="请输入" maxlength="160"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'supplier_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }

    /*往来银行新增*/
    function add_bank() {
        var a = $("#bank_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#bank_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCorrespondentBank[' + a + '][bank_name]" placeholder="请输入" maxlength="50"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true,validType:\'int\'" type="text"  name="CrmCorrespondentBank[' + a + '][account_num]" placeholder="请输入" maxlength="50"></td>' +
            '<td><input class="text-center easyui-validatebox"  data-options="required:true" type="text"  name="CrmCorrespondentBank[' + a + '][curremt_project]" placeholder="请输入" maxlength="80"></td>' +
            '<td><input class="text-center" type="text"  name="CrmCorrespondentBank[' + a + '][remark]" placeholder="请输入" maxlength="200"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'bank_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }

    /*上传文件选择*/
    function change(obj) {
        var span = obj.parentNode.previousSibling.previousSibling;
        var temp = "";
        var Sys = {};
        var ua = navigator.userAgent.toLowerCase();
        var s;
        (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] : 0;
        var file_url="";
        if(Sys.ie<="6.0"){
            //ie5.5,ie6.0
            file_url = obj.value;
            span.innerHTML=substr(file_url,"\\");
        }else if(Sys.ie>="7.0"){
            //ie7,ie8
            obj.select();
            obj.blur();
            file_url = document.selection.createRange().text;
            span.innerHTML=substr(file_url,"\\");
        }else{
            for (var i = 0; i < obj.files.length; i++) {
                if (i == 0) {
                    temp = obj.files[i].name;
                } else {
                    temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
                }
                span.innerHTML = temp;
            }
        }
    }
    function readFileFirefox(fileBrowser) {
        try {
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
        }
        catch (e) {
            alert('无法访问本地文件，由于浏览器安全设置。为了克服这一点，请按照下列步骤操作：(1)在地址栏输入"about:config";(2) 右键点击并选择 New->Boolean; (3) 输入"signed.applets.codebase_principal_support" （不含引号）作为一个新的首选项的名称;(4) 点击OK并试着重新加载文件');
            return;
        }
        var fileName=fileBrowser.value; //这一步就能得到客户端完整路径。下面的是否判断的太复杂，还有下面得到ie的也很复杂。
        var file = Components.classes["@mozilla.org/file/local;1"]
            .createInstance(Components.interfaces.nsILocalFile);
        try {
            // Back slashes for windows
            file.initWithPath( fileName.replace(/\//g, "\\\\") );
        }
        catch(e) {
            if (e.result!=Components.results.NS_ERROR_FILE_UNRECOGNIZED_PATH) throw e;
            alert("File '" + fileName + "' cannot be loaded: relative paths are not allowed. Please provide an absolute path to this file.");
            return;
        }
        if ( file.exists() == false ) {
            alert("File '" + fileName + "' not found.");
            return;
        }
        return file.path;
    }


    //根据不同浏览器获取路径
    function getvl(obj){
//判断浏览器
        var Sys = {};
        var ua = navigator.userAgent.toLowerCase();
        var s;
        (s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
            (s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
                (s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
                    (s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
                        (s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
        var file_url="";
        console.log(obj.length);
        if(Sys.ie<="6.0"){
            //ie5.5,ie6.0
            file_url = obj.value;
        }else if(Sys.ie>="7.0"){
            //ie7,ie8
            obj.select();
            obj.blur();
            file_url = document.selection.createRange().text;
        }else if(Sys.firefox){
            //fx
            //file_url = document.getElementById("file").files[0].getAsDataURL();//获取的路径为FF识别的加密字符串
            file_url = readFileFirefox(obj);
        }else if(Sys.chrome){
            file_url = obj.value;
        }
        //alert(file_url);
        var span = obj.parentNode.previousSibling.previousSibling;
        span.innerHTML=substr(file_url);
    }

    /*截取最后一个反斜杠后面的字符*/
    function substr(str,flag){
        var index = str .lastIndexOf(flag);
        str  = str .substring(index + 1, str.length);
        return str;
    }

    /*重置表格*/
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
    }
    function check(object) {
        if (($(object).val())==0 ||($(object).val())==100){
                layer.alert("只能输入0~100之间的数字",{icon:2});
            $(object).val("");
            }
    }
    /*删除行*/
    function vacc_del(obj,id) {
        var a = $("#"+ id +" tr").length;
//        if(a != 1){
//            $(obj).parents("tr").remove();
//        }
        $(obj).parents("tr").remove();
        for(var i=0;i<a;i++){
            $('#'+id).find('tr').eq(i).find('td:first').text(i+1);
        }
    }

    function cancle(str){
        $('#'+str).remove();
    }
</script>

