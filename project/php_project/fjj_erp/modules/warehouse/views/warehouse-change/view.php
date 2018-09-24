<?php
/**
 * User: F1676624
 * Date: 2017/7/22
 */
use yii\helpers\Url;
use app\classes\Menu;

$this->title = '仓库异动详情页';
$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => Url::to(['/index/index'])];
$this->params['breadcrumbs'][] = ['label' => '仓库异动', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '仓库异动查询', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .width-150{width: 150px;}
    .width-40{width: 40px;}
    .width-80{width: 80px;}
</style>
<div class="content">
    <h1 class="head-first"><?= $this->title ?></h1>
    <div class="mb-10">
        <?php if ($modelH['status'] == 10 || $modelH['status'] == 40) { ?>
            <?php if (Menu::isAction('/warehouse/other-stock-in/edit')) { ?>
                <button type="button" class="button-blue"
                        onclick="location.href='<?= Url::to(['update', 'id' => $modelH['chh_id']]) ?>'">修改
                </button>
            <?php } ?>
            <?php if (Menu::isAction('/warehouse/other-stock-in/delete')) { ?>
                <button id="delete_btn" type="button" class="button-blue">删除</button>
            <?php } ?>
        <?php } ?>
        <?php if ($modelH['status'] == 10) { ?>
            <?php if (Menu::isAction('/warehouse/other-stock-in/check')) { ?>
                <button id="check_btn" type="button" class="button-blue">送审</button>
            <?php } ?>
        <?php } ?>
        <?= Menu::isAction('/warehouse/other-stock-in/index') ? "<button type='button' class='button-blue' style='width:80px;' onclick='location.href=\"" . Url::to(['index']) . "\"'>切换列表</button>" : '' ?>
<!--        <button type="button" class="button-blue" onclick="history.go(-1)">返回</button>-->
    </div>
    <h1 class="head-second"><?= $modelH['chh_typeName'] ?>信息</h1>
    <table width="70%" class="no-border vertical-center ">
        <tr class="no-border">
<!--            <td class="no-border vertical-center" width="13%">异动单号:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['chh_code'] ?><!--</td>-->
<!--            <td class="no-border vertical-center" width="4%"></td>-->
            <td class="no-border vertical-center label-align" width="10%">异动类型:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['chh_typeName'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">出仓名称:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['wh_name'] ?></td>
<!--            <td class="no-border vertical-center" width="13%">异动单状态:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['chh_status'] ?><!--</td>-->
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] == 42 ? "hiden" : "" ?>">
        <tr class="no-border">
<!--            <td class="no-border vertical-center" width="13%">出仓名称:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['wh_name'] ?><!--</td>-->
<!--            <td class="no-border vertical-center" width="4%"></td>-->
            <td class="no-border vertical-center label-align" width="10%">出仓仓库代码:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['wh_code'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">出仓仓库属性:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['wh_attr'] ?></td>
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] != 43 ? "hiden" : "" ?>">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">入仓仓库:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['wh_name2'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">入仓仓库代码:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['wh_code2'] ?></td>
<!--            <td class="no-border vertical-center" width="4%"></td>-->
<!--            <td class="no-border vertical-center" width="13%">入仓仓库属性:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['wh_attr2'] ?><!--</td>-->
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] != 43 ? "hiden" : "" ?>">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">入仓仓库属性:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['wh_attr2'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">操作人:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['create_by'] ?></td>
<!--            <td class="no-border vertical-center" width="4%"></td>-->
<!--            <td class="no-border vertical-center" width="13%">操作日期:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['create_at'] ?><!--</td>-->
<!--            <td class="no-border vertical-center" width="35%"></td>-->
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] != 43 ? "hiden" : "" ?>">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">操作日期:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['create_at'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">确认人:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['review_by'] ?></td>
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] != 43 ? "hiden" : "" ?>">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">确认日期:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['review_at'] ?></td>
            <td class="no-border vertical-center" width="35%"></td>
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] == 43 ? "hiden" : "" ?> ">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">操作人:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['create_by'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">操作日期:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['create_at'] ?></td>
        </tr>
    </table>
    <table width="70%" class="no-border vertical-center ml-25 mb-30 <?= $modelH['chh_type'] == 43 ? "hiden" : "" ?> ">
        <tr class="no-border">
            <td class="no-border vertical-center label-align" width="10%">确认人:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['review_by'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center label-align" width="10%">确认日期:</td>
            <td class="no-border vertical-center" width="20%"><?= $modelH['review_at'] ?></td>
        </tr>
    </table>
    <h1 class="head-second"><?= $modelH['chh_typeName'] ?>商品信息</h1>
    <div style="overflow:auto;">
        <?php if ($modelH['chh_type'] == 41) { ?>
            <table class="table" style="width: 1380px;">
                <thead>
                <tr>
                    <th class="width-40">序号</th>
                    <th class="width-150">料号</th>
                    <th class="width-150">商品名称</th>
                    <th class="width-150">规格型号</th>
                    <th class="width-150">批次</th>
                    <th class="width-150">异动前储位</th>
                    <th class="width-150">异动后储位</th>
                    <th class="width-150">异动数量</th>
                    <th class="width-150">单位</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($model[0]['pdt_no'])) { ?>
                    <?php foreach ($model as $key => $val) { ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $val['pdt_no'] ?></td>
                            <td><?= $val['pdt_name'] ?></td>
                            <td><?= $val['tp_spec'] ?></td>
                            <td><?= $val['chl_bach'] ?></td>
                            <td><?= $val['st_id'] ?></td>
                            <td><?= $val['st_id2'] ?></td>
                            <td><?= sprintf("%.2f", $val['chl_num']); ?></td>
                            <td><?= $val['unit'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php } else if ($modelH['chh_type'] == 42) { ?>
            <table class="table" style="width: 1380px;">
                <thead>
                <tr>
                    <th class="width-80">序号</th>
                    <th class="width-150">料号</th>
                    <th class="width-150">商品名称</th>
                    <th class="width-150">规格型号</th>
                    <th class="width-150">仓库</th>
                    <th class="width-150">批次</th>
                    <th class="width-150">储位</th>
                    <th class="width-150">库存量</th>
                    <th class="width-150">异动数量</th>
                    <th class="width-150">异动后库存数量</th>
                    <th class="width-150">单位</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($model[0]['pdt_no'])) { ?>
                    <?php foreach ($model as $key => $val) { ?>
                        <tr>
                            <td><?= "异动前" . ($key + 1) ?></td>
                            <td><?= $val['pdt_no'] ?></td>
                            <td><?= $val['pdt_name'] ?></td>
                            <td><?= $val['tp_spec'] ?></td>
                            <td><?= $val['wh_id'] ?></td>
                            <td><?= $val['chl_bach'] ?></td>
                            <td><?= $val['st_id'] ?></td>
                            <td><?= $val['before_num1'] ?></td>
                            <td><?= sprintf("%.2f", $val['chl_num']); ?></td>
                            <td><?= $val['before_num1'] - $val['chl_num'] ?></td>
                            <td><?= $val['unit'] ?></td>
                        </tr>
                        <tr>
                            <td><?= "异动后" . ($key + 1) ?></td>
                            <td><?= $val['part_no2'] ?></td>
                            <td><?= $val['pdt_name2'] ?></td>
                            <td><?= $val['tp_spec2'] ?></td>
                            <td><?= $val['wh_id2'] ?></td>
                            <td><?= $val['chl_bach2'] ?></td>
                            <td><?= $val['st_id2'] ?></td>
                            <td><?= $val['before_num2'] ?></td>
                            <td><?= sprintf("%.2f", $val['chl_num']); ?></td>
                            <td><?= $val['before_num2'] + $val['chl_num'] ?></td>
                            <td><?= $val['unit'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php } else if ($modelH['chh_type'] == 43) { ?>
            <table class="table" style="width: 1380px;">
                <thead>
                <tr>
                    <th class="width-40">序号</th>
                    <th class="width-150">料号</th>
                    <th class="width-150">商品名称</th>
                    <th class="width-150">品牌</th>
                    <th class="width-150">规格型号</th>
                    <th class="width-150">批次</th>
                    <th class="width-150">移仓前储位</th>
                    <th class="width-150">移仓后储位</th>
                    <th class="width-150">移仓数量</th>
                    <th class="width-150">单位</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($model[0]['pdt_no'])) { ?>
                    <?php foreach ($model as $key => $val) { ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $val['pdt_no'] ?></td>
                            <td><?= $val['pdt_name'] ?></td>
                            <td><?= $val['brand'] ?></td>
                            <td><?= $val['tp_spec'] ?></td>
                            <td><?= $val['chl_bach'] ?></td>
                            <td><?= $val['st_id'] ?></td>
                            <td><?= $val['st_id2'] ?></td>
                            <td><?= sprintf('%.2f', $val['chl_num']) ?></td>
                            <td><?= $val['unit'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
    <div style="height: 10px;"></div>
    <div class="mb-20" style="overflow: auto; margin-top: 10px">
        <?php if (!empty($verify)){ ?>
        <div >
            <h1 class="head-second">签核记录</h1>
            <div>
                <table class="mb-30 product-list" style="width:990px;">
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
                    <?php foreach ($verify as $key=>$val){ ?>
                        <tr>
                            <th><?= $key+1 ?></th>
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
            <?php } ?>
        </div>
    </div>
<!--    <div class="space-10"></div>-->
<!--    <table width="90%" class="no-border vertical-center ml-25 mb-20">-->
<!--        <tr class="no-border">-->
<!--            <td class="no-border vertical-center" width="13%">确认人:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['review_by'] ?><!--</td>-->
<!--            <td class="no-border vertical-center" width="4%"></td>-->
<!--            <td class="no-border vertical-center" width="13%">确认日期:</td>-->
<!--            <td class="no-border vertical-center" width="18%">--><?//= $modelH['review_at'] ?><!--</td>-->
<!--            <td class="no-border vertical-center" width="35%"></td>-->
<!--        </tr>-->
<!--    </table>-->
</div>
<script>
    $(function () {
        var isApply = "<?= $isApply ?>";
        var id = "<?= $modelH['chh_id'] ?>";
        var status = "<?= $modelH['status'] ?>";

        var type = "<?= $modelH['chh_type'] ?>";

        if (isApply == 1 && status != 20 && status != 30) {
            var url = "<?=Url::to(['view'], true)?>?id=" + id;
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        }
        //删除
        $("#delete_btn").click(function () {
            layer.confirm('确定删除吗？', {icon: 2},
                function () {
                    $.ajax({
                        url: "<?=Url::to(['delete'])?>",
                        data: {"id": "<?= $modelH['chh_id'] ?>"},
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {
                                    icon: 1, end: function () {
                                        location.href = "<?=Url::to(['index'])?>";
                                    }
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    });
                },
                function () {
                    layer.closeAll();
                }
            );
        });

        //送审
        $("#check_btn").click(function () {
            var id = "<?= $modelH['chh_id'] ?>";
            var status = "<?= $modelH['status'] ?>";

            var type = "<?= $modelH['chh_type'] ?>";
            var url = "<?=Url::to(['view'], true)?>?id=" + id;
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });
    })
</script>