<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);

?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<style>
    .label-width{
        width:100px;
    }
    .value-width{
        width:140px;
    }
</style>
<div>
    <div>
        <div class="mb-10">
            <label class="label-align label-width" for="inv_id">预警编号：</label>
            <input type="text" id="inv_id" class="value-align value-width" name="inv_id"
                   value="<?= $get['inv_id'] ?>"
            >
            <label class="label-width label-align" for="wh_name">仓库名称：</label>
            <select name="wh_id" class="value-align value-width" id="wh_id">
                <option value="">请选择</option>
                <?php foreach ($downList['whname'] as $val) { ?>
                    <?php if ($get['wh_id'] == $val['wh_id']) { ?>
                        <option selected="selected" value="<?= $val['wh_id'] ?>"
                                name="wh_id"><?= $val['wh_name'] ?></option>
                    <?php } else { ?>
                        <option value="<?= $val['wh_id'] ?>" name="wh_id"><?= $val['wh_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div>
            <label class="label-align label-width" for="startdate">申请时间：</label>
            <input type="text" id="startDate" class="Wdate value-width value-align "
                   onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', maxDate: '%y-%M-%d %H:%m' })"
                   name="startDate"
                   value="<?= $get['startDate'] ?>"
            >
            <label class="no-after label-width" for="enddate" style="text-align:center">至</label>
            <input type="text" id="endDate" class="value-width Wdate"
                   name="endDate"
                   onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd', minDate: '#F{$dp.$D(\'startDate\');}',maxDate:'%y-%M-%d %H:%m' })"
                   value="<?= $get['endDate'] ?>"
            >
            <label class="label-width label-align" for="so_type">状态：</label>
            <?= Html::dropDownList("so_type", $get["so_type"], $downList["so_type"], ["class" => "value-align value-width"]) ?>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-80', 'type' => 'submit','style'=>'margin-left:20px']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-40', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
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

