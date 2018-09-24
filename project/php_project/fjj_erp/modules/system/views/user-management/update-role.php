<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/9/11
 * Time: 下午 02:35
 */
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);
$this->title = '修改类别信息';
?>
<h1 class="head-first"> 用户角色设置</h1>
<div class="content">
    <?php foreach ($roles as $key=>$val){?>
        <div style="font-size:14px;">
            <input style="vertical-align: middle;margin-left: 30px;" type="checkbox"<?php foreach ($role as $keys=>$values){?>
                <?= $val['role_pkid']==$values['role_pkid']? "checked":null;} ?>
                   value="<?= $val['role_pkid']?>"><?= $val['role_name']?>
        </div>
    <?php }?>
    <input type="hidden" id="user_id" value="<?= $user_id ?>">
    <div class="mb-20 text-center" id="buttons">
        <button class="button-blue-big" type="button" id="submit" onclick="submit()">保存</button>
        <button class="button-white-big ml-20" type="button" id="back">取消</button>
    </div>
</div>
<script>
    $(function () {

    });
    function submit() {
        var user_id = $("#user_id").val();
        var Role_rid = [];
        //用户角色
        $("input[type='checkbox']").each(function () {
            if ($(this).is(':checked')) {
                Role_rid.push($(this).val());
            }
        });
        if (Role_rid.length > 0) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?=Url::to(["saver"])?>',
                async: false,
                data: {user_id: user_id, Role_rid: Role_rid.join()},
                success: function (data) {
                    if (data == 1) {
//                        layer.alert("保存成功", {icon: 2, time: 3000});
                        layer.alert("保存成功", {icon: 2}, function () {
                            parent.$.fancybox.close();
                        });
                    } else {
                        layer.alert("保存出现错误,保存失败!", {icon: 2})
                    }
                }
            });
        } else {
            layer.alert("您未选中任何部门!");
        }
    }
    $("#back").click(function () {
        parent.$.fancybox.close();
    });
</script>
