<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/21
 * Time: 上午 10:15
 */
use yii\helpers\Url;
use \app\classes\Menu;

$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '菜单管理'];
$this->title = '菜单管理'
?>
<div class="content">
    <input type="hidden" value="<?= $search['menu_name'] ?>" id="search">
    <input type="hidden" value="<?= $search['yn'] ?>" id="yn">
    <?php echo $this->render('_search', ['search' => $search]); ?>
    <div class="table-content">
        <?php echo $this->render('_action'); ?>
        <div class="space-10"></div>
        <div id="data">
            <?= $dataProvider ?>
        </div>
    </div>
</div>
<script>
    //递归隐藏所有子元素
    function HideChilde(id) {
        var object = [];
        var tr = $("#treeTable tr");
        tr.each(function () {
            var pid = $.trim($(this).attr("pid"));
            if (id == pid) {
                object.push($(this));
            }
        })
        for (var i = 0; i < object.length; i++) {
            object[i].find('img').attr("src", "../../img/icon/111_u821.png");
            object[i].removeClass('active');
            object[i].hide();
            HideChilde($.trim(object[i].attr("id")));
        }
    }
    $(function () {
        //頁面加載后顯示所有的一階數據
        var search = $("#search").val();
        var yn = $("#yn").val();
        if (search.length == 0) {
            tt = [];
            var tr = $("#treeTable tr");
            tr.each(function () {
                var level = $(this).attr("level");
                if (level == 1) {
                    $(this).show();
                }
            });
        }
        //當有查詢條件時調整頁面格式
        else if(search.length != 0 && yn.length == 0){
            var tr = $("#treeTable tr");
            if (tr.length > 2) {
                var tr1 = $("#treeTable tr:gt(1)");
                var i = 200;
                var trArray = [];
                tr1.each(function () {
                    var k = 0;
                    var pid1 = $.trim($(this).attr("pid"));
                    for (var j = 0; j < trArray.length; j++) {
                        var pid = $.trim(trArray[j].attr("pid"));
                        if (pid1 == pid) {
                            var attr = trArray[j].find("td").first().attr('style');
                            var td = $(this).find("td").first();
                            td.attr('style', attr);
                            k = 1;
                            i = trArray[j].find("td").first().attr('i');
                            i = parseInt(i) + 30;
                            break;
                        }
                    }
                    trArray.push($(this));
                    if (k == 0) {
                        var td = $(this).find("td").first();
                        td.attr('style', 'text-align:left;padding-left:' + i + 'px');
                        td.attr('i', i);
                        i = i + 30;
                    }
                });

            }
        }

        //點擊修改事件
        $(".update").on("click", function ($this) {
            var i = 1;
            var menuPkid = $(this).parent().parent().attr("id");
            var updatype = $(this).parent().parent().attr("updatype");
            if (typeof(updatype) == 'undefined') {
                i = 0;
            }
            $.fancybox({
                type: "iframe",
                width: 589,
                height: 380,
                autoSize: false,
                href: "<?=Url::to(['update-add'])?>?menuPkid=" + menuPkid + "&type=" + 1 + "&i=" + i,
                padding: 0
            });
        })
        //點擊新增事件
        $("#create").on('click', function () {
            $.fancybox({
                type: "iframe",
                width: 589,
                height: 380,
                autoSize: false,
                href: "<?=Url::to(['update-add'])?>?type=" + 0,
                padding: 0
            });

        })
        //操作設置事件
        $(".set").on('click', function ($this) {
            var menu_pkid = $(this).parent().parent().attr("id");
            var menu_name = $(this).parent().parent().attr("name");
            window.location = 'opera-set?menu_pkid=' + menu_pkid + "&menu_name=" + menu_name;
        })
        //點擊圖標展開子類和隱藏子類
        $(".showChilde").on('click', function () {
            var imgSrc = $(this).attr("src");
            if (imgSrc == '../../img/icon/111_u821.png') {
                $(this).attr('src', '../../img/icon/111_u955.png');
            }
            else {
                $(this).attr('src', '../../img/icon/111_u821.png');
            }
            var id = $.trim($(this).parent().parent().attr('id'));
            var obj = $(this).parent().parent().toggleClass('active');
            if (obj.hasClass('active')) {
                var tr = $("#treeTable tr");
                tr.each(function () {
                    var pid = $.trim($(this).attr('pid'));
                    if (id == pid) {
                        $(this).toggle();
                    }
                })
            }
            else {
                HideChilde(id);
            }
        })
    })
</script>