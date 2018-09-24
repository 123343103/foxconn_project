<?php

use yii\helpers\Url;
use \yii\helpers\Html;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程'];
$this->params['breadcrumbs'][] = ['label' => '厂商呈报列表'];
$this->params['breadcrumbs'][] = ['label' => '呈报详情'];
$this->title = '呈报详情';/*BUG修正 增加title*/
?>
<style>
    .font-size {
        font-size: 14px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        呈报详情
        <span class="head-code">编号：<?= $model['report_code'] ?></span>
    </h1>
    <div class="border-bottom mb-20 pb-10">
        <?php if ($type == '2') { ?>
            <?= Menu::isAction('/ptdt/firm-report/update') ? Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model["pfr_id"] . '&childId=' . $childModel["pfrc_id"] . '\'']) : '' ?>
            <?= Menu::isAction('/ptdt/firm-report/delete') ? Html::button('删除', ['class' => 'button-blue width-80', 'id' => 'delete']) : '' ?>
            <?= Menu::isAction('/ptdt/firm-report/check') ? Html::button('送审', ['class' => 'button-blue width-80', 'id' => 'check']) : '' ?>
        <?php } ?>
        <?= Html::button('切换列表', ['class' => ' button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>

    </div>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center" width="13%">代理等级:</td>
            <td class="no-border vertical-center" width="18%"><?= $model['bsPubdata']['agentsType'] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td class="no-border vertical-center" width="13%">开发类型:</td>
            <td class="no-border vertical-center" width="18%"><?= $model['bsPubdata']['developType'] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td class="no-border vertical-center" width="13%">紧急程度:</td>
            <td class="no-border vertical-center" width="18%"><?= $model['bsPubdata']['urgencyDegree'] ?></td>
        </tr>
    </table>
    <div class="mb-30">
        <h2 class="head-second">
            <p>厂商基本信息</p>
        </h2>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">注册公司名称:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firmName'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">简称:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firm_shortname'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">品牌:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firm_message']['firm_brand'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">英文全称:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firm_message']['firm_ename'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">是否为集团供应商:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firmSupplier'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">厂商地址:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firm_message']['firmAddress']['fullAddress'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">厂商来源:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firm_source'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">厂商类型:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firm_type'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">分级分类:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['firmCategory'] ?></td>
            </tr>
        </table>
    <div class="mb-20">
        <h2 class="head-second">
            <p>代理授权谈判呈报</p>
        </h2>
        <div id="tabs" class="easyui-tabs" style="width:980px;height:auto;">
            <div title="谈判摘要">
                <div class="mt-20 mb-20">
                    <div class="mb-10 text-center view-title">基本信息</div>
                    <table width="90%" class="no-border vertical-center ml-25 mb-20">
                        <tr class="no-border">
                            <td class="no-border vertical-center" width="13%">主谈人(商品经理人):</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['productPerson']['code']; ?>--<?= $childModel['productPerson']['name']; ?></td>
                            <td width="4%" class="no-border vertical-center"></td>
                            <td class="no-border vertical-center" width="13%">职位:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['productPerson']['job']; ?></td>
                            <td width="4%" class="no-border vertical-center"></td>
                            <td class="no-border vertical-center" width="13%">联系电话:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['productPerson']['mobile'] ?></td>
                        </tr>
                    </table>
                    <?php foreach ($childModel['staffPerson'] as $item) { ?>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">陪同人员:</td>
                                <td class="no-border vertical-center" width="18%"><?= $item['staff_name'] ?>--<?= $childModel['productPerson']['name']; ?></td>
                                <td width="4%" class="no-border vertical-center"></td>
                                <td class="no-border vertical-center" width="13%">职位:</td>
                                <td class="no-border vertical-center" width="18%"><?= $item['job_task'] ?></td>
                                <td width="4%" class="no-border vertical-center"></td>
                                <td class="no-border vertical-center" width="13%">联系电话:</td>
                                <td class="no-border vertical-center" width="18%"><?= $item['staff_mobile'] ?></td>
                            </tr>
                        </table>
                    <?php } ?>
                    <table width="90%" class="no-border vertical-center ml-25 mb-20">
                        <tr class="no-border">
                            <td class="no-border vertical-center" width="13%">谈判日期:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['pfrc_date']; ?></td>
                            <td width="4%" class="no-border vertical-center"></td>
                            <td class="no-border vertical-center" width="13%">谈判时间:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['pfrc_time']; ?></td>
                            <td width="4%" class="no-border vertical-center"></td>
                            <td class="no-border vertical-center" width="13%">谈判地点:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['pfrc_location']; ?></td>
                        </tr>
                    </table>
                    <table width="90%" class="no-border vertical-center ml-25 mb-20">
                        <tr class="no-border">
                            <td class="no-border vertical-center" width="13%">厂商主谈人:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['reception']['receSname']; ?></td>
                            <td width="4%" class="no-border vertical-center"></td>
                            <td class="no-border vertical-center" width="13%">职位:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['reception']['recePosition']; ?></td>
                            <td width="4%" class="no-border vertical-center"></td>
                            <td class="no-border vertical-center" width="13%">联系电话:</td>
                            <td class="no-border vertical-center" width="18%"><?= $childModel['reception']['receMobile']; ?></td>
                        </tr>
                    </table>

                    <div class="mb-10">
                        <div class="mb-10 text-center view-title">谈判内容</div>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">代理等级:</td>
                                <td class="no-border vertical-center" width="18%"><?= $childModel['bsPubdata']['agentsGrade']; ?></td>
                                <td width="4%" class="no-border vertical-center"></td>
                                <td class="no-border vertical-center" width="13%">授权区域范围:</td>
                                <td class="no-border vertical-center" width="18%"><?= $childModel['bsPubdata']['authorizeArea']; ?></td>
                                <td width="4%" class="no-border vertical-center"></td>
                                <td class="no-border vertical-center" width="13%">销售范围:</td>
                                <td class="no-border vertical-center" width="18%"><?= $childModel['bsPubdata']['saleArea']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">授权日期:</td>
                                <td class="no-border vertical-center" width="18%"><?= $childModel['authorize']['pdaa_bdate']; ?>-<?= $childModel['authorize']['pdaa_edate']; ?></td>
                                <td width="4%" class="no-border vertical-center"></td>
                                <td class="no-border vertical-center" width="13%">结算方式:</td>
                                <td class="no-border vertical-center" width="18%"><?= $childModel['firmSettlement']; ?></td>
                                <td width="4%" class="no-border vertical-center"></td>
                                <td class="no-border vertical-center" width="13%">交期:</td>
                                <td class="no-border vertical-center" width="18%"><?= $childModel['authorize']['pdaa_delivery_day']; ?>天</td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td width="13%" class="no-border vertical-center">售后服务:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['bsPubdata']['firmService']; ?></td>
                                <td width="31%" class="no-border vertical-center"></td>
                                <td width="13%" class="no-border vertical-center">物流配送:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['bsPubdata']['firmDeliveryWay']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div title="产品优劣势分析">
                <div class="mt-20 mb-20">
                    <div class="mb-20">
                        <div class="mb-10 text-center view-title">厂商实力评估</div>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td width="13%" class="no-border vertical-center">业界影响力/排名:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['analysis']['pdna_influence']; ?></td>
                                <td width="31%" class="no-border vertical-center"></td>
                                <td width="13%" class="no-border vertical-center">厂商配合度:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['bsPubdata']['cooperateDegree']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">技术实力/技术服务:</td>
                                <td class="no-border vertical-center" width="87%"><?= $childModel['analysis']['pdna_technology_service']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">其他:</td>
                                <td class="no-border vertical-center" width="87%"><?= $childModel['analysis']['pdna_others']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="mb-20">
                        <div class="mb-10 text-center view-title">商品面</div>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td width="13%" class="no-border vertical-center">商品认证/厂商认证:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['analysis']['pdna_goods_certificate']; ?></td>
                                <td width="31%" class="no-border vertical-center"></td>
                                <td width="13%" class="no-border vertical-center">客户群(By产业):</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['analysis']['pdna_customer_base']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td width="13%" class="no-border vertical-center">销量/市占率:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['analysis']['pdna_market_share']; ?></td>
                                <td width="31%" class="no-border vertical-center"></td>
                                <td width="13%" class="no-border vertical-center">价格优势:</td>
                                <td width="26%" class="no-border vertical-center"><?= $childModel['analysis']['sales_advantage']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">利润分析:</td>
                                <td class="no-border vertical-center" width="87%"><?= $childModel['analysis']['profit_analysis']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">市场需求趋势:</td>
                                <td class="no-border vertical-center" width="87%"><?= $childModel['analysis']['pdna_demand_trends']; ?></td>
                            </tr>
                        </table>

                    </div>
                    <div class="mb-20">
                        <div class="mb-10 text-center view-title">代理价值</div>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">富金机:</td>
                                <td class="no-border vertical-center" width="87%"><?= $childModel['analysis']['value_fjj']; ?></td>
                            </tr>
                        </table>
                        <table width="90%" class="no-border vertical-center ml-25 mb-20">
                            <tr class="no-border">
                                <td class="no-border vertical-center" width="13%">厂商:</td>
                                <td class="no-border vertical-center" width="87%"><?= $childModel['analysis']['value_frim']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div title="商品资料明细">
                <div class="mt-20 mb-20">
                    <div class="mb-10 text-center view-title forbidden">商品详细信息</div>
                    <p style="font-size: 14px;">商品信息</p>
                    <div class="overflow-auto  margin-auto pb-20">
                        <div class="space-10"></div>
                        <table class="product-list table-auto">
                            <tbody id="table_body">
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                <div class="space-40"></div>
                <?php if (!empty($childModel['products'][0]['demand_id'])) { ?>
                    <div style="border-bottom:1px dashed #000;">商品经理人</div>
                    <div class="width-800 margin-auto">

                        <div class="goods-manage">
                            <label for="" class="width-100">开发中心:</label>
                            <span><?= $childModel['requirement']['developCenterName'] ?></span>
                            <label for="" class="width-100">开发部:</label>
                            <span><?= $childModel['requirement']['developDepartmentName'] ?></span>
                            <label for="" class="width-100">Commodity:</label>
                            <span><?= $childModel['requirement']['commodityName'] ?></span>
                            <label for="" class="width-100">姓名/工号:</label>
                            <span><?= $childModel['requirement']['createBy']['code'] . '/' . $childModel['requirement']['createBy']['code'] ?></span>
                        </div>
                    </div>
                <?php } ?>
                <div class="space-40"></div>
            </div>
        </div>
    </div>
    <?php if (!empty($verify)) { ?>
        <div class="mb-20">
            <h1 class="head-second text-center">签核记录</h1>
            <div class="mb-20">
                <table class="product-list" style="width:990px;">
                    <thead>
                    <tr>
                        <th class="width-60">#</th>
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
        </div>
    <?php } ?>

</div>
<script>
    <?php
    $i = 1;
    foreach($childModel['products'] as $key){
    ?>
    var tdStr = "<tr>";
    tdStr += "<td>" + "<?= $key['product_name']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['product_size']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['product_brand']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['delivery_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['payment_terms']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['product_unit']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['currency_type']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_max']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_min']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_range']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['price_average']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['levelName']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['profit_margin']; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][0]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][1]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][2]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][3]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][4]; ?>" + "</td>";
    tdStr += "<td>" + "<?= $key['typeName'][5]; ?>" + "</td>";
    tdStr += "</tr>";
    $("#table_body").append(tdStr);
    <?php $i++;} ?>
    $(function () {
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
                        data: {"id": <?= $model['pfr_id'] ?>, 'childId':<?= $childModel["pfrc_id"] ?>},
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

        $("#check").on("click", function () {
            id = <?= $model['pfr_id'] ?>;
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type=12;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+ id +"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:520
            });
        });

    })
</script>
