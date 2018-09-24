<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/11/27
 * Time: 上午 08:37
 */
use app\assets\MultiSelectAsset;
use app\assets\TreeAsset;
use yii\helpers\Url;


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
</style>
<div class="content">
    <div class="mb-20"></div>
    <input type="text" style="display: none" id="catg_id" value="<?= $catgid ?>">
    <div style="margin-left: 40px;">
        <ul id="tt" class="easyui-tree"
            checkbox="true">
        </ul>
    </div>

</div>
<script>
    $(function () {
        coverit();
        $("#tt").tree({
            url: '<?= Url::to(["tree"]) ?>?catgid=' + $("#catg_id").val(),
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
            }
        });
//        $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
//        $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");

        $("#back").on('click', function () {
            window.location.href = '<?=Url::to(['index'])?>';
        })
    });

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