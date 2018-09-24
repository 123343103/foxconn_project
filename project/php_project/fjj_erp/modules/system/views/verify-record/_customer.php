<?php
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;
use yii\widgets\ActiveForm;

$this->title = '客户代码申请审核';
$this->params['homeLike'] = ['label' => '审核管理'];
//$this->params['breadcrumbs'][] = ['label' => '客户代码申请列表'];
$this->params['breadcrumbs'][] = ['label' => '客户代码申请审核'];
$regname = unserialize($model['cust_regname']);
$regnumber = unserialize($model['cust_regnumber']);
?>
<style>
    .width-100 {
        width: 110px;
    }

    .width-200 {
        width: 200px;
    }

    .color-blue {
        color: #030aff;
        background-color: #ffffff;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" name="id" id="ids" value="<?= $id ?>">
    <input type="hidden" name="batchaudit" value="0">
    <h2 class="head-first">
        <?= $this->title; ?>
    </h2>
    <div class="mb-10 float-right" style="font-weight:700;">
        <label>客户编号</label>
        <span><?= $model['cust_filernumber'] ? $model['cust_filernumber'] : "/"; ?></span>
    </div>
    <div class="border-bottom mb-10 pb-10">
        <?= Html::button('通过', ['class' => 'button-blue width-80', 'id' => 'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue width-80', 'id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <div class="space-10"></div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>客户基本信息</a>
    </h2>
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10"
               style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">客户全称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_sname'] ?></td>
                <td class="no-border vertical-center label-align" width="15%">客户简称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_shortname'] ?></td>
                <td class="no-border vertical-center label-align" width="15%">客户代码<label>：</label></td>
                <td class="no-border vertical-center value-align" width="15%"><?= $model['apply_code'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司电话<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_tel1'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">传真<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_fax'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户来源<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custSource'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">所在地区<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['area'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">营销区域<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['saleArea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">联系人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_contacts'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">联系电话<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_tel2'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">邮箱<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_email'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户经理人<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= $model['manager_code'] ? $model['manager_code'] : ""; ?></td>
                <td class="no-border vertical-center label-align" width="10%">详细地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3"
                    width="20%"><?= $model['district'][0]['district_name'] . $model['district'][1]['district_name'] . $model['district'][2]['district_name'] . $model['district'][3]['district_name'] . $model['cust_adress'] ?></td>

            </tr>
        </table>
    </div>
    <div class="space-10"></div>
    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>客户详情信息</a>
    </h2>

    <div class="display-none">
        <table width="90%" class="no-border vertical-center mb-10"
               style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="15%">公司属性<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compvirtue'] ?></td>
                <td class="no-border vertical-center label-align" width="15%">公司规模<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compscale'] ?></td>
                <td class="no-border vertical-center label-align" width="15%">行业类别<label>：</label></td>
                <td class="no-border vertical-center value-align" width="15%"><?= $model['industryType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户等级<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['custLevel'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">经营类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['businessType'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">员工人数<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_personqty'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">注册时间<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_regdate'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">注册资金<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= empty($model['cust_regfunds']) ? '' : $model['cust_regfunds'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">注册货币<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['dealCurrency'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">是否上市公司<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= $model['cust_islisted'] == 1 ? "是" : "否"; ?></td>
                <td class="no-border vertical-center label-align" width="10%">是否公司会员<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= $model['cust_ismember'] == 1 ? "是" : "否" ?></td>
                <td class="no-border vertical-center label-align" width="10%">会员类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['memberType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">潜在需求<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['latDemand'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">需求类目<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['category_name'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">旺季分布<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_bigseason'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">交易币别<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="20%"><?= $model['bsPubdata']['memberCurr'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">年营业额<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['member_compsum'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司主页<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['member_compwebside'] ?></td>
            </tr>
            <?php foreach ($regname as $key => $val) { ?>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">登记证名称<?php echo $key + 1; ?>
                        <label>：</label></td>
                    <td class="no-border vertical-center value-align" width="20%"><?= $val ?></td>
                    <td class="no-border vertical-center label-align" width="10%">登记证号码<?php echo $key + 1; ?>
                        <label>：</label></td>
                    <td class="no-border vertical-center value-align" colspan="3"
                        width="20%"><?= $regnumber[$key] ?></td>
                </tr>
            <?php } ?>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">申请发票类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['invoiceType'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">具备开票类型<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3" width="20%">            <select name="hv_inv_type" id="hv_inv_type" class="value-width value-align easyui-validatebox width-200"
                                                                                                              data-options="required:'true'">
                        <!--            <option value="">请选择...</option>-->
                        <?php foreach ($downList['invoiceType'] as $key => $val) { ?>
                            <option
                                value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                        <?php } ?>
                    </select></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票抬头<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5"
                    width="20%"><?= $model['invoice_title'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票抬头地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5"
                    width="20%"><?= $model['invoiceTitleDistrict'][0]['district_name'] . $model['invoiceTitleDistrict'][1]['district_name'] . $model['invoiceTitleDistrict'][2]['district_name'] . $model['invoiceTitleDistrict'][3]['district_name'] . $model['invoice_title_address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">发票寄送地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5"
                    width="20%"><?= $model['invoiceMailDistrict'][0]['district_name'] . $model['invoiceMailDistrict'][1]['district_name'] . $model['invoiceMailDistrict'][2]['district_name'] . $model['invoiceMailDistrict'][3]['district_name'] . $model['invoice_mail_address'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">经营范围<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5"
                    width="20%"><?= $model['member_businessarea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">总公司<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_parentcomp'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司负责人<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3"
                    width="20%"><?= $model['cust_inchargeperson'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">总公司地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="5"
                    width="20%"><?= $model['districtCompany'][0]['district_name'] . $model['districtCompany'][1]['district_name'] . $model['districtCompany'][2]['district_name'] . $model['districtCompany'][3]['district_name'] . $model['cust_headquarters_address'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>认证信息</a>
    </h2>
    <div class="display-none">
        <div class="mb-10">
            <input type="hidden" id="ynspp" value="<?= $crmcertf['YN_SPP'] ?>">
            <label class=" label-align width-100" style="width: 161px" for="">是否供应商：</label>
            <input type="radio" value="1" id="radyes" checked="checked" disabled>

            <span class="vertical-middle label-width  label-align">是</span>
            <input type="radio" value="0" id="radno" style="margin-left: 50px;" disabled>

            <span class="vertical-middle label-width  label-align">否</span>

            <div id="custcode" style="float: right;margin-right: 175px;">
                <label class="label-width  label-align width-100" for="">供应商代码：</label>
                <input class="value-width value-align width-200" type="text" readonly
                       value="<?= $crmcertf['SPP_NO'] ?>" maxlength="120">
            </div>
        </div>
        <div class="mb-10">
            <input type="hidden" id="crtftype" value="<?= $crmcertf['crtf_type'] ?>">
            <label class="  label-align" style="width: 161px;" for="">证件类型：</label>
            <input type="radio" value="0" id="old" checked="checked" disabled>
            <span class="vertical-middle label-width  label-align">旧版三证</span>
            <input type="radio" value="1" id="new" style="margin-left: 14px;" disabled>
            <span class="vertical-middle label-width  label-align">新版三证合一</span>
            <span style="margin-left: 100px;color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
        </div>
        <div class="mb-10">
            <label class=" label-align ">税籍编码/统一社会信用代码：</label>
            <span class="value-width value-align width-200"><?= $model['cust_tax_code'] ?></span>
        </div>
        <div class="mb-10">
            <label class=" label-align "  style="width: 161px;" id="business">公司营业执照：</label>
            <input type="hidden" id="httpIp" value="<?= Yii::$app->ftpPath['httpIP'] ?>">
            <input type="hidden" id="cmpname" value="<?= Yii::$app->ftpPath['CMP']['father'] ?>">
            <input type="hidden" id="bslcn" value="<?= Yii::$app->ftpPath['CMP']['BsLcn'] ?>">
            <input type="hidden" id="bslic" value="<?= $crmcertf['bs_license'] ?>">
            <a id="license" target="_blank"><?= $crmcertf['o_license'] ?>
                <!--                <input class="value-width value-align width-200 " style="color: #030aff; background-color: #ffffff" type="text"-->
                <!--                       readonly-->
                <!--                       value="-->
                <? //= $crmcertf['o_license'] ?><!--" maxlength="120" id="license_name">-->
            </a>
        </div>
        <div class="mb-10" id="tax">
            <label class="  label-align" style="width: 161px">税务登记证：</label>
            <input type="hidden" id="reg" value="<?= Yii::$app->ftpPath['CMP']['TaxReg'] ?>">
            <input type="hidden" id="oregname" value="<?= $crmcertf['tx_reg'] ?>">
            <a id="oreg" target="_blank"><?= $crmcertf['o_reg'] ?>
                <!--                <input class="value-width value-align width-200" style="color: #030aff;background-color: #ffffff" type="text"-->
                <!--                       readonly-->
                <!--                       value="--><? //= $crmcertf['o_reg'] ?><!--" maxlength="120" id="tax_name">-->
            </a>
        </div>
        <div class="mb-10">
            <label class=" label-align" style="width: 161px;">一般纳税人资格证：</label>
            <input type="hidden" id="qlf" value="<?= Yii::$app->ftpPath['CMP']['TaxQlf'] ?>">
            <input type="hidden" id="ocerftname" value="<?= $crmcertf['qlf_certf'] ?>">
            <a id="cerft" target="_blank"><?= $crmcertf['o_cerft'] ?>
                <!--                <input class="value-width value-align width-200" style="color: #030aff;background-color: #ffffff" type="text"-->
                <!--                       readonly-->
                <!--                       value="-->
                <? //= $crmcertf['O_cerft'] ?><!--" maxlength="120" id="organization_name">-->
            </a>
        </div>
        <div class="mb-10">
            <label class=" label-align vertical-top" style="width: 161px;">备注：</label>
            <span class="value-align" style="width: 500px;display: inline-block;word-wrap: break-word;white-space: normal;"
                  id="remark"><?= $crmcertf['marks'] ?></span>
        </div>
    </div>

    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>主要联系人</a>
    </h2>
    <div class="mb-10 display-none">
        <div class="mb-10">
            <div id="contact" class="retable"></div>
        </div>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>设备信息</a>
    </h2>
    <div class="mb-10 display-none">
        <div id="device" class="retable"></div>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>主营产品</a>
    </h2>
    <div class="mb-10 display-none">
        <div id="mainProduct" class="retable"></div>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>主要客户</a>
    </h2>
    <div class="mb-30 display-none">
        <div class="mb-10">
            <div id="mainCustomer" class="retable"></div>
        </div>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>签核记录</a>
    </h2>
    <div class="mb-30">
        <div class="mb-10" >
            <div id="record"></div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(function () {
        var bslicname = $("#bslic").val();//公司营业执照/三证合一证文件名
        var bslicna = bslicname.substring(2, 8);//从第二位到第八位
        //var reg = new RegExp("-", "g");
        // bslicna = bslicna.replace(reg, '');//公司营业执照/三证合一证时间文件名

        var oregname = $("#oregname").val();//税务登记证文件名
        var oregna = oregname.substring(2, 8);//从第二位到第八位
        //oregna = oregna.replace(reg, '');//税务登记证时间文件名

        var ocerftname = $("#ocerftname").val();//一般纳税人资格证文件名
        var ocerftna = ocerftname.substring(2, 8);//从第二位到第八位
        //ocerftna = ocerftna.replace(reg, '');//一般纳税人资格证时间文件名

        var httpip = $("#httpIp").val();//服务器地址
        var cmp = $("#cmpname").val();//cmp文件夹
        var bslcn = $("#bslcn").val();//公司营业执照/三证合一文件夹
        var taxreg = $("#reg").val();//税务登记证文件夹
        var taxqlf = $("#qlf").val();//一般纳税人资格证书文件夹
        $("#license").attr('href', httpip + cmp + bslcn + '/' + bslicna + '/' + bslicname);//公司营业执照/三证合一证书的图片路径
        $("#oreg").attr('href', httpip + cmp + taxreg + '/' + oregna + '/' + oregname);//税务登记证书的图片路径
        $("#cerft").attr('href', httpip + cmp + taxqlf + '/' + ocerftna + '/' + ocerftname);//一般纳税人资格证书的图片路径
        var ynspp = $("#ynspp").val();//获取是否供应商标记
        if (ynspp == 1) {
            $("#radyes").attr('checked', 'checked');
            $("#radno").attr('checked', false);
            $("#custcode").css('display', 'block');
        }
        else {
            $("#radyes").attr('checked', false);
            $("#radno").attr('checked', 'checked');
            $("#custcode").css('display', 'none');
        }
        var types = $("#crtftype").val();//获取证件类型
        if (types == 1) {
            $("#new").attr('checked', 'checked');
            $("#old").attr('checked', false);
            $("#tax").css('display', 'none');
            $("#business").text("公司三证合一证：");
        }
        else {
            $("#new").attr('checked', false);
            $("#old").attr('checked', 'checked');
            $("#tax").css('display', 'block');
            $("#business").text("公司营业执照：");
        }

        /*主要联系人*/
        $("#contact").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/contact-person']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "ccper_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "ccper_name", title: "姓名", width: 100},
                {
                    field: "ccper_sex", title: "性别", width: 50, formatter: function (val, row) {
                    return row.ccper_sex == 1 ? "男" : "女";
                }
                },
                /*{field: "birthPlace", title: "籍贯", width: 100},*/
                {field: "ccper_post", title: "职务", width: 100},
                {field: "ccper_tel", title: "办公电话", width: 100},
                {field: "ccper_mobile", title: "手机号码", width: 100},
                {field: "ccper_mail", title: "邮箱", width: 120},
                {field: "ccper_qq", title: "QQ", width: 100},
                {field: "ccper_wechat", title: "微信", width: 120},
                {
                    field: "ccper_ismain", title: "是否主要联系人", width: 100, formatter: function (val, row) {
                    return row.ccper_ismain == 1 ? "是" : "否";
                }
                },
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#contact');
                showEmpty($(this), data.total, 0);
            }
        });
        /*设备信息*/
        $("#device").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-device']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "custd_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "type", title: "设备类型", width: 500},
                {field: "brand", title: "设备品牌", width: 450},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#device');
                showEmpty($(this), data.total, 0);
            }
        });
        /*主营产品*/
        $("#mainProduct").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-product']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "ccp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "ccp_sname", title: "主营产品", width: 250},
                {field: "ccp_model", title: "规格/型号", width: 150},
                {field: "ccp_annual", title: "年产量", width: 150},
                {field: "ccp_brand", title: "品牌", width: 200},
                {field: "ccp_remark", title: "备注", width: 250},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#mainProduct');
                showEmpty($(this), data.total, 0);
            }
        });
        /*主要客户*/
        $("#mainCustomer").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-customer']);?>?id=" + <?= $model['cust_id'] ?>,
            rownumbers: true,
            method: "get",
            idField: "cc_customerid",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "cc_customer_name", title: "客户名称", width: 200},
                {field: "customer_type", title: "经营类型", width: 200},
                {field: "cc_customer_person", title: "公司负责人", width: 200},
                {field: "cc_customer_tel", title: "公司电话", width: 160},
                {field: "cc_customer_ratio", title: "占营收比例(%)", width: 150},
                {field: "cc_customer_remark", title: "备注", width: 250},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#mainCustomer');
                showEmpty($(this), data.total, 0);
            }
        });


        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record']);?>?id=" + <?= $id ?>,
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {
                    field: "verifyOrg", title: "审核节点", width: 150
                },
                {field: "verifyName", title: "审核人", width: 150},
                {
                    field: "vcoc_datetime", title: "审核时间", width: 156
                },
                {field: "verifyStatus", title: "操作", width: 150},
                {
                    field: "vcoc_remark", title: "审核意见", width: 200
                },
                {
                    field: "vcoc_computeip", title: "审核IP", width: 150
                },

            ]],
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this), data.total, 0);
                $(this).datagrid("resize");
            }
        });
