<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div>
    <div>
        <div class="inline-block mb-20">
            <label class="width-80 text-left" for="part_no">料号</label>
            <input type="text" id="part_no" class="width-200" name="pdt_no"
                   value="<?= $get['pdt_no'] ?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-80 ml-20 text-left" for="pdt_name">品名</label>
            <input type="text" id="pdt_name" class="width-200" name="pdt_name"
                   value="<?= $get['pdt_name'] ?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-80 ml-20 text-left" for="pdt_name">品牌</label>
            <input type="text" id="pdt_name" class="width-200" name="brand_name"
                   value="<?= $get['brand_name'] ?>">
            <div class="help-block"></div>
        </div>

        <div class="space-10"></div>

        <div class="inline-block mb-20">
            <label class="width-80 text-left" for="part_no">供应商名称</label>
            <input type="text" id="part_no" class="width-200" name="supplier_name"
                   value="<?= $get['supplier_name'] ?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block">
            <label class="width-80 ml-20 text-left" for="pdt_name">商品属性</label>
            <input type="text" id="pdt_name" class="width-200" name="pdt_attribute"
                   value="<?= $get['pdt_attribute'] ?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-80 ml-20 text-left" for="pdt_name">商品经理人</label>
            <input type="text" id="pdt_name" class="width-200" name="pdt_manager"
                   value="<?= $get['pdt_manager'] ?>">
            <div class="help-block"></div>
        </div>

        <div class="space-10"></div>

        <div class="inline-block">
            <div class="mb-20">
                <label class="width-80 text-left" for="type">类别</label>
                <?=Html::dropDownList("type_1",$get["type_1"],$type1,["id"=>"type_1","class"=>"width-100 type"])?>
                <?=Html::dropDownList("type_2",$get["type_2"],$type2,["id"=>"type_2","class"=>"width-100 type"])?>
                <?=Html::dropDownList("type_3",$get["type_3"],$type3,["id"=>"type_3","class"=>"width-100 type"])?>
                <?=Html::dropDownList("type_4",$get["type_4"],$type4,["id"=>"type_4","class"=>"width-100 type"])?>
                <?=Html::dropDownList("type_5",$get["type_5"],$type5,["id"=>"type_5","class"=>"width-100 type"])?>
                <?=Html::dropDownList("type_6",$get["type_6"],$type6,["id"=>"type_6","class"=>"width-100 type"])?>
            </div>
        </div>


        <div class="inline-block ">
            <label class="width-50" for="status">状态</label>
            <select class="width-80 type" id="status" name="status">
                <option value="">请选择</option>
                <option value="1">正常</option>
                <option value="0">封存</option>
            </select>
        </div>

        <?= Html::submitButton('查询', ['class' => 'button-blue', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>

    </div>
    <?php ActiveForm::end(); ?>
    <script>
        $(function () {
            $('.type').on("change", function () {
                var $select = $(this);
                getMyNextType($select, "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>", "select");
            });
        });
        /**
         * 分类级联
         * @param $select  //第一个select
         * @param url     // "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>",
         */
        function getMyNextType($select, url, selectStr) {
            var id = $select.val();
            if (id == "") {
                clearOption($select);
                return;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: url,
                success: function (data) {
                    var $nextSelect = $select.next(selectStr);
                    clearOption($nextSelect);
                    $nextSelect.html('<option value>请选择</option>');
                    if ($nextSelect.length != 0)
                        for (var x in data) {
                            $nextSelect.append('<option value="' + data[x].category_id + '" >' + data[x].category_name + '</option>');
                        }
                }

            })
        }

    </script>

