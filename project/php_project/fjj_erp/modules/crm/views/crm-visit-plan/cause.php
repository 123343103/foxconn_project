<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/9/1
 * Time: 上午 11:06
 */
use app\assets\JqueryUIAsset;
use yii\helpers\Url;

JqueryUIAsset::register($this);
?>
<h1 class="head-first">系统提示</h1>
<div style="margin-left: 15px;">
    <?php if ($type == 1 ) { ?>
        请输入取消计划的原因:
    <?php } ?>
    <?php if ($type == 2 ) { ?>
        请输入终止计划的原因:
    <?php } ?>
</div>
<div style="margin:2px 15px 20px;">
    <textarea style='width:100%;height:100px;' id="cause"></textarea>
</div>
<div style='text-align: center;'>
    <button type="button" class='button-blue' id="submit">确定</button>
    <button type="button" class='button-white' id="close" style="margin-left: 10px;">取消</button>
</div>
<script>
    $(function () {
        $("#close").on("click", function () {
            parent.$.fancybox.close();
        });

        $("#submit").on("click", function () {
            if ($("#cause").val() === '') {
                parent.layer.alert("原因必须填写，可填无！", {icon: 2});
                return false;
            }
            $.ajax({
                type: "POST",
                data: {id: "<?=$svp_id?>", cause: $("#cause").val()},
                dataType: "json",
                url: "<?php
                    if($type == 1){
                        echo Url::to(['cancel']);
                    }
                    if($type == 2){
                        echo Url::to(['stop']);
                    }
                    ?>",
                success: function (data) {
                    if (data.flag == 1) {
                        parent.layer.alert(data.msg, {icon: 1,end: function () {
                            parent.$("#update").hide().next().hide();
                            parent.$("#cancel").hide().next().hide();
                            parent.$("#stop").hide().next().hide();
                            parent.$("#add-visit-record").hide().next().hide();
                            if(parent.$("#data").length == 1){
                                parent.$("#data").datagrid("reload");
                            }else{
                                parent.location.reload();
                            }
                            parent.$.fancybox.close();
                        }});
                    } else {
                        parent.layer.alert(data.msg, {icon: 2});
                    }
                },
                error: function (xhr, type) {
                    alert("出现异常!");
                }
            })
        })
    })
</script>