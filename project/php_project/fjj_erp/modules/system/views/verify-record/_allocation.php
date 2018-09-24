<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/12/23
 * Time: 下午 05:20
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title = '调拨单详情';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '调拨单申请审核'];
?>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <div class="mb-30">
        <h2 class="head-first">
            <?= $this->title ?>
            <span style="color: white;float: right;font-size:12px;margin-right:20px">调拨单号：<?= $data[0]['chh_code'] ?></span>
        </h2>
        <div class="mb-10">
            <?= Html::button('通过', ['class' => 'button-blue', 'id' => 'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue', 'id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">调拨单信息</a>
        </h2>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center mb-10"
                   style="border-collapse:separate; border-spacing:5px;">
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="15%">调拨单号<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['chh_code'] ?></td>
                    <td class="no-border vertical-center label-align" width="15%">调拨类型<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['business_type_desc'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="15%">调拨单位<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['depart_id'] ?></td>
                    <td class="no-border vertical-center label-align" width="15%">单据状态<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['chh_status'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="15%">调出仓库<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['wh_id'] ?></td>
                    <td class="no-border vertical-center label-align" width="15%">出库状态<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['o_status'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="15%">调入仓库<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['wh_id2'] ?></td>
                    <td class="no-border vertical-center label-align" width="15%">入库状态<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['in_status'] ?></td>
                </tr>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="15%">制单人<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['create_by'] ?></td>
                    <td class="no-border vertical-center label-align" width="15%">制单时间<label>：</label></td>
                    <td class="no-border vertical-center value-align" width="30%"><?= $data[0]['create_at'] ?></td>
                </tr>
            </table>
        </div>
        <div class="space-10"></div>
        <h1 class="head-second" style="text-align:center;">商品基本信息</h1>
        <div style="overflow:auto;">
            <table class="table" style="width:1380px;">
                <thead>
                <tr>
                    <th style="width:30px;">序号</th>
                    <th style="width:150px;">料号</th>
                    <th style="width:150px;">商品名称</th>
                    <th style="width:100px;">品牌</th>
                    <th style="width:150px;">规格型号</th>
                    <th style="width:150px;">批次</th>
                    <th style="width:100px;">现有库存量</th>
                    <th style="width:100px;">调拨数量</th>
                    <th style="width:100px;">出仓储位</th>
                    <!--                <th style="width:100px;">入仓储位</th>-->
                    <th style="width:100px;">单位</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($data)) { ?>
                    <?php foreach ($data as $key => $val) { ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $val['part_no'] ?></td>
                            <td><?= $val['pdt_name'] ?></td>
                            <td><?= $val['brand'] ?></td>
                            <td><?= $val['tp_spec'] ?></td>
                            <td><?= $val['chl_bach'] ?></td>
                            <td><?= $val['before_num1'] ?></td>
                            <td><?= $val['chl_num'] ?></td>
                            <td><?= $val['st_id'] ?></td>
                            <!--                        <td>--><?//= $val['Ist_code'] ?><!--</td>-->
                            <td><?= $val['unit'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div style="height: 20px;"></div>
        <div class="mb-20" style="overflow: auto; margin-top: 10px">
            <?php if (!empty($verify)){ ?>
            <div >
                <h1 class="head-second mt-30" >签核记录</h1>
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
    })
</script>



