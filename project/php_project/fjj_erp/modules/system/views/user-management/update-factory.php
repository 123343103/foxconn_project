<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/12/8
 * Time: 下午 03:41
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->params['homeLike'] = ['label' => '系统平臺设置', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => 'index'];
$this->params['breadcrumbs'][] = '厂区设置';
$this->title = '厂区设置';
?>
<div class="content">
    <h1 class="head-first">
        厂区设置
    </h1>
    <div class="mb-20" style="font-size: 14px;">
        <lable class="label-width" style="margin-left: 50px;">绑定账号：</lable><?= $staff['staff_code']?>
        <lable class="label-width" style="margin-left: 150px;">用户姓名：</lable><?= $staff['staff_name']?>
    </div>
    <div class="mb-20" style="margin-left: 50px;margin-top: 20px;">
        <label style="float:left;margin-left: 1px;" class="label-width qlabel-align">绑定厂区：</label><div id="selected-area"  style="width: 633px;overflow-y:scroll;height: 35px;border: 1px solid #ccc;"></div>
        <div  style="width: 694px;height: 200px;overflow-y:scroll;margin-top: 20px;border: 1px solid #ccc;">
            <?php foreach ($factory as $key => $val) { ?>
                <span style="margin-left: 30px;">
                    <input type="checkbox" name="factory"
                           style="vertical-align: middle;"
                        <?php foreach ($area as $keys => $values) { ?><?= $val['factory_id'] == $area[$keys]['area_pkid'] ? "checked" : null;}?>
                           data-name="<?= $val['factory_name'] ?>"
                           value="<?= $val['factory_id'] ?>">
                    <?= $val['factory_name'] ?>
                </span>
            <?php } ?>
        </div>
    </div>
    <div class="mb-20 text-center" id="buttons" style="margin-top: 30px;">
        <button style="margin-left: -200px;" class="button-blue-big" type="button" id="submit" onclick="submit()">保存</button>
        <button class="button-white-big ml-20" type="button" id="back" onclick="history.go(-1);">返回</button>
    </div>
    <input type="hidden" id="user_id" value="<?= $user_id ?>">
</div>
<script>
    $(function(){
        $("input[name='factory']").each(function(){
            var name=$(this).data('name');
            var id=$(this).val();
            var item=$("<div class='item'></div>").text(name).attr("data-id",id).css({width:"80px","height":"30px","line-height":"30px","float":"left"});
            var remove=$("<span class='remove'>&times;</span>").css({"cursor":"pointer","color":"red"});
            item.append(remove);
            if($(this).is(':checked'))
            {
                $("#selected-area").append(item);
            }
        });

        //选择厂区显示在上面的显示框中
        $("input[name='factory']").click(function(){
            var name=$(this).data('name');
            var id=$(this).val();
            var item=$("<div class='item'></div>").text(name).attr("data-id",id).css({width:"80px","height":"30px","line-height":"30px","float":"left"});
            var remove=$("<span class='remove'>&times;</span>").css({"cursor":"pointer","color":"red"});
            item.append(remove);
            if($(this).is(':checked'))
            {
                $("#selected-area").append(item);
            }
            else
            {
//                $("#selected-area").empty();
                $(".item").each(function() {
//                    alert($(this).data("id"));
                    if($(this).data("id")==id)
                    {
//                        alert($(this).data("id"));
                        $(this).remove();
                    }
                });
            }
        });
        //点击红色×
        $("#selected-area").on("click",".remove",function() {
            var id = $(this).parent(".item").data("id");
            $(this).parent(".item").remove();
            $("input[name='factory']").each(function () {
                if(id==$(this).val())
                {
                    $(this).attr("checked",false)
                }
            });
        });
    });
    function submit() {
        var user_id = $("#user_id").val();
        var factory_id = [];
        //厂区
        $("input[name='factory']").each(function () {
            if ($(this).is(':checked')) {
                factory_id.push($(this).val());
            }
        });
        if (factory_id.length > 0) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?=Url::to(["savef"])?>',
                async: false,
                data: {user_id: user_id, factory_id: factory_id.join()},
                success: function (data) {
                    if (data == 1) {
                        layer.alert("保存成功", {icon: 2});
                        window.location.href = "<?=Url::to(['index'])?>";
                    } else {
                        layer.alert("保存出现错误,保存失败!", {icon: 2})
                    }
                }
            });
        } else {
            layer.alert("您未选中任何厂区!");
        }
    }
</script>
