<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/21
 * Time: 上午 10:47
 */
use app\classes\Menu;
use yii\helpers\Url;
?>
<div class="space-10"></div>
<div class="space-10"></div>
<div class="table-head">
    <p style="margin-left: 20px;">菜单列表</p>
    <div class="float-right" style="margin-right: 30px;">
        <?= Menu::isAction('/system/menu-power/update-add') ?
            "<a id='create'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
            : '' ?>
        <span style='float: left;'>&nbsp;|&nbsp;</span>
        <a href="<?= Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>
