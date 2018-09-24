<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/27
 * Time: 下午 04:09
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '物流订单详情';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '采购申请审核'];
?>
<style>
    .border-style {
        border: none;
    }

    .right-style {
        width: 300px;
    }

    .div-style {
        margin-left: 80px;
    }

    .label-right {
        text-align: right;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h2 class="head-first">
        <?= $this->title ?>
    </h2>
    <div class="mb-10">
        <?= Html::button('通过', ['class' => 'button-blue','id'=>'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue','id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue', 'style'=>'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">基本信息</a>
    </h2>
    <div class="mb-10 div-style">
        <table width="90%" class="border-style">
            <tr class="mb-10 border-style ">
                <td width="20%" class="border-style label-right">来源单据类型：</td>
                <td width="30%" class="border-style right-style"><?= $model[0]['sr_type'] ?></td>
                <td width="20%" class="border-style label-right">运输类型：</td>
                <td width="20%" class="border-style right-style"><?= $model[0]['trade_type'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">预计出货时间：</td>
                <td class="border-style"><?= $model[0]['lgst_date'] ?></td>
                <td class="border-style label-right">运输模式：</td>
                <td class="border-style"><?= $model[0]['TRANSMODE'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">富金机配送：</td>
                <td class="border-style"><?= $model[0]['YN_FJJ'] ?></td>
                <td class="border-style label-right">贸易性质：</td>
                <td class="border-style"><?= $model[0]['trade_act'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">是否无账：</td>
                <td class="border-style"><?= $model[0]['YN_Fee'] ?></td>
                <td class="border-style label-right">报价：</td>
                <td class="border-style"><?= $model[0]['YN_ins'] ?></td>
            </tr>
            <tr class=" border-style mb-10">
                <td class="border-style label-right">进出口类别：</td>
                <td class="border-style"><?= $model[0]['ie_type'] ?></td>
                <td class="border-style label-right">车种：</td>
                <td class="border-style"><?= $model[0]['kd_car'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">费用代码：</td>
                <td class="border-style"><?= $model[0]['cost_no'] ?></td>
                <td class="border-style label-right">开立人：</td>
                <td class="border-style"><?= $model[0]['staff_name'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">开立时间：</td>
                <td class="border-style" colspan="3"><?= $model[0]['crt_date'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td  class="border-style label-right">备注：</td>
                <td class="border-style" colspan="3"><?= $model[0]['marks'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">出货人信息</a>
    </h2>
    <div class="mb-10 div-style">
        <table width="90%" class="border-style">
            <tr class="mb-10 border-style ">
                <td width="20%" class="border-style label-right">仓库代码：</td>
                <td width="30%" class="border-style right-style"><?= $model[0]['wh_code'] ?></td>
                <td width="20%" class="border-style label-right">仓库名称：</td>
                <td width="20%" class="border-style right-style"><?= $model[0]['wh_name'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">详细地址：</td>
                <td class="border-style" colspan="3"><?= $model[0]['wh_addr'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">联系人：</td>
                <td class="border-style"><?= $model[0]['shp_cntct'] ?></td>
                <td class="border-style label-right">联系电话：</td>
                <td class="border-style"><?= $model[0]['shp_tel'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">备注：</td>
                <td class="border-style" colspan="3"><?= $model[0]['shp_marks'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">收货人信息</a>
    </h2>
    <div class="mb-10 div-style">
        <table  width="90%" class="border-style">
            <tr class="mb-10 border-style ">
                <td width="20%" class="border-style label-right">客户代码：</td>
                <td width="30%" class="border-style right-style"><?= $model[0]['cust_code'] ?></td>
                <td width="20%" class="border-style label-right">公司名称：</td>
                <td width="20%" class="border-style right-style"><?= $model[0]['cust_sname'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">详细地址：</td>
                <td class="border-style" colspan="3"><?= $model[0]['cust_readress'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">联系人：</td>
                <td class="border-style"><?= $model[0]['rcv_cntct'] ?></td>
                <td class="border-style label-right">联系电话：</td>
                <td class="border-style"><?= $model[0]['rcv_tel'] ?></td>
            </tr>
            <tr class="mb-10 border-style">
                <td class="border-style label-right">备注：</td>
                <td class="border-style" colspan="3"><?= $model[0]['rcv_marks'] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">单身列表</a>
    </h2>
    <div class="mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <table width="90%" class="table" style="width:1500px;">
            <thead>
            <tr>
                <th width="50">项次</th>
                <th width="150">来源订单号</th>
                <th width="200">商品料号</th>
                <th width="100">商品规格</th>
                <th width="200">商品名称</th>
                <th width="100">数量</th>
                <th width="80">单位</th>
                <th width="150">原产地</th>
                <th width="150">包装方式</th>
                <th width="100">包装件数</th>
                <th width="100">板数</th>
                <th width="100">净重/KG</th>
                <th width="100">毛重/KG</th>
                <th width="200">长宽高/CBM</th>
            </tr>
            </thead>
            <tbody id="orderinfo">
            <?php if (!empty($model)) { ?>
                <?php foreach ($model as $key => $val) { ?>
                    <tr>
                        <td><span><?= $key + 1 ?></span></td>
                        <td><span><?= $val['ord_no'] ?></span></td>
                        <td><span><?= $val['part_no'] ?></span></td>
                        <td><span><?= $val['tp_spec'] ?></span></td>
                        <td><span><?= $val['pdt_name'] ?></span></td>
                        <td><span><?= sprintf("%.2f", $val['sapl_quantity'])  ?></span></td>
                        <td><span><?= $val['unit'] ?></span></td>
                        <td><span><?= $val['pdt_origin'] ?></span></td>
                        <td><span><?= $val['pck_type'] ?></span></td>
                        <td><span><?= $val['pdt_qty'] ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['plate_num']) ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['suttle']) ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['pdt_weight']) ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['pdt_length']) ?>/<?= sprintf("%.2f",$val['pdt_width']) ?>/<?= sprintf("%.2f",$val['pdt_height']) ?></span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-down"></i>
        <a href="javascript:void(0)">审核记录</a>
    </h2>
    <div class="mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <?php if (!empty($verify)) { ?>
            <table width="90%" class="table" style="width:950px;">
                <thead>
                <tr>
                    <th width="50">序号</th>
                    <th width="150">签核节点</th>
                    <th width="200">签核人员</th>
                    <th width="100">签核日期</th>
                    <th width="200">操作</th>
                    <th width="200">签核意见</th>
                    <th width="80">签核人IP</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($verify as $key => $val) { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $val['verifyOrg'] ?></td>
                        <td><?= $val['verifyName'] ?></td>
                        <td><?= $val['vcoc_datetime'] ?></td>
                        <td><?= $val['verifyStatus'] ?></td>
                        <td><?= $val['vcoc_remark'] ?></td>
                        <td><?= $val['vcoc_computeip'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
<script>
    $(function () {
        $(".head-second").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
        });
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
            layer.confirm("是否通过?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        data: $("#check-form").serialize(),
                        url: "<?= Url::to(['/system/verify-record/audit-pass']) ?>",
                        success: function (msg) {
                            if (msg.flag == 1) {
                                layer.alert(msg.msg, {icon: 1}, function () {
                                    parent.window.location.href = '<?= Url::to(['index']) ?>'
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (data) {
//                            console.log('data: ',data)
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
