<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '采购单详情';
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
        width: 270px;
    }

    .req_no {
        color: blue;
        text-align: center;
        cursor: pointer;
    }
</style>
<!--startprint-->
<div class="content">
    <div class="mb-30">
        <h2 class="head-first">
            <?= $this->title ?>
            <span
                style="color: white;float: right;font-size:12px;margin-right:20px">采购单号：<?= $model['prch_no'] ?></span>
        </h2>
        <h2 class="head-second color-1f7ed0">
            <a >采购单信息</a>
        </h2>
        <div>
            <div class="mb-10">
                <input type="hidden" id="_regid" value="<?= $id ?>">
                <label class="label-width qlabel-align width100 ml-20" style="width: 100px">采购单号<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['prch_no'] ?></label>
                <label class="label-width qlabel-align width270" style="width:250px">采购单状态<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['prch_name'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20" style="width: 100px">单据类型<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['req_dct'] ?></label>
                <label class="label-width qlabel-align width270" style="width:250px">法人<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['leg_id'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20" style="width: 100px">采购区域<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['area_id'] ?></label>
                <label class="label-width qlabel-align width270" style="width:250px">采购部门<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['dpt_id'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20" style="width: 100px">采购员<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['apper'] ?>
                    --<?= $model['staff_code'] ?></label>
                <label class="label-width qlabel-align width270" style="width:250px">联系方式<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['contact_info'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20" style="width: 100px">收货中心<label>：</label></label>
                <label class="label-width text-left width200 " style="width:200px"><?= $model['rcp_name'] ?></label>
                <label class="label-width qlabel-align width270" style="width:250px">采购日期<label>：</label></label>
                <label class="label-width text-left width200" style="width:200px"><?= $model['app_date'] ?></label>
                <div class="mb-10">
                    <label class="label-width qlabel-align width100 ml-20" style="width:100px">备注<label>：</label></label>
                    <label class="label-width text-left vertical-center" style="width:700px"><?= $model['remarks'] ?></label>
                </div>
                <?php if ($model['yn_can'] != 0) { ?>
                    <div class="mb-10">
                        <label class="label-width qlabel-align width100 ml-20">取消原因<label>：</label></label>
                        <label class="label-width text-left vertical-center" style="width:700px"><?= $model['can_rsn'] ?></label>
                    </div>
                <?php } ?>
            </div>
        </div>
        <h2 class="head-second color-1f7ed0">
            <a >商品信息</a>
        </h2>
        <div class="mb-20" >
            <div style="width:990px;overflow: auto;">
                <table class="table" >
                    <thead>
                    <tr style="height: 50px">
                        <th><p class="width-40">序号</p></th>
                        <th><p class="width-140">料号</p></th>
                        <th><p class="width-90">品名</p></th>
                        <th><p class="width-90">规格</p></th>
                        <th><p class="width-90">品牌</p></th>
                        <th><p class="width-90">单位</p></th>
<!--                        <th><p class="width-90">供应商代码</p></th>-->
                        <th><p class="width-90">供应商名称</p></th>
                        <th><p class="width-90">付款条件</p></th>
                        <th><p class="width-90">交货条件</p></th>
                        <th><p class="width-90">单价</p></th>
                        <th><p class="width-90">采购数量</p></th>
<!--                        <th><p class="width-90">金额</p></th>-->
<!--                        <th><p class="width-90">付款方式</p></th>-->
<!--                        <th><p class="width-90">税别/税率</p></th>-->
<!--                        <th><p class="width-90">币别</p></th>-->
                        <th><p class="width-90">交货日期</p></th>
<!--                        <th><p class="width-90">送货地址</p></th>-->
<!--                        <th><p class="width-90">关联单号</p></th>-->
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <?php foreach ($products as $key => $val) { ?>

                        <tr style="height: 50px;" data-id="<?= $val["part_no"] ?>" data-name="<?= $val["spp_fname"] ?>">
                            <td><?= ($key + 1) ?></td>
                            <!-- 料号-->
                            <td><?= $val["part_no"] ?></td>
                            <!-- 品名-->
                            <td><?= ($val["pdt_name"]) ?></td>
                            <!-- 规格-->
                            <td><?= $val["tp_spec"] ?></td>
                            <!--品牌-->
                            <td><?= $val["brand"] ?></td>
                            <!--单位-->
                            <td><?= $val["unit"] ?></td>
                            <!-- 供应商代码-->
<!--                            <td>--><?//= $val["spp_code"] ?><!--</td>-->
                            <!--供应商名称-->
                            <td><?= $val["spp_fname"] ?></td>
                            <!--                        付款条件-->
                            <td><?= $val["pay_condition"] ?></td>
                            <!--                        交货条件-->
                            <td><?= $val["goods_condition"] ?></td>
                            <!--单价-->
                            <td><?= number_format($val["price"], 5) ?></td>
                            <!--采购数量-->
                            <td><?=sprintf("%.2f",$val["prch_num"]) ?></td>
                            <!--金额-->
<!--                            <td>--><?//= number_format($val["total_amount"], 2) ?><!--</td>-->
                            <!--付款方式-->
<!--                            <td>--><?//= $val["bs_prch"] ?><!--</td>-->
                            <!--税别/税率 -->
<!--                            <td>--><?//= $val["tax_no"] ?><!--/--><?//= $val["tax_value"] ?><!--</td>-->
                            <!--币别-->
<!--                            <td>--><?//= $val["cur_code"] ?><!--</td>-->
                            <!--交货日期-->
                            <td><?= $val["deliv_date"] ?></td>
                            <!-- 送货地址-->
<!--                            <td>--><?//= $val["wh_code"] ?><!--</td>-->
                            <!--关联单号-->
<!--                            <td class="width-80 req_no" data-id="--><?//= $val['req_id'] ?><!--">--><?//= $val["req_no"] ?><!--</td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mb-10" style=" margin-top: 10px">
            <?php if (!empty($verify)){ ?>
            <div>
                <h2 class="head-second color-1f7ed0">
                    <a >签核信息</a>
                </h2>
                <div>
                    <table class="mb-30 product-list">
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
        <!--endprint-->
        <div style="text-align: center;margin-top: 10px">
            <button type="button" id="print" class="button-blue-big" onclick="btnPrints()">打印</button>
            <button  onclick="close_select()" class="button-white-big ml-20" type="button">取消</button>
        </div>
        <script>
            $(function () {
                $("#submit").on("click", function () {
                    window.history.go(-1);
                });
                $(".req_no").on("click", function () {
                    var id = $(this).data("id");
                    window.location.href = "<?=Url::to(['/purchase/purchase-apply/view'])?>?id=" + $(this).data("id");
                })
                var app_id = "<?=$model["app_id"]?>";
                var recer = "<?=$model["recer"]?>";
                if (app_id == recer) {
                    $(".fd").css("display", 'none');
                }

                //合并单元格
                for (var index = 11; index >= 0; index--) {
                    autoRowSpan("product_table", 0, index);
                }
            });
            //合并单元格
            function autoRowSpan(product_table, row, col) {
                var tb = document.getElementById(product_table);
                var lastValue = "";                   //前一单元格值
                var value = "";                       //当前单元格值
                var pos = 1;                          //累计数
                var id = "";                        //当前行料号
                var lastId = "";                    //前一行料号
                var ventor = "";                    //当前行供应商
                var lastVentor = "";                //上一行供应商
                var index = 1                        //累计数
                var productCodeAry = new Array();   //存放单号的，用于去除重复单号


                for (var i = row; i < tb.rows.length; i++) {
                    var d = $(tb).children("tr").eq(i).children("td").eq(col);
//                    value = tb.rows[i].cells[col].innerText;                //获取当前单元格值
                    value = d.text();
//                    id = $(tb.rows[i].cells[col]).parent().data('id');      //获取当前行的料号
                    id = d.parent().data("id");
//                    ventor = $(tb.rows[i].cells[col]).parent().data('name');//获取当前行的供应商
                    ventor = d.parent().data("name");

                    //根据料号合并追加单号
//                    if (col == 16) {
//                        var text = $(tb.rows[i].cells[col]).text();
//                        if (id != "") {
//                            if (id == lastId) {
//                                if ($.inArray(text, productCodeAry) === -1) {
//                                    productCodeAry.push(text);
//                                    var lastText = $(tb.rows[i - index].cells[col]).text() + "," + $(tb.rows[i].cells[col]).text();
//                                    $(tb.rows[i - index].cells[col]).text(lastText);
//                                }
//
//                                tb.rows[i].deleteCell(col);
//                                tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
//                                index++;
//                                pos++;
//                            }
//                            else {
//                                index = 1;
//                                productCodeAry = [];
//                                productCodeAry.push(text);
//                                lastId = id;
//                                pos = 1;
//                            }
//                        }
//
//                    }
                    //根据顺序号合并
                    if (col == 0) {
                        if (id != "") {
                            if (id == lastId) {
                                tb.rows[i].deleteCell(col);
                                tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
                                $(tb.rows[i - pos].cells[col]).text(index - 1);

                                pos++;
                            }
                            else {
                                index++;
                                lastValue = value;
                                lastId = id;
                                pos = 1;
                            }
                        }
                    }

                    //根据供应商合并
//                    else if (col <= 10 && col >= 6) {
//                        if (lastVentor == ventor && id == lastId) {
//                            tb.rows[i].deleteCell(col);
//                            tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
//                            pos++;
//                        }
//                        else {
//                            lastVentor = ventor;
//                            lastId = id;
//                            pos = 1;
//                        }
//                    }
                    //根据料号合并
                    else {
                        if (id != "") {
                            if (id == lastId) {
                                tb.rows[i].deleteCell(col);
                                tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
                                pos++;
                            }
                            else {
                                lastValue = value;
                                lastId = id;
                                pos = 1;
                            }
                        }
                    }
                }
            }
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
