<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = '盘点单详情';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '盘点单审核','url'=>Url::to(['index'])];
//$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style type="text/css">
    td p {
        display: block;
        overflow: hidden;
        word-break: break-all;
        word-wrap: break-word;
    }

    thead tr th p {
        color: white;
    }
    .width50{
        width: 50px;
    }
    .width200{
        width: 220px;
    }
    .width110{
        width: 110px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .width100{
        width: 100px;
    }
    .width270{
        width: 270px;
    }
    .head-second + div {
        display: none;
    }
</style>
<style type="text/css" media=print>
    @media print {
        .noprint{
            display: none;
        }
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <div class="mb-30">
        <h2 class="head-first">
            <?=$this->title ?>
        </h2>
        <div class="mb-10">
            <?= Html::button('通过', ['class' => 'button-blue', 'id' => 'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue', 'id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>

        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">盘点单信息</a>
        </h2>
        <div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">法人<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['company_name'] ?></label>
                <label class="label-width qlabel-align width270">期别<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['stage']?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">币别<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['cur_code'] ?></label>
                <label class="label-width qlabel-align width270">仓库名称<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['wh_name'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">仓库代码<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['wh_code'] ?></label>
                <label class="label-width qlabel-align width270">库存截止时间<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['expiry_date'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">初盘人<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['first_ivtor']?></label>
                <label class="label-width qlabel-align width270">初盘日期<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['first_date'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">复盘人<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['re_ivtor'] ?></label>
                <label class="label-width qlabel-align width270">复盘日期<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['re_date'] ?></label>
            </div>
            <h2 class="head-second color-1f7ed0">
                <i class="icon-caret-right"></i>
                <a href="javascript:void(0)">盘点商品信息</a>
            </h2>
            <div class="mb-20" style="overflow: auto">

                <div style="width:100%;overflow: auto;">
                    <table class="table" style="width: 2500px;">
                        <thead>
                        <tr style="height: 50px">
                            <th width="50">序号</th>
                            <th width="100">料号</th>
                            <th width="100">品名</th>
                            <th width="100">规格型号</th>
                            <th width="100">单位</th>
                            <th width="100">成本单价</th>
                            <th width="100">库存数量</th>
                            <th width="100">初盘数量</th>
                            <th width="100">复盘数量</th>
                            <th width="100">盈亏数量</th>
                            <th width="100">盈亏金额</th>
                            <th width="100">初盘备注</th>
                            <th width="100">复盘备注</th>
                        </tr>
                        </thead>
                        <tbody id="product_table">
                        <?php foreach ($pdtmodel as $key => $val) { ?>
                            <tr style="height: 50px;">
                                <td><p class="width-40"><?= ($key + 1) ?></p></td>
                                <td><p class="width-80"><?= $val["part_no"] ?></p></td>
                                <td><p class="width-80"><?= $val["pdt_name"] ?></p></td>
                                <td><p class="width-80"><?= $val["tp_spec"] ?></p></td>
                                <td><p class="width-80"><?= $val["unit"] ?></p></td>
                                <td><p class="width-80"><?= $val["notax_price"] ?></p></td>
                                <td><p class="width-80"><?= $val["invt_num"] ?></p></td>
                                <td><p class="width-80"><?=$val["first_num"]?></p></td>
                                <td><p class="width-80"><?=$val["re_num"]?></p></td>
                                <td><p class="width-80"><?= $val["lose_num"] ?></p></td>
                                <td><p class="width-80"><?= $val["lose_price"] ?></p></td>
                                <td><p class="width-80"><?= $val["remarks"] ?></p></td>
                                <td><p class="width-80"><?= $val["remarks1"] ?></p></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-20" style="overflow: auto; margin-top: 10px">
                <?php if (!empty($verify)){ ?>
                <div>
                    <h2 class="head-second color-1f7ed0">
                        <i class="icon-caret-right"></i>
                        <a href="javascript:void(0)">签核信息</a>
                    </h2>
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
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

            <script>
                $(function () {
                    $(".head-second").next("div:eq(0)").css("display", "block");
                    $(".head-second>a").click(function () {
                        $(this).parent().next().slideToggle();
                        $(this).prev().toggleClass("icon-caret-down");
                        $(this).prev().toggleClass("icon-caret-right");
                        $(".retable").datagrid("resize");
                    });
                    var app_id="<?=$model["app_id"]?>";
                    var recer="<?=$model["recer"]?>";
                    if(app_id==recer){
                        $(".fd").css("display",'none');
                    }
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

