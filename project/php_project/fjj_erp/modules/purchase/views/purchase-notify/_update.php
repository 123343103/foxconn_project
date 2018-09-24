<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/28
 * Time: 14:31
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);

$this->title = '修改采购单';
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '采购单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '修改采购单'];
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

    .select-width {
        width: 120px;
    }
</style>

<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <label style="color:#FFFFFF;font-size: 13px;margin-left: 630px;">采购单号：<?= $model[0]['prch_no'] ?></label>
    </h1>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <h2 class="head-second color-1f7ed0 ">采购单信息</h2>
    <input type="hidden" id="prch_id" value="<?= $model[0]['prch_id'] ?>">
    <div class="ml-10">
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 30px;">
                <label class="label-width label-align">单据类型：</label>
                <span class="span-width"><?= $reqdct['bsp_svalue'] ?></span>
                <input type="hidden" value="<?= $reqdct['bsp_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">法人：</label>
                <span class="span-width"><?= $legid['company_name'] ?></span>
                <input type="hidden" value="<?= $legid['company_id'] ?>">
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 30px;">
                <label class="label-width label-align">采购区域：</label>
                <span class="span-width"><?= $areaid['factory_name'] ?></span>
                <input type="hidden" value="<?= $areaid['factory_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">采购部门：</label>
                <span class="span-width"><?= $buyerinfo[0]['organization_name'] ?></span>
                <input type="hidden" value="<?= $buyerinfo[0]['organizationID'] ?>">

            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 30px;">
                <label class="label-width label-align">采购员：</label>
                <span class="span-width"><?= $buyerinfo[0]['staff_code'] ?>
                    --<?= $buyerinfo[0]['staff_name'] ?></span>
                <input type="hidden" value="<?= $buyerinfo[0]['staff_id'] ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>联系方式：</label>
                <input class="value-width value-align easyui-validatebox" id="contact"
                       name="BsPrch[contact_info]"
                       data-options="required:'true',validType:'tel_mobile_c' "
                       value="<?= $model[0]['contact_info'] ?>" placeholder="请输入手机或座机号码">
            </div>
        </div>
        <div class="mb-10" style="margin-left: 30px;">
            <div class="inline-block">
                <label class="label-width label-align"><span class="red">*</span>收货中心：</label>
                <input type="text" class="value-width value-align easyui-validatebox address" id="addr"
                       value="<?= $model[0]['rcp_name'] ?>" readonly="readonly">
                <input type="hidden" id="whid" name="BsPrch[rcp_id]" value="<?= $model[0]['rcp_id'] ?>">
                <span class="icon-search searchaddress"></span>
            </div>
            <div class="inline-block" style="margin-left: 115px;">
                <label class="label-width label-align">采购日期：</label>
                <span class="span-width"><?= date('Y/m/d', time()) ?></span>
                <input type="hidden" value="<?= $model[0]['app_date'] ?>">
            </div>
        </div>
        <div class="mb-10" style="margin-left: 30px;">
            <div class="inline-block">
                <label class="label-width label-align">备注：</label>
                <input style="width:580px;height: 50px;" class="rem" type="text" id="remarks" name="BsPrch[remarks]"
                       maxlength="100"
                       value="<?= $model[0]['remarks'] ?>" placeholder="最多输入100个字"
                       onfocus="onfocustishi(this.placeholder,'最多输入100个字',this.id)"
                       onblur="blurtishi(this.value,'最多输入100个字',this.id)">
            </div>
        </div>
    </div>
    <h2 class="head-second color-1f7ed0">采购料号信息</h2>
    <div class=" mb-10" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
        <input type="hidden" id="buynumprice" value="">
        <input type="hidden" id="MorethanNum" value="">
        <input type="hidden" id="prnums" value="">
        <table class="table" id="tb_table" style="width: 2500px;">
            <thead>
            <tr>
                <th width="50">序号</th>
                <th width="130">料号</th>
                <th width="130">品名</th>
                <th width="100">规格</th>
                <th width="100">品牌</th>
                <th width="50">单位</th>
                <th width="180">供应商代码</th>
                <th width="150">供应商名称</th>
                <th width="150">付款条件</th>
                <th width="150"><span class="red">*</span>交货条件</th>
                <th width="100">单价</th>
                <th width="80">申请采购数量</th>
                <th width="80">金额</th>
                <th width="80"><span class="red">*</span>税别/税率</th>
                <th width="80">币别</th>
                <th width="150"><span class="red">*</span>交货日期</th>
                <th width="150">关联单号</th>
            </tr>
            </thead>
            <tbody id="partspp">
            <?php if (!empty($model)) { ?>
                <?php foreach ($model as $key => $val) { ?>
                    <tr class="proinfo">
                        <td class="num">
                            <span style="width: 50px;"><?= $key + 1 ?></span>
                        </td>
                        <td class="partno">
                            <span><?= $val['part_no'] ?></span>
                        </td>
                        <td class="pdtname">
                            <input type="hidden" class="prtid" name="BsPrchDt[<?= $key ?>][part_no]"
                                   value="<?= $val['part_no'] ?>">
                            <input type="hidden" class="prchid" name="BsPrchDt[<?= $key ?>][prch_id]"
                                   value="<?= $val['prch_id'] ?>">
                            <input type="hidden" class="dtid" name="BsPrchDt[<?= $key ?>][dt_id]" value="">
                            <span><?= $val['pdt_name'] ?></span>
                            <input type="hidden" value="<?= $val['pdt_name'] ?>"></td>
                        <td class="tpspec">
                            <span><?= $val['tp_spec'] ?></span>
                        </td>
                        <td class="brandname">
                            <span><?= $val['brand'] ?></span>
                        </td>
                        <td class="unit">
                            <span><?= $val['unit'] ?></span>
                        </td>
                        <td class="sppcode">
                            <span><?= $val['spp_code'] ?></span>
                            <input type="hidden" class="sppid" name="BsPrchDt[<?= $key ?>][spp_id]"
                                   value="<?= $val['spp_id'] ?>">
                            <input type="hidden" class="sppco" value="<?= $val['spp_code'] ?>">
                        </td>
                        <td class="suppname"><span><?= $val['spp_fname'] ?></span>
                        </td>
                        <td class="pay">
                            <span><?= $val['pay_condition'] ?></span>
                            <input type="hidden" class="conpay" name="BsPrchDt[<?= $key ?>][pay_condition]"
                                   value="<?= $val['pay_condition'] ?>">
                        </td>
                        <td class="goods">
                            <select class="delivc select-width" name="BsPrchDt[<?= $key ?>][goods_condition]">
                                <option value="">--请选择--</option>
                            </select>
                            <input type="hidden" class="congoods" value="<?= $val['goods_condition'] ?>">
                        </td>

                        <td class="pricetax">
                            <span class="prchprice"><?= sprintf("%.5f", $val['price']) ?></span>
                            <input type="hidden" class="pri" name="BsPrchDt[<?= $key ?>][price]"
                                   value="<?= sprintf("%.5f", $val['price']) ?>">
                        </td>
                        <td class="prchnum">
                            <input type="text" class="number easyui-validatebox "
                                   style="width: 80px; text-align: center;"
                                   name="BsPrchDt[<?= $key ?>][prch_num]" data-options="required:'true'"
                                   value="<?= sprintf("%.2f", $val['prch_num']) ?>">
                            <input type="hidden" class="num" value="<?= sprintf("%.2f", $val['prch_num']) ?>">
                            <span id="numInfo" style="display: none;" class="numtInfo">采购数量必须大于0</span>
                            <span id="numlen" style="display: none;" class="numlen"></span>
                        </td>
                        <td class="totalamount">
                            <input type="hidden" class="amount" name="BsPrchDt[<?= $key ?>][total_amount]"
                                   value="<?= sprintf("%.2f", $val['total_amount']) ?>">
                            <span class="total"><?= sprintf("%.2f", $val['total_amount']) ?></span>
                        </td>
                        <td class="tax" style="width:130px;">
                            <input type="hidden" value="<?= $val['tax'] ?>" name="BsPrchDt[<?= $key ?>][tax]"
                                   class="taxid"><input class="key" type="hidden" value="<?= $key + 1 ?>">
                            <input type="text" value="<?= $val['tax_no'] ?>/<?= $val['tax_value'] * 100 ?>%"
                                   class="easyui-validatebox rate" style="width:100px;text-align:center;"
                                   data-options="required:'true'" disabled>
                            <span class="icon-search searchtax"></span>
                        </td>
                        <td class="currency">
                            <span class="cur"><?= $val['currency'] ?></span>
                            <input type="hidden" class="currency" value="<?= $val['cur_id'] ?>"
                                   name="BsPrchDt[<?= $key ?>][cur_id]">
                        </td>
                        <td class="delivdate">
                            <input type="hidden" id="start" value="<?= date('Y-m-d', time()) ?>">
                            <input class=" no-border deldate text-center Wdate" type="text" id="Deliverytime"
                                   style="width: 150px;"
                                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '%y-%M-#{%d+1}' })"
                                   onfocus="this.blur()" name="BsPrchDt[<?= $key ?>][deliv_date]"
                                   value="<?= $val['deliv_date'] ?>" readonly="readonly">
                        </td>
                        <td class="req_nos">
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
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
    var _this1 = null;
    var _this2 = null;
    /////////////////////////////////////
    //函数说明：合并指定表格（表格id为_w_table_id）指定列（列数为_w_table_colnum）的相同文本的相邻单元格
    //参数说明：_w_table_id 为需要进行合并单元格的表格的id。如在HTMl中指定表格 id="data" ，此参数应为 #data
    //参数说明：_w_table_colnum 为需要合并单元格的所在列。为数字，从最左边第一列为1开始算起。
    ///////////////////////////////////
    function _w_table_rowspan(_w_table_id, _w_table_colnum) {
        _w_table_firsttd = "";
        _w_table_nexittd="";
        _w_table_currenttd = "";
        _w_table_currenttd1="";
        _w_table_SpanNum = 0;
        _w_table_Obj = $(_w_table_id + " tr td:nth-child(" + _w_table_colnum + ")");
        _w_table_Obj.each(function (i) {
            if (i == 0) {
                _w_table_firsttd = $(this);//指定列的第一行的td
                _w_table_nexittd=$(this).siblings('.pdtname');//商品名称列
                _w_table_SpanNum = 1;//合并的行数
            } else {
                _w_table_currenttd = $(this);//当前td
                _w_table_currenttd1 = $(this).siblings('.pdtname');//当前的商品名称列
                //这边注意不是val（）属性，而是text（）属性，判断是否相等
                if (_w_table_firsttd.text() == _w_table_currenttd.text())
                {   _w_table_SpanNum++;//合并的行数+1
                    _w_table_currenttd.hide(); //remove();
                    _w_table_firsttd.attr("rowSpan", _w_table_SpanNum);//相同就合并
                }
                //合并料号相同的商品名称
                if(_w_table_nexittd.find(".prtid").val()==_w_table_currenttd1.find(".prtid").val())
                {
                    _w_table_SpanNum++;//合并的行数+1
                    _w_table_currenttd1.hide();//remove();
                    _w_table_nexittd.attr("rowSpan",_w_table_SpanNum);
                }
                else {
                    _w_table_firsttd = $(this);//否则就将当前的td附给变量
                    _w_table_nexittd=$(this).siblings('.pdtname');
                    _w_table_SpanNum = 1;//合并行数也为1
                }
            }
        });
    }
    $(function () {
        var _rows = $("#tb_table").find("tbody tr");
        var _this = null;
        _rows.each(function () {
            _this = $(this);
            var partno = _this.find(".pdtname").find(".prtid").val();//料号
            var prchid = _this.find(".pdtname").find(".prchid").val();//采购单ID
            var sppid = _this.find(".sppcode").find(".sppid").val();//供应商ID
            //获取请购单号
            $.ajax({
                url: "<?=Url::to(['req-no']);?>",
                data: {"id": prchid, "partno": partno},
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    if (data != null) {
                        var _tdreqnos = _this.find(".req_nos");
                        var _tddtid = _this.find(".pdtname").find(".dtid");
                        var dtid = "";
                        //获取请购的总数量
                        //获取请购的详情ID,用于请购与采购关联
                        for (var i = 0; i < data.length; i++) {
                            if (i == data.length - 1)//去掉最后一个后面的逗号
                            {
                                dtid += data[i].req_dt_id;
                            }
                            else {
                                dtid += data[i].req_dt_id + ',';
                            }
                            _tdreqnos.append('<a href="<?= \yii\helpers\Url::to(['/purchase/purchase-apply/view'])?>?id=' + data[i].req_id + '">' + data[i].req_no + '</a><span>&nbsp;&nbsp;&nbsp;</span>');
                        }
                        _tddtid.val(dtid);
                    }
                }
            });
        });

        //获取交货条件
        var _td = $(".goods");
        _td.each(function () {
            var partno = $(this).siblings(".pdtname").find(".prtid").val();//料号
            var sppid = $(this).siblings(".sppcode").find(".sppco").val();//供应商代码
            var obj = $(this).children('.delivc');//交货条件的下拉框
            var deliv = $(this).find('.congoods').val();//采购单所保存的交货条件
            var payterms = $(this).siblings('.pay').find('.conpay').val();//采购单所保存的付款条件
            $.ajax({
                url: "<?=Url::to(['/purchase/purchase-before-work/delivery']);?>",
                data: {"partno": partno, "sppcode": sppid, "payterms": payterms},
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    //console.log(data);
                    if (data.length > 0) {
                        for (var i = 0; i < data.length; i++) {
                            if (data[i].trading_terms == deliv) {
                                obj.append("<option selected value='" + data[i].trading_terms + "'>" + data[i].trading_terms + "</option>");
                            }
                            else {
                                obj.append("<option value='" + data[i].trading_terms + "'>" + data[i].trading_terms + "</option>");
                            }
                        }
                    }
                }
            });
        });
        //根据付款条件与交货条件获取价格
        $("#partspp").delegate('.delivc', 'change', function () {
            var tradterms = $(this).val();//交货条件
            var _goodtd = $(this).parent('.goods');//当前交货条件所在的td
            var partno = _goodtd.siblings(".pdtname").find(".prtid").val();//料号
            var sppcode = _goodtd.siblings(".sppcode").find(".sppco").val();//供应商代码
            var _price = _goodtd.siblings('.pricetax').children('.prchprice');//单价单元格要显示的数据span
            var _num = _goodtd.siblings('.prchnum').children('.number').val();//获取数量
            var _total = _goodtd.siblings('.totalamount');//总金额td
            var payterms = _goodtd.siblings('.pay').find('.conpay').val();//获取付款条件
            $.ajax({
                url: "<?=Url::to(['/purchase/purchase-before-work/price']);?>",
                data: {"partno": partno, "sppcode": sppcode, "payterms": payterms, "tradterms": tradterms},
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    if (data) {
                        _price[0].innerHTML = parseFloat(data.quote_price).toFixed(5);//显示的单价用于显示数据
                        _price.siblings('.pri').val(parseFloat(data.quote_price).toFixed(5));//隐藏的单价用于保存数据
                        _total.children('.amount').val(parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2));//隐藏的总金额用于保存数据
                        _total.children('.total')[0].innerHTML = parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2);//显示的总金额用于显示数据
                        _goodtd.siblings('.currency').find('.cur')[0].innerHTML = data.quote_currency;
                        _goodtd.siblings('.currency').find('.currency').val(data.currency);
                    }
                    else {
                        _price[0].innerHTML = "";//显示的单价用于显示数据
                        _price.siblings('.pri').val("");//隐藏的单价用于保存数据
                        _total.children('.amount').val("");//隐藏的总金额用于保存数据
                        _total.children('.total')[0].innerHTML = "";//显示的总金额用于显示数据
                        _goodtd.siblings('.currency').find('.cur')[0].innerHTML = "";
                        _goodtd.siblings('.currency').find('.currency').val("");
                    }
                }
            });
        });
        //_w_table_rowspan("#partspp", 2);//合并第二列的数据
       // _w_table_rowspan("#partspp", 17);//合并最后一列的数据

        //采购数量失去焦点时计算总金额
        $("#partspp").delegate('.number', 'blur', function () {
            var _numinput = $(this);//当前的数量input
            var _num = parseFloat($(this).val());//当前输入的采购数量
            var prchnum = parseFloat($(this).siblings('.num').val());//采购的数量
            if(_num=="")
            {
                $(this).val(_num);
            }
            else {
                $(this).val(parseFloat(_num).toFixed(2));//为数量保留两位小数
            }
            var _tdnum = _numinput.parent();//当前数量的td
            var partno = _tdnum.siblings(".pdtname").find(".prtid").val();//料号
            var sppcode = _tdnum.siblings(".sppcode").find(".sppco").val();//供应商代码
            var _prices = _tdnum.siblings('.pricetax').children('.prchprice');//单价单元格要显示的数据span
            //var _num = _goodtd.siblings('.prchnum').children('.number').val();//获取数量
            var _totals = _tdnum.siblings('.totalamount');//总金额td
            var payterms = _tdnum.siblings('.pay').find('.conpay').val();//获取付款条件
            var tradterms = _tdnum.siblings('.goods').find('.delivc').val();//获取交货条件
            var upp_num=0;//上限
            var low_num=0;//下限
            $.ajax({
                url: "<?=Url::to(['/purchase/purchase-before-work/ratio-info']);?>",
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    upp_num=data[0].upp_num;
                    low_num=data[0].low_num;
                }
            });
            var compareupp=parseFloat(parseFloat(prchnum*(1+parseFloat(upp_num))).toFixed(2));//比较的上限数量
            var comparelow=parseFloat(parseFloat(prchnum*(1-parseFloat(low_num))).toFixed(2));//比较的下限数量
            //当采购总数量超过限制数量时(采购数量小于下限或者大于上限)
            if ((parseFloat(_num.toFixed(2)) <comparelow) || (parseFloat(_num.toFixed(2))>compareupp)) {
                $("#prnums").val("");
                $(this).addClass('red-border');
                $("#MorethanNum").val("采购数量超过或不足限制数量");
                $(this).siblings('.numlen')[0].innerHTML = "采购数量超过或不足限制数量" + parseFloat(comparelow).toFixed(2)+"-"+parseFloat(compareupp).toFixed(2);
                if(_num==0)
                {
                    $(this).addClass('red-border');//数量等于空或0时添加红色错误边框提醒
                    $("#prnums").val("0");
                    _prices[0].innerHTML = "";//显示的单价用于显示数据
                    _prices.siblings('.pri').val("");//隐藏的单价用于保存数据
                    _totals.children('.amount').val("");//隐藏的总金额用于保存数据
                    _totals.children('.total')[0].innerHTML = "";//显示的总金额用于显示数据
                    _tdnum.siblings('.currency').find('.cur')[0].innerHTML = "";//显示的币别用于显示数据
                    _tdnum.siblings('.currency').find('.currency').val("");//隐藏的币别用于保存数据
                }
            }
            else {
                $("#MorethanNum").val("");//将超过采购数量的代表清空
                //输入的采购数量为空或为0
                if (_num > 0) {
                    $(this).removeClass('red-border');
                    $("#prnums").val("");//将保存为0为空为负的代表清空
                    $.ajax({
                        url: "<?=Url::to(['/purchase/purchase-before-work/price']);?>",
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
                            if (data) {
                                _prices[0].innerHTML = parseFloat(data.quote_price).toFixed(5);//显示的单价用于显示数据
                                _prices.siblings('.pri').val(parseFloat(data.quote_price).toFixed(5));//隐藏的单价用于保存数据
                                _totals.children('.amount').val(parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2));//隐藏的总金额用于保存数据
                                _totals.children('.total')[0].innerHTML = parseFloat(parseFloat(data.quote_price).toFixed(5) * _num).toFixed(2);//显示的总金额用于显示数据
                                _tdnum.siblings('.currency').find('.cur')[0].innerHTML = data.quote_currency;//显示的币别用于显示数据
                                _tdnum.siblings('.currency').find('.currency').val(data.currency);//隐藏的币别用于保存数据
                            }
                            else {
                                _prices[0].innerHTML = "";//显示的单价用于显示数据
                                _prices.siblings('.pri').val("");//隐藏的单价用于保存数据
                                _totals.children('.amount').val("");//隐藏的总金额用于保存数据
                                _totals.children('.total')[0].innerHTML = "";//显示的总金额用于显示数据
                                _tdnum.siblings('.currency').find('.cur')[0].innerHTML = "";//显示的币别用于显示数据
                                _tdnum.siblings('.currency').find('.currency').val("");//隐藏的币别用于保存数据
                            }
                        }
                    });
                }
                else {
                    $(this).addClass('red-border');//数量等于空或0时添加红色错误边框提醒
                    $("#prnums").val("0");
                    _prices[0].innerHTML = "";//显示的单价用于显示数据
                    _prices.siblings('.pri').val("");//隐藏的单价用于保存数据
                    _totals.children('.amount').val("");//隐藏的总金额用于保存数据
                    _totals.children('.total')[0].innerHTML = "";//显示的总金额用于显示数据
                    _tdnum.siblings('.currency').find('.cur')[0].innerHTML = "";//显示的币别用于显示数据
                    _tdnum.siblings('.currency').find('.currency').val("");//隐藏的币别用于保存数据
                }
