<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/3/7
 * Time: 17:27
 */
use \yii\bootstrap\ActiveForm;

$this->title = "消息提醒"
?>
<style>
    table input {
        outline: none;
        border: none;
    }

    .head-second + div {
        display: none;
    }

    .ml-25 {
        margin-left: 25px;
    }
</style>
<h2 class="head-first"><?= $this->title ?></h2>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>

<table width="90%" class="no-border vertical-center ml-25 mb-10">
    <tr class="no-border">
        <td width="20%" class="no-border vertical-center">公司名称<label>：</label></td>
        <td width="80%" class="no-border vertical-center"><?= $model['cust_sname']; ?></td>
    </tr>
</table>

<div class="space-20"></div>

<table width="90%" class="no-border vertical-center ml-25 mb-10">
    <tr class="no-border">
        <td width="20%" class="no-border vertical-center">公司简称<label>：</label></td>
        <td width="80%" class="no-border vertical-center"><?= $model['cust_shortname']; ?></td>
    </tr>
</table>

<div class="space-20"></div>

<table width="90%" class="no-border vertical-center ml-25 mb-10">
    <tr class="no-border">
        <td width="20%" class="no-border vertical-center">公司电话<label>：</label></td>
        <td width="80%" class="no-border vertical-center"><?= $model['cust_tel1']; ?></td>
    </tr>
</table>

<div class="space-20"></div>

<table width="90%" class="no-border vertical-center ml-25 mb-10">
    <tr class="no-border">
        <td width="20%" class="no-border vertical-center">联系人<label>：</label></td>
        <td width="30%" class="no-border vertical-center"><?= $model['cust_contacts']; ?></td>
        <td width="20%" class="no-border vertical-center">手机号码<label>：</label></td>
        <td width="30%" class="no-border vertical-center"><?= $model['cust_tel2']; ?></td>
        <!--        <td width="30%" class="no-border vertical-center">--><? //=$reminder['code'];?><!------>
        <? //=$reminder['receiver'];?><!--</td>-->
    </tr>
    <!--    <tr class="no-border">-->
    <!--        <td width="20%" class="no-border vertical-center">手机号码：</td>-->
    <!--        <td width="30%" class="no-border vertical-center">--><? //=$model['cust_tel2'];?><!--</td>-->
    <!--    </tr>-->
</table>

<div class="space-20"></div>

<table width="90%" class="no-border vertical-center ml-25 mb-10">
    <tr class="no-border">
        <td width="20%" class="no-border vertical-center">开始时间：</td>
        <td width="30%"
            class="no-border vertical-center"><?= date("Y-m-d", strtotime($reminder['imesg_btime'])); ?></td>
        <td width="20%" class="no-border vertical-center">结束时间：</td>
        <td width="30%"
            class="no-border vertical-center"><?= date("Y-m-d", strtotime($reminder['imesg_etime'])); ?></td>
    </tr>
</table>

<div class="space-20"></div>

<table width="90%" class="no-border vertical-center ml-25 mb-10">
    <tr class="no-border">
        <td width="20%" class="no-border vertical-center">提醒内容：</td>
        <td width="80%" class="no-border vertical-center"><?= $reminder['imesg_notes'] ?></td>
    </tr>
</table>

<div class="space-20 mb-10"></div>

<div class="space-20 mb-10"></div>

<div class="text-center mt-10 mb-10">
    <button class="button-white-big ml-20" <?= $reminder['imesg_status'] == 2 ? "disabled" : "" ?> type="button"
            id="sub">关闭
    </button>
    <button class="button-white-big ml-20" onclick="close_select()">退出</button>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function () {
        $("#sub").on('click', function () {
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: $("#add-form").serialize(),
                url: "<?= \yii\helpers\Url::to(['update']) ?>?mid=" +<?= $reminder['imesg_id'] ?>,
                success: function (msg) {
//                    console.log(msg);
                    if (msg == 1) {
//                        parent.$.fancybox.close();
                        parent.window.location.reload();
                        $("#sub").prop("disabled", true);
                        $("#imesg_status").text("结束");
                    }
                },
                error: function (msg) {
//                    console.log(msg)
                }
            })
        });
    })
</script>
