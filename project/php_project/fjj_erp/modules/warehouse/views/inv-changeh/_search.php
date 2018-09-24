<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/10
 * Time: 下午 03:34
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);

$get = Yii::$app->request->get();
if (!isset($get['InvChangehSearch'])) {
    $get['InvChangehSearch'] = null;
}
//dumpE($downList);
?>
<style>
    .label-align{width: 70px;}
    .width-110{width: 70px;}
    .width-130{width: 130px;}
    .width-200{width: 130px;}
</style>

<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div>
    <div>
        <div class="inline-block mb-20">
            <label class="label-align" for="wh_code">报废单号</label><label>:</label>
            <input type="text" id="wh_code" class="width-130" name="InvChangehSearch[chh_code]"
                   value="<?= $queryParam['chh_code'] ?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-110" for="wh_name">报废单状态</label><label>:</label>
            <select name="InvChangehSearch[chh_status]" class="width-130">
                <option value="">全部</option>
                <option
                    value="10" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '20' ? "selected" : null; ?>>
                    待提交
                </option>
                <option
                    value="20" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '30' ? "selected" : null; ?>>
                    审核中
                </option>
                <option
                    value="30" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '40' ? "selected" : null; ?>>
                    审核完成
                </option>
                <option
                    value="40" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '50' ? "selected" : null; ?>>
                    驳回
                </option>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="inline-block ">
            <label class="width-110" for="wh_type">报废类别</label><label>:</label>
            <select name="InvChangehSearch[chh_type]" class="width-130">
                <option value="">全部</option>
                <?php foreach ($downlist["changeType"] as $key => $val) { ?>
                    <option
                        value="<?= $val['business_type_id'] ?>" <?= isset($queryParam['chh_type']) && $queryParam['chh_type'] == $val['business_type_id'] ? "selected" : null ?>><?= $val['business_value'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="inline-block ">
            <label class="width-110" for="wh_state">出仓仓库</label><label>:</label>
            <select name="InvChangehSearch[wh_id]" class="width-130">
                <option value="">全部</option>
                <?php foreach ($whname as $val) { ?>
                    <option
                        value="<?= $val['wh_id'] ?>" <?= isset($get['InvChangehSearch']['wh_id']) && $get['InvChangehSearch']['wh_id'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="help-block"></div>
    </div>

    <div class="space-10"></div>
    <div class="inline-block">
        <label class="label-align" for="part_no">申请部门</label><label>:</label>
        <select name="InvChangehSearch[organization_id]" style="width: 130px;">
            <option value="">全部</option>
            <?php foreach ($downlist['organization'] as $key => $val) { ?>
                <option
                    value="<?= $val['organization_id'] ?>" <?= isset($get['InvChangehSearch']['organization_id']) && $get['InvChangehSearch']['organization_id'] == $val['organization_id'] ? "selected" : null ?>><?= $val['organization_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="inline-block mb-20">
        <label class="width-110" for="part_no">申请人</label><label>:</label>
        <input type="text" id="staff_name" class="width-130" name="InvChangehSearch[staff_name]"
               value="<?= $queryParam['staff_name'] ?>">

        <div class="help-block"></div>
    </div>
    <div class="inline-block mb-20">
        <label class="width-110" for="part_no">制单时间</label><label>:</label>
        <input id="start_date" type="text" class="Wdate width-200" readonly="readonly"
               name="InvChangehSearch[start]" value="<?= $queryParam['start'] ?>">
<!--        <input type="text" id="hrstaff_name" class="width-130" name="InvChangehSearch[review_by]"-->
<!--               value="--><?//= $get['hrstaff_name'] ?><!--">-->
        <div class="help-block"></div>
    </div>
    <div class="inline-block mb-20">
        <label class="width-110" for="part_no">至 &nbsp;&nbsp;&nbsp;&nbsp; </label>
        <input id="end_date" type="text" class="Wdate width-200" readonly="readonly"
               name="InvChangehSearch[end]" value="<?= $queryParam['end'] ?>">
<!--        <input type="text" id="hrstaff_name" class="width-130" name="InvChangehSearch[review_by]"-->
<!--               value="--><?//= $get['hrstaff_name'] ?><!--">-->
        <div class="help-block"></div>
    </div>
    <div class="float-right mr-115">
        <?= Html::submitButton('查询', ['class' => 'button-blue ml-10 search-btn-blue', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue reset-btn-yellow', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        //制单时间
        $("#start_date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                maxDate:"#F{$dp.$D('end_date',{d:-1})}"
            });
        });
        //结束时间
        $("#end_date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                minDate:"#F{$dp.$D('start_date',{d:1})}"
            });
        });


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


