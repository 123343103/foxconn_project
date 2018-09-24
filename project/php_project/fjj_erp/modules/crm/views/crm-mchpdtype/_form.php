<?php
/**
 * User: F3859386
 */
/* @var $this yii\web\view */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\assets\MultiSelectAsset;

MultiSelectAsset::register($this);

?>
<style>
    .select2-container .select2-selection--single .select2-selection__rendered {
        overflow: visible !important;
    }

    .label-width {
        width: 80px;
    }

    .value-width {
        width: 250px;
    }

    .value-m {
        width: 152px;
    }

    .value-s {
        width: 120px;
    }

    .margin {
        margin-left: 120px;
    }

    .ml-20 {
        margin-left: 80px;
    }

    .mt-20 {
        margin-top: 40px;
    }

    .mb-10 {
        margin-bottom: 20px;
    }

    .select2-container--krajee .select2-selection--single {
        height: 25px;
        width: 250px;
        line-height: 0.8;
        padding: 6px 24px 6px 12px;
    }
</style>
<div class="pop-head">
    <p><?= Html::encode($this->title) ?></p>
</div>
<div class="mb-20"></div>
<div class="ml-100 mt-20">
    <?php ActiveForm::begin([
        'action' => Url::to([empty($model['id']) ? 'create' : 'update']),
        'id' => 'add-form',
        'class' => 'ml-10'
    ]); ?>
    <input type="hidden" name="CrmMchpdtype[id]" value="<?= $model['id'] ?>">
    <?php if (\Yii::$app->controller->action->id == "create" && Yii::$app->user->identity->is_supper == 1) { ?>

    <div class="mb-10">
        <label class="label-width qlabel-align ml-20"><span class="red">*</span>人员</label><label>：</label>
        <?php
        echo Select2::widget([
            'name' => 'CrmMchpdtype[staff_code]',
            'class' => 'value-width qvalue-align',
            'id' => 'staff_code',
            'data' => yii\helpers\ArrayHelper::map($downList['staffList'], 'staff_code', 'staff_name'),
            'options' => ['placeholder' => '请选择...'],
            'value' => $model['staff_code']
        ]); ?>
        <?php } else if (\Yii::$app->controller->action->id == "create" && Yii::$app->user->identity->is_supper != 1) { ?>
            <label class="label-width qlabel-align ml-20"><span class="red">*</span>人员</label><label>：</label>
            <input type="hidden" name="CrmMchpdtype[staff_code]"
                   value="<?= Yii::$app->user->identity->staff->staff_code ?>">
            <span class="value-width qvalue-align "><?= Yii::$app->user->identity->staff->staff_name ?></span>
        <?php } ?>
        <?php if (\Yii::$app->controller->action->id == "update") { ?>
            <label class="label-width qlabel-align ml-20"><span class="red">*</span>人员</label><label>：</label>
            <span class="value-width qvalue-align "><?= $model['staff_name'] ?></span>
        <?php } ?>
        <div class="mb-10" style="margin-top: 10px">
            <div class="inline-block field-pdproductmanager-parent_id">
                <label class="label-width qlabel-align ml-20" for="pdproductmanager-parent_id"><span
                        class="red">*</span> 商品分类</label><label>：</label>
                <div class="multi-select">
                    <?php
                    $catArr = array_filter(explode(",", $model['category_id']));
                    krsort($catArr);
                    $catNameArr = [];
                    if (count($catArr) > 0) {
                        foreach ($catArr as $v) {
                            $catNameArr[] = $categorys[$v];
                        }
                    }
                    $catStr = implode(",", $catArr);
                    $catNameStr = implode(",", array_filter($catNameArr));
                    ?>
                    <input class="value-width qvalue-align multi-select-title easyui-validatebox"
                           style="margin-left: 76px; <?= (\Yii::$app->controller->action->id == "update") ? 'background-color:#ffffff' : "" ?>"
                           data-options="required:'true'"
                           placeholder="请选择" value="<?= $catNameStr ?>" readonly>
                    <input name="CrmMchpdtype[category_id]" class="multi-select-hidden" type="hidden"
                           value="<?= $catStr ?>">
                    <ul style="left: 76px;width: 228px">
                        <?php foreach ($categorys as $cat_id => $catg_name) { ?>
                            <li><label><input <?= in_array($cat_id, $catArr) ? "checked" : "" ?> value="<?= $cat_id ?>"
                                                                                                 class="multi-select-checkbox"
                                                                                                 type="checkbox"/>
                                    <span><?= $catg_name ?></span></label></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align vertical-top ml-20">备注</label><label class="vertical-top">：</label>
            <textarea class="value-width qvalue-align  easyui-validatebox" data-options="validType:'maxLength[200]'"
                      maxlength="200" placeholder="最多输入200字"
                      name="CrmMchpdtype[mpdt_remark]" rows="3"><?= $model['mpdt_remark'] ?></textarea>
        </div>
        <div>
            <label class="label-width qlabel-align  vertical-top mb-10 ml-20">状态</label><label
                class="vertical-top">：</label>
            <select class="value-width qvalue-align easyui-validatebox" data-options="required:'true'"
                    name="CrmMchpdtype[mpdt_status]">
                <?php foreach ($downList['MchpdStatus'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= $model['mpdt_status'] == $key ? "selected" : null; ?>><?= $val ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="text-center mt-20">
            <button type="submit" class="button-blue">保存</button>
            <button class="button-white close" type="button">取消</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <script>
        $(function () {
            ajaxSubmitForm($("#add-form"), function () {
                if ($('#staff_code').val() == "") {
                    $('.select2-selection').css('border-color', '#ffa8a8');
                }
                return true;
            });
            $('#staff_code').on('change', function () {
                if ($(this).val() != "") {
                    $('.select2-selection').css('border-color', '');
                }
            });
            //关闭弹窗
            $('#staff_code').css('display', 'block');
            $('#staff_code').css('top', '95px');
            $('#staff_code').validatebox({
                required: true,
                deltaX: 426
            });
            $(".close").click(function () {
                parent.$.fancybox.close();
            });

            $(".multi-select li").bind("click", function (event) {
                event.stopPropagation();
            });
            $(".multi-select-checkbox").bind("click", function () {
                var tmp = new Array();
                var tmp2 = new Array();
                $(this).parents(".multi-select").find(".multi-select-checkbox:checked").each(function () {
                    tmp.push($(this).val());
                    tmp2.push($(this).next().text());
                });
                $(this).parents(".multi-select").find(".multi-select-hidden").val(tmp.join(","));
                $(this).parents(".multi-select").find(".multi-select-title").val(tmp2.join(",")).validatebox();
            });

            $(".multi-select-title").click(function (event) {
                event.stopPropagation();
                $(this).parents(".multi-select").find("ul").toggle();
            });
            $("body").click(function () {
                $(".multi-select ul").hide();
            });
        })
    </script>
    <style>
        .multi-select {
            position: relative;
            width: 200px;
            height: 25px;
            margin-left: 100px;
            margin-top: -25px;
        }

        .multi-select-title {
            height: 25px;
            line-height: 25px;
            position: absolute;
            margin-left: 4px;
            display: block;
        }

        .multi-select ul {
            position: absolute;
            width: 180px;
            padding: 0px 10px;
            height: 250px;
            top: 28px;
            border: #ccc 1px solid;
            overflow-x: hidden;
            overflow-y: auto;
            display: none;
            background: #f0f1f4;
        }

        .multi-select label {
            line-height: 25px;
        }

        .multi-select label:after, .multi-select label:before {
            content: ""
        }

        .multi-select label span {
            vertical-align: top;
        }
    </style>