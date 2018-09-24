<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2018/1/31
 * Time: 上午 10:04
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
use yii\helpers\Url;
$this->title = '新增请购单';

$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '请购单列表', 'url' => "index"];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style>
    thead tr th p {
        color: white;
    }
    .value-width{
        width:200px !important;
    }
    .label-width{
        width:100px;
    }
    .label-widths{
        width:160px;
    }
    .ml-220{
        margin-left: 130px;
    }
    .ml-230{
        margin-left: 70px;
    }
    .add-bottom {
        margin-bottom: 5px;
    }
    .reds{margin-left: 176px;color: #ff0000;}
    .addline{width: 80px;height: 24px; margin-left: 0px; cursor: pointer;}
    ._zadm{width: 400px;float: left}
    ._psss{width: 400px;}
</style>
<div class="content">
<h1 class="head-first" xmlns="http://www.w3.org/1999/html">
    <?= $this->title ?>
</h1>
<h2 class="head-second text-left">
    请购单信息
</h2>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="ml-10">
    <div class="mb-10">
        <label class="label-width label-align add-bottom"><span class="red">*</span>请购形式</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove qgxs" data-options="required:'true'" name="BsReq[req_rqf]" id="">
            <option value="">请选择...</option>
            <?php foreach ($downList['req_rqf'] as $key => $val) { ?>
                <option  value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align">单据类型</label><label>:</label>
        <input type="hidden" name="BsReq[req_dct]" value="109016">
        <span>具体料号请购</span>
<!--        <select class="value-width value-align easyui-validatebox remove djlx" data-options="required:'true'" name="BsReq[req_dct]" >-->
<!--            <option value="">请选择...</option>-->
<!--            --><?php //foreach ($downList['req_dct'] as $key => $val) { ?>
<!--                <option  value="--><?//= $val['bsp_id'] ?><!--">--><?//= $val['bsp_svalue'] ?><!--</option>-->
<!--            --><?php //} ?>
<!--        </select>-->
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom"><span class="red">*</span>采购区域</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove" data-options="required:'true'" name="BsReq[area_id]" id="">
            <option value="">请选择...</option>
            <?php foreach ($downList['area_id'] as $key => $val) { ?>
                <option value="<?= $val['factory_id'] ?>"><?= $val['factory_name'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align">&nbsp;&nbsp;所属法人</label><label>:</label>
        <span class="value-width value-align"  id="memberType"><?=$fr ?></span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">&nbsp;&nbsp;请购部门</label><label>:</label>
        <span class="value-width value-align" id="memberType"><?= $u['organization_name'] ?>
            <input type="hidden" name="BsReq[spp_dpt_id]" value="<?=$u['organization_id'] ?>">
            </span>
        <label class="ml-220 label-width label-align">&nbsp;&nbsp;申请人</label><label>:</label>
        <input type="hidden" class="applyperson" value="<?= $u['staff_code']?>">
        <span class="value-width value-align" id="memberType"><?= $u['staff_code']?>--<?=$u['staff_name'] ?></span>
    </div>
    <div class="mb-10 psaddr">
        <?php if($u['staff_mobile']==""){
            $mobel=$u['staff_tel'];
        }else{
            $mobel=$u['staff_mobile'];
        }?>
        <label class="label-width label-align add-bottom"><span class="red">*</span>联系方式</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" type="text"
               name="BsReq[contact]" data-options="required:'true',validType:'tel_mobile_c'"
               value="<?= $mobel ?>" placeholder="请输入手机或座机号码">
        <span class="reds">*</span></label><label>配送地点</label><label>:</label>
        <input type="hidden" class="_wh_code" name="BsReq[addr]">
        <input class="value-width value-align easyui-validatebox" data-options="required:'true'" maxlength="60" id="_wh_name" readonly="true" >
        <span  class="icon-search width-20 cursor-pointer " onclick="send_addr()"></span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom"><span class="red">*</span>采购方式</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove" data-options="required:'true'" name="BsReq[req_type]" id="">
            <option value="">请选择...</option>
            <?php foreach ($downList['req_type'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align"><span class="red">*</span>费用类型</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove" data-options="required:'true'" name="BsReq[cst_type]" id="">
            <option value="">请选择...</option>
            <?php foreach ($downList['cst_type'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">币别</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove" data-options="required:'true'" name="BsReq[cur_id]" id="">
            <?php foreach ($downList['cur_id'] as $key => $val) { ?>
                <option value="<?= $val['cur_id'] ?>"><?= $val['cur_code'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align">合同协议号</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" maxlength="50" id=""
               name="BsReq[agr_code]" >
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">e商贸部门</label><label>:</label>
        <label class="value-width value-align easyui-validatebox" maxlength="60" id=""
               name="BsReq[e_dpt_id]" value=""></label>
        <label class="ml-220 label-width label-align">来源</label><label>:</label>
        <label class="value-width value-align easyui-validatebox" maxlength="60" id=""
               name="BsReq[scrce]" value=""></label>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">是否领用人</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" checked="checked"
                       name="BsReq[yn_lead]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0"
                       name="BsReq[yn_lead]" >
                <span class="vertical-middle">否</span>
            </span>
        <label class="ml-220 label-width label-align">多部门领用</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="ismember_y"
                       name="BsReq[yn_mul_dpt]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="ismember_n" checked="checked"
                       name="BsReq[yn_mul_dpt]" >
                <span class="vertical-middle">否</span>
            </span>
    </div>
    <div class="mb-10 _lyperson" style="display: none" >
        <label class="label-width label-align add-bottom"><span class="red">*</span>领用人</label><label>:</label>
        <input type="hidden" name="BsReq[recer]" class="_users111">
        <input class=" value-align _users"
               data-options="validType:['tdSame','staffCode','nameSame'],validateOnBlur:true,delay:1000000" data-url="<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>"
               style="width: 200px;"
               type="text" name="" placeholder="请输入工号"
               value="" >
        <label class="ml-220 label-width label-align"><span class="red">*</span>联系方式</label><label>:</label>
        <input class="value-align _tel"  type="text" style="width: 200px;"
               data-options="validType:'tel_mobile_c'"
               name="BsReq[rec_cont]"
               value="" placeholder="请输入手机或座机号码">
    </div>
    <div class="mb-10 _wlgs" style="display: none">
        <label class="label-width label-align add-bottom">物料归属</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove" name="BsReq[mtr_ass]" id="">
            <?php foreach ($downList['mtr_ass'] as $key => $val) { ?>
                <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align">是否三方贸易</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="sf_yv"
                       name="BsReq[yn_three]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_nv"
                       name="BsReq[yn_three]" >
                <span class="vertical-middle">否</span>
            </span>
    </div>
    <div class="mb-10 _zwbp">
        <label class="label-width label-align add-bottom">总务备品</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="zw_y"
                       name="BsReq[yn_aff]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="zw_n" checked="checked"
                       name="BsReq[yn_aff]" >
                <span class="vertical-middle">否</span>
            </span>
        <label class="ml-220 label-width label-align">是否三方贸易</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="sf_y"
                       name="BsReq[yn_three]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_n" checked="checked"
                       name="BsReq[yn_three]" >
                <span class="vertical-middle">否</span>
            </span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">是否设备部预算</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="zw_y"
                       name="BsReq[yn_eqp_budget]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="zw_n" checked="checked"
                       name="BsReq[yn_eqp_budget]" >
                <span class="vertical-middle">否</span>
            </span>
        <label class="ml-230 label-widths label-align">是否已做低值易耗品判断</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="sf_ys"
                       name="BsReq[yn_low_value]">
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_ns" checked="checked"
                       name="BsReq[yn_low_value]" >
                <span class="vertical-middle">否</span>
            </span>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">是否做固资管控</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="zw_y"
                       name="BsReq[yn_fix_cntrl]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="zw_n" checked="checked"
                       name="BsReq[yn_fix_cntrl]" >
                <span class="vertical-middle">否</span>
            </span>
        <label class="ml-220 label-width label-align">是否来自需求单</label><label>:</label>
        <span class="value-width">
                <input type="radio" value="1" class="sf_yx"
                       name="BsReq[yn_req]" >
                <span class="vertical-middle">是</span>
                <input type="radio" value="0" class="sf_nx" checked="checked"
                       name="BsReq[yn_req]" >
                <span class="vertical-middle">否</span>
            </span>
    </div>
    <div class="mb-10 mmppp" style="float: left">
        <div class="mb-10 _zadm" style="display: none">
            <label class="label-width label-align add-bottom">专案代码</label><label>:</label>
            <input class="value-width value-align easyui-validatebox _ahan" maxlength="50" id=""
                   name="BsReq[prj_code]" readonly="true"
                   data-options="" value="">
            <span class="icon-search width-20 cursor-pointer _zadmss"></span>
        </div>
        <div class="mb-10 _psss" style="float: left">
            <label class="label-width label-align add-bottom"><span class="red">*</span>采购部门</label><label>:</label>
            <input class="value-width value-align easyui-validatebox pur_apart" data-options="required:'true'" maxlength="60" id="_organization_name" readonly="true"
                   value="">
            <input type="hidden" id="_organization_id" name="BsReq[req_dpt_id]" value="">
            <span  class="icon-search width-20 cursor-pointer " onclick="purchurse_apar()"></span>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="label-width label-align vertical-top">请购原因/用途 :</label>
            <textarea  style="width:643px;" name="BsReq[remarks]" id="member_businessarea"  cols="5" rows="3"
                       maxlength="200" onchange="this.value=this.value.substring(0, 200)" placeholder="最多输入200个字"><?= $model['member_businessarea'] ?></textarea>
        </div>
    </div>
</div>


<h2 class="head-second text-left mt-30">
    商品信息 <span class="text-right float-right">
<!--                <a id="select_product" title="添加">添加</a>-->
<!--                <a id="delete_product" title="删除">删除</a></span>-->
</h2>
<div class="mb-20 tablescroll" style="overflow-x: scroll">
    <table class="table" style="width: 1500px;">
        <thead>
        <tr>
            <th><p style="width:40px; ">序号</p></th>
            <th><p style="width:40px;"><input type="checkbox" id="checkAll"></p></th>
            <th><p style="width:150px"><span class="red">*</span>料号</p></th>
            <th><p style="width:150px">品名</p></th>
            <th><p style="width:150px">规格</p></th>
            <th><p style="width:150px">品牌</p></th>
            <th><p style="width:150px">单位</p></th>
            <th><p style="width:150px"><span class="red">*</span>请购量</p></th>
            <th><p style="width:150px">费用科目</p></th>
            <th><p style="width:150px"><span class="red">*</span>需求交期</p></th>
            <th><p style="width:150px">专案编号</p></th>
            <th><p style="width:150px">备注</p></th>
            <th><p style="width:150px">操作</p></th>
            <?php foreach ($columns as $key => $val) { ?>
                <th><p class="text-center width-150 "><?= $val["field_title"] ?></p></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody id="product_table">
        <?php foreach ($result as $key => $val) { ?>
        <tr>
            <td><?= ($key + 1) ?></td>
            <td><input type="checkbox"></td>
            <td><input type='text' class='_partno easyui-validatebox' name='prod[<?= ($key) ?>][BsReqDt][part_no]' value="<?= $val["part_no"] ?>" data-options="required:'true',validateOnBlur:true,validType:'productCodeValidate'"></td>
            <td><?= $val["pdt_name"] ?></td>
            <td><?= $val["tp_spec"] ?></td>
            <td><?= $val["brand"] ?></td>
            <td><?= $val["unit_name"] ?></td>
            <td><input type='text'  name='prod[<?= ($key) ?>][BsReqDt][req_nums]'  placeholder='请输入数量' class='_num easyui-validatebox ' maxlength="10" data-options="required:'true',validType:'intnum'" value="<?= sprintf("%.2f",$val['sapl_quantity'])?>"></td>
            <td><input type='hidden' name='prod[<?= ($key) ?>][BsReqDt][exp_account]' value=''></td>
            <td><input onclick='choicetime()' type='text' readonly data-options="required:'true'"
                       name='prod[<?= ($key) ?>][BsReqDt][req_date]' class='_choicetime easyui-validatebox' value="<?= $val['request_date']?>"></td>
            <td><input type='hidden' name='prod[<?= ($key) ?>][BsReqDt][prj_no]' ></td>
            <td><input type='text' name='prod[<?= ($key) ?>][BsReqDt][remarks]' maxlength='100' class='_remarks' placeholder="最多输入100字" ></td>
            <td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a></td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div style="margin-top: 20px;"></div>
<!--<p class="text-left mb-20">-->
<!--    <input type="button" class="icon-plus button-white-big addline" onclick="add_product()" value="+ 添加行">-->
<!--</p>-->
<div style="margin-top: 40px;"></div>
<div style="text-align:center;">
    <button class="button-blue-big" type="submit" id="save">保存</button>
    <button class="button-blue-big" type="submit" style="margin-left:40px;">提交</button>
    <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
</div>
<?php ActiveForm::end(); ?>
</div>
<script>
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
        //采购部门与配送地点之间的切换
        $(".psdd").text("采购部门");
        $('.djlx').change(function(){
            var p1=$(this).children('option:selected').val();
//            alert(p1);
            if(p1=="109018"){
                //$(".psaddr").removeAttr("style");         //配送地址
//                $("#_organization_name").removeClass("validatebox-text validatebox-invalid");
                $("._psss").css("display","none");        //采购部门
                $(".pur_apart").validatebox({required:false});
                $("._wlgs").removeAttr("style");
                $(".sf_y").removeAttr("name");
                $(".sf_n").removeAttr("name");
                $(".sf_nv").attr("checked",true);
                $("._zwbp").css("display","none");
            }else {
                //$(".psaddr").css("display","none");      //配送地址
                //$("#_wh_name").removeClass("validatebox-text validatebox-invalid");
                $("._psss").css("display","block");      //采购部门
                $(".pur_apart").validatebox({required: true});
                $("._wlgs").css("display","none");
                $("._zwbp").css("display","block");
            }
        });

        //当选择不是领用人时弹框
        $('input:radio[name="BsReq[yn_lead]"]').change(function(){
            if($(this).is(":checked")){
                if($(this).val()==0){
                    $("._lyperson").show();
                    $('._users').validatebox({
                        required: true,
                        validType:['nameSame','tdSame','staffCode'],
                        delay: 1000000});
                    $('._tel').validatebox({required: true, validType: 'tel_mobile_c'});
                }else {
                    $("._lyperson").hide();
                    $("._users").validatebox({required: false});
                    $("._tel").validatebox({required: false});
                }
            }
        });
        //当选专案请购时专案代码show；
        $('.qgxs').change(function(){
            var p1=$(this).children('option:selected').val();
            if(p1=="100911"){
                $("._zadm").removeAttr("style");
                //$("._ahan").validatebox({required: true});
            }else {
                $("._zadm").css("display","none");
                //$("._ahan").validatebox({required: false});
            }
        });
        //点击专案代码搜索图标弹框
        $("._zadmss").click(function () {
            alert("需求未完善,后续扩展");
        });
        //点击采购部门后面搜索图标弹框
        $(".purchurse_part").click(function () {
            $(this).alert("需求未完善,后续扩展");
        });
        //保存和提交
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
            //验证商品数量，需求日期不为空

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
        //时间控制
        $("#auth_stime").click(function(){
            alert();
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd"
            });
        });
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


    //拟采购商品
    var purpdtIndex=$("#product_table").find("tr").length;
    function addPurpdt(){
        var trStr="<tr>";
        trStr+="<td></td>";
        trStr+="<td><input type='checkbox'></td>";
        trStr+="<td><input type='text' class='easyui-validatebox  _partno' name='prod["+purpdtIndex+"][BsReqDt][part_no]' data-options=\"required:'true',validateOnBlur:true\"></td>"; //
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td><input type='text' maxlength='10' name='prod["+purpdtIndex+"][BsReqDt][req_nums]' value='' placeholder='请输入数量' class='easyui-validatebox  _num ' data-options=\"required:'true',validType:'intnum'\"></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][exp_account]' value=''></td>";
        trStr+="<td><input onclick='choicetime()' type='text' readonly='true' name='prod["+purpdtIndex+"][BsReqDt][req_date]' class='_choicetime easyui-validatebox ' data-options=\"required:'true'\"></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][prj_no]' ></td>";
        trStr+="<td><input type='text' name='prod["+purpdtIndex+"][BsReqDt][remarks]' maxlength='100' class='_remarks' placeholder='最多输入100字'></td>";
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

    //获取料号
    function purpdtVal(rows){
        var _partno="";
        var obj2;
        $.each(rows,function(i,n){
            _partno+=n.part_no+",";
        });
        obj2=_partno.split(",");
//        alert(obj2);
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
                                if((arr.indexOf(data.rows[i][j].part_no))<0)  //限制重复添加料号
                                {
                                    addPurpdt();
                                    var $trLast=$("#product_table").find("tr:last");
                                    $trLast.find("._partno").val(data.rows[i][j].part_no);
                                    $trLast.find("._partno").addClass("partnos");
                                    $trLast.find("td:eq(3)").text(data.rows[i][j].pdt_name);
                                    $trLast.find("td:eq(4)").text(data.rows[i][j].tp_spec);
                                    $trLast.find("td:eq(5)").text(data.rows[i][j].brand);
                                    $trLast.find("td:eq(6)").text(data.rows[i][j].unit);
                                    $trLast.find("._num").val("");
                                    $trLast.find("td:eq(10)").text("");
                                    $trLast.find("._choicetime").val("");
                                    $trLast.find("td:eq(12)").text("");
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

    //重置和删除
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
    $(document).on("click",".icon-repeat",function(){
        $(this).parents("tr").find("input").val('');
        $(this).parents("tr").find("td").eq(3).text('');
        $(this).parents("tr").find("td").eq(4).text('');
        $(this).parents("tr").find("td").eq(5).text('');
        $(this).parents("tr").find("td").eq(6).text('');
//        $(this).parents("tr").find("td").eq(7).text('');
//        $(this).parents("tr").find("._price").text('');
        //$(this).parents("tr").find("td").eq(10).text('');
//        $(this).parents("tr").find(".totalspan").text('');
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
                    var per=$(".applyperson").val();
                    if(per!=data.staff_code){
                        var ret = data.staff_code+"--"+data.staff_name;
                        $("._users").val(ret);
                        $("._tel").val(data.staff_mobile);
                        $("._tel").removeClass("validatebox-invalid");
                        $("._tel").attr("data-options","false");
                        $("._users111").val(data.staff_id);
                        $("._users").removeClass("validatebox-invalid");
                    }
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