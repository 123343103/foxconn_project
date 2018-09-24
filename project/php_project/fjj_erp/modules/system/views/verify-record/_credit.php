<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/7/14
 * Time: 16:23
 */
use app\assets\JeDateAsset;
use app\classes\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
JeDateAsset::register($this);
$this->title = '账信审核';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = $this->title;
$ftp = new \app\widgets\upload\Ftp();
?>
<style>
    .width-80 {
        width: 80px;
    }

    .width-100 {
        width: 100px;
    }

    .width-120 {
        width: 120px;
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

    .mt-10 {
        margin-top: 10px;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .pb-10 {
        padding-bottom: 10px;
    }

    .credit-content th {
        height: 30px;
        font-weight: 100;
        color: #333333;
        font-size: 12px;
        background: #d9f0ff;
    }

    /*.img-border{*/
    /*width:300px;*/
    /*height:240px;*/
    /*margin:10px;*/
    /*}*/
    .img {
        width: 300px;
        height: 200px;
    }

    .img-title {
        height: 30px;
    }

    .img-title a {
        height: 30px;
        display: block;
        text-align: center;
        font-size: 14px;
        line-height: 40px;
    }

    .preview-widget {
        width: 620px;
        float: left;
    }

    .credit-content tr td p {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        display: inline-block;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none !important;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
</style>
<div <?= Yii::$app->controller->action->id == 'verify' ? 'class="content"' : 'style="margin:5px;"'; ?>>
    <?php $form = ActiveForm::begin([
        'id' => 'check-form',
        'options' => ['enctype' => 'multipart/form-data'],
        'method' => 'post',
    ]); ?>
    <h1 class="head-first">
        <?= $this->title ?>
        <span class="head-code">编号：<?= $result['vco_code'] ?></span>
    </h1>
    <div class="border-bottom mb-20 pb-10">
        <?=  Html::button('通过', ['class' => 'button-blue width-80 opt-btn', 'id' => 'pass']) ?>
        <?=  Html::button('驳回', ['class' => 'button-blue width-80 opt-btn', 'id' => 'reject']) ?>
        <?=  Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <div class="mb-30">
        <input type="hidden" name="id" id="cid" value="<?= $id ?>">
        <h2 class="head-second mt-20">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">客户信息</a>
        </h2>
        <div>
            <table width="100%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">客户代码<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['apply_code'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">客户全称<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_sname'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">客户简称<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_shortname'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">税籍编码<label>：</label></td>
                    <td class="no-border vertical-center" width="30%"><?= $model['cust_tax_code'] ?></td>

                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">公司电话<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_tel1'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">客户经理人<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['manager']['staff_name'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">交易法人<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['company_name'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">营销区域<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['csarea']['csarea_name'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">客户类型<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_type']['bsp_svalue'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">客户等级<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_level']['bsp_svalue'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">主要联系人<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_contacts'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">联系电话<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_tel2'] ?></td>
                    <td class="no-border vertical-center" width="30%" colspan="2"></td>
                </tr>
            </table>
        </div>
        <h2 class="head-second">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">所需信用额度</a>
        </h2>
        <div>
            <div class="mb-10">
                <label class="width-100 label-align">申请账信类型<label>：</label></label>
                <span class="width-150 value-align"><?= $model['creditType'] ?></span>
                <label class="width-100 label-align">起算日<label>：</label></label>
                <span class="width-150 value-align"><?= $model['initialDay'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-100 label-align">付款方式<label>：</label></label>
                <span class="width-150 value-align"><?= $model['paymentMethod'] ?></span>
                <label class="width-100 label-align">付款条件<label>：</label></label>
                <span class="width-150 value-align"><?= $model['paymentType'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-100 label-align">付款日<label>：</label></label>
                <span class="width-150 value-align"><?= $model['payDay'] ?></span>
                <label class="width-100 label-align">预估月交易额<label>：</label></label>
                <span
                    class="width-150 value-align"><?= !empty($model['volume_trade']) ? '￥' . $model['volume_trade'] : ''; ?></span>
            </div>
            <?php if(empty($child['file'])){ ?>
                <?php if (!empty($model['limit'])) { ?>
                    <?php foreach ($model['limit'] as $kl => $vl) { ?>
                        <div class="mb-10">
                            <label class="width-100 label-align"><?= $vl['creditType'] ?>申请额度<label>：</label></label>
                            <span
                                    class="width-150 value-align"><?= !empty($vl['credit_limit']) ? '￥' . $vl['credit_limit'] : '' ?></span>
                            <label style="width:115px;"
                                   class="label-align"><?= $vl['creditType'] !== 'AP担保' ? '<sapn class="red">*</sapn>' : '' ?><?= $vl['creditType'] ?>
                                授予额度<label>：</label></label>
                            <input type="hidden" class="width-200 value-align"
                                   name="LCrmCreditLimit[<?= $kl ?>][l_limit_id]" value="<?= $vl['l_limit_id'] ?>">
                            <input id="lim" type="text"
                                   class="width-140 value-align <?= $vl['creditType'] !== 'AP担保' ? 'easyui-validatebox' : '' ?>" <?= $vl['creditType'] == 'AP担保' ? 'readonly="readonly"' : '' ?> <?= $vl['creditType'] !== 'AP担保' ? 'data-options="validType:[\'decimal[17,2]\',\'noZero\'],required:true"' : '' ?>
                                   name="LCrmCreditLimit[<?= $kl ?>][approval_limit]">
                            <label> <?= $vl['creditType'] !== 'AP担保' ? '<sapn class="red">*</sapn>' : '' ?>
                                有效期<label>：</label></label>
                            <input type="text"
                                   class="width-140 Wdate value-align <?= $vl['creditType'] !== 'AP担保' ? 'easyui-validatebox validity_date' : '' ?>"<?= $vl['creditType'] == 'AP担保' ? 'readonly="readonly"' : '' ?> <?= $vl['creditType'] !== 'AP担保' ? 'data-options="required:true"' : '' ?>
                                   name="LCrmCreditLimit[<?= $kl ?>][validity_date]" <?= $vl['creditType'] !== 'AP担保' ? 'onclick="time(this)"' : '' ?>
                                   onfocus="this.blur()">
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if ($model['creditType'] !== 'AP担保') { ?>
                    <div class="mb-10 overflow-auto">
                        <label class="width-100 label-align float-left"><span class="red">*</span>附件<label>：</label></label>
                        <?= \app\widgets\upload\FileUploadWidget::widget([
                            "name" => "file_new",
                            "extensions" => "png,jpg,xlsx",
                            "items" => $model['file_new']
                        ]) ?>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <?php if (!empty($child['limit'])) { ?>
                    <?php foreach ($child['limit'] as $kl => $vl) { ?>
                        <div class="mb-10">
                            <label class="width-100 label-align"><?= $vl['creditType'] ?>申请额度<label>：</label></label>
                            <span class="width-150 value-align"><?= !empty($vl['credit_limit']) ? '￥' . $vl['credit_limit'] : '' ?></span>
                            <label style="width:115px;"
                                   class="label-align"><?= $vl['creditType'] !== 'AP担保' ? '<sapn class="red">*</sapn>' : '' ?><?= $vl['creditType'] ?>
                                授予额度<label>：</label></label>
                            <input type="hidden" class="width-200 value-align"
                                   name="LCrmCreditLimit[<?= $kl ?>][l_limit_id]" value="<?= $vl['l_limit_id'] ?>">
                            <input type="hidden" class="width-140 value-align" name="LCrmCreditLimit[<?= $kl ?>][approval_limit]" value="<?= $vl['approval_limit'] ?>">
                            <span class="width-150 value-align"><?= $vl['approval_limit'] ?></span>
                            <label> <?= $vl['creditType'] !== 'AP担保' ? '<sapn class="red">*</sapn>' : '' ?>
                                有效期<label>：</label></label>
                            <input type="hidden" class="width-140 value-align" name="LCrmCreditLimit[<?= $kl ?>][validity_date]" value="<?= $vl['validity_date'] ?>">
                            <span class="width-100 value-align"><?= $vl['validity_date'] ?></span>
                        </div>
                    <?php } ?>
                <?php } ?>
                <?php if ($model['creditType'] !== 'AP担保') { ?>
                    <div class="mb-10 overflow-auto">
                        <label class="width-100 label-align float-left"><span class="red">*</span>附件<label>：</label></label>
                        <a class="float-left" target="_blank" href="<?= Yii::$app->ftpPath['httpIP'].$child['file']['file_new'] ?>"><?= $child['file']['file_old'] ?></a>
                        <input type="hidden" name="LCrmCreditApply[file_new]" value="<?= $child['file']['file_new'] ?>">
                        <input type="hidden" name="LCrmCreditApply[file_old]" value="<?= $child['file']['file_old'] ?>">
                    </div>
                <?php } ?>

            <?php } ?>
            <div class="mb-10">
                <label class="width-100 label-align">备注<label>：</label></label>
                <span style="width:80%;" class="value-align"><?= $model['apply_remark'] ?></span>
            </div>

        </div>
        <h2 class="head-second mt-20">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">客户信用相关信息</a>
        </h2>
        <div>
            <table width="100%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">注册资金<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['cust_regfunds'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">注册货币<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['regcurr'] ?></td>
                    <td class="no-border vertical-center label-align" width="12%">实收资本<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"><?= $model['official_receipts'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="12%">主营项目<label>：</label></td>
                    <td class="no-border vertical-center" width="20%"
                        colspan="4"><?= $model['member_businessarea'] ?></td>
                </tr>
            </table>
            <table class="credit-table mt-10">
                <tbody class="credit-content">
                <tr>
                    <th class="width-100" rowspan="2">母公司</th>
                    <th>公司名称</th>
                    <th colspan="2">投资总额(RMB)</th>
                    <th colspan="2">持股比率</th>
                    <th></th>
                </tr>
                <tr>
                    <td><p class="text-center width-150"><?= $model['cust_parentcomp'] ?></p></td>
                    <td colspan="2"><p class="text-center width-150"><?= !empty($model['total_investment'])?bcsub($model['total_investment'], 0, 2):'' ?></p>
                    </td>
                    <td colspan="2"><p class="text-center width-150"><?= !empty($model['shareholding_ratio'])?bcsub($model['shareholding_ratio'], 0, 2).'%':'' ?>
                            </p></td>
                    <td></td>
                </tr>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['contact']) + 1 ?>">客户联系方式</th>
                    <th>姓名</th>
                    <th colspan="2">电子邮箱</th>
                    <th colspan="2">电话(手机)</th>
                    <th colspan="2">其他</th>
                </tr>
                <?php if (!empty($model['contact'])) { ?>
                    <?php foreach ($model['contact'] as $val) { ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['ccper_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['ccper_mail'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['ccper_mobile'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['ccper_remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100"
                        rowspan="<?= count($model['turnover']) == 0 ? 2 : count($model['turnover']) + 1; ?>">营业额
                    </th>
                    <th>币别</th>
                    <th colspan="2"><?= date('Y', time()) - 1 ?></th>
                    <th colspan="2"><?= date('Y', time()) - 2 ?></th>
                    <th colspan="2"><?= date('Y', time()) - 3 ?></th>
                </tr>
                <?php if (!empty($model['turnover'])) { ?>
                    <?php foreach ($model['turnover'] as $key => $val) { ?>
                        <tr>
                            <td><?= $model['t_currency']['bsp_svalue'] ?></td>
                            <td colspan="2"><?= !empty($val[date('Y', time()) - 1])?bcsub($val[date('Y', time()) - 1], 0, 2):'' ?></td>
                            <td colspan="2"><?= !empty($val[date('Y', time()) - 2])?bcsub($val[date('Y', time()) - 2], 0, 2):'' ?></td>
                            <td colspan="2"><?= !empty($val[date('Y', time()) - 3])?bcsub($val[date('Y', time()) - 3], 0, 2):'' ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['linkcomp']) + 1 ?>">子公司及关联公司</th>
                    <th>公司名称</th>
                    <th colspan="2">关联性质</th>
                    <th colspan="2">投资金额</th>
                    <th colspan="2">持股比例(%)</th>
                </tr>
                <?php if (!empty($model['linkcomp'])) { ?>
                    <?php foreach ($model['linkcomp'] as $val) { ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['linc_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['relational_character'] ?></p>
                            </td>
                            <td colspan="2"><p
                                    class="text-center width-150"><?= !empty($val['total_investment'])?bcsub($val['total_investment'], 0, 2):'' ?></p></td>
                            <td colspan="2"><p
                                    class="text-center width-150"><?= !empty($val['shareholding_ratio'])?bcsub($val['shareholding_ratio'], 0, 2):'' ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['custCustomer']) + 1 ?>">主要客户</th>
                    <th>公司名称</th>
                    <th colspan="2">占营收比率(%)</th>
                    <th colspan="2">电话</th>
                    <th colspan="2">备注</th>
                </tr>
                <?php if (!empty($model['custCustomer'])) { ?>
                    <?php foreach ($model['custCustomer'] as $val) { ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['cc_customer_name'] ?></p></td>
                            <td colspan="2"><p
                                    class="text-center width-150"><?= !empty($val['cc_customer_ratio'])?bcsub($val['cc_customer_ratio'], 0, 2):'' ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_tel'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['supplier']) + 1 ?>">主要供应商</th>
                    <th>公司名称</th>
                    <th colspan="2">付款条件</th>
                    <th colspan="2">电话</th>
                    <th colspan="2">备注</th>
                </tr>
                <?php if (!empty($model['supplier'])) { ?>
                    <?php foreach ($model['supplier'] as $val) { ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['cc_customer_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['caluse'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_tel'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['cc_customer_remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th class="width-100" rowspan="<?= count($model['bank']) + 1 ?>">主要往来银行</th>
                    <th>银行名称</th>
                    <th colspan="2">账号</th>
                    <th colspan="2">往来项目</th>
                    <th colspan="2">备注</th>
                </tr>
                <?php if (!empty($model['supplier'])) { ?>
                    <?php foreach ($model['bank'] as $val) { ?>
                        <tr>
                            <td><p class="text-center width-150"><?= $val['bank_name'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['account_num'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['curremt_project'] ?></p></td>
                            <td colspan="2"><p class="text-center width-150"><?= $val['remark'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <h2 class="head-second mt-20">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">公司三证信息</a>
        </h2>
        <div class="ftp">
            <?php if ($crmcertf['crtf_type'] == 0) { ?>
                <?php if (!empty($crmcertf['bs_license'])) { ?>
                    <div class="inline-block img-border">
                        <img class="img"
                             src="<?= ($ftp->ftp_file_exists('/cmp/bslcns/' . $newnName1 . '/' . $crmcertf['bs_license']) == true) ? Yii::$app->ftpPath['httpIP'] . '/cmp/bslcns/' . $newnName1 . '/' . $crmcertf['bs_license'] : Url::to('@web/img/layout/403.png') ?>"
                             alt="">
                        <div class="img-title">
                            <a href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>">公司营业执照</a>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($crmcertf['tx_reg'])) { ?>
                    <div class="inline-block img-border">
                        <img class="img"
                             src="<?= ($ftp->ftp_file_exists('/cmp/txrg/' . $newnName2 . '/' . $crmcertf['tx_reg']) == true) ? Yii::$app->ftpPath['httpIP'] . '/cmp/txrg/' . $newnName2 . '/' . $crmcertf['tx_reg'] : Url::to('@web/img/layout/403.png') ?>"
                             alt="">
                        <div class="img-title">
                            <a href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txrg/<?= $newnName2 ?>/<?= $crmcertf['tx_reg'] ?>">税务登记证</a>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!empty($crmcertf['qlf_certf'])) { ?>
                    <div class="inline-block img-border">
                        <img class="img"
                             src="<?= ($ftp->ftp_file_exists('/cmp/txqlf/' . $newnName3 . '/' . $crmcertf['qlf_certf']) == true) ? Yii::$app->ftpPath['httpIP'] . '/cmp/txqlf/' . $newnName3 . '/' . $crmcertf['qlf_certf'] : Url::to('@web/img/layout/403.png') ?>"
                             alt="">
                        <div class="img-title">
                            <a href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>">一般纳税人资格证</a>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="crmcertf">
                    <?php if (!empty($crmcertf['bs_license'])) { ?>
                        <div class="inline-block img-border">
                            <img class="img"
                                 src="<?= ($ftp->ftp_file_exists('/cmp/bslcns/' . $newnName1 . '/' . $crmcertf['bs_license']) == true) ? Yii::$app->ftpPath['httpIP'] . '/cmp/bslcns/' . $newnName1 . '/' . $crmcertf['bs_license'] : Url::to('@web/img/layout/403.png') ?>"
                                 alt="">
                            <div class="img-title">
                                <a href="<?= \Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>">公司三证合一</a>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($crmcertf['qlf_certf'])) { ?>
                        <div class="inline-block img-border">
                            <img class="img"
                                 src="<?= ($ftp->ftp_file_exists('/cmp/txqlf/' . $newnName3 . '/' . $crmcertf['qlf_certf']) == true) ? Yii::$app->ftpPath['httpIP'] . '/cmp/txqlf/' . $newnName3 . '/' . $crmcertf['qlf_certf'] : Url::to('@web/img/layout/403.png') ?>"
                                 alt="">
                            <div class="img-title">
                                <a href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>">一般纳税人资格证</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <h2 class="head-three">
                <span style="color:#1f7ed0;">附件</span>
            </h2>
            <div class="mb-10">
                <label class="width-100 label-align">客户信息签字档：</label>
                <?php if (!empty($model['file1'])) { ?>
                    <?php foreach ($model['file1'] as $key7 => $val7) { ?>
                        <span class="value-align"><a
                                href="<?= Yii::$app->ftpPath['httpIP'] ?>/cca/credit/<?= $val7['date_file'] ?>/<?= $val7['file_new'] ?>"><?= $val7['file_old'] ?></a></span>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="mb-10">
                <label class="width-100 label-align">附件：</label>
                <?php if (!empty($model['file2'])) { ?>
                    <?php foreach ($model['file2'] as $key8 => $val8) { ?>
                        <span class="value-align"><a
                                href="<?= Yii::$app->ftpPath['httpIP'] ?>/cca/credit/<?= $val8['date_file'] ?>/<?= $val8['file_new'] ?>"><?= $val8['file_old'] ?></a></span>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <h2 class="head-second mt-20">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">签核记录</a>
    </h2>
    <div class="mb-30">
        <div class="mb-20">
            <div id="record" width="100%"></div>
        </div>
    </div>
</div>
</div>
<script>
    $(function () {
        $(document).on("input propertychange", function () {
            var sum = 0;
            $('.money').each(function () {
                sum += $(this).val() * 1;
            })
            $('.total_amount').val(sum);
        });
        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record']);?>?id=" + <?= $id ?>,
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
            singleSelect: true,
            columns: [[
                {field: "verifyOrg", title: "审核节点", width: 150},
                {field: "verifyName", title: "审核人", width: 150},
                {field: "vcoc_datetime", title: "审核时间", width: 156},
                {field: "verifyStatus", title: "操作", width: 150},
                {field: "vcoc_remark", title: "审核意见", width: 200},
                {field: "vcoc_computeip", title: "审核IP", width: 150},
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
                datagridTip('#record');
            }
        });
        $("#pass").on("click", function () {
            if ($('#check-form').form('validate')) {
                var id = $('#cid').val();
                <?php if($model['creditType'] !== 'AP担保' && empty($child['file'])){ ?>
                var file_new = $('.file_new').val();
                if (file_new == undefined) {
                    layer.alert('请上传文件', {icon: 2});
                    return false;
                }
                <?php } ?>
                $.fancybox({
                    href: "<?=Url::to(['pass-opinion'])?>?id=" + id + '&form=' + $('#check-form').serialize(),
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 435,
                    height: 240
                });
            }
        });

        $("#reject").on("click", function () {
            var id = $('#cid').val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + id + '&form=' + $('#check-form').serialize(),
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 435,
                height: 240
            });
        });
    })

    function time(object) {
        WdatePicker({
            skin:"whyGreen",
            dateFmt:"yyyy-MM-dd",
            minDate:"%y-%M-%d",
            onpicked:function(){
                $(object).validatebox('validate');
            }
        });


    }
</script>