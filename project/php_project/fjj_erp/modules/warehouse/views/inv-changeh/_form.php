<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/11
 * Time: 上午 10:04
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
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
    .width-150{width: 150px;}
    .ml-220{
         margin-left: 130px;
     }
    .ml-180{
        margin-left: 48px;
    }
    .ml-230{
        margin-left: 70px;
    }
    .add-bottom {
        margin-bottom: 5px;
    }
    .width-80{width: 80px;}
    .addline{width: 80px;height: 24px; margin-left: 0px; cursor: pointer;}
</style>
<h2 class="head-second ">
    报废申请信息
</h2>
<div class="mb-30">
    <?php $form = ActiveForm::begin(
        ['id' => 'add-form']
    ); ?>
    <div class="mb-10">
            <label class="label-width label-align add-bottom"><span class="red">*</span>报废类别</label><label>:</label>
            <select name="InvChangeh[chh_type]" class="width-220 easyui-validatebox" <?= empty($invChangeInfoH['chh_type']) ? null : "disabled" ?> data-options="required:'true'" style="width: 140px">
                <option value="">请选择</option>
                <?php foreach ($downList["changeType"] as $key => $val) { ?>
                    <option value="<?= $val["business_type_id"] ?>"
                        <?= $invChangeInfoH['chh_type'] == $val['business_type_id'] ? "selected" : null; ?> >
                        <?= $val["business_value"] ?></option>
                <?php } ?>
            </select>
        <label class="ml-220 label-width label-align"><span class="red">*</span>出仓仓库</label><label>:</label>
        <select name="InvChangeh[wh_id]" class="width-220 output_wh easyui-validatebox" <?= empty($invChangeInfoH['wh_id']) ? null : "disabled" ?> data-options="required:'true'" style="width: 140px">
            <option value="">请选择</option>
            <?php foreach ($whname as $val) { ?>
                <option value="<?= $val["wh_id"] ?>"
                    <?= $invChangeInfoH['wh_id'] == $val['wh_id'] ? "selected" : null; ?> ><?= $val["wh_name"] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <?php if(\Yii::$app->controller->action->id == "update"){?>
            <label class="label-width label-align add-bottom"><span class="red">*</span>申请人</label><label>:</label>
            <input type="hidden" id="staff_id" value="<?= $invChangeInfoH['create_by'] ?>" name="InvChangeh[create_by]" class="staff_id">
            <input type="hidden" id="">
            <input type="text"  class="width-200 staff_code" disabled="disabled" data-options="required:true"
                   value="<?= $invChangeInfoL[0]['staffCode']?>" onblur="setStaffInfo(this)">
            <span class="width-80 staff_name"></span>
            <label class="ml-180 label-width label-align"><span class="red">*</span>申请部门</label><label>:</label>
            <input type="hidden" class="organizationid" value="<?= $invChangeInfoL[0]['depart_id'] ?>" name="InvChangeh[depart_id]"/>
            <input type="text" readonly="readonly"  id="organization_name" class="width-200 organization"
                   value="<?= $invChangeInfoL[0]['organization'] ?>">
        <?php }else{?>
            <label class="label-width label-align add-bottom"><span class="red">*</span>申请人</label><label>:</label>
            <input type="hidden" id="staff_id" value="" name="InvChangeh[create_by]" class="staff_id">
            <input type="hidden" id="">
            <input type="text"  class="width-200 staff_code" data-options="required:true"
                   value="" onblur="setStaffInfo(this)">
            <span class="width-80 staff_name"></span>
            <label style="margin-left: 28px" class=" label-width label-align"><span class="red">*</span>申请部门</label><label>:</label>
            <input type="hidden" class="organizationid" value="" name="InvChangeh[depart_id]"/>
            <input type="text" readonly="readonly"  id="organization_name" class="width-200 organization"
                   value="">
        <?php }?>

<!--        --><?php //if (\Yii::$app->controller->action->id == "update"){?>
<!--            <span class="width-80 staff_name">--><?//= $invChangeInfoL[0]['create_by']?><!--</span>-->
<!--        --><?php //} else {?>
<!--            <span class="width-80 staff_name"></span>-->
<!--        --><?php //}?>
    </div>


    <h2 class="head-second ">
        报废品信息 <span class="text-right float-right">
                <!--<a id="select_product">添加</a>
                <a onclick="">删除</a></span>-->
<!--            <a id="select_product">添加商品</a>-->
<!---->
<!--            <a id="">删除</a>-->
            <span style="" class="float-right mr-10">
            <a id="select_product" class="icon-reorder"></a>
<!--            <a id="add_btn" class="icon-plus"></a>-->
            <a id="delete_btn" class="icon-remove"></a>
        </span>
    </h2>
    <div class="mb-20 tablescroll" style="overflow-x: scroll">
        <table class="table" style="width: 2500px">
            <thead>
            <tr>
                <th class="width-40">序号</th>
                <th class="width-40"><input type="checkbox" id="checkAll"></th>
                <th class="width-150">料号</th>
                <th class="width-150">品名</th>
                <th class="width-150">类别</th>
                <th class="width-150">规格型号</th>
                <th class="width-150">批次</th>
                <th class="width-150">单位</th>
                <th class="width-150">当前储位</th>
                <th class="width-150">现有库存</th>
                <th class="width-150">报废数量</th>
                <th class="width-150">报废方式</th>
                <th class="width-250">报废原因</th>
                <th class="width-150">存放库存</th>
                <th class="width-150">存放储位</th>
                <th class="width-150">资产元值(元)</th>
                <th class="width-150">处理价(元)</th>
                <?php foreach ($columns as $key => $val) { ?>
                    <th><p class="text-center width-150 color-w"><?= $val["field_title"] ?></p></th>
                <?php } ?>
                <th class="width-100 text-center">操作</th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php if (\Yii::$app->controller->action->id == "update") { ?>
                <?php foreach ($invChangeInfoL[1] as $key => $val) { ?>
                    <tr>
                        <td>
                            <?= $key+1 ?>
                        </td>
                        <td><span class="width-40"><input type="checkbox"></span></td>
                        <td>
                            <input class="height-30 width-150  text-center  pdt_no"
                                   type="text" data-options="required:true"
                                   maxlength="20" placeholder="请输入"
                                   value="<?= $val['pdt_no'] ?>"><input class="hiden pdt_id"
                                                   name="changeL[<?=$key?>][pdt_no]" value="<?= $val['pdt_no']?>"/></td>
                        <td><span ><?= $val['pdt_name'] ?></span>
<!--                            <input class="height-30 width-150  text-center  "-->
<!--                                   type="text" data-options="required:true"-->
<!--                                   maxlength="20" placeholder="请输入"-->
<!--                                   value="--><?//= $val['pdt_name'] ?><!--"-->
<!--                            >-->
                        </td>
                        <td><span ><?= $val['catg_name'] ?></span>
<!--                            <input class="height-30 width-150  text-center  "-->
<!--                                   type="text" data-options="required:true"-->
<!--                                   maxlength="20" placeholder="请输入"-->
<!--                                   value="--><?//= $val['category_sname'] ?><!--">-->
                        </td>
                        <td><span ><?= $val['tp_spec'] ?></span>
<!--                            <input class="height-30 width-150  text-center  "-->
<!--                                   type="text" data-options="required:true"-->
<!--                                   maxlength="20" placeholder="请输入"-->
<!--                                   value="--><?//= $val['tp_spec'] ?><!--">-->
                        </td>
                        <td><span ><?= $val['chl_bach'] ?></span>
                            <input class="height-30 width-150  text-center  "
                                   type="hidden" name="changeL[<?= $key?>][chl_bach]"
                                   value="<?= $val['chl_bach'] ?>">
                        </td>
                        <td><span ><?= $val['unit'] ?></span>
<!--                            <input class="height-30 width-150  text-center  "-->
<!--                                   type="text" data-options="required:true"-->
<!--                                   maxlength="20" placeholder="请输入"-->
<!--                                   value="--><?//= $val['unit'] ?><!--">-->
                        </td>
                            <td><span ><?= $val['st_id'] ?></span>
                                <input class="height-30 wd100  text-center  store" type="hidden"
                                       data-options="required:true" maxlength="20"
                                       value="<?= $val["st_id"] ?>"><input type="hidden"
                                       name="changeL[<?= $key?>][st_id]" value="<?= $val["st_id"] ?>"></td>
                        <td><span ><?= $val['before_num1'] ?></span>
                            <input class="height-30 width-150  text-center invt_num"
                                   type="hidden"
                                   name="changeL[<?= $key?>][before_num1]"
                                   value="<?= $val['before_num1']?>">
                        </td>
                        <td><input class="height-30 width-150  text-center chl_num easyui-validatebox"
                                   type="text" maxlength="10" data-options="required:'true',validType:'intnum'"
                                   name="changeL[<?= $key?>][chl_num]"
                                   placeholder="请输入" value="<?= sprintf("%.2f", $val['chl_num'])?>">
                        </td>
                        <td>
                            <select class="height-30 width-150  text-center" type="text"  name="changeL[<?= $key?>][mode]">' +
                                <option value="">请选择...</option>
                                <?php foreach ($downList["scrap"] as $k => $v) { ?>
                                    <option value=" <?= $k ?>" <?= $k==$val['mode'] ? "selected" : null ?>><?= $v ?></option>
                                <?php } ?>
                                </select>
                        </td>
                        <td><input class="height-30 width-250  text-center chh_remark easyui-validatebox"
                                   type="text" maxlength="200" data-options="required:'true'"
                                   name="changeL[<?= $key?>][chh_remark]"
                                   placeholder="请输入" value="<?= $val['chh_remark']?>">
                        </td>
                        <td>
                            <select class="height-30 width-150  text-center input_wh easyui-validatebox" type="text"
                                    name="changeL[<?= $key?>][wh_id2]" data-options="required:'true'">
                                <option value="">请选择...</option>
                                <?php foreach ($downList["warehousebf"] as $k => $v) { ?>
                                    <option value="<?= $v["wh_id"] ?>" <?= $v["wh_id"]==$val['wh_id2'] ? "selected" : null ?>> <?= $v["wh_name"] ?></option>
                                <?php } ?>
                                </select>
                        </td>
                        <td>
                            <input class="height-30 wd100  text-center  store2 " readonly="readonly" type="text"
                                   data-options="required:true" maxlength="20" placeholder="请点击选择" value="<?= $val['st_id2']?>">
                            <input type="hidden" class="store2_ts" name="changeL[<?= $key?>][st_id2]" value="<?= $val['st_id2']?>">
                        </td>
                        <td><span></span>
                            <input class="height-30 width-150 " type="hidden" name="">
                        </td>
                        <td><input class="height-30 width-150  text-center easyui-validatebox" type="text"
                                   maxlength="10" data-options="required:'true',validType:'intnum'"
                                   value="<?= $val['deal_price']?>"
                                   name="changeL[<?= $key?>][deal_price]" placeholder="请输入"></td>
                        <td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'product_table')">删除</a></td>
                    </tr>
                <?php } ?>
            <?php }else {?>
                <tr>
                    <td>1</td>
                    <td><span class="width-40"><input type="checkbox"></span></td>
                    <td >
                        <input class=" pdt_no"
                               type="text" data-options="required:true"
                               maxlength="20" placeholder="请输入"
                               value=""><input class="hiden pdt_id"
                               name="changeL[0][pdt_no]" value=""/></td>
                    <td><span class="pdt_name"></span>
                    </td>
                    <td><span class="cata_name"></span>
                    </td>
                    <td><span class="tp_spec"></span>
                    </td>
                    </td>
                    <td><span class="L_invt_bach"></span>
                    </td>
                    <td><span class="unit"></span>
                    </td>
                    <td><span class="st_td"></span><input type="hidden" class="_st_td" name="changeL[0][st_id]" /></td>
                    <td><span class="invt_num"></span> <input type="hidden" class="height-30 width-150  text-center invt_num"
                               name="changeL[0][invt_num]"
                                value="">
                    </td>
                    <td><input class="height-30 width-150 text-center chl_num easyui-validatebox" type="text"
                               name="changeL[0][chl_num]" maxlength="10" data-options="required:'true',validType:'intnum'"
                               placeholder="请输入" value="">
                    </td>
                    <td>
                        <select class="height-30 width-150  text-center easyui-validatebox" type="text"
                                name="changeL[<?= $key?>][distribution]" data-options="required:'true'" >
                            <option value="">请选择...</option>
                            <?php foreach ($downList["scrap"] as $k => $v) { ?>
                    <option value=" <?= $k ?>"><?= $v ?></option>
                <?php } ?>
                </select>
                </td>
                <td><input class="height-30 width-250  text-center chh_remark easyui-validatebox"
                           type="text" maxlength="200"
                           name="changeL[<?= $key?>][chh_remark]" data-options="required:'true'"
                           placeholder="请输入" value="<?= $val['chh_remark']?>">
                </td>
                <td>
                    <select class="height-30 width-150  text-center input_wh easyui-validatebox" type="text"
                            name="changeL[<?= $key?>][transport]" data-options="required:'true'">
                        <option value="">请选择...</option>
                        <?php foreach ($downList["warehousebf"] as $k => $v) { ?>
                            <option value="<?= $v["wh_id"] ?>"> <?= $v["wh_name"] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input class="height-30 wd100  text-center easyui-validatebox store2" readonly="readonly" type="text"
                           data-options="required:true" maxlength="20" placeholder="请点击选择"
                           value="">
                </td>
                <td><span class=""></span><input class="height-30 width-150 " type="hidden" name=""></td>
                <td><input class="height-30 width-150  text-center easyui-validatebox" type="text"
                           name="changeL[<?= $key?>][deal_price]" maxlength="10"
                           data-options="required:'true',validType:'intnum'"  placeholder="请输入"></td>
                <td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,'product_table')">删除</a></td>
                </tr>
            <?php }; ?>
            </tbody>
        </table>
    </div>
    <div style="height: 20px;"></div>
    <p class="text-left mb-20">
        <input type="button" class="icon-plus button-white-big addline" id="add_btn" value="添加行"/>
    </p>
