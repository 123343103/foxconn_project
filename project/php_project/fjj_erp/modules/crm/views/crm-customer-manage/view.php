<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/16
 * Time: 10:36
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;

$this->title = '客戶详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '我的客户', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '客户详情'];
$regname = unserialize(Html::decode($model['cust_regname']));
$regnumber = unserialize(Html::decode($model['cust_regnumber']));
?>
<style type="text/css">
    .head-three + div {
        display: none;
    }

    .panel-header {
        height: 10px !important;
    }

    i {
        color: #1f7ed0;
        font-size: 16px;
    }

    .panel-tool a {
        opacity: 1;
    }

    label, span {
        height: 25px;
        line-height: 25px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    tr {
        line-height: 25px;
        height: 25px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">客户编号：<?= $model['cust_filernumber'] ?></span>
    </h1>
    <div class="border-bottom mb-20 pb-10">
        <?php if ($model['apply_status'] != 20 && $model['apply_status'] != 30 && $model['saleStatus'] == "10") { ?>
            <?= Menu::isAction('/crm/crm-customer-info/delete') ? Html::button('删除', ['class' => 'button-blue width-80', 'id' => 'delete']) : '' ?>
        <?php } ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?php if (!$model['cust_code'] && $model["apply_status"] != 20 && $model["apply_status"] != 30 && $model['saleStatus'] == "10") { ?>
            <?= Menu::isAction('/crm/crm-customer-apply/customer-info') ? Html::button('申请客户代码', ['style' => 'width:100px;', 'class' => 'button-blue', 'onclick' => 'window.location.href=\'' . Url::to(["/crm/crm-customer-apply/customer-info"]) . '?id=' . $model['cust_id'] . '&status=10\'']) : '' ?>
        <?php } ?>
    </div>
    <div class="space-10 overflow-auto"></div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>客户基本信息</a>
        <ul class="float-right">
            <!--            <li class="float-left mr-10"><a href="-->
            <? //= Url::to(['/crm/crm-customer-info/create']) ?><!--"><i-->
            <!--                            class="icon-plus-sign"></i></a></li>-->
            <?php if (!$model["cust_code"] && $model["apply_status"] != 20 && $model["apply_status"] != 30 && $model['saleStatus'] == "10") { ?>
                <li class="float-left mr-10"><a title="客户基本信息修改"
                                                href="<?= Url::to(['/crm/crm-customer-manage/base-customer']) ?>?id=<?= $id ?>"
                                                class="update-baseinfo"><i
                            class="icon-edit"></i></a></li>
            <?php } ?>
        </ul>
    </h2>

    <div>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">客户全称：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_sname']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">客户简称：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_shortname']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">客户代码：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['apply_code']; ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">公司电话：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_tel1']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">传真：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_fax']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">客户类型：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['custType']; ?></td>
            </tr>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">客户来源：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['custSource']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">所在地区：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['area']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">营销区域：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['saleArea']; ?></td>
            </tr>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">联系人：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_contacts']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">联系电话：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_tel2']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">邮箱：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_email']; ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">客户经理人：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['manager']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">详细地址：</td>
                <td width="55%"
                    class="no-border vertical-center value-align"><?= $model['district'][0]['district_name'] . $model['district'][1]['district_name'] . $model['district'][2]['district_name'] . $model['district'][3]['district_name'] . $model['cust_adress'] ?></td>
            </tr>
        </table>
    </div>

    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>客户详细信息</a>
        <ul class="float-right">
            <?php if (!$model["cust_code"] && $model["apply_status"] != 20 && $model["apply_status"] != 30 && $model['saleStatus'] == "10") { ?>
                <li class="float-left mr-10"><a title="客户详细信息修改"
                                                href="<?= Url::to(['/crm/crm-customer-manage/company-customer']) ?>?id=<?= $id ?>"
                                                class="update-cmpinfo"><i class="icon-edit"></i></a></li>
            <?php } ?>
        </ul>
    </h2>
    <div>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">公司属性：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['compvirtue']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">公司规模：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['compscale']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">行业类别：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['industryType']; ?></td>
            </tr>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">客户等级：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['custLevel']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">经营类型：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['businessType']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">员工人数：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_personqty']; ?></td>
            </tr>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">注册时间：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_regdate']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">注册资金：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_regfunds']; ?></td>
                <td width="11%" class="no-border vertical-center label-align">注册货币：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['dealCurrency']; ?></td>
            </tr>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">是否上市公司：</td>
                <td width="22%"
                    class="no-border vertical-center value-align"><?= $model['cust_islisted'] == 1 ? "是" : "否"; ?></td>
                <td width="11%" class="no-border vertical-center label-align">是否公司会员：</td>
                <td width="22%"
                    class="no-border vertical-center value-align"><?= $model['cust_ismember'] == 1 ? "是" : "否" ?></td>
                <td width="11%" class="no-border vertical-center label-align">会员类型：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['memberType'] ?></td>
            </tr>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">潜在需求：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['latDemand'] ?></td>
                <td width="11%" class="no-border vertical-center label-align">需求类目：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['category_name'] ?></td>
                <td width="11%" class="no-border vertical-center label-align">旺季分布：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_bigseason'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">交易币别：</td>
                <td width="22%"
                    class="no-border vertical-center value-align"><?= $model['bsPubdata']['memberCurr'] ?></td>
                <td width="11%" class="no-border vertical-center label-align">年营业额：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['member_compsum'] ?></td>
                <td width="11%" class="no-border vertical-center label-align">公司主页：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['member_compwebside'] ?></td>
            </tr>
        </table>


        <?php foreach ($regname as $key => $val) { ?>
            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="11%" class="no-border vertical-center label-align">登记证名称<?php echo $key + 1; ?>：</td>
                    <td width="22%" class="no-border vertical-center value-align"><?= $val ?></td>
                    <td width="11%" class="no-border vertical-center label-align">登记证号码<?php echo $key + 1; ?>：</td>
                    <td width="55%" class="no-border vertical-center value-align"><?= $regnumber[$key] ?></td>
                </tr>
            </table>
        <?php } ?>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">申请的发票类型：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['invoiceType'] ?></td>
                <td width="11%" class="no-border vertical-center label-align">具备开票类型：</td>
                <td width="55%" class="no-border vertical-center value-align"><?= $model['invoType'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">发票抬头：</td>
                <td width="88%" class="no-border vertical-center value-align"><?= $model['invoice_title'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">发票抬头地址：</td>
                <td width="88%"
                    class="no-border vertical-center value-align"><?= $model['invoiceTitleDistrict'][0]['district_name'] . $model['invoiceTitleDistrict'][1]['district_name'] . $model['invoiceTitleDistrict'][2]['district_name'] . $model['invoiceTitleDistrict'][3]['district_name'] . $model['invoice_title_address'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">发票寄送地址：</td>
                <td width="88%"
                    class="no-border vertical-center value-align"><?= $model['invoiceMailDistrict'][0]['district_name'] . $model['invoiceMailDistrict'][1]['district_name'] . $model['invoiceMailDistrict'][2]['district_name'] . $model['invoiceMailDistrict'][3]['district_name'] . $model['invoice_mail_address'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">经营范围：</td>
                <td width="88%" class="no-border vertical-center value-align"><?= $model['member_businessarea'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">总公司：</td>
                <td width="22%" class="no-border vertical-center value-align"><?= $model['cust_parentcomp'] ?></td>
                <td width="11%" class="no-border vertical-center label-align">公司负责人：</td>
                <td width="55%" class="no-border vertical-center value-align"><?= $model['cust_inchargeperson'] ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="11%" class="no-border vertical-center label-align">总公司地址：</td>
                <td width="88%"
                    class="no-border vertical-center value-align"><?= $model['districtCompany'][0]['district_name'] . $model['districtCompany'][1]['district_name'] . $model['districtCompany'][2]['district_name'] . $model['districtCompany'][3]['district_name'] . $model['cust_headquarters_address'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-right"></i>
        <a>认证信息</a>
        <ul class="float-right">
            <?php if (!$model["cust_code"] && $model["apply_status"] != 20 && $model["apply_status"] != 30 && $model['saleStatus'] == "10") { ?>
                <li class="float-left mr-10"><a title="认证信息修改"
                                                href="<?= Url::to(['/crm/crm-customer-manage/auth-info']) ?>?id=<?= $id ?>"
                                                class="update"><i class="icon-edit"></i></a></li>
            <?php } ?>
        </ul>
    </h2>
    <div>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="20%" class="no-border vertical-center label-align">是否供应商：</td>
                <td width="22%"
                    class="no-border vertical-center value-align"><?= $crmcertf['yn_spp'] ? "是" : "否" ?></td>
                <td width="14%"
                    class="no-border vertical-center label-align"><?= $crmcertf['yn_spp'] ? "供应商代码：" : "" ?></td>
                <td width="52%"
                    class="no-border vertical-center value-align"><?= $crmcertf['yn_spp'] ? $crmcertf['spp_no'] : "" ?></td>
            </tr>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="20%" class="no-border vertical-center label-align">证件类型：</td>
                <td width="22%" class="no-border vertical-center value-align">
                    <?php
                    switch ($crmcertf['crtf_type']) {
                        case 0:
                            echo "旧版三证";
                            break;
                        case 1:
                            echo "新版三证合一";
                            break;
                        default:
                            break;
                    }
                    ?>
                </td>
                <td width="66%" class="no-border vertical-center value-align"><span
                        style="margin-left: 48px;color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
                </td>
            </tr>

            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="20%">税籍编码/统一社会信用代码：</td>
                <td class="no-border vertical-center value-align" colspan="3"
                    width="20%"><?= $model['cust_tax_code'] ?></td>
            </tr>
        </table>


        <?php if ($crmcertf['crtf_type'] == 0) { ?>
            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="20%" class="no-border vertical-center label-align">公司营业执照：</td>
                    <td width="80%" class="no-border vertical-center value-align"><a
                            href="<?= \Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>"><?= $crmcertf['o_license'] ?></a>
                    </td>
                </tr>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="20%" class="no-border vertical-center label-align">税务登记证：</td>
                    <td width="80%" class="no-border vertical-center value-align"><a
                            href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txrg/<?= $newnName2 ?>/<?= $crmcertf['tx_reg'] ?>"><?= $crmcertf['o_reg'] ?></a>
                    </td>
                </tr>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="20%" class="no-border vertical-center label-align">一般纳税人资格证：</td>
                    <td width="80%" class="no-border vertical-center value-align"><a
                            href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>"><?= $crmcertf['o_cerft'] ?></a>
                    </td>
                </tr>
            </table>
        <?php } else { ?>
            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="20%" class="no-border vertical-center label-align">公 司 三 证 合 一：</td>
                    <td width="80%" class="no-border vertical-center value-align"><a
                            href="<?= \Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>"><?= $crmcertf['o_license'] ?></a>
                    </td>
                </tr>
            </table>

            <table width="90%" class="no-border vertical-center mb-10">
                <tr class="no-border mb-10">
                    <td width="20%" class="no-border vertical-center label-align">一般纳税人资格证：</td>
                    <td width="80%" class="no-border vertical-center value-align"><a
                            href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>"><?= $crmcertf['o_cerft'] ?></a>
                    </td>
                </tr>
            </table>
        <?php } ?>
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td width="20%" class="no-border vertical-center label-align">备注：</td>
                <td width="80%" class="no-border vertical-center value-align"><?= $crmcertf['marks'] ?></td>
            </tr>
        </table>


    </div>
    <div>
        <div class="float-left" style="width:470px;">
            <div id="contact_module">
                <h2 class="head-three" onclick="contact()">
                    <i class="icon-caret-right"></i>
                    <a>联系人</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a
                           href="<?= Url::to(['/crm/crm-customer-manage/customer-contact-person']) ?>?id=<?= $id ?>"
                           class="update"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="contact" class="retable">
                    </div>
                </div>
            </div>
            <div id="claim_module">
                <h2 class="head-three" onclick="claimInfo()">
                    <i class="icon-caret-right"></i>
                    <a>认领信息</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                    </div>
                    <div id="claimInfo" class="retable">
                    </div>
                </div>
            </div>
            <div id="cost_module">
                <h2 class="head-three" onclick="costInfo()">
                    <i class="icon-caret-right"></i>
                    <a>费用信息</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <div class="panel-tool">
                            <ul class="float-right">

                            </ul>
                        </div>
                    </div>
                    <div id="costInfo" class="retable">
                    </div>
                </div>
            </div>
            <div id="quotation_module">
                <h2 class="head-three" onclick="quotedPrice()">
                    <i class="icon-caret-right"></i>
                    <a>报价单</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <div class="panel-tool">
                            <ul class="float-right">
                            </ul>
                        </div>
                    </div>
                    <div id="quotedPrice" class="retable">
                    </div>
                </div>
            </div>
            <div id="plan_module">
                <h2 class="head-three" onclick="visitPlan()">
                    <i class="icon-caret-right"></i>
                    <a>拜访计划</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a  href="<?= Url::to(['/crm/crm-visit-plan/create']) ?>?customerId=<?= $id ?>"><i
                                class="icon-plus-sign"></i></a>
                    </div>
                    <div id="visitPlan" class="retable">
                    </div>
                </div>
            </div>
            <div id="record_module">
                <h2 class="head-three" onclick="visitRecord()">
                    <i class="icon-caret-right"></i>
                    <a>拜访记录</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a  href="<?= Url::to(['/crm/crm-visit-record/add']) ?>?customerId=<?= $id ?>"><i
                                class="icon-plus-sign"></i></a>
                    </div>
                    <div id="visitRecord" class="retable">
                    </div>
                </div>
            </div>
            <div id="device_module">
                <h2 class="head-three" onclick="custDevice()">
                    <i class="icon-caret-right"></i>
                    <a>设备</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a
                           href="<?= Url::to(['/crm/crm-customer-manage/cust-device-info']) ?>?id=<?= $id ?>"
                           class="device"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="custDevice" class="retable">
                    </div>
                </div>
            </div>
            <div id="main_customer_module">
                <h2 class="head-three" onclick="mainCustomer()">
                    <i class="icon-caret-right"></i>
                    <a>主要客户</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a
                           href="<?= Url::to(['/crm/crm-customer-manage/main-customer']) ?>?id=<?= $id ?>"
                           class="main-customer"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="mainCustomer" class="retable">
                    </div>
                </div>
            </div>
        </div>

        <div class="float-right" style="width:470px;">
            <div id="company_module">
                <h2 class="head-three" onclick="relateCompany()">
                    <i class="icon-caret-right"></i>
                    <a>子公司及关联公司</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a  id="linkComp_add"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="relateCompany" class="retable">
                    </div>
                </div>
            </div>
            <div id="order_module">
                <h2 class="head-three" onclick="customerOrder()">
                    <i class="icon-caret-right"></i>
                    <a>客户订单</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <div class="panel-tool">
                            <ul class="float-right">

                            </ul>
                        </div>
                    </div>
                    <div id="customerOrder" class="retable">
                    </div>
                </div>
            </div>
            <div id="business_product_module">
                <h2 class="head-three" onclick="businessProduct()">
                    <i class="icon-caret-right"></i>
                    <a>商机商品</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a
                           href="<?= Url::to(['/crm/crm-customer-manage/cust-oddsitem']) ?>?id=<?= $id ?>"
                           class="main-product"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="businessProduct" class="retable">
                    </div>
                </div>
            </div>
            <div id="follow_module">
                <h2 class="head-three" onclick="projectFollow()">
                    <i class="icon-caret-right"></i>
                    <a>项目跟进</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <div class="panel-tool">
                            <ul class="float-right">

                            </ul>
                        </div>
                    </div>
                    <div id="projectFollow" class="retable">
                    </div>
                </div>
            </div>
            <div id="purchase_module">
                <h2 class="head-three" onclick="purchase()">
                    <i class="icon-caret-right"></i>
                    <a>采购</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a
                           href="<?= Url::to(['/crm/crm-customer-manage/cust-purchase-info']) ?>?id=<?= $id ?>"
                           class="main-product"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="purchase" class="retable">
                    </div>
                </div>
            </div>
            <div id="cooperation_product_module">
                <h2 class="head-three" onclick="cooperationProduct()">
                    <i class="icon-caret-right"></i>
                    <a>合作商品</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <div class="panel-tool">
                            <ul class="float-right">

                            </ul>
                        </div>
                    </div>
                    <div id="cooperationProduct" class="retable">
                    </div>
                </div>
            </div>
            <div id="main_product_module">
                <h2 class="head-three" onclick="mainProduct()">
                    <i class="icon-caret-right"></i>
                    <a>主营产品</a>
                </h2>
                <div class="mb-20">
                    <div class="panel-header title-color" style="height:20px;">
                        <a  href="<?= Url::to(['/crm/crm-customer-manage/main-product']) ?>?id=<?= $id ?>"
                           class="main-product"><i class="icon-plus-sign"></i></a>
                    </div>
                    <div id="mainProduct" class="retable">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="width-200 margin-auto clear">
    </div>
</div>
<script>
    var id = "<?= $id ?>";
    $(function () {
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
            $("#business").text("公司三证合一证");
        }
        else {
            $("#new").attr('checked', false);
            $("#old").attr('checked', 'checked');
            $("#tax").css('display', 'block');
            $("#business").text("公司营业执照证");
        }


        $(".head-three").next("div:eq(0),div:eq(1)").css("display", "block");
        $(".head-three>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
//            $(".retable").datagrid("resize");
//            var ele=$(this).next().find(".retable");
//            showEmpty(".retable");
        });
//        ajaxSubmitForm($("#add-form"));
        $('.disName').on("change", function () {
            var $select = $(this);
            getNextDistrict($select);
        });


        var id = "<?= $model['cust_id'] ?>";
//        var str = eval(<?//= $resultList ?>//);
//        var strVisit = eval(<?//= $visit ?>//);

//        window.onload=function(){
//            if(str.custPersonInch.total != 0){
//                $("#claim").hide();
//            }else{
//                $("#claim").show();
//            }
//        }


        $(".update-baseinfo,.update-cmpinfo").fancybox({
            padding: [],
            fitToView: false,
            width: 840,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
        $(".update").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
        $(".device").fancybox({
            padding: [],
            fitToView: false,
            width: 400,
            height: 250,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
        $(".main-customer").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 380,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
        $(".main-product").fancybox({
            padding: [],
            fitToView: false,
            width: 550,
            height: 380,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });

        //编辑拜访计划 --start--
        $("#edit-plan").on("click", function () {
            var a = $("#visitPlan").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条拜访计划信息!", {icon: 2, time: 5000});
            } else {
                var svpId = $("#visitPlan").datagrid("getSelected")['svp_id'];

                $("#edit-plan").attr("href", "<?= Url::to(['/crm/crm-customer-manage/visit-plan-info']) ?>?svpId=" + svpId + "&id=" +<?= $id ?>);
                $("#edit-plan").fancybox({
                    padding: [],
                    fitToView: false,
                    width: 800,
                    height: 530,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                });
            }
        })
        //编辑拜访计划--end--
        //编辑拜访记录--start--
        $("#edit-info").on("click", function () {
            var a = $("#visitRecord").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条拜访记录信息!", {icon: 2, time: 5000});
            } else {
                var silId = $("#visitRecord").datagrid("getSelected")['sil_id'];

                $("#edit-info").attr("href", "<?= Url::to(['/crm/crm-customer-manage/visit-plan-record']) ?>?silId=" + silId + "&id=" +<?= $id ?>);
                $("#edit-info").fancybox({
                    padding: [],
                    fitToView: false,
                    width: 800,
                    height: 530,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                });
            }
        })
        //编辑拜访记录--end--

        //修改认领信息--start--
        $("#edit-personinch").on("click", function () {
            var a = $("#claimInfo").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条认领信息!", {icon: 2, time: 5000});
            } else {
                var ccpichId = $("#claimInfo").datagrid("getSelected")['ccpich_id'];

                $("#edit-personinch").attr("href", "<?= Url::to(['/crm/crm-customer-manage/person-inch']) ?>?id=" +<?= $id ?> +"&ccpichId=" + ccpichId + "&status=10");
                $("#edit-personinch").fancybox({
                    padding: [],
                    fitToView: false,
                    width: 700,
                    height: 530,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                });
            }
        })
        //修改认领信息--end--

        //修改报价单--start--
        $("#edit-quote-price").on("click", function () {
            var a = $("#quotedPrice").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条联系人信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#quotedPrice").datagrid("getSelected")['sapl_id'];

                $("#edit-quote-price").attr("href", "<?= Url::to(['/crm/crm-quote-price/edit']) ?>?id=" + id);
            }
        })
        //修改报价单--end--
        //删除
        $("#delete").on("click", function () {
            var index = layer.confirm("确定要删除这条记录吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"id": <?= $model['cust_id'] ?>},
                        url: "<?=Url::to(['/crm/crm-customer-info/delete']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        location.href = '<?= Url::to(['index']) ?>'
                                    }
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (msg) {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });
        $("#linkComp_add").fancybox({
            href: "<?= Url::to(['/crm/crm-customer-manage/link-comp']) ?>?id=<?= $id ?>",
            padding: [],
            fitToView: false,
            width: 750,
            height: 450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });
    });
    //遞歸清除級聯選項
    function clearOption($select) {
        if ($select == null) {
            $select = $("#disName_1")
        }
        $tagNmae = $select.next().prop("tagName");
        if ($select.next().length != 0 && $tagNmae == 'SELECT') {
            $select.next().html('<option value=>请选择...</option>');
            clearOption($select.next());
        }
    }

    function getNextDistrict($select) {
        var id = $select.val();
        //console.log(id);
        if (id == "") {
            clearOption($select);
            return;
        }
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {"id": id},
            url: "<?=Url::to(['/ptdt/firm/get-district']) ?>",
            success: function (data) {
                var $nextSelect = $select.next("select");
                clearOption($nextSelect);
                $nextSelect.html('<option value>请选择...</option>')
                if ($nextSelect.length != 0)
                    for (var x in data) {
                        $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                    }
            }

        })
    }
    /*联系人修改*/
    function changePerson(id, type) {
        $("#edit-contact").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/customer-contact-person']) ?>?id=" +<?= $id ?> +"&ccperId=" + id + "&type=" + type
        });
    }

    /*设备信息修改*/
    function changeDevice(id) {
        $("#edit-device").fancybox({
            padding: [],
            fitToView: false,
            width: 400,
            height: 250,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/cust-device-info']) ?>?id=" + <?= $id ?> +"&custdId=" + id,
        });
    }

    /*主营产品修改*/
    function changeProduct(id) {
        $("#edit-main-product").fancybox({
            padding: [],
            fitToView: false,
            width: 550,
            height: 380,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/main-product']) ?>?id=" +<?= $id ?> +"&ccpId=" + id
        });
    }

    /*采购修改*/
    function changePurchase(id) {
        $("#edit-cust-purchase").fancybox({
            padding: [],
            fitToView: false,
            width: 550,
            height: 380,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/cust-purchase-info']) ?>?id=" +<?= $id ?> +"&cpurchId=" + id
        });
    }
    /*商机商品修改*/
    function changeBusiness(id) {
        $("#edit-cust-oddsitem").fancybox({
            padding: [],
            fitToView: false,
            width: 550,
            height: 380,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/cust-oddsitem']) ?>?id=" +<?= $id ?> +"&oddsId=" + id
        });
    }
    /*主要客户修改*/
    function changeCustomer(id) {
        $("#edit-main-customer").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/main-customer']) ?>?id=" +<?= $id ?> +"&ccId=" + id
        });
    }
    /*关联公司修改*/
    function changeComp(id) {
        $("#edit-linkcomp").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 530,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['/crm/crm-customer-manage/link-comp']) ?>?id=" +<?= $id ?> +"&lincId=" + id
        });
    }

    /*删除*/
    function deleteMessage(id, str, name) {
        layer.confirm("确定删除?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id, "str": str},
                    url: "<?= Url::to(['delete-message']) ?>",
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    $('#' + name).datagrid('reload')
                                }
                            });
                        } else {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    },
                    error: function (msg) {
                        layer.alert(msg.msg, {icon: 2})
                    }
                })
            },
            function () {
                layer.closeAll();
            }
        )
    }
    /*取消/终止拜访计划*/
    function showcause(type, id, cause) {
        $.fancybox({
            type: "iframe",
            width: 400,
            height: 260,
            autoSize: false,
            href: "<?=Url::to(['/crm/crm-visit-plan/cause'])?>?svp_id=" + id + "&type=" + type + "&cause=" + cause,
            padding: 0
        });
    }
    /*删除拜访计划*/
    function deleteRecord(id) {
        layer.confirm('确定删除吗？', {icon: 2},
            function () {
                $.ajax({
                    url: "<?=Url::to(['/crm/crm-visit-record/delete-child']);?>",
                    data: {"childId": id},
                    dataType: "json",
                    success: function (data) {
                        if (data.flag == 1) {
                            layer.alert(data.msg, {icon: 1}, function () {
                                layer.closeAll();
                                $("#visitRecord").datagrid('reload').datagrid('clearSelections');
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                })
            },
            layer.closeAll()
        );
    }

    /*联系人*/
    function contact() {
        $("#contact").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/contact-person']);?>?id=" + id,
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
                {field: "sex", title: "性别", width: 100},
                {field: "ccper_post", title: "职务", width: 100},
                {field: "ccper_deparment", title: "部门", width: 100},
                {field: "ccper_birthday", title: "生日", width: 100},
                {field: "ccper_tel", title: "座机", width: 100},
                {field: "ccper_mobile", title: "手机", width: 120},
                {field: "ccper_fax", title: "传真", width: 120},
                {field: "ccper_mail", title: "邮箱", width: 120},
                {field: "ccper_qq", title: "QQ", width: 120},
                {field: "ccper_wechat", title: "微信", width: 120},
                {
                    field: "ccper_ismain", title: "是否主要联系人", width: 120, formatter: function (value, row, index) {
                    return value == 1 ? "是" : "否";
                }
                },
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-contact" onclick="changePerson(' + row.ccper_id + ',' + '2' + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.ccper_id + ',' + '\'delete-person\'' + ',' + '\'contact\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#contact');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });
        //        $('#contact').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.contactPerson);
    }

    /*拜访计划*/
    function visitPlan() {
        $("#visitPlan").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/visit-plan']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "svp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "svp_code", title: "档案编号", width: 100},
                {field: "visitPerson", title: "拜访人", width: 100},
                {field: "visitType", title: "拜访类型", width: 100},
                {field: "start", title: "开始时间", width: 150},
                {field: "end", title: "结束时间", width: 150},
                {field: "svp_content", title: "计划内容", width: 150},
                {field: "status", title: "状态", width: 100}
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#visitPlan');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });
        //        $('#visitPlan').datagrid({loadFilter:pagerFilter}).datagrid('loadData', strVisit.visitPlan);
    }

    /*拜访记录*/
    function visitRecord() {
        $("#visitRecord").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/visit-record']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "sil_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {
                    field: "sil_code", title: "档案编号", width: 100, formatter: function (value, row) {
                    return '<a href="<?=Url::to(['/crm/crm-visit-record/view-record'])?>?childId=' + row.sil_id + '" style="color:#0000aa; ">' + row.sil_code + '</a>';
                }
                },
                {
                    field: "visitPersonName", title: "拜访人", width: 70, formatter: function (value, row) {
                    return row.visitPerson ? row.visitPerson : '';
                }
                },
                {field: "visit_type", title: "拜访类型", width: 70},
                {field: "start", title: "开始时间", width: 150},
                {field: "end", title: "结束时间", width: 150},
                {field: "sil_interview_conclus", title: "拜访总结", width: 140},
                {
                    field: "related_plan", title: "关联拜访计划", width: 100, formatter: function (value, row) {
                    if (row.visitPlan) {
                        return '<a href="<?=Url::to(['/crm/crm-visit-plan/view'])?>?id=' + row.svp_plan_id + '" style="color:#0000aa; ">' + row.related_plan + '</a>';
                    }
                }
                }

            ]],

            onLoadSuccess: function (data) {
                datagridTip('#visitRecord');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });
    }
    //        $('#visitRecord').datagrid({loadFilter:pagerFilter}).datagrid('loadData', strVisit.visitRecord);
    /*设备*/
    function custDevice() {
        $("#custDevice").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-device']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "custd_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "custd_id", title: "设备表ID", width: 10, hidden: true},
                {field: "type", title: "设备类型", width: 150},
//                {field: "nqty", title: "设备数量", width: 150},
                {field: "brand", title: "设备品牌", width: 150},
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-device" onclick="changeDevice(' + row.custd_id + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.custd_id + ',' + '\'delete-device\'' + ',' + '\'custDevice\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],

            onLoadSuccess: function (data) {
                datagridTip('#custDevice');
                setMenuHeight();
                showEmpty($(this), data.total, 1);
            }
        });
    }
    //        $('#custDevice').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.custDevice);
    /*主营产品*/
    function mainProduct() {
        $("#mainProduct").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-product']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "ccp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "ccp_sname", title: "主营产品", width: 150},
                {field: "ccp_model", title: "规格/型号", width: 150},
                {field: "ccp_annual", title: "年产量", width: 150},
                {field: "ccp_brand", title: "品牌", width: 150},
                {field: "ccp_remark", title: "备注", width: 150},
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-main-product" onclick="changeProduct(' + row.ccp_id + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.ccp_id + ',' + '\'delete-product\'' + ',' + '\'mainProduct\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],

            onLoadSuccess: function (data) {
                datagridTip('#mainProduct');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });
    }
    //        $('#mainProduct').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.mainProduct);
    /*主要客户*/
    function mainCustomer() {
        $("#mainCustomer").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-customer']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "cc_customerid",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "cc_customerid", title: "主营客户ID", width: 10, hidden: true},
                {field: "cc_customer_name", title: "公司名称", width: 150},
                {field: "customer_type", title: "经营类型", width: 100},
                {field: "cc_customer_tel", title: "公司电话", width: 100},
                {field: "cc_customer_ratio", title: "占营收比率(%)", width: 100},
                {field: "cc_customer_person", title: "负责人", width: 100},
                {field: "cc_customer_mobile", title: "联系电话", width: 100},
                {field: "cc_customer_remark", title: "备注", width: 150},
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-main-customer" onclick="changeCustomer(' + row.cc_customerid + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.cc_customerid + ',' + '\'delete-customer\'' + ',' + '\'mainCustomer\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],

            onLoadSuccess: function (data) {
                datagridTip('#mainCustomer');
                setMenuHeight();
                showEmpty($(this), data.total, 1);
            }
        });

    }
    //        $('#mainCustomer').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.mainCustomer);
    /*商机商品*/
    function businessProduct() {
        $("#businessProduct").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/check-cust-oddsitem']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "odds_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
//                {field: "odds_id", title: "商机商品ID", width: 10, hidden: true},
                {field: "typeName", title: "商品类别", width: 150},
                {field: "odds_sname", title: "品名", width: 150},
                {field: "odds_model", title: "规格型号", width: 100},
                {field: "brand", title: "品牌", width: 100},
                {field: "remark", title: "备注", width: 100},
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-cust-oddsitem" onclick="changeBusiness(' + row.odds_id + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.odds_id + ',' + '\'delete-business\'' + ',' + '\'businessProduct\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#businessProduct');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });

    }
    //        $('#businessProduct').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.custOddsitrm);
    /*关联公司*/
    function relateCompany() {
        $("#relateCompany").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-link-comp']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "linc_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {
                    field: "linc_name", title: "公司名称", width: 150, formatter: function (val, row) {
                    return '<span>' + row.linc_name + '</span>';
                }
                },
                {
                    field: "total_investment", title: "投资金额", width: 150, formatter: function (val, row) {
//                    return '<span title="'+ row.total_investment+'('+ row.investment_cur +')' +'">'+ row.total_investment+'('+ row.investment_cur +')' + '</span>';
                    return '<span title="' + row.total_investment + '">' + row.total_investment + '</span>';
                }
                },
                {
                    field: "shareholding_ratio", title: "持股比率(%)", width: 150, formatter: function (val, row) {
                    return '<span title="' + row.shareholding_ratio + '">' + row.shareholding_ratio + '</span>';
                }
                },
                {
                    field: "lincType", title: "经营类型", width: 150, formatter: function (val, row) {
                    return '<span title="' + row.lincType + '">' + row.lincType + '</span>';
                }
                },
                {
                    field: "linc_date", title: "注册时间", width: 150, formatter: function (val, row) {
                    return '<span title="' + row.linc_date + '">' + row.linc_date + '</span>';
                }
                },
                {
                    field: "linc_incpeople", title: "公司负责人", width: 150, formatter: function (val, row) {
                    return '<span title="' + row.linc_incpeople + '">' + row.linc_incpeople + '</span>';
                }
                },
                {
                    field: "linc_tel", title: "联系电话", width: 150, formatter: function (val, row) {
                    return '<span title="' + row.linc_tel + '">' + row.linc_tel + '</span>';
                }
                },
                {
                    field: "short_address", title: "公司地址", width: 300, formatter: function (val, row) {
                    return '<span title="' + row.address + '">' + row.address + '</span>';
                }
                },
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-linkcomp" onclick="changeComp(' + row.linc_id + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.linc_id + ',' + '\'delete-linkcomp\'' + ',' + '\'relateCompany\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],
            onLoadSuccess: function (data) {
//                datagridTip('#relateCompany');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });

    }
    //        $('#relateCompany').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.linkComp);
    /*认领信息*/
    function claimInfo() {
        $("#claimInfo").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-person-inch']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "ccpich_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "saleArea", title: "认领军区", width: 150},
                {field: "manager_name", title: "客户经理人", width: 150},
                {field: "manager_code", title: "工号", width: 150},
                {field: "sts_name", title: "销售点", width: 150},
                {field: "sale_name", title: "销售代表", width: 150},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#claimInfo');
                setMenuHeight();
                showEmpty($(this), data.total, 1);
            }
        });

    }
    //        $('#claimInfo').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.custPersonInch);
    /*采购信息*/
    function purchase() {
        $("#purchase").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-purchase']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "cpurch_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "itemname", title: "主要采购商品", width: 150},
                {field: "pruchasetype", title: "采购渠道", width: 150},
                {
                    field: "pruchasecost", title: "年采购额", width: 150, formatter: function (val, row) {
                    return row.pruchasecost;
                }
                },
                {
                    field: "action", title: "操作", width: 150, formatter: function (val, row) {
                    return '<a title="修改" id="edit-cust-purchase" onclick="changePurchase(' + row.cpurch_id + ')" style="color:#ccc;"><i class="icon-edit fs-18"></i></a>&nbsp;&nbsp;' + '&nbsp;&nbsp;<a title="删除" onclick="deleteMessage(' + row.cpurch_id + ',' + '\'delete-purchase\'' + ',' + '\'purchase\'' + ')" style="color:#ccc;"><i class="icon-minus-sign fs-18"></i></a>';
                }
                }
            ]],

            onLoadSuccess: function (data) {
                datagridTip('#purchase');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });

    }
    //        $('#purchase').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.custPurchase);
    /*订单信息*/
    function customerOrder() {
        $("#customerOrder").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/sale-order']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "soh_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "soh_code", title: "订单编号", width: 150},
                {field: "soh_source", title: "订单来源", width: 150},
                {field: "soh_type", title: "订单类型", width: 150},
                {field: "quantity", title: "订单数量", width: 100},
                {field: "soh_date", title: "下单时间", width: 100},
                {field: "bill_oamount", title: "订单总金额(含税)", width: 150},
                {field: "soh_person", title: "制单人", width: 150},
                {field: "status", title: "订单状态", width: 150},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#customerOrder');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });

    }
    //        $('#customerOrder').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.saleOrder);
    /*报价单信息*/
    function quotedPrice() {
        $("#quotedPrice").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/sale-quotedprice']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "sapl_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "saph_no", title: "报价单号", width: 150},
                {field: "part_no", title: "料号", width: 150},
                {field: "num", title: "品名", width: 100},
                {field: "date", title: "规格", width: 100},
                {field: "origin_unit_price", title: "单价(未税)", width: 150},
                {field: "origin_total_price", title: "单价(含税)", width: 150},
                {field: "origin_total_price", title: "折扣后总金额", width: 150},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#quotedPrice');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
            }
        });
    }
    //        $('#quotedPrice').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.quotedPrice);
    /*费用信息*/
    function costInfo() {
        $("#costInfo").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cost-info']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "type", title: "业务内容", width: 100},
                {field: "date", title: "发生时间", width: 150},
                {field: "shape", title: "交际形式", width: 150},
                {field: "address", title: "地址", width: 150},
                {field: "count", title: "参与人数", width: 150},
                {field: "cost", title: "总金额(RMB)", width: 150},
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#costInfo');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
//                $(this).datagrid("autoMergeCells", ['type']);
            }
            /*费用信息*/
        });

    }
    //        $('#costInfo').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.costInfo);
    /*费用信息分页-end-*/
    /*合作商品信息*/
    function cooperationProduct() {
        $("#cooperationProduct").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/cooperation-product']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "sol_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "productType", title: "产品类别", width: 150},
                {field: "productName", title: "具体商品", width: 150},
                {field: "saleName", title: "销售op", width: 150},
                {field: "main_pm", title: "大PM", width: 150},
                {field: "pm_leader", title: "PM负责人", width: 150}
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#cooperationProduct');
                setMenuHeight();
                showEmpty($(this), data.total, 0);
//                $(this).datagrid("autoMergeCells", ['productType']);
            }
        });
    }
    //        $('#cooperationProduct').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.cooperationProduct);
    /*项目跟进信息*/
    function projectFollow() {
        $("#projectFollow").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/project-follow']);?>?id=" + id,
            rownumbers: true,
            method: "get",
            idField: "pro_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "pro_code", title: "项目编号", width: 150},
                {field: "pro_code", title: "立项原因", width: 150},
                {field: "pro_child", title: "所立子专案", width: 150},
                {field: "pro_total_amount", title: "预计商机金额(RMB)", width: 150},
                {field: "pro_op", title: "销售（OP）", width: 150},
                {field: "pro_main_pm", title: "大PM", width: 150},
                {field: "pro_pm", title: "PM負責人", width: 150},
                {field: "pro_issue", title: "问题点", width: 150},
                {field: "pro_schedule", title: "处理进度", width: 150},
                {field: "pro_close", title: "结案情况", width: 150},
                {field: "pro_remark", title: "备注", width: 150}

            ]],
            onLoadSuccess: function (data) {
                datagridTip('#projectFollow');
                setMenuHeight();
                showEmpty($(this), data.total, 1);
            }
        });

    }
    //        $('#projectFollow').datagrid({loadFilter:pagerFilter}).datagrid('loadData', str.projectFollow);
</script>