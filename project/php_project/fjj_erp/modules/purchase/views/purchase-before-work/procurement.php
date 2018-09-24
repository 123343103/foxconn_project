<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 09:15
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '采购信息确认';
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '采购前置作业', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '采购信息确认'];
?>
<style>
    .alignstyle {
        border: none;
        padding-top: 0px;
    }

    .label-width {
        width: 100px;
    }

    .span-width {
        width: 300px;
    }

    .red-border {
        border: 1px solid #ffa8a8;
    }

    .prompt-box {
        position: relative;
        width: 125px;
        height: 25px;
        border: 1px solid #d2abab;
        background-color: rgba(255, 253, 227, 0.85);
        z-index: 99;
        top: -20px;
        left: 50px;
    }

    .select-width {
        width: 120px;
    }

    .delivc {
        width: 120px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-second color-1f7ed0 ">采购单信息</h2>
    <div class="mb-10">
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 30px;">
                <label class="label-width label-align">单据类型：</label>
                <span class="span-width"><?= $reqdct['bsp_svalue'] ?></span>
                <input type="hidden" name="BsPrch[req_dct]" value="<?= $reqdct['bsp_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">法人：</label>
                <span class="span-width"><?= $legid['company_name'] ?></span>
                <input type="hidden" name="BsPrch[leg_id]" value="<?= $legid['company_id'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 30px;">
                <label class="label-width label-align">采购区域：</label>
                <span class="span-width"><?= $model[0]['factory_name'] ?></span>
                <input type="hidden" name="BsPrch[area_id]" value="<?= $model[0]['area_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">采购部门：</label>
                <span class="span-width"><?= $buyerinfo[0]['organization_name'] ?></span>
                <input type="hidden" name="BsPrch[dpt_id]" value="<?= $buyerinfo[0]['organizationID'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 30px;">
                <label class="label-width label-align">采购员：</label>
                <span class="span-width"><?= $buyerinfo[0]['staff_code'] ?>
                    --<?= $buyerinfo[0]['staff_name'] ?></span>
                <input type="hidden" name="BsPrch[apper]" value="<?= $buyerinfo[0]['staff_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>联系方式：</label>
                <input type="text" class="value-width value-align easyui-validatebox" id="contact"
                       name="BsPrch[contact_info]"
                       data-options="required:'true',validType:'tel_mobile_c'"
                       value="<?= $buyerinfo[0]['staff_mobile'] ?>"
                       placeholder="请输入手机或座机号" onfocus="onfocustishi(this.placeholder,'请输入手机或座机号',this.id)"
                       onblur="blurtishi(this.value,'请输入手机或座机号',this.id)">
            </div>
        </div>
        <div class="mb-10" style="margin-left: 30px;">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>收货中心：</label>
                <input type="text" class="value-width value-align easyui-validatebox address"
                       data-options="required:'true'" id="addr" maxlength="60"
                       value="<?= $model[0]['rcp_name'] ?>" readonly="true">
                <input type="hidden" id="whid" name="BsPrch[rcp_id]" value="<?= $model[0]['rcp_id'] ?>">
                <span class="icon-search searchaddress"></span>
            </div>
            <div class="inline-block" style="margin-left: 115px;">
                <label class="label-width label-align">采购日期：</label>
                <span class="span-width"><?= date('Y/m/d', time()) ?></span>
                <input type="hidden" name="BsPrch[app_date]" value="<?= date('Y/m/d', time()) ?>">
            </div>
        </div>
        <div class="mb-10" style="margin-left: 30px;">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <textarea rows="3" name="BsPrch[remarks]" style="width:580px;height: 50px;" id="remark"
                          placeholder="最多输入100个字"
                          maxlength="100"></textarea>
            </div>
        </div>
    </div>
    <h2 class="head-second color-1f7ed0">商品信息</h2>
    <div class=" mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <input type="hidden" id="buynumprice" value="">
        <input type="hidden" id="MorethanNum" value="">
        <input type="hidden" id="prchnum" value="">
        <table class="table" style="width: 2500px;">
            <thead>
            <tr>
                <th width="50">序号</th>
                <th width="130">料号</th>
                <th width="130">品名</th>
                <th width="100">规格</th>
                <th width="100">品牌</th>
                <th width="50">单位</th>
                <th width="180"><span class="red">*</span>供应商代码</th>
                <th width="300">供应商名称</th>
                <th width="150"><span class="red">*</span>付款条件</th>
                <th width="150"><span class="red">*</span>交货条件</th>
                <th width="100">单价</th>
                <th width="80">采购数量</th>
                <th width="80">金额</th>
                <th width="80"><span class="red">*</span>税别/税率</th>
                <th width="80">币别</th>
                <th width="150"><span class="red">*</span>交货日期</th>
                <th width="150">关联单号</th>
            </tr>
            </thead>
            <tbody id="partspp">
            </tbody>
        </table>
    </div>
    <div class="text-center" style="margin-top: 30px;">
        <button type="button" class="button-blue-big save-form">保存</button>
        <button type="button" style="margin-left: 40px;" class="button-blue-big apply-form">提交
            <button>
                <button class="button-white-big" onclick="window.history.go(-1)" type="button">取消</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    function onfocustishi(val, title, id) {
        if (val == title) {
            $("#" + id).attr("placeholder", "");
        }
    }
    function blurtishi(val, title, id) {
        if (val == "") {
            $("#" + id).attr("placeholder", title);
        }
    }
    var _this = null;
    var _this1 = null;
    var _this2 = null;
    function loadtable() {
        <?php if (!empty($model)) { ?>
        <?php foreach ($model as $key => $val) { ?>
        var partno = "<?=$val['part_no']?>";
        $.ajax({
            url: "<?=Url::to(['spp-partno']);?>",
            data: {"id": partno},
            dataType: "json",
            type: "get",
            async: false,
            success: function (data) {
                //console.log(data);
                if (data != null) {
                    //一个料号对应多个供应商
                    if (data.length > 1) {
                        $("#partspp").append(
                            '<tr class="proinfo">' +
                            '<td class="num">' +
                            '<span style="width: 50px;"><?= $key + 1 ?></span>' +
                            '</td>' +
                            '<td class="partno">' +
                            '<input type="hidden" class="dtid" value="<?=$val['req_dt_id']?>">' +
                            '<span><?= $val['part_no'] ?></span>' +
                            '<input type="hidden" class="partid" value="<?=$val['part_no']?>">' +
                            '<input type="hidden" class="part"  value="<?=$val['part_no']?>">' +
                            '</td>' +
                            '<td class="pdtname">' +
                            '<span><?= $val['pdt_name']?></span>' +
                            '</td>' +
                            '<td class="tpspec">' +
                            '<span><?= $val['tp_spec'] ?></span>' +
                            '</td>' +
                            '<td class="brandname">' +
                            '<span><?= $val['brand'] ?></span>' +
                            '</td>' +
                            '<td class="unit">' +
                            '<span><?= $val['unit'] ?></span>' +
                            '</td>' +
                            '<td class="sppcode">' +
                            '<input class="key" type="hidden" value="<?=$key + 1?>">' +
                            '<input type="hidden" class="dtid" value="<?=$val['req_dt_id']?>">' +
                            '<input type="text" readonly="readonly" unselectable="on" class="easyui-validatebox codes" data-options="required:\'true\'">' +
                            '<span class="icon-search searchspp"></span>' +
                            '</td>' +
                            '<td class="suppname"></td>' +
                            '<td class="pay">' +
                            '<div class="pdiv"><span></span></div>' +
                            '</td>' +
                            '<td class="delivery">' +
                            '<select class="select-width goods goodscon" name="BsPrchDt[<?= $key ?>][goods_condition]">' +
                            '<option value="">--请选择--</option>' +
                            '</select>' +
                            '<input type="hidden" class="prtsppid" value="' + data[0]['prt_spp_pkid'] + '">' +
                            '</td>' +
                            '<td class="reqprice">' +
                            '<span class="prices"></span>' +
                            '</td>' +
                            '<td class="reqnum">' +
                            '<span class="nums"><?=sprintf("%.2f", $val['req_nums'])?></span>' +
                            '<input type="hidden" class="number" value="<?=sprintf("%.2f", $val['req_nums'])?>">' +
                            '</td>' +
                            '<td class="totalamount">' +
                            '<span class="total"></span>' +
                            '</td>' +
                            '<td class="taxs" style="width:130px;">' +
                            '<input type="hidden" class="taxid">' +
                            '<input class="key" type="hidden" value="<?=$key + 1?>">' +
                            '<input type="text" class="easyui-validatebox tax" style="width:100px; text-align:center;" data-options="required:\'true\'" readonly="readonly">' +
                            '<span class="icon-search searchtax"></span>' +
                            '</td>' +
                            '<td class="currency">' +
//                            '<select class="label-width curr" name="">' +
//                            <?php //foreach ($currency as $n => $a) { ?>
//                            '<option value="<?//= $a['cur_id'] ?>//"><?//= $a['cur_code'] ?>//</option>' +
//                            <?php //} ?>
//                            '</select>' +
                            '</td>' +
                            '<td class="delivdate">' +
                            '<input type="hidden" id="start" value="<?= date('Y-m-d', time()) ?>">' +
                            '<input class=" no-border easyui-validatebox deldate text-center Wdate" data-options="required:\'true\'" type="text" id="Deliverytime" style="width: 150px;" onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'%y-%M-#{%d+1}\' })" onfocus="this.blur()"  value="" readonly="readonly">' +
                            '</td>' +
                            '<td class="reqnos">' +
                            '<input type="hidden" class="reqno" value="<?=$val['req_no']?>">' +
                            <!-- 用explode方法先将用分号分隔的数据转换为数组-->
                            <?php foreach ($reqnos = explode(";", $val['req_no']) as $ls=>$no) { ?>
                            <?php if (count($reqnos) > 1){?>
                            <!--然后将用逗号分隔的数据转换为数组，获取索引为0的单号，获取索引为1的相关id-->
                            '<a id="reqno" href="<?=\yii\helpers\Url::to(['/purchase/purchase-apply/view'])?>?id=<?=explode(",", $reqnos[$ls])[1]?>"><?=explode(",", $reqnos[$ls])[0]?></a><span>&nbsp;&nbsp;&nbsp;&nbsp;<span>' +
                            <?php }?>
                            <?php if (count($reqnos) == 1){?>
                            <!--然后将用逗号分隔的数据转换为数组，获取索引为0的单号，获取索引为1的相关id-->
                            '<a id="reqno" href="<?=\yii\helpers\Url::to(['/purchase/purchase-apply/view'])?>?id=<?=$val['req_id']?>"><?=$val['req_no']?></a>' +
                            <?php }?>
                            <?php }?>
                            '</td>' +
                            '</tr>'
                        );
                        $.parser.parse($("#partspp"));
                    }
                    else if (data.length == 1) {
                        $("#partspp").append(
                            '<tr class="proinfo">' +
                            '<td class="num">' +
                            '<input type="hidden" class="invalid" value="0">' +
                            '<span style="width: 50px;"><?= $key + 1 ?></span>' +
                            '</td>' +
                            '<td class="partno">' +
                            '<input type="hidden" name="BsPrchDt[<?= $key ?>][dt_id]" class="dtid" value="<?=$val['req_dt_id']?>">' +
                            '<span><?= $val['part_no'] ?></span>' +
                            '<input type="hidden" class="partid" name="BsPrchDt[<?= $key ?>][part_no]" value="<?=$val['part_no']?>">' +
                            '</td>' +
                            '<td class="pdtname">' +
                            '<span><?= $val['pdt_name'] ?></span>' +
                            '</td>' +
                            '<td class="tpspec">' +
                            '<span><?= $val['tp_spec'] ?></span>' +
                            '</td>' +
                            '<td class="brandname">' +
                            '<span><?= $val['brand'] ?></span>' +
                            '</td>' +
                            '<td class="unit">' +
                            '<span><?= $val['unit'] ?></span>' +
                            '</td>' +
                            '<td class="sppcode">' +
                            '<span>' + data[0]['group_code'] + '</span>' +
                            '<input class="key" type="hidden" value="<?=$key?>">' +
                            '<input class="spp<?=$key?>" type="hidden" value="' + data[0]['group_code'] + '">' +
                            '<input type="hidden"  name="BsPrchDt[<?= $key ?>][spp_id]" value="' + data[0]['spp_id'] + '">' +
                            '</td>' +
                            '<td class="suppname">' +
                            '<span>' + data[0]['spp_fname'] + '</span>' +
                            '</td>' +
                            '<td class="pay">' +
                            '<span>' + data[0]['payment_terms'] + '</span>' +
                            '<div class="paydiv<?=$key?>">' +
                            '<input type="hidden" class="payment" value="' + data[0]['payment_terms'] + '">' +
                            '</div>' +
                            '</td>' +
                            '<td class="delivery deli">' +
                            '<div class="delivdiv">' +
                            '<select class="delivc delivselect<?=$key?> goods select-width" name="BsPrchDt[<?= $key ?>][goods_condition]">' +
                            '<option value="">--请选择--</option>' +
                            '</select>' +
                            '<input type="hidden" class="tagn" value="<?= $key ?>">' +
                            '<input type="hidden" class="payment" name="BsPrchDt[<?= $key ?>][pay_condition]" value="' + data[0]['payment_terms'] + '">' +
                            '<input type="hidden" class="sppco" value="' + data[0]['group_code'] + '">' +
                            '</div>' +
                            '</td>' +
                            '<td class="reqprice">' +
                            '<span class="prchprice pric<?= $key ?>"></span>' +
                            '<input type="hidden" class="pricetax pri<?=$key?>" name="BsPrchDt[<?= $key ?>][price]" value="">' +
                            '</td>' +
                            '<td class="reqnum">' +
                            '<input type="hidden" class="number" value="<?=sprintf("%.2f", $val['req_nums'])?>">' +
                            '<div class="numdiv">' +
                            '<input name="BsPrchDt[<?= $key ?>][prch_num]" class="numinput numins num<?=$key?>" ' +
                            'style="width: 80px; margin-bottom: 1px;margin-top: 2px; height: 24px; text-align: center;" type="text" ' +
                            'id="renum" value="<?= sprintf("%.2f", $val['req_nums']) ?>" >'+
                            '<span id="numInfo" style="display: none;" class="numtInfo">采购数量必须大于0</span>' +
                            '<span id="numlen" style="display: none;" class="numlen">采购数量超过或不足限制数量<span class="len"></span></span>' +
                            '<input type="hidden" class="numn" value="<?=$key?>">'+
//                            '<input type="hidden" class="number num<?//=$key?>//" name="BsPrchDt[<?//= $key ?>//][prch_num]" value="<?//=sprintf("%.2f", $val['req_nums'])?>//">' +
                            '</div>' +
                            '</td>' +
                            '<td class="totalamount">' +
                            '<input type="hidden" class="amount pricess<?=$key?>" name="BsPrchDt[<?= $key ?>][total_amount]" value="">' +
                            '<span class="total amount<?=$key?>">0</span>' +
                            '</td>' +
                            '<td class="taxs" style="width:130px;">' +
                            '<input type="hidden" name="BsPrchDt[<?=$key?>][tax]" class="taxid">' +
                            '<input class="key" type="hidden" value="<?=$key + 1?>">' +
                            '<input type="text" class="easyui-validatebox  tax" style="width:100px;text-align:center;" data-options="required:\'true\'" readonly="readonly">' +
                            '<span id="receiptInfo" style="display: none;" class="receiptInfo">该输入框为必填项</span>' +
                            '<span class="icon-search searchtax"></span>' +
                            '</td>' +
                            '<td class="currency">' +
                            '<div class="currdiv">' +
                            '<span class="cur<?=$key?>"></span>' +
                            '<input type="hidden" class="currency<?=$key?>" value=""  name="BsPrchDt[<?=$key?>][cur_id]"></div>' +
//                            '<select class="label-width" name="BsPrchDt[<?//=$key?>//][cur_id]"">' +
//                            <?php //foreach ($currency as $n => $a) { ?>
//                            '<option value="<?//= $a['cur_id'] ?>//"><?//= $a['cur_code'] ?>//</option>' +
//                            <?php //} ?>
//                            '</select>' +
                            '</td>' +
                            '<td class="delivdate">' +
                            '<input type="hidden" id="start" value="<?= date('Y-m-d', time()) ?>">' +
                            '<input class=" no-border easyui-validatebox deldate text-center Wdate" data-options="required:\'true\'" type="text" id="Deliverytime" style="width: 150px;" onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'%y-%M-#{%d+1}\' })" onfocus="this.blur()" name="BsPrchDt[<?= $key ?>][deliv_date]" value="" readonly="readonly">' +
                            '</td>' +
                            '<td>' +
                            '<input type="hidden" class="reqno" value="<?=$val['req_no']?>">' +
                            <?php foreach ($reqnos = explode(";", $val['req_no']) as $ls=>$no) { ?>
                            <?php if (count($reqnos) > 1){?>
                            <!--然后将用逗号分隔的数据转换为数组，获取索引为0的单号，获取索引为1的相关id-->
                            '<a id="reqno" href="<?=\yii\helpers\Url::to(['/purchase/purchase-apply/view'])?>?id=<?=explode(",", $reqnos[$ls])[1]?>"><?=explode(",", $reqnos[$ls])[0]?></a><span>&nbsp;&nbsp;&nbsp;&nbsp;<span>' +
                            <?php }?>
                            <?php if (count($reqnos) == 1){?>
                            <!--然后将用逗号分隔的数据转换为数组，获取索引为0的单号，获取索引为1的相关id-->
                            '<a id="reqno" href="<?=\yii\helpers\Url::to(['/purchase/purchase-apply/view'])?>?id=<?=$val['req_id']?>"><?=$val['req_no']?></a>' +
                            <?php }?>
                            <?php }?>
                            '</td>' +
                            '</tr>'
                        );
                        $.parser.parse($("#partspp"));
                    }
                    else {
                        $("#partspp").append(
                            '<tr class="proinfo">' +
                            '<td colspan="17" style="text-align: left;color:red;padding-left: 500px;">此料号的核价信息已失效' +
                            '<input type="hidden" class="invalid" value="1">' +
                            '</td>' +
                            '</tr>');
                    }
                }
            }
        });
        <?php } ?>
        <?php } ?>
    }
    var m = null;
    var n = null;
    function selectSupplierCallback(rows) {
        var number = null;
        var _tr = _this.parent().parent();//当前元素所在行的tr
        var _tdnum = _tr.find('.reqnum');//当前行所在的采购数量单元格
        var _tdprice = _tr.find('.reqprice');//当前行所在的采购价格单元格
        var _tdsppcode = _tr.find('.sppcode');//当前行所在的供应商代码单元格
        var _tdsuppname = _tr.find('.suppname');//当前行所在的供应商名称单元格
        var _tdpartno = _tr.find(".partno");//当前行所在的料号单元格
        var _tddelivdate = _tr.find(".delivdate");//当前行所在的交货时间单元格
        var _tdtotalamount = _tr.find(".totalamount");//当前行所在的金额单元格
        var _tdtax = _tr.find(".taxs");//当前行所在的税别单元格
        var _tdcurrency = _tr.find(".currency");//当前行所在的币别单元格
        var _tdreqnos = _tr.find(".reqnos");//当前行所在的关联请购单号
        var _tddelivery = _tr.find(".delivery");//当前行所在的交货条件
        var _tdpay = _tr.find(".pay");//当前行所在的付款条件

        var numslen = _tdnum.find('.nums').length;//采购数量的原始数据
        number = _tdnum.find(".number").val();//采购数量
        var key = _tdsppcode.find(".key").val();//第几行
        var partid = _tdpartno.find(".partid").val();//料号ID
        var dtid = _tdpartno.find(".dtid").val();//请购详情id
        var partno = _tdpartno.find(".part").val();//料号
        var delivdate = _tddelivdate.find(".deldate").val();//交货时间
        var pricelen = _tdprice.find('.prices').length;//采购价格的原始数据
        var codeslen = _tdsppcode.find('.codes').length;//供应商代码的原始数据
        var totalamount = _tdtotalamount.find(".amount");//金额
        var reqnos = _tdreqnos.find(".reqno");//请购单号
        if (numslen == 0 && pricelen == 0 && codeslen == 0)//当这些原始数据不存在说明不是第一次选择供应商代码将清除上一次选择的数据
        {
            _tdnum.children('.numdiv').remove();
            _tdnum.children('.numinput').remove();
            _tdnum.children('.hiddendiv').remove();
            _tdprice.children('.priceinput').remove();
            _tdsppcode.children('.codeinput').remove();
            _tdsuppname.children('.suppinput').remove();
            _tddelivdate.children('.dateinput').remove();
            _tdtotalamount.children('.amountinput').remove();
            _tdtax.children('.rateinput').remove();
            _tdtax.children('.taxdiv').remove();
            _tdcurrency.children('.currdiv').remove();
            _tdcurrency.children('.currselect').remove();
            _tddelivery.children('.delivdiv').remove();
            _tdpay.children('.pdiv').remove();
            _tddelivery.children('.delivselecthr').remove();
            _tdpay.children('.payselecthr').remove();
        }
        else {
            _tdnum.find('.nums').remove();
            _tdprice.find('.prices').remove();
            _tdsppcode.find('.codes').remove();
            _tdsppcode.find('.searchspp').remove();
            _tddelivdate.find('.deldate').remove();
            _tdtotalamount.find('.total').remove();
            _tdtax.find('.tax').remove();
            _tdtax.find('.searchtax').remove();
//            _tdcurrency.find('.curr').remove();
            _tddelivery.find('.goods').remove();
            _tdpay.find('.pdiv').remove();
        }
        for (var i = 0; i < rows.length; i++) {
            var buynum = parseInt(number / rows.length);
            var total = parseInt(rows[i].price) * buynum;
            if (m == null) {
                n = parseInt(key) + parseInt(i) + 30;
                m = n;
            }
            n = m + 1;//n用于name的下标
            m = n;//m便于判断与记录n值的变化
            var prt_spp_pkid = "";
            if (i != rows.length - 1) {
                prt_spp_pkid = rows[i].prt_spp_pkid;
                //供应商代码
                _tdsppcode.append('<input class="codeinput  easyui-validatebox spp' + n + '" readonly="readonly" ' +
                    'data-options="required:true" style="width: 170px; margin-bottom: 1px;margin-top: 2px; height: 24px; text-align: center;"' +
                    ' type="text" id="renum" value="' + rows[i].group_code + '">' +
                    '<input type="hidden"  class="codeinput" name="BsPrchDt[' + n + '][spp_id]" value="' + rows[i].spp_id + '">' +
                    '<span class="icon-search searchspp codeinput"></span>' +
                    '<hr class="codeinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //供应商名称
                _tdsuppname.append('<span class="suppinput" style="margin-bottom: 1px;margin-top: 2px;height: 24px;">' + rows[i].spp_fname + '</span>' +
                    '<hr class="suppinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //付款条件
                _tdpay.append('<div class="paydiv' + n + ' pdiv">' + //pdiv用于非第一次选择供应商时删除旧的数据添加新的数据
                    '<span style="margin-bottom: 1px;margin-top: 2px;height: 24px;line-height:24px">' + rows[i].payment_terms + '</span>' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][pay_condition]" class="payment"' +
                    ' value="' + rows[i].payment_terms + '">' +
                    '<input type="hidden" class="tagn" value="' + n + '">' +
                    '</div>' +
                    '<hr class="payselecthr" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //交货条件
                _tddelivery.append('<div class="delivdiv">' + //div用于将多个供应商的交货条件分开
                    '<select class="delivselect' + n + ' goods select-width" ' +
                    'style="margin-bottom: 1px;margin-top: 2px;height: 24px;text-align:center;"' +
                    ' name="BsPrchDt[' + n + '][goods_condition]">' +
                    '<option value="">--请选择--</option>' +
                    '</select>' +
                    '<input type="hidden" class="tagn" value="' + n + '">' +
                    '</div>' +
                    '<hr class="delivselecthr" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //单价
                _tdprice.append('<span  class="priceinput pric' + n + '" style="margin-bottom: 1px;margin-top: 2px;' +
                    'height: 24px;line-height:24px">&nbsp;</span>' +
                    '<input type="hidden" class=" pri' + n + ' priceinput" name="BsPrchDt[' + n + '][price]" value="">' +
                    '<hr class="priceinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //数量
                _tdnum.append('<div class="numdiv">' + //div用于将多个供应商的采购数量区分开
                    '<input name="BsPrchDt[' + n + '][prch_num]" class="numinput numins num' + n + '" ' +
                    'style="width: 80px; margin-bottom: 1px;margin-top: 2px; height: 24px; text-align: center;" type="text" ' +
                    'id="renum" ' +
                    'value="' + parseFloat(buynum).toFixed(2) + '" >' +
//                    '<span id="numInfo" style="display: none;" class="numtInfo">采购数量必须大于0</span>' +
//                    '<span id="numlen" style="display: none;" class="numlen">采购数量超过限制数量' + number + '</span>' +
                    '<input type="hidden" class="numn" value="' + n + '"></div>' +
                    '<hr class="numinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">' +
                    '<div class="hiddendiv">' + //hiddendiv用于重新选择供应商时删除旧的数据添加新的数据
                    '<input type="hidden" name="BsPrchDt[' + n + '][part_no]" value="' + partno + '">' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][dt_id]" value="' + dtid + '">' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][deliv_date]" value="' + delivdate + '"></div>');
                //金额
                _tdtotalamount.append('<span class="amountinput amount' + n + '" ' +
                    'style="margin-bottom: 1px;margin-top: 2px;height: 24px;line-height:24px">&nbsp;</span>' +
                    '<input class=" pricess' + n + ' amountinput" type="hidden" value="" name="BsPrchDt[' + n + '][total_amount]">' +
                    '<hr class="amountinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //税别/税率
                _tdtax.append('<div class="taxdiv">' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][tax]" class="taxid">' +
                    '<input type="text" class="easyui-validatebox    rateinput tax"  ' +
                    'style="width:100px;margin-bottom: 1px;margin-top: 2px;height: 24px;text-align:center;" ' +
                    'data-options="required:true" readonly="readonly">' +
                    '<span id="receiptInfo" style="display: none;" class="receiptInfo">该输入框为必填项</span>' +
                    '<span class="icon-search searchtax rateinput"></span>' + //搜索符号
                    '</div>' +
                    '<hr class="rateinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //币别
                _tdcurrency.append('<div class="currdiv"><span style="margin-bottom: 1px;margin-top: 2px;height: 24px;' +
                    'line-height:24px" class="cur' + n + '">&nbsp;</span>' +
                    '<input type="hidden" class="currency' + n + '" value=""  name="BsPrchDt[' + n + '][cur_id]">' +
//                    '<select style="margin-bottom: 2px;margin-top: 1px; text-align: center;" class="label-width currselect" name="BsPrchDt[' + n + '][cur_id]">' +
//                    <?php //foreach ($currency as $n => $a) { ?>
//                    '<option value="<?//= $a['cur_id'] ?>//"><?//= $a['cur_code'] ?>//</option>' +
//                    <?php //} ?>
//                    '</select>' +
                    '</div>' +
                    '<hr class="currselect" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');
                //交货日期
                _tddelivdate.append('<input  type="hidden" id="start" value="<?=date('Y-m-d', time()) ?>">' +
                    '<input class="no-border easyui-validatebox   deldate dateinput text-center Wdate" type="text" ' +
                    'data-options="required:true" id="Deliverytime" style="width: 150px;height: 24px;margin-bottom: 1px;margin-top: 2px" ' +
                    'onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'%y-%M-#{%d+1}\' })" ' +
                    'onfocus="this.blur()" name="BsPrchDt[' + n + '][deliv_date]" value="" readonly="readonly">' +
                    '<hr class="dateinput" style="width: 100%;height: 1px; border:none;background-color: #cccccc">');

                objdeliv = _tddelivery.find(".delivselect" + n + "");
                $.ajax({
                    url: "<?=Url::to(['delivery']);?>",
                    data: {"partno": rows[i].part_no, "sppcode": rows[i].group_code, "payterms": rows[i].payment_terms},
                    dataType: "json",
                    type: "get",
                    async: false,
                    success: function (data) {
                        if (data.length > 0) {
                            objdeliv.children("option").remove();
                            objdeliv.append('<option value="">--请选择--</option>');
                            for (var i = 0; i < data.length; i++) {
                                objdeliv.append("<option value='" + data[i].trading_terms + "'>" + data[i].trading_terms + "</option>");
                            }
                        }
                    }
                });
            }
            //选择多个供应商加载最后一个供应商时
            if (i == rows.length - 1) {
                prt_spp_pkid = rows[i].prt_spp_pkid;
                //供应商代码
                _tdsppcode.append('<input class="codeinput easyui-validatebox spp' + n + '" readonly="readonly" ' +
                    'data-options="required:true" style="width: 170px;height: 24px; margin-bottom: 2px;margin-top: 2px; text-align: center;"  ' +
                    'type="text" id="renum" value="' + rows[i].group_code + '">' +
                    '<input type="hidden" class="codeinput" name="BsPrchDt[' + n + '][spp_id]" value="' + rows[i].spp_id + '">' +
                    '<span class="icon-search searchspp codeinput"></span>');
                //供应商名称
                _tdsuppname.append('<span class="suppinput" style="margin-bottom: 2px;margin-top: 2px;height: 24px;">' + rows[i].spp_fname + '</span>');
                //付款条件
                _tdpay.append('<div class="paydiv' + n + ' pdiv">' +
                    '<span style="margin-bottom: 2px;margin-top: 2px;height: 24px;line-height:24px">' + rows[i].payment_terms + '</span>' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][pay_condition]" class="payment" ' +
                    'value="' + rows[i].payment_terms + '">' +
                    '<input type="hidden" class="tagn" value="' + n + '">' +
                    '</div>');
                //交货条件
                _tddelivery.append('<div class="delivdiv">' + //div用于将多个供应商的交货条件分开
                    '<select class="delivselect' + n + ' goods  select-width" style="margin-bottom: 2px;margin-top: 2px;height: 24px; ' +
                    'text-align: center;" name="BsPrchDt[' + n + '][goods_condition]">' +
                    '<option value="">--请选择--</option>' +
                    '</select>' +
                    '<input type="hidden" class="tagn" value="' + n + '">' +
                    '</div>');
                //单价
                _tdprice.append('<span class="priceinput pric' + n + '" style="margin-bottom: 2px;' +
                    'margin-top: 2px;height: 24px;line-height:24px">&nbsp;</span>' +
                    '<input type="hidden" class=" pri' + n + ' priceinput" name="BsPrchDt[' + n + '][price]" value="">');
                //数量
                _tdnum.append('<div class="numdiv">' + //div用于将多个供应商的采购数量区分开
                    '<input class="numinput numins num' + n + '" style="width: 80px; margin-bottom: 2px;' +
                    'margin-top: 2px;height: 24px;text-align: center;" name="BsPrchDt[' + n + '][prch_num]" ' +
                    'type="text" id="renum" value="' + parseFloat(buynum).toFixed(2) + '">' +
                    '<span id="numInfo" style="display: none;" class="numtInfo">采购数量必须大于0</span>' +
                    '<span id="numlen" style="display: none;" class="numlen">采购数量超过或不足限制数量<span class="len"></span></span>' +
                    '<input type="hidden" class="numn" value="' + n + '">' +
                    '</div>' +
                    '<div class="hiddendiv">' + //hiddendiv用于重新选择供应商时删除旧的数据添加新的数据
                    '<input type="hidden" name="BsPrchDt[' + n + '][part_no]" value="' + partno + '">' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][dt_id]" value="' + dtid + '">' +
                    '<input type="hidden" name="BsPrchDt[' + n + '][deliv_date]" value="' + delivdate + '"></div>');
                //金额
                _tdtotalamount.append('<span class="amountinput amount' + n + '" ' +
                    'style="margin-bottom: 2px;margin-top: 2px;height: 24px;line-height:24px">&nbsp;</span>' +
                    '<input type="hidden" class=" amountinput  pricess' + n + '" value="" name="BsPrchDt[' + n + '][total_amount]">');
                //税别/税率
                _tdtax.append('<div class="taxdiv"><input type="hidden" name="BsPrchDt[' + n + '][tax]" class="taxid">' +
                    '<input type="text" class="easyui-validatebox   rateinput tax"  ' +
                    'style="width: 100px; margin-bottom: 2px;margin-top: 2px; height: 24px; text-align: center;" ' +
                    'data-options="required:true" readonly="readonly">' +
                    '<span class="icon-search searchtax rateinput"></span></div>');
                //币别
                _tdcurrency.append('<div class="currdiv"><span style="margin-bottom: 2px;margin-top: 2px;height: 24px;line-height:24px" ' +
                    'class="cur' + n + '">&nbsp;</span>' +
                    '<input type="hidden" class="currency' + n + '" value="" name="BsPrchDt[' + n + '][cur_id]">' +
//                    '<select  style="margin-bottom: 2px;margin-top: 1px;  text-align: center;" class="label-width currselect" name="BsPrchDt[' + n + '][cur_id]">' +
//                    <?php //foreach ($currency as $n => $a) { ?>
//                    '<option value="<?//= $a['cur_id'] ?>//"><?//= $a['cur_code'] ?>//</option>' +
//                    <?php //} ?>
//                    '</select>'
                    '</div>');
                //交货日期
                _tddelivdate.append('<input  type="hidden" id="start" value="<?=date('Y-m-d', time()) ?>">' +
                    '<input class="no-border easyui-validatebox  deldate dateinput text-center Wdate" ' +
                    'type="text" data-options="required:true" id="Deliverytime" ' +
                    'style="width: 150px;height: 24px; margin-bottom: 2px;margin-top: 2px;  text-align: center;" ' +
                    'onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'%y-%M-#{%d+1}\' })" ' +
                    'onfocus="this.blur()" name="BsPrchDt[' + n + '][deliv_date]" value="" readonly="readonly">');

                objdeliv = _tddelivery.find(".delivselect" + n + "");
                $.ajax({
                    url: "<?=Url::to(['delivery']);?>",
                    data: {"partno": rows[i].part_no, "sppcode": rows[i].group_code, "payterms": rows[i].payment_terms},
                    dataType: "json",
                    type: "get",
                    async: false,
                    success: function (data) {
                        if (data.length > 0) {
                            objdeliv.children("option").remove();
                            objdeliv.append('<option value="">--请选择--</option>');
                            for (var i = 0; i < data.length; i++) {
                                objdeliv.append("<option value='" + data[i].trading_terms + "'>" + data[i].trading_terms + "</option>");
                            }
                        }
                    }
                });
            }
            $.parser.parse(_tdnum);
            $.parser.parse(_tdprice);
            $.parser.parse(_tdsppcode);
            $.parser.parse(_tdsuppname);
        }
    }
    //rows为税别/税率，data为关联收货中心
    function addTax(rows, data) {
        if (rows != "") {
            if (rows.length == 1) {
                _this1.siblings('.tax').removeClass('red-border');
                _this1.siblings('.tax').val(rows[0].tax_no + '/' + (rows[0].tax_value * 100) + '%');
                _this1.siblings('.taxid').val(rows[0].tax_pkid);
                _this1.parent('.taxdiv').find('.tax').val(rows[0].tax_no + '/' + (rows[0].tax_value * 100) + '%');
                _this1.parent('.taxdiv').find('.taxid').val(rows[0].tax_pkid);
            }
            else {
                _this1.siblings('.tax').removeClass('red-border');
                _this1.siblings('.tax').val(rows.tax_no + '/' + (rows.tax_value * 100) + '%');
                _this1.siblings('.taxid').val(rows.tax_pkid);
                _this1.parent('.taxdiv').find('.tax').val(rows.tax_no + '/' + (rows.tax_value * 100) + '%');
                _this1.parent('.taxdiv').find('.taxid').val(rows.tax_pkid);
            }
        }
        if (data != "") {
            if (data.length == 1) {
                _this2.siblings('.address').removeClass('red-border');
                var rcpname=HTMLDecode(data[0].rcp_name);
                _this2.siblings('.address').val(rcpname);
                _this2.siblings('#whid').val(data[0].rcp_id);
            }
            //双击
            else {
                _this2.siblings('.address').removeClass('red-border');
                var rcpname1=HTMLDecode(data.rcp_name);
                _this2.siblings('.address').val(rcpname1);
                _this2.siblings('#whid').val(data.rcp_id);
            }
        }
    }
    function HTMLDecode(text)
    {    var temp = document.createElement("div");
        temp.innerHTML = text;
        var output = temp.innerText || temp.textContent;
        temp = null;
        return output;
    }
    $(function () {
        loadtable();//加载料号信息

        //根据付款条件获取交货条件
        var _deli = $(".deli");//交货条件所在的td
        //循环为交货条件获取数据
        _deli.each(function () {
            var payment = $(this).children('.delivdiv').children('.payment').val();//付款条件
            var partno = $(this).siblings('.partno').children('.partid').val();//料号
            var sppcode = $(this).children('.delivdiv').children('.sppco').val();//供应商代码
            var obj = $(this).children('.delivdiv').children('.delivc');//交货条件的Select元素
            $.ajax({
                url: "<?=Url::to(['delivery']);?>",
                data: {"partno": partno, "sppcode": sppcode, "payterms": payment},
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    //循环为交货条件加载数据
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            obj.append("<option value='" + data[i].trading_terms + "'>" + data[i].trading_terms + "</option>");
                        }
                    }
                }
            });
        });
        //选择交货条件时，根据料号，供应商代码，付款条件、交货条件以及数量获取价格
        $("#partspp").delegate('.goods', 'change', function () {
            $(this).removeClass('red-border');//先删除所有的红色边框
            var tradterms = $(this).val();//交货条件
            var n = $(this).siblings('.tagn').val();//数组的下标
            var _thistd = $(this).parent('.delivdiv').parent('.delivery');//当前交货条件的td
            var partno = _thistd.siblings('.partno').children('.partid').val();//料号
            var _price = _thistd.siblings('.reqprice').children('.pric' + n + '');//单价单元格要显示的数据span
            var _num = _thistd.siblings('.reqnum').children('.numdiv').children('.num' + n + '').val();//获取数量
            var _total = _thistd.siblings('.totalamount');//总金额td
            var payterms = _thistd.siblings('.pay').children('.paydiv' + n + '').children('.payment').val();//获取付款条件
            var sppcode = _thistd.siblings('.sppcode').children('.spp' + n + '').val();//供应商代码
            var _currency = _thistd.siblings('.currency').children('.currdiv');//币别所对应的td下的DIV
            $.ajax({
                url: "<?=Url::to(['price']);?>",
                data: {"partno": partno, "sppcode": sppcode, "payterms": payterms, "tradterms": tradterms, "num": _num},
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    //console.log(data);
                    //将获取的价格与数量进行计算显示在前台
                    if (data) {
                        _price[0].innerHTML = parseFloat(data.quote_price).toFixed(5);//显示的单价用于在前台显示数据
                        _price.siblings('.pri' + n + '').val(parseFloat(data.quote_price).toFixed(5));//隐藏的单价用于后台保存数据
                        _total.children('.pricess' + n + '').val(parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2));//隐藏的总金额用于后台保存数据
                        _total.children('.amount' + n + '')[0].innerHTML = parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2);//显示的总金额用于前台显示数据
                        _currency.children('.cur' + n + '')[0].innerHTML = data.quote_currency;//显示的币别用于在前台显示数据
                        _currency.children('.currency' + n + '').val(data.currency);//隐藏的币别用于后台保存数据
                    }
                    else {
                        //添加&nbsp;是为了不让单元格变形
                        _price[0].innerHTML = "&nbsp;";//显示的单价用于在前台显示数据
                        _price.siblings('.pri' + n + '').val("");//隐藏的单价用于后台保存数据
                        _total.children('.pricess' + n + '').val("");//隐藏的总金额用于后台保存数据
                        _total.children('.amount' + n + '')[0].innerHTML = "&nbsp;";//显示的总金额用于前台显示数据
                        _currency.children('.cur' + n + '')[0].innerHTML = "&nbsp;";//显示的币别用于在前台显示数据
                        _currency.children('.currency' + n + '').val("");//隐藏的币别用于后台保存数据
                    }
                }
            });
        });
        //供应商选择
        $("#partspp").delegate('.searchspp', 'click', function () {
            _this = $(this);
            var partid = $(this).parent().siblings('.partno').find('.partid').val();
            var url = "<?=Url::to(['spp-partno'])?>?id=" + partid;
            var idField = "prt_spp_pkid";
            $.fancybox({
                href: "<?=Url::to(['/spp/supplier-pop-tpl/select-supplier'])?>?id=" + partid + "&url=" + url + "&idField" + idField,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 600
            });
        });
        //税别/税率弹窗
        $("#partspp").delegate('.searchtax', 'click', function () {
            $("button[type='submit']").removeAttr("disabled");
            _this1 = $(this);
            $.fancybox({
                href: "<?=Url::to(['/common/rate-data/rate-select'])?>",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 590,
                height: 600
            });
        });
        //关联收货中心弹窗
        $(".searchaddress").click(function () {
            $("button[type='submit']").removeAttr("disabled");
            _this2 = $(this);
            $.fancybox({
                href: "<?=Url::to(['/warehouse/receipt-center-set/receipt-info'])?>",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 700,
                height: 600
            });
        });


        //采购数量失去焦点时计算总金额
        $("#partspp").delegate('.numins', 'blur', function () {
            var _num = $(this).val();//当前的采购数量
            if(_num=="")
            {
                $(this).val(_num);
            }
            else {
                $(this).val(parseFloat(_num).toFixed(2));
            }
            var _thistd = $(this).parent('.numdiv').parent('.reqnum');//当前数量的td
            var sumnum = parseFloat(_thistd.find(".number").val());//请购总数量
            var numss = _thistd.find(".numins");//输入框的请购数量
            var sumnums = 0;//新输入的采购数量
            var upp_num=0;//上限
            var low_num=0;//下限
            numss.each(function () {
                sumnums += parseFloat($(this).val());
            });
            $.ajax({
                url: "<?=Url::to(['ratio-info']);?>",
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    upp_num=data[0].upp_num;
                    low_num=data[0].low_num;
                }
            });
            var compareupp=parseFloat(parseFloat(sumnum*(1+parseFloat(upp_num))).toFixed(2));//比较的上限数量
            var comparelow=parseFloat(parseFloat(sumnum*(1-parseFloat(low_num))).toFixed(2));//比较的下限数量
            var n = $(this).siblings('.numn').val();//数组的下标
            var partno = _thistd.siblings('.partno').children('.partid').val();//料号
            var _prices = _thistd.siblings('.reqprice').children('.pric' + n + '');//单价单元格要显示的数据span
            var _totals = _thistd.siblings('.totalamount');//总金额td
            var payterms = _thistd.siblings('.pay').children('.paydiv' + n + '').children('.payment').val();//获取付款条件
            var sppcode = _thistd.siblings('.sppcode').children('.spp' + n + '').val();//供应商代码
            var _currency = _thistd.siblings('.currency').children('.currdiv');//币别所对应的td下的DIV
            var tradterms = _thistd.siblings('.delivery').children('.delivdiv').children('.delivselect' + n + '').val();//获取交货条件
            //当采购总数量超过限制数量时(采购数量小于下限或者大于上限)
            if ((parseFloat(sumnums.toFixed(2)) <comparelow) || (parseFloat(sumnums.toFixed(2))>compareupp)) {
                _thistd.find(".numins").addClass('red-border');
                $("#MorethanNum").val("采购数量超过或不足限制数量");
                $(".len")[0].innerHTML=comparelow+'-'+compareupp;
                if(_num==0)
                {
                    $(this).addClass('red-border');//为当前元素添加红色边框
                    _prices[0].innerHTML = "&nbsp;";//显示的单价用于在前台显示数据
                    _prices.siblings('.pri' + n + '').val("");//隐藏的单价用于后台保存数据
                    _totals.children('.pricess' + n + '').val("");//隐藏的总金额用于后台保存数据
                    _totals.children('.amount' + n + '')[0].innerHTML = "&nbsp;";//显示的总金额用于前台显示数据
                    _currency.children('.cur' + n + '')[0].innerHTML = "&nbsp;";//显示的币别用于在前台显示数据
                    _currency.children('.currency' + n + '').val("");//隐藏的币别用于后台保存数据
                }
            }
            else {
                $("#MorethanNum").val("");
                if (_num > 0) {
                    var _numins = _thistd.find(".numins");
                    _numins.each(function () {
                        var num = $(this).val();
                        if (num > 0) {
                            $(this).removeClass('red-border');
                        }
                        else {
                            $("#prchnum").val("0");
                        }
                    });
                    $.ajax({
                        url: "<?=Url::to(['price']);?>",
                        data: {
                            "partno": partno,
                            "sppcode": sppcode,
                            "payterms": payterms,
                            "tradterms": tradterms,
                            "num": _num
                        },
                        dataType: "json",
                        type: "get",
                        async: false,
                        success: function (data) {
                            //将获取的价格与数量进行计算显示在前台
                            if (data) {
                                _prices[0].innerHTML = parseFloat(data.quote_price).toFixed(5);//显示的单价用于在前台显示数据
                                _prices.siblings('.pri' + n + '').val(parseFloat(data.quote_price).toFixed(5));//隐藏的单价用于后台保存数据
                                _totals.children('.pricess' + n + '').val(parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2));//隐藏的总金额用于后台保存数据
                                _totals.children('.amount' + n + '')[0].innerHTML = parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2);//显示的总金额用于前台显示数据
                                _currency.children('.cur' + n + '')[0].innerHTML = data.quote_currency;//显示的币别用于在前台显示数据
                                _currency.children('.currency' + n + '').val(data.currency);//隐藏的币别用于后台保存数据
                            }
                            else {
                                //添加&nbsp;是为了不让单元格变形
                                _prices[0].innerHTML = "&nbsp;";//显示的单价用于在前台显示数据
                                _prices.siblings('.pri' + n + '').val("");//隐藏的单价用于后台保存数据
                                _totals.children('.pricess' + n + '').val("");//隐藏的总金额用于后台保存数据
                                _totals.children('.amount' + n + '')[0].innerHTML = "&nbsp;";//显示的总金额用于前台显示数据
                                _currency.children('.cur' + n + '')[0].innerHTML = "&nbsp;";//显示的币别用于在前台显示数据
                                _currency.children('.currency' + n + '').val("");//隐藏的币别用于后台保存数据
                            }
                        }
                    });
                }
                //采购总数量没有超过请购数量但是采购数量为0为负或为空
                else {
                    $(this).addClass('red-border');//为当前元素添加红色边框
                    _prices[0].innerHTML = "&nbsp;";//显示的单价用于在前台显示数据
                    _prices.siblings('.pri' + n + '').val("");//隐藏的单价用于后台保存数据
                    _totals.children('.pricess' + n + '').val("");//隐藏的总金额用于后台保存数据
                    _totals.children('.amount' + n + '')[0].innerHTML = "&nbsp;";//显示的总金额用于前台显示数据
                    _currency.children('.cur' + n + '')[0].innerHTML = "&nbsp;";//显示的币别用于在前台显示数据
                    _currency.children('.currency' + n + '').val("");//隐藏的币别用于后台保存数据
                }
            }
        });

        //当采购数量为0时鼠标移动到上面时提示
        $("#partspp").delegate('.numinput', 'mouseover', function () {
            $("button[type='submit']").removeAttr("disabled");
            var _numdiv = $(this).parent();//当前数量的父元素div
            var _num = $(this).val();//当前的采购数量
            $(this).val(_num);
            var _tdtotal = _numdiv.parent();//当前数量的td
            var sumnum = _tdtotal.find(".number").val();//请购总数量
            var numss = _tdtotal.find(".numins");//输入框的请购数量
            var sumnums = 0;//新输入的采购数量
            var upp_num=0;//上限
            var low_num=0;//下限
            numss.each(function () {
                sumnums += parseFloat($(this).val());
            });
            $.ajax({
                url: "<?=Url::to(['ratio-info']);?>",
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    upp_num=data[0].upp_num;
                    low_num=data[0].low_num;
                }
            });
            var compareupp=parseFloat(parseFloat(sumnum*(1+parseFloat(upp_num))).toFixed(2));//比较的上限数量
            var comparelow=parseFloat(parseFloat(sumnum*(1-parseFloat(low_num))).toFixed(2));//比较的下限数量
            //当采购总数量超过限制数量时(采购数量小于下限或者大于上限)
            if ((parseFloat(sumnums.toFixed(2)) <comparelow) || (parseFloat(sumnums.toFixed(2))>compareupp)) {
                if ($(this).val() > 0) {
                    $("#numInfo").css('display', 'none');//隐藏不为0的提示框
                    $(this).myHoverTip('numlen');//显示超过数量的提示框
                }
                //已超过请购数量但是采购数量为0 或负 或空
                else {
                    $("#numlen").css('display', 'none');//隐藏超过数量的提示框
                    $(this).myHoverTip('numInfo');//显示超过数量的提示框
                }
            }
            else {
                if ($(this).val() > 0) {
                    $("#numlen").css('display', 'none');//隐藏超过数量的提示框
                    $("#numInfo").css('display', 'none');//隐藏不为0的提示框
                }
                //没超过但是采购数量为0 或负 或空
                else {
                    $("#numlen").css('display', 'none');//隐藏超过数量的提示框
                    $(this).myHoverTip('numInfo');//显示相关的提示框
                }

            }

        });
        //税率/税别为空或不为空鼠标移动到上面时的样式
        $("#partspp").delegate('.tax', 'mouseover', function () {
            $("button[type='submit']").removeAttr("disabled");
            if ($(this).val() == null || $(this).val() == "") {
                $(this).myHoverTip('receiptInfo');
            }
            else {
                $("#receiptInfo").css('display', 'none');
            }
        });
        //交货条件为空或不为空鼠标移动到上面时的样式
        $("#partspp").delegate('.goods', 'mouseover', function () {
            $("button[type='submit']").removeAttr("disabled");
            if ($(this).val() == null || $(this).val() == "") {
                $(this).myHoverTip('receiptInfo');
            }
            else {
                $("#receiptInfo").css('display', 'none');
            }
        });
        //交货日期为空或不为空鼠标移动到上面或移开时的样式
        $("#partspp").delegate('.deldate', 'mouseover', function () {
            $("button[type='submit']").removeAttr("disabled");
            if ($(this).val() == null || $(this).val() == "") {
                $(this).myHoverTip('receiptInfo');
            }
            else {
                $("#receiptInfo").css('display', 'none');
            }
        });
        $("#partspp").delegate('.deldate', 'mouseout', function () {
            $("button[type='submit']").removeAttr("disabled");
            if ($(this).val() == null || $(this).val() == "") {
                $(this).myHoverTip('receiptInfo');
            }
            else {
                $(this).removeClass('red-border');
            }
        });
        //保存
        $(".save-form").click(function () {
            btnFlag = $(this).text().trim();
            var trcount=$(".proinfo").length;
            var  invalid=$(".proinfo").find(".invalid").val();
            console.log(invalid);
            var prchnum=$("#prchnum").val();//当采购数量为0为空为负时的代表
            var morethan = $("#MorethanNum").val();
            var flag = setstyle("tax", "rateinput");//税别/税率
            var flag1 = setstyle("goods", "delivc");//交货条件
            var flag2 = setstyle("deldate", "dateinput");//交货时间
            var flag3 = setstyle("numins", "numinput");//采购数量
            var flag4=setstyle("codes","");//供应商代码
            if (morethan == "采购数量超过或不足限制数量" || prchnum=="0") {
                layer.alert("采购数量超过或不足限制数量或采购数量必须大于0", {icon: 2});
                return false;
            }
            if (flag > 0 || flag1 > 0 || flag2 > 0 || flag3 > 0||flag4>0) {
                return false;
            }
            if(trcount==1&&invalid=="1")
            {
                layer.alert("此料号的核价信息已失效,请重新选择！", {icon: 2});
                return false;
            }
            else {
                $("form").submit();
                $(this).attr("disabled",'disabled');
                $("#add-form").attr('action', '<?=Url::to(['create'])?>');
            }
        });
        //提交
        $(".apply-form").click(function () {
            btnFlag = $(this).text().trim();
            var trcount = $(".proinfo").length;
            var prchnum=$("#prchnum").val();//当采购数量为0为空为负时的代表
            var morethan = $("#MorethanNum").val();
            var flag = setstyle("tax", "rateinput");//税别/税率
            var flag1 = setstyle("goods", "delivc");//交货条件
            var flag2 = setstyle("deldate", "dateinput");//交货时间
            var flag3 = setstyle("numins", "numinput");//采购数量
            var flag4=setstyle("codes","");//供应商代码
            if (morethan == "采购数量超过或不足限制数量" || prchnum=="0") {
                layer.alert("采购数量超过或不足限制数量或采购数量必须大于0", {icon: 2});
                return false;
            }
            if (flag > 0 || flag1 > 0 || flag2 > 0 || flag3 > 0||flag4>0) {
                return false;
            }
            else {
                $("form").submit();
                $(this).attr("disabled",'disabled');
                $("#add-form").attr('action', '<?=Url::to(['create'])?>');
            }
        });

        /**
         * 鼠标移上去显示层
         * @param divId 显示的层ID
         * @returns
         */
        $.fn.myHoverTip = function (divId) {
            var div = $("#" + divId); //要浮动在这个元素旁边的层
            div.css("position", "absolute");//让这个层可以绝对定位
            var self = $(this); //当前对象
            self.hover(function () {
                    div.css("display", "block");
                    var p = self.position(); //获取这个元素的left和top
                    var x = p.left + self.width();//获取这个浮动层的left
                    var docWidth = $(document).width();//获取网页的宽
                    if (x > docWidth - div.width() - 20) {
                        x = p.left - div.width();
                    }
                    div.css("left", x + 10);
                    div.css("top", p.top);
                    div.css("z-index", '99');
                    if (divId == "numlen") {
                        div.css("width", '260px');
                    }
                    else {
                        div.css("width", '130px');
                    }
                    div.css("height", '25px');
                    div.css("border", '1px solid #d2abab');
                    div.css("border-radius", "5px");
                    div.css("background-color", '#FFFFCC ');
                    div.css("line-height", '25px ');
                    div.show();
                },
                function () {
                    div.css("display", "none");
                }
            );
            return this;
        };
        ajaxSubmitForm($("#add-form"), '', function (data) {
            if (data.flag == 1) {
                if (btnFlag == '提交') {
                    var id = data.billId;
                    var url = "<?=Url::to(['/purchase/purchase-notify/index'], true)?>";
                    var type = data.billTypeId;
                    $.fancybox({
                        href: "<?=Url::to(['/system/verify-record/prch-verify'])?>?type=" + type + "&id=" + id + "&url=" + url,
                        type: "iframe",
                        padding: 0,
                        autoSize: false,
                        width: 750,
                        height: 480,
                        afterClose: function () {
                            location.href = "<?=Url::to(['/purchase/purchase-notify/index'])?>";
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
    //当元素为空时添加错误提醒样式
    function setstyle(classname, inputclass) {
        flag = 0;
        var _classname = $("." + classname + "");
        _classname.each(function () {
            if ($(this).val() == null || $(this).val() == "" || $(this).val() == 0) {
                //交货时间
                if (classname == 'deldate') {
                    $(this).attr('class', "easyui-validatebox " + classname + " " + inputclass + " text-center Wdate");
                    $(this).focus();
                    flag += 1;
                }
                //数量
                else if (classname == 'numins') {
                    $(this).attr('class', "red-border " + classname + "");
                    $(this).focus();
                    flag += 1;
                }
                else {
                    $(this).attr('class', "red-border " + classname + " " + inputclass + " easyui-validatebox");
                    $(this).focus();
                    flag += 1;
                }
            }
        });
        return flag;
    }

</script>


