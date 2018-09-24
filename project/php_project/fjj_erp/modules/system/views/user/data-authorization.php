<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/9
 * Time: 下午 02:15
 */
use app\assets\MultiSelectAsset;
use app\assets\TreeAsset;
use yii\helpers\Url;
use yii\helpers\Html;

MultiSelectAsset::register($this);
TreeAsset::register($this);
$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '角色管理','url' => Url::to(['/system/authority/role-index'])];
$this->params['breadcrumbs'][] = ['label' => '部门权限设置'];
$this->title = '数据权限设置';
?>
<style>
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
</style>

<div class="content">

    <?php echo $this->render('tree', ['user_id' => $user_id,'tid'=>$tid]); ?>
    <div class="mb-20 text-center">
        <button class="button-blue-big" type="button" id="submit" onclick="submit()">保存</button>
        <button class="button-white-big ml-20" type="button" id="back" onclick="history.go(-1);">取消</button>
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
        var ass_id = $("#user_id").val();
        var tid = $("#tid").val();
        var rid = [];
        $(".tree-checkbox1").each(function () {
            var s = $(this).next();
            var a = s[0].childNodes[2].innerText;
            rid.push(s[0].childNodes[1].innerText);
        })
        if (rid.length > 0) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?=Url::to(["user/save"])?>',
                async: false,
                data: {ass_id: ass_id, tid: tid, rid: rid.join()},
                success: function (data) {
                    if (data == 1) {
                        layer.alert("保存成功", {icon: 2, time: 3000});
                        if (tid == 1) {
                            window.location.href = "<?=Url::to(['user/index'])?>";
                        }
                        if (tid == 0) {
                            window.location.href = "<?=Url::to(['authority/role-index'])?>";
                        }
                    } else {
                        layer.alert("保存出现错误,保存失败!", {icon: 2})
                    }
                }
            });
        } else {
            layer.alert("您未选中任何部门!");
        }
    }
</script>
