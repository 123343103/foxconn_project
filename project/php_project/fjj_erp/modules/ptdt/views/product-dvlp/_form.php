<?php
/**
 * 新增修改表单
 * F3858995
 * 2016/9/27
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<h2 class="head-second">
    商品经理人
</h2>
<?php $form = ActiveForm::begin(['id' => "add-form"]) ?>
<input type="hidden" id="status" name="status" value="10">
<div class="mb-20">
    <div class="mb-20">
        <div class="mb-20">
            <div class="inline-block field-pdrequirement-develop_center required has-error">
                <label class="width-80" for="pdrequirement-develop_center">开发中心</label>
                <select id="pdrequirement-develop_center" class="easyui-validatebox width-200"
                        data-options="required:'true'" name="PdRequirement[develop_center]">
                    <option value="">请选择</option>
                    <?php foreach ($downList['developCenters'] as $key => $val) { ?>
                        <?php if (isset($planModel)) { ?>
                            <option
                                value="<?= $key ?>" <?= $planModel->develop_center == $key ? "selected" : null ?>><?= $val ?> </option>
                        <?php } else { ?>
                            <option value="<?= $key ?>"><?= $val ?> </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block field-pdrequirement-develop_department required has-error">
                <label class="width-80" for="pdrequirement-develop_department">开发部</label>
                <select id="pdrequirement-develop_department" class="width-200 easyui-validatebox"
                        data-options="required:'true'"
                        name="PdRequirement[develop_department]">
                    <option value="">请选择</option>
                    <?php if (isset($developDep)) { ?>
                        <?php foreach ($developDep as $key => $val) { ?>
                            <?php if (isset($planModel)) { ?>
                                <option
                                    value="<?= $key ?>" <?= $planModel->develop_department == $key ? "selected" : null ?>><?= $val ?> </option>
                            <?php } else { ?>
                                <option
                                    value="<?= $key ?>"><?= $val ?> </option>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block field-pdrequirement-develop_type required has-error">
                <label class="width-100" for="pdrequirement-develop_type">开发类型</label>
                <select id="pdrequirement-develop_type" class="width-200 easyui-validatebox"
                        name="PdRequirement[develop_type]" data-options="required:'true'">
                    <option value="">请选择</option>
                    <?php foreach ($downList['developTypes'] as $key => $val) { ?>
                        <?php if (isset($planModel)) { ?>
                            <option
                                    value="<?= $key ?>" <?= $planModel->develop_type == $key ? "selected" : null ?>><?= $val ?> </option>
                        <?php } else {?>
                            <option
                                    value="<?= $key ?>" ><?= $val ?> </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>

        </div>
        <div>
            <div class="inline-block field-pdrequirement-pdq_source_type required has-error">
                <label class="width-80" for="pdrequirement-pdq_source_type">需求类型</label>
                <select id="pdrequirement-pdq_source_type" class="width-200 easyui-validatebox"
                        name="PdRequirement[pdq_source_type]" data-options="required:'true'">
                    <option value="">请选择</option>
                    <?php foreach ($downList['requirementTypes'] as $key => $val) { ?>
                        <?php if (isset($planModel)) { ?>
                            <option
                                    value="<?= $key ?>" <?= $planModel->pdq_source_type == $key ? "selected" : null ?>><?= $val ?> </option>
                        <?php }else{ ?>
                            <option
                                    value="<?= $key ?>" ><?= $val ?> </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block field-pdrequirement-commodity required has-error">
                <label class="width-80" for="pdrequirement-commodity">商品大类</label>
                <select id="pdrequirement-commodity" class="width-200 easyui-validatebox"
                        name="PdRequirement[commodity]" data-options="required:'true'">
                    <option value="">请选择</option>
                    <?php foreach ($downList['productTypes'] as $key => $val) { ?>
                        <?php if (isset($planModel)) { ?>
                            <option
                                value="<?= $key ?>" <?= $planModel->commodity == $key ? "selected" : null ?>><?= $val ?> </option>
                        <?php }else { ?>
                            <option
                                value="<?= $key ?>" ><?= $val ?> </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>

            <div class="inline-block field-pdrequirement-product_manager required has-error">
                <label class="width-100" for="pdrequirement-product_manager">商品经理人</label>
                <select id="pdrequirement-product_manager" class="width-200 easyui-validatebox"
                        name="PdRequirement[product_manager]" data-options="required:'true'">

                    <option value="">请选择</option>
                    <?php foreach ($downList['staffManager'] as $key => $val) { ?>
                        <?php if (isset($planModel)) { ?>
                            <option
                                value="<?= $val['staff_id'] ?>" <?= $planModel->product_manager == $val['staff_id'] ? "selected" : null ?>><?= $val['staff_code']."-".$val['staff_name'] ?> </option>
                        <?php }else{ ?>
                            <option
                                value="<?= $val['staff_id'] ?>" ><?= $val['staff_code']."-".$val['staff_name']?> </option>
                        <?php } ?>
                    <?php } ?>

                </select>
            </div>
        </div>
    </div>
</div>
<h2 class="head-second">
    商品基本信息
</h2>
<div>
    <div class="mb-20">
        <label class="width-80">商品类别</label>
        <select class="width-130 type" id="type_1">
            <option value>请选择</option>
            <?php foreach ($downList['productTypes']  as $key => $val) { ?>
                <option value="<?= $key ?>"><?= $val ?></option>
            <?php } ?>
        </select>
        <select class="width-130 type" id="type_2">
            <option value>请选择</option>
        </select>
        <select class="width-130 type" id="type_3">
            <option value>请选择</option>
        </select>
        <select class="width-130 type" id="type_4">
            <option value>请选择</option>
        </select>
        <select class="width-130 type" id="type_5">
            <option value>请选择</option>
        </select>
        <select class="width-130 type" id="type_6">
            <option value>请选择</option>
        </select>
    </div>
    <div class="mb-20">
        <label class="width-80"><span class="red">*</span>商品品名</label>
        <input class="width-165" id="product_name">
        <label class="width-150 ml--5"><span class="red">*</span>规格型号</label>
        <input class="width-165" id="product_size">
        <label class="width-150 ml--5"><span class="red">*</span>商品定位</label>
        <select class="width-165" id="product_level_id"><!--BUG修正，添加为必填项-->
            <option value>请选择</option>
            <?php foreach ($downList['productLevel'] as $key => $val) { ?>
                <option value="<?= $key ?>"><?= $val ?></option>
            <?php } ?>
        </select>
        <!--<label class="width-150">单位</label>
        <input class="width-120" id="product_unit">-->
    </div>
    <div class="mb-20">
        <input id="index" type="hidden">
        <label class="width-80 vertical-top">商品要求</label>
        <textarea class="width-800" rows="3" id="product_requirement"></textarea>
    </div>
    <div class="mb-20">
        <label class="width-80 vertical-top">制程要求</label>
        <textarea class="width-800" rows="3" id='product_process_requirement'></textarea>
    </div>
    <div class="mb-20">
        <label class="width-80 vertical-top">品质要求</label>
        <textarea class="width-800" rows="3" id="product_quality_requirement"></textarea>
    </div>
    <div class="mb-20">
        <label class="width-80 vertical-top">备注</label>
        <textarea class="width-800" rows="3" id="other_des"></textarea>
    </div>
    <div class="text-center mb-20">
        <input type="hidden" id="product_id" value="1">
        <button type="button" id="save-button" class="button-blue-big">保存商品</button>
        <button type="button" id='edit-button' class="button-white-big ml-20">编辑商品</button>
        <button class="button-red-big ml-20" type="button" id="delete-button">删除</button>
    </div>
    <div class="overflow-auto width-900  margin-auto pb-20">
        <table class="product-list">
            <tbody id="table_body">
            <tr>
                <th>商品品名</th>
                <th>规格型号</th>
                <th>商品定位</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
                <th class="width-200">商品要求</th>
                <th class="width-200">制程要求</th>
                <th class="width-200">品质要求</th>
                <th class="width-200">备注</th>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="space-20"></div>
    <div class="text-center mb-20">
        <button type="submit" class="button_sub button-white-big" id="save">保存</button>
<!--        <button type="submit" class="button_sub button-blue-big ml-20"  id="sub">提交</button>-->
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>
</div>
<?php $form->end() ?>
<script>
    //关闭或刷新页面时提示
    $(window).bind('beforeunload',function(){
            return '您输入的内容尚未保存，确定离开此页面吗？';
    });


    $(function () {
        //提交
//        $("#sub").click(function () {
//            saveProduct();
//            $("#status").val("20");
//            return submitForm()
//
//        });
        //保存
        $("#save").click(function () {
            saveProduct();
            $("#status").val("10");
            return submitForm()
        });

        var $productName = $('#product_name');
        var $productSize = $('#product_size');
//        var $productUnit = $('#product_unit');
        var $productLevel = $('#product_level_id');
        var $typeOne = $('#type_1');
        var $typeTwo = $('#type_2');
        var $typeThree = $('#type_3');
        var $typeFour = $('#type_4');
        var $typeFive = $('#type_5');
        var $typeSix = $('#type_6');
        var $productRequirement = $('#product_requirement');
        var $productProcessRequirement = $('#product_process_requirement');
        var $productQualityRequirement = $('#product_quality_requirement');
        var $otherDes = $('#other_des');
        var $productId = $('#product_id');
        var y=1;
        function saveProduct(){
            if($productName.val() !='' && $productSize.val() !='' && $productLevel.val() !=''){
                appendTb()
            }
            if($('#table_body').children('tr').length<=1 ){
                return layer.alert("请先添加一个商品", {icon: 2, time: 5000});
            }
        }
        var i = 0;
        $("#delete-button").on("click", function () {
            var $selectTr = $('.table-click-tr');
            if ($selectTr.length == 0) {
                layer.alert("请先点击选择一个商品", {icon: 2, time: 5000});
                return;
            }
            $selectTr.remove();
        });

        /*保存商品*/
        $('#save-button').on('click', function () {
//            if ($typeOne.val() == 0) {
//                layer.alert("请选择商品类别", {icon: 2, time: 5000});
//                return;
//            }
            if ($productName.val() == "") {
                layer.alert("请填写商品品名", {icon: 2, time: 5000});
                return;
            }
          if ($productSize.val() == "") {
              layer.alert("请填写规格型号", {icon: 2, time: 5000});
               return;
          }
            /*if ($productUnit.val() == "") {
                layer.alert("请填写商品单位", {icon: 2, time: 5000});
                return;
            }*/
            if ($productLevel.val() == "") {
                layer.alert("请填写商品定位", {icon: 2, time: 5000});
                return;
            }

            appendTb()
        });

         function appendTb(){
            var trStr="<tr>";
            var tdStrEnd = "</tr>";
            var tdStr='';
            tdStr += "<td>" + htmlEncodeJQ($productName.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ($productSize.val()) + "</td>";
            //tdStr += "<td>" + $productUnit.val() + "</td>";/*BUG修正 除去display-none属性*/

            if ($productLevel.find('option:selected').val() != "") {
                tdStr += "<td>" + $productLevel.find('option:selected').text() + "</td>";
            } else {
                tdStr += "<td></td>";
            }
            var typeOne=$typeOne.find("option:selected").text();
            if(typeOne=='请选择'){
                typeOne='';
            }
            var typeTwo=$typeTwo.find("option:selected").text();
            if(typeTwo=='请选择'){
                typeTwo='';
            }
            var typeThree=$typeThree.find("option:selected").text();
            if(typeThree=='请选择'){
                typeThree='';
            }
            var typeFour=$typeFour.find("option:selected").text();
            if(typeFour=='请选择'){
                typeFour='';
            }
            var typeFive=$typeFive.find("option:selected").text();
            if(typeFive=='请选择'){
                typeFive='';
            }
            var typeSix=$typeSix.find("option:selected").text();
            if(typeSix=='请选择'){
                typeSix='';
            }
            tdStr += "<td>" + typeOne + "</td>";
            tdStr += "<td>" + typeTwo + "</td>";
            tdStr += "<td>" + typeThree + "</td>";
            tdStr += "<td>" + typeFour + "</td>";
            tdStr += "<td>" + typeFive + "</td>";
            tdStr += "<td>" + typeSix + "</td>";
            tdStr += "<td>" + htmlEncodeJQ($productRequirement.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ($productProcessRequirement.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ($productQualityRequirement.val()) + "</td>";
            tdStr += "<td>" + htmlEncodeJQ($otherDes.val()) + "</td>";
            tdStr += "<td class='product_id display-none' data-info="+y+">" + y + "</td>";


            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_name]" value="' + $productName.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_size]" value="' + $productSize.val() + '">';
//            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_unit]" value="' + $productUnit.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_level_id]" value="' + $productLevel.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_type_id]" value="' + $typeSix.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_requirement]" value="' + $productRequirement.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_process_requirement]" value="' + $productProcessRequirement.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][product_quality_requirement]" value="' + $productQualityRequirement.val() + '">';
            tdStr += '<input type="hidden" name="PdRequirementProduct[' + i + '][other_des]" value="' + $otherDes.val() + '">';

            $typeOne.find('option[value=""]').prop("selected", "selected");
            $typeTwo.find('option[value=""]').prop("selected", "selected");
            $productLevel.find('option[value=""]').prop("selected", "selected");
            $typeThree.find('option[value=""]').prop("selected", "selected");
            $typeFour.find('option[value=""]').prop("selected", "selected");
            $typeFive.find('option[value=""]').prop("selected", "selected");
            $typeSix.find('option[value=""]').prop("selected", "selected");
            $productName.val("");
            $productSize.val("");
//            $productUnit.val("");
            $productRequirement.val("");
            $productQualityRequirement.val("");
            $productProcessRequirement.val("");
             $otherDes.val("");
            var t_id=$(".product_id[data-info="+$productId.val()+"]");
             if(t_id.html()!=undefined){
                 t_id.parent().html(tdStr)
             }else{
                 $('#table_body').append(trStr+tdStr+tdStrEnd);
             }
            clearOption();
            y++;
                 $productId.val(y);
            i++;
            setMenuHeight()
        }
        $(document).on("click", '#table_body tr:nth-child(n+2)', function () {
            $('.table-click-tr').removeClass('table-click-tr');
            $(this).addClass('table-click-tr');
        });

         /*编辑商品*/
        $("#edit-button").on("click", function () {
            var $selectTr = $('.table-click-tr');
            if ($selectTr.length == 0) {
                layer.alert("请先点击选择一个商品", {icon: 2, time: 5000});
                return;
            }
            $productName.val($selectTr.find("td:nth-child(1)").text());
            $productSize.val($selectTr.find("td:nth-child(2)").text());
//            $productUnit.val($selectTr.find("td:nth-child(3)").text());
            if ($selectTr.find("td:nth-child(3)").text() != '') {
                $productLevel.find("option:contains('" + $selectTr.find("td:nth-child(3)").text() + "')").prop("selected", 'selected');
            }
            if($selectTr.find("td:nth-child(9)").text()!=''){
                $typeOne.find("option:contains('" + $selectTr.find("td:nth-child(4)").text() + "')").prop("selected", 'selected');
                getTypeNext($typeOne);
                $typeTwo.find("option:contains('" + $selectTr.find("td:nth-child(5)").text() + "')").prop("selected", 'selected');
                getTypeNext($typeTwo);
                $typeThree.find("option:contains('" + $selectTr.find("td:nth-child(6)").text() + "')").prop("selected", 'selected');
                getTypeNext($typeThree);
                $typeFour.find("option:contains('" + $selectTr.find("td:nth-child(7)").text() + "')").prop("selected", 'selected');
                getTypeNext($typeFour);
                $typeFive.find("option:contains('" + $selectTr.find("td:nth-child(8)").text() + "')").prop("selected", 'selected');
                getTypeNext($typeFive);
                $typeSix.find("option:contains('" + $selectTr.find("td:nth-child(9)").text() + "')").prop("selected", 'selected');
            }
            $productRequirement.val($selectTr.find("td:nth-child(10)").text());
            $productQualityRequirement.val($selectTr.find("td:nth-child(12)").text());
            $productProcessRequirement.val($selectTr.find("td:nth-child(11)").text());
            $("#other_des").val($selectTr.find("td:nth-child(13)").text());
            $("#product_id").val($selectTr.find("td:nth-child(14)").text());
//            $selectTr.remove();
        });
        $('.type').on("change", function () {
            var $select = $(this);
            getTypeNext($select);

        });
        //ajax请求部门
        $("#pdrequirement-develop_center").on('change', function () {
            var code = $(this).val();
            $.ajax({
                type: 'get',
                dataType: 'json',
                data: {"code": code},
                url: "<?=Url::to(['/ptdt/product-dvlp/get-develop-dep']); ?>",
                success: function (data) {
                    $('#pdrequirement-develop_department').html("<option value>请选择</option>");
                    for (var x in data) {
                        $('#pdrequirement-develop_department').append('<option value="' + x + '" >' + data[x] + '</option>')
                    }
                }
            })
        });

        <?php  if( \Yii::$app->controller->action->id == "edit") {?>
        var j = 200;   //足够大，防止衝突
        <?php foreach ($planModel->products as $key =>$val  ) {?>
        var editTdStr = '<tr>';

        editTdStr += "<td>" + '<?=$val->product_name  ?>' + "</td>";
        editTdStr += "<td>" + '<?=$val->product_size  ?>' + "</td>";
//        editTdStr += "<td class='display-none'>" + '<?//=$val->product_unit  ?>//' + "</td>";
        editTdStr += "<td>" + '<?= $val->levelName ?>' + "</td>";
        editTdStr += "<td>" + '<?= $val->typeName[0] ?>' + "</td>";
        editTdStr += "<td id='aa'>" + '<?= $val->typeName[1];  ?>' + "</td>";
        editTdStr += "<td>" + '<?= $val->typeName[2]; ?>' + "</td>";
        editTdStr += "<td>" + '<?= $val->typeName[3]; ?>' + "</td>";
        editTdStr += "<td>" + '<?= $val->typeName[4]; ?>' + "</td>";
        editTdStr += "<td>" + '<?= $val->typeName[5];  ?>' + "</td>";
        editTdStr += "<td>" + '<?=$val->product_requirement  ?>' + "</td>";
        editTdStr += "<td>" + '<?=$val->product_process_requirement  ?>' + "</td>";
        editTdStr += "<td>" + '<?=$val->product_quality_requirement  ?>' + "</td>";
        editTdStr += "<td>" + '<?=$val->other_des  ?>' + "</td>";
        editTdStr += "<td class='display-none product_id' data-info=" + y + ">" + y + "</td>";

        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_name]" value="' + '<?=$val->product_name  ?>' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_size]" value="' + '<?=$val->product_size  ?>' + '">';
//        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_unit]" value="' + '<?//=$val->product_unit  ?>//' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_level_id]" value="' + '<?=  $val->product_level_id ?>' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_type_id]" value="' + '<?= $val->product_type_id ?>' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_requirement]" value="' + '<?=$val->product_requirement  ?>' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_process_requirement]" value="' + '<?=$val->product_process_requirement  ?>' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][product_quality_requirement]" value="' + '<?=$val->product_quality_requirement  ?>' + '">';
        editTdStr += '<input type="hidden" name="PdRequirementProduct[' + j + '][other_des]" value="' + '<?=$val->other_des  ?>' + '">';
        editTdStr += "</tr>";
        $('#table_body').append(editTdStr);
        j++;
        y++;
        $productId.val(y);
        setMenuHeight();
        <?php } ?>
        <?php } ?>

        $("#table_body").on('mouseover','td,th',function(){
            this.title=$(this).text();
        })
    });

    //分级分类
    function getTypeNext($select){
        getNextType($select, "<?=Url::to(['/ptdt/product-dvlp/get-product-type']) ?>", "select");
    }
//    //转义
//    function htmlEncode(value){
//        return $('<div/>').text(value).html();
//    }
//    //Html解码获取Html实体
//    function htmlDecode(value){
//        return $('<div/>').html(value).text();
//    }
    //提交
    function submitForm(){
        $("#add-form").on("beforeSubmit", function () {
            if (!$(this).form('validate')) {
                $("button[type='submit']").prop("disabled",false);
                return false;
            }
        $(window).off('beforeunload');

            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                if (data.url != undefined) {
                                    parent.location.href = data.url;
                                }

                            }
                        });
                    }
                    if (data.flag == 2) {
                        var id=data.msg;
                        var url="<?=Url::to(['view'])?>?id="+id;
                        var type=11;
                        $.fancybox({
                            href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                            type:"iframe",
                            padding:0,
                            autoSize:false,
                            width:750,
                            height:480
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                        $("button[type='submit']").prop("disabled", false);
                    }
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };
            $("#add-form").ajaxSubmit(options);
            return false;
    });
}
</script>