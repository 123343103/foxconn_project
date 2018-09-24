<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/30
 * Time: 下午 03:56
 */
use yii\helpers\Url;
use yii\helpers\Html;

$this->params['homeLike'] = ['label' => '系统平臺设置', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => 'index'];
$this->params['breadcrumbs'][] = '仓库权限设置';
$this->title = '仓库权限设置';
?>
<div class="content">
    <h1 class="head-first">
        仓库权限设置
    </h1>
    <label class="width-100">查询:</label>
    <input type="text" class="width-200 easyui-validatebox" id="wh_name">
    <?= Html::Button('查询', ['class' => 'button-blue  search-btn-blue ml-20', 'id' => 'select']) ?>
<!--    <div class="mb-20"></div>-->
    <div id="wh_setup" class="mb-20" style="margin-left: 100px;margin-top:50px;font-size: 14px;">
        <input type="hidden" id="user_id" value="<?= $user_id ?>">
        <ul id="wh_part" class="easyui-tree"
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
        var wh_name = $("#wh_name").val().trim();

        //根据菜单名称查询菜单role_pkid
        if (wh_name.length != 0) {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"wh_name": wh_name},
                'url': "<?= Url::to(['get-wh-id']); ?>",
                'success': function (msg) {
                    if (msg == "-1") {
                        layer.alert("未能找到该仓库或分区！",{icon:2});
                        tree(0);
                        $("#wh_name").val("");
                    } else if(msg=="0"){
                        trees(wh_name);
                    }
                    else {
                        tree(msg);
                    }
                }
            });
        } else {
            tree(0);
        }
    });

    function tree(msg) {
        $("#wh_part").tree({
            url: '<?= Url::to(["get-wh-tree"]) ?>?wh_id='+msg+'&user_id=' + $("#user_id").val(),
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#wh_part").tree("collapseAll");
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
    function trees(wh_name) {
        $("#wh_part").tree({
            url: '<?= Url::to(["get-part-tree"]) ?>?part_name='+wh_name+'&user_id=' + $("#user_id").val(),
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#wh_part").tree("collapseAll");
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
        var part_id=[];
        var wh_id=[];
        var whpk_id=[];
        var user_id = $("#user_id").val();

        $("#wh_part .tree-checkbox1").each(function () {
            var s = $(this).parent().find(".tree-title");
            var a = s.find(".level").text();
            if (a == 2) {
                part_id.push(s.find(".part_id").text());
                whpk_id.push(s.find(".wh_id").text());
            }
            if(a==1)
            {
                wh_id.push(s.find(".wh_id").text())
            }
            for(var i=0;i<wh_id.length;i++)
            {
                for(var j=0;j<whpk_id.length;j++)
                {
                    if(wh_id[i]==whpk_id[j])
                    {
                        wh_id.splice(i,1);
                        break;
                    }
                }
            }
        });
        if (part_id.length > 0||wh_id.length>0||whpk_id.length>0) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?=Url::to(["savew"])?>',
                async: false,
                data: {
                    user_id: user_id,
                    part_id: part_id.join(),
                    wh_id:wh_id.join(),
                    whpk_id:whpk_id.join()
                },
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
            layer.alert("您未选中任何仓库或厂区!");
        }
    }

    function coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        var textcenter = document.getElementById("buttons");
        cover.style.display = 'block';
        covershow.style.display = 'block';
        textcenter.style.display = 'none';
    }
    function hidden_coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        var textcenter = document.getElementById("buttons");
        cover.style.display = 'none';
        covershow.style.display = 'none';
        textcenter.style.display = 'block';
    }
</script>
