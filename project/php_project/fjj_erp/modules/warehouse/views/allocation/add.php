<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/31
 * Time: 下午 02:57
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '增加调拨单列表';
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
    <?php ActiveForm::begin(['id' => 'add_form']); ?>
    <h1 class="head-second" style="text-align:left;">调拨单基本信息</h1>
    <div class="mb-10">
            <input type="hidden" class="_whid">
            <label class="label-width label-align add-bottom" for="partsearch-yn">
                <span class="red" title="*">*</span>调拨类型</label><label >:</label>
            <select id="partsearch-yn" class="value-width value-align easyui-validatebox"
                    data-options="required:'true'" name="InvChangeh[chh_type]">
                <option value="">请选择...</option>
                <?php foreach ($businessname as $val) { ?>
                    <option value="<?= $val['business_type_id'] ?>"><?= $val['business_type_desc'] ?></option>
                <?php } ?>
            </select>
         <?php if($usertype==""||$usertype==null){?>
            <label class="ml-220 label-width label-align" for="partsearch-yn">调拨单位</label><label >:</label>
            <input type="text" id="business_type_desc" class="value-align" name="InvChangeh[depart_id]"
                  disabled="disabled" value=" <?= $model['organization_name'] ?>">
         <?php }else{?>
             <label class="ml-220 label-width label-align" for="partsearch-yn">
                 <span class="red" title="*">*</span>调拨单位</label><label >:</label>
             <select id="partsearch-yn" class="value-width value-align easyui-validatebox"
                     data-options="required:'true'" name="InvChangeh[depart_id]">
                 <option value="">请选择...</option>
                 <?php foreach ($dpt as $val) { ?>
                     <option value="<?= $val['organization_id'] ?>"><?= $val['organization_name'] ?></option>
                 <?php } ?>
             </select>
        <?php };?>
    </div>
    <div class="mb-10">
            <label class="label-width label-align add-bottom" for="partsearch-part_code">
                <span class="red" title="*">*</span>调出仓库</label><label >:</label>
            <select id="Owh_name" class="value-width value-align easyui-validatebox" data-options="required:'true'"
                    name="InvChangeh[wh_id]" onclick="OCheckCode(this)">
                <option value="">请选择...</option>
                <?php foreach ($whname as $val) { ?>
                    <option data-code="<?= $val['wh_code'] ?>"
                            value="<?= $val['wh_id'] ?>"><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
        <label class="ml-220 label-width label-align" for="partsearch-yn">调出仓库代码</label><label >:</label>
        <span id="Owh_code"></span>
        <input type="hidden"  class="width-150 _ocode" name="InvChangeh[Owh_code]" value="">

    </div>
    <div class="mb-10">
            <label class="label-width label-align add-bottom" for="partsearch-part_code">
                <span class="red" title="*">*</span>调入仓库</label><label>:</label>
            <select id="Iwh_name" class="value-width easyui-validatebox" data-options="required:'true'"
                    name="InvChangeh[wh_id2]" onclick="ICheckCode(this)">
                <option value="">请选择...</option>
                <?php foreach ($whname as $val) { ?>
                    <option data-code="<?= $val['wh_code'] ?>"
                            value="<?= $val['wh_id'] ?>"><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
            <label class="ml-220 label-width label-align" for="partsearch-part_code">调入仓库代码</label><label>:</label>
            <span class="I_code value-width"></span>
            <input type="hidden" id="Iwh_code" class="width-150" name="InvChangeh[Iwh_code]" disabled="disabled"
                   value=""
            >
            <div class="help-block"></div>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom" for="partsearch-yn">制单人</label><label >:</label>
        <input type="hidden" name="InvChangeh[create_by]" value="<?= $model['staff_id'] ?>">
        <span class="value-width"><?=$model['staff_name'] ?></span>
        <input type="hidden" class="width-150 select-date"
               name="InvChangeh[username]" disabled="disabled"
               value="<?= $model['staff_name'] ?> ">
<!--            <label class="label-width label-align add-bottom" for="partsearch-part_code">状态</label><label >:</label>-->
<!--            <span class="value-width">待提交</span>-->
            <label class="ml-220 label-width label-align" for="partsearch-part_code">制单日期</label><label >:</label>
            <span id="_startdate" class="value-width"></span>
    </div>
<!--    <div class="mb-10">-->
<!--            <label class="label-width label-align add-bottom" for="partsearch-yn">制单人</label><label >:</label>-->
<!--            <input type="hidden" name="InvChangeh[create_by]" value="--><?//= $model['staff_id'] ?><!--">-->
<!--            <span class="value-width">--><?//=$model['staff_name'] ?><!--</span>-->
<!--            <input type="hidden" class="width-150 select-date"-->
<!--                   name="InvChangeh[username]" disabled="disabled"-->
<!--                   value="--><?//= $model['staff_name'] ?><!-- ">-->
<!--            <label class="ml-220 label-width label-align" for="partsearch-yn">费用代码</label><label >:</label>-->
<!--            <span></span>-->
<!--            <input type="hidden" class="width-150 "-->
<!--                   name="InvChangeh[whp_id]"-->
<!--                   value="">-->
    <!--    </div>-->
    <h1 class="head-second" style="text-align:left;">
        商品基本信息
        <span style="float:right;margin-right:15px;">
            <a id="list_btn" class="icon-reorder"></a>
            <!--            <a id="add_btn" class="icon-plus"></a>-->
            <a id="delete_btn" class="icon-remove"></a>
        </span>
        <input type="hidden" id="products" value="<?= implode(",", array_column($childs, 'pdt_id')) ?>">
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
            <tr data-id="1">
                <td>1</td>
                <td><input type='checkbox'
                           style='height:auto;vertical-align:middle;'>
                </td>
                <td><input type='text' style='width:100%;text-align:center;' class='pdt_no easyui-validatebox'
                           name='product[1][InvChangel][pdt_no]'
                           data-options="required:true"
                           ></td>
                <td class='pdt_name'></td>
                <td class='BRAND_NAME_CN'></td>
                <td class='pdt_model'></td>
                <td class="pdt_bach"><input type="hidden" class="_pdtbach" name="product[1][InvChangel][chl_bach]"></td>
                <td class='invt_num'><input type="hidden" name="product[1][InvChangel][before_num1]"></td>
                <td><input type='text' style='text-align:center;' class='easyui-validatebox chl_num'
                           data-options="required:'true',validType:'intnum'" name='product[1][InvChangel][chl_num]'></td>
                <td><span class="ostid"></span><input type='hidden' style='text-align:center;' class='easyui-validatebox Ost_id'
                           name='product[1][InvChangel][st_id]'></td>
                <td class='unit_name'></td>
                <td class='operate_td'><a class='icon-minus delete_btn mr-20'></a> &nbsp;&nbsp;
                    <a class='icon-refresh reset_btn' onclick='refreshRow(this)'></a></td>
            </tr>
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
    var locationInfo =null;
    var locationInfos =null;

//    function AddValue(rows)
//    {
//        console.log(rows);
//    }
    $(function () {
        //获取当前时间
        var myDate = new Date();
        $("#_startdate").text(myDate.toLocaleDateString())

        var button_flag=0;

        //保存
        $("#save_btn").click(function(){
            button_flag=0;
        });
        //提交
        $("#submit_btn").click(function(){
            button_flag=1;
        });
            ajaxSubmitForm($("#add_form"),'',function(data){
//                console.log(data);
                if (data.flag == 1) {
                    if(button_flag==1){
                        var id=data.id;
                        var url="<?=Url::to(['views'],true)?>?id="+id;
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
            var out=$("#Owh_name").val();
            if (out == "") {
                layer.alert("请先选择出仓仓库!", {icon: 2});
            }
            else {
                $.fancybox({
                    href: "<?=Url::to(['select-product'])?>" + "?wh_id=" + out,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 500
                });
            }
        });

        //获取单笔料号
        $(document).on("blur", ".pdt_no", function () {
            var s=$(this);
            var row = $(this).parent().parent().first().find("td").eq(0).text();//行数
            var pro=$(this).val();
            var out = $("#Owh_name").val();//出仓仓库
            if (out != "") {

            $.fancybox({
                href: "<?=Url::to(['select-product'])?>" + "?wh_id=" + out +"&kwd="+ pro +"&row="+row,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 400
            });
            }else {
                layer.alert("请先选择出仓仓库!", {icon: 2});
            }
        });

        //删除
        $content.on("click", ".delete_btn", function () {
            $(this).parents("tr").remove();
            $productTbody.find("tr").each(function (index) {
                $(this).find("td:first").text(index + 1);
            });
            if ($productTbody.find("tr").length == 1) {
                $(".operate_td").html("<a class='icon-minus delete_btn mr-20'></a>&nbsp;&nbsp;" +
                    "<a class='icon-refresh reset_btn' onclick='refreshRow(this)'></a>");
            }
            if ($productTbody.find("tr").length == 0) {
                $("#add_btn").click();
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
            if ($productTbody.find("tr").length == 0) {
                $("#add_btn").click();
            }
        });
        //添加
        $("#add_btn").click(function () {
            var num = $productTbody.find("tr:last").data("id") + 1;
            var tr = "<tr data-id=" + num + ">";
            tr += "<td></td>";
            tr += "<td><input type='checkbox' style='height:auto;vertical-align:middle;'>";
            tr += "<input class='pdt_id' type='hidden' name='product[" + num + "][InvChangel][pdt_id]'></td>";
            tr += "<td><input type='text' value='' style='width:100%;text-align:center;' class='pdt_no easyui-validatebox' name='product[" + num + "][InvChangel][pdt_no]' data-options='required:true' data-url='<?=Url::to(["/warehouse/allocation/get-pdtno"])?>'></td>";
            tr += "<td class='pdt_name'></td>";
            tr += "<td class='BRAND_NAME_CN'></td>";
            tr += "<td class='pdt_model'></td>";
            tr += "<td class='pdt_bach'><input type='hidden' class='_pdtbach' name='product[" + num + "][InvChangel][chl_bach]'></td>";
            tr += "<td class='invt_num'><input type='hidden' name='product[" + num + "][InvChangel][before_num1]'></td>";
            tr += "<td><input type='text' style='text-align:center;' " +
                "class='easyui-validatebox chl_num' " +
                "data-options=\"required:'true',validType:'intnum'\""+
                "name='product[" + num + "][InvChangel][chl_num]'></td>";
            tr += "<td><span class='ostid'></span><input type='hidden' style='width:100px;text-align:center;' class='easyui-validatebox Ost_id'  " +
                "name='product[" + num + "][InvChangel][st_id]'></td>";
            tr += "<td class='unit_name'></td>";
            tr += "<td class='operate_td'><a class='icon-minus delete_btn mr-20'></a> &nbsp;&nbsp;" +
                "<a class='icon-refresh reset_btn' onclick='refreshRow(this)'></a></td>";
            tr += "</tr>";
            num++;
            $productTbody.append(tr).find("tr").each(function (index) {
                $(this).find("td:first").text(index + 1);
            });
            $.parser.parse($productTbody.find("tr:last"));
//            $("#product_tbody .pdt_no ").validatebox({
//                required: true,
//                validType: ["tdSame", "productCodeValidate"],
//                delay: 1000000
//            });
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
        //入仓号
        $("#Iwh_name").change(function () {
            var id = $(this).val();
//            alert(id);
            if (id == '') {
                $(".lor_id").html("<option value=''>请选择</option>");
                locationInfos = [];
                return false;
            }
            $.ajax({
                url: "<?=Url::to(['get-warehouse-infos'])?>",
                data: {"Iid": id},
                dataType: "json",
                success: function (data) {
                    if (data.locationInfo.length > 0) {
                        locationInfos = data.locationInfo;
                        var $lorId = $(".lor_id");
                        if ($lorId.length > 0) {
                            var str = "<option value=''>请选择</option>";
                            $.each(data.locationInfo, function (i, n) {
                                str += "<option value='" + n.st_id + "'>" + n.st_code + "</option>";
                            });
                            $lorId.html(str);
//                            console.log($lorId.html());
                        }
                    } else {
                        $(".lor_id").html("<option value=''>请选择</option>");
                        locationInfos = [];
                    }
                }
            });
        });
        //出仓号
        $("#Owh_name").change(function () {
            var id = $(this).val();
//            alert(id);
            if (id == '') {
                $(".Ost_id").html("<option value=''>请选择</option>");
                locationInfo = [];
                return false;
            }
            $.ajax({
                url: "<?=Url::to(['get-warehouse-infos'])?>",
                data: {"Oid": id},
                dataType: "json",
                success: function (data) {
                    if (data.locationInfo.length > 0) {
                        locationInfo = data.locationInfo;
                        var $lorId = $(".Ost_id");
                        if ($lorId.length > 0) {
                            var str = "<option value=''>请选择</option>";
                            $.each(data.locationInfo, function (i, n) {
                                str += "<option value='" + n.st_id + "'>" + n.st_code + "</option>";
                            });
                            $lorId.html(str);
//                            console.log($lorId.html());
                        }
                    } else {
                        $(".Ost_id").html("<option value=''>请选择</option>");
                        locationInfo = [];
                    }
                }
            });
        });
    });
    function refreshRow(t) {
        $(t).parents("tr").find(".chl_num").val("");
        $(t).parents("tr").find("select").val("");
    }
    function OCheckCode(obj) {
        $("#Owh_code").text($(obj).find(" option:selected").data("code"));
        $("._ocode").val($(obj).find(" option:selected").data("code"));
    }
    function ICheckCode(obj) {
        $("#Iwh_code").val($(obj).find(" option:selected").data("code"));
        $(".I_code").text($(obj).find(" option:selected").data("code"));
    }

    //判断调拨数量与库存量的对比
    $(document).on("change",".chl_num",function () {
            $_num = $(this);
            var _bsinv=$_num.parents("tr").find("td").eq(7).text();
//            alert(_bsinv);
            var num=$(this).val();
            if(parseInt(num)>parseInt(_bsinv))
            {
                layer.alert("调拨数量必须小于库存量",{icon:2});
                $(".chl_num").addClass("validatebox-invalid");
                return false;
            }else {
                $(".chl_num").removeClass("validatebox-invalid");
            }
        });


</script>
