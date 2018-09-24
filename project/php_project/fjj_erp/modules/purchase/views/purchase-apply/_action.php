<?php
use yii\helpers\Url;
?>
<div class="table-head mb-5">
    <p class="head">商品请购单列表</p>
    <div class="float-right">
        <a id="add_btn">
            <div style="height: 23px;float: left">
                <p class="add-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a id="edit_btn" class="display-none">
            <div style="height: 23px;float: left;">
                <p class="update-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a id="censorship_btn" class="display-none">
            <div style="height: 23px;float: left;">
                <p class="submit-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="cancel_btn" class="display-none">
            <div style="height: 23px;float: left;">
                <p class="setting11" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="export_btn">
            <div style="height: 23px;float: left;">
                <p class="export-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a href="<?=Url::to(['/index/index'])?>">
            <div style="height: 23px;float: left">
                <p class="return-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
            </div>
        </a>
    </div>

</div>


