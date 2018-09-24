<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/11/27
 * Time: 下午 05:24
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
use yii\helpers\Url;
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
        width: 200px;
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
        width: 150px;
    }
    .value-width{
        width:200px !important;
    }
    .width300{width: 150px}
    .addline{width: 80px;height: 24px; margin-left: 0px; cursor: pointer;}
    /*.head-second + div {*/
        /*display: none;*/
    /*}*/
</style>

<h1 class="head-first" xmlns="http://www.w3.org/1999/html">
    <?= $this->title ?>
    <span style="color: white;float: right;font-size:12px;margin-right:20px">请购单号：<?=$model['req_no']?></span>
</h1>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<h2 class="head-second text-left">
    请购单信息
</h2>
<div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">请购形式<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['req_rqf'] ?></label>
        <label class="label-width qlabel-align width270">单据类型<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['req_dct'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">采购区域<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['area_id'] ?></label>
        <label class="label-width qlabel-align width270">所属法人<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['leg_id']?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">请购部门<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['spp_dpt_id'] ?></label>
        <label class="label-width qlabel-align width270">申请人<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['app_id'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20"><span class="red">*</span>联系方式<label>：</label></label>
        <input type="hidden" name="BsReq[contact]" value="<?= $model['contact'] ?>">
        <input class="label-width text-left width200 easyui-validatebox" data-options="required:'true',validType:'tel_mobile_c'" value="<?= $model['contact'] ?>" placeholder="请输入手机或座机号码" >
            <label class="label-width qlabel-align width270"><span class="red">*</span>配送地点<label>：</label></label>
            <input class="label-width text-left width200 easyui-validatebox" readonly="true" data-options="required:'true'" name="" id="_wh_name" value="<?= $model['qgaddr']?>" >
            <input type="hidden" class="_wh_code" name="BsReq[addr]" value="<?= $model['addr']?>"/>
            <span  class="icon-search width-20 cursor-pointer " onclick="send_addr()"></span>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">采购方式<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['req_type'] ?></label>
        <label class="label-width qlabel-align width270">费用类型<label>：</label></label>
        <select class="value-width value-align easyui-validatebox remove" data-options="required:'true'" name="BsReq[cst_type]" id="">
            <?php foreach ($downList['cst_type'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>" <?=$model['cst_type']==$val['bsp_svalue'] ? "selected" : null
                ?>><?=$val['bsp_svalue'] ?></option>
            <?php }?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">币别<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['cur_id'] ?></label>
        <label class="label-width qlabel-align width270">合同协议号<label>：</label></label>
        <input class="label-width text-left width200" maxlength="50" name="BsReq[agr_code]" value="<?= $model['agr_code'] ?>" >
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">e商贸部门<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['e_dpt_id'] ?></label>
        <label class="label-width qlabel-align width270">来源<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['scrce'] ?></label>
    </div>

        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20 ">是否领用人<label>：</label></label>
            <input type="hidden" class="ylrr" value="<?php $model['yn_lead']?>">
            <span class="value-width" style="width: 200px;">
                <input type="radio" value="1"  class="ynleader"
                       name="BsReq[yn_lead]" <?= $model['yn_lead'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="ynleader"
                       name="BsReq[yn_lead]" <?= $model['yn_lead'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
            <label class="label-width qlabel-align width270">多部门领用<label>：</label></label>
            <span class="value-width">
                <input type="radio" value="1" class="ismember_y" disabled="disabled"
                       name="" <?= $model['yn_mul_dpt'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="ismember_n" disabled="disabled"
                       name="BsReq[yn_mul_dpt]" <?= $model['yn_mul_dpt'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
        <?php if($model['yn_lead'] == 0){ ?>
            <div class="mb-10 fd yyyyy">
                <label class="label-width qlabel-align width100 ml-20 "><span class="red">*</span>领用人<label>：</label></label>
                <input type="hidden" name="" class="" value="">
                <input class="label-width text-left width200 _users"
                       data-options="validType:['tdSame','staffCode'],validateOnBlur:true,delay:1000000"
                       data-url="<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>"
                       name="" value="<?=$model['hrcode']?>--<?= $model['recer'] ?>" >
                <label class="label-width qlabel-align width270"><span class="red">*</span>联系电话<label>：</label></label>
                <input class="label-width text-left width200 easyui-validatebox _tel" data-options="validType:'tel_mobile_c'" name="" value="<?= $model['rec_cont'] ?>" >
            </div>
        <?php };?>

        </div>
        <div class="mb-10 fd _lyperson " style="display: none">
            <label class="label-width qlabel-align width100 ml-20 "><span class="red">*</span>领用人<label>：</label></label>
            <input type="hidden" name="BsReq[recer]" class="_users111" value="<?=$model['restid']?>" >
            <input class="label-width text-left width200 _users ynl"
                   data-options="validType:['tdSame','staffCode','nameSame'],validateOnBlur:true,delay:1000000"
                   data-url="<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>"
                   value="" >
            <label class="label-width qlabel-align width270"><span class="red">*</span>联系电话<label>：</label></label>
            <input class="label-width text-left width200 easyui-validatebox _tel" data-options="validType:'tel_mobile_c'" name="BsReq[rec_cont]" value="<?= $model['rec_cont'] ?>" >
        </div>
    <?php if($model['req_dct_id']!='109018'){ ?>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">总务备品<label>：</label></label>
            <span class="value-width" style="width: 200px;">
                <input type="radio" value="1" class="zw_y" disabled="disabled"
                       name="" <?= $model['yn_aff'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="zw_n" disabled="disabled"
                       name="" <?= $model['yn_aff'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
            <label class="label-width qlabel-align width270">是否三方贸易<label>：</label></label>
            <span class="value-width">
                <input type="radio" value="1" class="sf_y" disabled="disabled"
                       name="" <?= $model['yn_three'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_y" disabled="disabled"
                       name="" <?= $model['yn_three'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
        </div>
    <?php }else{ ?>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">物料归属<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['mtr_ass'] ?></label>
            <label class="label-width qlabel-align width270">是否三方贸易<label>：</label></label>
            <span class="value-width">
                <input type="radio" value="1" class="sf_y" disabled="disabled"
                       name="" <?= $model['yn_three'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_y" disabled="disabled"
                       name="" <?= $model['yn_three'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
            </span>
        </div>
    <?php } ?>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">是否设备部预算<label>：</label></label>
        <span class="value-width" style="width: 200px;">
                <input type="radio" value="1" class="zw_y" disabled="disabled"
                       name="" <?= $model['yn_eqp_budget'] == 1 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="zw_n" disabled="disabled"
                       name="" <?= $model['yn_eqp_budget'] == 0 ? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
        </span>
        <label class="label-width qlabel-align width300">是否已做低值易耗品判断<label>：</label></label>
        <span class="value-width">
                <input type="radio" value="1" class="sf_y" disabled="disabled"
                       name="" <?= $model['yn_low_value'] == 1? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_n" disabled="disabled"
                       name="" readonly <?= $model['yn_low_value'] == 0? "checked=checked" : null; ?> >
                <span class="vertical-middle">否</span>
        </span>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">是否做固资管控<label>：</label></label>
        <span class="value-width" style="width: 200px;">
                <input type="radio" value="1" class="zw_y" disabled="disabled"
                       name="" <?= $model['yn_fix_cntrl'] == 1? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="zw_n" disabled="disabled"
                       name="" <?= $model['yn_fix_cntrl'] == 0? "checked=checked" : null; ?>>
                <span class="vertical-middle">否</span>
        </span>
        <label class="label-width qlabel-align width270">是否来自需求单<label>：</label></label>
        <span class="value-width">
                <input type="radio" value="1" class="sf_y" disabled="disabled"
                       name="" <?= $model['yn_req'] == 1? "checked=checked" : null; ?>>
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_n" disabled="disabled"
                       name=""<?= $model['yn_req'] == 0? "checked=checked" : null; ?> >
                <span class="vertical-middle">否</span>
        </span>
    </div>
      <div class="mb-10">
          <?php if($model['req_rqf'] == "专案请购"){?>
          <label class="label-width qlabel-align width100 ml-20">专案代码<label>：</label></label>
          <label class="label-width text-left width200"><?= $model['prj_code']!=null ? $model['prj_code']:null  ?></label>
          <?php };?>
          <?php if($model['req_dct_id']!='109018'){ ?>
              <label class="label-width qlabel-align width100 ml-20"><span class="red">*</span>采购部门<label>：</label></label>
              <input type="hidden" id="_organization_id"  name="BsReq[req_dpt_id]" value="<?= $model['reque_id']?>">
              <input class="label-width text-left width200 pur_apart" readonly="true" id="_organization_name"  value="<?= $model['req_dpt_id']?>" >
              <span  class="icon-search width-20 cursor-pointer " onclick="purchurse_apar()"></span>
          <?php }?>
      </div>
    <div class="mb-10">
        <label class="label-width label-align vertical-top" style="margin-left: 30px;">请购原因/用途<label>：</label></label>
        <input type="hidden" name="BsReq[remarks]" value="<?= $model['remarks'] ?>">
        <textarea  style="width:643px;" name="BsReq[remarks]" id="member_businessarea"  cols="5" rows="3"
                   maxlength="200" onchange="this.value=this.value.substring(0, 200)" placeholder="最多输入200个字"><?= $model['remarks'] ?></textarea>
    </div>
    <?php if($model['yn_can']!=0){?>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">取消原因<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['can_rsn'] ?></label>
        </div>
    <?php } ?>
</div>
<h2 class="head-second text-left mt-30">
    商品信息 <span class="text-right float-right">
                <a id="select_product" title="添加">添加</a>
                <a id="delete_product" title="删除">删除</a></span>
</h2>
<div class="mb-20" style="overflow: auto">
<!--    <div class="mb-10">-->
<!--        <label class="label-width qlabel-align width50 ml-20">总金额<label>：</label></label>-->
<!--        <input type="hidden" name="BsReq[t_amount_addfax]" value="--><?//= $model['t_amount_addfax'] ?><!--" class="_totalallprice">-->
<!--        <span style="color: red" class="_totalallprice">--><?//= sprintf("%.2f",$model['t_amount_addfax'] )?><!--</span>-->
<!--    </div>-->
    <div style="width:100%;overflow: auto;">
        <table class="table" style="width: 1400px;">
            <thead>
                <th><p style="width:40px; ">序号</p></th>
                <th><p style="width:40px;"><input type="checkbox" id="checkAll"></p></th>
                <th><p style="width:150px"><span class="red">*</span>料号</p></th>
                <th><p style="width:150px">品名</p></th>
                <th><p style="width:150px">规格</p></th>
                <th><p style="width:150px">品牌</p></th>
                <th><p style="width:150px">单位</p></th>
<!--                <th><p style="width:150px">库存量</p></th>-->
                <th><p style="width:150px"><span class="red">*</span>请购量</p></th>
<!--                <th><p style="width:150px">单价</p></th>-->
<!--                <th><p style="width:150px">供应商代码</p></th>-->
<!--                <th><p style="width:150px">金额</p></th>-->
                <th><p style="width:150px">费用科目</p></th>
                <th><p style="width:150px"><span class="red">*</span>需求交期</p></th>
                <th><p style="width:150px">专案编号</p></th>
<!--                <th><p style="width:150px">剩余预算</p></th>-->
<!--                <th><p style="width:150px">原币单价</p></th>-->
<!--                <th><p style="width:150px">退税率</p></th>-->
                <th><p style="width:150px">备注</p></th>
                <th><p style="width:150px">操作</p></th>
            </thead>
            <tbody id="product_table" class="_product_table">
            <?php foreach ($pdtmodel as $key => $val) { ?>
                <tr style="height: 32px;">
                    <td><?= ($key + 1) ?></td>
                    <td><input type="checkbox"></td>
                    <td><input type='text' class='_partno easyui-validatebox partnos'
                               data-options="required:'true',validateOnBlur:true,validType:'productCodeValidate'"
                               name='prod[<?= ($key) ?>][BsReqDt][part_no]' value="<?= $val["part_no"] ?>"></td>
                    <td><?= $val["pdt_name"] ?></td>
                    <td><?= $val["tp_spec"] ?></td>
                    <td><?= $val["brand"] ?></td>
                    <td><?= $val["unit"] ?></td>
<!--                    <td>--><?//= $val["invt_num"] ?><!--</td>-->
                    <td><input type='text' maxlength="10" name='prod[<?= ($key) ?>][BsReqDt][req_nums]'  value='<?= $val["req_nums"] ?>' placeholder='请输入数量' class='_num easyui-validatebox ' data-options="required:'true',validType:'intnum'" ></td>
<!--                    <td><span class="_price">--><?//= sprintf("%.5f",$val["req_price"]) ?><!--</span><input type='hidden' name='prod[--><?//= ($key) ?><!--][BsReqDt][req_price]' class='subpri'   value="--><?//= $val['req_price']?><!--" ></td>-->
<!--                    <td><span class='_spp'></span><input type='hidden' name='' class='subspp'>--><?//= $val["spp_id"] ?><!--</td>-->
<!--                    <td><span class='totalspan'>--><?//= sprintf("%.2f",$val["total_amount"]) ?>
<!--                        </span><input type='hidden' name='prod[--><?//= ($key) ?><!--][BsReqDt][total_amount]' class='_total' value="--><?//= $val["total_amount"] ?><!--"></td>-->
                    <td><input type='hidden' name='prod[<?= ($key) ?>][BsReqDt][exp_account]' value=''><?= $val["bs_req_dt"] ?></td>
                    <td><input onclick='choicetime()' type='text' readonly='true' name='prod[<?= ($key) ?>][BsReqDt][req_date]' class='_choicetime easyui-validatebox' data-options="required:'true'" value="<?= $val["req_date"] ?>"></td>
                    <td><input type='hidden' name='prod[<?= ($key) ?>][BsReqDt][prj_no]' ><?= $val["prj_no"] ?></td>
<!--                    <td>--><?//= $val["sonl_remark"] ?><!--</td>-->
<!--                    <td><input type='hidden' name='prod[--><?//= ($key) ?><!--][BsReqDt][org_price]' >--><?//= $val["org_price"] ?><!--</td>-->
<!--                    <td><input type='hidden' name='prod[--><?//= ($key) ?><!--][BsReqDt][rebat_rate]'>--><?//= $val["rebat_rate"] ?><!--</td>-->
                    <td><input type='text' name='prod[<?= ($key) ?>][BsReqDt][remarks]' placeholder="最多输入100字" maxlength='100' class='_remarks' value="<?= $val["remarks"] ?>"></td>
                    <td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 20px;"></div>
<p class="text-left mb-20">
    <input type="button" class="icon-plus button-white-big addline" onclick="add_product()" value="+ 添加行">
</p>
<div style="margin-bottom: 40px;"></div>
<div style="text-align:center;">
    <button class="button-blue-big" type="submit">保存</button>
    <button class="button-blue-big" type="submit" style="margin-left:40px;">提交</button>
    <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
</div>
<?php ActiveForm::end()?>

<script>
    $(function () {
        //重新验证初始化
        $.extend($.fn.validatebox.defaults.rules, {
            //判断料号是否存在
            productCodeValidate: {
                validator: function (value) {
                    var data = $.ajax({
                        url: '<?=Url::to(['get-purchase-products'])?>',
                        data: {
                            'id': value
                        },
                        async: false,
                        cache: false
                    }).responseText;
                    data = JSON.parse(data);
//                    console.log(data.rows);
                    var tr = $(this).parent().parents("tr");
                    if (data.rows == '') {
                        return false;
                    }
                    else {
                        tr.find("._partno").addClass("partnos");
                        tr.find("td").eq(3).text(data.rows[0].pdt_name);
                        tr.find("td").eq(4).text(data.rows[0].tp_spec);
                        tr.find("td").eq(5).text(data.rows[0].brand);
                        tr.find("td").eq(6).text(data.rows[0].unit);
                        return true;
                    }
                },
                message: '料号不存在'
            },
            //判断是否重复料号
            tdSame: {
                validator: function (value) {
                    var trs = $(this).parents("tr").siblings();
                    var tr = $(this).parents("tr");
                    $.each(trs, function (i, n) {
                        if ($(n).find("._partno").val() != undefined) {
                            if ($(n).find("._partno").val().toUpperCase() == $.trim(value).toUpperCase()) {
                                trs = 'same';
                                tr.find("td").eq(3).text("");
                                tr.find("td").eq(4).text("");
                                tr.find("td").eq(5).text("");
                                tr.find("td").eq(6).text("");
                                return false;
                            }
                        }
                    });
                    return trs != 'same';
                },
                message: '该料号商品已存在'
            },
            //判断工号不能相同
            nameSame:{
                validator: function(value, param){
                    var a = $('.applyperson').val();
                    var b = $(this).val();
                    if(a === b){
                        $.fn.validatebox.defaults.rules.nameSame.message = '工号与申请人重复';
                    }
                    return a != b;
                },
                message: '工号与申请人重复'
            }
        });

        $("#product_table ._partno").validatebox({
            required: true,
            validType: ["tdSame", "productCodeValidate"],
            delay: 1000000
        });


        //提交
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
            $("#product_table").find("tr").each(function () {
                var part=$(this).find("._partno").val();
                var num=$(this).find("._num").val();

                var _time=$(this).find("._choicetime").val();
                if(part==""||num==""||_time==""||num<=0) {
                    if(part==""){
                        $(this).find("._partno").addClass("validatebox-invalid");
                    }else if(num==""||num<=0) {
                        $(this).find("._num").addClass("validatebox-invalid");
                    }else if(_time=="") {
                        $(this).find("._choicetime").addClass("validatebox-invalid");
                    }
                    return false;
                }else {
                    $(this).find("._partno").removeClass("validatebox-invalid");
                    $(this).find("._num").removeClass("validatebox-invalid");
                    $(this).find("._choicetime").removeClass("validatebox-invalid");
                }
            });
        });
        $(".Onlynum").numbervalid();
        ajaxSubmitForm("form","",function(data){
            if (data.flag == 1) {
                if(btnFlag == '提交'){
                    var id=data.billId;
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=data.billTypeId;
                    $.fancybox({
                        href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:750,
                        height:480,
                        afterClose:function(){
                            location.href="<?=Url::to(['view'])?>?id="+id;
                        }
                    });
                }else{
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
                if((typeof data.msg)=='object'){
                    layer.alert(JSON.stringify(data.msg),{icon:2});
                }else{
                    layer.alert(data.msg,{icon:2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });
        //修改收縮樣式修改
        $(".head-second").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
            $(".retable").datagrid("resize");
        });
        //当选择不是领用人时弹框
        $('input:radio[name="BsReq[yn_lead]"]').change(function(){
             if($(this).is(":checked")) {
//                 alert($(this).val());
                 var la = $(this).val();
                 if (la == 0) {
                     $("._lyperson").show();
                     $('._users').validatebox({required: true, validType: ['tdSame','staffCode','nameSame'],validateOnBlur:true,delay:1000000});
                     $('._tel').validatebox({required: true, validType: 'tel_mobile_c'});
                 } else {
                     $("._lyperson").hide();
                     $(".yyyyy").hide();
                     $("._users111").val("");
                     $("._tel").val("");
                     $("._users").validatebox({required: false});
                     $("._tel").validatebox({required: false});
                 }
             }
        });
        //选择商品
        $("#select_product").click(function(){
            //排除已选中的商品
            var $selectedRows=$("#purpdt_tbody").find("input");
            var selectedId='';
            if($selectedRows.length > 0){
                $.each($selectedRows,function(i,n){
                    if(n.value != ''){
                        selectedId+=n.value+',';
                    }
                });
                selectedId=selectedId.substr(0,selectedId.length-1);
            }
            $.fancybox({
                width:720,
                height:500,
                padding:[],
                autoSize:false,
                type:"iframe",
                href:"<?=\yii\helpers\Url::to(['/purchase/purchase-apply/prod-select'])?>?filters="+selectedId
            });
        });
    });
    //采购部门
    function purchurse_apar() {
        var url;
        url = '<?= Url::to(['select-depart']) ?>';
        $.fancybox({
            autoScale: true,
            padding: [],
            fitToView: false,
            width: 520,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: url
        });

    }
    //点击配送地点后面搜索图标弹框
    function send_addr() {
        var url;
        url = '<?= Url::to(['select-place']) ?>';
        $.fancybox({
//            autoScale: true,
//            padding: [],
            fitToView: false,
            width: 520,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: url
        });
    }
    //拟采购商品
    var purpdtIndex=$("._product_table").find("tr").length;
    //添加一行tr
    function addPurpdt(){
        var trStr="<tr>";
        trStr+="<td></td>";
        trStr+="<td><input type='checkbox'></td>";
        trStr+="<td><input type='hidden'  class='pkid'><input type='text' " +
            "class='easyui-validatebox  _partno'  name='prod["+purpdtIndex+"][BsReqDt][part_no]' " +
            "data-options=\"required:'true',validateOnBlur:true\" ></td>"; //
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
//        trStr+="<td></td>";
        trStr+="<td><input type='text' maxlength='10' name='prod["+purpdtIndex+"][BsReqDt][req_nums]' value='' placeholder='请输入数量' class='_num easyui-validatebox' data-options=\"required:'true',validType:'intnum'\"></td>";
//        trStr+="<td><span class='_price'></span><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][req_price]' class='subpri'></td>";
//        trStr+="<td><span class='_spp'></span><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][spp_id]' class='subspp'></td>";
//        trStr+="<td><span class='totalspan'></span><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][total_amount]' class='_total'></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][exp_account]' value=''></td>";
        trStr+="<td><input onclick='choicetime()' type='text' readonly='true' name='prod["+purpdtIndex+"][BsReqDt][req_date]' class='_choicetime easyui-validatebox ' data-options=\"required:'true'\"></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][prj_no]' ></td>";
//        trStr+="<td></td>";
//        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][org_price]' ></td>";
//        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][rebat_rate]'></td>";
        trStr+="<td><input type='text' name='prod["+purpdtIndex+"][BsReqDt][remarks]' maxlength='100' placeholder=\"最多输入100字\" class='_remarks'></td>";
        trStr+="<td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a></td>";
        trStr+="</tr>";
        var obj = $("#product_table").append(trStr).find("tr").each(function(index){
            $(this).find("td:first").html(index+1);
        });
        $.parser.parse(obj);
        purpdtIndex++;
        $("#product_table ._partno ").validatebox({
            required: true,
            validType: ["tdSame", "productCodeValidate"],
            delay: 1000000
        });
    }
    //获取时间
    function choicetime() {
        WdatePicker({
            skin:"whyGreen",
            dateFmt:"yyyy-MM-dd",
            minDate:'%y-%M-{%d+1}',
            onpicked:function(){
                $(this).validatebox('validate');
            }
        });
    }
    function purpdtVal(rows){
        var _partno="";
        var obj2;
        $.each(rows,function(i,n){
            _partno+=n.part_no+",";
        });
        obj2=_partno.split(",");
        if(obj2!=""&&obj2!=null)
        {
            $.ajax({
                url:"<?=Url::to(['get-purchase-product'])?>",
                data:{"id":obj2},
                dataType:"json",
                success:function(data){
                    var arr=new Array();
                    $("#product_table").find("tr").each(function () {
                        var par=$(this).find("._partno").val();
                        arr.push(par);
                    });
                    $("#product_table").find("tr").each(function () {
                        var ls=$(this).find("._partno").val();
                        if(ls=="") {
                            $(this).remove();
                        }
                    });
                    for(var i=0;i<data.rows.length;i++)
                    {
                        $.each(data.rows[i],function(){
                            for (var j=0;j<data.rows[i].length;j++)
                            {
                                if((arr.indexOf(data.rows[i][j].part_no))<0)
                                {
//                                    var ss=data.rows[i][j].price;
//                                    str = ss.substr(0,ss.length-1);
                                    addPurpdt();
                                    var $trLast=$("#product_table").find("tr:last");
                                    //$trLast.find("input").val(data.rows[i][j].prt_pkid);
                                    $trLast.find("._partno").val(data.rows[i][j].part_no);
                                    $trLast.find("._partno").addClass("partnos");
                                    $trLast.find("td:eq(3)").text(data.rows[i][j].pdt_name);
                                    $trLast.find("td:eq(4)").text(data.rows[i][j].tp_spec);
                                    $trLast.find("td:eq(5)").text(data.rows[i][j].brand);
                                    $trLast.find("td:eq(6)").text(data.rows[i][j].unit);
//                                    $trLast.find("td:eq(7)").text(data.rows[i][j].invt_num);
                                    $trLast.find("._num").val("");
//                                    $trLast.find("._price").text(str);
//                                    $trLast.find(".subpri").val(data.rows[i][j].price);
//                                    $trLast.find("._spp").text(data.rows[i][j].spp_code);
//                                    $trLast.find(".subspp").val(data.rows[i][j].spp_id);
                                    $trLast.find("td:eq(10)").text("");
                                    $trLast.find("._choicetime").val("");
                                    $trLast.find("td:eq(12)").text("");
//                                    $trLast.find("td:eq(13)").text("");
//                                    $trLast.find("td:eq(14)").text("");
//                                    $trLast.find("td:eq(15)").text("");
                                    $trLast.find("._remarks").val("");
                                    $trLast.find("td:last").append("<a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a>");
                                }else {
                                    layer.alert("料号"+data.rows[i][j].part_no+"已经添加过了,请重新选择",{icon:2});
                                }
                            }
                        })
                    }
                }
            })
        }
    }
    //调用函数
    function productSelectorCallback(rows) {
        purpdtVal(rows);
    }
    //+添加商品
    function add_product() {
        addPurpdt();
    }
    //删除
    $(document).on("click",".icon-remove",function(){
        var $tbody=$(this).parents("tbody");
        var _len=$tbody.find("tr").length;
        if(_len>1){
            $(this).parents("tr").remove();
            $tbody.find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        }
    });
    //重置
    $(document).on("click",".icon-repeat",function(){
        $(this).parents("tr").find("input").val('');
        $(this).parents("tr").find("td").eq(3).text('');
        $(this).parents("tr").find("td").eq(4).text('');
        $(this).parents("tr").find("td").eq(5).text('');
        $(this).parents("tr").find("td").eq(6).text('');
//        $(this).parents("tr").find("td").eq(7).text('');
        //$(this).parents("tr").find("._price").text('');
       // $(this).parents("tr").find("td").eq(10).text('');
        //$(this).parents("tr").find(".totalspan").text('');
    });
    //全选
    $(document).on("click","#checkAll",function () {
        if ($(this).is(":checked")) {
            $('.table').find("td input[type='checkbox']").prop("checked", true);
        } else {
            $('.table').find("td input[type='checkbox']").prop("checked", false);
        }
    });
    //批量删除
    $("#delete_product").on('click', function () {
        $('#product_table input:checkbox:checked').each(function () {
            var $tbody=$(this).parents("tbody");
            var _length=$tbody.find("tr").length;
            if(_length>1){
                $(this).parents("tr").remove();
                $tbody.find("tr").each(function(index){
                    $(this).find("td:first").html(index+1);
                });
            }
        });
    });
    //单笔料号新增
//    $(document).on("change", "._partno", function () {
//        var $pdt_no = $(this);
//        var arrayObj = new Array();
//        $pdt_no.validatebox();        //验证初始化
//        var pdt_no = $(this).val();
//        arrayObj.push(pdt_no);
//        var row = $(this).parent().prev().prev().find("span").html();//行数
//        var url = "<?//= Url::to(['get-purchase-product'])?>//";
//        var arr=new Array();
//        $.ajax({
//            type: 'GET',
//            dataType: 'json',
//            data: {"id": arrayObj},
//            url: url,
//            beforeSend:function () {
//                $("#product_table").find("tr").each(function () {
//                    var par=$(this).find(".partnos").val();
//                    arr.push(par);
//                });
//            },
//            success: function (data) {
//                var arr=new Array();
//                $("#product_table").find("tr").each(function () {
//                    var par=$(this).find(".partnos").val();
//                    arr.push(par);
//                });
//                if(data.rows[0].length>0)
//                {
//                    if((arr.indexOf(data.rows[0][0].part_no))<0) {
//                        $pdt_no.parent().parent().find("._partno").addClass("partnos");
//                        $pdt_no.parent().parent().find("td").eq(3).text(data.rows[0][0].pdt_name);
//                        $pdt_no.parent().parent().find("td").eq(4).text(data.rows[0][0].tp_spec);
//                        $pdt_no.parent().parent().find("td").eq(5).text(data.rows[0][0].brand);
//                        $pdt_no.parent().parent().find("td").eq(6).text(data.rows[0][0].unit);
//                    }else {
//                        layer.alert("料号"+data.rows[0][0].part_no+"已经添加过了,请重新选择",{icon:2});
//                    }
//                }else {
//                    layer.alert("未找到该料号!", {icon: 2});
//                    $pdt_no.parent().parent().find("td").eq(3).text("");
//                    $pdt_no.parent().parent().find("td").eq(4).text("");
//                    $pdt_no.parent().parent().find("td").eq(5).text("");
//                    $pdt_no.parent().parent().find("td").eq(6).text("");
//                }
//            },
//            error: function (data) {
//                layer.alert("请求错误!", {icon: 2});
//            }
//        })
//    });

    //领用人
    $(document).on("change","._users",function () {
        $staffcode=$("._users").val();
//        alert($staffcode);
        var url = "<?= Url::to(['get-staff-info'])?>?code="+$staffcode;
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code":$staffcode},
            url: url,
            success: function (data) {
                if(data!=false){
                    var ret = data.staff_code+"--"+data.staff_name;
                    $("._users").val(ret);
                    $("._tel").val(data.staff_mobile);
                    $("._tel").removeClass("validatebox-invalid");
                    $("._tel").attr("data-options","false");
                    $("._users111").val(data.staff_id);
                }else {
                    //layer.alert("没有查到此工号",{icon: 2});
//                    $('._users').validatebox({required: true, validType: ['tdSame','staffCode'],validateOnBlur:true,delay:1000000});
                }
            }
        })
    });
    //领用电话
    $(document).on("change","._tel",function () {
        $('._tel').validatebox({required: true, validType: 'tel_mobile_c'});
    });
    //采购部门模糊搜索
    $(document).on("change",".pur_apart",function () {
        var _text=$(".pur_apart").val().trim();
        if(_text==""){
            $(".pur_apart").validatebox({required: true});
        }
        $.fancybox({
            autoScale: true,
            padding: [],
            fitToView: false,
            width: 520,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: '<?= Url::to(['select-depart']) ?>?keyWord='+_text
        });
    });

</script>





