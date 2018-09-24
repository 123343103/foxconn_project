<?php
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;

$this->title = "拜访计划详情";
//dumpE(Html::decode($model['spend_time']));
?>
<div class="content fs-14">
    <table width="100%" class="no-border vertical-center fs-14 head-first">
        <tr>
            <td width="50%" class="no-border vertical-center white">拜访计划详情</td>
            <td width="20%" class="no-border vertical-center white text-right">编号</td>
            <td width="30%" class="no-border vertical-center white"><?= $model['svp_code'] ?></td>
        </tr>
    </table>
    <h3 class="head-second mt-20">客户基本信息</h3>
    <table width="100%" class="no-border vertical-center fs-14">
        <tr class="no-border vertical-center ">
            <td width="10%" class="no-border vertical-center">客户全称：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerName'] ?></td>
            <td width="10%" class="no-border vertical-center">客户类型：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerType'] ?></td>
        </tr>
    </table>
    <div class="space-20"></div>
    <table width="100%" style="border: 2px solid #eff0f2;margin:0 auto;vertical-align: middle; font-family:
serif; font-size: 14px; color: #ffffff;">
        <tr>
            <td width="10%" class="no-border vertical-center">客户经理：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerManager'] ?></td>
            <td width="10%" class="no-border vertical-center">营销区域：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerSaleArea'] ?></td>
        </tr>
    </table>
    <div class="space-20"></div>
    <table width="100%" class="no-border vertical-center">
        <tr class="no-border vertical-center">
            <td width="10%" class="no-border vertical-center">联系人：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerContacts'] ?></td>
            <td width="10%" class="no-border vertical-center">联系电话：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerTel2'] ?></td>
        </tr>
    </table>
    <div class="space-30"></div>
    <div class="lable-p" >
        <label class="width-80 text-left">详细地址:</label>
        <p class="width-700 text-next"> <?= $model['customerInfo']['customerDistrict'] ?></p>
    </div>
    <div class="space-20"></div>
    <h3 class="head-second">行程计划</h3>
    <div class="space-20"></div>
    <table width="100%" class="no-border vertical-center">
        <tr class="no-border vertical-center">
            <td width="10%" class="no-border vertical-center">拜访人：</td>
            <td width="40%" class="no-border vertical-center"><?= $model['visitPerson'] ?></td>
            <td width="10%" class="no-border vertical-center">拜访类型：</td>
            <td width="40%"
                class="no-border vertical-center"><?= $model['customerInfo']['customerType'] ?></td>
        </tr>
    </table>
    <div class="space-20"></div>
    <table width="100%" class="no-border vertical-center">
        <tr class="no-border vertical-center">
            <td width="10%" class="no-border vertical-center">开始时间：</td>
            <td width="40%" class="no-border vertical-center"><?= $model['start'] ?></td>
            <td width="10%" class="no-border vertical-center">结束时间：</td>
            <td width="40%" class="no-border vertical-center"><?= $model['end'] ?></td>
        </tr>
    </table>
    <div class="space-20"></div>
    <div class="mb-10  lable-p no-border vertical-center">
        <label class="width-80 ml-30 no-border vertical-center text-left">计划用时:</label>
        <span class="width-170 text-next">             <?= unserialize($model['spend_time'])[0] ?>天
            <?= unserialize($model['spend_time'])[1] ?>时
            <?= unserialize($model['spend_time'])[2] ?>分</span>
    </div>
    <div class="mb-10 mt-20">
        <label class="width-80 text-left">计划内容:</label>
        <p class="width-700 text-next"> <?= $model['svp_content'] ?></p>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="width-80 text-left">备注:</label>
        <p class="width-700 text-next"> <?= $model['svp_remark'] ?></p>
    </div>

    <!--    <div class="text-center">-->
    <!--        <button class="button-white-big" type="button" onclick="history.go(-1);">返回</button>-->
    <!--    </div>-->
</div>