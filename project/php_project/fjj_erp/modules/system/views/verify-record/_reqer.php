<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/11/23
 * Time: 下午 02:26
 */
use app\classes\Menu;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = '请购单详情';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '请购申请审核'];
?>
<style>
    td p {
        display: block;
        overflow: hidden;
        word-break: break-all;
        word-wrap: break-word;
    }

    thead tr th p {
        color: white;
    }

    .width50 {
        width: 50px;
    }

    .width200 {
        width: 220px;
    }

    .width110 {
        width: 110px;
    }

    .ml-20 {
        margin-left: 20px;
    }

    .width100 {
        width: 100px;
    }

    .width150 {
        width: 150px;
    }

    .width220 {
        width: 220px;
    }

    .width270 {
        width: 170px;
    }

    .head-second + div {
        display: none;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <div class="mb-30">
        <h2 class="head-first">
            <?= $this->title ?>
            <span style="color: white;float: right;font-size:12px;margin-right:20px">请购单号：<?= $model['req_no'] ?></span>
        </h2>
        <div class="mb-10">
            <?= Html::button('通过', ['class' => 'button-blue', 'id' => 'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue', 'id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">请购单信息</a>
        </h2>
        <div>
        <div class="mb-10">
            <input type="hidden" id="_regid" value="<?= $id ?>">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">请购单号：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['req_no'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">申请日期：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['app_date'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">请购形式：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['req_rqf'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">单据类型：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['req_dct'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">采购区域：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['area_id'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">所属法人：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['leg_id'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">请购部门：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['spp_dpt_id'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">申请人：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['app_id'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">联系方式：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['contact'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">配送地点：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['addr'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">采购方式：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['req_type'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">费用类型：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['cst_type'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">币别：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['cur_id'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">合同协议号：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['agr_code'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">e商贸部门：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['e_dpt_id'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">来源：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['scrce'] ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">是否领用人：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['yn_lead'] == 1 ? '是' : '否' ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">多部门领用：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['yn_mul_dpt'] == 1 ? '是' : '否' ?></td>
                </tr>
            </table>
        </div>
        <?php if ($model['yn_lead'] == 0) { ?>
            <div class="mb-10 fd">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="17%" class="no-border qlabel-align vertical-center">领用人：</td>
                        <td width="31%" class="no-border  text-left vertical-center"><?= $model['recer'] ?></td>
                        <td width="4%" class="no-border vertical-center"></td>
                        <td width="17%" class="no-border qlabel-align vertical-center">联系方式：</td>
                        <td width="35%"
                            class="no-border vertical-center"><?= $model['rec_cont'] ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">总务备品：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['yn_aff'] == 1 ? '是' : '否' ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">是否三方贸易：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['yn_three'] == 1 ? '是' : '否' ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">是否设备部预算：</td>
                    <td width="31%"
                        class="no-border text-left vertical-center"><?= $model['yn_eqp_budget'] == 1 ? '是' : '否' ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">是否已做低值易耗品判断：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['yn_low_value'] == 1 ? '是' : '否' ?></td>
                </tr>
            </table>
        </div>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="17%" class="no-border qlabel-align vertical-center">是否做固资管控：</td>
                    <td width="31%" class="no-border  text-left vertical-center"><?= $model['yn_fix_cntrl'] == 1 ? '是' : '否' ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="17%" class="no-border qlabel-align vertical-center">是否来自需求单：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $model['yn_req'] == 1 ? '是' : '否' ?></td>
                </tr>
            </table>
        </div>
        <?php if ($model['req_rqf_id'] == '100911') { ?>
            <div class="mb-10">
                <table width="90%" class="no-border  vertical-center ml-25">
                    <tr class="no-border">
                        <td width="15%" class="no-border  qlabel-align vertical-center">专案代码：</td>
                        <td width="74%" class="no-border text-left vertical-center"><?= $model['prj_code'] ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
        <?php if ($model['req_dct_id'] != '109018') { ?>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="15%" class="no-border  qlabel-align vertical-center">采购部门：</td>
                        <td width="74%" class="no-border text-left vertical-center"><?= $model['req_dpt_id'] ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
        <div class="mb-10">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="15%" class="no-border  qlabel-align vertical-center">请购原因/用途：</td>
                    <td width="74%" class="no-border text-left vertical-center"><?= $model['remarks'] ?></td>
                </tr>
            </table>
        </div>
        <?php if ($model['yn_can'] != 0) { ?>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="15%" class="no-border  qlabel-align vertical-center">取消原因：</td>
                        <td width="74%" class="no-border text-left vertical-center"><?= $model['can_rsn'] ?></td>
                    </tr>
                </table>
            </div>
        <?php } ?>
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">商品信息</a>
    </h2>
    <div class="mb-20" style="overflow: auto">
        <div style="width:100%;overflow: auto;">
            <table class="table" style="width: 2500px;">
                <thead>
                <tr style="height: 50px">
                    <th width="50">序号</th>
                    <th width="100">料号</th>
                    <th width="100">品名</th>
                    <th width="100">规格</th>
                    <th width="100">品牌</th>
                    <th width="100">单位</th>
                    <th width="100">请购量</th>
                    <!--                <th width="100">单价</th>-->
                    <!--                <th width="100">供应商代码</th>-->
                    <!--                <th width="100">金额</th>-->
                    <th width="100">费用科目</th>
                    <th width="100">需求日期</th>
                    <th width="100">专案编号</th>
                    <!--                <th width="100">剩余预算</th>-->
                    <!--                <th width="100">原币单价</th>-->
                    <!--                <th width="100">退税率</th>-->
                    <th width="100">备注</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach ($pdtmodel as $key => $val) { ?>
                    <tr style="height: 50px;">
                        <td><p class="width-40"><?= ($key + 1) ?></p></td>
                        <td><p class="width-80"><?= $val["part_no"] ?></p></td>
                        <td><p class="width-80"><?= $val["pdt_name"] ?></p></td>
                        <td><p class="width-80"><?= $val["tp_spec"] ?></p></td>
                        <td><p class="width-80"><?= $val["brand"] ?></p></td>
                        <td><p class="width-80"><?= $val["unit"] ?></p></td>
                        <td><p class="width-80"><?= $val["req_nums"] ?></p></td>
                        <!--                    <td><p class="width-80">--><?//=number_format( $val["req_price"],5)?><!--</p></td>-->
                        <!--                    <td><p class="width-80">--><?//= $val["spp_id"] ?><!--</p></td>-->
                        <!--                    <td><p class="width-80">--><?//=number_format($val["total_amount"],2) ?><!--</p></td>-->
                        <td><p class="width-80"><?= $val["bs_req_dt"] ?></p></td>
                        <td><p class="width-80"><?= $val["req_date"] ?></p></td>
                        <td><p class="width-80"><?= $val["prj_no"] ?></p></td>
                        <!--                    <td><p class="width-80">--><?//= $val["sonl_remark"] ?><!--</p></td>-->
                        <!--                    <td><p class="width-80">--><?//= $val["org_price"] ?><!--</p></td>-->
                        <!--                    <td><p class="width-80">--><?//= $val["rebat_rate"] ?><!--</p></td>-->
                        <td><p class="width-80"><?= $val["remarks"] ?></p></td>
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

    <script>
        //审核详情
        $(function () {
            $(".head-second").next("div:eq(0)").css("display", "block");
            $(".head-second>a").click(function () {
                $(this).parent().next().slideToggle();
                $(this).prev().toggleClass("icon-caret-down");
                $(this).prev().toggleClass("icon-caret-right");
                $(".retable").datagrid("resize");
            });
            var app_id = "<?=$model["app_id"]?>";
            var recer = "<?=$model["recer"]?>";
            if (app_id == recer) {
                $(".fd").css("display", 'none');
            }
            //驳回
//                $("#reject").on("click", function () {
//                    layer.confirm("是否驳回?",
//                        {
//                            btn: ['确定', '取消'],
//                            icon: 2
//                        },
//                        function () {
//                            $.ajax({
//                                type: "post",
//                                dataType: "json",
//                                data: $("#check-form").serialize(),
//                                url: "<?//= Url::to(['/system/verify-record/audit-reject']) ?>//",
//                                success: function (msg) {
//                                    if (msg.flag == 1) {
//                                        layer.alert(msg.msg, {icon: 1}, function () {
//                                            parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                                        });
//                                    } else {
//                                        layer.alert(msg.msg, {icon: 2})
//                                    }
//                                }
//                            })
//                        },
//                        function () {
//                            layer.closeAll();
//                        }
//                    )
//                });

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
            //通过
//                $("#pass").on("click", function () {
//                    layer.confirm("是否通过?",
//                        {
//                            btn: ['确定', '取消'],
//                            icon: 2
//                        },
//                        function () {
//                            $.ajax({
//                                type: "post",
//                                dataType: "json",
//                                data: $("#check-form").serialize(),
//                                url: "<?//= Url::to(['/system/verify-record/audit-pass']) ?>//",
//                                success: function (msg) {
//                                    if (msg.flag == 1) {
//                                        layer.alert(msg.msg, {icon: 1}, function () {
//                                            parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                                        });
//                                    } else {
//                                        layer.alert(msg.msg, {icon: 2})
//                                    }
//                                },
//                                error: function (data) {
////                            console.log('data: ',data)
//                                }
//                            })
//                        },
//                        function () {
//                            layer.closeAll();
//                        }
//                    )
//                });

        })

    </script>


