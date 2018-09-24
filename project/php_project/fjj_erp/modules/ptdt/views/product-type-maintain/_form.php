<?php
/**
 * User: F1676624
 * Date: 2016/10/29
 */
?>


<h2 class="head-second">
    <?= $title ?>
</h2>
<div class="mb-50"></div>
<form id="addd-form" method="post" type="hidden">
    <div class="mb-30">
        <label class="width-200 vertical-top "><span class="red">*</span>ID</label>
        <input class="width-200" id="type_id" name="type_id" readonly="readonly" value=<?= $model['type_id'] ?>>
        <label class="width-200 vertical-top"><span class="red">*</span>级别</label>
        <select class="width-200 " id="type_level" disabled="disabled">
            <option value=<?= $model['type_level'] ?>><?= $level_all[$model['type_level']] ?></option>
        </select>
    </div>
    <div class="mb-30">
        <label class="width-200 vertical-top"><span class="red">*</span>类别名称</label>
        <input class="easyui-validatebox width-200" data-options="required:'true'" id="type_name" name="type_name"
               value=<?= $model['type_name'] ?>>
        <label class="width-200 vertical-top "><span class="red">*</span>类别编号</label>
        <input class="easyui-validatebox width-200" data-options="required:'true'" id="type_no" name="type_no"
               value=<?= $model['type_no'] ?>>
    </div>

    <div class="mb-30">
        <label class="width-200 vertical-top"><span class="red">*</span>标题</label>
        <input class="width-200" id="type_title" name="type_title" value=<?= $model['type_title'] ?>>
        <label class="width-200 vertical-top"><span class="red">*</span>关键词</label>
        <input class="width-200" id="type_keyword" name="type_keyword" value=<?= $model['type_keyword'] ?>>
    </div>

    <div class="mb-40">
        <label class="width-200 vertical-top"><span class="red">*</span>图片地址</label>
        <input class="width-200" id="type_picture" name="type_picture" value=<?= $model['type_keyword'] ?>>
        <label class="width-200 vertical-top"><span class="red">*</span>设备专区</label>
        <select class="easyui-validatebox width-200" data-options="required:'true'" id="is_special" name="is_special">
            <option value="">请选择</option>
            <?php foreach ($is_special as $key => $val) { ?>
                <?php if (isset($model['is_special'])) { ?>
                    <option
                        value="<?= $key ?>" <?= $model['is_special'] == $key ? "selected" : null ?>><?= $val ?> </option>
                <?php } else { ?>
                    <option
                        value="<?= $key ?>"><?= $val ?> </option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>

    <div class="mb-30">
        <label class="width-150 vertical-top">描述</label>
        <textarea class="width-750" rows="3" id="type_description"
                  name="type_description"><?= $model['type_description'] ?></textarea>

        <input name="type_index" hidden="hidden" value=<?= $model['type_index'] ?>>
        <input name="type_level" hidden="hidden" value=<?= $model['type_level'] ?>>
    </div>

    <div class="text-center pt-20 pb-20">
        <button class="button-blue-big" type="submit">提交</button>
        <button class="button-white-big ml-20" type="button" onclick="history.go(-1)">返回</button>
    </div>

</form>
<script>
    $(document).ready(function () {
        ajaxMySubmit($("#addd-form"));     //ajax 提交
    });
    function ajaxMySubmit($form) {
        $("body").on("submit", $form, function () {

            if (!$(this).form('validate')) {
                return false;
            }
            var id = $($form).attr('id');
            $("button[type='submit']").prop("disabled", true);
            var options = {
                dataType: 'json',
                method: 'POST',
                data: $("#addd-form").serializeArray(),
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                if (data.url != undefined) {
                                    location.href = data.url;
                                }

                            }
                        });
                    }
                    if (data.flag == 0) {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                        $("button[type='submit']").prop("disabled", false);
                    }
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };

            $("#" + id).ajaxSubmit(options);
            return false;
        })
    }
</script>