<!--    <p class="text-left mb-20">-->
<!--        <a class="icon-plus" id="add_btn">添加栏位</a>-->
<!--    </p>-->
    <div style="height: 20px;"></div>
    <div class="form-group text-center">
        <button id="save_btn" class="button-blue mr-20" type="submit">保存</button>
        <button id="submit_btn" class="button-blue mr-20" type="submit" style="margin-left: 40px;">提交</button>
        <button class="button-white" type="button" onclick="history.go(-1)">返回</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<script>
    var b = 0;
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
    $(function () {
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });

        var button_flag=0;

        //保存
        $("#save_btn").click(function(){
            button_flag=0;
        });
        //提交
        $("#submit_btn").click(function(){
            button_flag=1;
        });
        ajaxSubmitForm($("#add-form"),'',function(data){
//                console.log(data);
            if (data.flag == 1) {
                if(button_flag==1){
                    var id=data.id;
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=data.chh_type;
//                        console.log(id+":"+type);
                    $.fancybox({
                        href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:750,
                        height:480,
                        afterClose:function(){
                            location.href=data.url;
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

//        ajaxSubmitForm($("#add-form"));
    });
    var fieldArr = new Array();
    <?php foreach ($columns as $key => $val) { ?>
    fieldArr[<?= $key ?>] = "<?= $val["field_field"] ?>";
    <?php } ?>
    //选择商品
    $("#select_product").click(function () {
        var codes="";
        var out = $(".output_wh").val();//出仓仓库
        var url = "<?= Url::to(['wh-code'])?>?id="+out;
        //根据仓库id获取仓库代码

        if(out!=""){
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"whid": out},
                url: url,
                success: function (data) {
                    codes=data.wh_code;
                    if (codes != "") {
                        $.fancybox({
                            href: "<?=Url::to(['select-product'])?>" + "?whId=" + codes,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 400
                        });
                    }else {
                        layer.alert("未找到该仓库代码!", {icon: 2});
                    }
                },
                error: function (data) {
                    layer.alert("未找到该仓库代码!", {icon: 2});
                }
            });
        }else{
            layer.alert("请先选择出仓仓库!", {icon: 2});
        }
    });

    //批量删除
    $("#delete_btn").on('click', function () {
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



//    $(document).on("change", ".pdt_no", function () {
//        var $pdt_no = $(this);
//        $pdt_no.validatebox();//验证初始化
//        var pdt_no = $(this).val();
//        var url = "<?//= Url::to(['get-pdt'])?>//";
//        $.ajax({
//            type: 'GET',
//            dataType: 'json',
//            data: {"pdt_no": pdt_no},
//            url: url,
//            success: function (data) {
//                if (data.pdt_no != null) {
//                    console.log(data);
//                    <?php //foreach ($columns as $key => $val) { ?>
//                    fieldArr[<?//= $key ?>//] = "<?//= $val["field_field"] ?>//";
//                    $pdt_no.parent().parent().find("td.<?//= $val["field_field"] ?>//").children(":first").html(data.<?//= $val["field_field"] ?>//);
//                    <?php //} ?>
//                    $pdt_no.next().val(data.pdt_id);
//                }
//            },
//            error: function (data) {
////                layer.alert("未找到该料号!", {icon: 2});
//            }
//        })
//    });


//    $(".tablescroll").on("scroll", function () {
//        if ($(this).scrollLeft() > 120) {
//            $("input").blur();
//        }
//        if ($(this).scrollLeft() > 110 && $(this).scrollLeft() < 1400) {
//            //关闭日期选择
//            $(".jedatedeep").hide();
//        }
//    });
    function more() {
        $(".moreInfo").show();
        $("#less").show();
        $("#more").hide();
    }
    function less() {
        $(".moreInfo").hide();
        $("#more").show();
        $("#less").hide();
    }
    $("#add_btn").click(function(){
        var $productTbody=$("#product_table");
        var tr='<tr>' +
            '<td>' +   '</td>' +
            '<td>'+'<span class="width-40"><input type="checkbox"></span>'+'</td>'+
            '<td><input class=" pdt_no easyui-validatebox" type="text" data-options="required:true"  maxlength="20" placeholder="请输入" value=""><input class="hiden pdt_id" name="changeL[' + b + '][pdt_no]" value=""/></td>' +
            '<td><span class="pdt_name"></span></td>' +
            '<td><span class="cata_name"></td>' +
            '<td><span class="tp_spec"></td>' +
            '<td><span class="L_invt_bach"><input type="hidden" ' +
            'name="changeL[' + b + '][chl_bach]"/></td>' +
            '<td><span class="unit"></span></td>' +
            '<td><span class="st_td"></span>' +
            '<input class="_st_td" name="changeL[' + b + '][st_id]" type="hidden" ' +
            ' value=""></td>' +
            '<td><span class="invt_num"></span><input type="hidden" class="invtnum" name="changeL[' + b + '][before_num1]" /></td>' +
            '<td><input class="height-30 width-150  text-center chl_num easyui-validatebox" ' +
            'type="text" data-options="required:\'true\',validType:\'intnum\'" maxlength="10" ' +
            'name="changeL[' + b + '][chl_num]" placeholder="请输入" value=""></td>' +
            '<td><select class="height-30 width-150  text-center easyui-validatebox" ' +
            'type="text" data-options="required:true" ' +
            'name="changeL[' + b + '][mode]">' +
            '<option value="">请选择...</option>' +
            <?php foreach ($downList["scrap"] as $key => $val) { ?>
            '<option value="' + "<?= $key ?>" + '">' + "<?= $val ?>" + '</option>' +
            <?php } ?>
            '</select></td>' +
            '<td><input class="height-30 width-250  text-center chh_remark easyui-validatebox" ' +
            'type="text" data-options="required:true" ' +
            'name="changeL[' + b + '][chh_remark]" placeholder="请输入"></td>' +
            '<td><select class="height-30 width-150  text-center input_wh easyui-validatebox" type="text" ' +
            'data-options="required:true" ' +
            'name="changeL[' + b + '][wh_id2]">' +
            '<option value="">请选择...</option>' +
            <?php foreach ($downList["warehousebf"] as $key => $val) { ?>
            '<option value="' + "<?= $val["wh_id"] ?>" + '">' + "<?= $val["wh_name"] ?>" + '</option>' +
            <?php } ?>
            '</select></td>' +
            '<td><input class="height-30 wd100  text-center  store2 easyui-validatebox" ' +
            'readonly="readonly" name="changeL[' + b + '][st_id2]" type="text" ' +
            'data-options="required:true" maxlength="20" placeholder="请点击选择" value="<?= $val["st_id2"] ?>"></td>' +
            '<td><span></span><input class="height-30 width-150 " type="hidden" name=""></td>' +
            '<td><input class="height-30 width-150  text-center easyui-validatebox"  type="text" name="" ' +
            'data-options="required:\'true\',validType:\'intnum\'" maxlength="10" ' +
            'placeholder="请输入"></td>' +
            '<td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
            '</tr>';
        b++;
        $productTbody.append(tr).find("tr").each(function(index){
            $(this).find("td:first").text(index+1);
        });
        $.parser.parse($productTbody.find("tr:last"));

    });

//    //选择储位
//    $(document).on("click", ".store", function () {
//        var out = $(".output_wh").val();//出仓仓库
//        var type = 1;
//        var row = $(this).parent().parent().first().find("td").eq(0).text();//行数
//        if (out != "") {
//            $.fancybox({
//                href: "<?//=Url::to(['select-store'])?>//" + "?whId=" + out +"&types="+ type +"&rows=" + row ,
//                type: "iframe",
//                padding: 0,
//                autoSize: false,
//                width: 800,
//                height: 520,
//            });
//        } else {
//            layer.alert("请先选择仓库!", {icon: 2});
//        }
//    });


    //选择存放储位
    $(document).on("click", ".store2", function () {
        var s=$(this);
        var out = s.parent().parent().find(".input_wh").val();//出仓仓库
//        alert(out);
        var type = 2;
        var row = $(this).parent().parent().first().find("td").eq(0).text();//行数
        if (out != "") {
            $.fancybox({
                href: "<?=Url::to(['select-store'])?>" + "?whId=" + out +"&types=" + type +"&rows=" + row,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 520,
            });
        } else {
            layer.alert("请先选择存放仓库!", {icon: 2});
        }
    });


    //获取单笔料号
    $(document).on("change", ".pdt_no", function () {
        var s=$(this);
        var row = $(this).parent().parent().first().find("td").eq(0).text();//行数
        var pro=$(this).val();
        var out = $(".output_wh").val();//出仓仓库
        var url = "<?= Url::to(['wh-code'])?>?id="+out;
        if (out != "") {
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"whid": out},
                url: url,
                success: function (data) {
                    codes=data.wh_code;
                    if (codes != "") {
                        $.fancybox({
                            href: "<?=Url::to(['select-product'])?>" + "?whId=" + codes +"&kwd="+ pro +"&row="+row,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 400
                        });
                    }else {
                        layer.alert("未找到该仓库代码!", {icon: 2});
                    }
                },
                error: function (data) {
                    layer.alert("未找到该仓库代码!", {icon: 2});
                }
            });


//            $.fancybox({
//                href: "<?//=Url::to(['select-product'])?>//" + "?whId=" + out +"&kwd="+ pro +"&row="+row,
//                type: "iframe",
//                padding: 0,
//                autoSize: false,
//                width: 800,
//                height: 400
//            });
        }else {
            layer.alert("请先选择出仓仓库!", {icon: 2});
        }
    });

    //控制数量不能超过库存量
    $(document).on("blur", ".chl_num", function () {
        var chlnum=$(this);
        var val=$(this).val();
//        alert(val);
//        val.validatebox({required:'true',validType:'intnum'});
        var _kcnum=chlnum.parent().prev().find("span").text();
        if(parseFloat(val) > parseFloat(_kcnum)){
            layer.alert('报废数量不能大于库存量',{icon:2});
            chlnum.addClass("validatebox-text validatebox-invalid");
            return false;
        }else if(parseFloat(val) <= parseFloat(_kcnum)){
            chlnum.removeClass("validatebox-text validatebox-invalid");
        }
    });



    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
    }
    function vacc_del(obj, id) {
        var a = $("#" + id + " tr").length;
        if(a>1){
            $(obj).parents("tr").remove();
            for (var i = 0; i < a; i++) {
                $('#' + id).find('tr').eq(i).find('td:first').text(i + 1);
            }
        }
    }
    //全选
    $(".table").on('click',"th input[type='checkbox']",function(){
//        console.log(000);
//        $("input[name='checkbox']").attr("checked","true");
        if($(this).is(":checked")){
            $('.table').find("td input[type='checkbox']").prop("checked",true);
        }else{
            $('.table').find("td input[type='checkbox']").prop("checked",false);
        }
    });

//    function change(obj) {
//        var length = obj.files.length;
//        var span = obj.parentNode.previousSibling.previousSibling;
//        var temp = "";
//        for (var i = 0; i < obj.files.length; i++) {
//            if (i == 0) {
//                temp = obj.files[i].name;
//            } else {
//                temp = temp + "&nbsp,&nbsp" + obj.files[i].name;
//            }
//            span.innerHTML = temp;
//        }
//    }
</script>
