<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:31
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '其他出库单详情';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '其他出库单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .width-120 {
        width: 120px;
    }

    .content {
        font-size: 12px;
    }

    .width-300 {
        width: 300px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    label:after {
        content: "：";
    }
</style>
<div class="content">
    <h3 class="head-first"><?= $this->title ?><span style="float: right;color: #fff;">出库单号：<?= $model['o_whcode'] ?></span></h3>
    <div class="mb-10">
        <?php if($model['o_whstatus']=='7'||$model['o_whstatus']=='6'||$model['o_whstatus']=='8'|| $model['o_whstatus'] == '4'){?>
            <?= Html::button("切换列表", ["id" => "list", "class" => "button-blue"]); ?>
            <?= Html::button("打印", ["id" => "print", "class" => "button-blue",'onclick'=>'btnPrints()']); ?>
        <?php }else {?>
        <?= Html::button("修改", ["id" => "edit", "class" => "button-blue"]); ?>
        <?= Html::button("送审", ["id" => "check", "class" => "button-blue"]); ?>
        <?= Html::button("切换列表", ["id" => "list", "class" => "button-blue"]); ?>
        <?= Html::button("打印", ["id" => "print", "class" => "button-blue"]); }?>
    </div>
    <div style="height:2px;background:#9acfea;"></div>
    <div class="space-30"></div>

    <h3 class="head-second">出库单信息</h3>
    <div class="space-30"></div>


    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">单据类型：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["o_whtype"] as $key => $val){
                if (strval($key) == $model['o_whtype']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">关联单号：</td>
            <td class="no-border vertical-center"><?= $model["relate_packno"] ?></td>
        </tr>
    </table>
    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出仓仓库：</td>
            <td class="no-border vertical-center width-300" id="wh_id"
                data-id="<?= $model['o_whid'] ?>"><?php foreach ($options["warehouse"] as $key => $val){
                if ($val['wh_id'] == $model['o_whid']){ ?>
                <?= $val['wh_name'] ?></td>
            <?php break;
            }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">仓库代码：</td>
            <td class="no-border vertical-center" ><?php foreach ($options["warehouse"] as $key => $val){
                if ($val['wh_id'] == $model['o_whid']){ ?>
                <?= $val['wh_code'] ?></td>
        <?php break;
        }
        } ?>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">法人名称：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["warehouse"] as $key => $val){
                if ($val['wh_id'] == $model['o_whid']){ ?>
                <?= $val['company_name'] ?></td>
            <?php break;
            }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">出货日期：</td>
            <td class="no-border vertical-center "><?= date("Y/m/d", strtotime($model["plan_odate"])) ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">入仓仓库：</td>
            <td class="no-border vertical-center width-300" id="wh_id"
                data-id="<?= $model['i_whid'] ?>"><?php foreach ($options["warehouse"] as $key => $val){
                if ($val['wh_id'] == $model['i_whid']){ ?>
                <?= $val['wh_name'] ?></td>
            <?php break;
            }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">仓库代码：</td>
            <td class="no-border vertical-center" ><?php foreach ($options["warehouse"] as $key => $val){
                if ($val['wh_id'] == $model['i_whid']){ ?>
                <?= $val['wh_code'] ?></td>
        <?php break;
        }
        } ?>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">申请人：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["staff"] as $key => $val){
                if (strval($key) == $model['creator']){ ?>
                <?= $options["staff_code"][$key] ?>-<?= $val?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">申请部门：</td>
            <td class="no-border vertical-center"><?php foreach ($options["organization"] as $key => $val){
                if (strval($key) == $model["app_depart"]){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">备注：</td>
            <td class="no-border vertical-center"><?= $model["remarks"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <?php if ($model['o_whstatus'] == '4') { ?>
        <table width="90%" class="no-border vertical-center">
            <tr class="no-border">
                <td class="no-border vertical-center label-width label-align width-120">取消原因：</td>
                <td class="no-border vertical-center"><?= $model["can_reason"] ?></td>
            </tr>
        </table>
    <?php } ?>

    <div class="space-30" style="height: 20px;"></div>

    <h3 class="head-second">出库商品信息</h3>
    <table class="table">
        <thead>
        <th width="100">序号</th>
        <th width="200">料号</th>
        <th width="300">品名</th>
        <th width="100">规格型号</th>
        <th width="100">储位</th>
        <th width="100">批次</th>
        <th width="100">单位</th>
        <th width="100">可用库存量</th>
        <th width="100">出库数量</th>
        <th width="100">备注</th>
        </thead>
        <tbody id="data">
        <?php if (count($childs['rows']) > 0) { ?>
            <?php foreach ($childs['rows'] as $k => $child) { ?>
                <tr>
                    <td align="center"><?= $k + 1 ?></td>
                    <td align="center"><?= $child['part_no'] ?></td>
                    <td align="center"><?= $child['pdt_name'] ?></td>
                    <td align="center"><?= $child['tp_spec'] ?></td>
                    <td align="center"><?= $child['st_code']?></td>
                    <td align="center"><?= $child['batch_no']?></td>
                    <td align="center"><?= $child['unit_name'] ?></td>
                    <td align="center"><?= $child['invt_num'] ?></td>
                    <td align="center"><?= $child['o_whnum'] ?></td>
                    <td align="center"><?= $child['remarks'] ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td align="center" colspan="10">列表为空</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="space-30" style="height: 20px;"></div>
    <?php if (!empty($model["checks"]) && ($model['o_whstatus'] == '7' || $model['o_whstatus'] == '6'|| $model['o_whstatus'] == '4' || $model['o_whstatus'] == '8')) { ?>
        <h3 class="head-second">审核路径</h3>
        <table class="table">
            <thead>
            <th>#</th>
            <th>签核节点</th>
            <th>签核人员</th>
            <th>签核日期</th>
            <th>操作</th>
            <th>签核意见</th>
            <th>签核人IP</th>
            </thead>
            <tbody>
            <?php foreach ($model["checks"] as $k => $check) { ?>
                <tr>
                    <td><?= $k + 1 ?></td>
                    <td><?= $check["ver_acc_rule"] ?></td>
                    <td><?= $check["verifyName"] ?></td>
                    <td><?= $check["vcoc_datetime"] ?></td>
                    <td><?= $check["verifyStatus"] ?></td>
                    <td><?= $check["vcoc_remark"] ?></td>
                    <td><?= $check["vcoc_computeip"] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
<script>
    $(function () {
        $("#wh_id").change();
    });
    $("#edit").click(function () {
        window.location.href = "<?=Url::to(['edit'])?>?id=<?=$model['o_whpkid']?>";
    });
    $("#list").click(function () {
        window.location.href = "<?=Url::to(['index'])?>";
    });
    $("#check").click(function () {
        var url = "<?=Url::to(['view', 'id' => \Yii::$app->request->get('id')], true)?>";
        var tpList=<?= $businessType?>;
        var changeType = "其它出库";
        for (var k in tpList) {
            if (changeType == tpList[k]) {
                var type = k;
                break;
            }
        }
        $.fancybox({
            href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=<?=\Yii::$app->request->get('id')?>&url=" + url,
            type: "iframe",
            padding: 0,
            autoSize: false,
            width: 750,
            height: 480
        });
    });
    $("#wh_id").change(function () {
        var wh_id = $(this).data("id");
        if (wh_id.length != 0) {
            $.ajax({
                url: "<?=Url::to(['get-wh-code'])?>?wh_id=" + wh_id,
                type: "get",
                success: function (data) {
                    $("#whs_operator").text(data);
                }
            });
        }
    });
    /*表格打印*/
    function btnPrints() {
        var bdhtml = window.document.body.innerHTML;
        var sprnstr = "<!--startprint-->";
        var eprnstr = "<!--endprint-->";
        var prnhtml = bdhtml.substr(bdhtml.indexOf(sprnstr) + 17);
        var prnhtml = prnhtml.substring(0, prnhtml.indexOf(eprnstr));
        window.document.body.innerHTML = prnhtml;
        window.print();
        // 还原界面
        window.document.body.innerHTML = bdhtml
        window.location.reload();
    }
</script>