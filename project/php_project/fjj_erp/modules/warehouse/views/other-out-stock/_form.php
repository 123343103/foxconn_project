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
use app\assets\JeDateAsset;

JeDateAsset::register($this);
?>
<style>
    .width-120 {
        width: 120px;
    }

    .width-100 {
        width: 100px;
    }

    .width-80 {
        width: 156px;
    }

    .width-150 {
        width: 150px;
    }

    .width-200 {
        width: 200px;
    }

    .width-230 {
        width: 230px;
    }

    .width-250 {
        width: 200px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .ml-25 {
        margin-left: 25px;
    }

    .ml-60 {
        margin-left: 60px;
    }

    .ml-70 {
        margin-left: 70px;
    }

    .ml-80 {
        margin-left: 80px;
    }

    .ml-90 {
        margin-left: 85px;
    }

    .ml-320 {
        margin-left: 320px;
    }

    label:after {
        content: "：";
    }

    .validatebox-invalid {
        border-color: #ffa8a8;
    }

    td {
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    tr {
        width: 990px;
        overflow: hidden;
        height: 36px;
    }
</style>
<?php ActiveForm::begin(); ?>
<div class="content">
    <h3 class="head-first"><?= $this->title ?><span style="color:#fff;float: right;">
            <?php if(!empty($model['o_whcode'])){?>
            出库单号:<?= $model['o_whcode'] ?>
            <?php }?>
        </span></h3>
    <h3 class="head-second">出库单信息</h3>
    <div>
        <div class="mb-20">
            <label for="" class="label-width label-align width-120"><span style="color:red;">*</span>单据类型</label>
            <?= Html::dropDownList("OWhpdt[o_whtype]", $model['o_whtype'], $options["o_whtype"], ["prompt" => "请选择", "class" => "width-250 easyui-validatebox", "data-options" => "required:true","disabled"=> !empty($model["o_whtype"])?'disabled':null]) ?>
            <label for="" class="label-width label-align width-230">关联单号</label>
            <input type="text" class="width-250" name="OWhpdt[relate_packno]" disabled value="<?= $model['relate_packno'] ?>">
        </div>
        <div class="mb-20">
            <label for="" class="wlabel-width label-align width-120"><span style="color:red;">*</span>出仓仓库</label>
            <select id="o_wh_name" name="OWhpdt[o_whid]" class="width-250 easyui-validatebox validatebox-text"
                    data-options="required:true,validateOnBlur:true" <?= !empty($model["o_whid"])?'disabled':null?>>
                <option value data-code="0">请选择</option>
                <?php foreach ($options['warehouse'] as $key => $val) { ?>
                    <option value="<?= $val['wh_id'] ?>"
                            code="<?= $val['wh_code'] ?>" company="<?= $val['company_name']?>" <?= !empty($model['o_whid']) && $model['o_whid'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
            <label for="" class="label-width label-align width-230">仓库代码</label>
            <input type="text" id="o_wh_code" class="width-250" name="OWhpdt[o_wh_code]"
                   value="" readonly>
        </div>
        <div class="mb-20">
            <label for="" class="label-width label-align width-120">法人名称</label>
            <input type="text" id="company_name" class="width-250" name="OWhpdt[company_name]" value="" readonly>
            <label for="" class="label-width label-align width-230"><span style="color:red;">*</span>出货日期</label>
            <input type="text" id="pre_outstock_date" readonly="readonly"
                   class="width-250 select-date easyui-validatebox" data-options="required:true"
                   name="OWhpdt[plan_odate]" value="<?= $model['plan_odate'] ?>">
        </div>
        <div class="mb-20">
            <label for="" class="wlabel-width label-align width-120"><span style="color:red;">*</span>入仓仓库</label>
            <select id="i_wh_name" name="OWhpdt[i_whid]" class="width-250 easyui-validatebox validatebox-text"
                    data-options="required:true,validateOnBlur:true">
                <option value data-code="0">请选择</option>
                <?php foreach ($options['warehouse'] as $key => $val) { ?>
                    <option value="<?= $val['wh_id'] ?>"
                            code="<?= $val['wh_code'] ?>" <?= !empty($model['i_whid']) && $model['i_whid'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
            <label for="" class="label-width label-align width-230">仓库代码</label>
            <input type="text" id="i_wh_code" class="width-250" name="OWhpdt[i_wh_code]"
                   value="" readonly>
        </div>
        <?php if(!empty($staff_is_supper)){if($staff_is_supper['is_supper']==1){?>
        <div class="mb-20">
            <label for="" class="label-width label-align width-120"><span style="color:red;">*</span>申请人</label>
            <input type="text" id="creator" class="width-250 validatebox-text easyui-validatebox" name="OWhpdt[creators]"
                   value="" data-options="required:true,validateOnBlur:true,validType:'staffcode',delay: 1000000 " >
            <input type="hidden" id="creators" name="OWhpdt[creator]"
                   value="" >
            <label for="" class="label-width label-align width-230">申请部门</label>
            <input type="text" id="app_departs" class="width-250" name="OWhpdt[app_departs]"
                   value="" readonly>
            <input type="hidden" id="app_depart" name="OWhpdt[app_depart]"
                   value="" >

        </div>
        <?php }else{?>
            <div class="mb-20">
                <label for="" class="label-width label-align width-120"><span style="color:red;">*</span>申请人</label>
                <input type="text" id="creator" class="width-250" name="OWhpdt[creators]"
                       value="<?= $staff['staff_code']?>-<?= $staff['staff_name']?>" readonly>
                <input type="hidden" id="creators" name="OWhpdt[creator]"
                       value="<?= $staff['staff_id']?>" >
                <label for="" class="label-width label-align width-230">申请部门</label>
                <input type="text" id="app_departs" class="width-250" name="OWhpdt[app_departs]"
                       value="<?= $staff['organization_name']?>" readonly>
                <input type="hidden" id="app_depart" name="OWhpdt[app_depart]"
                       value="<?= $staff['organizationID']?>" >

            </div>
        <?php }}else{?>
            <div class="mb-20">
                <label for="" class="label-width label-align width-120"><span style="color:red;">*</span>申请人</label>
                <input id="staff_id" value="<?= $model['creator']?>?>" type="hidden">
                <input type="text" id="creator" class="width-250" name="OWhpdt[creators]"
                       value="" readonly>
                <input type="hidden" id="creators" name="OWhpdt[creator]"
                       value="" >
                <label for="" class="label-width label-align width-230">申请部门</label>
                <input type="text" id="app_departs" class="width-250" name="OWhpdt[app_departs]"
                       value="" readonly>
                <input type="hidden" id="app_depart" name="OWhpdt[app_depart]"
                       value="<?= $model['app_depart']?>" >
            </div>
        <?php }?>
        <div class="mb-20">
            <label for="" class="label-width label-align width-120">备注</label>
            <input type="text" style="width:637px" name="OWhpdt[remarks]" value="<?= $model['remarks'] ?>">
        </div>
    </div>
    <h3 class="head-second">出库商品信息
        <span class="pull-right width-200 text-center">
            <a class="icon-plus product-adds"></a>
            <a class="icon-remove product-del"></a>
        </span>
    </h3>
    <div style="width:100%;overflow: auto;">
        <table class="table">
            <thead>
            <th>序号</th>
            <th><input id="checkAll" type="checkbox"></th>
            <th>料号</th>
            <th>品名</th>
            <th>规格型号</th>
            <th>储位</th>
            <th>批次</th>
            <th>单位</th>
            <th>可用库存量</th>
            <th>出货数量</th>
            <th>备注</th>
            <th>操作</th>
            </thead>
            <tbody id="data">
            <input type="hidden" id="products" value="<?= implode(",", array_column($childs['rows'], 'invt_id')) ?>">
            <?php if (count($childs['rows']) > 0){ ?>
                <?php foreach ($childs['rows'] as $k => $child) { ?>
                    <tr>
                        <td align="center"><?= $k + 1 ?></td>
                        <td align="center">
                            <input type="checkbox">
                            <input type="hidden" class="invt_id" name="OWhpdtDt[invt_id][]" value="<?= $child['invt_id']?>">
                        </td>
                        <td align="center"><input class="part_no easyui-validatebox validatebox-text"
                                                  name="OWhpdtDt[part_no][]" value="<?= $child['part_no'] ?>"
                                                  data-options="required:true,validateOnBlur:true">
                        </td>
                        <td align="center"><?= $child['pdt_name'] ?></td>
                        <td align="center"><?= $child['tp_spec'] ?></td>
                        <td align="center"><?= $child['st_code']?></td>
                        <td align="center"><?= $child['batch_no']?></td>
                        <td align="center"><?= $child['unit_name'] ?></td>
                        <td align="center"><?= $child['invt_num'] ?></td>
                        <td align="center"><input name="OWhpdtDt[o_whnum][]"
                                                  class="width-50 number easyui-validatebox validatebox-text"
                                                  data-options="required:true" style="text-align:center;" type="text"
                                                  value="<?= $child['o_whnum'] ?>"></td>
                        <td align="center"><textarea name="OWhpdtDt[remarks][]" class="width-100" style=""
                                                     type="text"><?= $child['remarks'] ?></textarea></td>
                        <td align="center">
                            <a onclick="delRow(this)"><i class="icon-minus"></i></a>&nbsp;&nbsp;<a
                                onclick="refreshRow(this)"><i class="icon-refresh ml-20"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
            <tfoot>
            <tr class="empty">
                <td align="center" colspan="12">列表为空</td>
            </tr>
            </tfoot>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        <button class="button-blue product-add" type="button">添加商品</button>
    </div>
    <div class="mt-50 text-center">
        <button class="button-blue" type="submit">保存</button>
        <button style="margin-left: 15px;" class="button-blue" type="submit">提交</button>
        <button style="margin-left: 15px;" class="button-white" type="button"
                onclick="window.location.href='<?= Url::to(['index']) ?>'">返回
        </button>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(function () {
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
        });

        //根据申请人带出部门
        $("#staff_id").change(function(){
            var _this = $(this);
            if(_this.val()!=undefined) {
                $.ajax({
                    url: "<?=Url::to(['get-staff'])?>?id=" + _this.val(),
                    type: "get",
                    success: function (data) {
                        data = $.parseJSON(data);
//                    console.log(data);
                        $("#creator").val(data.staff_code + "-" + data.staff_name);
                        $("#app_departs").val(data.organization_name);
                        $("#creators").val(data.staff_id);
                        $("#app_depart").val(data.organizationID);
                    }
                });
            }
        });
        ajaxSubmitForm("form","",function(data){
            if (data.flag == 1) {
                if(btnFlag == '提交'){
                    var id='<?=\Yii::$app->request->get('id')?>';
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var tpList=<?= $businessType?>;
                    var changeType = "其它出库";
                    for (var k in tpList) {
                        if (changeType == tpList[k]) {
                            var type = k;
                            break;
                        }
                    }
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

        //重写验证规则
        $.extend($.fn.validatebox.defaults.rules, {
            //联系方式
            mobile: {
                validator: function () {
                    var str = $("#mobile").val();
                    var phone = /^0?1[3|4|5|8][0-9]\d{8}$/;
                    var tels = /^0\d{2,3}-[1-9]\d{6,7}$/;
                    var telss = /^[\(|（]0\d{2,3}[\)|）]-[1-9]\d{6,7}$/;
                    if (!str.match(phone) && !str.match(tels) && !str.match(telss)) {
//                        $.fn.validatebox.defaults.rules.mobile.message='请输入正确的联系方式';
                        return false;
                    }
                    return true;
                },
                message: '请输入正确的联系方式'
            },

            //根据工号判断
            staffcode: {
                validator: function (value) {
//                    alert(value);
                    var data = $.ajax({
                        url: '<?=Url::to(['get-staff-code'])?>',
                        data: {
                            'staff_code': value.split('-')[0]
                        },
                        async: false,
                        cache: false
                    }).responseText;
                    data = JSON.parse(data);
                    console.log(data);
                    if(data==1) {
                        $("#app_departs").val("");
                        $("#creators").val("");
                        $("#app_depart").val("");
                        return false;
                    }
                    else {
                        $("#creator").val(data.staff_code + "-" + data.staff_name);
                        $("#app_departs").val(data.organization);
                        $("#creators").val(data.staff_id);
                        $("#app_depart").val(data.organizationid);
                        return true;
                    }
                },
                message: '工号不存在'
            },

            //判断出库量是否大于库存量
            judgenumber:{
                validator: function (value) {
                    var tr = $(this).parents("tr");
                    var num=tr.find("td").eq(8).text();
                    if(num=='')
                    {
                        num=0;
                    }
                    else if(parseFloat(num)<0)
                    {
                        num=-parseFloat(num);
                    }
                    else {
                        num=parseFloat(num);
                    }
                    if(num<parseFloat($.trim(value)))
                        return false;
                    else
                        return true;
                },
                message:'出库数量不能大于当前库存量'
            },///^[0-9]{1}([0-9]|[.])*$/

            //判断出库数量是否填写正确
            isnumber:{
                validator: function (value) {
                    var number = /^(?!0+(?:\.0+)?$)(?:[1-9]\d*|0)(?:\.\d{1,2})?$/;
                    var str=$.trim(value);
                    if (!str.match(number) ) {
                        return false;
                    }
                    return true;
                },
                message: '请输入正确数量（保留两位小数）'
            },

            //判断料号是否存在
            productCodeValidate: {
                validator: function (value) {
                    if ($("#o_wh_name").val() == null || $("#o_wh_name").val() == "") {
                        layer.alert("请选择出仓仓库！", {icon: 2});
                    }
                    else {
                        var data = $.ajax({
                            url: '<?=Url::to(['get-product'])?>',
                            data: {
                                'part_no': $.trim(value),
                                'id': $("#o_wh_name").val()
                            },
                            async: false,
                            cache: false
                        }).responseText;
                        data = JSON.parse(data);
                        var tr = $(this).parents("tr");
                        if (data == '') {
                            var products = [];
                            tr.find("td").eq(1).find(".invt_id").val("");
                            tr.find("td").eq(3).text("");
                            tr.find("td").eq(4).text("");
                            tr.find("td").eq(5).text("");
                            tr.find("td").eq(6).text("");
                            tr.find("td").eq(7).text("");
                            tr.find("td").eq(8).text("");
                            tr.find(".number").val("");
                            tr.find("textarea").val("");
                            $("#data .invt_id").each(function (index, ele) {
                                products.push($(ele).val());
                            });
                            $("#products").val(products.join(","));
                            if (products.length == 0) {
                                $("#data+tfoot").show();
                            }
                            return false;
                        }
                        else {
                            return true;
                        }
                    }
                },
                    message: '料号不存在'
            }
        });

        $("#data .part_no").validatebox({
            required: true,
            validType: ["productCodeValidate"],
            delay: 1000000
        });
        $("#data .number").validatebox({
            required: true,
            validType: ["judgenumber","isnumber"],
            delay: 1000000
        });

        $("#data").delegate(".part_no","blur",function(){
            if ($("#o_wh_name").val() == null || $("#o_wh_name").val() == "") {
                layer.alert("请选择出仓仓库！", {icon: 2});
            }
            else {
                var data = $.ajax({
                    url: '<?=Url::to(['get-product'])?>',
                    data: {
                        'part_no': $.trim($(this).val()),
                        'id': $("#o_wh_name").val()
                    },
                    async: false,
                    cache: false
                }).responseText;
                data = JSON.parse(data);
                if(data=='')
                {

                }
                else {
                    $.fancybox({
                        type: "iframe",
                        width: 800,
                        height: 500,
                        autoSize: false,
                        padding: 0,
                        href: "<?=Url::to(['select-product'])?>?id=" + $("#o_wh_name").val() + "&part_no=" + $.trim($(this).val())
                    });
                }
            }
        });
        //预出库日期
        $("#pre_outstock_date").click(function () {
            WdatePicker({
                onpicked: function (obj) {
                    $(this).validatebox('validate');
                },
                skin: 'whyGreen',
                minDate: '%y-%M-%d',
                dateFmt: 'yyyy-MM-dd'
//                isShowToday: false
//                maxDate: '#F{$dp.$D(\'end_time\');}'
            })
        });
        $(".content").click(function () {
            $("#checkAll").prop("checked", $("#data :checked").size() == $("#data :checkbox").size() && $("#data :checked").size() > 0);
        });
        //查看添加商品列表
        $(".product-adds").click(function () {
            if($("#o_wh_name").val()==null||$("#o_wh_name").val()=="")
            {
                layer.alert("请选择出仓仓库！", {icon: 2});
            }
            else {
                $.fancybox({
                    type: "iframe",
                    width: 800,
                    height: 500,
                    autoSize: false,
                    padding: 0,
                    href: "<?=Url::to(['select-product'])?>?id=" + $("#o_wh_name").val()+"&part_no="
                });
            }
        });
        //全选
        $("#checkAll").click(function () {
            $("#data :checkbox").prop("checked", $(this).prop("checked"));
        });
        //选择删除
        $(".product-del").click(function () {
            var products = [];
            var i = 0;
            $("input[type='checkbox']").each(function () {
                if ($(this).is(':checked')) {
                    i++;
                }
            });
            if (i == 0) {
                layer.alert("请选择要删除的信息！", {icon: 2});
            }
            else {
                layer.confirm("确定删除选择的信息?", {icon: 2},
                    function () {
                        $("#data :checked").parents("tr").remove();
//                        alert($("#data tr").size());
                        for (var y = 0; y < $("#data tr").size(); y++) {
                            $("#data tr").eq(y).find("td:first").text(y + 1);
                            $("#checkAll").prop("checked", $("#data :checked").size() == $("#data :checkbox").size() && $("#data :checked").size() > 0);
                        }
                        $("#data .invt_id").each(function (index, ele) {
                            products.push($(ele).val());
                        });
                        $("#products").val(products.join(","));
                        if (products.length == 0) {
                            $("#data+tfoot").show();
                        }
                        layer.closeAll();
                    },
                    function () {
                        layer.closeAll();
                    }
                );
            }
        });
        //选择部门带出人员
        $("#organization").change(function () {
            var _this = $(this);
            $.ajax({
                url: "<?=Url::to(['get-org-staff'])?>?org_id=" + _this.val() + "&selected=<?=$model['creator']?>",
                type: "get",
                success: function (data) {
                    $("#applicant").html(data);
                }
            });
        });

        //选择chu仓库带出仓库代码
        $("#o_wh_name").change(function () {
            var wh_code = $(this).find("option:selected").attr("code");
            var company_name = $(this).find("option:selected").attr("company");
            if($("#i_wh_name").val()==$(this).val()&&$("#i_wh_name").val().length!=0&&$(this).val().length!=0)
            {
                layer.alert("出仓仓库不能与入仓仓库相同！", {icon: 2});
                $(this).find("option:first").prop("selected", 'selected');
                $("#o_wh_code").val("");
                $("#company_name").val("");
            }
            else{
                $("#o_wh_code").val(wh_code);
                $("#company_name").val(company_name);
            }
        });

        //选择ru仓库带出仓库代码
        $("#i_wh_name").change(function () {
            var wh_code = $(this).find("option:selected").attr("code");
            if($("#o_wh_name").val()==$(this).val()&&$("#o_wh_name").val().length!=0&&$(this).val().length!=0)
            {
                layer.alert("出仓仓库不能与入仓仓库相同！", {icon: 2});
                $(this).find("option:first").prop("selected", 'selected');
                $("#i_wh_code").val("");
            }
            else{
                $("#i_wh_code").val(wh_code);
            }
        });

        //添加一行（添加商品）
        $(".product-add").click(function () {
            $("#data").append('<tr>\
                        <td align="center"></td>\
                        <td align="center">\
                        <input type="checkbox">\
                        <input type="hidden" class="invt_id" name="OWhpdtDt[invt_id][]" value="">\
                        </td>\
                        <td align="center"><input class="part_no" name="OWhpdtDt[part_no][]" value="" data-options="required:true,validateOnBlur:true"></td>\
                        <td align="center"></td>\
                        <td align="center"></td>\
                        <td align="center"></td>\
                        <td align="center"></td>\
                        <td align="center"></td>\
                        <td align="center"></td>\
                        <td align="center"><input class="number" name="OWhpdtDt[o_whnum][]" data-options="required:true,validateOnBlur:true"  class="width-50" style="text-align:center;" type="text"></td>\
                        <td align="center"><textarea name="OWhpdtDt[remarks][]" class="width-100"  style=""></textarea></td>\
                        <td align="center">\
                        <a onclick="delRow(this)"><i class="icon-minus"></i></a>&nbsp;&nbsp;<a onclick="refreshRow(this)"><i class="icon-refresh ml-20"></i></a>\
                        </td>\
                        </tr>\
                        ');
            $("#data+tfoot").hide();
            for (var y = 0; y < $("#data tr").size(); y++) {
                $("#data tr").eq(y).find("td:first").text(y + 1);
                $("#checkAll").prop("checked", $("#data :checked").size() == $("#data :checkbox").size() && $("#data :checked").size() > 0);
            }
            $("#data .part_no").validatebox({
                required: true,
                validType: ["productCodeValidate"],
                delay: 1000000
            });
            $("#data .number").validatebox({
                required: true,
                validType: ["judgenumber","isnumber"],
                delay: 1000000
            });
        });


    });
    //删除一行
    function delRow(t) {
        var products = [];
        layer.confirm("确定删除此条信息?", {icon: 2},
            function () {
                $(t).parents("tr").remove();
                for (var y = 0; y < $("#data tr").size(); y++) {
                    $("#data tr").eq(y).find("td:first").text(y + 1);
                    $("#checkAll").prop("checked", $("#data :checked").size() == $("#data :checkbox").size() && $("#data :checked").size() > 0);
                }
                $("#data .invt_id").each(function (index, ele) {
                    products.push($(ele).val());
                });
                $("#products").val(products.join(","));
                if (products.length == 0) {
                    $("#data+tfoot").show();
                }
                layer.closeAll();
            },
            function () {
                layer.closeAll();
            }
        );
    }
    //刷新一行
    function refreshRow(t) {
        var products = [];
        var tr = $(t).parents("tr");
        $(t).parents("tr").find("input").val("");
        tr.find("td").eq(3).text("");
        tr.find("td").eq(4).text("");
        tr.find("td").eq(5).text("");
        tr.find("td").eq(6).text("");
        tr.find("td").eq(7).text("");
        tr.find("td").eq(8).text("");
        tr.find(".number").val("");
        tr.find("textarea").val("");
        $("#data .invt_id").each(function (index, ele) {
            products.push($(ele).val());
        });
        $("#products").val(products.join(","));
    }
</script>
