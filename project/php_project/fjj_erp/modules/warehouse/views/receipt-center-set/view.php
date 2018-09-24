<?php
/**
 * User: F1677929
 * Date: 2017/12/6
 */
/* @var $this yii\web\View */
$this->title='收货中心详情页';
?>
<style>
    .div_tab_display {
        display: table;
        margin-bottom: 5px;
    }
    .div_tab_display > label {
        display: table-cell;
        vertical-align: middle;
        width: 120px;
    }
    .div_tab_display > span {
        display: table-cell;
        vertical-align: middle;
        width: 400px;
        word-break: break-all;
    }
</style>
<div class="head-first"><?=$this->title?></div>
<div class="div_tab_display">
    <label>编码：</label>
    <span><?=$viewData['rcp_no']?></span>
</div>
<div class="div_tab_display">
    <label>收货中心名称：</label>
    <span><?=$viewData['rcp_name']?></span>
</div>
<div class="div_tab_display">
    <label>联系人：</label>
    <span><?=$viewData['contact']?></span>
</div>
<div class="div_tab_display">
    <label>联系方式：</label>
    <span><?=$viewData['contact_tel']?></span>
</div>
<div class="div_tab_display">
    <label>邮箱：</label>
    <span><?=$viewData['contact_email']?></span>
</div>
<div class="div_tab_display">
    <label>状态：</label>
    <span><?=$viewData['rcp_status']?></span>
</div>
<div class="div_tab_display">
    <label>详细地址：</label>
    <span><?=$viewData['addr']?></span>
</div>
<div class="div_tab_display">
    <label>备注：</label>
    <span><?=$viewData['remarks']?></span>
</div>