<?php
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>
<style>
    .width-120{
        width: 120px;
    }
    .width-200{
        width: 200px;
    }
    .mb-20{
        margin-bottom: 20px;
    }
    .inputstyle{
        border: none;
        background-color: white !important;
    }    .inputstyle{
        border: none;
        background-color: white !important;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <div class="space-40"></div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120"><span class="red">*</span>区域代码<label>：</label></label>
        <input class="width-200 easyui-validatebox code" type="text" name="CrmSalearea[csarea_code]" style="ime-mode:disabled"
               value="<?= $model['csarea_code'] ?>"  data-options="required:'true',validType:'unique',delay:10000,validateOnBlur:'true'" maxlength="20"  data-act="<?=Url::to(['validate'])?>" data-id="<?=$model['csarea_id'];?>" data-attr="csarea_code">
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120"><span class="red">*</span>区域名称<label>：</label></label>
        <input class="width-200 easyui-validatebox" type="text" name="CrmSalearea[csarea_name]"
               data-options="required:'true'" value="<?= $model['csarea_name'] ?>" maxlength="20" placeholder="最多输入20字"></div>
    <div id="areaSelect">
        <?php if (!empty($dis)) { ?>
            <?php foreach ($dis as $key => $item) { ?>
                <div class="mb-10 area_select">
                    <?php if ($key == 0) { ?>
                        <label class="width-120 vertical-top"><span class="red">*</span>包含地区<label>：</label></label>
                    <?php } else { ?>
                        <label class="width-120 vertical-top" style="visibility:hidden"><span class="red">*</span>包含地区<label>：</label></label>
                    <?php } ?>
                    <select class="width-200 easyui-validatebox vertical-top disName" data-options="required:'true'"
                            name="CrmDistrictSalearea[district_id][]">
                        <option value="">--请选择--</option>
                        <?php if (!empty($districtAll)) { ?>
                            <?php foreach ($districtAll as $val) { ?>
                                <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $item['district_id'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <select class="width-200 vertical-top disCity" name="CrmDistrictSalearea[city_id][]">
                        <option value="">--请选择--</option>
                        <?php if (!empty($city[$key])) { ?>
                            <?php foreach ($city[$key] as $k => $val) { ?>
                                <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $item['city_id'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <a class="icon-minus ml-20" onclick="del_contacts(this)"> 删除地区</a>
                    <a class="icon-plus ml-20" onclick="add_contacts(this)" style="display: none"> 添加地区</a>
<!--                    --><?php //if ($key== 0) { ?>
<!--                        <a class="icon-plus ml-20" onclick="add_contacts(this)"> 添加地区</a>-->
<!--                    --><?php //} ?>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="mb-10 area_select">
                <label class="label-width qlabel-align width-120 vertical-top"><span class="red">*</span>包含地区<label>：</label></label>
                <select class="width-200 easyui-validatebox vertical-top disName" data-options="required:'true'"
                        name="CrmDistrictSalearea[district_id][]">
                    <option value="">请选择...</option>
                    <?php if (!empty($districtAll)) { ?>
                        <?php foreach ($districtAll as $val) { ?>
                            <option value="<?= $val['district_id'] ?>" <?= $val['district_id'] == $item['district_id'] ? 'selected' : null ?>><?= $val['district_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <select class="width-200 vertical-top disCity" name="CrmDistrictSalearea[city_id][]">
                    <option value="1">请选择...</option>
                </select>
                <a class="icon-minus ml-20" style="display: none" onclick="del_contacts(this)"> 删除地区</a>
                <a class="icon-plus ml-20" onclick ="add_contacts(this)"> 添加地区</a>
            </div>
        <?php } ?>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width-120">状态<label>：</label></label>
        <select class="width-200 easyui-validatebox status" type="text" name="CrmSalearea[csarea_status]"
                data-options="required:'true'">
            <?php if (!empty($status)) { ?>
                <?php foreach ($status as $key => $val) { ?>
                    <option value="<?= $key ?>" <?= isset($model['csarea_status']) && $model['csarea_status'] == $key ? "selected" : null ?>><?= $val ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <div class="space-20"></div>
    <div class="text-center" style="margin-top: 50px">
        <button type="submit" class="button-blue-big sub">保存</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
</div>
<?php ActiveForm::end() ?>

<script>
    $(function () {
        $('.sub').click(function () {
            $('.disCity option').attr('disabled', false);
        })
        ajaxSubmitForm($("#add-form"));
        $(document).on("change",".disName", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getCity($select, $url, "select");
        });
        var code=$(".code").val();
        if(code!=""&&code!=null){
            $(".code").attr({ readonly: 'true' }).addClass("inputstyle");
            $(".content span:eq(0) ").remove();
        }
        var tt= $(".area_select").length;
        if(tt==1){
            $(".area_select a:eq(0)").css("display","none");
            $(".area_select a:eq(1)").css("display","");
        }
        if(tt>1){
            $(".area_select").last().children("a:eq(1)").css("display","");
        }
        //判断是否为IE浏览器
        if(!!window.ActiveXObject || "ActiveXObject" in window){
        }
        else {
            $(".code").bind("keyup",function(){
                $(".code").val($(".code").val().replace(/[\u4e00-\u9fa5]/g,''));
            });
        }
    });
//    window.onload = function(){
//        console.log($('.disCity').find('option').length);
//        for($i=0;$i<$('.disCity').find('option').length;$i++){
//            <?php //foreach ($dis as $key => $item) { ?>
//            if(<?//= $item['city_id'] ?>// == null){
//                $('.disCity option')[i].disabled = "";
//            }
//            if ($('.disCity option')[i].value == <?//= $item['city_id'] ?>//) {
//                $('.disCity option')[i].disabled = "disabled";
//            }
//            <?php //} ?>
//        }
//    }
    function add_contacts(obj) {
        $(obj).prev("a").css("display","");
        $(obj).css("display","none");
        var a = $(".disName").length;
        var b = a;
        b += 1;
        var obj = $("#areaSelect").append(
            '<div class="mb-10 area_select">' +
            '<label style="visibility:hidden" class="label-width qlabel-align width-120 vertical-top"><span class="red">*</span>包含地区<label>：</label></label>&nbsp;' +
            '<select class="width-200 easyui-validatebox vertical-top disName" data-options="required:\'true\'" name="CrmDistrictSalearea[district_id][]">' +
            '<option value="">请选择...</option>' +
            "<?php if (!empty($districtAll)) { ?>" +
            "<?php foreach ($districtAll as $val) { ?>" +
            '<option value="' + "<?= $val['district_id'] ?>" + '"' + "<?= $val['district_id'] == $districtAll['twoLevelId'] ? 'selected' : null ?>" + '>' + "<?= $val['district_name'] ?>" + '</option>' +
            "<?php } ?>" +
            "<?php } ?>" +
            '</select>&nbsp;' +
            '<select class="width-200 vertical-top disCity" name="CrmDistrictSalearea[city_id][]">' +
            '<option value="">请选择...</option>' +
            '</select>' +
            '<a class="icon-minus ml-20" onclick="del_contacts(this)"> 删除地区</a>' +
            '<a class="icon-plus ml-20" onclick="add_contacts(this)"> 添加地区</a>' +
            '</div>'
        );
        $.parser.parse(obj);
        a++;
//        $(".disName").on("change", function () {
//            var $select = $(this);
//            var $url = "<?//=Url::to(['/ptdt/firm/get-district']) ?>//";
//            getCity($select, $url, "select");
//        });
    }

    function del_contacts(obj) {
        var a = $(".area_select").length;//总长度
        var b=$(obj).parent('#areaSelect .area_select').index();//要删除的div的索引
        if(b==1&&a==2){//删除的为第二行并且只有两行
            $(obj).parent('#areaSelect .area_select').prev().children("a:eq(1)").css("display", "");
            $(obj).parent('#areaSelect .area_select').prev ().children("a:eq(0)").css("display", "none");
            $(obj).parent('.area_select').remove();
        }else {
                if(b+1==a&&a>2){//删除的为最后一行，并且总行数>2
                    $(obj).parent('#areaSelect .area_select').prev().children("a:eq(1)").css("display", "");
                    $(obj).parent('.area_select').remove();
                }else{
                    if(b==0&&a>2){//删除的为第一行,并且总行数>2
                        $(obj).parent('#areaSelect .area_select').next().children("label").css("visibility","");
                        $(obj).parent('.area_select').remove();
                    }else {
                        if(b==0&&a==2){//如果删除的为第一行并且只有2行
                            $(obj).parent('#areaSelect .area_select').next().children("label").css("visibility","");
                            $(obj).parent('#areaSelect .area_select').next().children("a:eq(0)").css("display", "none");
                            $(obj).parent('#areaSelect .area_select').next().children("a:eq(1)").css("display", "");
                            $(obj).parent('.area_select').remove();
                        }else{
                            $(obj).parent('.area_select').remove();
                        }
                    }
            }
        }
    }

    $(document).on("change", '.disCity', function () {
        var oldvalue = $(this).data("last");//这次改变之前的值
        var value = $(this).val();//这次改变之前的值
        $(this).data("last", value); //每次改变都附加上去，以便下次变化时获取
        for (i = 0; i < $('.disCity option').length; i++) {
            if ($('.disCity option')[i].value == oldvalue) {
                $('.disCity option')[i].disabled = "";
            }
            if ($('.disCity option')[i].value == value) {
                $('.disCity option')[i].disabled = "disabled";
            }
        }
    });

    function getCity($select, url, selectStr) {
        var oSelected = [];
        var id = $select.val();
        if (id == "") {
            clearOption($select);
            return;
        }
        for (i = 0; i < $('.disCity option:selected').length; i++) {
            oSelected[i] = $('.disCity option:selected')[i].value;
        }
//        $(".disCity").val();
//        console.log(oSelected);
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {"id": id},
            url: url,
            success: function (data) {
                var $nextSelect = $select.next(selectStr);
                clearDistrictOption($nextSelect);
                $nextSelect.html('<option value="">请选择...</option>');
                for (var x in data) {
                    if ($nextSelect.length != 0) {
                        if (contains(oSelected, data[x].district_id)) {
                            $nextSelect.append('<option disabled="disabled" value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                        } else {
                            $nextSelect.append('<option value="' + data[x].district_id + '" >' + data[x].district_name + '</option>')
                        }
                    }
                }
            }

        })
    }
    function contains(arr, obj) {
        var i = arr.length;
        while (i--) {
            if (arr[i] === obj) {
                return true;
            }
        }
        return false;
    }
</script>