//        $("#pass").one("click", function () {
//            $("#check-form").attr('action','<?//= \yii\helpers\Url::to(['/system/verify-record/audit-pass']) ?>//');
//            return ajaxSubmitForm($("#check-form"));
//        });
        $("#pass").on("click", function () {
            layer.confirm("是否通过?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        data: $("#check-form").serialize(),
                        url: "<?= Url::to(['/system/verify-record/audit-pass']) ?>",
                        success: function (msg) {
                            if (msg.flag == 1) {
                                layer.alert(msg.msg, {icon: 1}, function () {
                                    parent.window.location.href = '<?= Url::to(['index']) ?>'
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (data) {
//                            console.log('data: ',data)
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });
//        $("#reject").one("click", function () {
//            $("#check-form").attr('action','<?//= Url::to(['/system/verify-record/audit-reject']) ?>//');
//            return ajaxSubmitForm($("#check-form"));
//        });
        $("#reject").on("click", function () {
            var idss = $("#ids").val();
            var hvinvtype = $("#hv_inv_type").val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + idss + "&hvinvtype=" + hvinvtype,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });

//            layer.confirm("是否驳回?",
//                {
//                    btn: ['确定', '取消'],
//                    icon: 2
//                },
//                function () {
//                    $.ajax({
//                        type: "post",
//                        dataType: "json",
//                        data: $("#check-form").serialize(),
//                        url: "<?//= Url::to(['/system/verify-record/audit-reject']) ?>//",
//                        success: function (msg) {
//                            if (msg.flag == 1) {
//                                layer.alert(msg.msg, {icon: 1}, function () {
//                                    parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                                });
//                            } else {
//                                layer.alert(msg.msg, {icon: 2})
//                            }
//                        }
//                    })
//                },
//                function () {
//                    layer.closeAll();
//                }
//            )
        });
//        $(".head-three").next("div:eq(0)").css("display", "block");
//        $(".head-three>a").click(function () {
//            $(this).parent().next().slideToggle();
//            $(this).prev().toggleClass("icon-caret-right");
//            $(this).prev().toggleClass("icon-caret-down");
//        });
        $(".head-three").next("div:eq(0)").css("display", "block");
//        $(".head-three").next("div:eq(1),div:eq(2),div:eq(3)").css("display", "none");
        $(".head-three>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
            $(".retable").datagrid("resize");
        });
    })

</script>