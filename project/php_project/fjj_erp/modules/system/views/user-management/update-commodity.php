<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/30
 * Time: 下午 04:05
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->params['homeLike'] = ['label' => '系统平臺设置', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => 'index'];
$this->params['breadcrumbs'][] = '商品权限设置';
$this->title = '商品权限设置';
?>
<div class="content">
    <h1 class="head-first">
        商品权限设置
    </h1>

    <label class="width-100">查询:</label>
    <input type="text" class="width-200 easyui-validatebox" id="ctg_name">
    <?= Html::Button('查询', ['class' => 'button-blue  search-btn-blue ml-20', 'id' => 'select']) ?>
    <div id="data_setup" class="mb-20" style="margin-left: 100px;margin-top:50px;font-size: 14px;">
        <input type="hidden" id="user_id" value="<?= $user_id ?>">
        <ul id="Commodity_category" class="easyui-tree"
            checkbox="true">
        </ul>
    </div>
    <div class="mb-20 text-center" id="buttons" style="display: none;">
        <button class="button-blue-big" type="button" id="submit" onclick="submit()">确定</button>
        <button class="button-white-big ml-20" type="button" id="back" onclick="history.go(-1);">返回</button>
    </div>


    <div id="cover"></div>
    <div id="coverShow" style="margin: 0 auto;width: 130px;height: 100px;">
        <table align="center" border="0" cellspacing="0" cellpadding="0"
               style="border-collapse: collapse; height: 30px; min-height: 30px;">
            <tr>
                <td height="30" style="font-size: 12px;">数据加载中，请稍后......</td>
            </tr>

        </table>
    </div>
</div>
<script>
    $(function () {
        coverit();
        tree(0);
    });
    $("#select").click(function () {
        coverit();
        var ctg_name = $("#ctg_name").val().trim();

        //根据菜单名称查询菜单role_pkid
        if (ctg_name.length != 0) {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"ctg_name": ctg_name},
                'url': "<?= Url::to(['get-ctgid']); ?>",
                'success': function (msg) {
                    if (msg == "0") {
                        layer.alert("未能找到该商品！",{icon:2});
                        tree(0);
                        $("#ctg_name").val("");
                    } else {
                        tree(msg);
                    }
                }
            });
        } else {
            tree(0);
        }
//           ;
    });

    function tree(msg)
    {
        $("#Commodity_category").tree({
            url: '<?= Url::to(["get-trees"]) ?>?p_catg_id='+msg+'&userid=' + $("#user_id").val(),
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#Commodity_category").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");
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
    function trees(msg)
    {
        $("#Commodity_category").tree({
            url: '<?= Url::to(["get-trees-by-id"]) ?>?p_catg_id='+msg+'&userid=' + $("#user_id").val(),
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#Commodity_category").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");
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

    function submit() {
        var user_id = $("#user_id").val();
        var Commodity_rid = [];
        //  商品权限选择id
        $("#Commodity_category .tree-checkbox1").each(function () {
            var s = $(this).parent().find(".tree-title");
            var a = s.find(".level").text();
            if (a == 3||a == 4||a == 2||a==1) {
                Commodity_rid.push(s.find(".catgid").text());
            }
        });
        if (Commodity_rid.length > 0) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?=Url::to(["savec"])?>',
                async: false,
                data: {user_id: user_id, Commodity_rid: Commodity_rid.join()},
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
            layer.alert("您未选中任何商品类别!");
        }
    }

    function coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        var textcenter= document.getElementById("buttons");
        var Commodity_category=document.getElementById("Commodity_category");
        cover.style.display = 'block';
        covershow.style.display = 'block';
        textcenter.style.display='none';
        Commodity_category.style.display='none';
    }
    function hidden_coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        var textcenter= document.getElementById("buttons");
        var Commodity_category=document.getElementById("Commodity_category");
        cover.style.display = 'none';
        covershow.style.display = 'none';
        textcenter.style.display='block';
        Commodity_category.style.display='block';
    }
</script>
