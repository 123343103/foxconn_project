<?php
use app\classes\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '计划列表', 'url' => Url::to(['/crm/crm-visit-plan/index'])];
$this->params['breadcrumbs'][] = ['label' => '拜访计划详情'];
$this->title = "拜访计划详情";
?>
<div class="content">
    <h1 class="head-first">
        拜访计划详情
        <span class="head-code">档案编号：<?= $model['svp_code'] ?></span>
    </h1>
    <div class="mb-10">
        <?php if($model['svp_status']===10 || $model['svp_status']===40){?>
        <?= Menu::isAction('/crm/crm-visit-plan/update') ? Html::button('修改', ['class' => 'button-blue width-80', 'id' => 'update']) : '' ?>
        <?php }?>

        <?php if($model['svp_status']===10){?>
        <?= Menu::isAction('/crm/crm-visit-plan/cancel') ? Html::button('取消计划', ['class' => 'button-blue', 'style'=>'width:80px', 'id' => 'cancel']) : '' ?>
        <?php }?>

        <?php if($model['svp_status']===40){?>
            <?= Menu::isAction('/crm/crm-visit-plan/stop') ? Html::button('终止计划', ['class' => 'button-blue', 'style'=>'width:80px', 'id' => 'stop']) : '' ?>
        <?php }?>

        <?= Html::button('切换列表', ['class' => 'button-blue', 'style'=>'width:80px', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>

        <?php if($model['svp_status']===10 || $model['svp_status']===40){?>
            <?php if(Menu::isAction('/crm/crm-visit-record/add')){?>
        <?= Html::button('添加拜访记录', ['class' => 'button-blue', 'style'=>'width:100px', 'id' => 'add-visit-record', 'onclick' => 'window.location.href=\'' . Url::to(["/crm/crm-visit-record/add"]) . "?customerId=" . $model["cust_id"] . "&planId=" . $model['svp_id'] . '\'']) ?>
            <?php }?>
        <?php }?>
        <button class="button-blue" onclick="history.go(-1)">返回</button>
    </div>
    <div class="mb-10" style="height:2px;background-color:#9acfea;"></div>
    <h2 class="head-second">客户基本信息</h2>
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户名称<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerName']?></td>
                <td class="no-border vertical-center label-align" width="10%">客户类型<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerType']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">客户经理<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerManager']?></td>
                <td class="no-border vertical-center label-align" width="10%">营销区域<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerSaleArea']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">联系人<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerContacts']?></td>
                <td class="no-border vertical-center label-align" width="10%">联系电话<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['customerInfo']['customerTel2']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">详细地址<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" colspan="3" width="80%"><?=$model['customerInfo']['customerDistrict']?></td>
            </tr>
        </table>
    </div>
    <h2 class="head-second">行程计划</h2>
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">拜访人<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['svp_staff_code'].'&nbsp;'.$model['visitPerson']?></td>
                <td class="no-border vertical-center label-align" width="10%">拜访类型<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=$model['visitType']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">开始时间<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=substr($model['start'],0,16)?></td>
                <td class="no-border vertical-center label-align" width="10%">结束时间<label>：</label></td>
                <td class="no-border vertical-center" width="35%"><?=substr($model['end'],0,16)?></td>
            </tr>
        </table>
    </div>
    <div class="mb-10" style="margin-left:30px;margin-right:30px;">
        <p style="margin-bottom:2px;font-size:13px;font-weight:900;">陪同人员信息：</p>
        <table class="table">
            <thead>
            <tr>
                <th style="width:4%;">序号</th>
                <th style="width:24%;">工号</th>
                <th style="width:24%;">姓名</th>
                <th style="width:24%;">职位</th>
                <th style="width:24%;">联系电话</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($accompanyData)){?>
                <?php foreach($accompanyData as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$val['staff_code']?></td>
                        <td><?=$val['staff_name']?></td>
                        <td><?=$val['title_name']?></td>
                        <td><?=$val['acc_mobile']?></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="mb-10">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">计划内容<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" width="80%"><?=$model['svp_content']?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">备注<label>：</label></td>
                <td class="no-border vertical-center qvalue-align" width="80%"><?=$model['svp_remark']?></td>
            </tr>
            <?php if($model['svp_status']===30){?>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="10%">取消原因<label>：</label></td>
                <td class="no-border vertical-center qvalue-align"
                    width="80%"><?=$model['cancl_rs']?></td>
            </tr>
            <?php }?>
            <?php if($model['svp_status']===50){?>
                <tr class="no-border mb-10">
                    <td class="no-border vertical-center label-align" width="10%">终止原因<label>：</label></td>
                    <td class="no-border vertical-center qvalue-align"
                        width="80%"><?=$model['cancl_rs']?></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>
<script>
    $(function () {
        $("#update").on("click", function () {
            window.location.href = "<?= Url::to(['update']) ?>?id=" +<?= $model['svp_id']?> ;
        });
        //取消拜访计划
        $("#cancel").on("click", function () {
            $.fancybox({
                type: "iframe",
                width: 400,
                height: 250,
                autoSize: false,
                href: "<?=Url::to(['cause'])?>?svp_id=" + <?=$model['svp_id']?> + "&type=1",
                padding: 0
            });
        });
        $("#stop").on("click", function () {
            $.fancybox({
                type: "iframe",
                width: 400,
                height: 250,
                autoSize: false,
                href: "<?=Url::to(['cause'])?>?svp_id=" + <?=$model['svp_id']?> + "&type=2",
                padding: 0
            });
        });
    })
</script>
