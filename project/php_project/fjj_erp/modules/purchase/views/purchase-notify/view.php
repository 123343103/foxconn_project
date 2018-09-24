<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '采购单详情';
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '采购单列表', 'url' => Url::to(['index'])];
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

    .head-second + div {
        display: none;
    }

    .req_no {
        color: blue;
        text-align: center;
        cursor: pointer;
    }
</style>
<div class="content">
    <div class="mb-30">
        <h2 class="head-first">
            <?= $this->title ?>
            <span
                style="color: white;float: right;font-size:12px;margin-right:20px">采购单号：<?= $model['prch_no'] ?></span>
        </h2>
        <div class="border-bottom mb-20">
            <?php if ($model['prch_status'] == 40 || $model['prch_status'] == 44) { ?>
                <?= Html::button('修改', ['class' => 'button-mody', 'onclick' => 'window.location.href=\'' . Url::to(["update", 'id' => $model['prch_id']]) . '\'']) ?>
                <?= Html::button('送审', ['class' => 'button-check', 'id' => 'check_btn']) ?>
            <?php } ?>
            <?php if ($model['prch_status'] == 47||$model['prch_status'] == 49) { ?>
                <?= Html::button('生成收货通知', ['class' => 'button-mody', 'id' => 'notice_btn', "style" => "width:100px"]) ?>
            <?php } ?>
            <?= Html::button('切换列表', ['class' => 'button-change', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            <?= Html::button('打印', ['class' => 'button-blue width-80', 'onclick'=>'btnPrints()']) ?>
        </div>
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">采购单信息</a>
        </h2>
        <div>
            <div class="mb-10">
                <input type="hidden" id="_regid" value="<?= $id ?>">
                <label class="label-width qlabel-align width100 ml-20">采购单号<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['prch_no'] ?></label>
                <label class="label-width qlabel-align width270">采购单状态<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['prch_name'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">单据类型<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['req_dct'] ?></label>
                <label class="label-width qlabel-align width270">法人<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['leg_id'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">采购区域<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['area_id'] ?></label>
                <label class="label-width qlabel-align width270">采购部门<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['dpt_id'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">采购员<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['apper'] ?>
                    --<?= $model['staff_code'] ?></label>
                <label class="label-width qlabel-align width270">联系方式<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['contact_info'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">收货中心<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['rcp_name'] ?></label>
                <label class="label-width qlabel-align width270">采购日期<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['app_date'] ?></label>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">备注<label>：</label></label>
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
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">商品信息</a>
    </h2>
    <div class="mb-20" style="overflow: auto;">
        <div style="width:100%;overflow: auto;">
            <table class="table" style="width:1400px;">
                <thead>
                <tr style="height: 50px">
                    <th><p class="width-40">序号</p></th>
                    <th><p class="width-140">料号</p></th>
                    <th><p class="width-90">品名</p></th>
                    <th><p class="width-90">规格</p></th>
                    <th><p class="width-90">品牌</p></th>
                    <th><p class="width-90">单位</p></th>
                    <th><p class="width-90">供应商代码</p></th>
                    <th><p class="width-90">供应商名称</p></th>
                    <th><p class="width-90">付款条件</p></th>
                    <th><p class="width-90">交货条件</p></th>
                    <th><p class="width-90">单价</p></th>
                    <th><p class="width-90">采购数量</p></th>
                    <th><p class="width-90">金额</p></th>
<!--                    <th><p class="width-90">付款方式</p></th>-->
                    <th><p class="width-90">税别/税率</p></th>
                    <th><p class="width-90">币别</p></th>
                    <th><p class="width-90">交货日期</p></th>
                    <th><p class="width-90">关联单号</p></th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach ($products as $key => $val) { ?>

                    <tr style="height: 50px;" data-id="<?= $val["part_no"] ?>" data-name="<?= $val["spp_code"] ?>">
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
                        <td><?= $val["group_code"] ?></td>
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
                        <td><?= number_format($val["total_amount"], 2) ?></td>
                        <!--付款方式-->
<!--                        <td>--><?//= $val["bs_prch"] ?><!--</td>-->
                        <!--税别/税率 -->
                        <td><?= $val["tax_no"] ?>/<?= $val["tax_value"] ?></td>
                        <!--币别-->
                        <td><?= $val["cur_id"] ?></td>
                        <!--交货日期-->
                        <td><?= $val["deliv_date"] ?></td>
                        <!--关联单号-->
                        <td class="width-80 req_no" data-id="<?= $val['req_id'] ?>"><?= $val["req_no"] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-10" style="overflow: auto; margin-top: 10px">
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
        $(function () {
            $("#submit").on("click", function () {
                window.history.go(-1);
            });
            $(".req_no").on("click", function () {
                var id = $(this).data("id");
                window.location.href = "<?=Url::to(['/purchase/purchase-apply/view'])?>?id=" + $(this).data("id");
            })
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
            $('#cancel-notify').click(function () {
                var id = "<?=$data['sonh_id'] ?>";
                $.ajax({
                    type: 'post',
                    dataType: 'json',
//                    data:$("#note-form").serialize(),
                    url: "<?= \yii\helpers\Url::to(['cancel-notify?id='])?>" + id,
                    success: function (data) {
                        if (data.status == 1) {
                            layer.alert(data.msg, {
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
            //生成收货通知单
            $("#notice_btn").on("click",function(){
                var arr = [];
                // var areaid=null;
                var id="<?=$model['prch_id']?>";
                $.fancybox({
                    href:"<?=Url::to(['notice-new'])?>?id="+id,
                    type:"iframe",
                    padding:0,
                    autoSize:false,
                    width:650,
                    height:520
                });
            });
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

            //合并单元格
            for (var index = 16; index >= 0; index--) {
                autoRowSpan("product_table", 0, index);
            }
            $("#check_btn").click(function () {
                var id = $("#_regid").val();
                var url = "<?=Url::to(['view'], true)?>?id=" + id;
                var type = 55;
                $.fancybox({
                    href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480,
                    afterClose: function () {
                        location.reload();
                    }
                });
            })
        });
        //合并单元格
        function autoRowSpan(product_table, row, col) {
            var tb = document.getElementById(product_table);
            var lastValue = "";                   //前一单元格值
            var value = "";                       //当前单元格值
            var pos = 1;                          //累计数
            var id = "";                        //当前行料    号
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
                if (col == 16) {
                    var text = $(tb.rows[i].cells[col]).text();
                    if (id != "") {
                        if (id == lastId) {
                            if ($.inArray(text, productCodeAry) === -1) {
                                productCodeAry.push(text);
                                var lastText = $(tb.rows[i - index].cells[col]).text() + "," + $(tb.rows[i].cells[col]).text();
                                $(tb.rows[i - index].cells[col]).text(lastText);
                            }

                            tb.rows[i].deleteCell(col);
                            tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
                            index++;
                            pos++;
                        }
                        else {
                            index = 1;
                            productCodeAry = [];
                            productCodeAry.push(text);
                            lastId = id;
                            pos = 1;
                        }
                    }

                }
                //根据顺序号合并
                else if (col == 0) {
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

//                //根据供应商合并
//                else if (col < 16 && col >= 6) {
//                    if (lastVentor == ventor && id == lastId) {
//                        tb.rows[i].deleteCell(col);
//                        tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
//                        pos++;
//                    }
//                    else {
//                        lastVentor = ventor;
//                        lastId = id;
//                        pos = 1;
//                    }
//                }
//                //根据料号合并
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
        function btnPrints(){
            var _id=$("#_regid").val();
            $.fancybox({
                padding: [],
                fitToView: true,
                width:1000,
                height: 800,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['prview'])?>?id="+_id
            });
//                $('.content').jqprint({
//                    debug: false,
//                    importCSS: true,
//                    printContainer: true,
//                    operaSupport: false
//                })
//                bdhtml=window.document.body.innerHTML;
//                sprnstr="<!--startprint-->";
//                eprnstr="<!--endprint-->";
//                prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);
//                prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
//                window.document.body.innerHTML=prnhtml;
//                window.print();
//                // 还原界面
//                window.document.body.innerHTML = bdhtml
//                window.location.reload();
        }
    </script>
