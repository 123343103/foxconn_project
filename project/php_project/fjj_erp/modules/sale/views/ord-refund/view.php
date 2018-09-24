<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\modules\sale\models\OrdRefund */

$this->title = '退款单审核详情';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '退款列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .value-width{
        width:200px;
    }
    .width-150{
        width:150px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .pb-10{
        padding-bottom: 10px;
    }
    thead tr th p {
        color: white;
    }
    .space-20{
        width:100%;
        height:20px;
    }
    #product_table tr td p{
        white-space: nowrap;
        display: inline-block;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
<div class="content">
    <div class="border-bottom mb-10  pb-10">
        <?= Html::button('修 &nbsp; 改', ['class' => 'button-blue width-100 display-none', 'id'=>'update' ,'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $id. '\'']) ?>
        <?= Html::button('送 &nbsp; 审', ['class' => 'button-blue width-100 display-none', 'type' => 'button', 'id' => 'check']) ?>
        <?= Html::button('取消退款', ['class' => 'button-blue width-100 display-none', 'type' => 'button', 'id' => 'cancle-quote']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <?= Html::button('打 &nbsp; 印', ['class' => 'button-blue width-80', 'onclick'=>'btnPrints()']) ?>
    </div>
    <div>
        <h2 class="head-second">
            退款基本信息
        </h2>
        <div class="mb-10 ml-20">
            <table width="90%" class="no-border vertical-center mb-10" style="border-collapse:separate; border-spacing:5px;">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">退款单号<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['refund_no'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">单据状态<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['refund_status'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">关联订单号<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ord_no'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">交易法人<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['company_name'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">订单类型<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ordType'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">购买日期<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ordDate'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">订单状态<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['ordStatus'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">客户代码<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custCode'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">客户名称<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custName'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">公司电话<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custTel1'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">联系人<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['cust_contacts'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">联系电话<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['custTel2'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="13%">订单负责人<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['manager'] ?></td>
                    <td width="4%" class="no-border vertical-center label-align"></td>
                    <td class="no-border vertical-center label-align" width="13%">负责人电话<label>：</label></td>
                    <td class="no-border vertical-center" width="35%"><?= $data['mg_tel'] ?></td>
                </tr>
            </table>
        </div>
        <h2 class="head-second">
            商品信息
        </h2>
        <div class="mb-10 ml-20" style="overflow-x: scroll">
            <table class="table">
                <thead>
                <tr>
                    <th><p class="width-40">序号</p></th>
                    <th><p class="width-150">料号</p></th>
                    <th><p class="width-150">品名</p></th>
                    <th><p class="width-150">规格/型号</p></th>
                    <th><p class="width-150">平均单价</p></th>
                    <th><p class="width-150">订单数量</p></th>
                    <th><p class="width-150">单位</p></th>
                    <th><p class="width-150">退款类型</p></th>
                    <th><p class="width-150">退/换货数量</p></th>
                    <th><p class="width-150">退款金额</p></th>
                    <th><p class="width-150">退/换货原因</p></th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php if(!empty($dt)){ ?>
                    <?php foreach ($dt as $key => $val){ ?>
                        <tr>
                            <td><?= $key+1 ?></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['part_no'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['pdt_name'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['tp_spec'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['uprice_tax_o'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['sapl_quantity'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['unit_name'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['rfndType'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['rfnd_nums'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['rfnd_amount'] ?></p></td>
                            <td><p class="text-center width-150 text-no-next"><?= $val['remarks'] ?></p></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <p class="float-right">
            <label class="label-align" style="color:#f60;">退款总金额 <label style="color:#f60;">：</label></label>
            <span class="value-align value-width" style="color:#f60;"><?= $data['currency_mark']. bcsub($data['tax_fee'],0,2) ?></span>
            <label class="label-align" style="color:#f60;">订单总金额(含税) <label style="color:#f60;">：</label></label>
            <span class="value-align value-width" style="color:#f60;"><?= $data['currency_mark']. bcsub($data['req_tax_amount'],0,2) ?></span>
        </p>
    </div>
    <div class="space-20 overflow-auto"></div>

    <?php if(!empty($verify)){ ?>
        <h2 class="head-second">
            签核记录
        </h2>
        <div class="mb-30">
            <table class="product-list" style="width:990px;">
                <thead>
                <tr>
                    <th class="width-60">序号</th>
                    <th>签核节点</th>
                    <th>签核人员</th>
                    <th>签核日期</th>
                    <th>操作</th>
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
<script>
    $(function(){
        <?php if($data['rfnd_status'] == 13){ ?>
        $('#update,#check,#cancle-quote').show();
        <?php } ?>

        var isApply = "<?= $isApply ?>";
        var id = '<?= $id ?>';
        var type='<?= $typeId ?>';
        if (isApply == 1) {
            var url="<?=Url::to(['view'],true)?>?id="+id;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        }

        /*送审*/
        $('#check').click(function(){
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });

        $('#cancle-quote').click(function(){
            $.fancybox({
                href:"<?=Url::to(['cancle-quote'])?>?id="+id,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:445,
                height:240
            });
        })
    })

    function btnPrints(){
        $('.content').jqprint({
            debug: false,
            importCSS: true,
            printContainer: true,
            operaSupport: false
        })
    }
</script>
