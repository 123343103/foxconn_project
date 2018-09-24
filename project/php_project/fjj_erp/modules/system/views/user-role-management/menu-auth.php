<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/21
 * Time: 下午 01:55
 */

use app\assets\MultiSelectAsset;
use app\assets\TreeAsset;
use yii\helpers\Url;

MultiSelectAsset::register($this);
TreeAsset::register($this);
$this->params['homeLike'] = ['label' => '系统平台设置', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '菜单权限设置'];
$this->title = '操作设置设置';
?>
<style>
    .width-100 {
        width: 100px;
    }

    .width-200 {
        width: 200px;
    }

    .tree {
        margin-left: 50px;
        margin-top: 20px;
    }

    #cover {
        display: none;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.44);
    }

    #coverShow {
        display: none;
        position: fixed;
        z-index: 2;
        top: 50%;
        left: 50%;
        border: 1px solid #fff;
        margin-top: -140px;
        background: #fff;
    }
</style>

<div class="content">
    <?php echo $this->render('tree', ['role_pkid' => $role_pkid]); ?>
    <div class="mb-20 text-center">
        <button class="button-blue-big" type="button" id="submit" onclick="submit()">确定</button>
        <button class="button-white-big ml-20" type="button" id="back" onclick="history.go(-1)" )>返回</button>
    </div>
    <div id="cover"></div>
    <div id="coverShow">
        <table align="center" border="0" cellspacing="0" cellpadding="0"
               style="border-collapse: collapse; height: 30px; min-height: 30px;">
            <tr>
                <td height="30" style="font-size: 12px;">数据加载中，请稍后......</td>
            </tr>

        </table>
    </div>
</div>
<script>
    function submit() {
        var role_pkid = $("#role_pkid").val();
        var dt_pkid = [];
        $(".tree-checkbox1").each(function () {
            var s = $(this).next();
            var a = s[0].childNodes[1].className;

            if ( a.indexOf("dt_pkid") >= 0) {
                dt_pkid.push(s[0].childNodes[1].innerText);
            }
        })
        if (dt_pkid.length > 0) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?=Url::to(["save"])?>',
                async: false,
                data: {role_pkid: role_pkid, dt_pkid: dt_pkid.join()},
                success: function (data) {
                    if (data == 1) {
                        layer.alert("保存成功", {icon: 2, time: 3000});
                        window.location.href = "<?=Url::to(['user-index'])?>";
                    } else {
                        layer.alert("保存出现错误,保存失败!", {icon: 2})
                    }
                }, error: function () {
                }
            });
        } else {
            layer.alert("您未选中任何页面下的按钮!");
        }
    }
</script>

