<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/13
 * Time: 上午 11:31
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php ActiveForm::begin([
        "id"=>"check-form"
]) ?>
<style>
    .width-120 {
        width: 120px;
    }

    .content {
        font-size: 16px;
    }

    .width-300 {
        width: 300px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    label:after {
        content: "：";
    }
</style>
<div class="content">
    <div class="mb-10">
        <?= Html::button('通过', ['class' => 'button-blue', 'id' => 'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue', 'id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <h3 class="head-first">出库单审核</h3>
    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出库单号：</td>
            <td class="no-border vertical-center width-300"><?= $model['o_whcode'] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">出库单状态：</td>
            <td class="no-border vertical-center"><?php foreach ($options["o_whstatus"] as $key => $val){
                if (strval($key) == $model['o_whstatus']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">单据类型：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["o_whtype"] as $key => $val){
                if (strval($key) == $model['o_whtype']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">申请部门：</td>
            <td class="no-border vertical-center"><?php foreach ($options["organization"] as $key => $val){
                if (strval($key) == $model["app_depart"]){ ?>
                <?= $val ?></td>
        <?php }
        } ?>
        </tr>
    </table>


    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">申请人：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["staff"] as $key => $val){
                if (strval($key) == $model['creator']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">法人名称：</td>
            <td class="no-border vertical-center"><?= $model["corporate"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">商品性质：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["product_property"] as $key => $val){
                if (strval($key) == $model['pdt_attr']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">预出库日期：</td>
            <td class="no-border vertical-center"><?= date("Y/m/d", strtotime($model["plan_odate"])) ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出货日期：</td>
            <td class="no-border vertical-center width-300"><?= date("Y/m/d", strtotime($model["plan_odate"])) ?></td>
            <td class="no-border vertical-center label-width label-align width-120">关联单号：</td>
            <td class="no-border vertical-center"><?= $model["relate_packno"] ?></td>
        </tr>
    </table>


    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">运输方式：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["trans_type"] as $key => $val){
                if (strval($key) == $model['logistics_type']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">配送方式：</td>
            <td class="no-border vertical-center"><?php foreach ($options["delivery_type"] as $key => $val){
                if (strval($key) == $model['delivery_type']){ ?>
                <?= $val ?></td>
        <?php }
        } ?>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">出仓仓库：</td>
            <td class="no-border vertical-center width-300" id="wh_id"
                data-id="<?= $model['o_whid'] ?>"><?php foreach ($options["warehouse"] as $key => $val){
                if ($val['wh_id'] == $model['o_whid']){ ?>
                <?= $val['wh_name'] ?></td>
            <?php break;
            }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">库管员：</td>
            <td class="no-border vertical-center" id="whs_operator"></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">用途：</td>
            <td class="no-border vertical-center"><?= $model["use_for"] ?></td>
        </tr>
    </table>


    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">收货人：</td>
            <td class="no-border vertical-center width-300"><?= $model["reciver"] ?></td>
            <td class="no-border vertical-center label-width label-align width-120">联系方式：</td>
            <td class="no-border vertical-center"><?= $model["reciver_tel"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">收货地址：</td>
            <td class="no-border vertical-center">
                <?= $model["all_address"] ?>
            </td>
        </tr>
    </table>

    <div class="space-30"></div>


    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">备注：</td>
            <td class="no-border vertical-center"><?= $model["remarks"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <table width="90%" class="no-border vertical-center">
        <tr class="no-border">
            <td class="no-border vertical-center label-width label-align width-120">制单人：</td>
            <td class="no-border vertical-center width-300"><?php foreach ($options["staff"] as $key => $val){
                if (strval($key) == $model['creator']){ ?>
                <?= $val ?></td>
            <?php }
            } ?>
            <td class="no-border vertical-center label-width label-align width-120">制单时间：</td>
            <td class="no-border vertical-center"><?= $model["creat_date"] ?></td>
        </tr>
    </table>

    <div class="space-30"></div>

    <?php if ($model['o_whstatus'] == '4') { ?>
        <table width="90%" class="no-border vertical-center">
            <tr class="no-border">
                <td class="no-border vertical-center label-width label-align width-120">取消原因：</td>
                <td class="no-border vertical-center"><?= $model["can_reason"] ?></td>
            </tr>
        </table>
    <?php } ?>

    <div class="space-30" style="height: 20px;"></div>

    <h3 class="head-second">出库商品信息</h3>
    <table class="table">
        <thead>
        <th width="100">序号</th>
        <th width="200">料号</th>
        <th width="300">品名</th>
        <th width="100">规格型号</th>
        <th width="100">品牌</th>
        <th width="100">库存数量</th>
        <th width="100">需求出库量</th>
        <th width="100">出库数量</th>
        <th width="100">单位</th>
        <th width="100">备注</th>
        </thead>
        <tbody id="data">
        <?php if (count($childs['rows']) > 0) { ?>
            <?php foreach ($childs['rows'] as $k => $child) { ?>
                <tr>
                    <td align="center"><?= $k + 1 ?></td>
                    <td align="center"><?= $child['part_no'] ?></td>
                    <td align="center"><?= $child['pdt_name'] ?></td>
                    <td align="center"><?= $child['tp_spec'] ?></td>
                    <td align="center"><?= $child['brand_name'] ?></td>
                    <td align="center"><?= $child['invt_num'] ?></td>
                    <td align="center"><?= $child['req_num'] ?></td>
                    <td align="center"><?= $child['o_whnum'] ?></td>
                    <td align="center"><?= $child['unit_name'] ?></td>
                    <td align="center"><?= $child['remarks'] ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td align="center" colspan="10">列表为空</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="space-30" style="height: 20px;"></div>
    <?php if (!empty($model["checks"]) && ($model['o_whstatus'] == '7' || $model['o_whstatus'] == '6'|| $model['o_whstatus'] == '4' || $model['o_whstatus'] == '8')) { ?>
        <h3 class="head-second">审核路径</h3>
        <table class="table">
            <thead>
            <th>#</th>
            <th>签核节点</th>
            <th>签核人员</th>
            <th>签核日期</th>
            <th>操作</th>
            <th>签核意见</th>
            <th>签核人IP</th>
            </thead>
            <tbody>
            <?php foreach ($model["checks"] as $k => $check) { ?>
                <tr>
                    <td><?= $k + 1 ?></td>
                    <td><?= $check["ver_acc_rule"] ?></td>
                    <td><?= $check["verifyName"] ?></td>
                    <td><?= $check["vcoc_datetime"] ?></td>
                    <td><?= $check["verifyStatus"] ?></td>
                    <td><?= $check["vcoc_remark"] ?></td>
                    <td><?= $check["vcoc_computeip"] ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
<input type="hidden" name="id" id="id" value="<?= $id ?>">
<?php ActiveForm::end() ?>
<script>
    $(function(){
        $("#wh_id").change();
        ajaxSubmitForm($("#check-form"));
//        $(".pass").click(function(){
//            layer.confirm("确定审核通过吗?",{btns:["确定","取消"],icon:2},function(){
//                $.ajax({
//                    type:"post",
//                    dataType:"json",
//                    data: $("#check-form").serialize(),
//                    url: "<?//= Url::to(['/system/verify-record/audit-pass']) ?>//",
//                    success:function(data){
//                        if(data.flag==1){
//                            layer.alert(data.msg,{icon:1},function(){
//                                parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                            });
//                        }else{
//                            layer.alert(data.msg,{icon:2});
//                        }
//                    }
//                });
//            },function(){
//                layer.closeAll();
//            });
//        });
//
//        $(".back").click(function(){
//            layer.confirm("确定驳回吗?",{btns:["确定","取消"],icon:2},function(){
//                $.ajax({
//                    type:"post",
//                    dataType:"json",
//                    data: $("#check-form").serialize(),
//                    url: "<?//= Url::to(['/system/verify-record/audit-reject']) ?>//",
//                    success:function(data){
//                        if(data.flag==1){
//                            layer.alert(data.msg,{icon:1},function(){
//                                parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                            });
//                        }else{
//                            layer.alert(data.msg,{icon:2});
//                        }
//                    }
//                });
//            },function(){
//                layer.closeAll();
//            });
//        });

        //驳回
        $("#reject").on("click", function () {
            var id = $("#id").val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + id,
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
            var id = $("#id").val();
            $.fancybox({
                href: "<?=Url::to(['pass-opinion'])?>?id=" + id,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });

//        $(".return").click(function(){
//            parent.$.fancybox.close();
//        });
    });
    $("#wh_id").change(function () {
        var wh_id = $(this).data("id");
        if (wh_id.length != 0) {
            $.ajax({
                url: "<?=Url::to(['/warehouse/other-out-stock/get-wh-code'])?>?wh_id=" + wh_id,
                type: "get",
                success: function (data) {
                    $("#whs_operator").text(data);
                }
            });
        }
    });
</script>