<?php
/**
 * User: F1678086
 * Date: 2017/3/10
 * Time: 8:39
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \app\classes\Menu;

$this->title = '移仓申请单详情';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '移仓申请审核'];

?>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h2 class="head-first">
        <?= $this->title ?>
        <span style="color: white;float: right;font-size:12px;margin-right:20px">异动单号：<?= $modelH['chh_code'] ?></span>
    </h2>
    <div class="mb-10">
        <?= Html::button('通过', ['class' => 'button-blue', 'id' => 'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue', 'id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
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
            <table class="table">
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
            <table class="table">
                <thead>
                <tr>
                    <th class="width-80">序号</th>
                    <th class="width-150">料号</th>
                    <th class="width-150">商品名称</th>
                    <th class="width-150">规格型号</th>
                    <th class="width-150">仓库</th>
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
                            <td><?= $val['st_id'] ?></td>
                            <td><?= $val['before_num1'] ?></td>
                            <td><?= $val['chl_num'] ?></td>
                            <td><?= $val['before_num1'] - $val['chl_num'] ?></td>
                            <td><?= $val['unit'] ?></td>
                        </tr>
                        <tr>
                            <td><?= "异动后" . ($key + 1) ?></td>
                            <td><?= $val['part_no2'] ?></td>
                            <td><?= $val['pdt_name2'] ?></td>
                            <td><?= $val['tp_spec2'] ?></td>
                            <td><?= $val['wh_id2'] ?></td>
                            <td><?= $val['st_id2'] ?></td>
                            <td><?= $val['before_num2'] ?></td>
                            <td><?= $val['chl_num'] ?></td>
                            <td><?= $val['before_num2'] + $val['chl_num'] ?></td>
                            <td><?= $val['unit'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php } else if ($modelH['chh_type'] == 43) { ?>
            <table class="table">
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
                            <td><?= $val['chl_num'] ?></td>
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
</div>
<script>
    $(function () {
//        var verify = "<?//= \Yii::$app->controller->action->id?>//" == "verify";
//        $("#record").datagrid({
//            url: "<?//= Url::to(['/system/verify-record/load-record']);?>//?id=" + <?//= $id ?>//,
//            rownumbers: true,
//            method: "get",
//            idField: "vcoc_id",
//            loadMsg: false,
//            fit: true,
////                    pagination: true,
//            singleSelect: true,
////                    pageSize: 5,
////                    pageList: [5, 10, 15],
//            columns: [[
//                {
//                    field: "verifyOrg", title: "审核节点", width: verify ? 150 : 80
//                },
//                {field: "verifyName", title: "审核人", width: verify ? 150 : 80},
//                {
//                    field: "vcoc_datetime", title: "审核时间", width: verify ? 156 : 80
//                },
//                {field: "verifyStatus", title: "操作", width: verify ? 150 : 80},
//                {
//                    field: "vcoc_remark", title: "审核意见", width: verify ? 200 : 200
//                },
//                {
//                    field: "vcoc_computeip", title: "审核IP", width: verify ? 150 : 89
//                },
//
//            ]],
//            onLoadSuccess: function (data) {
//                showEmpty($(this), data.total, 0);
//            }
//        });
//        $("#pass").one("click", function () {
//            $("#check-form").attr('action','<?//= \yii\helpers\Url::to(['/system/verify-record/audit-pass']) ?>//');
//            return ajaxSubmitForm($("#check-form"));
//        });
        //驳回
        $("#reject").on("click", function () {
            var idss = $("._ids").val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });

        //通过
        $("#pass").on("click", function () {
            var idss = $("._ids").val();
            $.fancybox({
                href: "<?=Url::to(['pass-opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
    });
</script>

