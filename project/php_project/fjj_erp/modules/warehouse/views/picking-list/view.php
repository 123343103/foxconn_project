<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '拣货单详情';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '拣货单列表', 'url' => Url::to(['index'])];
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
    .width-80{
        width: 80px;
    }
    .width-140{
        width: 240px;
    }
    .width-40{
        width: 40px;
    }
    .height-50{
        height: 50px;
        /*line-height: 70px;*/
    }
    .text-no-text{
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
<div class="content">
    <div class="mb-30">
        <h2 class="head-first">
            <?= $this->title ?>
        </h2>
        <div class="border-bottom mb-20">
            <?php if ($data["status"] == 4) { ?>
                <?= Menu::isAction('/sale/warehouse/picking-list/cancel-pick') ? Html::button('取消', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id'=>'cancel-pick']) : '' ?>
            <?php } ?>
            <?= Menu::isAction('/sale/warehouse/picking-list/index') ? Html::button('切换列表', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
            <?= Html::button('打印', ['class' => 'button-mody','onclick'=>'btnPrints()']) ?>
        </div>
        <!--startprint-->
        <h2 class="head-second">
             拣货单信息
        </h2>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">拣货单号：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["pck_no"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">拣货单状态：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["status"] == 4? "待拣货" : ($data["status"] == 1 ? "已拣货" : ($data["status"] == 2 ? "已出库" : "已取消")) ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">拣货单日期：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["pic_date"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">申请部门：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["organization_name"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">申请人：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["staff_name"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">法人：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["company_name"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">客户名称：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["cust_sname"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">关联订单号：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["ord_no"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">订单类型：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["business_value"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">仓库名称：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["wh_name"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">仓库代码：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["wh_code"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">仓库属性：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["wh_attr"] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">出货通知单号：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["note_no"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">厂商出库单号：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["addr_pho"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">操作人：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["pickor"] ?></td>
            </tr>
        </table>
        <?php if ($data["status"] == 3) { ?>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="13%" class="no-border qlabel-align vertical-center">取消原因：</td>
                    <td width="87%" class="no-border vertical-center"><?= $data["cancle_reason"] ?></td>
                </tr>
            </table>
        <?php } ?>
    </div>
    <h2 class="head-second">
        商品信息
        <?php if ($data["status"] == 4) { ?>
            <a id="maintenance-pick">拣货数量维护</a>
        <?php } ?>
    </h2>
    <div class="mb-20" style="overflow: auto">
        <table class="table" style="table-layout: fixed;width: 1500px">
            <thead>
            <tr style="height: 50px">
                <th class="width-40"><p >序号</p></th>
                <th class="width-140"><p >商品</p></th>
                <th class="width-80"><p >需求出货数量</p></th>
                <th class="width-80"><p >拣货数量</p></th>
                <th class="width-80"><p >交易单位</p></th>
                <th class="width-80"><p >区位</p></th>
                <th class="width-80"><p>货架位</p></th>
                <th class="width-80"><p>储位</p></th>
                <th class="width-80"><p>数量</p></th>
                <th class="width-80"><p>批次</p></th>
                <th class="width-80"><p>拣货日期</p></th>
                <th class="width-80"><p>备注</p></th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php foreach ($productinfo as $key => $val) { ?>
                <tr style="height: 50px;">
                    <td class="width-40 height-50"><p ><?= ($key + 1) ?></p></td>
                    <td class="width-140 height-50 text-no-text" style="text-align:left;overflow: hidden">
                        <?= '料号：' . $val["part_no"] . '<br/>' . '品名：' . $val["pdt_name"] . '<br/>' . '规格：' . $val["tp_spec"] . '<br/>' . '品牌：' . $val['brand_name_cn'] ?>
                    </td>
                    <td class="width-80 height-50"><?= $val["nums"] ?></td>
                    <td class="width-80 height-50"><?= $val["pck_nums"] ?></td>
                    <td class="width-80 height-50"><?= $val["unit"] ?></td>
                    <td class="width-80 height-50"><?= $val["part_name"] ?></td>
                    <td class="width-80 height-50"><?= $val["rack_code"] ?></td>
                    <td class="width-80 height-50"><?= $val["st_id"] ?></td>
                    <td class="width-80 height-50"><?= $val["count_pck_nums"] ?></td>
                    <td class="width-80 height-50"><?= $val["L_invt_bach"] ?></td>
                    <td class="width-80 height-50"><?= $val["pack_date"] ?></td>
                    <td class="width-80 height-50 text-no-text"><?= $val["marks"] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <!--endprint-->
</div>
<script>
    $(function () {
        $("#submit").on("click", function () {
            window.history.go(-1);
        });

        // 生成拣货单
        $('#create-pick').click(function () {
            var id = "<?=$data['sonh_id'] ?>";
            $.ajax({
                type: 'post',
                dataType: 'json',
//                    data:$("#note-form").serialize(),
                url: "<?= \yii\helpers\Url::to(['create-pick?id='])?>" + id,
                success: function (data) {
                    if (data.status == 1) {
                        layer.alert("通知发送成功！", {
                            icon: 1, end: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        layer.alert(data.msg, {
                            icon: 1, end: function () {

                            }
                        });
                    }
                },
                error: function (data) {
                }
            })
        })

        // 取消通知
        $('#cancel-pick').click(function () {
            var id = "<?=$id ?>";
            $.fancybox({
                type:"iframe",
                padding:0,
                width:480,
                height:300,
                href:"<?=Url::to(['cancel-pick'])?>?id=" + id,
            });
        })
    });

    //拣货数量维护
    $("#maintenance-pick").on("click", function () {
//            var a = $("#data").datagrid("getSelected");
        $.fancybox({
            type: "iframe",
            padding: 0,
            width: 900,
            height: 600,
            href: "<?=Url::to(['maintenance-pick'])?>?id=" +<?=$id?>,
        });
    })
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
