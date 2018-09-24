<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/24
 * Time: 下午 01:54
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="select">
    <h1 class="head-first">菜单权限设置</h1>
    <div class="mb-10"></div>
    <label class="width-100">菜单名称:</label>
    <input type="text" class="width-200 easyui-validatebox" id="menu_name">
    <?= Html::Button('查询', ['class' => 'button-blue  search-btn-blue ml-20', 'id' => 'select']) ?>
</div>
<div class="tree">
    <input type="text" style="display: none" id="role_pkid" value="<?= $role_pkid ?>">
    <!--        <input type="text" style="display: none" id="tid" value="--><? //= $tid ?><!--">-->
    <div>
        <ul id="tt" class="easyui-tree"
            checkbox="true">
        </ul>
    </div>
</div>


<script>
    $(function () {
        coverit();
        var role_pkid = $("#role_pkid").val();
        gettree(0);
        $("#select").click(function () {
            var menu_name = $("#menu_name").val();

            //根据菜单名称查询菜单role_pkid
            if (menu_name != "") {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"menu_name": menu_name},
                    'url': "<?= Url::to(['get-menuid']); ?>",
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
//           ;
        });
    })
    function gettree(msg) {
        var role_pkid = $("#role_pkid").val();
        $("#tt").tree({
            url: '<?= Url::to(["tree"]) ?>?role_pkid=' + role_pkid + '&menu_pkid=' + msg,
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#tt").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");
                $(".display134").parent().parent().parent().parent().parent().children('div').find('.tree-hit').addClass("tree-indent");
                $(".display134").parent().parent().parent().parent().parent().children('div').find('.tree-hit').removeClass("tree-hit");
                $(".display134").parent().parent().parent().parent().parent().children('div').find('.ree-expanded').removeClass("tree-expanded");
                $(".display134").parent().parent().parent().parent().parent().children('div').find('.tree-collapsed').removeClass("tree-collapsed");

            },
            onSelect: function (node) {
                if (node.state == "closed")
                    $(this).tree('expand', node.target);
                else
                    $(this).tree('collapse', node.target);
            }, onloaderror: function (arguments) {
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
