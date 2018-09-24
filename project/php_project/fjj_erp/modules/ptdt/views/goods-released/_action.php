<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/11
 * Time: 14:32
 */
use yii\helpers\Url;
use \app\classes\Menu;

?>
<div class="table-head">
    <p class="head"><?= $this->title ?></p>
    <div class="float-right">
        <a id="export">
            <div class='table-nav'>
                <p class='export-item-bgc float-left'></p>
                <p class='nav-font'>导出</p>
            </div>
        </a>
        <p class="float-left">&nbsp;|&nbsp;</p>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>

<script>
    'use strict';
    $(function(){
        /*导出*/
        $("#export").click(function () {
            layer.confirm("确定导出信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?=Url::to(['index'])?>?export=1&"+$("form").serialize()) {
                        layer.closeAll();
                    } else {
                        layer.alert('导出新商品列表错误!', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            );
        });
    })
</script>