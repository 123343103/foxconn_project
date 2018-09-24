<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$get = Yii::$app->request->get();
if(!isset($get['PdRequirementSearch'])){
    $get['PdRequirementSearch']=null;
}
?>
<?php $form = ActiveForm::begin(['method' => "get","action"=>"index"]); ?>
<div>
    <div>
        <div class="inline-block ">
            <label class="width-100" for="pdrequirementsearch-pdq_code">计划编号</label>
            <input type="text" id="pdrequirementsearch-pdq_code" class="width-150" name="PdRequirementSearch[pdq_code]"
                   value="<?=$get['PdRequirementSearch']['pdq_code'] ?>"
            >

            <div class="help-block"></div>
        </div>    <div class="inline-block">
            <label class="width-100" for="pdrequirementsearch-pdq_source_type">需求类型</label>
            <select id="pdrequirementsearch-pdq_source_type" class="width-150" name="PdRequirementSearch[pdq_source_type]">
                <option value="">请选择</option>
                <?php foreach ($requirementType as $key => $val) {?>
                    <option value="<?=$key ?>" <?= isset($get['PdRequirementSearch']['pdq_source_type'])&&$get['PdRequirementSearch']['pdq_source_type']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>    <div class="inline-block ">
            <label class="width-100" for="pdrequirementsearch-startdate">提出时间</label>
            <input type="text" id="pdrequirementsearch-startdate" class="width-100 select-date" name="PdRequirementSearch[startDate]"
                   value="<?= $get['PdRequirementSearch']['startDate'] ?>"
            >

            <div class="help-block"></div>
        </div>    <div class="inline-block">
            <label class="no-after" for="pdrequirementsearch-enddate">~</label>
            <input type="text" id="pdrequirementsearch-enddate" class="width-100 select-date" name="PdRequirementSearch[endDate]"
                   value="<?= $get['PdRequirementSearch']['endDate'] ?>"
            >

            <div class="help-block"></div>
        </div>

        <div class="space-10"></div>

        <div class="inline-block ">
            <label class="width-100" for="pdrequirementsearch-develop_center">开发中心</label>
            <select id="pdrequirementsearch-develop_center" class="width-150" name="PdRequirementSearch[develop_center]">
                <option value="">请选择</option>
                <?php foreach ($developCenter as $key => $val) {?>
                    <option value="<?=$key ?>" <?= isset($get['PdRequirementSearch']['develop_center'])&&$get['PdRequirementSearch']['develop_center']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>
        <div class="inline-block">
            <label class="width-100    " for="pdrequirementsearch-develop_department">开发部</label>
            <select id="pdrequirementsearch-develop_department" class="width-150" name="PdRequirementSearch[develop_department]">
                <option value="">请选择</option>
                <?php foreach ($developDep as $key => $val) {?>
                    <option value="<?=$key ?>" <?= isset($get['PdRequirementSearch']['develop_department'])&&$get['PdRequirementSearch']['develop_department']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>    <div class="inline-block ">
            <label class="width-100" for="pdrequirementsearch-pdq_status">状态</label>
            <select id="pdrequirementsearch-pdq_status" class="width-100" name="PdRequirementSearch[pdq_status]">
                <option value="">请选择</option>
                <?php foreach ($pdqStatus as $key => $val) {?>
                    <option value="<?=$key ?>" <?= isset($get['PdRequirementSearch']['pdq_status'])&&$get['PdRequirementSearch']['pdq_status']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>

            <div class="help-block"></div>
        </div>


        <?= Html::submitButton('查询', ['class' => 'button-blue ml-50', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>

    </div>
    <?php ActiveForm::end(); ?>
    <script>
        $(function () {
            $("#pdrequirementsearch-develop_center").on('change', function () {
                var code = $(this).val();
                $.ajax({
                    type: 'get',
                    dataType: 'json',
                    data: {"code": code},
                    url: "<?=Url::to(['/ptdt/product-dvlp/get-develop-dep']); ?>",
                    success: function (data) {
                        $('#pdrequirementsearch-develop_department').html("<option value>请选择</option>");
                        for (x in data) {
                            $('#pdrequirementsearch-develop_department').append('<option value="' + x + '" >' + data[x] + '</option>')
                        }
                    }
                })
            });
        })

    </script>