//                else {
//                    $("#prnums").val("");
//                    $(".number").removeClass('red-border');
//                    var _total = _tdnum.siblings('.totalamount');//金额的td
//                    var _amount = _total.find('.amount');//隐藏的金额input
//                    var _totalspan = _total.find('.total');//显示金额的span
//                    var _price = _tdnum.siblings('.pricetax');//单价td
//                    var _pri = _price.find('.pri').val();//采购的单价(含税)
//                    _amount.val(parseFloat(_num * _pri).toFixed(2));//为隐藏的金额input赋值(根据输入的数量计算总金额)
//                    _totalspan[0].innerHTML = parseFloat(_num * _pri).toFixed(2);//为显示的金额input赋值(根据输入的数量计算总金额)
//                }
            }
        });
        //当采购数量为0时或大于原始数量鼠标移动到上面时提示
        $("#partspp").delegate('.number', 'mouseover', function () {
            $("button[type='submit']").removeAttr("disabled");
            var _num = parseFloat($(this).val());//当前输入的采购数量
            var prchnum = parseFloat($(this).siblings('.num').val());//采购的数量
            var upp_num=0;//上限
            var low_num=0;//下限
            $.ajax({
                url: "<?=Url::to(['/purchase/purchase-before-work/ratio-info']);?>",
                dataType: "json",
                type: "get",
                async: false,
                success: function (data) {
                    upp_num=data[0].upp_num;
                    low_num=data[0].low_num;
                }
            });
            var compareupp=parseFloat(parseFloat(prchnum*(1+parseFloat(upp_num))).toFixed(2));//比较的上限数量
            var comparelow=parseFloat(parseFloat(prchnum*(1-parseFloat(low_num))).toFixed(2));//比较的下限数量
            //当采购总数量超过限制数量时(采购数量小于下限或者大于上限)
            if ((parseFloat(_num.toFixed(2)) <comparelow) || (parseFloat(_num.toFixed(2))>compareupp)) {
                //输入的数量等于0或为空
                if ($(this).val() == 0) {
                    $("#numlen").css('display', 'none');//隐藏超过数量的提示框
                    $(this).myHoverTips('numInfo');//显示数量不能为0的提示框
                }
                else {
                    //$("#numInfo").css('display', 'none');//隐藏不为0的提示框
                    $(this).myHoverTips('numlen');//显示超过数量的提示框
                }
            }
            else {
                //没超过但是采购数量为0
                if ($(this).val() == 0) {
                    $("#numlen").css('display', 'none');//隐藏超过数量的提示框
                    $(this).myHoverTips('numInfo');//显示相关的提示框
                }
                else {
                    $("#numlen").css('display', 'none');//隐藏超过数量的提示框
                    $("#numInfo").css('display', 'none');//隐藏不为0的提示框
                }
            }
        });

        //税别/税率弹窗
        $(".searchtax").click(function () {
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
        var btnFlag = '';
        //保存
        $(".save-form").click(function () {
            var morethan = $("#MorethanNum").val();
            var prnum = $("#prnums").val();
            var flag = setstyle("delivc", "select-width");//交货条件为空验证
            var flag1 = setstyle("rate", "");//税别/税率为空验证
            var flag2 = setstyle("deldate", "");//交货时间为空验证
            var flag3 = setstyle("number", "");//采购数量为空验证
            if (flag > 0 || flag1 > 0 || flag2 > 0 || flag3 > 0) {
                return false;
            }
            if (morethan == "采购数量超过或不足限制数量" || prnum == "0") {
                layer.alert("采购数量超过或不足限制数量或采购数量必须大于0", {icon: 2});
                return false;
            }
            else {
                $("form").submit();
                var prch_id = $("#prch_id").val();
                btnFlag = $(this).text().trim();
                $("#add-form").attr('action', '<?=Url::to(['update'])?>?id=' + prch_id + '');
            }
        });
        //提交
        $(".apply-form").click(function () {
            var morethan = $("#MorethanNum").val();
            var prnum = $("#prnums").val();
            var flag = setstyle("delivc", "select-width");//交货条件为空验证
            var flag1 = setstyle("rate", "");//税别/税率为空验证
            var flag2 = setstyle("deldate", "");//交货时间为空验证
            var flag3 = setstyle("number", "");//采购数量为空验证
            if (flag > 0 || flag1 > 0 || flag2 > 0 || flag3 > 0) {
                return false;
            }
            if (morethan == "采购数量超过或不足限制数量" || prnum == "0") {
                layer.alert("采购数量超过或不足限制数量或采购数量必须大于0", {icon: 2});
                return false;
            }
            else {
                $("form").submit();
                var prch_id = $("#prch_id").val();
                btnFlag = $(this).text().trim();
                $("#add-form").attr('action', '<?=Url::to(['update'])?>?id=' + prch_id + '');
            }

        });
        //文本框验证
        $.fn.myHoverTips = function (divId) {
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
                        div.css("width", '230px');
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
                    var url = "<?=Url::to(['view'], true)?>?id=" + id;
                    var type = data.billTypeId;
                    //alert(type);
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
    //rows为税别/税率，data为关联收货中心
    function addTax(rows, data) {
        if (rows.length != 0) {
            _this1.siblings('.rate').val(rows[0].tax_no + "/" + (rows[0].tax_value * 100) + "%");
            _this1.siblings('.taxid').val(rows[0].tax_pkid);
        }
        if (data.length != 0) {
            _this2.siblings('.address').val(data[0].rcp_name);
            _this2.siblings('.whid').val(data[0].rcp_id);
        }
    }
    //对文本框进行验证
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