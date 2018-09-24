<?php
/**
 * User: F3859386
 * Date: 2016/9/13
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\classes\Menu;

?>
<style>
    .wd-tc-10 {
        width: 10px;
        text-align: center;
    }

    .displayOrnot {
        display: none;
    }
</style>
<div class="table-head">
    <p class="head"></p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-mchpdtype/create') ?
//            Html::a("<span class='text-center ml--5'>新增</span>",Url::to(['create']), ['id' => 'create'])
            "<a id='create'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a><span class='float-left wd-tc-10'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-mchpdtype/update') ?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='update' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                </a><span class='display-none float-left wd-tc-10'>|</span>"
            : '' ?>
        <?= Menu::isAction('/crm/crm-mchpdtype/delete') ?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update'])
            "<a id='delete' class='display-none' onclick=\"MchpdtDelete()\">
                    <div class='table-nav'>
                        <p class='delete-row-item-bgc float-left'></p>
                        <p class='nav-font'>删除</p>
                    </div>
                </a><span class='display-none float-left wd-tc-10'>|</span>"
            : '' ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function () {
        $("#create").on("click", function () {
            action(false);
        });
        $("#update").on("click", function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                return layer.alert("请点击选择信息!", {icon: 2, time: 5000});
            }
            action(true, data['id']);
        })

//        $("#view").on("click",function(){
//            var data = $("#data").datagrid("getSelected");
//            if(data == null){
//                return layer.alert("请点击选择信息!",{icon:2,time:5000});
//            }
//            var url="<?//= Url::to(['view'])?>//?id="+data['id'];
//            $.fancybox.open({
//                href: url,
//                type: 'iframe',
//                padding : [],
//                fitToView	: false,
//                width		: 470,
//                height		: 350,
//                autoSize	: false,
//                closeClick	: false,
//                openEffect	: 'none',
//                closeEffect	: 'none'
//            });
//        })
    });

    function action(is, id) {
        var url = is !== true ? "<?= Url::to(['create'])?>" : "<?= Url::to(['update'])?>?id=" + id;
        $.fancybox.open({
            href: url,
            type: 'iframe',
            padding: [],
            fitToView: false,
            width: 600,
            height: 500,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none'
        });
    }
</script>
