<?php
/**
 * 分配员工
 */
use \yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
use yii\helpers\Url;

JqueryUIAsset::register($this);
?>
<style>
    .label-width {
        width: 130px;
    }

    .value-width {
        width: 170px;
    }

    .marginT-40 {
        margin-top: 40px;
    }
</style>
<div class="no-padding">
    <div class="pop-head">
        <p>分配员工</p>
    </div>
    <div class="space-30"></div>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div class="mb-10 marginT-40">
        <input name="CrmCustPersoninch[cust_id]" id="cust_id" type="hidden" value="">
        <label class="label-width qlabel-align">需求类目</label><label>：</label>
        <select type="text" name="mainType" id="main" class="value-width qvalue-align easyui-validatebox"
                data-options="required:true,tipPosition:'top'" <?= (isset($model) || $class != 'null') ? 'disabled="disabled" style="background-color: #ebebe6;"' : '' ?>>
            <option value="">请选择...</option>
            <?php foreach ($type as $val) { ?>
                <option
                    value="<?= $val['catg_id'] ?>" <?= $class == $val['catg_id'] ? 'selected' : null ?>><?= $val['catg_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align"><span
                class="red">*</span>分配员工</label><label>：</label>
        <?php if (!empty($model)) { ?>
            <input value="<?= $model['staff_name'] ?>" disabled="disabled">
        <?php } else { ?>
            <select id="type" name="assignStaff" class="value-width qvalue-align easyui-validatebox"
                    data-options="required:true,tipPosition:'top'">
                <option value="">请选择</option>
                <?php if (!empty($staffs)) { ?>
                    <!--                        <option value="--><? //= $staff['staff_code'] ?><!--" selected="selected">--><? //= $staff['staff_name'] ?><!--</option>-->
                    <?php foreach ($staffs as $val) { ?>
                        <option value="<?= $val['staff_code'] ?>"><?= $val['staff_name'] ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        <?php } ?>

    </div>
    <div class="text-center marginT-40">
        <?php if ($model) { ?>
            <!--            <input type="hidden" name="CrmCustPersoninch['ccpich_status']" value="0">-->
            <button type="submit" class="button-white-big">取消认领</button>
        <?php } else { ?>
            <button type="submit" id="submit" class="button-blue-big sub">确定</button>
        <?php } ?>
        <button class="button-white-big close" type="button">关闭</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function () {
        $('.sub').click(function () {
            $('#submit').attr('disabled', false);
        })
        ajaxSubmitForm($("#add-form"), '', function (res) {
            parent.layer.alert(res.msg, {icon: 1});
            parent.$("#data").datagrid("reload");
            parent.$.fancybox.close();
        });
        //关闭弹窗
        $(".close").click(function () {
            parent.$.fancybox.close();
        });
        $("#cust_id").val(parent.$("#data").datagrid("getSelected")['cust_id'])
        $("#main").on('change', function () {
            var type = $("#main").val();
            var next = $("#type");
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"type": type},
                url: "<?=Url::to(['/crm/crm-investment-dvelopment/get-assign-staff']) ?>",
                success: function (data) {
                    console.log(data);
                    next.html('<option value>请选择</option>');
                    for (var x in data) {
                        next.append('<option value="' + data[x].staff_code + '" >' + data[x].staff_name + '</option>')
                    }
                }

            })
        })
    })
</script>
