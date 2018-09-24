<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/22
 * Time: 下午 05:27
 */
use yii\widgets\ActiveForm;

?>
<style>
    .label-width {

        width: 120px;;
    }

    .value-width {
        width: 300px;
    }

    .mb-20 {

        margin-bottom: 20px;;
    }
</style>
<div>
    <?php if ($type == 1) { ?>
        <h2 class="head-first">修改菜单</h2>
    <?php } else if ($type == 0) { ?>
        <h2 class="head-first">新增菜单</h2>
    <?php } ?>
</div>
<?php $form = ActiveForm::begin(['action' => ['update-add'], 'method' => 'post', 'id' => 'commit-form']); ?>
<div class="content">
    <input type="hidden" name="Menu[type]" value="<?= $type ?>" id="menuType">
    <input type="hidden" value="<?= $i ?>" id="updatype" name="Menu[updatype]">
    <?php if (isset($menuData['menu_pkid'])) { ?>
        <input type="hidden" name="Menu[menu_pkid]" value="<?= $menuData['menu_pkid'] ?>">
    <?php } ?>
    <div class="mb-20">
        <label class="label-width lable-align"><span class="red">*</span>菜单名称：</label>
        <input class="value-width value-align easyui-validatebox" data-options="required:'true'"
               name="Menu[menu_name]" maxlength="50"
               value="<?= $menuData['menu_name'] ?>">
    </div>
    <div class="mb-20">
        <label class="label-width label-align">菜单层级：</label>
        <input class="value-width value-align" readonly="readonly" id="menuLevel" name="Menu[menu_level]"
               value="<?= $menuData['menu_level'] ?>">
    </div>
    <div class="mb-20">
        <label class="label-width label-align">上级菜单：</label>
        <select class="value-width value-align" id="selectMenu" name="Menu[p_menu_pkid]">
            <option value="-1" <?php if ($menuData['menu_level'] == 1) echo 'selected'; ?>>---</option>
            <?php foreach ($data as $value) { ?>
                <option
                    value="<?= $value['menu_pkid'] ?>"
                    data="<?= $value['menu_level'] ?>" <?php if ($menuData['p_menu_pkid'] == $value['menu_pkid']) echo 'selected'; ?>><?= $value['menu_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-20">
        <label class="label-width label-align">URL：</label>
        <input class="value-width value-align easyui-validatebox" data-options="validType:'taxCodeOld'"
               value="<?= $menuData['menu_url'] ?>" name="Menu[menu_url]" maxlength="50">
    </div>
    <div class="mb-20">
        <label class="label-width label-align">有效否：</label>
        <select class="value-width value-align" name="Menu[yn]">
            <option value="1" <?php if (isset($menuData['yn'])) {
                if ($menuData['yn'] == 1) echo 'selected' ?><?php } ?>>是
            </option>
            <option value="0" <?php if (isset($menuData['yn'])) {
                if ($menuData['yn'] == 0) echo 'selected' ?><?php } ?>>否
            </option>
        </select>
    </div>
    <div style="text-align: center">
        <button class="button-blue-big" type="submit" id="save">保存</button>
        <button class="button-white-big" id="cannel">取消</button>
    </div>
</div>
<?php ActiveForm::end() ?>
<script>
    $("#selectMenu").on('change', function () {
        var selValue = $(this).val();
        if (selValue == -1) {
            $("#menuLevel").val('1');
        }
        else {
            var dataValue = $(this).find("option:selected").attr("data");
            dataValue++;
            $("#menuLevel").val(dataValue);
        }
    })
    $(function () {
        var type = $("#menuType").val();
        if (type == 1) {
            var updatype=$("#updatype").val();
            if (updatype==0)
            {
                $("#selectMenu").attr("disabled", "disabled");
            }
        }
        else {
            $("#menuLevel").val('1');
        }
        $("#cannel").on('click', function () {
            parent.$.fancybox.close();
        })
        ajaxSubmitForm($("#commit-form"));
        $.extend($.fn.validatebox.defaults.rules, {
            taxCodeOld: {
                validator: function (value, param) {
                    return /^[^\u4e00-\u9fa5]{0,}$/.test(value);
                },
                message: 'URL不能包含中文'
            }
        })
    })
</script>
