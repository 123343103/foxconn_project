<?php
/**
 * Created by PhpStorm.
 * User: F1679086
 * Date: 2017/2/25
 * Time: 14:50
 */
use yii\helpers\Html;
use yii\helpers\Url;
use app\classes\Menu;

$this->title = '拜访记录详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
if ($ctype == 3) {
    $this->params['breadcrumbs'][] = ['label' => '招商开发列表', 'url' => ['/crm/crm-investment-dvelopment/index']];
} elseif ($ctype == 4) {
    $this->params['breadcrumbs'][] = ['label' => '潜在客户列表', 'url' => ['/crm/crm-potential-customer/index']];
} elseif ($ctype == 6) {
    $this->params['breadcrumbs'][] = ['label' => '招商会员列表', 'url' => ['/crm/crm-investment-customer/list']];
} else {
    $this->params['breadcrumbs'][] = ['label' => '会员开发任务列表', 'url' => Url::to(['index'])];
}
$this->params['breadcrumbs'][] = ['label' => '拜访记录详情'];
?>
<style>
    .panel {
        margin-bottom: 10px;
    }

    .ml-25 {
        margin-left: 25px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <?php if (!empty($child)) { ?>
            <?php if (count($child) == 1) { ?>
                <span class="head-code">档案编号：<?= $child[0]['sil_code'] ?></span>
            <?php } else { ?>
                <?php if (!empty($record)) { ?>
                    <span class="head-code">档案编号：<?= $record['sih_code'] ?></span>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </h1>
    <div class="border-bottom mb-20">
        <?php if ($ctype == 1) { ?>
            <?php $urlList = Url::to(["index"]) ?>
        <?php } else if ($ctype == 3) { ?>
            <?php $urlList = Url::to(["/crm/crm-investment-dvelopment/index"]); ?>
        <?php } else if ($ctype == 6) { ?>
            <?php $urlList = Url::to(["/crm/crm-investment-customer/list"]); ?>
        <?php } else if ($ctype == 4) { ?>
            <?php $urlList = Url::to(["/crm/crm-potential-customer/index"]); ?>
        <?php } ?>
        <?php $updateUrl = Url::to(["visit-update", 'id' => $member['cust_id'], 'childId' => $childid, 'ctype' => $ctype]);
        ?>
        <?php if (count($child) == 1) { ?>
            <?php $updateUrl = Url::to(["visit-update", 'id' => $member['cust_id'], 'childId' => $child[0]['sil_id'], 'ctype' => $ctype]);?>
            <?= Menu::isAction('/crm/crm-member-develop/visit-update') && $type == 0 ? Html::Button('修改', ['class' => 'button-blue mb-10 ml-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . $updateUrl . '\'']) : ''; ?>
            <?= Menu::isAction('/crm/crm-member-develop/delete') && $type == 0 ? Html::Button('删除', ['class' => 'button-blue mb-10 ml-10', 'style' => 'width:90px;', 'type' => 'button', 'onclick' => 'delete_child(' . $child[0]['sil_id'] . ')']) : '' ?>
        <?php } ?>
        <?= Html::Button('切换列表', ['class' => 'button-blue mb-10 ml-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . $urlList . '\'']) ?>
        <?php if (count($child) != 1) { ?>
            <?php if ($ctype == 1 || $ctype == 4) { ?>
                <?= Html::Button('转会员', ['class' => 'button-blue mb-10 ml-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'member']) ?>
                <?= Html::Button('转招商开发', ['class' => 'button-blue mb-10 ml-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'investment']) ?>
                <?= Html::Button('转销售', ['class' => 'button-blue mb-10 ml-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'sales']) ?>

            <?php } ?>
        <?php } ?>
    </div>
    <h2 class="head-second">客户信息</h2>
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10"
               style="border-collapse: separate; border-spacing: 5px;">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="6%">公司名称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="25%"><?= $member['cust_sname'] ?></td>
                <td class="no-border vertical-center label-align" width="6%">公司简称<label>：</label></td>
                <td class="no-border vertical-center value-align" width="25%"><?= $member['cust_shortname'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="6%">公司电话<label>：</label></td>
                <td class="no-border vertical-center value-align" width="25%"><?= $member['cust_tel1'] ?></td>
                <td class="no-border vertical-center label-align" width="6%">联系人<label>：</label></td>
                <td class="no-border vertical-center value-align" width="25%"><?= $member['cust_contacts'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="6%">职位<label>：</label></td>
                <td class="no-border vertical-center value-align" width="25%"><?= $member['cust_position'] ?></td>
                <td class="no-border vertical-center label-align" width="6%">联系方式<label>：</label></td>
                <td class="no-border vertical-center value-align" width="25%"><?= $member['cust_tel2'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="6%">详细地址<label>：</label></td>
                <td class="no-border vertical-center value-align" colspan="3"
                    width="25%"><?= $member['district'][0]['district_name'] . $member['district'][1]['district_name'] . $member['district'][2]['district_name'] . $member['district'][3]['district_name'] . $member['cust_adress'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">拜访信息</h2>
    <?php if (!empty($child)) { ?>
        <?php if (count($child) == 1) { ?>
            <div class="mb-20  mt-10">
                <table width="90%" class="no-border vertical-center mb-10"
                       style="border-collapse: separate; border-spacing: 5px;">
                    <tr class="no-border mb-10">
                        <td width="6%" class="no-border vertical-center label-align">拜访人<label>：</label></td>
                        <td width="25%"
                            class="no-border vertical-center value-align"><?= $child[0]['visitPerson'] ?></td>
                        <td width="6%" class="no-border vertical-center label-align">拜访类型<label>：</label></td>
                        <td width="25%"
                            class="no-border vertical-center value-align"><?= $child[0]['visitTypeName'] ?></td>
                    </tr>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="6%">开始时间<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="25%"><?= substr($child[0]['start'], 0, 16) ?></td>
                        <td class="no-border vertical-center label-align" width="6%">结束时间<label>：</label></td>
                        <td class="no-border vertical-center value-align"
                            width="25%"><?= substr($child[0]['end'], 0, 16) ?></td>
                    </tr>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="6%">拜访内容<label>：</label></td>
                        <td class="no-border vertical-center value-align" colspan="3"
                            width="25%"><?= $child[0]['sil_process_descript'] ?></td>
                    </tr>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="6%">反馈/总结<label>：</label></td>
                        <td class="no-border vertical-center value-align" colspan="3"
                            width="25%"><?= $child[0]['sil_interview_conclus'] ?></td>
                    </tr>
                    <tr class="no-border mb-10">
                        <td class="no-border vertical-center label-align" width="6%">备注<label>：</label></td>
                        <td class="no-border vertical-center value-align" colspan="3"
                            width="25%"><?= $child[0]['remark'] ?></td>
                    </tr>
                </table>
            </div>
        <?php } else { ?>
            <?php foreach ($child as $key => $val) { ?>
                <div class="easyui-panel"
                     data-options="collapsible:true<?= $key == 0 ? "" : ",collapsed:true" ?>,onCollapse:function(){setMenuHeight()},onExpand:function(){setMenuHeight()}"
                     title="<?= "<span style='color:red;'>拜访时间：" . $val['start'] . '~' . $val['end'] . "</span>" ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= Menu::isAction('/crm/crm-member-develop/visit-update') && $key == 0 && $type == 0 ? "<a href='" . Url::to(['visit-update', 'id' => $member['cust_id'], 'childId' => $val['sil_id'], 'ctype' => $ctype]) . "'>修改</a>" : "" ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= Menu::isAction('/crm/crm-member-develop/delete') && $key == 0 && $type == 0 ? "<a onclick='delete_child(" . $val['sil_id'] . ")'>删除</a>" : "" ?>">
                    <div class="mb-20 mt-10">
                        <table width="90%" class="no-border vertical-center mb-10"
                               style="border-collapse: separate; border-spacing: 5px;">
                            <tr class="no-border mb-10">
                                <td width="6%" class="no-border vertical-center label-align">拜访人<label>：</label></td>
                                <td width="25%"
                                    class="no-border vertical-center value-align"><?= $val['visitPerson'] ?></td>
                                <td width="6%" class="no-border vertical-center label-align">拜访类型<label>：</label></td>
                                <td width="25%"
                                    class="no-border vertical-center value-align"><?= $val['visitTypeName'] ?></td>
                            </tr>

                            <tr class="no-border mb-10">
                                <td class="no-border vertical-center label-align" width="6%">开始时间<label>：</label></td>
                                <td class="no-border vertical-center value-align" width="25%"><?= $val['start'] ?></td>
                                <td class="no-border vertical-center label-align" width="6%">结束时间<label>：</label></td>
                                <td class="no-border vertical-center value-align" width="25%"><?= $val['end'] ?></td>
                            </tr>

                            <tr class="no-border mb-10">
                                <td class="no-border vertical-center label-align" width="6%">拜访内容<label>：</label></td>
                                <td class="no-border vertical-center value-align" colspan="3"
                                    width="25%"><?= $val['sil_process_descript'] ?></td>
                            </tr>

                            <tr class="no-border mb-10">
                                <td class="no-border vertical-center label-align" width="6%">反馈/总结<label>：</label>
                                </td>
                                <td class="no-border vertical-center value-align" colspan="3"
                                    width="25%"><?= $val['sil_interview_conclus'] ?></td>
                            </tr>
                            <tr class="no-border mb-10">
                                <td class="no-border vertical-center label-align" width="6%">备注<label>：</label></td>
                                <td class="no-border vertical-center value-align" colspan="3"
                                    width="25%"><?= $val['remark'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
        <table width="90%" class="no-border vertical-center ml-25 mb-10">
            <tr class="no-border">
                <td class="no-border vertical-center text-center" width="100%">无详情</td>
            </tr>
        </table>
    <?php } ?>
</div>
<script>
    $(function () {

        $("#more").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'inline'
        });
    });
    <?php if(!empty($child)){ ?>
    function delete_child(childId) {
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
                    data: {"id": '<?= $member['sih_id']?>', "childId": childId},
                    url: "<?=$ctype==4?Url::to(['delete-visit']):Url::to(['delete']) ?>",
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    if (<?= $num ?> == 0
                                    )
                                    {
                                        location.reload();
                                    }
                                    else
                                    {
                                        locationIndex();
                                    }
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
    ;
    <?php } ?>
    /*转会员*/
    $("#member").on("click", function () {
        layer.confirm("确定要转会员吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                layer.closeAll('dialog');
                $.fancybox({
                    width: 700,
                    height: 450,
                    autoSize: false,
                    padding: 0,
                    type: "iframe",
                    href: "<?=Url::to(['turn-member'])?>?id=" + <?= $member['cust_id'] ?> +'&from=/crm/crm-member-develop/index',
                });
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
                    data: {"str": '<?= $member['cust_id'] ?>'},
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
                    data: {"str": '<?= $member['cust_id'] ?>'},
                    url: "<?=Url::to(['turn-sales']) ?>",
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    locationIndex();
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
    function locationIndex() {
        var index = '<?= Url::to(['index']) ?>';
        <?php if($ctype == 3){ ?>
        index = '<?= Url::to(["/crm/crm-investment-dvelopment/index"])?>';
        <?php }else if($ctype == 6){ ?>
        index = '<?= Url::to(["/crm/crm-investment-customer/list"])?>';
        <?php }else if($ctype == 4){ ?>
        index = '<?= Url::to(['/crm/crm-potential-customer/index']) ?>';
        <?php } ?>
        location.href = index;
    }

</script>
