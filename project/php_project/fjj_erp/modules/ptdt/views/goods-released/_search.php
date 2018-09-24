<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/11
 * Time: 14:32
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="crm-credit-apply-search">

    <?php $form = ActiveForm::begin([
//        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="search-div" style="margin:0;">
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align">品名：</label>
                <input type="text" name="FpPriceSearch[pdt_name]" class="value-width value-align"
                       value="<?= isset($queryParam['FpPriceSearch']['pdt_name']) ? $queryParam['FpPriceSearch']['pdt_name'] : '' ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">料号：</label>
                <input type="text" name="FpPriceSearch[part_no]" class="value-width value-align"
                       value="<?= isset($queryParam['FpPriceSearch']['part_no']) ? $queryParam['FpPriceSearch']['part_no'] : '' ?>">
            </div>
            <div class="inline-block">
                <label class="label-width label-align">料号状态：</label>
                <select class="value-width value-align" name="FpPriceSearch[status]" id="">
                    <option value="">请选择...</option>
                    <option value="10">已发起上架</option>
                    <option value="20" selected="selected">未发起上架</option>
                </select>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="label-width label-align">类别：</label>
                <select class="type" name="FpPriceSearch[levelOne]" id="" style="width: 200px;">
                    <option value="">请选择...</option>
                    <?php foreach ($downList['category'] as $key => $val) { ?>
                        <option
                            value="<?= $val['catg_id'] ?>" <?= isset($queryParam['FpPriceSearch']['levelOne']) && $queryParam['FpPriceSearch']['levelOne'] == $val['catg_id'] ? "selected" : null ?>><?= $val['catg_name'] ?></option>
                    <?php } ?>
                </select>
                <select class="type" name="FpPriceSearch[levelTwo]" id="" style="width: 200px;">
                    <option value="">请选择...</option>
                    <?php foreach ($levelTwo as $key => $val) { ?>
                        <option
                            value="<?= $val['catg_id'] ?>" <?= isset($queryParam['FpPriceSearch']['levelTwo']) && $queryParam['FpPriceSearch']['levelTwo'] == $val['catg_id'] ? "selected" : null ?>><?= $val['catg_name'] ?></option>
                    <?php } ?>
                </select>
                <select class="type" name="FpPriceSearch[levelThree]" id="" style="width: 200px;">
                    <option value="">请选择...</option>
                    <?php foreach ($levelThree as $key => $val) { ?>
                        <option
                            value="<?= $val['catg_id'] ?>" <?= isset($queryParam['FpPriceSearch']['levelThree']) && $queryParam['FpPriceSearch']['levelThree'] == $val['catg_id'] ? "selected" : null ?>><?= $val['catg_name'] ?></option>
                    <?php } ?>
                </select>
            </div>


            <?= Html::button('查询', ['class' => 'search ml-130 search-btn-blue', 'type' => 'button']) ?>
            <?= Html::button('重置', ['class' => 'reset button-blue reset-btn-yellow']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style type="text/css">
    .label-width {
        width: 100px;
    }

    .value-width {
        width: 131px;
    }
</style>
<script>
    var params={};
    var formArr=$("form").serializeArray();
    formArr.forEach(function(row){
        params[row.name]=row.value;
    });
    $(function () {
        $(".search").click(function(){
            var formArr=$("form").serializeArray();
            formArr.forEach(function(row){
                params[row.name]=row.value;
            });
            $("#data").datagrid("reload",params);
        });

        $(".reset").click(function(){
            document.querySelector("form").reset();
            var formArr=$("form").serializeArray();
            formArr.forEach(function(row){
                params[row.name]=row.value;
            });
            $("#data").datagrid("reload",params);
        });

        $('.type').on("change", function () {
            var $select = $(this);
            var $url = "<?= Url::to(['get-category-type']) ?>";
            getCategoryType($select, $url);
        });
    })
</script>
