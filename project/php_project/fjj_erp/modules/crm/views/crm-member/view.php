<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCustomerInfo */

$this->title = '会员详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '会员详情'];
?>
<style>
    .ml-10{
        margin-left: 10px;
    }
    .ml-150{
        margin-left: 150px;
    }
    .pb-10{
        padding-bottom: 10px;
    }
    .label-width{
        width:80px;
    }
    .value-width{
        width:300px;
    }
    .width-830{
        width:830px;
    }
    .width-100{
        width:100px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">会员编号：<?= $result['cust_filernumber'] ?></span>
    </h1>
    <div class="border-bottom mb-10  pb-10">
        <?= Menu::isAction('/crm/crm-member/update')?Html::button('修 &nbsp; 改', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $result['cust_id'] . '\'']):'' ?>
        <?= Menu::isAction('/crm/crm-member/delete-customer')?Html::button('删除', ['class' => 'button-blue width-100', 'type' => 'button', 'id' => 'delete']):'' ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Menu::isAction('/crm/crm-member/visit-create')?Html::button('新增回访', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["/crm/crm-return-visit/create"]) . '?id=' . $result['cust_id'].'&ctype=2' . '\'']):'' ?>
        <?php if($status['investment_status'] != 10){ ?>
        <?= Menu::isAction('/crm/crm-member/turn-investment')?Html::button('转招商开发', ['class' => 'button-blue width-100', 'type' => 'button', 'id' => 'investment']):'' ?>
        <?php } ?>
        <?php if($status['sale_status'] != 10){ ?>
        <?= Menu::isAction('/crm/crm-member/turn-sales')?Html::button('转销售', ['class' => 'button-blue width-100', 'id' => 'sales']):'' ?>
        <?php } ?>
    </div>
    <h2 class="head-second">
        <a href="javascript:void(0)">
            <i class="icon-caret-down"></i>
            会员信息
        </a>
    </h2>
    <div class="ml-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">用户名<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_name'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">注册网站<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['regWeb'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">会员类别<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['memberType'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">注册时间<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_regtime'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">
        <a href="javascript:void(0)">
            <i class="icon-caret-down"></i>
            客户基本信息
        </a>
    </h2>
    <div class="ml-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">公司名称<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_sname'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">公司简称<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_shortname'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">公司电话<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_tel1'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">邮编<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_compzipcode'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">联系人<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_contacts'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">职位<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_position'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">联系方式<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_tel2'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">邮箱<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_email'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">详细地址<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="3"
                    width="89.2%"><?= $result['district'][0]['district_name'] . $result['district'][1]['district_name'] . $result['district'][2]['district_name'] . $result['district'][3]['district_name'] . $result['cust_adress'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">
        <a href="javascript:void(0)">
            <i class="icon-caret-down"></i>
            客户详细信息
        </a>
    </h2>
    <div class="ml-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">法人代表<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_inchargeperson'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">注册时间<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_regdate'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">注册资金<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= empty($result['cust_regfunds'])?"":floor($result['cust_regfunds']) ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">注册货币<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['regCurrency'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">公司类型<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['compvirtue'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">客户来源<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['custSource'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">经营范围<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['businessType'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">交易币种<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['dealCurrency'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">年营业额<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= !empty($result['member_compsum'])?$result['member_compsum'].'万&nbsp;'.$result['member_compsum_name']:'' ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">年采购额<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= !empty($result['cust_pruchaseqty'])?$result['cust_pruchaseqty'].'万&nbsp;'.$result['cust_pruchaseqty_name']:'' ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">员工人数<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= !empty($result['cust_personqty'])?$result['cust_personqty']:'' ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">发票需求<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['compReq'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">潜在需求<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['latDemand'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">需求类目<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['productType'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">需求类别<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_reqdesription'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">主要市场<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_marketing'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">主要客户<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_compcust'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">主页<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['member_compwebside'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">范围说明<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="3"
                    width="89.2%"><?= $result['member_businessarea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">备注<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="3"
                    width="89.2%"><?= $result['member_remark'] ?></td>
            </tr>
        </table>
    </div>
</div>
<script>
    $(function () {
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).find('i').toggleClass("icon-caret-right");
            $(this).find('i').toggleClass("icon-caret-down");
        });
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
                        data: {"id": '<?= $result['cust_id'] ?>'},
                        url: "<?=Url::to(['delete-customer']) ?>",
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
        /*转招商*/
        $("#investment").on("click", function () {
            var index = layer.confirm("确定要转招商吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"str": <?= $result['cust_id'] ?>},
                        url: "<?=Url::to(['turn-investment']) ?>",
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
        /*转销售*/
        $("#sales").on("click", function () {
            var index = layer.confirm("确定要转销售吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"str": <?= $result['cust_id'] ?>},
                        url: "<?=Url::to(['turn-sales']) ?>",
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
    })
</script>
