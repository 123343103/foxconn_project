<?php
/**
 * User: F1676624
 * Date: 2017/7/22
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

?>
<style>
    /*.wd100 {*/
        /*width: 100%;*/
    /*}*/
     .label-align{width: 70px;}
    .width-110{width: 85px;}
    .width-130{width: 130px;}
    .width-200{width: 130px;}
    .width-150{width: 150px;}
    .width-80{width: 80px;}
    .width-40{width: 40px;}
</style>
<h2 class="head-second ">
    仓库异动基本信息
</h2>
<?php $form = ActiveForm::begin(
    ['id' => 'add-form']
); ?>
<div class="mb-30">
    <div class="mb-10">
            <label class="width-110" for="wh_name">异动类型</label><label >:</label>
            <select name="InvChangeh[chh_type]" class="width-130" id="type" data-options="required:true">
                <?php foreach ($downList['changeType'] as $key => $val) { ?>
                    <option value="<?= $val['business_type_id'] ?>" <?= isset($invChangeInfoLH['chh_type']) && $invChangeInfoLH['chh_type'] == $val['business_type_id'] ? "selected" : null ?>><?= $val['business_value'] ?></option>
                <?php } ?>
            </select>
        <label class="width-150" for="wh_id">出仓名称</label><label >:</label>
        <select name="InvChangeh[wh_id]" class="width-130 output_wh" data-options="required:true">
            <option value="">---请选择---</option>
            <?php foreach ($whname as $key => $val) { ?>
                <option data-id="<?= $val['wh_code'] ?>" data-value="<?= $val['wh_attrw'] == "Y" ? "自有" : "外租" ?>"
                        value="<?= $val['wh_id'] ?>" <?= isset($invChangeInfoLH['wh_id']) && $invChangeInfoLH['wh_id'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
            <?php } ?>
        </select>
    </div>
<!--    <div class="chucang --><?//= isset($invChangeInfoLH['chh_type']) && $invChangeInfoLH['chh_type'] == '42' ? "hiden" : "" ?><!--">-->
<!--        <div class="inline-block mb-20">-->
<!--            <label class="width-110" for="wh_id">出仓名称</label>-->
<!--            <select name="InvChangeh[wh_id]" class="width-130 output_wh" data-options="required:true">-->
<!--                <option value="">---请选择---</option>-->
<!--                --><?php //foreach ($whname as $key => $val) { ?>
<!--                    <option data-id="--><?//= $val['wh_code'] ?><!--" data-value="--><?//= $val['wh_attrw'] == "Y" ? "自有" : "外租" ?><!--"-->
<!--                            value="--><?//= $val['wh_id'] ?><!--" --><?//= isset($invChangeInfoLH['wh_id']) && $invChangeInfoLH['wh_id'] == $val['wh_id'] ? "selected" : null ?><!--><?//= $val['wh_name'] ?><!--</option>-->
<!--                --><?php //} ?>
<!--            </select>-->
<!--        </div>-->
    <div class="mb-10">
        <div class="inline-block mb-20">
            <label class="width-110" for="wh_state">出仓仓库代码</label><label >:</label>
            <input type="text" id="output_code" class="width-130" readonly="readonly"
                   value="<?= isset($invChangeInfoLH['wh_code']) ? $invChangeInfoLH['wh_code'] : "" ?>">
        </div>
        <div class="inline-block mb-20">
            <label class="width-150" for="wh_state">出仓仓库属性</label><label >:</label>
            <input type="text" id="output_attr" class="width-130" readonly="readonly"
                   value="<?= isset($invChangeInfoLH['wh_attr']) ? $invChangeInfoLH['wh_attr'] : "" ?>">
        </div>
    </div>
    <div
        class="rucang mb-10 <?= isset($invChangeInfoLH['chh_type']) && $invChangeInfoLH['chh_type'] == '43' ? "" : "hiden" ?>">
        <div class="inline-block mb-20">
            <label class="width-110" for="wh_id2">入仓名称</label><label >:</label>
            <select name="InvChangeh[wh_id2]" class="width-130 input_wh" data-options="required:true">
                <option value="">---请选择---</option>
                <?php foreach ($whname as $key => $val) { ?>
                    <option data-id="<?= $val['wh_code'] ?>" data-value="<?= $val['wh_attrw'] == "Y" ? "自有" : "外租" ?>"
                            value="<?= $val['wh_id'] ?>" <?= isset($invChangeInfoLH['wh_id2']) && $invChangeInfoLH['wh_id2'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inline-block mb-20">
            <label class="width-150" for="wh_state">入仓仓库代码</label><label >:</label>
            <input type="text" id="input_code" class="width-130" readonly="readonly"
                   value="<?= isset($invChangeInfoLH['wh_code2']) ? $invChangeInfoLH['wh_code2'] : "" ?>">
        </div>
        <div style="clear: both;margin-bottom: 5px; "></div>
        <div class="inline-block mb-20">
            <label class="width-110" for="wh_state">入仓仓库属性</label><label >:</label>
            <input type="text" id="input_attr" class="width-130" readonly="readonly"
                   value="<?= isset($invChangeInfoLH['wh_attr2']) ? $invChangeInfoLH['wh_attr2'] : "" ?>">
        </div>
    </div>
</div>
<div class="space-50"></div>
<h2 class="head-second ">
    异动商品信息 <span class="text-right float-right">
                <a id="select_product">添加</a>
                <a onclick="">删除</a></span>
</h2>
<div class="mb-20 tablescroll" style="overflow-x: scroll">
    <?php if (\Yii::$app->controller->action->id == "update") { ?>
        <?php if ($invChangeInfoLH['chh_type'] == 41) { ?>
            <table class="table" style="width: 1500px;">
                <thead>
                <tr>
                    <th class="width-40">序号</th>
                    <th class="width-40"><input type="checkbox" id="checkAll"></th>
                    <th class="width-130">料号</th>
                    <th class="width-130">商品名称</th>
                    <th class="width-130">规格型号</th>
                    <th class="width-130">批次</th>
                    <th class="width-130">异动前储位</th>
                    <th class="width-130">异动后储位</th>
                    <th class="width-80">异动数量</th>
                    <th class="width-80">单位</th>
                    <th class="width-80 text-center">操作</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach ($invChangeInfoL as $key => $val) { ?>
                    <tr>
                        <td><span class="xunhao"><?= ($key + 1) ?></span></td>
                        <td><span class="wd100"><input type="checkbox"></span></td>
                        <td><input class="height-30 wd100  text-center  pdt_no"
                                   name="changeL[<?= $key ?>][pdt_no]" type="text" data-options="required:true"
                                   maxlength="20" placeholder="请输入" value="<?= $val["pdt_no"] ?>"><input
                                class="hiden pdt_id" value="<?= $val["pdt_id"] ?>"/>
                        </td>
                        <td class=""><p class="wd100"><?= $val["pdt_name"] ?></p></td>
                        <td class=""><p class="wd100"><?= $val["tp_spec"] ?></p></td>
                        <td class=""><p class="wd100"><?= $val["chl_bach"] ?></p>
                            <input type="hidden" name="changeL[<?= $key ?>][chl_bach]" value="<?= $val["chl_bach"] ?>"/></td>
                        <td class=""><p class="wd100"><?= $val["st_id"] ?></p><input
                                class="hiden st_id" name="changeL[<?= $key ?>][st_id]" value="<?= $val["st_id"] ?>"/>
                        </td>
                        <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text"
                                   data-options="required:true" maxlength="20" placeholder="请点击选择"
                                   value="<?= $val["st_id2"] ?>">
                            <input class="hiden st_id2" name="changeL[<?= $key ?>][st_id2]"
                                   value="<?= $val["st_id2"] ?>">
                        </td>
                        <td><input class="height-30 wd100  text-center unit_name" type="text"
                                   data-options="required:true" maxlength="20" value="<?= $val["chl_num"] ?>"
                                   name="changeL[<?= $key ?>][chl_num]" placeholder="请输入"></td>
                        <td class=""><p class="wd100"><?= $val["unit"] ?></p></td>
                        <td><a class="wd100" onclick="reset(this)">重置</a> <a
                                onclick="vacc_del(this,'product_table')">删除</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else if ($invChangeInfoLH['chh_type'] == 42) { ?>
            <table class="table" style="width: 1500px;">
                <thead>
                <tr>
                    <th class="width-40">序号</th>
                    <th class="width-40"><input type="checkbox" id="checkAll"></th>
                    <th class="width-130">料号</th>
                    <th class="width-130">商品名称</th>
                    <th class="width-130">规格型号</th>
                    <th class="width-130">仓库</th>
                    <th class="width-130">储位</th>
                    <th class="width-130">库存量</th>
                    <th class="width-80">异动数量</th>
                    <th class="width-80">异动后库存</th>
                    <th class="width-80">单位</th>
                    <th class="width-80 text-center">操作</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach ($invChangeInfoL as $key => $val) { ?>
                    <tr>
                        <td rowspan="2">异前<span class="xunhao"><?= ($key + 1) ?></span>
                            <div class="space-10"></div>
                            异后<span class="xunhao"><?= ($key + 1) ?></span></td>
                        <td rowspan="2"><span class="wd100"><input type="checkbox" class="checkbox"></span></td>
                        <td><input class="height-30 wd100  text-center  pdt_no" type="text" data-options="required:true"
                                   maxlength="20" placeholder="请输入" value="<?= $val["pdt_no"] ?>">
                            <input class="hiden pdt_id" name="changeL[<?= $key ?>][pdt_id]"
                                   value="<?= $val["pdt_id"] ?>">
                            <input class="hiden wh_id" name="changeL[<?= $key ?>][wh_id]" value="<?= $val["wh_id"] ?>">
                            <input class="hiden st_id" name="changeL[<?= $key ?>][st_id]" value="<?= $val["st_id"] ?>">
                        </td>
                        <td class="pdt_name"><p class="wd100"><?= $val["pdt_name"] ?></p></td>
                        <td class="tp_spec"><p class="wd100"><?= $val["tp_spec"] ?></p></td>
                        <td class="whhouse"><p class="wd100"><?= $val["wh_id"] ?></p></td>
                        <td class="store1"><p class="wd100"><?= $val["st_id"] ?></p></td>
                        <td class="L_invt_num"><p class="wd100"><?= $val["before_num1"] ?></p></td>
                        <td><input class="height-30 wd100  text-center chl_num" type="text"
                                   data-options="required:true" maxlength="20" value="<?= $val["chl_num"] ?>"
                                   name="changeL[<?= $key ?>][chl_num]"
                                   placeholder="请输入"></td>
                        <td class="num"><p class="wd100"><?= $val["before_num1"] - $val["chl_num"] ?></p></td>
                        <td class=""><p class="wd100"><?= $val["unit"] ?></p></td>
                        <td rowspan="2"><a class="wd100" onclick="reset(this)">重置</a> <a
                                onclick="vacc_del(this,'product_table')">删除</a>
                        </td>
                    </tr>
                    <tr>
                        <td><input class="height-30 wd100  text-center pdt_no" type="text" data-options="required:true"
                                   maxlength="20" placeholder="请输入" value="<?= $val["pdt_no2"] ?>">
                            <input class="hiden pdt_id" name="changeL[<?= $key ?>][pdt_id2]"
                                   value="<?= $val["pdt_id2"] ?>">
                            <input class="hiden wh_id" name="changeL[<?= $key ?>][wh_id2]"
                                   value="<?= $val["wh_id2"] ?>">
                            <input class="hiden st_id" name="changeL[<?= $key ?>][st_id2]"
                                   value="<?= $val["st_id2"] ?>">
                        </td>
                        <td class="pdt_name"><p class="wd100"><?= $val["pdt_name2"] ?></p></td>
                        <td class="tp_spec"><p class="wd100"><?= $val["tp_spec2"] ?></p></td>
                        <td class="whhouse"><p class="wd100"><?= $val["wh_id2"] ?></p></td>
                        <td class="store1"><p class="wd100"><?= $val["st_id2"] ?></p></td>
                        <td class="L_invt_num"><p class="wd100"><?= $val["before_num2"] ?></p></td>
                        <td class="chl_num"><p class="wd100"><?= $val["chl_num"] ?></p></td>
                        <td class="num"><p class="wd100"><?= $val["before_num2"] + $val["chl_num"] ?></p></td>
                        <td class="unit"><p class="wd100"><?= $val["unit2"] ?></p></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else if ($invChangeInfoLH['chh_type'] == 43) { ?>
            <table class="table" style="width: 1500px;">
                <thead>
                <tr>
                    <th class="width-40">序号</th>
                    <th class="width-40"><input type="checkbox" id="checkAll"></th>
                    <th class="width-130">料号</th>
                    <th class="width-130">商品名称</th>
                    <th class="width-130">品牌</th>
                    <th class="width-130">规格型号</th>
                    <th class="width-130">批次</th>
                    <th class="width-130">移仓前储位</th>
<!--                    <th class="width-120">异动后储位</th>-->
                    <th class="width-80">移仓数量</th>
                    <th class="width-80">单位</th>
                    <th class="width-80 text-center">操作</th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach ($invChangeInfoL as $key => $val) { ?>
                    <tr>
                        <td><span class="xunhao"><?= ($key + 1) ?></span></td>
                        <td><span class="wd100"><input type="checkbox"></span></td>
                        <td><input class="height-30 wd100  text-center  pdt_no"
                                   name="changeL[<?= $key ?>][pdt_no]" type="text" data-options="required:true"
                                   maxlength="20" placeholder="请输入" value="<?= $val["pdt_no"] ?>">
                            <input class="hiden pdt_id" value=""></td>
                        <td class=""><p class="wd100"><?= $val["pdt_name"] ?></p></td>
                        <td class=""><p class="wd100"><?= $val["brand"] ?></p></td>
                        <td class=""><p class="wd100"><?= $val["tp_spec"] ?></p></td>
                        <td class=""><p class="wd100"><?= $val["chl_bach"] ?></p>
                            <input type="hidden" name="changeL[<?= $key ?>][chl_bach]" value="<?= $val["chl_bach"] ?>"/></td>
                        <td class=""><p class="wd100"><?= $val["st_id"] ?></p><input
                                class="hiden st_id" name="changeL[<?= $key ?>][st_id]" value="<?= $val["st_id"] ?>"/>
                        </td>
<!--                        <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text"-->
<!--                                   data-options="required:true" maxlength="20" placeholder="请点击选择"-->
<!--                                   value="--><?//= $val["st_code2"] ?><!--">-->
<!--                            <input class="hiden st_id2" name="changeL[--><?//= $key ?><!--][st_id2]"-->
<!--                                   value="--><?//= $val["st_id2"] ?><!--"></td>-->
                        <td><input class="height-30 wd100  text-center unit_name" type="text"
                                   data-options="required:true" maxlength="20" value="<?= $val["chl_num"] ?>"
                                   name="changeL[<?= $key ?>][chl_num]"
                                   placeholder="请输入"></td>
                        <td class=""><p class="wd100"><?= $val["unit"] ?></p></td>
                        <td><a class="wd100" onclick="reset(this)">重置</a> <a
                                onclick="vacc_del(this,'product_table')">删除</a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    <?php } else { ?>
        <table class="table" style="width: 1500px;">
            <thead>
            <tr>
                <th class="width-40">序号</th>
                <th class="width-40"><input type="checkbox" id="checkAll"></th>
                <th class="width-130">料号</th>
                <th class="width-130">商品名称</th>
                <th class="width-130">规格型号</th>
                <th class="width-130">批次</th>
                <th class="width-130">异动前储位</th>
                <th class="width-130">异动后储位</th>
                <th class="width-80">异动数量</th>
                <th class="width-80">单位</th>
                <th class="width-80 text-center">操作</th>
            </tr>
            </thead>
            <tbody id="product_table">
            <tr>
                <td><span class="xunhao">1</span></td>
                <td><span class="wd100"><input type="checkbox"></span></td>
                <td><input class="height-30 wd100  text-center  pdt_no" type="text" data-options="required:true"
                           name="changeL[0][pdt_no]" value=""
                           maxlength="20" placeholder="请输入">
                    <input class="hiden pdt_id"></td>
                <td class=""><p class="wd100"></p></td>
                <td class=""><p class="wd100"></p></td>
                <td class=""><p class="wd100"></p><input type="hidden" name="changeL[0][chl_bach]"></td>
                <td class=""><p class="wd100"></p><input type="hidden" name="changeL[0][st_id]"></td>
                <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text"
                           data-options="required:true"
                           maxlength="20" placeholder="请点击选择">
                    <input class="hiden st_id" name="changeL[0][st_id2]"></td>
                <td><input class="height-30 wd100  text-center" type="text" data-options="required:true"
                           maxlength="20" name="changeL[0][chl_num]" placeholder="请输入"></td>
                <td class=""><p class="wd100"></p></td>
                <td><a class="wd100" onclick="reset(this)">重置</a> <a
                        onclick="vacc_del(this,'product_table')">删除</a>
                </td>
            </tr>
            </tbody>
        </table>
    <?php } ?>
</div>
<p class="text-right mb-40">
    <a class="icon-plus" id="add_btn">添加商品</a>
</p>
<div class="form-group text-center">
    <button id="save-form" type="submit" class="button-blue-big">保&nbsp;存</button>
    <button id="apply-form" type="submit" class="button-white-big ml-40">提&nbsp;交</button>
    <button onclick="history.go(-1);" type="button" class="button-white-big ml-40">返&nbsp;回</button>
</div>

<?php ActiveForm::end(); ?>

<script>
    var tablescroll = $(".tablescroll");
    $(function () {

        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });
    $("#save-form").click(function () {
        ajaxSubmitForm($("#add-form"), function () {
            var isok = true;
            if ($("#product_table").children().length < 1) {
                isok = false;
                layer.alert("请选择异动商品", {icon: 2});
            }
            //去除隐藏的必填项
            $('input[type="hidden"],select,textarea', $("#add-form")).each(function () {
                $(this).validatebox({
                    required: false
                });
            });
            return isok;
        });
    });
    $("#apply-form").click(function () {
        <?php if(\Yii::$app->controller->action->id == "create"){ ?>
        $('#add-form').attr('action', '<?= Url::to(["create", "is_apply" => "1"]) ?>');
        <?php }else{ ?>
        $('#add-form').attr('action', '<?= Url::to(["update", "is_apply" => "1", "id" => $invChangeInfoLH['chh_id']]) ?>');
        <?php } ?>
        ajaxSubmitForm($("#add-form"), function () {
            var isok = true;
            if ($("#product_table").children().length < 1) {
                isok = false;
                layer.alert("请选择异动商品", {icon: 2});
            }
            //去除隐藏的必填项
            $('input[type="hidden"],select,textarea', $("#add-form")).each(function () {
                $(this).validatebox({
                    required: false
                });
            });
            return isok;
        });
    });
    //选择商品
    $("#select_product").click(function () {
        var out = $(".output_wh").val();//出仓仓库
        var type = $("#type").val();    //异动类型
        var url = "<?= Url::to(['/warehouse/inv-changeh/wh-code'])?>?id="+out;
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
                            href: "<?=Url::to(['select-product'])?>" + "?whId=" + codes + "&row=" + "&type=" + type,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 520,
                            afterClose: function () {
                                sort();//序号重排
                                $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
                                    $(this).validatebox();//验证初始化
                                });
                            }
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
//                href: "<?//=Url::to(['select-product'])?>//" + "?whId=" + out + "&row=" + "&type=" + type,
//                type: "iframe",
//                padding: 0,
//                autoSize: false,
//                width: 800,
//                height: 520,
//                afterClose: function () {
//                    sort();//序号重排
//                    $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
//                        $(this).validatebox();//验证初始化
//                    });
//                }
//            });
        } else {
            layer.alert("请先选择出仓仓库!", {icon: 2});
        }
    });
    $(".output_wh").on("change", function () {
        $("#output_code").val($(".output_wh option:selected").data("id"));
        $("#output_attr").val($(".output_wh option:selected").data("value"));
        var typeVal = $("#type").val();
        if (typeVal == 41) {
            for (i = 0; i < $('.input_wh option').length; i++) {
                if ($('.input_wh option')[i].value == $(this).val()) {
                    $('.input_wh option')[i].selected = 'selected';
                    $('.input_wh').change();
                }
            }
        }
    });
    $(".input_wh").on("change", function () {
        $("#input_code").val($(".input_wh option:selected").data("id"));
        $("#input_attr").val($(".input_wh option:selected").data("value"));
    });
    $("#type").on("change", function () {
        var typeVal = $(this).val();  //41储位异动，42 料号异动 ， 43 移仓管理
        if (typeVal == 41) { //41储位异动
            var output = $(".output_wh").val();
            $(".chucang").show();
            $(".rucang").hide();
            console.log(output);
            if (output != "") {
                for (i = 0; i < $('.input_wh option').length; i++) {
                    if ($('.input_wh option')[i].value == output) {
                        $('.input_wh option')[i].selected = 'selected';
                        $('.input_wh').change();
                    }
                }
            }
            locatiom();
        } else if (typeVal == 42) { //42 料号异动
            pdt();
            $(".chucang").show();
            $(".rucang").hide();
        } else if (typeVal == 43) { //43 移仓管理
            $(".rucang").show();
            $(".chucang").show();
            warehouse();
        }
    });
    $(document).on("blur", ".pdt_no", function () {
        var out = $(".output_wh").val();//出仓仓库
        if(out!=""){
            var $pdt_no = $(this);
            var pdt_no = $(this).val();
            if (pdt_no == "") {
                reset($pdt_no);
            }
            var url = "<?= Url::to(['/warehouse/inv-changeh/wh-code'])?>?id="+out;
            var type = $("#type").val();
            var row = $(this).parent().parent().first().find("span.xunhao").html();//行数
//        console.log(row);
            if ($("#type").val() == 42) {
                if (!row) {
                    row = $(this).parent().parent().prev().find("span.xunhao").html();//行数
                    row = row * 2;
                } else {
                    row = row * 2 - 1;
                }
            }
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"whid": out},
                url: url,
                success: function (data) {
                    codes=data.wh_code;
                    if (codes != "") {
                        $.fancybox({
                            href: "<?=Url::to(['select-product'])?>" + "?kwd=" + pdt_no + "&row=" + row + "&whId=" + codes + "&type=" + type,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 520,
                            afterClose: function () {
                                sort();//序号重排
                                $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
                                    $(this).validatebox();//验证初始化
                                });
                            }
                        });
                    }else {
                        layer.alert("未找到该仓库代码!", {icon: 2});
                    }
                },
                error: function (data) {
                    layer.alert("未找到该仓库代码!", {icon: 2});
                }
            });
        }else {
            layer.alert("请先选择出仓仓库!", {icon: 2});
        }
    });
    //选择储位
    $(document).on("click", ".store", function () {
        var out = $(".output_wh").val();//
        var type = $("#type").val();
        var row = $(this).parent().parent().first().find("span").html();//行数
        if (out != "") {
            $.fancybox({
                href: "<?=Url::to(['select-store'])?>" + "?whId=" + out + "&type=" + type + "&row=" + row,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 520,
                afterClose: function () {

                }
            });
        } else {
            layer.alert("请先选择仓库!", {icon: 2});
        }
    });
//    $(document).on("focus", ".pdt_no", function () {
//        var chucang = $(".output_wh").val();
//        var type = $("#type").val();    //异动类型
//        if (chucang == "" && type != 42) {
//            layer.alert("请先选择出仓仓库!", {icon: 2});
//            $(this).trigger("blur");
//        }
//    });
//    tablescroll.on("scroll", function () {
//        if ($(this).scrollLeft() > 120) {
//            $("input").blur();
//        }
//        if ($(this).scrollLeft() > 110 && $(this).scrollLeft() < 1400) {
//            //关闭日期选择
//            $(".jedatedeep").hide();
//        }
//    });
    $(document).on("change", ".chl_num", function () {
        var movetal;
        var orgin=$(this).parent().parent().next().find(".chl_num").prev().find("p").html();
        var move = $(this).val();
        var kun1 = $(this).parent().prev().find("p").html();
        var kun2;
        if (kun1) {
            kun2 = parseFloat(kun1) - move;
            $(this).parent().next().find("p").html(parseFloat(kun2));
            $(this).parent().next().find(".changenum").val(parseFloat(kun2));
        }
        $(this).parent().parent().next().find(".chl_num").find("p").html(move);
        if(orgin){
             movetal = parseFloat(orgin) + parseFloat(move);
            $(this).parent().parent().next().find(".num").find("p").html(parseFloat(movetal));
            $(this).parent().parent().next().find(".changenum").val(parseFloat(movetal));
        }
    });
    var xun = $("#product_table").children().length + 1;
    $("#add_btn").click(function () {
        var xunhao;
        var typeVal = $("#type").val();  //41储位异动，42 料号异动 ， 43 移仓管理
        if (typeVal == 42) {
            xunhao = $("#product_table").children().length / 2 + 1;

        } else {
            xunhao = $("#product_table").children().length + 1;
        }
        var $productTbody = $("#product_table");
        var tr = "";
        if (typeVal == 41) {
            tr = '<tr>' +
                '<td><span class="xunhao">' + xunhao + '</span></td>' +
                '<td>' + '<span class="wd100"><input type="checkbox"></span>' + '</td>' +
                '<td><input class="height-30 wd100  text-center  pdt_no" ' +
                'name="changeL[' + xun + '][pdt_no]" type="text" ' +
                'data-options="required:true"  maxlength="20" placeholder="请输入" value="">' +
                '<input class="hiden pdt_id" value=""/></td>' +
                '<td class=""><p class="wd100"></p></td>' +
                '<td class=""><p class="wd100"></p></td>' +
                '<td class=""><p class="wd100"><input class="hiden " name="changeL[' + xun + '][chl_bach]" value=""></p></td>' +
                '<td class=""><p class="wd100"></p><input class="hiden st_id" name="changeL[' + xun + '][st_id]" value=""/></td>' +
                '   <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text" data-options="required:true" maxlength="20" placeholder="请点击选择" value="">' +
                '<input class="hiden st_id2" name="changeL[' + xun + '][st_id2]" value=""></td>' +
                '<td><input class="height-30 wd100  text-center" type="text" data-options="required:true"  maxlength="20"                                    name="changeL[' + xun + '][chl_num]" placeholder="请输入 "></td>' +
                '<td class=""><p class="wd100"></p></td>' +
                '<td><a class="wd100" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
                '</tr>';
        } else if (typeVal == 42) {
            var len=$("#product_table").find("tr").length;
            tr = '    <tr>' +
                '  <td rowspan="2">异前' + '<span class="xunhao">' + xunhao + '</span>' + '<div class="space-10"></div>异后' + '<span class="xunhao">' + xunhao + '</span>' + '</td>' +
                '  <td rowspan="2"><span class="wd100"><input type="checkbox" class="checkbox"></span></td>' +
                '   <td><input class="height-30 wd100  text-center  pdt_no" ' +
                'name="changeL[' + xun + '][pdt_no]" type="text" ' +
                'data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
                '<input class="hiden pdt_id"  value="">' +
                '<input class="hiden wh_id" name="changeL[' + xun + '][wh_id]" value="">' +
                '<input class="hiden _calpc" name="changeL[' + xun + '][chl_bach]" value="">' +
                '<input class="hiden st_id" name="changeL[' + xun + '][st_id]" value=""></td>' +
                ' <td class="pdt_name"><p class="wd100"></p></td>' +
                '   <td class="tp_spec"><p class="wd100"></p></td>' +
                '   <td class="whhouse"><p class="wd100"></p></td>' +
                '   <td class="cal_pc"><input class="hiden _calpc" ' +
                'name="changeL[' + xun + '][chl_bach]"></td>' +
                '   <td class="store1"><p class="wd100"></p></td>' +
                '   <td class="L_invt_num"><p class="wd100"></p><input type="hidden" class="_beforenum" ' +
                'name="changeL[' + xun + '][before_num1]" value=""></td>' +
                '   <td><input class="height-30 wd100  text-center chl_num" type="text" data-options="required:true" maxlength="20" value="" name="changeL[' + xun + '][chl_num]" placeholder="请输入"></td>' +
                ' <td class=""><p class="wd100"></p><input type="hidden"  class="changenum" ' +
                'name="LBsInvtList[' + len + '][L_invt_num]"><input type="hidden" class="_invtiid" ' +
                'name="LBsInvtList[' + len + '][invt_iid]"></td>' +
                ' <td class="unit"><p class="wd100"></p></td>' +
                '  <td rowspan="2"><a class="wd100" onclick="reset(this)">重置</a> <a onclick="vacc_del(this,\'product_table\')">删除</a>' +
                '   </td>' +
                '   </tr>' +
                '    <tr>' +
                '   <td><input class="height-30 wd100  text-center pdt_no" name="changeL[' + xun + '][part_no2]" type="text" ' +
                'data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
                '<input class="hiden pdt_id"  value=""><input class="hiden wh_id" ' +
                'name="changeL[' + xun + '][wh_id2]" value="">' +
                '<input class="hiden st_id" ' +
                'name="changeL[' + xun + '][st_id2]" value=""><input class="hiden _calpc" ' +
                'name="changeL[' + xun + '][chl_bach2]" value=""></td>' +
                ' <td class="pdt_name"><p class="wd100"></p></td>' +
                '   <td class="tp_spec"><p class="wd100"></p></td>' +
                '   <td class="whhouse"><p class="wd100"></p></td>' +
                '   <td class="cal_pc"><input class="hiden _calpc" ' +
                'name="changeL[' + xun + '][chl_bach2]"></td>' +
                '   <td class="store1"><p class="wd100"></p></td>' +
                '   <td class="L_invt_num"><p class="wd100"></p><input type="hidden" class="_beforenum" ' +
                'name="changeL[' + xun + '][before_num2]"></td>' +
                '   <td class="chl_num"><p class="wd100"></p></td>' +
                '   <td class="num"><p class="wd100"></p><input type="hidden" class="changenum" ' +
                'name="LBsInvtList[' + (len+1) + '][L_invt_num]"><input type="hidden" class="_invtiid" ' +
                'name="LBsInvtList[' + (len+1) + '][invt_iid]"></td>' +
                ' <td class="unit"><p class="wd100"></p></td>' +
                '   </tr>';
        } else if (typeVal == 43) {
            tr = '    <tr>' +
                '  <td><span class="xunhao">' + xunhao + '</span></td>' +
                '<td>' + '<span class="wd100"><input type="checkbox"></span>' + '</td>' +
                '   <td><input class="height-30 wd100  text-center  pdt_no" name="changeL[' + xun + '][pdt_no]" type="text" ' +
                'data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
                '<input class="hiden pdt_id" name="" value=""></td>' +
                ' <td class=""><p class="wd100"></p></td>' +
                '   <td class=""><p class="wd100"></p></td>' +
                '   <td class=""><p class="wd100"></p></td>' +
                '   <td class=""><p class="wd100"></p><input class="hiden pdt_pici" ' +
                'name="changeL[' + xun + '][chl_bach]"></td>' +
                '   <td class=""><p class="wd100"></p></p><input class="hiden st_id" name="changeL[' + xun + '][st_id]" value=""/></td>' +
//                '   <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text" data-options="required:true" maxlength="20" placeholder="请点击选择">' +
//                '<input class="hiden st_id2" name="changeL[' + xun + '][st_id2]" value=""></td>' +
                '   <td><input class="height-30 wd100  text-center unit_name" type="text" data-options="required:true" maxlength="20" value="" name="changeL[' + xun + '][chl_num]" placeholder="请输入"></td>' +
                ' <td class=""><p class="wd100"></p></td>' +
                '  <td><a class="wd100" onclick="reset(this)">重置</a> <a onclick="vacc_del(this,\'product_table\')">删除</a>' +
                '   </td>' +
                '   </tr>';
        }
        xun++;
        $productTbody.append(tr);
        $.parser.parse($productTbody.find("tr:last"));
        //全选
        $(".table").on('click', "th input[type='checkbox']", function () {
            if ($(this).is(":checked")) {
                $('.table').find("td input[type='checkbox']").prop("checked", true);
            } else {
                $('.table').find("td input[type='checkbox']").prop("checked", false);
            }
        });
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    });
    function reset(obj) {
        if ($("#type").val() == 42) {
            $(obj).parents("tr").find("input").val("");
            $(obj).parents("tr").find("p").html("");
            $(obj).parents("tr").find("input.hiden").val("");
            //下行
            $(obj).parents("tr").next("tr").find("input").val("");
            $(obj).parents("tr").next("tr").find("input.hiden").val("");
            $(obj).parents("tr").next("tr").find("p").val("");
        } else {
            var td = $(obj).parents("tr").find("td");
            $(obj).parents("tr").find("p").html("");
            $(obj).parents("tr").find("input").val("");
            $(obj).parents("tr").find("input.hiden").val("");
        }
    }
    function vacc_del(obj, id) {
        var a = $("#" + id + " tr").length;
        var xunhao = 0;
        if(a>1) {
            if ($("#type").val() == 42) {
                $(obj).parents("tr").next("tr").remove();
                $(obj).parents("tr").remove();
                for (var j = 0; j < a; j = j + 2) {
                    xunhao++;
                    $('#' + id).find('tr').eq(j).find('td:first').find("span.xunhao").html(xunhao);
                }
            } else {
                $(obj).parents("tr").remove();
                for (var i = 0; i < a; i++) {
                    $('#' + id).find('tr').eq(i).find('td:first').find("span.xunhao").text(i + 1);
                }
            }
        }
    }
    //全选
    $(".table").on('click', "th input[type='checkbox']", function () {
//        console.log(000);
//        $("input[name='checkbox']").attr("checked","true");
        if ($(this).is(":checked")) {
            $('.table').find("td input[type='checkbox']").prop("checked", true);
        } else {
            $('.table').find("td input[type='checkbox']").prop("checked", false);
        }
    });
    //储位异动初始化
    function locatiom() {
        tablescroll.children().remove();
        var table = '<table class="table" style="width: 1500px;"><thead><tr>' +
            '<th class="width-40">序号</th>' +
            ' <th class="width-40"><input type="checkbox" id="checkAll"></th>' +
            ' <th class="width-130">料号</th>' +
            '  <th class="width-130">商品名称</th>' +
            '   <th class="width-130">规格型号</th>' +
            '   <th class="width-130">批次</th>' +
            '    <th class="width-130">异动前储位</th>' +
            '    <th class="width-130">异动后储位</th>' +
            '    <th class="width-80">异动数量</th>' +
            '<th class="width-80">单位</th>' +
            '   <th class="width-80 text-center">操作</th>' +
            '  </tr>' +
            '   </thead>' +
            '   <tbody id="product_table">' +
            '    <tr>' +
            '  <td><span class="xunhao">1</span></td>' +
            '  <td><span class="wd100"><input type="checkbox"></span></td>' +
            '   <td><input class="height-30 wd100  text-center  pdt_no" type="text" ' +
            'name="changeL[0][pdt_no]" data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
            '<input class="hiden pdt_id"  value=""></td>' +
            ' <td class=""><p class="wd100"></p></td>' +
            '   <td class=""><p class="wd100"></p></td>' +
            '   <td class=""><p class="wd100"></p><input type="hidden" name="changeL[0][chl_bach]"></td>' +
            '   <td class=""><p class="wd100"></p><input class="hiden st_id" name="changeL[0][st_id]" value=""></td>' +
            '   <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text" ' +
            'data-options="required:true" maxlength="20" placeholder="请点击选择" value="">' +
            '<input class="hiden st_id2" name="changeL[' + xun + '][st_id2]" value=""></td>' +
            '   <td><input class="height-30 wd100  text-center unit_name" type="text" ' +
            'data-options="required:true" maxlength="20" value="" name="changeL[0][unit_name]" placeholder="请输入"></td>' +
            ' <td class=""><p class="wd100"></p></td>' +
            '  <td><a class="wd100" onclick="reset(this)">重置</a> <a onclick="vacc_del(this,\'product_table\')">删除</a>' +
            '   </td>' +
            '   </tr>' +
            '   </tbody>' +
            '    </table>';
        tablescroll.append(table);
        //全选
        $(".table").on('click', "th input[type='checkbox']", function () {
//        console.log(000);
//        $("input[name='checkbox']").attr("checked","true");
            if ($(this).is(":checked")) {
                $('.table').find("td input[type='checkbox']").prop("checked", true);
            } else {
                $('.table').find("td input[type='checkbox']").prop("checked", false);
            }
        });
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    }
    //料号异动初始化
    function pdt() {
        tablescroll.children().remove();
        var table = '<table class="table _tabless" style="width: 1500px;"><thead><tr>' +
            '<th class="width-40">序号</th>' +
            ' <th class="width-40"><input type="checkbox" id="checkAll"></th>' +
            ' <th class="width-130">料号</th>' +
            '  <th class="width-130">商品名称</th>' +
            '   <th class="width-130">规格型号</th>' +
            '   <th class="width-130">仓库</th>' +
            '   <th class="width-130">批次</th>' +
            '    <th class="width-130">储位</th>' +
            '    <th class="width-130">库存量</th>' +
            '    <th class="width-80">异动数量</th>' +
            '    <th class="width-80">异动后库存</th>' +
            '<th class="width-80">单位</th>' +
            '   <th class="width-80 text-center">操作</th>' +
            '  </tr>' +
            '   </thead>' +
            '   <tbody id="product_table">' +
            '    <tr>' +
            '  <td rowspan="2">异前' + '<span class="xunhao">1</span>' + '<div class="space-10"></div>异后' + '<span class="xunhao">1</span>' + '</td>' +
            '  <td rowspan="2"><span class="wd100"><input type="checkbox" class="checkbox"></span></td>' +
            '   <td><input class="height-30 wd100  text-center  pdt_no" name="changeL[0][pdt_no]" ' +
            'type="text" data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
            '<input class="hiden pdt_id"  value=""><input class="hiden wh_id" ' +
            'name="changeL[0][wh_id]" value=""><input class="hiden st_id" name="changeL[0][st_id]" value="">' +
            '<input class="hiden _calpc" name="changeL[0][chl_bach]" value=""></td>' +
            ' <td class="pdt_name"><p class="wd100"></p></td>' +
            '   <td class="tp_spec"><p class="wd100"></p></td>' +
            '   <td class="whhouse"><p class="wd100"></p></td>' +
            '   <td class="cal_pc"><input class="hiden _calpc" value="" name="changeL[0][chl_bach]"></td>' +
            '   <td class="store1"><p class="wd100"></p></td>' +
            '   <td class="L_invt_num"><p class="wd100"></p><input type="hidden" class="_beforenum" ' +
            'name="changeL[0][before_num1]"></td>' +
            '   <td><input class="height-30 wd100  text-center chl_num" type="text" ' +
            'data-options="required:true" maxlength="20" value="" name="changeL[0][chl_num]" placeholder="请输入"></td>' +
            '   <td class="L_invt_num"><p class="wd100"></p><input type="hidden" class="changenum" ' +
            'name="LBsInvtList[0][L_invt_num]"><input type="hidden" class="_invtiid" name=""></td>' +
            ' <td class="unit"><p class="wd100"></p></td>' +
            '  <td rowspan="2"><a class="wd100" onclick="reset(this)">重置</a> <a onclick="vacc_del(this,\'product_table\')">删除</a>' +
            '   </td>' +
            '   </tr>' +
            '    <tr>' +
            '   <td><input class="height-30 wd100  text-center  pdt_no" name="changeL[0][part_no2]" ' +
            'type="text" data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
            '<input class="hiden pdt_id"  value=""><input class="hiden wh_id" name="changeL[0][wh_id2]"> ' +
            '<input class="hiden st_id" name="changeL[0][st_id2]">' +
            '<input class="hiden _calpc" name="changeL[0][chl_bach2]" value=""></td>' +
            ' <td class="pdt_name"><p class="wd100"></p></td>' +
            '   <td class="tp_spec"><p class="wd100"></p></td>' +
            '   <td class="whhouse"><p class="wd100"></p></td>' +
            '   <td class="cal_pc"><input class="hiden _calpc" name="changeL[0][chl_bach2]" /></td>' +
            '   <td class="store1"><p class="wd100"></p></td>' +
            '   <td class="L_invt_num"><p class="wd100"></p><input type="hidden" class="_beforenum" ' +
            'name="changeL[0][before_num2]"></td>' +
            '   <td class="chl_num"><p class="wd100"></p></td>' +
            '   <td class="num"><p class="wd100"></p><input type="hidden" class="changenum" ' +
            'name="LBsInvtList[1][L_invt_num]"><input type="hidden" class="_invtiid" name=""></td>' +
            ' <td class="unit"><p class="wd100"></p></td>' +
            '   </tr>' +
            '   </tbody>' +
            '    </table>';
        tablescroll.append(table);
        //全选
        $(".table").on('click', "th input[type='checkbox']", function () {
            if ($(this).is(":checked")) {
                $('.table').find("td input[type='checkbox']").prop("checked", true);
            } else {
                $('.table').find("td input[type='checkbox']").prop("checked", false);
            }
        });
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    }
    //移仓异动初始化
    function warehouse() {
        tablescroll.children().remove();
        var table = '<table class="table" style="width: 1500px;"><thead><tr>' +
            '<th class="width-40">序号</th>' +
            ' <th class="width-40"><input type="checkbox" id="checkAll"></th>' +
            ' <th class="width-130">料号</th>' +
            '  <th class="width-130">商品名称</th>' +
            '  <th class="width-130">品牌</th>' +
            '   <th class="width-130">规格型号</th>' +
            '   <th class="width-130">批次</th>' +
            '    <th class="width-130">移仓前储位</th>' +
//            '    <th class="width-120">移仓后储位</th>' +
            '    <th class="width-80">异动数量</th>' +
            '<th class="width-80">单位</th>' +
            '   <th class="width-80 text-center">操作</th>' +
            '  </tr>' +
            '   </thead>' +
            '   <tbody id="product_table">' +
            '    <tr>' +
            '  <td><span class="xunhao">1</span></td>' +
            '  <td><span class="wd100"><input type="checkbox"></span></td>' +
            '   <td><input class="height-30 wd100  text-center  pdt_no" type="text" data-options="required:true" maxlength="20" placeholder="请输入" value="">' +
            '<input class="hiden pdt_id" name="" value=""></td>' +
            ' <td class=""><p class="wd100"></p></td>' +
            '   <td class=""><p class="wd100"></p></td>' +
            '   <td class=""><p class="wd100"></p></td>' +
            '   <td class=""><p class="wd100"></p></td>' +
            '   <td class=""><p class="wd100"></p><input class="hiden st_id" name="changeL[0][st_id]" value=""></td>' +
//            '   <td><input class="height-30 wd100  text-center  store" readonly="readonly" type="text" data-options="required:true" maxlength="20" placeholder="请点击选择" value=""><input class="hiden st_id2" name="changeL[0][st_id2]" value=""></td>' +
            '   <td><input class="height-30 wd100  text-center unit_name" type="text" data-options="required:true" maxlength="20" value="" name="changeL[0][unit_name]" placeholder="请输入"></td>' +
            ' <td class=""><p class="wd100"></p></td>' +
            '  <td><a class="wd100" onclick="reset(this)">重置</a> <a onclick="vacc_del(this,\'product_table\')">删除</a>' +
            '   </td>' +
            '   </tr>' +
            '   </tbody>' +
            '    </table>';
        tablescroll.append(table);
        //全选
        $(".table").on('click', "th input[type='checkbox']", function () {
            if ($(this).is(":checked")) {
                $('.table').find("td input[type='checkbox']").prop("checked", true);
            } else {
                $('.table').find("td input[type='checkbox']").prop("checked", false);
            }
        });
        $('input[type!="hidden"],select,textarea', $("#add-form")).each(function () {
            $(this).validatebox();//验证初始化
        });
    }
    function sort() {
        var a = $("#product_table tr").length;
        var xunhao = 0;
        if ($("#type").val() == 42) {
            for (var j = 0; j < a; j = j + 2) {
                xunhao++;
                $("#product_table").find('tr').eq(j).find('td:first').find("span.xunhao").html(xunhao);
            }
        } else {
            for (var i = 0; i < a; i++) {
                $("#product_table").find('tr').eq(i).find('td:first').find("span.xunhao").html(i + 1);
            }
        }
    }
</script>
