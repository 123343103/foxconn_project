<?php
use yii\helpers\Url;
?>
<div class="table-head">
    <p class="head">盘点单列表</p>
    <div class="float-right">
        <a id="add_btn">
            <div style="height: 23px;float: left">
                <p class="add-item-bgc" style="float: left;"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
            </div>
        </a>
        <p style="float: left;">&nbsp;|&nbsp;</p>
        <a id="edit_btn" class="display-none">
            <div style="height: 23px;float: left">
                <p class="update-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="censorship_btn" class="display-none">
            <div style="height: 23px;float: left">
                <p class="submit-item-bgc" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="addmes_btn" class="display-none">
            <div style="height: 23px;float: left">
                <p class="setting2" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;添加复盘信息</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="cancel_btn" class="display-none">
            <div style="height: 23px;float: left">
                <p class="setbcg9" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;取消</p>
            </div>
        </a>
        <p style="float: left;display: none">&nbsp;|&nbsp;</p>
        <a id="detail_btn">
            <div style="height: 23px;float: left;">
                <p class="setting10" style="float: left"></p>
                <p style="font-size: 14px;margin-top: 2px;">&nbsp;盘点单明细表</p>
            </div>
        </a>
        <p style="float: left">&nbsp;|&nbsp;</p>
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


