<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$get = Yii::$app->request->get();
if (!isset($get['pdt_name'])) {
    $get['part_no'] = null;
    $get['pdt_name'] = null;
    $get['status'] = null;
    $get['startDate'] = null;
    $get['endDate'] = null;
}
?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div>
    <div>
        <div class="inline-block mb-20">
            <label class="width-50" for="part_no">料号</label>
            <input type="text" id="part_no" class="width-170" name="part_no"
                   value="<?= $get['part_no'] ?>">

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
            <select class="width-100" name="status" id="">
                <option value="">请选择</option>
                <option value="0">未定价</option>
                <option value="1">发起定价</option>
                <option value="2">商品开发维护</option>
                <option value="3">审核中</option>
                <option value="4">已定价</option>
                <option value="5">被驳回</option>
                <option value="6">已逾期</option>
                <option value="7">重新定价</option>
            </select>
        </div>

        <div class="inline-block ">

            <label class="width-90" for="status">定价方式</label>
            <select class="width-100 type" id="status" name="p_flag">
                <option value="">请选择</option>
                <option value="1">标准定价</option>
                <option value="2">实时定价</option>
            </select>
        </div>



        <div>
            <div class="mb-20" style="display: inline-block;">
                <label class="width-50" for="type">类别</label>
                <select class="width-130 type" id="type_1" name="type_1">
                    <option value>请选择</option>
                    <?php foreach ($productTypeIdToValue as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($get['type_1']) && $get['type_1'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type" id="type_2" name="type_2">
                    <option value>请选择</option>
                    <?php foreach ($type2 as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($get['type_2']) && $get['type_2'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type" id="type_3" name="type_3">
                    <option value>请选择</option>
                    <?php foreach ($type3 as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($get['type_3']) && $get['type_3'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type" id="type_4" name="type_4">
                    <option value>请选择</option>
                    <?php foreach ($type4 as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($get['type_4']) && $get['type_4'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type" id="type_5" name="type_5">
                    <option value>请选择</option>
                    <?php foreach ($type5 as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($get['type_5']) && $get['type_5'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type" id="type_6" name="type_6">
                    <option value>请选择</option>
                    <?php foreach ($type6 as $key => $val) { ?>
                        <option
                                value="<?= $key ?>" <?= isset($get['type_6']) && $get['type_6'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="display: inline-block;">
                <?=Html::submitButton('查询', ['class' => 'button-blue ml-10', 'type' => 'submit']) ?>
                <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            </div>
        </div>



            <div class="help-block"></div>
        </div>

        <div class="space-10"></div>



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

