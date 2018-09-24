<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCreditApply */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .width-30 {
        width: 30px;
    }
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
    .width-300 {
        width: 300px;
    }
    .ml-10 {
        margin-left: 10px;
    }
    .mt-15 {
        margin-top: 15px;
    }
</style>
<style>
    .select{
        background-color: #87CEFF !important;
    }
    .width-180 {
        width: 125px;
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
<div>
    <div class="mb-10">
        <input type="hidden" name="CrmCreditApply[cust_id]" class="cust_id" value="<?= $model['cust_id'] ?>">
        <label class="width-140 label-align">客户代码</label>
        <label>:</label>
        <span class="width-200 value-align"><?= $model['apply_code'] ?></span>
        <label class="width-300 label-align"><span class="red">*</span>币别</label>
        <label>:</label>
        <select name="CrmCreditApply[currency]" class="width-200 value-align">
            <option value="">请选择...</option>
            <?php foreach ($downList['currency'] as $key => $val){ ?>
                <option value="<?= $val['bsp_id'] ?>" <?= $model['currency'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="width-140 label-align">申请总额度</label>
        <label>:</label>
        <span type="text" class="width-200 value-align easyui-validatebox total_amount"><?= $model['total_amount'] ?></span>
        <label class="width-300 label-align"><span class="red">*</span>审核流选择</label><!-- 额度类型改为审核流选择？？ -->
        <label>:</label>
        <select name="CrmCreditApply[verify_type]" class="width-200 value-align verify_type easyui-validatebox" data-options="required:true">
            <option value="">请选择...</option>
            <?php foreach ($downList['verifyType'] as $key => $val){ ?>
                <option value="<?= $val['business_type_id'] ?>" <?= !empty($model['verify_type'])&&$model['verify_type'] == $val['business_type_id']?'selected':null; ?>><?= $val['business_value'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <table class="table">
            <thead>
            <tr>
                <th class="width-30">序号</th>
                <th class="width-80">信用额度类型</th>
                <th class="width-80">申请额度</th>
                <th class="width-80">付款条件</th>
                <th class="width-80">付款方式</th>
                <th class="width-80">起算日</th>
                <th class="width-80">付款日</th>
                <th class="width-150">备注</th>
                <th class="width-80">有效期至</th>
                <th class="width-80">操作</th>
            </tr>
            </thead>
            <tbody id="limit_table">
            <?php if(!empty($model['limit'])){ ?>
                <?php foreach ($model['limit'] as $k => $value){ ?>
                    <tr>
                        <td><?= $k+1 ?><input type="hidden" class="width-150" name="CrmCreditLimit[<?= $k ?>][laid]" value="<?= $value['laid'] ?>"</td>
                        <td>
                            <select name="CrmCreditLimit[<?= $k ?>][credit_type]" class="easyui-validatebox" data-options='required:"true",validType:["credit_type"],delay:10000000,validateOnBlur:true'>
                                <option value="">请选择...</option>
                                <?php foreach ($downList['credit_type'] as $key => $val) { ?>
                                    <option
                                        value="<?= $val['id'] ?>" <?= $val['id'] == $value['credit_type_id'] ? 'selected' : null ?>><?= $val['credit_name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="CrmCreditLimit[<?= $k ?>][credit_limit]" class="width-80 money easyui-validatebox" data-options="required:'true',validType:'two_decimal'" value="<?= $value['credit_limit'] ?>">
                        </td>
                        <td>
                            <select name="CrmCreditLimit[<?= $k ?>][payment_clause]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['settlement'] as $key => $val) { ?>
                                    <option
                                        value="<?= $val['bnt_id'] ?>" <?= $val['bnt_id'] == $value['pay_clause_id'] ? 'selected' : null ?>><?= $val['bnt_sname'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="CrmCreditLimit[<?= $k ?>][payment_method]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['pay_method'] as $key => $val) { ?>
                                    <option
                                        value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $value['pay_method_id'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="CrmCreditLimit[<?= $k ?>][initial_day]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['initial_day'] as $key => $val) { ?>
                                    <option
                                        value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $value['initial_id'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="CrmCreditLimit[<?= $k ?>][pay_day]">
                                <option value="">请选择...</option>
                                <?php foreach ($downList['pay_day'] as $key => $val) { ?>
                                    <option
                                        value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $value['pay_id'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="width-150" name="CrmCreditLimit[<?= $k ?>][remark]" value="<?= $value['remark'] ?>"
                        </td>
                        <td>
                            <input type="text" class="width-80 select_date" name="CrmCreditLimit[<?= $k ?>][validity]" value="<?= $value['validity'] ?>">
                        </td>
                        <td>
                            <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="delete_del(this,'limit_table')"><span class="icon-remove-sign"></span></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                    <td>1</td>
                    <td>
                        <select name="CrmCreditLimit[0][credit_type]" class="easyui-validatebox" data-options='required:"true",validType:["credit_type"],delay:10000000,validateOnBlur:true'>
                            <option value="">请选择...</option>
                            <?php foreach ($downList['credit_type'] as $key => $val) { ?>
                                <option
                                    value="<?= $val['id'] ?>" <?= $val['id'] == $model['credit_type'] ? 'selected' : null ?>><?= $val['credit_name'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="CrmCreditLimit[0][credit_limit]" class="width-80 money easyui-validatebox" data-options="required:'true',validType:'two_decimal'">
                    </td>
                    <td>
                        <select name="CrmCreditLimit[0][payment_clause]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['settlement'] as $key => $val) { ?>
                                <option
                                    value="<?= $val['bnt_id'] ?>" <?= $val['bnt_id'] == $model['payment_clause'] ? 'selected' : null ?>><?= $val['bnt_sname'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select name="CrmCreditLimit[0][payment_method]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['pay_method'] as $key => $val) { ?>
                                <option
                                    value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $model['payment_method'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select name="CrmCreditLimit[0][initial_day]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['initial_day'] as $key => $val) { ?>
                                <option
                                    value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $model['initial_day'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <select name="CrmCreditLimit[0][pay_day]">
                            <option value="">请选择...</option>
                            <?php foreach ($downList['pay_day'] as $key => $val) { ?>
                                <option
                                    value="<?= $val['bsp_id'] ?>" <?= $val['bsp_id'] == $model['pay_day'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" class="width-150" name="CrmCreditLimit[0][remark]">
                    </td>
                    <td>
                        <input type="text" class="width-80 select_date" name="CrmCreditLimit[0][validity]">
                    </td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="delete_del(this,'limit_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>

            </tbody>
        </table>
        <div class="mt-20">
            <button type="button" class="button-blue-big" onclick="add_table()">添加行</button>
        </div>
    </div>
</div>

<!--<div class="mb-10">-->
<!--    <label class="width-80 vertical-top">备注</label>-->
<!--    <textarea name="CrmCreditApply[apply_remark]" rows="3" class="width-549" maxlength="200">--><?//= $model['apply_remark'] ?><!--</textarea>-->
<!--</div>-->
<h2 class="head-second color-1f7ed0">
    <i class="icon-caret-down"></i>
    <a href="javascript:void(0)">客户信用相关信息</a>
</h2>
<div class="ml-10">
<div class="mb-10">
    <label class="width-140 label-align">客户全称</label>
    <label>:</label>
    <span class="width-200 value-align cust_name"><?= $model['cust_sname'] ?></span>
    <label class="width-300 label-align">客户简称</label>
    <label>:</label>
    <span class="width-200 value-align cust_shortname"><?= $model['cust_shortname'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">税籍编码</label>
    <label>:</label>
    <span type="text" class="width-200 value-align cust_tax_code"><?= $model['cust_tax_code'] ?></span>
    <label class="width-300 label-align">客户经理人</label>
    <label>:</label>
    <span class="width-200 value-align staff_name"><?= $model['manager']['staff_name'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">交易法人</label>
    <label>:</label>
    <span class="width-200 value-align legal_person"><?= $model['company_name'] ?></span>
    <label class="width-300 label-align">营销区域</label>
    <label>:</label>
    <span class="width-200 value-align cust_salearea" disabled="disabled">
        <?php foreach ($downList['salearea'] as $key => $val){ ?>
            <?= $model['csarea']['csarea_id'] == $val['csarea_id'] ? $val['csarea_name'] : null; ?>
        <?php } ?>
    </span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">客户类型</label>
    <label>:</label>
    <span class="width-200 value-align cust_type" disabled="disabled">
        <?php foreach ($downList['customerType'] as $key => $val) { ?>
            <?= $model['cust_type']['bsp_id'] == $val['bsp_id'] ? $val['bsp_svalue'] : null; ?>
        <?php } ?>
    </span>
    <label class="width-300 label-align">客户等级</label>
    <label>:</label>
    <span class="width-200 value-align cust_level" disabled="disabled">
        <?php foreach ($downList['custLevel'] as $key => $val) { ?>
            <?= $model['cust_level']['bsp_id'] == $val['bsp_id'] ? $val['bsp_svalue'] : null; ?>
        <?php } ?>
    </span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">公司电话</label>
    <label>:</label>
    <span class="width-200 value-align cust_tel1"><?= $model['cust_tel1'] ?></span>
    <label class="width-300 label-align">主要联系人</label>
    <label>:</label>
    <span class="width-200 value-align cust_contacts"><?= $model['cust_contacts'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">联系电话</label>
    <label>:</label>
    <span class="width-200 value-align cust_tel2"><?= $model['cust_tel2'] ?></span>
    <label class="width-300 label-align" for="">注册资金</label>
    <label>:</label>
    <span class="width-200 value-align cust_regfunds"><?= $model['cust_regfunds'] ?></span>
</div>
<div class="mb-10">
    <label class="width-140 label-align">注册货币</label>
    <label>:</label>
    <span type="text" class="width-200 value-align member_regcurr"><?= $model['regcurr'] ?></span>
    <label class="width-300 label-align">实收资本</label>
    <label>:</label>
    <input type="text" name="CrmCustomerInfo[official_receipts]" class="width-100 value-align easyui-validatebox receipts" data-options="validType:'two_decimal'" value="<?= $model['official_receipts'] ?>">
    <select name="CrmCustomerInfo[official_receipts_cur]" class="width-100 value-align receipts_currency">
        <?php foreach ($downList['currency'] as $key => $val){ ?>
            <option value="<?= $val['bsp_id'] ?>" <?= $model['official_receipts_cur'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-10">
    <label class="width-140 label-align vertical-top">主要经营项目</label>
    <label class="vertical-top">:</label>
    <textarea rows="3" style="width:708px;font-size:12px;" onkeyup="surplus(this,200);" name="CrmCustomerInfo[member_businessarea]" class="member_businessarea  value-align" maxlength="180" placeholder="主营项依照营业执照的经营范围填写，最多输入两百个字"><?= $model['member_businessarea'] ?></textarea>
<!--    <span class="red surplus">--><?//= isset($model['member_businessarea'])?strlen($model['member_businessarea']):'0'; ?><!--/200</span>-->
</div>
<div class="mb-10">
    <label class="width-140 label-align">母公司</label>
    <label>:</label>
    <input class="width-200 value-align cust_parentcomp" type="text" name="CrmCustomerInfo[cust_parentcomp]" value="<?= $model['cust_parentcomp'] ?>" maxlength="120" placeholder="如存在，请务必填写">
    <label class="width-300 label-align" for="">投资总额</label>
    <label>:</label>
    <input class="width-100 value-align easyui-validatebox total_investment" data-options="validType:'two_decimal'" type="text" name="CrmCustomerInfo[total_investment]" value="<?= $model['total_investment'] ?>" maxlength="15">
    <select name="CrmCustomerInfo[total_investment_cur]" class="width-100 value-align investment_currency">
        <?php foreach ($downList['currency'] as $key => $val){ ?>
            <option value="<?= $val['bsp_id'] ?>" <?= $model['total_investment_cur'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-10">
    <label class="width-140 label-align" for="">持股比率</label>
    <label>:</label>
    <input class="width-200 value-align easyui-validatebox shareholding_ratio" data-options="validType:'two_percent'" type="text" name="CrmCustomerInfo[shareholding_ratio]" value="<?= $model['shareholding_ratio'] ?>" maxlength="15">&nbsp;%
</div>
<h2 class="head-three">
    <span class="ml-10">客户联系方式</span>
    <i class="icon-plus-sign ml-4" onclick="add_contacts()"></i>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('contacts_table')"></i>-->
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th>姓名</th>
            <th>邮箱</th>
            <th>电话（手机）</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="contacts_table" class="select_table">
            <?php if(!empty($model['contact'])){ ?>
                <?php foreach($model['contact'] as $key => $val){ ?>
                    <tr>
                        <td><?= $key+1 ?></td>
                        <td><input class="text-center" type="text"  name="CrmCustomerPersion[<?= $key ?>][ccper_name]" placeholder="请输入" maxlength="20" value="<?= $val['ccper_name'] ?>"></td>
                        <td><input class="text-center easyui-validatebox" data-options="validType:'mobile'" type="text"  name="CrmCustomerPersion[<?= $key ?>][ccper_mobile]" placeholder="请输入" maxlength="20" value="<?= $val['ccper_mobile'] ?>"></td>
                        <td><input class="text-center easyui-validatebox" data-options="validType:'email'" type="text"  name="CrmCustomerPersion[<?= $key ?>][ccper_mail]" placeholder="请输入" maxlength="20" value="<?= $val['ccper_mail'] ?>"></td>
                        <td><input class="text-center" type="text"  name="CrmCustomerPersion[<?= $key ?>][ccper_remark]" placeholder="请输入" maxlength="160" value="<?= $val['ccper_remark'] ?>"></td>
                        <td>
                            <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'contacts_table')"><span class="icon-remove-sign"></span></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>
<h2 class="head-three">
    <span class="ml-10">营业额</span>
    <i class="icon-plus-sign ml-4" onclick="add_turnover()"></i>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('turnover_table')"></i>-->
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th>币别</th>
            <th><?= date('Y',time())-1 ?><input type="hidden" value="<?= date('Y',time())-1 ?>" name="CrmTurnover[year][]"></th>
            <th><?= date('Y',time())-2 ?><input type="hidden" value="<?= date('Y',time())-2 ?>" name="CrmTurnover[year][]"></th>
            <th><?= date('Y',time())-3 ?><input type="hidden" value="<?= date('Y',time())-3 ?>" name="CrmTurnover[year][]"></th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="turnover_table" class="select_table">
        <?php if(!empty($model['turnover'])){ $a=0;?>
            <?php foreach($model['turnover'] as $key => $val){ ?>
                <tr>
                    <td><?= $a+1 ?></td>
                    <td><input class="text-center" type="text"  name="CrmTurnover[ct][<?= $a ?>][currency]" placeholder="请输入" maxlength="20" value="<?= $key ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmTurnover[ct][<?= $a ?>][turnover][]" placeholder="请输入" maxlength="20" value="<?= $val[date('Y',time())-1] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'price'" type="text"  name="CrmTurnover[ct][<?= $a ?>][turnover][]" placeholder="请输入" maxlength="20" value="<?= $val[date('Y',time())-2] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'price'" type="text"  name="CrmTurnover[ct][<?= $a ?>][turnover][]" placeholder="请输入" maxlength="160" value="<?= $val[date('Y',time())-3] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'turnover_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php  $a++; } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<h2 class="head-three">
    <span class="ml-10">子公司及关联公司</span>
    <i class="icon-plus-sign ml-4" onclick="add_linkcomp()"></i>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('linkcomp_table')"></i>-->
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th>公司名称</th>
            <th>关联性质</th>
            <th>投资金额</th>
            <th>持股比率(%)</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="linkcomp_table" class="select_table">
        <?php if(!empty($model['linkcomp'])){ ?>
            <?php foreach($model['linkcomp'] as $key => $val){ ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><input class="text-center" type="text"  name="CrmCustLinkcomp[<?= $key ?>][linc_name]" placeholder="请输入" maxlength="20" value="<?= $val['linc_name'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCustLinkcomp[<?= $key ?>][relational_character]" placeholder="请输入" maxlength="20" value="<?= $val['relational_character'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'price'" type="text"  name="CrmCustLinkcomp[<?= $key ?>][total_investment]" placeholder="请输入" maxlength="20" value="<?= $val['total_investment'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'two_percent'" type="text"  name="CrmCustLinkcomp[<?= $key ?>][shareholding_ratio]" placeholder="请输入" maxlength="160" value="<?= $val['shareholding_ratio'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'linkcomp_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<h2 class="head-three">
    <span class="ml-10">主要客户</span>
    <i class="icon-plus-sign ml-4" onclick="add_company()"></i>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('company_table')"></i>-->
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th>公司名称</th>
            <th>占营收比率(%)</th>
            <th>电话</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="company_table" class="select_table">
        <?php if(!empty($model['custCustomer'])){ ?>
            <?php foreach($model['custCustomer'] as $key => $val){ ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><input class="text-center" type="text"  name="CrmCustCustomer[<?= $key ?>][cc_customer_name]" placeholder="请输入" maxlength="20" value="<?= $val['cc_customer_name'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" type="text" data-options="validType:'float'"  name="CrmCustCustomer[<?= $key ?>][cc_customer_ratio]" placeholder="请输入" maxlength="20" value="<?= $val['cc_customer_ratio'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'telphone'" type="text"  name="CrmCustCustomer[<?= $key ?>][cc_customer_tel]" placeholder="请输入" maxlength="20" value="<?= $val['cc_customer_tel'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCustCustomer[<?= $key ?>][cc_customer_remark]" placeholder="请输入" maxlength="160" value="<?= $val['cc_customer_remark'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'company_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<h2 class="head-three">
    <span class="ml-10">主要供应商</span>
    <i class="icon-plus-sign ml-4" onclick="add_supplier()"></i>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('supplier_table')"></i>-->
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th>公司名称</th>
            <th>付款条件</th>
            <th>电话</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="supplier_table" class="select_table">
        <?php if(!empty($model['supplier'])){ ?>
            <?php foreach($model['supplier'] as $key => $val){ ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><input class="text-center" type="text"  name="CrmCustSupplier[<?= $key ?>][cc_customer_name]" placeholder="请输入" maxlength="20" value="<?= $val['cc_customer_name'] ?>"></td>
                    <td>
                        <select name="CrmCustSupplier[<?= $key ?>][payment_clause]" id="">
                            <option>请选择...</option>
                            <?php foreach ($downList["settlement"] as $k => $v){ ?>
                                <option value="<?= $v['bnt_id'] ?>" <?= $val['payment_clause'] == $v['bnt_id']?'selected':null; ?>><?= $v['bnt_sname'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'telphone'" type="text"  name="CrmCustSupplier[<?= $key ?>][cc_customer_tel]" placeholder="请输入" maxlength="20" value="<?= $val['cc_customer_tel'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCustSupplier[<?= $key ?>][cc_customer_remark]" placeholder="请输入" maxlength="160" value="<?= $val['cc_customer_remark'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'supplier_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<h2 class="head-three">
    <span class="ml-10">主要往来银行</span>
    <i class="icon-plus-sign ml-4" onclick="add_bank()"></i>
<!--    <i class="icon-remove-sign ml-4" onclick="delete_tr('bank_table')"></i>-->
</h2>
<div class="mb-10">
    <table class="table">
        <thead>
        <tr>
            <th class="width-50">序号</th>
            <th>银行名称</th>
            <th>账号</th>
            <th>往来项目</th>
            <th>备注</th>
            <th class="width-80">操作</th>
        </tr>
        </thead>
        <tbody id="bank_table" class="select_table">
        <?php if(!empty($model['bank'])){ ?>
            <?php foreach($model['bank'] as $key => $val){ ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><input class="text-center" type="text"  name="CrmCorrespondentBank[<?= $key ?>][bank_name]" placeholder="请输入" maxlength="50" value="<?= $val['bank_name'] ?>"></td>
                    <td><input class="text-center easyui-validatebox" data-options="validType:'int'" type="text"  name="CrmCorrespondentBank[<?= $key ?>][account_num]" placeholder="请输入" maxlength="50" value="<?= $val['account_num'] ?>"></td>
                    <td><input class="text-center" type="text"  name="CrmCorrespondentBank[<?= $key ?>][curremt_project]" placeholder="请输入" maxlength="80" value="<?= $val['curremt_project'] ?>"></td>
                    <td>
                        <a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,'bank_table')"><span class="icon-remove-sign"></span></a>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>
<h2 class="head-three">
    <span class="ml-10">附件</span>
</h2>
<div class="mb-10">
    <div class="inline-block width-890 mb-10">
        <label for="" class="width-100 label-align"><span class="red">*</span>客户信息签字档</label>
<!--        <input type="file" multiple="multiple" onchange="change(this)" name="Upload[message][]" value="" class="easyui-validatebox" data-options="required:true">-->
        <span style="text-align: center;">
                <?php foreach ($model['acc1'] as $key => $val) { ?>
                    <a class="text-center width-150 color-w ml-10"
                       href="<?= Url::to("@web/uploads/creditApply/") . base64_encode(pathinfo($val)['filename']).'.'.pathinfo($val)['extension'] ?>"><?= $val ?></a>
                <?php } ?>
        </span>
        <a href="javascript:;" class="file ml-20"
           style="text-align: center;"><?= empty($model['acc1']) ? "选择文件" : "重新选择" ?>
            <input type="file" onchange="change(this)" multiple="multiple"
                   class="width-60"
                   name="Upload[message][]"
                   value=""/>
        </a>
    </div>
    <br/>
    <span style="">其他附件：组织机构代码、最近三年度会计师审核通过的财务报表(资产负债表、利润表、现金流量表(若客户为上市公司提供股票代码即可))</span>
    <div class="file-border">
        <div class="mt-15">
<!--            <input type="file" multiple="multiple" onchange="change(this)" name="Upload[file][]" value=""  class="easyui-validatebox" data-options="required:true">-->
            <a href="javascript:;" class="file ml-10 ml-20"
               style="text-align: center;"><?= empty($model['acc2']) ? "选择文件" : "重新选择" ?>
                <input type="file" onchange="change(this)" multiple="multiple"
                       class="width-60"
                       name="Upload[file][]"
                       value=""/>
            </a>
            <span style="text-align: center;">
                <?php foreach ($model['acc2'] as $key => $val) { ?>
                    <a class="text-center width-150 color-w ml-10"
                       href="<?= Url::to("@web/uploads/creditApply/") . base64_encode(pathinfo($val)['filename']).'.'.pathinfo($val)['extension'] ?>"><?= $val ?></a>
                <?php } ?>
        </span>
        </div>
    </div>
    <span style="color:#ff0000">如可附上3年度审计财务报表,批复额度可能性越高,额度越大</span>
</div>
</div>
<div class="text-center">
    <button type="submit" class="button-blue-big sub">保存</button>
    <button type="submit" class="button-blue-big apply">提交</button>
    <?php if(empty($list)){ ?>
        <button class="button-white-big" onclick="window.location.href='<?= Url::to(["index"]) ?>'" type="button">返回</button>
    <?php }else{ ?>
        <button class="button-white-big" onclick="window.location.href='<?= Url::to(["list"]) ?>'" type="button">返回</button>
    <?php } ?>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function(){
//        $(".head-three").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });

        $('.sub').click(function(){
            $('#add-form').attr('action','<?= Url::to(["update",'id'=>$model["aid"]]) ?>');
            ajaxSubmitForm($("#add-form"));
        });
        var status = '<?= $model['credit_status'] ?>';
        if(status == '20'){
            $('.apply').attr('disabled',true);
        }
        $('.apply').click(function(){
            $('#add-form').attr('action', '<?= Url::to(["update", "is_apply" => "1", "status" => "20", "id" => $model["aid"]]) ?>');
            ajaxSubmitForm($("#add-form"));
        });

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
//        $(document).on('click','.select_date',function () {
//            jeDate({
//                dateCell: this,
//                zIndex:8831,
//                format: "YYYY-MM-DD",
//                skinCell: "jedatedeep",
//                isTime: false,
//                okfun:function(elem) {
//                    $(elem).change();
//                },
//                //点击日期后的回调, elem当前输入框ID, val当前选择的值
//                choosefun:function(elem) {
//                    $(elem).change();
//                }
//            })
//        })
        $(document).on('click','.select_date',function () {
            $(this).jeDate({
                format:"YYYY-MM-DD",
                zIndex:5 //菜单栏弹出层的层级为7(myMenu.css)
            })
            $(this).click();
        })
    })
    /*额度申请*/
    function add_table(){
        var a = $("#limit_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#limit_table").append(
            '<tr>'+
            '<td>'+ b +'</td>'+
            '<td>'+
            '<select name="CrmCreditLimit['+ b +'][credit_type]" class="easyui-validatebox" data-options=\'required:"true",validType:["credit_type"],delay:10000000,validateOnBlur:true\'>'+
            '<option value="">请选择...</option>'+
            '<?php foreach ($downList["credit_type"] as $key => $val) { ?>'+
            '<option value="<?= $val['id'] ?>" <?= $model['credit_type'] == $val['id'] ? 'selected' : null ?>><?= $val['credit_name'] ?></option>'+
            '<?php } ?>'+
            '</select>'+
            '</td>'+
            '<td>'+
            '<input type="text" name="CrmCreditLimit['+ b +'][credit_limit]" class="width-80 money easyui-validatebox" data-options="required:\'true\',validType:\'two_decimal\'">'+
            '</td>'+
            '<td>'+
            '<select name="CrmCreditLimit['+ b +'][payment_clause]">'+
            '<option value="">请选择...</option>'+
            '<?php foreach ($downList["settlement"] as $key => $val) { ?>'+
            '<option value="<?= $val['bnt_id'] ?>" <?= $model['payment_clause'] == $val['bnt_id'] ? 'selected' : null ?>><?= $val['bnt_sname'] ?></option>'+
            '<?php } ?>'+
            '</select>'+
            '</td>'+
            '<td>'+
            '<select name="CrmCreditLimit['+ b +'][payment_method]">'+
            '<option value="">请选择...</option>'+
            '<?php foreach ($downList["pay_method"] as $key => $val) { ?>'+
            '<option value="<?= $val['bsp_id'] ?>" <?= $model['payment_method'] == $val['bsp_id'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>'+
            '<?php } ?>'+
            '</select>'+
            '</td>'+
            '<td>'+
            '<select name="CrmCreditLimit['+ b +'][initial_day]">'+
            '<option value="">请选择...</option>'+
            '<?php foreach ($downList["initial_day"] as $key => $val) { ?>'+
            '<option value="<?= $val['bsp_id'] ?>" <?= $model['initial_day'] == $val['bsp_id'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>'+
            '<?php } ?>'+
            '</select>'+
            '</td>'+
            '<td>'+
            '<select name="CrmCreditLimit['+ b +'][pay_day]">'+
            '<option value="">请选择...</option>'+
            '<?php foreach ($downList["pay_day"] as $key => $val) { ?>'+
            '<option value="<?= $val['bsp_id'] ?>" <?= $model['pay_day'] == $val['bsp_id'] ? 'selected' : null ?>><?= $val['bsp_svalue'] ?></option>'+
            '<?php } ?>'+
            '</select>'+
            '</td>'+
            '<td>'+
            '<input type="text" class="width-150" name="CrmCreditLimit['+ b +'][remark]">'+
            '</td>'+
            '<td>'+
            '<input type="text" class="width-80 select_date" name="CrmCreditLimit['+ b +'][validity]">'+
            '</td>'+
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="delete_del(this,\'limit_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }
    /*联系人新增*/
    function add_contacts() {
        var a = $("#contacts_table tr").length;
        var b = a;
        b += 1;
        var obj = $("#contacts_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCustomerPersion[' + a + '][ccper_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'email\'" type="text"  name="CrmCustomerPersion[' + a + '][ccper_mail]" placeholder="请输入" maxlength="20"></td>' +
             '<td><input class="text-center easyui-validatebox" data-options="required:true,validType:\'mobile\'" type="text"  name="CrmCustomerPersion[' + a + '][ccper_mobile]" placeholder="请输入" maxlength="20"></td>' +
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
    var select = '<select name="CrmCreditApply[currency]" class="width-180 value-align">' +
            '<?php foreach ($downList['currency'] as $key => $val){ ?>'+
            '<option value="<?= $val['bsp_id'] ?>" <?= $model['currency'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>'+
            '<?php } ?>'+
            '</select>';
        var obj = $("#turnover_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td>'+select+'</td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'price\'" type="text"  name="CrmTurnover[ct][' + a + '][turnover][]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'price\'" type="text"  name="CrmTurnover[ct][' + a + '][turnover][]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'price\'" type="text"  name="CrmTurnover[ct][' + a + '][turnover][]" placeholder="请输入" maxlength="20"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'turnover_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
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
        var select = '<select name="CrmCreditApply[currency]" class="width-80 value-align">' +
            '<?php foreach ($downList['currency'] as $key => $val){ ?>'+
            '<option value="<?= $val['bsp_id'] ?>" <?= $model['currency'] == $val['bsp_id']?'selected':null; ?>><?= $val['bsp_svalue'] ?></option>'+
            '<?php } ?>'+
            '</select>';
        var obj = $("#linkcomp_table").append(
            '<tr>' +
            '<td>'+ b +'</td>'+
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCustLinkcomp[' + a + '][linc_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCustLinkcomp[' + a + '][relational_character]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox width-80" data-options="required:true,validType:\'price\'" type="text"  name="CrmCustLinkcomp[' + a + '][total_investment]" placeholder="请输入" maxlength="20">&nbsp;&nbsp;&nbsp;'+select+'</td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true,validType:\'three_percent\'" type="text"  name="CrmCustLinkcomp[' + a + '][shareholding_ratio]" placeholder="请输入" maxlength="160"></td>' +
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
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCustCustomer[' + a + '][cc_customer_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><input class="text-center easyui-validatebox" type="text" data-options="required:true,validType:\'float\'"  name="CrmCustCustomer[' + a + '][cc_customer_ratio]" placeholder="请输入" maxlength="20"></td>' +
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
            '<td><input class="text-center" type="text"  name="CrmCustSupplier[' + a + '][cc_customer_name]" placeholder="请输入" maxlength="20"></td>' +
            '<td><select name="CrmCustSupplier[' + a + '][payment_clause]" id="">' +
            '<option>请选择...</option>'+
            <?php foreach ($downList["settlement"] as $key => $val){ ?>
            '<option value="<?= $val['bnt_id'] ?>" <?= $model['payment_clause'] == $val['bnt_id']?'selected':null; ?>><?= $val['bnt_sname'] ?></option>'+
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
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text" name="CrmCorrespondentBank[' + a + '][bank_name]" placeholder="请输入" maxlength="50"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="validType:\'int\'" type="text"  name="CrmCorrespondentBank[' + a + '][account_num]" placeholder="请输入" maxlength="50"></td>' +
            '<td><input class="text-center easyui-validatebox" data-options="required:true" type="text"  name="CrmCorrespondentBank[' + a + '][curremt_project]" placeholder="请输入" maxlength="80"></td>' +
            '<td><input class="text-center" type="text"  name="CrmCorrespondentBank[' + a + '][remark]" placeholder="请输入" maxlength="80"></td>' +
            '<td>'+
            '<a onclick="reset(this)"><span class="icon-refresh"></span></a> <a onclick="vacc_del(this,\'bank_table\')"><span class="icon-remove-sign"></span></a>'+
            '</td>'+
            '</tr>'
        );
        $.parser.parse(obj);
        a++;
    }


    /*删除表格行并且计算总额度*/
    function delete_del(obj,id){
//        var b = $("#"+id).find('.select').length;
//        if(b==0){
//            layer.alert('请选择要删除的行',{icon:2});
//        }
//        $("#"+id).find('.select').remove();
//        var a = $("#"+id+' tr').length;
//        for(var i =0; i<a;i++){
//            $("#"+id).find('tr').eq(i).find('td:first').text(i+1);
//        }
        var a = $("#"+ id +" tr").length;
        if(a != 1){
            $(obj).parents("tr").remove();
        }
        for(var i=0;i<a;i++){
            $('#'+id).find('tr').eq(i).find('td:first').text(i+1);
        }
        /*计算总额度*/
        var sum = 0;
        $('.money').each(function(){
            sum += $(this).val()*1;
        })
        $('.total_amount').val(sum);
    }

    function change(obj) {
        var length = obj.files.length;
        var span = obj.parentNode.previousSibling.previousSibling;
        var temp = "";
        for (var i = 0; i < obj.files.length; i++) {
            if (i == 0) {
                temp = obj.files[i].name;
            } else {
                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
            }
            span.innerHTML = temp;
        }
    }
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input,select").val("");
    }
    /*删除行*/
    function vacc_del(obj,id) {
        var a = $("#"+ id +" tr").length;
        if(a != 1){
            $(obj).parents("tr").remove();
        }
        for(var i=0;i<a;i++){
            $('#'+id).find('tr').eq(i).find('td:first').text(i+1);
        }
    }
</script>

