<?php
/**
 * User: F1677929
 * Date: 2016/11/11
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php ActiveForm::begin([
    'id' => 'evaluate_form',
    'options' => ['enctype' => 'multipart/form-data'],
]); ?>
<h2 class="head-second">厂商基本信息</h2>
<div class="mb-20">
    <label class="width-110"><span class="red">*</span>厂商全称</label>
    <input type="hidden" id="firm_id" name="PdFirmEvaluate[firm_id]" value="<?= !empty($firmInfo) ? $firmInfo['firm_id'] : null ?>">
    <input class="width-200" id="firm_sname" readonly="readonly" placeholder="请点击选择厂商" value="<?= !empty($firmInfo) ? $firmInfo['firm_sname'] : null ?>">
    <span style="width: 70px;"><a id="select_firm">选择厂商</a></span>
    <label>简称</label>
    <input class="width-200" id="firm_shortname" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_shortname'] : null ?>">
    <label class="width-110">来源</label>
    <input class="width-200" id="firm_source" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['bsPubdata']['firmSource'] : null ?>">
</div>
<div class="mb-20">
    <label class="width-110">类型</label>
    <input class="width-200" id="firm_type" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['bsPubdata']['firmType'] : null ?>">
    <label class="width-110">地位</label>
    <input class="width-200" id="firm_position" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['bsPubdata']['firmPosition'] : null ?>">
    <label class="width-110">是否为集团供应商</label>
    <input class="width-200" id="firm_issupplier" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['issupplier'] : null ?>">
</div>
<div class="mb-20">
    <label class="width-110">地址</label>
    <input type="text" id="firm_compaddress" style="width: 834px;" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firmAddress']['fullAddress'] : null ?>">
</div>
<div class="mb-20">
    <label class="width-110">厂商负责人</label>
    <input class="width-200" id="firm_compprincipal" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_compprincipal'] : null ?>">
    <label class="width-110">联系电话</label>
    <input class="width-200" id="firm_comptel" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_comptel'] : null ?>">
    <label class="width-110">邮箱</label>
    <input class="width-200" id="firm_compmail" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_compmail'] : null ?>">
</div>
<div class="mb-30">
    <label class="width-110">厂商联络人</label>
    <input class="width-200" id="firm_contaperson" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_contaperson'] : null ?>">
    <label class="width-110">联系电话</label>
    <input class="width-200" id="firm_contaperson_tel" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_contaperson_tel'] : null ?>">
    <label class="width-110">邮箱</label>
    <input class="width-200" id="firm_contaperson_mail" readonly="readonly" value="<?= !empty($firmInfo) ? $firmInfo['firm_contaperson_mail'] : null ?>">
</div>
<h2 class="head-second">评鉴信息</h2>
<div class="mb-20">
    <label class="width-110">评鉴日期</label>
    <input type="text" name="PdFirmEvaluateChild[evaluate_date]" class="width-200 select-date" readonly="readonly" placeholder="请点击选择日期">
</div>
<div class="mb-30">
    <label class="width-110 vertical-top">评鉴理由</label>
    <textarea name="PdFirmEvaluateChild[evaluate_reason]" style="width: 834px; height: 50px;"></textarea>
</div>
<h2 class="head-second">附件导入</h2>
<div class="mb-20">
    <label class="width-100">通路与服务能力</label>

</div>
<h2 class="head-second">评鉴结果</h2>
<table class="table" style="width: 95%; margin: 0 auto 20px;">
    <thead>
        <tr>
            <td>通路与服务能力(30%)</td>
            <td>价格及交货能力(30%)</td>
            <td>经营与财务能力(20%)</td>
            <td>管理与创新能力(20%)</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <label class="no-after">评鉴得分</label>
                <input type="text" name="PdFirmEvaluateChild[passage_server_score]" class="width-120">
            </td>
            <td>
                <label class="no-after">评鉴得分</label>
                <input type="text" name="PdFirmEvaluateChild[price_delivery_score]" class="width-120">
            </td>
            <td>
                <label class="no-after">评鉴得分</label>
                <input type="text" name="PdFirmEvaluateChild[operate_finance_score]" class="width-120">
            </td>
            <td>
                <label class="no-after">评鉴得分</label>
                <input type="text" name="PdFirmEvaluateChild[manage_innovate_score]" class="width-120">
            </td>
        </tr>
        <tr>
            <td>
                <label class="no-after">结果判定</label>
                <input type="text" name="PdFirmEvaluateChild[passage_server_decide]" class="width-120">
            </td>
            <td>
                <label class="no-after">结果判定</label>
                <input type="text" name="PdFirmEvaluateChild[price_delivery_decide]" class="width-120">
            </td>
            <td>
                <label class="no-after">结果判定</label>
                <input type="text" name="PdFirmEvaluateChild[operate_finance_decide]" class="width-120">
            </td>
            <td>
                <label class="no-after">结果判定</label>
                <input type="text" name="PdFirmEvaluateChild[manage_innovate_decide]" class="width-120">
            </td>
        </tr>
    </tbody>
</table>
<div class="mb-20">
    <label class="width-110">综合得分</label>
    <input type="text" name="PdFirmEvaluateChild[evaluate_synthesis_score]" class="width-200">
    <label class="width-110">综合等级</label>
    <select name="PdFirmEvaluateChild[evaluate_level]" class="width-200">
        <option value="">请选择</option>
        <?php foreach ($synthesisLevel as $key => $val) { ?>
            <option value="<?= $key ?>"><?= $val ?></option>
        <?php } ?>
    </select>
    <label class="width-110"><span class="red">*</span>评鉴结果</label>
    <select name="PdFirmEvaluateResult[evaluate_result]" class="width-200" id="evaluate_result">
        <option value="">请选择</option>
        <?php foreach ($firmEvaluateResultList as $key => $val) { ?>
            <option value="<?= $key ?>"><?= $val ?></option>
        <?php } ?>
    </select>
</div>
<div class="mb-30">
    <label class="width-110">评鉴人</label>
    <input type="text" class="width-200" readonly="readonly" value="<?= $evaluatePersonInfo['staff_name'] ?>">
    <label class="width-110">部门</label>
    <input type="text" class="width-200" readonly="readonly" value="<?= $evaluatePersonInfo['organization'] ?>">
</div>
<div class="text-center">
    <?= Html::submitButton('确定', ['class' => 'button-blue-big']) ?>
    <?= Html::button('取消', ['class' => 'button-white-big ml-20', 'onclick' => 'window.location.href="' . Url::to(['index']) . '"']) ?>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        //ajax提交表单
        ajaxSubmitForm($("#evaluate_form"));

        //选择厂商
        $("#select_firm").fancybox({
            padding     : [],
            width		: 800,
            height		: 530,
            autoSize	: false,
            type        : 'iframe',
            href        : '<?= Url::to(['select-firm']) ?>'
        });

        //验证
        $("#firm_sname,#evaluate_result").validatebox({
            required: true
        });
    })
</script>