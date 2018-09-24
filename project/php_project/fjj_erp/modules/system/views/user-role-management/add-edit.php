<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/20
 * Time: 下午 04:28
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<style>
    .width-100 {
        width: 100px;
        margin-bottom: 10px;
        text-align: right;
    }

    .width-250 {
        width: 250px;
    }

    * {
        font-size: 14px;
    }

</style>
<h1 class="head-first"><?= empty($model) ? "新增用户角色" : "修改用户角色" ?></h1>

<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="content"><label class="width-100"><span class="red">*</span>角色编码:</label>
    <?php if (empty($model)) { ?>
        <input type="text" class="width-250 easyui-validatebox" data-options="required:'true',validType:'CheckID'"
               name="BsRole[role_no]" value="<?= $model['role_no'] ?>"><br>
    <?php } else { ?>
        <label><?= $model['role_no'] ?></label><br>
    <?php } ?>
    <label class="width-100"><span class="red">*</span>角色名称:</label>
    <input type="text" class="width-250 easyui-validatebox" data-options="required:'true',validType:'CodeId'"
           name="BsRole[role_name]" value="<?= $model['role_name'] ?>"><br>
    <label class="width-100"
           style="line-height:21px; float:left; ">角色描述:</label>
    <textarea name="BsRole[role_des]" id="" placeholder="最多输入200个字"
              data-options="required:'true'" maxlength="200"
              style="width: 250px;height: 100px;margin-left: 4px;margin-bottom: 10px;"><?= $model['role_des'] ?></textarea><br>
    <label class="width-100" for="BsRole[role_state]">状态:</label>
    <select name="BsRole[role_state]" class="width-250" id="role_state">
        <option value="0" <?= $model['role_state'] ?>
                name="role_state" <?= ($model['role_state'] == 0 && !empty($model)) ? 'selected="selected"' : '' ?>>
            禁用
        </option>
        <option value="1"
                name="role_state" <?= (($model['role_state'] == 1 && !empty($model)) || empty($model)) ? 'selected="selected"' : '' ?>>
            启用
        </option>
    </select>
    <div class="text-center" style="margin-top:10px;">
        <?= Html::submitButton($type == 'add' ? '新增' : '修改', ['class' => 'button-blue-big sbtn']) ?>&nbsp;
        <?= Html::button('返回', ['class' => 'button-white-big ml-20', 'id' => 'close']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function ($) {
        ajaxSubmitForm($("#add-form"));
        $.extend($.fn.validatebox.defaults.rules, {
            CheckID: {
                validator: function (value, param) {
                    return /^[^\u4e00-\u9fa5]{0,}$/.test(value);
                },
                message: '不能输入中文！'
            }
        });
    })
    $('#close').click(function () {
        parent.$.fancybox.close();
    });


</script>
