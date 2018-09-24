<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 08:17
 */
use app\classes\Menu;
use yii\helpers\Url;
?>
<div class="table-head"  style="margin-top:20px;">
    <p class="head">请购料号列表</p>
    <div class="float-right">
        <?=Menu::isAction('/purchase/purchase-before-work/procurement') ? "<a id='procurement' style='display:none;'>
                <div class='table-nav'>
                    <p class='float-left add-item-bgc'></p>
                    <p class='nav-font'>采购</p>
                </div>
            </a>"
            : '' ?>
        <p class="float-left" id="add-visit-record1" style='display:none;'>&nbsp;|&nbsp;</p>
        <a href="<?=Url::to(['/index/index']) ?>">
            <div class='table-nav'>
                <p class='return-item-bgc float-left'></p>
                <p class='nav-font'>返回</p>
            </div>
        </a>
    </div>
</div>
