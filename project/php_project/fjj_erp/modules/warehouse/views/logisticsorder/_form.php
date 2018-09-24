<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/8
 * Time: 上午 11:27
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);
?>
<style>
    .span-width {
        width: 300px;
    }

    .label-width {
        width: 100px;
    }

    .div-margin {
        margin-left: 50px;
    }
</style>
<?php $form = ActiveForm::begin(['id' => 'add-form']) ?>
<div class="content">
    <h2 class="head-first">
        <?= $this->title; ?>
    </h2>
    <h2 class="head-second color-1f7ed0">基本信息</h2>
    <div class="mb-10">
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">来源单据类型：</label>
                <span class="span-width"><?= $model[0]['sr_type'] ?></span>
                <input type="hidden"  id="srtype" value="<?= $model[0]['sr_type'] ?>">
                <input type="hidden"  id="lgid" value="<?= $model[0]['ord_lg_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">运输类型：</label>
                <span class="span-width"><?= $model[0]['trade_type'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">预计出货时间：</label>
                <span class="span-width"><?= $model[0]['lgst_date'] ?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">运输模式：</label>
                <span class="span-width"><?= $model[0]['TRANSMODE'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">富金机配送：</label>
                <span class="span-width"><?= $model[0]['YN_FJJ'] ?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">贸易性质：</label>
                <span class="span-width"><?= $model[0]['trade_act'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">是否无账：</label>
                <span class="span-width"><?= $model[0]['YN_Fee'] ?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">报价：</label>
                <span class="span-width"><?= $model[0][' '] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">进出口类别：</label>
                <span class="span-width"><?= $model[0]['ie_type'] ?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">车种：</label>
                <span class="span-width"><?= $model[0]['kd_car'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">费用代码：</label>
                <span class="span-width"><?= $model[0]['cost_no'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input type="text" style="width: 700px;height: 50px;" name="OrdLgst[marks]" maxlength="200"
                       value="<?= $model[0]['marks'] ?>"
                       placeholder="最多输入200个字"
                       onfocus="onfocustishi(this.placeholder,'最多输入200个字',this.id)"
                       onblur="blurtishi(this.value,'最多输入200个字',this.id)">

            </div>
        </div>
    </div>
    <h2 class="head-second color-1f7ed0">出货人信息</h2>
    <div class="mb-10">
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">仓库代码：</label>
                <span class="span-width"><?= $model[0]['wh_code'] ?></span>

            </div>
            <div class="inline-block">
                <label class="label-width label-align">仓库名称：</label>
                <span class="span-width"><?= $model[0]['wh_name'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">详细地址：</label>
                <span class="span-width" id="address"><?= $model[0]['wh_addr'] ?></span>
                <input type="hidden" id="addrid" value="<?= $model[0]['district_id'] ?>">
                <input type="hidden" id="whaddr" value="<?= $model[0]['wh_addr'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>联系人：</label>
                <input type="text" name="OrdLgst[shp_cntct]" class="value-width value-align easyui-validatebox"
                       data-options="required:'true'" value="<?= $model[0]['shp_cntct'] ?>">
            </div>
            <div class="inline-block" style="margin-left: 130px;">
                <label class="label-width label-align"><span class="red">*</span>联系电话：</label>
                <input type="text" class="value-width value-align easyui-validatebox"
                       data-options="required:'true',validType:'tel_mobile_c'" name="OrdLgst[shp_tel]"
                       value="<?= $model[0]['shp_tel'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input type="text" style="width: 700px;height: 50px;" name="OrdLgst[shp_marks]" maxlength="200"
                       value="<?= $model[0]['shp_marks'] ?>"
                       placeholder="最多输入200个字"
                       onfocus="onfocustishi(this.placeholder,'最多输入200个字',this.id)"
                       onblur="blurtishi(this.value,'最多输入200个字',this.id)">

            </div>
        </div>
    </div>
    <h2 class="head-second color-1f7ed0">收货人信息</h2>
    <div class="mb-10">
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">客户代码：</label>
                <span class="span-width"><?= $model[0]['cust_code'] ?></span>
                <input type="hidden" value="<?= $model[0]['cust_code'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">公司名称：</label>
                <span class="span-width"><?= $model[0]['cust_sname'] ?></span>
                <input type="hidden" value="<?= $model[0]['cust_sname'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">详细地址：</label>
                <span class="span-width" id="readress"><?= $model[0]['cust_readress'] ?></span>
                <input type="hidden" id="custaddr"  value="<?= $model[0]['cust_readress'] ?>">
                <input type="hidden" id="custaddrid" value="<?=$model[0]['cust_district_1']?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>联系人：</label>
                <input type="text" name="OrdLgst[rcv_cntct]" class="value-width value-align easyui-validatebox"
                       data-options="required:'true'" value="<?= $model[0]['rcv_cntct'] ?>">
            </div>
            <div class="inline-block" style="margin-left: 130px;">
                <label class="label-width label-align"><span class="red">*</span>联系电话：</label>
                <input type="text" name="OrdLgst[rcv_tel]" class="value-width value-align easyui-validatebox"
                       data-options="required:'true',validType:'tel_mobile_c'" value="<?= $model[0]['rcv_tel'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input type="text" style="width: 700px;height: 50px;" name="OrdLgst[rcv_marks]" maxlength="200"
                       value="<?= $model[0]['rcv_marks'] ?>"
                       placeholder="最多输入200个字"
                       onfocus="onfocustishi(this.placeholder,'最多输入200个字',this.id)"
                       onblur="blurtishi(this.value,'最多输入200个字',this.id)">

            </div>
        </div>
    </div>

    <h2 class="head-second color-1f7ed0">单身列表</h2>
    <div class="mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <table class="table" style="width:1500px;">
            <thead>
            <tr>
                <th width="50">项次</th>
                <th width="150">来源订单号</th>
                <th width="200">商品料号</th>
                <th width="100">商品规格</th>
                <th width="200">商品名称</th>
                <th width="100">数量</th>
                <th width="80">单位</th>
                <th width="150">原产地</th>
                <th width="150">包装方式</th>
                <th width="100">包装件数</th>
                <th width="100">板数</th>
                <th width="100">净重/KG</th>
                <th width="100">毛重/KG</th>
                <th width="200">长宽高/CBM</th>
            </tr>
            </thead>
            <tbody id="orderinfo">
            <?php if (!empty($model)) { ?>
                <?php foreach ($model as $key => $val) { ?>
                    <tr>
                        <td><span><?= $key + 1 ?></span></td>
                        <td><span><?= $val['ord_no'] ?></span></td>
                        <td><span><?= $val['part_no'] ?></span></td>
                        <td><span><?= $val['tp_spec'] ?></span></td>
                        <td><span><?= $val['pdt_name'] ?></span></td>
                        <td><span><?= sprintf("%.2f", $val['sapl_quantity'])  ?></span></td>
                        <td><span><?= $val['unit'] ?></span></td>
                        <td><span><?= $val['pdt_origin'] ?></span></td>
                        <td><span><?= $val['pck_type'] ?></span></td>
                        <td><span><?= $val['pdt_qty'] ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['plate_num']) ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['suttle']) ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['pdt_weight']) ?></span></td>
                        <td><span><?= sprintf("%.2f",$val['pdt_length']) ?>/<?= sprintf("%.2f",$val['pdt_width']) ?>/<?= sprintf("%.2f",$val['pdt_height']) ?></span>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mb-10">
        <div class="text-center" style="margin-top: 30px;">
            <button type="button" class="button-blue-big save-form">保存</button>
            <button type="button" style="margin-left: 40px;" class="button-blue-big apply-form">提交
                <button>
                    <button class="button-white-big" onclick="window.history.go(-1)" type="button">取消</button>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        var addrid=$("#addrid").val();
        var whaddr= $("#whaddr").val();
        var custaddr=$("#custaddr").val();
        var custaddrid=$("#custaddrid").val();
        // 获取仓库的详细地址
        $.ajax({
            url: "<?=Url::to(['address']);?>",
            data: {"id": addrid},
            dataType: "json",
            type: "get",
            async: false,
            success: function (data) {
                $("#address")[0].innerHTML=data+whaddr;
                //console.log(data);
            }
        });
        //获取客户的详细地址
        $.ajax({
            url: "<?=Url::to(['address']);?>",
            data: {"id": custaddrid},
            dataType: "json",
            type: "get",
            async: false,
            success: function (data) {
                $("#readress")[0].innerHTML=data+custaddr;
                //console.log(data);
            }
        });
        var btnFlag="";
        $(".save-form").click(function () {
            $("form").submit();
            var id=$("#lgid").val();
            btnFlag = $(this).text().trim();
            $("#add-form").attr('action', '<?=Url::to(['update'])?>?id=' + id);
        });
        $(".apply-form").click(function () {
            $("form").submit();
            var id=$("#lgid").val();
            btnFlag = $(this).text().trim();
            $("#add-form").attr('action', '<?=Url::to(['update'])?>?id=' + id);
        });

        ajaxSubmitForm($("#add-form"), '', function (data) {
            if (data.flag == 1) {
                if (btnFlag == '提交') {
                    var id = data.billId;
                    var url = "<?=Url::to(['view'], true)?>?id=" + id;
                    var type = data.billTypeId;
                   // alert(id+','+url+','+type);
                    $.fancybox({
                        href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                        type: "iframe",
                        padding: 0,
                        autoSize: false,
                        width: 750,
                        height: 480,
                        afterClose: function () {
                            location.href = "<?=Url::to(['view'])?>?id=" + id;
                        }
                    });
                } else {
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            if (data.url != undefined) {
                                parent.location.href = data.url;
                            }
                        }
                    });
                }
            }
            if (data.flag == 0) {
                if ((typeof data.msg) == 'object') {
                    layer.alert(JSON.stringify(data.msg), {icon: 2});
                } else {
                    layer.alert(data.msg, {icon: 2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });
    });
</script>

