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
        <input class="width-200" id="category_id" name="category_id" readonly="readonly" value=<?= $model['category_id'] ?>>
        <label class="width-200 vertical-top"><span class="red">*</span>类别层级</label>
        <select class="width-200 " id="category_level" disabled="disabled">
            <option value=<?= $model['category_level'] ?>><?= $level_all[$model['category_level']] ?></option>
        </select>
    </div>
    <div class="mb-30">
        <label class="width-200 vertical-top"><span class="red">*</span>类别名称</label>
        <input class="easyui-validatebox width-200" data-options="required:'true'" id="category_name" name="category_name"
               value=<?= $model['category_name'] ?>>

        <label class="width-200 vertical-top "><span class="red">*</span>上级类别ID</label>
        <input class="width-200" id="category_id" name="p_category_id" readonly="readonly" value=<?= $model['p_category_id'] ?>>
        </div>
        <div class="mb-30">
        <label class="width-200 vertical-top "><span class="red">*</span>排序</label>
        <input class="easyui-validatebox width-200" data-options="required:'true'" id="orderby" name="orderby"
               value=<?= $model['orderby'] ?>>

        <label class="width-200 vertical-top"><span class="red">*</span>中心</label>
        <input class="width-200" id="center" name="center" value=<?= $model['center'] ?>>
    </div>

    <div class="mb-40">
        <label class="width-200 vertical-top"><span class="red">*</span>最小利率</label>
        <input class="width-200" id="min_margin" name="min_margin" value=<?= $model['min_margin'] ?>>
        <label class="width-200 vertical-top"><span class="red">*</span>是否有效</label>
        <select class="easyui-validatebox width-200" data-options="required:'true'" id="isvalid" name="isvalid">
            <?php foreach ($isvalid as $key => $val) { ?>
                <?php if (isset($model['isvalid'])) { ?>
                    <option
                        value="<?= $key ?>" <?= $model['isvalid'] == $key ? "selected" : null ?>><?= $val ?> </option>
                <?php } else { ?>
                    <option
                        value="<?= $key ?>"><?= $val ?> </option>
                <?php } ?>
            <?php } ?>
        </select>
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