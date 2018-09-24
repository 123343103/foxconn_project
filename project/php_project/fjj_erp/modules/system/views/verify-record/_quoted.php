<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
use yii\widgets\ActiveForm;

$this->title = '报价单审核';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '报价单审核'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
//dumpE($data);
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
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h2 class="head-first">
        <?= $this->title; ?>
    </h2>
    <div class="border-bottom mb-20 pb-10">
        <?= (!empty($token)) ?Html::button('通过', ['class' => 'button-blue width-80 opt-btn','id'=>'pass']):'' ?>
        <?= (!empty($token))?Html::button('驳回', ['class' => 'button-blue width-80 opt-btn','id' => 'reject']):'' ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <div class="space-10"></div>
        <h2 class="head-second text-center">
            基本信息
        </h2>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">下单时间：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["saph_date"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">订单来源：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["older_from"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">订单类型：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["business_value"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">预计交货日期：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["delivery_date"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">客戶全称：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["cust_sname"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">客户代码：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["cust_code"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">联系人：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["cust_contacts"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">联系电话：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["cust_tel2"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">客户地址：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["cust_adress"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">公司电话：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["cust_tel1"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">交易法人：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["company_name"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">交易模式：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["trad_mode"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">交易币别：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["cur_code"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">合同编号：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["contract_no"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">发票类型：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["invoice_type"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">发票抬头：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["invoice_title"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">发票抬头地址：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["title_addr"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">发票寄送地址：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["send_addr"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">收货地址：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["delivery_addr"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">客户下单附件：</td>
                <?php foreach ($data["products"] as $key => $val) { ?>
                <?php } ?>
                <td width="87%" class="no-border vertical-center"><?= $data["cust_attachment"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">销售员下单附件：</td>
                <?php foreach ($data["products"] as $key => $val) { ?>
                <?php } ?>
                <td width="87%" class="no-border vertical-center"><?= $data["seller_attachment"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">订单备注说明：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["saph_remark"] ?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second text-center">
        订单商品信息
    </h2>
    <div class="mb-20" style="width: <?= !empty($style) ? $style : '710px';?>;overflow: auto;margin-left:10px;">
        <table class="table">
            <thead>
            <tr style="height: 50px;">
                <th><p class="width-40">序号</p></th>
                <th><p class="width-140">商品</p></th>
                <th><p class="width-80">下单数量</p></th>
                <th><p class="width-80">交易单位</p></th>
                <th><p class="width-80">商品单价 <br/>（含税）</p></th>
                <th><p class="width-80">商品总价 <br/>（含税）</p></th>
                <th><p class="width-80">折扣后金额</p></th>
                <th><p class="width-80">配送方式</p></th>
                <th><p class="width-80">出仓仓库</p></th>
                <th><p class="width-80">需求交期</p></th>
                <th><p class="width-80">交期</p></th>
                <th><p class="width-80">备注</p></th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php foreach ($data["products"] as $key => $val) { ?>
                <tr style="height: 50px;">
                    <td><p class="width-40"><?= ($key + 1) ?></p></td>
                    <td><p class="width-140"><?= $val[""] ?></p></td>
                    <td><p class="width-80"><?= $val["sapl_quantity"] ?></p></td>
                    <td><p class="width-80"><?= $val["unit"] ?></p></td>
                    <td><p class="width-80"><?= $val["uprice_tax_o"] ?></p></td>
                    <td><p class="width-80"><?= $val["tprice_tax_o"] ?></p></td>
                    <td><p class="width-80"><?= ($val["tprice_tax_o"] * $val["discount"]) ?></p></td>
                    <td><p class="width-80"><?= $val["bdm_sname"] ?></p></td>
                    <td><p class="width-80"><?= $val["wh_name"] ?></p></td>
                    <td><p class="width-80"><?= $val["delivery_date"] ?></p></td>
                    <td><p class="width-80"><?= $val["consignment_date"] ?></p></td>
                    <td><p class="width-80"><?= $val["sapl_remark"] ?></p></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="space-40 "></div>
    <table width="90%" class="no-border vertical-center ml-25">
        <tr class="no-border">
            <td width="13%" class="no-border vertical-center">总运费(含税)：</td>
            <td width="18%" class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">商品总金额(含税)：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">订单总金额(含税)：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data[""] ?></td>
        </tr>
    </table>
    <div class="space-40 "></div>
    <h2 class="head-second text-center">
        支付方式
    </h2>
    <table width="90%" class="no-border vertical-center ml-25">
        <tr class="no-border">
            <td width="13%" class="no-border vertical-center">剩余信用额度：</td>
            <td width="18%" class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">付款方式：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data["pat_sname"] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">收款条件：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data[""] ?></td>
        </tr>
    </table>
    <div class="space-40 "></div>
    <h2 class="head-second text-center">
        其他
    </h2>
    <table width="90%" class="no-border vertical-center ml-25">
        <tr class="no-border">
            <td width="13%" class="no-border vertical-center">部 门：</td>
            <td width="18%" class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">客户经理人：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data["custManager"] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">商品经理：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data[""] ?></td>
        </tr>
    </table>
    <div class="space-40 "></div>
    <table width="90%" class="no-border vertical-center ml-25">
        <tr class="no-border">
            <td width="13%" class="no-border vertical-center">销售区域：</td>
            <td width="18%" class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">销售点：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">销售代表：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data["sell_delegate"] ?></td>
        </tr>
    </table>
    <div class="space-40 "></div>
    <table width="90%" class="no-border vertical-center ml-25">
        <tr class="no-border">
            <td width="13%" class="no-border vertical-center">制单人：</td>
            <td width="18%" class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center">制单日期：</td>
            <td width="18%"
                class="no-border vertical-center"><?= $data[""] ?></td>
            <td width="4%" class="no-border vertical-center"></td>
            <td width="13%" class="no-border vertical-center"></td>
            <td width="18%"
                class="no-border vertical-center"></td>
        </tr>
    </table>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>审核路径</a>
    </h2>
    <div class="mb-30">
        <div class="mb-20" style="width:<?= !empty($style) ? $style : '720px';?>;">
            <div id="record"></div>
        </div>
    </div>
    <script>
        $(function () {
//            console.log("<?//= $token ?>//");
            $("#record").datagrid({
                url: "<?= Url::to(['/system/verify-record/load-record','id'=>$id,'token'=>$token]);?>",
                rownumbers: true,
                method: "get",
                idField: "vcoc_id",
                loadMsg: false,
//                    pagination: true,
                singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
                columns: [[
                    {
                        field: "verifyOrg", title: "审核节点", width: 150
                    },
                    {field: "verifyName", title: "审核人", width: 150},
                    {
                        field: "vcoc_datetime", title: "审核时间", width: 156
                    },
                    {field: "verifyStatus", title: "操作", width: 150},
                    {
                        field: "vcoc_remark", title: "审核意见", width: 200
                    },
                    {
                        field: "vcoc_computeip", title: "审核IP", width: 150
                    },

                ]],
                onLoadSuccess: function (data) {
                    showEmpty($(this),data.total,0);
                }
            });
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
                            url: "<?= Url::to(['/system/verify-record/audit-pass?token=']) . $token ?>",
                            success: function (msg) {
                                if (msg.flag == 1) {
                                    layer.alert(msg.msg, {icon: 1}, function () {
                                        parent.window.location.href = '<?= Url::to(['index']) ?>'
                                    });
                                } else if (msg.flag == 2) {
                                    layer.alert(msg.msg, {icon: 2}, function () {
                                        parent.$("#record").datagrid('reload');
                                        layer.closeAll();
                                    });
                                    if ( ("<?= $token ?>"!="undefined") && ("<?= $token ?>"!="") ) {
                                        $(".opt-btn").attr('disabled',true);
                                    }
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
                );
            });
            $("#reject").on("click", function () {
                layer.confirm("是否驳回?",
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        $.ajax({
                            type: "post",
                            dataType: "json",
                            data: $("#check-form").serialize(),
                            url: "<?= Url::to(['/system/verify-record/audit-reject?token=']) . $token ?>",
                            success: function (msg) {
                                if (msg.flag == 1) {
                                    layer.alert(msg.msg, {icon: 1}, function () {
                                        parent.window.location.href = '<?= Url::to(['index']) ?>'
                                    });
                                } else if (msg.flag == 2) {
                                    layer.alert(msg.msg, {icon: 2}, function () {
                                        parent.$("#record").datagrid('reload');
                                        layer.closeAll();
                                    })
                                } else {
                                    layer.alert(msg.msg, {icon: 2})
                                }
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });

            // 点击收起展开
            $(".head-three>a").click(function () {
                $(this).parent().next().slideToggle();
                $(this).prev().toggleClass("icon-caret-right");
                $(this).prev().toggleClass("icon-caret-down");
            });
        });
    </script>
