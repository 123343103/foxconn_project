<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
use yii\widgets\ActiveForm;

$this->title = '采购单详情';
$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '采购申请审核'];
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
    .width150{
        width: 150px;
    }
    .width220{
        width: 220px;
    }
    .width270{
        width: 270px;
    }
    .head-second + div {
        display: none;
    }
    .req_no{
        color: blue;
        text-align: center;
        cursor: pointer;
    }
</style>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <div class="mb-30">
        <h2 class="head-first">
            <?=$this->title ?>
            <span style="color: white;float: right;font-size:12px;margin-right:20px">采购单号：<?=$model['prch_no']?></span>
        </h2>
        <div class="mb-10">
            <?= Html::button('通过', ['class' => 'button-blue','id'=>'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue','id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue', 'style'=>'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">采购单信息</a>
        </h2>
        <div>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="17%" class="no-border qlabel-align vertical-center">采购单号：</td>
                        <td width="31%" class="no-border  text-left vertical-center"><?= $model['prch_no'] ?></td>
                        <td width="4%" class="no-border vertical-center"></td>
                        <td width="17%" class="no-border qlabel-align vertical-center">采购单状态：</td>
                        <td width="35%"
                            class="no-border vertical-center"><?= $model['prch_name'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="17%" class="no-border qlabel-align vertical-center">单据类型：</td>
                        <td width="31%" class="no-border  text-left vertical-center"><?= $model['req_dct'] ?></td>
                        <td width="4%" class="no-border vertical-center"></td>
                        <td width="17%" class="no-border qlabel-align vertical-center">法人：</td>
                        <td width="35%"
                            class="no-border vertical-center"><?= $model['leg_id'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="17%" class="no-border qlabel-align vertical-center">采购区域：</td>
                        <td width="31%" class="no-border  text-left vertical-center"><?= $model['area_id'] ?></td>
                        <td width="4%" class="no-border vertical-center"></td>
                        <td width="17%" class="no-border qlabel-align vertical-center">采购部门：</td>
                        <td width="35%"
                            class="no-border vertical-center"><?= $model['dpt_id'] ?></td>
                    </tr>
                </table>
            </div>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="17%" class="no-border qlabel-align vertical-center">采购员：</td>
                        <td width="31%" class="no-border  text-left vertical-center"><?= $model['apper'] ?>--<?= $model['staff_code'] ?></td>
                        <td width="4%" class="no-border vertical-center"></td>
                        <td width="17%" class="no-border qlabel-align vertical-center">联系方式：</td>
                        <td width="35%"
                            class="no-border vertical-center"><?= $model['contact_info']?></td>
                    </tr>
                </table>
            </div>
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="17%" class="no-border qlabel-align vertical-center">收货中心：</td>
                        <td width="31%" class="no-border  text-left vertical-center"><?= $model['rcp_name'] ?></td>
                        <td width="4%" class="no-border vertical-center"></td>
                        <td width="17%" class="no-border  qlabel-align vertical-center">采购日期：</td>
                        <td width="35%" class="no-border text-left vertical-center"><?= $model['app_date'] ?></td>
                    </tr>
                </table>
                <!--            <label class="label-width qlabel-align width270">供应商<label>：</label></label>-->
                <!--            <label class="label-width text-left width200">--><?//= $model['spp_id']?><!--</label>-->
            </div>
            <!--        <div class="mb-10">-->
            <!--            <label class="label-width qlabel-align width100 ml-20">付款方式<label>：</label></label>-->
            <!--            <label class="label-width text-left width200">--><?//= $model['pay_type'] ?><!--</label>-->
            <!--            <label class="label-width qlabel-align width270">税别/税率<label>：</label></label>-->
            <!--            <label class="label-width text-left width200">--><?//= $model['tax']?><!--</label>-->
            <!--        </div>-->
            <!--        <div class="mb-10">-->
            <!--            <label class="label-width qlabel-align width100 ml-20">币别<label>：</label></label>-->
            <!--            <label class="label-width text-left width200">--><?//= $model['cur_id'] ?><!--</label>-->
            <!--            <label class="label-width qlabel-align width270">送货地址<label>：</label></label>-->
            <!--            <label class="label-width text-left width200">--><?//= $model['addr_id']?><!--</label>-->
            <!--        </div>-->
            <!--        <div class="mb-10">-->
            <!--            <label class="label-width qlabel-align width100 ml-20">关联单号<label>：</label></label>-->
            <!--            --><?php //foreach ($reqno as $key => $val) { ?>
            <!--                <label class="label-width text-left  req_no"  data-id="--><?//= $val['req_id'] ?><!--">--><?//= $val['req_no'] ?><!--</label>-->
            <!--            --><?php //} ?>
            <!--        </div>-->
            <div class="mb-10">
                <table width="90%" class="no-border vertical-center ml-25">
                    <tr class="no-border">
                        <td width="15%" class="no-border  qlabel-align vertical-center">备注：</td>
                        <td width="74%" class="no-border text-left vertical-center"><?= $model['remarks'] ?></td>
                    </tr>
                </table>
            </div>
            <?php if($model['yn_can']!=0){?>
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
    </div>
    <h2 class="head-second color-1f7ed0">
        <i class="icon-caret-right"></i>
        <a href="javascript:void(0)">商品信息</a>
    </h2>
    <div class="mb-20" style="overflow: auto">
        <div style="width:100%;overflow: auto;">
            <table class="table" style="width: 1400px;">
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
    <div class="mb-20" style="overflow: auto; margin-top: 10px">
        <?php if (!empty($verify)){ ?>
        <div >
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


    <script>
        $(function () {
            $("#submit").on("click", function () {
                window.history.go(-1);
            });
            $(".req_no").on("click",function () {
                var id=$(this).data("id");
                window.location.href = "<?=Url::to(['/purchase/purchase-apply/view'])?>?id=" + $(this).data("id");
            })
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
                var id = "<?=$data['sonh_id'] ?>";
                $.ajax({
                    type:'post',
                    dataType:'json',
//                    data:$("#note-form").serialize(),
                    url:"<?= \yii\helpers\Url::to(['cancel-notify?id='])?>" + id,
                    success:function(data){
                        if(data.status == 1){
                            layer.alert(data.msg,{icon:1,end: function () {
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
            //合并单元格
            for (var index = 16; index >= 0; index--) {
                autoRowSpan("product_table", 0, index);
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
//            $("#pass").on("click", function () {
//                layer.confirm("是否通过?",
//                    {
//                        btn: ['确定', '取消'],
//                        icon: 2
//                    },
//                    function () {
//                        $.ajax({
//                            type: "post",
//                            dataType: "json",
//                            data: $("#check-form").serialize(),
//                            url: "<?//= Url::to(['/system/verify-record/audit-pass']) ?>//",
//                            success: function (msg) {
//                                if (msg.flag == 1) {
//                                    layer.alert(msg.msg, {icon: 1}, function () {
//                                        parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                                    });
//                                } else {
//                                    layer.alert(msg.msg, {icon: 2})
//                                }
//                            },
//                            error: function (data) {
////                            console.log('data: ',data)
//                            }
//                        })
//                    },
//                    function () {
//                        layer.closeAll();
//                    }
//                )
//            });
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
    </script>
