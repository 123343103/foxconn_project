<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2018/1/2
 * Time: 下午 01:39
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '生成物流订单';
$this->params['homeLike'] = ['label' => '物流订单查询'];
$this->params['breadcrumbs'][] = ['label' => '物流订单查询', 'url' => Url::to(['logisticsorder/index'])];
$this->params['breadcrumbs'][] = ['label' => '生成物流订单'];
date_default_timezone_set("Asia/Shanghai");
?>
<style>
    .content{
        font-size:12px;
    }
    .span-width {
        width: 300px;
    }

    .label-width {
        width: 110px;
    }
    .width-150{
        width:150px;
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
                <span class="span-width">客户订单</span>
                <input type="hidden" name="OrdLgst[sr_type]" value="">
                <input type="hidden" name="OrdLgst[ord_id]" value="<?= $model['ord_id']?>">
                <input type="hidden" id="o_whpkid" value="<?= $model['o_whpkid']?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align" style="width: 110px; ">预计发货时间：</label>
                <span class="span-width"><?= date('Y-m-d') ?></span>
                <input name="OrdLgst[lgst_date]" type="hidden" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span style="color:red;" title="*">*</span>贸易性质：</label>
                <select class="width-150" name="OrdLgst[trade_act]">
                    <option value="1">保税</option>
                    <option value="0">非保税</option>
                </select>
            </div>
            <div class="inline-block" style="margin-left: 150px;">
                <label class="label-width label-align">运输类型：</label>
                <span class="span-width"><?= $model['tran_sname'] ?></span>
                <input name="OrdLgst[trade_type]" type="hidden" value="<?= $model['logistics_type'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span style="color:red;" title="*">*</span>进出口别：</label>
                <select class="width-150" name="OrdLgst[[ie_type]">
                    <option value="1">进口</option>
                    <option value="0">出口</option>
                </select>
            </div>
            <div class="inline-block" style="margin-left: 150px;">
                <label class="label-width label-align"><span style="color:red;" title="*">*</span>车种：</label>
                <select class="width-150" name="OrdLgst[kd_car]">
                    <?php foreach ($Car as $key=>$val){?>
                        <option value="<?=$val['bsp_id']?>"><?=$val['bsp_svalue']?></option>
                    <?php }?>
                </select>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">含税运费：</label>
                <span class="span-width"><?= sprintf("%.3f",$model['tax_freight']) ?>RMB</span>
                <input name="OrdLgst[lg_fee_tax]" type="hidden" value="<?= sprintf("%.3f",$model['tax_freight']) ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">未税运费：</label>
                <span class="span-width"><?= sprintf("%.3f",$model['freight']) ?>RMB</span>
                <input name="OrdLgst[lg_fee]" type="hidden" value="<?= sprintf("%.3f",$model['freight']) ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span style="color:red;" title="*">*</span>是否无账：</label>
                <input name="OrdLgst[YN_Fee]" type="radio" value="1">是
                <input name="OrdLgst[YN_Fee]" type="radio" style="margin-left: 20px;" value="0" checked >否
            </div>
            <div class="inline-block" style="margin-left: 70px;">
                <label class="label-width label-align"><span style="color:red;" title="*">*</span>富金机配送：</label>
                <input name="OrdLgst[YN_FJJ]" type="radio" value="1">是
                <input name="OrdLgst[YN_FJJ]" type="radio" style="margin-left: 20px;" value="0" checked>否
            </div>
            <div class="inline-block" style="margin-left: 50px;">
                <label class="label-width label-align"><span style="color:red;" title="*">*</span>保价：</label>
                <input name="OrdLgst[YN_ins]" type="radio" value="1" checked>是
                <input name="OrdLgst[YN_ins]" type="radio" style="margin-left: 20px;" value="0">否
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input type="text" style="width: 700px;height: 50px;" name="OrdLgst[marks]" maxlength="200"
                       value=""
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
                <span class="span-width"><?= $model['wh_code'] ?></span>

            </div>
            <div class="inline-block">
                <label class="label-width label-align">仓库名称：</label>
                <span class="span-width"><?= $model['wh_name'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">详细地址：</label>
                <span class="span-width" id="address"><?= $model['bw_all_address'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>联系人：</label>
                <input type="text" name="OrdLgst[shp_cntct]" class="value-width value-align easyui-validatebox"
                       data-options="required:'true'" value="">
            </div>
            <div class="inline-block" style="margin-left: 130px;">
                <label class="label-width label-align"><span class="red">*</span>联系电话：</label>
                <input type="text" class="value-width value-align easyui-validatebox"
                       data-options="required:'true',validType:'tel_mobile_c'" name="OrdLgst[shp_tel]"
                       value="">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input type="text" style="width: 700px;height: 50px;" name="OrdLgst[shp_marks]" maxlength="200"
                       value=""
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
                <span class="span-width"><?= $model['cust_contacts'] ?></span>
                <input type="hidden" value="<?= $model['cust_contacts'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">公司名称：</label>
                <span class="span-width"><?= $model['company_name'] ?></span>
                <input type="hidden" value="<?= $model['company_name'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">详细地址：</label>
                <span class="span-width" id="readress"><?= $model['ow_all_address'] ?></span>
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>联系人：</label>
                <input type="text" name="OrdLgst[rcv_cntct]" class="value-width value-align easyui-validatebox"
                       data-options="required:'true'" value="<?= $model['reciver'] ?>">
            </div>
            <div class="inline-block" style="margin-left: 130px;">
                <label class="label-width label-align"><span class="red">*</span>联系电话：</label>
                <input type="text" name="OrdLgst[rcv_tel]" class="value-width value-align easyui-validatebox"
                       data-options="required:'true',validType:'tel_mobile_c'" value="<?= $model['reciver_tel'] ?>">
            </div>
        </div>
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input type="text" style="width: 700px;height: 50px;" name="OrdLgst[rcv_marks]" maxlength="200"
                       value="<?= $model['rcv_marks'] ?>"
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
            <?php if (!empty($orddata)) { ?>
                <?php foreach ($orddata as $key => $val) { ?>
                    <tr>
                        <td><span><?= $key + 1 ?></span></td>
                        <td>
                            <span><?= $val['ord_no'] ?></span>
                            <input type="hidden" name="OrdLgstDt[<?=$key?>][ord_dt_id]" value="<?=$val['ord_dt_id']?>">
                            <input type="hidden" name="OrdLgstDt[<?=$key?>][o_whdtid]" value="<?=$val['o_whdtid']?>">
                            <input type="hidden" name="OrdLgstDt[<?=$key?>][o_whnum]" value="<?=$val['o_whnum']?>">
                        </td>
                        <td style="overflow: hidden;"><span><?= $val['part_no'] ?></span></td>
                        <td style="overflow: hidden;"><span><?= $val['tp_spec'] ?></span></td>
                        <td style="overflow: hidden;"><span><?= $val['pdt_name'] ?></span></td>
                        <td style="overflow: hidden;"><span><?= sprintf("%.2f", $val['sapl_quantity'])  ?></span></td>
                        <td style="overflow: hidden;"><span><?= $val['unit'] ?></span></td>
                        <td style="overflow: hidden;"><span><?= $val['pdt_origin'] ?></span></td>
                        <td style="overflow: hidden;"<span><?= $val['pck_type'] ?></span></td>
                        <td style="overflow: hidden;"><span><?= $val['pdt_qty'] ?></span></td>
                        <td style="overflow: hidden;"><span><?= sprintf("%.2f",$val['plate_num']) ?></span></td>
                        <td style="overflow: hidden;"><span><?= sprintf("%.6f",$val['suttle']) ?></span></td>
                        <td style="overflow: hidden;"><span><?= sprintf("%.6f",$val['gross_weight']) ?></span></td>
                        <td style="overflow: hidden;"><span><?= sprintf("%.2f",$val['pdt_length']) ?>/<?= sprintf("%.2f",$val['pdt_width']) ?>/<?= sprintf("%.2f",$val['pdt_height']) ?></span>
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
        var btnFlag="";
        $(".save-form").click(function () {
            $("form").submit();
            btnFlag = $(this).text().trim();
            $("#add-form").attr('action', '<?=Url::to(['generating-logistics'])?>?id='+$("#o_whpkid").val());
        });
        $(".apply-form").click(function () {
            $("form").submit();
            btnFlag = $(this).text().trim();
            $("#add-form").attr('action', '<?=Url::to(['generating-logistics'])?>?id='+$("#o_whpkid").val());
        });

        ajaxSubmitForm($("#add-form"), '', function (data) {
            if (data.flag == 1) {
                if (btnFlag == '提交') {
                    var id = data.billId;//单据ID
                    var wid=data.id;//出库单id
                    var url = "<?=Url::to(['view'], true)?>?id=" + wid;
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
                            location.href = "<?=Url::to(['view'])?>?id=" + wid;
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

