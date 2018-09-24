<?php
/**
 * User: F3859386
 * Date: 2017/2/13
 * Time: 上午 09:33
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '招商会员开发', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '客户信息'];
$this->title = "客户信息";
?>
<style>
    .wd-150{
        width: 150px;
    }
    .tt{
        display: block;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
<div class="content">
    <h2 class="head-first">
        <?= $this->title ?>
        <span class="head-code">编号：<?= $model['cust_filernumber'] ?></span>
    </h2>

    <div class="mb-10">
        <button id="edit" class="button-blue">修改</button>
        <button id="remove" class="button-blue hiden">删除</button>
        <button id="to-list" class="button-blue">切换列表</button>
<!--        <button class="button-blue" onclick="window.history.go(-1)">返回</button>-->
        <!--        <button id="switch_sale" class="button-blue">转销售</button>-->
    </div>
    <div style="height:2px;background:#9acfea;" class="mb-10"></div>
    <h2 class="head-second">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">客户基本信息</a>
    </h2>
    <div class=" mb-10">
        <table width="90%" class="no-border vertical-center ml-25 mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">公司名称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_sname']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">公司简称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_shortname']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">公司电话<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_tel1']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">邮编<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['member_compzipcode'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">联系人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_contacts'] ?>
                    &nbsp;<?= $model['cust_position'] ?></td>
                <td class="no-border vertical-center label-align" width="5%">部门<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_department']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">职位职能<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_function'] ?></td>
                <td class="no-border vertical-center label-align" width="5%">联系方式<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_tel2'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">邮箱<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_email'] ?></td>
                <td class="no-border vertical-center label-align" width="5%">注册网站<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= ($model['bsPubdata']['memberWeb'] != "") ? $model['bsPubdata']['memberWeb'] : "无" ?></td>
            </tr>
            <tr class="no-border mb-10 <?= ($model['bsPubdata']['memberWeb'] == "") ? "hiden" : "" ?>">
                <td class="no-border vertical-center label-align" width="5%">用户名<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= $model['member_name'] ?></td>
                <td class="no-border vertical-center label-align" width="5%">注册时间<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['member_regtime'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">详细地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3"
                    width="89.2%"><?= $model['district'][0]['district_name'] . $model['district'][1]['district_name'] . $model['district'][2]['district_name'] . $model['district'][3]['district_name'] . $model['cust_adress'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">客户详细信息</a>
    </h2>
    <div class=" mb-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">法人代表<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= $model['cust_inchargeperson']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">注册时间<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['cust_regdate']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">注册货币<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['regCurrency']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">注册资金<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= empty($model['cust_regfunds']) ? "" : $model['cust_regfunds']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">公司类型<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['compvirtue']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">客户来源<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['custSource']; ?></td>

            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">经营范围<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['businessType'] ?></td>
                <td class="no-border vertical-center label-align" width="5%">交易币种<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= $model['bsPubdata']['memberCurr']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">年营业额<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= empty($model['member_compsum']) ? "" : $model['member_compsum']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">年采购额<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= empty($model['cust_pruchaseqty']) ? "" : $model['cust_pruchaseqty']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">员工人数<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= empty($model['cust_personqty']) ? "" : $model['cust_personqty']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">发票需求<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['member_compreq']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">潜在需求<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['latDemand']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">需求类目<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['category_name']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">需求类别<label>：</label></td>
                <td class="no-border vertical-center value-align"
                    width="18%"><?= $model['member_reqdesription']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">主要市场<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['member_marketing']; ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">主要客户<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['member_compcust']; ?></td>
                <td class="no-border vertical-center label-align" width="5%">主页<label>：</label></td>
                <td class="no-border vertical-center value-align" width="18%"><?= $model['member_compwebside']; ?></td>

            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">范围说明<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3"
                    width="89.2%"><?= $model['member_businessarea'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="5%">备注<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3"><?= $model['member_remark'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">其他联系人信息</a>
    </h2>
    <div class="mb-10" style="margin-left: 40px">
        <p class="mb-10 ">客户其他联系人<label>：</label></p>
        <table class=" table" style="width: 98%">
            <thead>
            <th>序号</th>
            <th>姓名</th>
            <th>职位</th>
            <th>电子邮箱</th>
            <th>电话(手机)</th>
            <th>备注</th>
            </thead>
            <tbody>
            <?php foreach ($model["contacts"] as $k => $contact) { ?>
                <tr align="center">
                    <td width="150"><p class="tt wd-150"><?= $k + 1 ?></p></td>
                    <td width="150"><p class="tt wd-150"><?= $contact['ccper_name'] ?></p></td>
                    <td width="150"><p class="tt wd-150"><?= $contact['ccper_post'] ?></p></td>
                    <td width="150"><p class="tt wd-150"><?= $contact['ccper_mail'] ?></p></td>
                    <td width="150"><p class="tt wd-150"><?= $contact['ccper_mobile'] ?></p></td>
                    <td width="150"><p class="tt wd-150"><?= $contact['ccper_remark'] ?></p></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    table input {
        outline: none;
        border: none;
    }

    .head-second + div {
        display: none;
    }
</style>
<script>
    $(function () {

        $("#edit").click(function () {
            window.location.href = "<?=Url::to(['update', 'id' => $model['cust_id']])?>";
        });
        $("#remove").click(function () {
            layer.confirm("确定删除？", {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                $.get("<?=Url::to(['delete', 'id' => $model['cust_id']])?>", function (res) {
                    obj = JSON.parse(res);
                    if (obj.flag == 1) {
                        layer.alert("删除成功", {icon: 2, time: 5000});
                        window.location.href = "<?=Url::to(['index'])?>";
                    } else {
                        layer.alert("删除失败", {icon: 2, time: 5000});
                    }
                });
            });
        });

        $("#to-list").click(function () {
            window.location.href = "<?=Url::to(['index'])?>";
        });


//        $("#switch_sale").on("click", function () {
//            layer.confirm("确定要转销售吗?",
//                {
//                    btn: ['确定', '取消'],
//                    icon: 2
//                },
//                function () {
//                    $.ajax({
//                        type: "get",
//                        dataType: "json",
//                        async: false,
//                        data: {"str": <?//= $model['cust_id'] ?>//},
//                        url: "<?//=Url::to(['/crm/crm-member/turn-sales']) ?>//",
//                        success: function (msg) {
//                            if (msg.flag === 1) {
//                                layer.alert(msg.msg, {
//                                    icon: 1, end: function () {
//                                        location.href = '<?//= Url::to(['index']) ?>//'
//                                    }
//                                });
//                            } else {
//                                layer.alert(msg.msg, {icon: 2})
//                            }
//                        },
//                        error: function (msg) {
//                            layer.alert(msg.msg, {icon: 2})
//                        }
//                    })
//                },
//                function () {
//                    layer.closeAll();
//                }
//            )
//        });

        $(".head-second").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-right");
            $(this).prev().toggleClass("icon-caret-down");
        });
    });
</script>

<style>
    button:hover {
        border: none;
    }
</style>