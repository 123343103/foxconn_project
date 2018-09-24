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
        <div class="mb-20">
            <label class="width-100" for="part_no">料号</label>
            <input type="text" id="part_no" class="width-120" name="part_no"
                   value="<?= $get['part_no'] ?>">
            <label class="width-100" for="part_no">商品经理人</label>
            <input type="text" id="part_no" class="width-120" name="pdt_manager"
                   value="<?= $get['part_no'] ?>">
            <label class="width-100" for="part_no">供应商简称</label>
            <input type="text" id="part_no" class="width-120" name="supplier_name_shot"
                   value="">
            <label class="width-100" for="part_no">商品定位</label>
            <select class="width-120 type" id="status" name="pdt_level">
                <option value="">请选择</option>
                <option value="1">高</option>
                <option value="2">中</option>
                <option value="3">低</option>
            </select>
        </div>

        <div class="mb-20">

            <label class="width-100" for="status">状态</label>
            <select class="width-100 type" id="status" name="status">
                <option value="">请选择</option>
                <?php foreach($statusType as $key=>$val){ ?>
                    <option <?=(!isset($get['status']) && $key==4 || $get['status']==$key)?"selected":"" ?>
                            value="<?=$key?>"><?=$val?></option>
                <?php } ?>
            </select>

            <label class="width-100" for="startdate">有效期</label>
            <input type="text" name="valid_date_start" class="select-date">
            至
            <input type="text" name="valid_date_end" class="select-date">
            </select>


            <?= Html::submitButton('查询', ['class' => 'button-blue ml-60', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>


        </div>

        </div>

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

