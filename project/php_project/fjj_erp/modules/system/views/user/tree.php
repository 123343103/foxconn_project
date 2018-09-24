<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/27
 * Time: 上午 08:31
 */
use app\assets\MultiSelectAsset;
use app\assets\TreeAsset;
use yii\helpers\Url;
use yii\helpers\Html;


?>

<div class="content">

    <h1 class="head-first">
        部门权限设置
    </h1>

    <label class="width-100">部门:</label>
    <input type="text" class="width-200 easyui-validatebox" id="org_name">
    <?= Html::Button('查询', ['class' => 'button-blue  search-btn-blue ml-20', 'id' => 'select']) ?>
    <div class="mb-20"></div>
    <input type="text" style="display: none" id="user_id" value="<?= $user_id ?>">
    <input type="text" style="display: none" id="tid" value="<?= $tid ?>">
    <div style="margin-left: 40px;">
        <ul id="tt" class="easyui-tree"
            checkbox="true">
        </ul>
    </div>


</div>
<script>
    $(function () {
        coverit();
        gettree(0);
        $("#select").click(function () {
            var org_name = $("#org_name").val();

            //根据菜单名称查询菜单role_pkid
            if (org_name != "") {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"name": org_name},
                    'url': "<?= Url::to(['/hr/organization/get-id-by-name']); ?>",
                    'success': function (msg) {
                        if (msg == "0") {
                            alert("未能找到该菜单名称！");
                        } else {
                            gettree(msg);
                        }
                    }
                });
            } else {
                gettree();
            }
        });

    })

    function gettree(msg) {
        var user_id = $("#user_id").val();
        $("#tt").tree({
            url: '<?= Url::to(["tree"]) ?>?ass_id=' + user_id+'&org_id='+msg,
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#tt").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");


            },
            onSelect: function (node) {
                if (node.state == "closed")
                    $(this).tree('expand', node.target);
                else
                    $(this).tree('collapse', node.target);
            },onloaderror:function(arguments)
            {
                alert(arguments);
            }
        });
    }
    function coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        cover.style.display = 'block';
        covershow.style.display = 'block';
    }
    function hidden_coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        cover.style.display = 'none';
        covershow.style.display = 'none';
    }

</script>
