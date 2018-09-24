<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
$get = Yii::$app->request->get();
if (!isset($get['pdt_name'])) {
    $get['pdt_no'] = null;
    $get['pdt_name'] = null;
    $get['status'] = null;
    $get['startDate'] = null;
    $get['endDate'] = null;
    $get['type_1'] = null;
    $get['type_2'] = null;
    $get['type_3'] = null;
    $get['type_4'] = null;
    $get['type_5'] = null;
    $get['type_6'] = null;
}
?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div>
    <div class="mb-10">
        <div class="inline-block ">
            <label class="width-50 qlabel-align" style="width: 80px;" for="part_no">料号</label>
            <label>:</label>
            <input type="text" id="pdt_no" class="width-200 qvalue-align" style="width: 130px;" name="pdt_no"
                   value="<?= $get['pdt_no'] ?>">

            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-50 qlabel-align" style="width: 80px" for="pdt_name">品名</label>
            <label>:</label>
            <input type="text" id="pdt_name" class="width-200 qvalue-align" style="width: 130px" name="pdt_name"
                   value="<?= $get['pdt_name'] ?>">

            <div class="help-block"></div>
        </div>
        <div class="inline-block ">

            <label class="width-50 qlabel-align" for="status" style="width: 80px;">状态</label>
            <label>:</label>
            <select class="width-100 type qvalue-align" id="status" name="status" style="width: 100px;">
                <option value="">请选择</option>
                <?php foreach ($statusType as $key => $val) { ?>
                    <option <?= (!isset($get['status']) && $key == 4 || $get['status'] == $key) ? "selected" : "" ?>
                        value="<?= $key ?>"><?= $val ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="inline-block ">
            <label class="width-90 qlabel-align" for="startdate" style="width: 80px;">定价时间</label>
            <label>:</label>
            <!--            <input type="text" id="startdate" class="width-100 select-date qvalue-align"-->
            <!--                   name="startDate"-->
            <!--                   value="--><?//= $get['startDate'] ?><!--"-->
            <!--            >-->

            <input type="text" class=" qvalue-align" id="startdate"  style="width: 90px;" name="startDate"
                   value="<?= $get['startDate'] ?>">
            <label class="no-after">~</label>
            <input type="text" class="qvalue-align" id="enddate" style="width: 90px;" name="endDate"
                   value="<?= $get['endDate'] ?>">
            <div class="help-block"></div>
        </div>
    </div>
    <div class="mb-10">

<!--        <div class="inline-block">-->
<!--            <label class="no-after" for="enddate">~</label>-->
<!--            <input type="text" id="enddate" class="width-100 select-date qvalue-align"-->
<!--                   name="endDate"-->
<!--                   value="--><?//= $get['endDate'] ?><!--"-->
<!--            >-->
<!---->
<!--            <div class="help-block"></div>-->
<!--        </div>-->


        <div class="inline-block ">
                <label class="width-50 qlabel-align " style="width: 80px;" for="type">类别</label>
                <label>:</label>
                <select class="width-130 type qvalue-align" style="width: 120px;" id="type_1" name="type_1">
                    <option value>请选择</option>
                    <?php foreach ($productTypeIdToValue as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($get['type_1']) && $get['type_1'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type qvalue-align" style="width: 120px;"  id="type_2" name="type_2">
                    <option value>请选择</option>
                    <?php foreach ($type2 as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($get['type_2']) && $get['type_2'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type qvalue-align" style="width: 120px;"  id="type_3" name="type_3">
                    <option value>请选择</option>
                    <?php foreach ($type3 as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($get['type_3']) && $get['type_3'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type qvalue-align" style="width: 120px;"  id="type_4" name="type_4">
                    <option value>请选择</option>
                    <?php foreach ($type4 as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($get['type_4']) && $get['type_4'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type qvalue-align" style="width: 120px;"  id="type_5" name="type_5">
                    <option value>请选择</option>
                    <?php foreach ($type5 as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($get['type_5']) && $get['type_5'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <select class="width-130 type qvalue-align" style="width: 120px;"  id="type_6" name="type_6">
                    <option value>请选择</option>
                    <?php foreach ($type6 as $key => $val) { ?>
                        <option
                            value="<?= $key ?>" <?= isset($get['type_6']) && $get['type_6'] == $key ? "selected" : null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
        </div>
        <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-10', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>


</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $('.type').on("change", function () {
            var $select = $(this);
            getMyNextType($select, "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>", "select");
        });
        var start = {
            format: 'YYYY-MM-DD',
            minDate: '2000-01-01',
//            maxDate: $.nowDate({DD: 0}).substring(0, 10) + ' 22:59:00', //最大日期
            okfun: function (obj) {
                end.minDate = obj.val; //开始日选好后，重置结束日的最小日期
                endDates();
            }
        };
        var end = {
            format: 'YYYY-MM-DD',
//            maxDate: $.nowDate({DD: 0}).substring(0, 10) + ' 22:59:00', //设定最小日期为当前日期
            okfun: function (obj) {
                start.maxDate = obj.val; //将结束日的初始值设定为开始日的最大日期
            }
        };
        //        //这里是日期联动的关键
        function endDates() {
            //将结束日期的事件改成 false 即可
            end.trigger = false;
            $("#enddate").jeDate(end);
        }

        $('#startdate').jeDate(start);
        $('#enddate').jeDate(end);
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

