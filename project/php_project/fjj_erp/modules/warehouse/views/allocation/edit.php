<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/31
 * Time: 下午 02:57
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '修改调拨单列表';
$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => Url::to(['/index'])];
$this->params['breadcrumbs'][] = ['label' => '调拨单列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
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
</style>
<div class="content">
    <h1 class="head-first"><?= $this->title ?></h1>
    <?php ActiveForm::begin(['id' => 'update_form']); ?>
    <h1 class="head-second" style="text-align:center;">调拨单基本信息</h1>
    <input type="hidden" id="chh_id" value="<?php echo $_GET['id'] ?>">
    <div class="mb-10">
            <label class="label-width label-align add-bottom" for="partsearch-yn">调拨类型</label><label>:</label>
            <span class="value-width value-align"><?= $data[0]['business_type_desc']?></span>
            <label class="ml-220 label-width label-align" for="partsearch-yn">调拨单位</label><label>:</label>
            <span><?= $data[0]['depart_id']?></span>
    </div>
    <div class="mb-10">
            <label class="label-width label-align add-bottom" for="partsearch-part_code">调出仓库</label><label>:</label>
            <input type="hidden" id="Owh_name" value="<?=$data[0]['whid']?>" />
            <span class="value-width value-align"><?= $data[0]['wh_id']?></span>
            <label class="ml-220 label-width label-align" for="partsearch-yn">调出仓库代码</label><label>:</label>
            <span class=" Owh_code"></span>
    </div>
    <div class="mb-10">
            <label class="label-width label-align add-bottom" for="partsearch-part_code">调入仓库</label><label>:</label>
            <select id="Iwh_name" class="value-width value-align" name="InvChangeh[wh_id2]" onclick="ICheckCode(this)">
                <option value="">请选择...</option>
                <?php foreach ($whname as $val) { ?>
                    <option data-code="<?= $val['wh_code'] ?>"
                            value="<?= $val['wh_id'] ?>" <?= isset($data[0]['wh_id2']) && $data[0]['wh_id2'] == $val['wh_name'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
            <label class="ml-220 label-width label-align" for="partsearch-part_code">调入仓库代码</label><label>:</label>
            <span class="Iwh_code"><?= $data[0]['wh_code2'] ?></span>
            <input type="hidden" id="Iwh_code" class="value-alig" name="InvChangeh[Iwh_code]"
                   value="<?= $data[0]['wh_code2'] ?>">
    </div>
    <div class="mb-10">
            <label class="label-width label-align add-bottom" for="partsearch-yn">制单人</label><label>:</label>
            <span class="value-width"><?= $data[0]['create_by'] ?></span>
            <label class="ml-220 label-width label-align" for="partsearch-part_code">制单日期</label><label>:</label>
            <span class="value-width"><?= $data[0]['create_at'] ?></span>
    </div>
    <h1 class="head-second" style="text-align:center;">
        商品基本信息
        <span style="float:right;margin-right:15px;">
            <a id="list_btn" class="icon-reorder"></a>
            <a id="delete_btn" class="icon-remove"></a>
            <input type="hidden" id="products" value="<?= implode(",", array_column($childs, 'pdt_id')) ?>">
        </span>
    </h1>
    <div style="overflow:auto;">
        <table class="table" style="width:1380px;">
            <thead>
            <tr>
                <th style="width:30px;">序号</th>
                <th style="width:20px;"><input type="checkbox" style="height:auto;vertical-align:middle;"></th>
                <th style="width:150px;">料号</th>
                <th style="width:150px;">商品名称</th>
                <th style="width:100px;">品牌</th>
                <th style="width:150px;">规格型号</th>
                <th style="width:150px;">批次</th>
                <th style="width:100px;">现有库存量</th>
                <th style="width:100px;">调拨数量</th>
                <th style="width:100px;">出仓储位</th>
<!--                <th style="width:100px;">入仓储位</th>-->
                <th style="width:100px;">单位</th>
                <th style="width: 100px;">操作</th>
            </tr>
            </thead>
            <tbody id="product_tbody">
            <?php if (empty($data)) { ?>
                <tr data-id="1">
                    <td>1</td>
                    <td><input type='checkbox' style='height:auto;vertical-align:middle;'></td>
                    <input class='pdt_id' type='hidden' name=''></td>
                    <td><input type='text' style='width:100%;text-align:center;' class='pdt_no easyui-validatebox'
                               name="product[0][InvChangel][pdt_no]"
                               data-options='required:true'
                               data-url='<?= Url::to(['/warehouse/allocation/get-pdtno']) ?>'></td>
                    <td class='pdt_name'></td>
                    <td class='BRAND_NAME_CN'></td>
                    <td class='pdt_model'></td>
                    <td class="pdt_bach"><input type="hidden" class="_pdtbach" name="product[0][InvChangel][chl_bach]"></td>
                    <td class='invt_num'><input type="hidden" name="product[0][InvChangel][before_num1]"></td>
                    <td><input type='text' style='width:100px;text-align:center;' class='easyui-validatebox chl_num'
                               data-options="required:'true',validType:'intnum'"
                               name='product[0][InvChangel][chl_num]'></td>
                    <td><input type='hidden' style='text-align:center;' class='easyui-validatebox Ost_id'
                               name='product[0][InvChangel][st_id]'></td>
                    <td class='unit_name'></td>
                    <td class='operate_td'><a class="icon-minus delete_btn mr-20"></a>&nbsp;&nbsp;
                        <a class='icon-refresh reset_btn' onclick='refreshRow(this)'></a></td>
                </tr>
            <?php } else { ?>
                <?php foreach ($data as $key => $val) { ?>
                    <tr data-id="<?= $key + 1 ?>">
                        <td><?= $key + 1 ?></td>
                        <td><input type="checkbox" style="height:auto;vertical-align:middle;"></td>
                        <td><input type='text' style='width:100%;text-align:center;' value="<?= $val['part_no'] ?>"
                                   name="product[<?= $key ?>][InvChangel][pdt_no]"
                                   class='pdt_no easyui-validatebox' data-options='required:true'
                                   data-url='<?= Url::to(['/warehouse/allocation/get-pdtno']) ?>'></td>
                        <td><?= $val['pdt_name'] ?></td>
                        <td><?= $val['brand'] ?></td>
                        <td><?= $val['tp_spec'] ?></td>
                        <td><input type="hidden" name="product[<?= $key ?>][InvChangel][chl_bach]"
                                   value="<?= $val['chl_bach'] ?>"/><?= $val['chl_bach'] ?></td>
                        <td><?= $val['before_num1'] ?><input type="hidden" name="product[<?= $key ?>][InvChangel][before_num1]"></td>
                        <td><input class="width-100 chl_num" name="product[<?= $key ?>][InvChangel][chl_num]"
                                   value="<?= sprintf('%.2f', $val['chl_num']) ?>"></td>
                        <td class="Ost_id"><input  type="hidden" class="Ost_id" name="product[<?= $key ?>][InvChangel][st_id]"
                                   value="<?= $val['stid'] ?>"/><?= $val['st_id'] ?></td>
                        <td><?= $val['unit'] ?></td>
                        <td class="operate_td"><a class="icon-minus delete_btn mr-20"></a>&nbsp;&nbsp;
                            <a class="icon-refresh reset_btn" onclick="refreshRow(this)"></a></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="mb-10" style="height: 20px;"></div>
    <button id="add_btn" class="button-blue ml-20" type="button">添加商品</button>
    <div class="space-20 mb-10"></div>
    <div class="text-center">
        <button id="save_btn" class="button-blue mr-20" type="submit">保存</button>
        <button id="submit_btn" class="button-blue mr-20" type="submit" style="margin-left: 40px;">提交</button>
        <button class="button-white" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    var locationInfo =<?=json_encode($data['locationInfo'] ? $data['locationInfo'] : [])?>;//出
    var locationInfos =<?=json_encode($data['locationInfos'] ? $data['locationInfos'] : [])?>;//入
    $(function () {
        var button_flag=0;
        //保存
        $("#save_btn").click(function(){
            button_flag=0;
        });
        //提交
        $("#submit_btn").click(function(){
            button_flag=1;
        });
        ajaxSubmitForm($("#update_form"),'',function(data){
            if (data.flag == 1) {
                if(button_flag==1){
                    var id=data.id;
                    var url="<?=Url::to(['views'])?>?id="+id;
                    var type=36;
                        console.log(id+":"+type);
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
        //复选框
        var $content = $(".content");
        var $productTbody = $("#product_tbody");
        $content.on("click", "th input[type='checkbox']", function () {
            $content.find("td input[type='checkbox']").prop("checked", $(this).prop("checked"));
        });
        $content.on("click", "td input[type='checkbox']", function () {
            var num = $content.find("td input[type='checkbox']:not(:checked)").length;
            $content.find("th input[type='checkbox']").prop("checked", !num);
        });

        $(".content").click(function () {
            $("#checkAll").prop("checked", $("#data :checked").size() == $("#data :checkbox").size() && $("#data :checked").size() > 0);
        });
        $("#list_btn").click(function () {
            if ($("#Owh_name").val() == "") {
                layer.alert("请先选择出仓仓库!", {icon: 2});
            }
            else {
                $.fancybox({
                    type: "iframe",
                    width: 800,
                    height: 500,
                    autoSize: false,
                    padding: 0,
                    href: "<?=Url::to(['select-product'])?>" + "?wh_id=" + $("#Owh_name").val()
                });
            }
        });

        $("#product_tbody").delegate('.pdt_no', 'click', function () {
            if ($("#Owh_name").val() == "") {
                layer.alert('请选择出仓仓库！', {icon: 2});
                return false;
            }
        });

        //获取单笔料号
        $(document).on("change",".pdt_no",function () {
            var tr = $(this).parents('tr');
            //alert($(this).val());
//            alert($(this).data('url'));
            if ($("#Owh_name").val() == "") {
                layer.alert("请先选择出仓仓库!", {icon: 2});
            }else {
                if($(this).val()==null||$(this).val()=="")
                {
                    layer.alert("请输入料号", {icon: 2, time: 5000});
                }
                else {
                    $.ajax({
                        url: "<?=Url::to(['select-product'])?>" + "?wh_id=" + $("#Owh_name").val(),
                        data: {
                            'pdt_no': $(this).val()
                        },
                        async: false,
                        cache: false,
                        success: function (data) {
                            data = JSON.parse(data);
//                    alert(data);
                            if (data['rows'] == '') {
                                layer.alert('料号不存在！', {icon: 2});
                                return false;
                            } else {
//                        alert(tr.find('.pdt_no').val());
                                //tr.find(".pdt_id").val(data['rows'][0].pdt_id);
                                tr.find(".pdt_name").html(data['rows'][0].pdt_name);
                                tr.find(".BRAND_NAME_CN").html(data['rows'][0].brand);
                                tr.find(".pdt_model").html(data['rows'][0].tp_spec);
                                tr.find(".pdt_bach").html(data['rows'][0].L_invt_bach);
                                tr.find("._pdtbach").val(data['rows'][0].L_invt_bach);
                                tr.find(".invt_num").html(data['rows'][0].L_invt_num);
                                tr.find(".Ost_id").html(data['rows'][0].st_code);
                                tr.find(".unit_name").html(data['rows'][0].unit);
                                return true;
                            }
                        }
                    })
                }
            }
        });

        $.extend($.fn.validatebox.defaults.rules, {
            //商品代码验证，并带出相关信息
            productCodeValidate: {
                validator: function (value) {
                    if ($("#Owh_name").val() == "") {
                        layer.alert('请选择出仓仓库！', {icon: 2});
                        return false;
                    }
                    else {
                        var data = $.ajax({
                            url: $(this).data('url'),
                            data: {
                                'pdt_no': value,
                                'wh_id': $("#Owh_name").val()
                            },
                            async: false,
                            cache: false
                        }).responseText;
//                    console.log(data);
                        data = JSON.parse(data);
//                    alert(data);
                        if (data['rows'] == '') {
                            layer.alert('料号不存在！', {icon: 2});
                            return false;
                        } else {
                            var tr = $(this).parents("tr");
                            //tr.find(".pdt_id").val(data['rows'][0].pdt_id);
                            tr.find(".pdt_name").html(data['rows'][0].pdt_name);
                            tr.find(".BRAND_NAME_CN").html(data['rows'][0].BRAND_NAME_CN);
                            tr.find(".pdt_model").html(data['rows'][0].attr_name);
                            tr.find(".invt_num").html(data['rows'][0].invt_num);
//                            tr.find(".Ost_code").html(data['rows'][0].st_code);
                            tr.find(".unit_name").html(data['rows'][0].unit_name);
                            return true;

                        }
                    }
                },
                message: '料号不存在'
            }
        });
        //删除
        $content.on("click", ".delete_btn", function () {
            $(this).parents("tr").remove();
            $productTbody.find("tr").each(function (index) {
                $(this).find("td:first").text(index + 1);
            });
            if ($productTbody.find("tr").length == 1) {
                $(".operate_td").html("<a class='icon-refresh reset_btn'></a>");
            }
        });
        $content.on("click", "#delete_btn", function () {
            if ($content.find("td input[type='checkbox']:checked").length == 0) {
                layer.alert('请选择要删除的商品！', {icon: 2});
                return false;
            }
            $content.find("td input[type='checkbox']:checked").parents("tr").remove();
            $content.find("th input[type='checkbox']").prop("checked", false);
            $productTbody.find("tr").each(function (index) {
                $(this).find("td:first").text(index + 1);
            });
            $("#add_btn").click();
        });
        //添加
        $("#add_btn").click(function () {
            var num = $productTbody.find("tr").length;
            var tr = "<tr data-id='" + num + "'>";
            tr += "<td></td>";
            tr += "<td><input type='checkbox' style='height:auto;vertical-align:middle;'>";
            tr += "<input class='pdt_id' type='hidden'></td>";
            tr += "<td><input type='text' style='width:100%;text-align:center;' class='pdt_no easyui-validatebox' name='product[" + num + "][InvChangel][pdt_no]' data-options='required:true' data-url='<?=Url::to(['/warehouse/allocation/get-pdtno'])?>'></td>";
            tr += "<td class='pdt_name'></td>";
            tr += "<td class='BRAND_NAME_CN'></td>";
            tr += "<td class='pdt_model'></td>";
            tr += "<td class='pdt_bach'><input type='hidden' class='_pdtbach' name='product[" + num + "][InvChangel][chl_bach]'></td>";
            tr += "<td class='invt_num'><input type='hidden' name='product[" + num + "][InvChangel][before_num1]'></td>";
            tr += "<td><input type='text' style='text-align:center;' class='easyui-validatebox chl_num' " +
                "data-options=\"required:'true',validType:'intnum'\""+
                "name='product[" + num + "][InvChangel][chl_num]'></td>";
            tr += "<td><input type='hidden' style='text-align:center;' class='easyui-validatebox Ost_id'  " +
                "name='product[" + num + "][InvChangel][st_id]'></td>";
//            tr += "<td><select class='Ost_id' style='width: 100px;;' name='product[" + num + "][InvChangel][Ost_id]'><option value=''>请选择</option>";
//            if (locationInfo.length > 0) {
//                $.each(locationInfo, function (i, n) {
//                    tr += "<option value='" + n.st_id + "'>" + n.st_code + "</option>";
//                })
//            }
//            tr += "</select></td>";
//            tr += "<td><select class='lor_id' style='width: 100px;;' name='product[" + num + "][InvChangel][Ist_id]'><option value=''>请选择</option>";
//            if (locationInfos.length > 0) {
//                $.each(locationInfos, function (i, n) {
//                    tr += "<option value='" + n.st_id + "'>" + n.st_code + "</option>";
//                })
//            }
//            tr += "</select></td>";
            tr += "<td class='unit_name'></td>";
            tr += "<td class='operate_td'><a class='icon-minus delete_btn mr-20'></a> &nbsp;&nbsp;" +
                "<a class='icon-refresh reset_btn' onclick='refreshRow(this)'></a></td>";
            tr += "</tr>";
            num++;
            $productTbody.append(tr).find("tr").each(function (index) {
                $(this).find("td:first").text(index + 1);
            });
            $.parser.parse($productTbody.find("tr:last"));
            if ($productTbody.find("tr").length > 1) {
                $(".operate_td").html("<a class='icon-minus delete_btn mr-20'></a> &nbsp;&nbsp;" +
                    "<a class='icon-refresh reset_btn' onclick='refreshRow(this)'></a>");
            }
            setMenuHeight();
        });

        //料号
        $content.on("change", ".pdt_no", function () {
            $(this).validatebox({
                required: true,
                validType: ["tdSame", "productCodeValidate"],
                delay: 1000000
            });
        });

        //修改入仓号
//        $("#Iwh_name").change(function () {
//            var id = $(this).val();
////            alert(id);
//            if (id == '') {
//                $(".lor_id").html("<option value=''>请选择</option>");
//                locationInfos = [];
//                return false;
//            }
//            $.ajax({
//                url: "<?//=Url::to(['get-warehouse-info'])?>//",
//                data: {"id": id},
//                dataType: "json",
//                success: function (data) {
//                    if (data.locationInfo.length > 0) {
//                        locationInfos = data.locationInfo;
//                        var $lorId = $(".lor_id");
//                        if ($lorId.length > 0) {
//                            var str = "<option value=''>请选择</option>";
//                            $.each(data.locationInfo, function (i, n) {
//                                str += "<option value='" + n.st_id + "'>" + n.st_code + "</option>";
//                            });
//                            $lorId.html(str);
//                            console.log($lorId.html());
//                        }
//                    } else {
//                        $(".lor_id").html("<option value=''>请选择</option>");
//                        locationInfos = [];
//                    }
//                }
//            });
//        });
//        //修改出仓号
//        $("#Owh_name").change(function () {
//            var id = $(this).val();
////            alert(id);
//            if (id == '') {
//                $(".Ost_id").html("<option value=''>请选择</option>");
//                locationInfo = [];
//                return false;
//            }
//            $.ajax({
//                url: "<?//=Url::to(['get-warehouse-info'])?>//",
//                data: {"id": id},
//                dataType: "json",
//                success: function (data) {
//                    if (data.locationInfo.length > 0) {
//                        locationInfo = data.locationInfo;
//                        var $lorId = $(".Ost_id");
//                        if ($lorId.length > 0) {
//                            var str = "<option value=''>请选择</option>";
//                            $.each(data.locationInfo, function (i, n) {
//                                str += "<option value='" + n.st_id + "'>" + n.st_code + "</option>";
//                            });
//                            $lorId.html(str);
//                            console.log($lorId.html());
//                        }
//                    } else {
//                        $(".Ost_id").html("<option value=''>请选择</option>");
//                        locationInfo = [];
//                    }
//                }
//            });
//        });
    });
    function refreshRow(t) {
        $(t).parents("tr").find("input").val("");
        $(t).parents("tr").find("select").val("");
    }
    function OCheckCode(obj) {
        $("#Owh_code").val($(obj).find(" option:selected").data("code"));
        $(".Owh_code").text($(obj).find(" option:selected").data("code"));
    }
    function ICheckCode(obj) {
        $("#Iwh_code").val($(obj).find(" option:selected").data("code"));
        $(".Iwh_code").text($(obj).find(" option:selected").data("code"));
    }

    //判断调拨数量与库存量的对比
    $(document).on("change",".chl_num",function () {
//        alert(1111);
        $_num = $(this);
        var _bsinv=$_num.parents("tr").find("td").eq(7).text();
//            alert(_bsinv);
        var num=$(this).val();
        if(parseInt(num)>parseInt(_bsinv))
        {
            layer.alert("调拨数量必须小于库存量",{icon:2});
            $_num.addClass("validatebox-invalid");
            return false;
        }else {
            $_num.removeClass("validatebox-invalid");
        }
        if(num==""||num==null){
//            alert(0);
            $_num.validatebox({required:true,validType: 'intnum'});
            return false;
        }
    });

</script>
