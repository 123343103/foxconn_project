<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
/* @var $this yii\web\View */
/* @var $model app\modules\crm\models\CrmCustomerInfo */

$this->title = '客户信息';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '会员开发任务列表','url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '客户信息'];
?>
<style>
    .pb-10{
        padding-bottom: 10px;
    }
    .width-80{
        width:80px;
    }
    .width-120{
        width:120px;
    }

    .width-300{
        width:300px;
    }

</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <span class="head-code">编号：<?= $result['cust_filernumber'] ?></span>
    </h1>
    <div class="border-bottom mb-10 pb-10">
        <?= Menu::isAction('/crm/crm-member-develop/update')?Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $result['cust_id'] . '\'']):'' ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Menu::isAction('/crm/crm-member-develop/turn-member')?Html::button('转会员', ['class' => 'button-blue width-80', 'type' => 'button', 'id' => 'member']):'' ?>
        <?= Menu::isAction('/crm/crm-member-develop/turn-investment')?Html::button('转招商开发', ['class' => 'button-blue width-80', 'type' => 'button', 'id' => 'investment']):'' ?>
        <?= Menu::isAction('/crm/crm-member-develop/turn-sales')?Html::button('转销售', ['class' => 'button-blue width-80', 'id' => 'sales']):'' ?>
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
                <td class="no-border vertical-center qlabel-align" width="5%">部门<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_department'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">职位<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_position'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">职位职能<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['custFunction'] ?></td>
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
        <a href="javascript:void(0)" class="ml-10">
            <i class="icon-caret-right"></i>
            客户详细信息
        </a>
    </h2>
    <div class="ml-10 display-none">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">法人代表<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_inchargeperson'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">注册时间<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['cust_regdate'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">注册货币<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= $result['regCurrency'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">注册资金<label>：</label></td>
                <td class="no-border vertical-center" width="18%"><?= empty($result['cust_regfunds'])?"":floor($result['cust_regfunds']) ?></td>
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
            var index = layer.confirm("确定要删除该客户?",
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
                        url: "<?=Url::to(['delete']) ?>",
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
                        data: {"str": '<?= $result['cust_id'] ?>'},
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
                        data: {"str": '<?= $result['cust_id'] ?>'},
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
        /*转会员*/
        $('#member').on('click',function(){
            layer.confirm("确定要转会员吗?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },function(){
                    layer.closeAll('dialog');
                    var id = '<?= $result["cust_id"] ?>';
                    $.fancybox({
                        width:700,
                        height:450,
                        autoSize:false,
                        padding:0,
                        type:"iframe",
                        href:"<?=Url::to(['turn-member'])?>?id="+id+'&from=/crm/crm-member-develop/index',
                    });
                }
            )
        })
    })
</script>
