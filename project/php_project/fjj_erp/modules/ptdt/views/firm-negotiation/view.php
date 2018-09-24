<?php

use yii\helpers\Html;
use app\classes\Menu;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $negotiation app\modules\ptdt\models\PdNegotiation */

$this->title = '谈判履历';
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判履历列表', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '谈判详情', 'url' => ""];
?>
<div class="content">
    <h1 class="head-first">
        谈判详情
        <span class="head-code">编号：<?= $negotiation['pdn_code'] ?></span>
    </h1>
    <div class="border-bottom mb-20 height-40">


        <?= Html::button('切换列表', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Menu::isAction('/ptdt/firm-negotiation/create') ? Html::button('新增谈判', ['class' => 'button-blue width-80', 'id' => 'create']) : '' ?>
        <?php if ($negotiation['pdn_status'] == 20) { ?>
            <?= Html::button('谈判完成', ['class' => 'button-blue width-80', 'id' => 'nego']) ?>
        <?php } ?>
        <?php if ($negotiation['pdn_status'] == 10) { ?>
            <?= Menu::isAction('/ptdt/firm-negotiation/update') ? Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?cid=' . $child['pdnc_id'] . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/firm-negotiation/delete') ? Html::button('删除', ['class' => 'button-blue width-80', 'id' => 'delete']) : '' ?>
        <?php } ?>
        <?php if ($negotiation['pdn_status'] == 30) { ?>
            <?= Html::button('新增呈报', ['class' => 'button-blue width-80', 'id' => 'report']) ?>
        <?php } ?>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            厂商基本信息
        </h2>
        <div class="mb-10">
            <label class="width-100">注册公司名称</label>
            <span class="width-200 text-top"><?= $firmInfo['firm_sname'] ?></span>
            <label class="width-100 ">简称</label>
            <span class="width-150 text-top"><?= $firmInfo['firm_shortname'] ?></span>
            <label class="width-100 ">品牌</label>
            <span class="width-200 text-top"><?= $firmInfo['firm_brand'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">英文全称</label>
            <span class="width-200 text-top"><?= $firmInfo['firm_ename'] ?></span>
            <label class="width-150">是否为集团供应商</label>
            <span class="width-150 text-top"><?= $firmInfo['issupplier'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">厂商类型</label>
            <span class="width-200 text-top"><?= $firmInfo['firmType'] ?></span>
            <label class="width-100">厂商来源</label>
            <span class="width-200 text-top"><?= $firmInfo['firmSource'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">厂商地址</label>
            <span class="width-750 text-top"><?= $firmInfo['firmAddress']['fullAddress'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100 ">分级分类</label>
            <span class="width-750 text-top"><?= $firmInfo['category'] ?></span>
        </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            厂商基本信息
        </h2>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">谈判日期</label>
            <span class="width-200 text-top"><?= $child['pdnc_date'] ?></span>
            <label class="width-100">谈判时间</label>
            <span class="width-200 text-top"><?= $child['pdnc_time'] ?></span>
            <label class="width-100">谈判地点</label>
            <span class="width-250 text-top"><?= $child['pdnc_location'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-100">厂商主谈人</label>
            <span class="width-200 text-top"><?= $reception['rece_sname'] ?></span>
            <label class="width-100">职位</label>
            <span class="width-200 text-top"><?= $reception['rece_position'] ?></span>
            <label class="width-100">厂商联系电话</label>
            <span class="width-200 text-top"><?= $reception['rece_mobile'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100">主谈人</label>
            <span class="width-200 text-top"><?= $child['productPerson']['name'] ?></span>
            <label class="width-100 ">职位</label>
            <span class="width-200 text-top"><?= $child['productPerson']['title'] ?></span>
            <label class="width-100 ">联系电话</label>
            <span class="width-200 text-top"><?= $child['productPerson']['mobile'] ?></span>
        </div>
        <?php if (!empty($accompany)) { ?>
            <?php foreach ($accompany as $key => $val) { ?>
                <div class="mb-10">
                    <label class="width-100">陪同人员</label>
                    <span class="width-200 text-top"><?= $val['staffInfo']['staffName'] ?></span>
                    <label class="width-100 ">职位</label>
                    <span class="width-200 text-top"><?= $val['staffInfo']['staffJob'] ?></span>
                    <label class="width-100 ">联系电话</label>
                    <span class="width-200 text-top"><?= $val['staffInfo']['staffMobile'] ?></span>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            谈判内容
        </h2>
        <div class="mb-10">
            <label class="width-150">厂商地位</label>
            <span class="width-150 text-top"><?= $analysis['bsPubdata']['position'] ?></span>
            <label class="width-100 ">厂商年营业额</label>
            <span class="width-150 text-top"><?= $analysis['pdna_annual_sales'] ?></span>
            <label class="width-150 ">业界影响力/排名</label>
            <span class="width-200 text-top"><?= $analysis['pdna_influence'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">厂商配合度</label>
            <span class="width-200 text-top"><?= $analysis['bsPubdata']['cooperateDegree'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">技术实力、技术服务</label>
            <span class="width-750 text-top"><?= $analysis['pdna_technology_service'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150">其他</label>
            <span class="width-750 text-top"><?= $analysis['pdna_others'] ?></span>
        </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            商品与市场
        </h2>
        <div class="mb-10">
            <label class="width-150">商品类别</label>
            <span class="width-150 text-top"><?= $analysis['productType'] ?></span>
            <label class="width-100 ">代理商品定位</label>
            <span class="width-150 text-top"><?= $analysis['bsPubdata']['loction'] ?></span>
            <label class="width-150 ">客户群（By产业）</label>
            <span class="width-200 text-top"><?= $analysis['pdna_customer_base'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">利润分析</label>
            <span class="width-650 text-top"><?= $analysis['profit_analysis'] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-150">市场需求趋势</label>
            <span class="width-650 text-top"><?= $analysis['pdna_demand_trends'] ?></span>
        </div>
    </div>
    <div class="mb-30">
        <h2 class="head-second">
            谈判事项
        </h2>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">过程描述</label>
            <span class="width-665 text-top"><?= $child['process_descript'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">谈判结论</label>
            <span class="width-200 text-top"><?= $child['bsPubdata']['concluse'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">追踪事项</label>
            <span class="width-665 text-top"><?= $child['trace_matter'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">下次谈判注意事项</label>
            <span class="width-665 text-top"><?= $child['next_notice'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="width-150">其他</label>
            <span class="width-665 text-top"><?= $child['negotiate_others'] ?></span>
        </div>
        <?php ;
        if (!empty($child['attachment'])) {
            ?>
            <div class="mb-10">
                <label class="width-150">附件</label>
                <span class="width-665 text-top"><?= Html::a($child['attachment_name'], Url::to(['/']) . $child['attachment']) ?></span>
            </div>
        <?php } ?>
    </div>
    <h2 class="head-second">
        商品信息
    </h2>
    <div class="overflow-auto width-900 margin-auto pb-20">
        <div class="space-10"></div>
        <table class="product-list">
            <tr>
                <th>商品品名</th>
                <th>规格型号</th>
                <th>品牌</th>
                <th>交货条件</th>
                <th>付款条件</th>
                <th>交易单位</th>
                <th>交易币别</th>
                <th>定价上限</th>
                <th>定价下限</th>
                <th>量价区间</th>
                <th>市场均价</th>
                <th>代理商品定位</th>
                <th>利润率</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
            </tr>
            <?php if (isset($productInfo) && !empty($productInfo)) { ?>
                <?php foreach ($productInfo as $key => $val) { ?>
                    <tr data-key="<?= $val['pdnp_id'] ?>">
                        <td><?= $val['product_name'] ?></td>
                        <td><?= $val['product_size'] ?></td>
                        <td><?= $val['product_brand'] ?></td>
                        <td><?= $val['delivery_terms'] ?></td>
                        <td><?= $val['payment_terms'] ?></td>
                        <td><?= $val['product_unit'] ?></td>
                        <td><?= $val['currency_type'] ?></td>
                        <td><?= $val['price_max'] ?></td>
                        <td><?= $val['price_min'] ?></td>
                        <td><?= $val['price_range'] ?></td>
                        <td><?= $val['price_average'] ?></td>
                        <td><?= $val['productLevel'] ?></td>
                        <td><?= $val['profit_margin'] ?></td>
                        <td><?= $val['typeName'][0] ?></td>
                        <td><?= $val['typeName'][1] ?></td>
                        <td><?= $val['typeName'][2] ?></td>
                        <td><?= $val['typeName'][3] ?></td>
                        <td><?= $val['typeName'][4] ?></td>
                        <td><?= $val['typeName'][5] ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="20">无数据</td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="space-30"></div>
    <?php if ($child['negotiate_concluse'] == 100019) { ?>
        <div class="mb-30" id="load-authorize">
            <h2 class="head-second">
                授权项目
            </h2>
            <div class="mb-10">
                <label class="width-150">代理等级</label>
                <span class="width-200 text-top"><?= $authorize['agentsGrade'] ?></span>
                <label class="width-150">授权区域范围</label>
                <span class="width-200 text-top"><?= $authorize['authorizeArea'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">销售范围</label>
                <span class="width-200 text-top"><?= $authorize['saleArea'] ?></span>
                <label class="width-150">授权开始日期</label>
                <span class="width-200 text-top"><?= $authorize['pdaa_bdate'] ?>至<?= $authorize['pdaa_edate'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">结算方式</label>
                <span class="width-200 text-top"><?= $authorize['settlement'] ?></span>
                <label class="width-150">交期</label>
                <span class="width-200 text-top"><?= $authorize['pdaa_delivery_day'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">物流配送</label>
                <span class="width-200 text-top"><?= $authorize['deliveryWay'] ?></span>
                <label class="width-150">售后服务</label>
                <span class="width-200 text-top"><?= $authorize['service'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">样品提供</label>
                <span class="width-200 text-top"><?= $authorize['pdaa_sample'] ?></span>
            </div>
            <div class="mb-10">
                <label class="width-150">培训方式</label>
                <span class="width-200 text-top"><?= $authorize['pdaa_train_description'] ?></span>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    $(function () {
        var pdnId = <?= $negotiation['pdn_id']?>;
        var cid = <?= $child['pdnc_id']?>;
        $("#create").on("click", function () {
            window.location.href = "<?=Url::to(['create'])?>?pdnId=" + pdnId;
        });
        $("#delete").on("click", function () {
            layer.confirm("确定要删除吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        data: {"id": pdnId},
                        url: "<?=Url::to(['delete']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        window.location.href = "<?=Url::to(['index'])?>";
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

        $("#update").on("click", function () {
            window.location.href = "<?=Url::to(['update'])?>?cid=" + cid;
        })
        /*谈判完成*/
        $("#nego").on("click", function () {
            layer.confirm("确定完成谈判?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        async: false,
                        data: {"pdnId": pdnId},
                        url: "<?=Url::to(['/ptdt/firm-negotiation/negotiate']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        window.location.href = "<?=Url::to(['index'])?>";
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

        /*新增呈报*/
        $("#report").on("click", function () {
            window.location.href = "<?=Url::to(['/ptdt/firm-report/add'])?>?firmId=" + <?= $firmInfo['firm_id'] ?>;
        });
    })
</script>