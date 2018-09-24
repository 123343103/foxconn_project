<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/7/19
 * Time: 14:12
 */
use yii\helpers\Url;
use yii\helpers\Html;
use app\classes\Menu;
?>
<div class="table-head">
    <p class="head">信用额度类型列表</p>
    <div class="float-right">
        <?= Menu::isAction('/crm/crm-credit-maintain/create')?
//            Html::a("<span>新增</span>",null, ['id' => 'create'])
            "<a id='create'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>新增</p>
                </div>
                <p class=\"float-left\">&nbsp;|&nbsp;</p>
            </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-credit-maintain/update')?
//            Html::a("<span class='text-center ml--5'>修改</span>", null,['id'=>'update','class'=>'display-none'])
            "<a onclick='updateType();' id='update' style='display: none;'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <?= Menu::isAction('/crm/crm-credit-maintain/delete')?
//            Html::a("<span class='text-center ml--5'>刪除</span>", null,['id'=>'delete','class'=>'display-none'])
            "<a onclick='cancle();' id='delete' style='display: none;'>
                    <div class='table-nav'>
                        <p class='delete-item-bgc float-left'></p>
                        <p class='nav-font'>删除</p>
                    </div>
                    <p class=\"float-left\">&nbsp;|&nbsp;</p>
                </a>"
            :'' ?>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>
<script>
    $(function(){
        $('#create').click(function(){
            $.fancybox({
                padding: [],
                fitToView: true,
                width: 500,
                height: 350,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['create'])?>"
            });
        })
    })
    //删除类型
    function cancle(data=null){
        var arr = [];
        var id;
        var url = "<?=Url::to(['delete'])?>";
        if(data == null){
            var obj = $("#data").datagrid("getChecked");
            if(obj.length == 0){
                layer.alert("请先选择一个类型", {icon: 2, time: 5000});
                return false;
            }
            for (var i = 0; i < obj.length; i++) {
                arr.push(obj[i].id);
            }
            id = arr.join(',');
        }else{
            id = data;
        }
        data_delete(id,url);
    };
    /*修改类型*/
    function updateType(data = null){
        if(data == null){
            var a = $("#data").datagrid("getSelected");
            if(a == null){
                layer.alert("请选择一条账信类型!",{icon:2,time:5000});
                return false;
            }
            id = a.id;
        }else{
            id = data;
        }
        $.fancybox({
            padding: [],
            fitToView: true,
            width: 500,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['update'])?>?id="+id
        });
    }
</script>