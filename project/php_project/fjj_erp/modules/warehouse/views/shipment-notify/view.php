<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '出货通知单详情';
$this->params['homeLike'] = ['label' => '销售管理'];
$this->params['breadcrumbs'][] = ['label' => '出货通知单列表','url'=>Url::to(['index'])];
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
            <?=$this->title ?>
        </h2>
        <div class="border-bottom mb-20">
            <?php if($data["status"]=="待处理"){?>
                <?= Menu::isAction('/sale/warehouse/shipment-notify/cancel-notify') ? Html::button('取消', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id'=>'cancel-notify']) : '' ?>
            <?php } ?>
            <?= Menu::isAction('/sale/warehouse/shipment-notify/index') ? Html::button('切换列表', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
            <?= Html::button('打印', ['class' => 'button-mody','onclick'=>'btnPrints()']) ?>
        </div>
        <!--startprint-->
        <h2 class="head-second">
            出货通知单信息
        </h2>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">出货通知单号：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["note_no"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">通知单日期：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["n_time"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">通知部门：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["organization_name"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">通知人：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["staff_name"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">订单类型：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["business_value"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">关联订单号：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["ord_no"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">客户名称：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["cust_sname"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">交易法人：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["company_name"] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">运输方式：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["tran_sname"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">紧急程度：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["urg"]=="1"?"一般":( $data["urg"]=="2"?"急":"紧急") ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">收货人：</td>
                <td width="35%" class="no-border vertical-center"><?= $data["receipter"] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border qlabel-align vertical-center">联系方式：</td>
                <td width="35%"
                    class="no-border vertical-center"><?= $data["receipter_Tel"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">单据状态：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["status"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <table width="90%" class="no-border vertical-center ml-25">
            <tr class="no-border">
                <td width="13%" class="no-border qlabel-align vertical-center">收货地址：</td>
                <td width="87%" class="no-border vertical-center"><?= $data["receipt_Address"] ?></td>
            </tr>
        </table>
        <div class="space-20"></div>
        <?php if($data["yn_cancel"]!=0){?>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="13%" class="no-border qlabel-align vertical-center">取消原因：</td>
                    <td width="87%" class="no-border vertical-center"><?= $data["cancel_rs"] ?></td>
                </tr>
            </table>
        <?php }?>
    </div>
    <h2 class="head-second">
        商品信息
    </h2>
    <div class="mb-20" style="overflow: auto">
        <table class="table" style="table-layout: fixed;width: 1500px">
            <thead>
            <tr style="height: 50px">
                <th class="width-40"><p>序号</p></th>
                <th class="width-140"><p >商品</p></th>
                <th class="width-80"><p >订单数量</p></th>
                <th class="width-80"><p >出货数量</p></th>
                <th class="width-80"><p >出仓仓库</p></th>
                <th class="width-80"><p >交易单位</p></th>
                <th class="width-80"><p >商品单价 <br/>（含税）</p></th>
                <th class="width-80"><p >订单总金额 <br/>（含税）</p></th>
                <th class="width-80"><p >配送方式</p></th>
                <th class="width-80"><p >交期</p></th>
                <th class="width-80"><p >备注</p></th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php foreach ($productinfo as $key => $val) { ?>
                <tr style="height: 50px;">
                    <td class="width-40 height-50"><?= ($key + 1) ?></p></td>
                    <td class="width-140 height-50 text-no-text" style="text-align:left;overflow: hidden"><?= '料号：'. $val["part_no"] . '<br/>' . '品名：' . $val["pdt_name"] . '<br/>' . '规格：' . $val["tp_spec"] . '<br/>' . '品牌：' . $val['brand_name_cn'] ?></td>
                    <td class="width-80 height-50"><?= $val["sapl_quantity"] ?></td>
                    <td class="width-80 height-50"><?= ($val["nums"]) ?></td>
                    <td class="width-80 height-50"><?= ($val["wh_name"]) ?></td>
                    <td class="width-80 height-50"><?= $val["unit"] ?></td>
                    <td class="width-80 height-50"><?= number_format($val["uprice_tax_o"],5) ?></td>
                    <td class="width-80 height-50"><?= number_format($val["tprice_tax_o"],2) ?></td>
                    <td class="width-80 height-50"><?= $val["bdm_sname"] ?></td>
                    <td class="width-80 height-50"><?= $val["request_date"] ?></td>
                    <td class="width-80 text-no-text"><?= $val["sapl_remark"] ?></td>
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
            $('#create-pick').click(function(){
                var id = "<?=$data['sonh_id'] ?>";
                $.ajax({
                    type:'post',
                    dataType:'json',
//                    data:$("#note-form").serialize(),
                    url:"<?= \yii\helpers\Url::to(['create-pick?id='])?>" + id,
                    success:function(data){
                        if(data.status == 1){
                            layer.alert("通知发送成功！",{icon:1,end: function () {
                                window.location.reload();
                            }});
                        } else {
                            layer.alert(data.msg,{icon:1,end: function () {

                            }});
                        }
                    },
                    error: function (data) {
                    }
                })
            })

            // 取消通知
            $('#cancel-notify').click(function(){
                var id = "<?=$data['note_pkid'] ?>";
                $.fancybox({
                    type:"iframe",
                    padding:0,
                    width:480,
                    height:300,
                    href:"<?=Url::to(['cancel-notify'])?>?id=" + id,
                });
//                $.ajax({
//                    type:'post',
//                    dataType:'json',
////                    data:$("#note-form").serialize(),
//                    url:"<?//= \yii\helpers\Url::to(['cancel-notify?id='])?>//" + id,
//                    success:function(data){
//                        if(data.status == 1){
//                            layer.alert(data.msg,{icon:1,end: function () {
//                                window.location.reload();
//                            }});
//                        } else {
//                            layer.alert(data.msg,{icon:1,end: function () {
//
//                            }});
//                        }
//                    },
//                    error: function (data) {
//                    }
//                })
            })
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
