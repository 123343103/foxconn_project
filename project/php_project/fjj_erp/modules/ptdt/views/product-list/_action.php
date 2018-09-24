<?php
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;

?>
<style>
    .table-heads #_export  p:hover{
        border-bottom: 3px solid #3683ec !important;
    }
</style>
<div class="table-heads">
    <div class="float-right" id="_export">
        <a id="export">
            <div style="height: 23px;width: 55px;float: left;">
                <p class="export-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <?= Html::a('<div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>', Url::to(['/index/index'])) ?>
    </div>
</div>