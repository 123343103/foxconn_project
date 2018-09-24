<?php

use app\assets\MultiSelectAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


MultiSelectAsset::register($this);
\app\assets\JeDateAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<? //= dumpE($model) ?>
<? //= dumpE($staff) ?>
<style>
    .width-120 {
        width: 120px;
    }

    .width-130 {
        width: 130px;
    }

    .width-200 {
        width: 200px;
    }

    .width-250 {
        width: 250px;
    }

    .width-523 {
        width: 523px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .ml-25 {
        margin-left: 25px;
    }

    .ml-60 {
        margin-left: 60px;
    }

    .ml-70 {
        margin-left: 70px;
    }

    .ml-80 {
        margin-left: 80px;
    }

    .ml-90 {
        margin-left: 85px;
    }

    .ml-320 {
        margin-left: 320px;
    }

    label:after {
        content: "：";
    }
</style>
<h2 class="head-second">
    基本信息
</h2>
<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
//        'validateOnBlur'=>false,//关闭失去焦点验证
        'enableAjaxValidation' => $model->isNewRecord ? true : false, //开启Ajax验证
        'validationUrl' => Url::to(['/system/user/ajax-validation']),
//        'enableClientValidation'=>false, //关闭客户端验证
        'fieldConfig' => [
            'template' => "<div class='width-120 display-style'>{label}</div><div class='display-style'>\n{input}\n{error}\n</div>",
            'options' => ['class' => 'div-row', false],
            'labelOptions' => ['class' => 'width-120'],
            'inputOptions' => ['class' => 'width-250'],
            'errorOptions' => ['class' => 'error-notice'],
        ],
    ]); ?>

    <div class="mb-20">
        <?= $form->field($model, 'user_account', ['inputOptions' => ['readonly' => $model->isNewRecord ? false : 'readonly']])->textInput(['maxlength' => true]) ?>
    </div>
    <div class="mb-20">
        <?= $form->field($model, 'username_describe', ['inputOptions' => ['class' => 'width-523']])->textInput(['maxlength' => true]) ?>
    </div>
    <div class="mb-20">
        <label style="margin-left: 55px;"><span class="red">*<span>启用日期</label>
        <input id="start" name="User[start_at]" value="" type="text" class="Wdate easyui-validatebox" data-options="required:'true'"
               style="width:200px;" readonly="readonly">
        <label style="margin-left: 50px;"><span class="red">*<span>结束日期</label>
        <input id="end" name="User[end_at]" value="" type="text" class="Wdate easyui-validatebox" data-options="required:'true'"
               style="width:200px;" readonly="readonly">
    </div>
    <div class="mb-20">
        <label class="label-width qlabel-align  width-120"><span class="red">*</span>公司</label>
        <select name="User[company_id]" class="width-200 easyui-validatebox" data-options="required:'true'"
                style="margin-left: -3px">
            <option value="">---请选择---</option>
            <?php foreach ($company as $key => $val) { ?>
                <option
                    value="<?= $val['company_id'] ?>" <?= $model->company_id == $val['company_id'] ? "selected" : null ?>><?= $val['company_name'] ?></option>
            <?php } ?>
        </select>
        <?= $form->field($model, 'user_status')->radioList(['20' => '是', '10' => '否'], ['itemOptions' => ['labelOptions' => ['class' => 'no-after']]])->label('是否封存') ?>
        <span class="<?= (Yii::$app->user->identity->is_supper == 1) ? '' : 'hiden' ?>">
        <label style="margin-left: 165px" for="User[staff_id]" class="label-width qlabel-align">是否超级管理员</label>
        <input type="checkbox" name="User[is_supper]" value="1" <?= $model->is_supper ? "checked=\"checked\"" : '' ?>
               style="height: auto">
    </span>
        <div class="ml-25 mb-10">

        </div>
        <div class="mb-20">
            <div style="display: flex">
                <div>
                    <label class="label-width qlabel-align  width-120"><span class="red">*</span>工号</label>
                    <input type="text" value="<?= $staff['staff_code'] ? Html::encode($staff['staff_code']) : null ?>"
                           style="margin-left: -3px"
                           class="width-200 easyui-validatebox staff_code_null"
                           data-options="required:'true',validType:'staffCode',delay:1000000"
                           data-url="<?= Url::to(['/hr/staff/get-staff-info']) ?>"/>
                    <input type="hidden" name="User[staff_id]" id="User[staff_id]"
                           value="<?= $staff['staff_id'] ? Html::encode($staff['staff_id']) : 0 ?>" class="width-130 staff_id"/>
                    <label class="label-width qlabel-align ml-90">姓名</label>
                    <input type="text" class="width-200 staff_name" style="margin-left: -3px"
                           value="<?= $staff['staff_name'] ? Html::encode($staff['staff_name']) : null ?>" disabled/>
                    <label class="label-width qlabel-align ml-90">部门</label>
                    <input type="text" value="<?= $staff['organization'] ? Html::encode($staff['organization']) : null ?>"
                           class="width-130 staff_organization" disabled/>
                </div>
            </div>
        </div>
        <div style="margin-bottom: 10px;">
            <span style="margin-left: 200px;">用户组信息<span>
            <span style="margin-left: 260px;">已选择用户组<span>
        </div>
        <div class="mb-20 mtl-select" style="display: <?= $model->is_supper ? 'none' : 'block' ?>">
            <select multiple="multiple" id="my-select" name="roles[]">
                <?php foreach ($roles as $k => $v) { ?>
                    <option value="<?= $v->name ?>"><?= Html::encode($v->title) ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="ml-320">
            <?= Html::submitButton($model->isNewRecord ? '确认' : '更新', ['class' => 'button-blue-big sbtn']) ?>&nbsp;
            <?= Html::button('返回', ['class' => 'button-white-big ml-20', 'onclick' => 'history.go(-1)']) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <script>
            $(function ($) {
                $("#start").click(function () {
                    WdatePicker({
                        skin: "whyGreen",
                        maxDate: "#F{$dp.$D('end',{d:-1})}"
                    });
                });
                $("#end").click(function () {
                    WdatePicker({
                        skin: "whyGreen",
                        minDate: "#F{$dp.$D('start',{d:1})}"
                    });
                });
                ajaxSubmitForm($("#add-form"));
                $(".sbtn").click(function () {
                    setTimeout("$('.sbtn').attr('disabled',false)", 100);
                })
                $("#my-select").multiSelect({
                    'selectableOptgroup': true
                });
                <?php if(isset($permission)){ ?>
                <?php foreach ( $permission as $key => $val ){ ?>
                $("#my-select").multiSelect("select", "<?= $val->name ?>");
                <?php } ?>
                <?php } ?>
                $('#select-com').fancybox(
                    {
                        padding: [],
                        fitToView: false,
//                width		: 200,
//                height		: 650,
                        autoSize: false,
                        closeClick: false,
                        openEffect: 'none',
                        closeEffect: 'none'
                    }
                );
                //获取下拉框选中值
//            $('#user-staff_id').change(function (e) {
//                var staffId = $('#user-staff_id option:selected').val();
//                if (staffId == '') {
//                    $("#staff_code").val('');
//                    $("#staff_org").val('');
//                    return false;
//                }
//                $.ajax({
//                    url: "<?//= Url::to(['/hr/staff/get-info'])?>//?id=" + staffId,
//                    type: 'get',
//                    success: function (data) {
//                        var obj = eval('(' + data + ')');
//                        $("#staff_code").val(obj.staff_code)
//                        $("#staff_org").val(obj.organization_name)
//                    }
//                });
//            })
                $('input[type="checkbox"]').on('click', function () {
                    if ($('input[type="checkbox"]').get(0).checked) {
                        $('.mtl-select').hide();
                    } else {
                        $('.mtl-select').show();
                    }
                })
            })

        </script>


