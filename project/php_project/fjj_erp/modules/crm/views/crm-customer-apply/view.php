<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = ' 客户代码申请详情';

if ($model["apply_status"] == 10) {
    $this->title = "  客户代码申请详情";
}
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '客户代码申请列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
$regname = unserialize($model['cust_regname']);
$regnumber = unserialize($model['cust_regnumber']);
?>

<style>
    .label-width {
        width: 120px;
    }

    .value-width {
        width: 200px;
    }
</style>

<div class="content">
    <h2 class="head-first">
        <?php if ($model["apply_status"] != 10) {
            $this->title = " 客户代码申请详情";
        }
        ?>
        <?= $this->title; ?>
        <span class="head-code">客户编号:<?= $model['cust_filernumber'] ?></span>
    </h2>
    <div class="mb-10 float-right" style="font-weight:700;">

    </div>
    <div class="border-bottom">
        <?php if ($status == 50 || $status == 40) { ?>
            <?= Menu::isAction('/crm/crm-customer-apply/customer-info') ? Html::button('修改', ['class' => 'button-mody', 'style' => 'width:80px;margin-left:10px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["customer-info"]) . '?id=' . $model['cust_id'] . '\'']) : '' ?>
        <?php } ?>
        <?php if ($status == 50 || $status == 20) { ?>
            <?= Menu::isAction('/crm/crm-customer-apply/reviewer') ? Html::button('送审', ['class' => 'button-check', 'id' => 'check', 'style' => 'width:80px;margin-left:10px;']) : '' ?>
        <?php } ?>
            <?= Menu::isAction('/crm/crm-customer-apply/index') ? Html::button('切换列表', ['class' => 'button-change', 'style' => 'width:80px;margin-left:10px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>客户基本信息</a>
    </h2>
    <!--    <div>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">客户全称:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_sname'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">客户简称:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_shortname'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">客户代码:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $capplyInfo['applyno'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="8%">公司电话:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_tel1'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="3%">传真:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_fax'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="6%">客户类型:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['custType'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">客户来源:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['custSource'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">所在地区:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">--><? //= $model['area'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">营销区域:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['saleArea'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="6%">联系人:</td>-->
    <!--                <td class="no-border vertical-center" width="10%">-->
    <? //= $model['cust_contacts'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="13%"></td>-->
    <!--                <td class="no-border vertical-center" width="7%">联系电话:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_tel2'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="6%">邮箱:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_email'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">客户经理人:</td>-->
    <!--                <td class="no-border vertical-center"-->
    <!--                    width="18%">--><? //= $model['personinch']['code'] ? $model['personinch']['code'] : ""; ?>
    <!--                    &nbsp;-->
    <? //= $model['personinch']['manager'] ? $model['personinch']['manager'] : ""; ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">详细地址:</td>-->
    <!--                <td class="no-border vertical-center"-->
    <!--                    width="87%">-->
    <? //= $model['district'][0]['district_name'] . $model['district'][1]['district_name'] . $model['district'][2]['district_name'] . $model['district'][3]['district_name'] . $model['cust_adress'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--    </div>-->
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10"
               style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户全称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_sname'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户简称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['cust_shortname'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">客户代码<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['apply_code'] ?></td>
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
    <!--    <div class="display-none">-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">公司属性:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['compvirtue'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">公司规模:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['compscale'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">行业类别:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['industryType'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">经营类型:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['businessType'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">客户等级:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['custLevel'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">员工人数:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_personqty'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">注册时间:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_regdate'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">注册资金:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_regfunds'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">注册币别:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['member_regcurr'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">是否上市公司:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_islisted'] == 1 ? "是" : "否"; ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">是否公司会员:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_ismember'] == 1 ? "是" : "否" ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">会员类型:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['memberType'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">潜在需求:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['latDemand'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">需求类目:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['category_name'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="4%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">旺季分布:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_bigseason'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">交易币别:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['bsPubdata']['memberCurr'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="3%"></td>-->
    <!--                <td class="no-border vertical-center" width="13%">年营业额:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['member_compsum'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="13%"></td>-->
    <!--                <td class="no-border vertical-center" width="18%"></td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="6%">公司主页:</td>-->
    <!--                <td class="no-border vertical-center" width="45%">-->
    <? //= $model['member_compwebside'] ?><!--</td>-->
    <!--                <td class="no-border vertical-center" width="17%">税籍编码/统一社会信用代码:</td>-->
    <!--                <td class="no-border vertical-center" width="18%">-->
    <? //= $model['cust_tax_code'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        --><?php //foreach ($regname as $key => $val) { ?>
    <!--            <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--                <tr class="no-border">-->
    <!--                    <td width="8%" class="no-border vertical-center">登记证名称-->
    <?php //echo $key + 1; ?><!--:</td>-->
    <!--                    <td width="26%" class="no-border vertical-center">--><? //= $val ?><!--</td>-->
    <!--                    <td width="8%" class="no-border vertical-center">登记证号码-->
    <?php //echo $key + 1; ?><!--:</td>-->
    <!--                    <td width="26%" class="no-border vertical-center">-->
    <? //= $regnumber[$key] ?><!--</td>-->
    <!--                    <td width="31%" class="no-border vertical-center"></td>-->
    <!--                </tr>-->
    <!--            </table>-->
    <!--        --><?php //} ?>
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td width="10%" class="no-border vertical-center">申请的发票类型:</td>-->
    <!--                <td width="26%" class="no-border vertical-center">-->
    <? //= $model['invoiceType'] ?><!--</td>-->
    <!--                <td width="8%" class="no-border vertical-center">发票抬头:</td>-->
    <!--                <td width="26%" class="no-border vertical-center">-->
    <? //= $model['invoice_title'] ?><!--</td>-->
    <!--                <td width="31%" class="no-border vertical-center"></td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">发票抬头地址:</td>-->
    <!--                <td class="no-border vertical-center"-->
    <!--                    width="87%">-->
    <? //= $model['invoiceTitleDistrict'][0]['district_name'] . $model['invoiceTitleDistrict'][1]['district_name'] . $model['invoiceTitleDistrict'][2]['district_name'] . $model['invoiceTitleDistrict'][3]['district_name'] . $model['invoice_title_address'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">发票邮寄地址:</td>-->
    <!--                <td class="no-border vertical-center"-->
    <!--                    width="87%">-->
    <? //= $model['invoiceMailDistrict'][0]['district_name'] . $model['invoiceMailDistrict'][1]['district_name'] . $model['invoiceMailDistrict'][2]['district_name'] . $model['invoiceMailDistrict'][3]['district_name'] . $model['invoice_mail_address'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td width="13%" class="no-border vertical-center">经营范围说明:</td>-->
    <!--                <td width="87%" class="no-border vertical-center">-->
    <? //= $model['member_businessarea'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td width="10%" class="no-border vertical-center">总公司:</td>-->
    <!--                <td width="26%" class="no-border vertical-center">-->
    <? //= $model['member_compwebside'] ?><!--</td>-->
    <!--                <td width="10%" class="no-border vertical-center">公司负责人:</td>-->
    <!--                <td width="26%" class="no-border vertical-center">-->
    <? //= $model['cust_inchargeperson'] ?><!--</td>-->
    <!--                <td width="31%" class="no-border vertical-center"></td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--        <table width="90%" class="no-border vertical-center ml-25 mb-10">-->
    <!--            <tr class="no-border">-->
    <!--                <td class="no-border vertical-center" width="13%">总公司地址:</td>-->
    <!--                <td class="no-border vertical-center"-->
    <!--                    width="87%">-->
    <? //= $model['districtCompany'][0]['district_name'] . $model['districtCompany'][1]['district_name'] . $model['districtCompany'][2]['district_name'] . $model['districtCompany'][3]['district_name'] . $model['cust_headquarters_address'] ?><!--</td>-->
    <!--            </tr>-->
    <!--        </table>-->
    <!--    </div>-->

    <div class="display-none">
        <table width="90%" class="no-border vertical-center mb-10"
               style="border-collapse:separate; border-spacing:5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">公司属性<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compvirtue'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">公司规模<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['compscale'] ?></td>
                <td class="no-border vertical-center label-align" width="10%">行业类别<label>：</label></td>
                <td class="no-border vertical-center value-align" width="20%"><?= $model['industryType'] ?></td>
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
                <td class="no-border vertical-center value-align" width="20%"><?= !empty($model['member_compsum'])?$model['member_compsum']:'' ?></td>
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
                <td class="no-border vertical-center value-align" colspan="3" width="20%"><?= $model['invoType'] ?></td>
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
            <input type="hidden" value="<?= $crmcertf['yn_spp'] ?>" id="ynspp">
            <label class=" label-align" style="width: 161px;" for="">是否供应商：</label>
            <span class=" value-align" style="width: 100px;"><?php if ($crmcertf['yn_spp'] == 1) echo "是"; else echo "否"; ?></span>
            <span style="display: none;float: right;margin-right:483px;" id="custcode">
            <label class="lable-width label-align ">供应商代码：</label>
                <?= $crmcertf['spp_no'] ?>
        </span>
        </div>
        <div class="mb-10">
            <input type="hidden" id="crtftype" value="<?= $crmcertf['crtf_type'] ?>">
            <label class=" label-align" style="width: 161px;" for="">证件类型：</label>
            <span class=" value-align"
                  style="width: 100px;"><?php if ($crmcertf['crtf_type'] == 1) echo "新版三证合一"; else echo "旧版三证"; ?></span>
            <span style="color: #989898">证件格式：JPG、PNG、TIF，PDF、BMP、压缩档7z、rar、zip等文件小于2M。</span>
        </div>
        <div class="mb-10">
            <label class=" label-align"  for="">税籍编码/统一社会信用代码：</label>
            <span><?= $model['cust_tax_code'] ?></span>
        </div>
        <div class="mb-10">
            <label
                class=" label-align" style="width: 161px;"
                id="business">公司营业执照：</label>
            <span class=" value-align" style="width: 500px;"><a
                    href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/bslcns/<?= $newnName1 ?>/<?= $crmcertf['bs_license'] ?>"><?= $crmcertf['o_license'] ?></a></span>
        </div>
        <div class="mb-10" id="tax">
            <label class=" label-align" style="width: 161px;">税务登记证：</label>
            <span class="value-align" style="width: 500px;"><a
                    href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txrg/<?= $newnName2 ?>/<?= $crmcertf['tx_reg'] ?>"><?= $crmcertf['o_reg'] ?></a></span>
        </div>
        <div class="mb-10">
            <label class=" label-align" style="width: 161px;">一般纳税人资格证：</label>
            <span class=" value-align" style="width: 500px;"><a
                    href="<?= Yii::$app->ftpPath['httpIP'] ?>/cmp/txqlf/<?= $newnName3 ?>/<?= $crmcertf['qlf_certf'] ?>"><?= $crmcertf['o_cerft'] ?></a></span>
        </div>
        <div class="mb-10">
            <label class=" label-align vertical-top" style="width: 161px;">备注：</label>
            <span class="value-align" style="width: 600px;vertical-align:middle" name="CrmC[marks]"
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
    <!--<div class="mb-30">
        <div class="mb-10">
            <div id="checkInfo"></div>
        </div>
    </div>-->
    <!--<h1 class="head-second text-center">签核记录</h1>-->
    <div class="mb-10">
        <table class="product-list" style="width:990px;">
            <thead>
            <tr>
                <th class="width-60">序号</th>
                <th class="width-70">签核节点</th>
                <th class="width-60">签核人员</th>
                <th>签核日期</th>
                <th class="width-60">操作</th>
                <th>签核意见</th>
                <th>签核人IP</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($verify as $key => $val) { ?>
                <tr>
                    <th><?= $key + 1 ?></th>
                    <th><?= $val['verifyOrg'] ?></th>
                    <th><?= $val['verifyName'] ?></th>
                    <th><?= $val['vcoc_datetime'] ?></th>
                    <th><?= $val['verifyStatus'] ?></th>
                    <th><?= $val['vcoc_remark'] ?></th>
                    <th><?= $val['vcoc_computeip'] ?></th>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <!--<div class="value-width label-aligin margin-auto">
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>-->
</div>
<script>
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
            $("#business").text("公司三证合一证：");
        }
        else {
            $("#new").attr('checked', false);
            $("#old").attr('checked', 'checked');
            $("#tax").css('display', 'block');
            $("#business").text("公司营业执照证：");
        }

        $(".head-three").next("div:eq(0)").css("display", "block");
//        $(".head-three").next("div:eq(1),div:eq(2),div:eq(3)").css("display", "none");
        $(".head-three>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
            $(".retable").datagrid("resize");
        });
        /*主要联系人*/
        $("#contact").datagrid({
            url: "<?= Url::to(['/crm/crm-customer-manage/contact-person'])?>?id=" + "<?= $model['cust_id'] ?>",
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
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-device']);?>?id=" + "<?= $model['cust_id'] ?>",
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
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-product']);?>?id=" + "<?= $model['cust_id'] ?>",
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
            url: "<?= Url::to(['/crm/crm-customer-manage/cust-main-customer']);?>?id=" + "<?= $model['cust_id'] ?>",
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
        $("#check").on("click", function () {
            var id = "<?=$capplyId?>";
            var url = "<?=Url::to(['index'], true)?>";
            var type = "<?= $typeId ?>";
            $.fancybox({
                href: "<?=Url::to(['reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });

        var isApply = "<?= $isApply ?>";
        var capplyId = "<?= $capplyId ?>";
        var viewId = "<?= $model['cust_id'] ?>";
        if (isApply == 1) {
            var id = capplyId;
            var url = "<?=Url::to(['index'], true)?>?id=" + viewId + "&iss=" + '<?=$iss?>';
            var type =<?= $typeId ?>;
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                closeBtn: false,
                autoSize: false,
                width: 750,
                height: 480
            });
        }
    })

</script>