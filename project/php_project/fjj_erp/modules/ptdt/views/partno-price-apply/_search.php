<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div>
    <div>
        <div class="inline-block mb-20">
            <label class="width-80" for="part_no">料号</label>
            <input type="text" id="part_no" class="width-170" name="pdt_no"
                   value="<?= $get['pdt_no'] ?>">

            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-80" for="pdt_name">品名</label>
            <input type="text" id="pdt_name" class="width-170" name="pdt_name"
                   value="<?= $get['pdt_name'] ?>">

            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-90" for="status">状态</label>
            <?=Html::dropDownList("status",$get["status"],$downlist["status"],["class"=>"width-100"])?>
        </div>

        <div class="inline-block ">
            <label class="width-150" for="startdate">定价发起来源</label>
            <?=Html::dropDownList("price_from",$get["price_from"],$downlist["price_from"],["class"=>"width-100"])?>
        </div>

            <div class="help-block"></div>
        </div>

        <div class="space-10"></div>
        <div class="inline-block mb-20">
            <label class="width-80" for="part_no">商品经理人</label>
            <input type="text" id="part_no" class="width-170" name="pdt_manager"
                   value="<?= $get['part_no'] ?>">

            <div class="help-block"></div>
        </div>
        <div class="inline-block mb-20">
            <label class="width-80" for="part_no">定价类型</label>
            <?=Html::dropDownList("price_type",$get["price_type"],$downlist["price_type"],["class"=>"width-100"])?>

            <div class="help-block"></div>
        </div>
        <div class="inline-block mb-20">
            <label class="width-90" for="part_no">商品定位</label>
            <?=Html::dropDownList("pdt_level",$get["pdt_level"],$downlist["pdt_level"],["class"=>"width-100"])?>

            <div class="help-block"></div>
        </div>

        <?= Html::submitButton('查询', ['class' => 'button-blue ml-60', 'type' => 'submit']) ?>
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

