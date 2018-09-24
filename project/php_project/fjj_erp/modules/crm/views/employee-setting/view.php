<?php
use yii\helpers\Url;
use \yii\helpers\Html;
$this->title = '销售员资料详情';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '销售员资料列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '销售员信息'];
/*\app\classes\Menu::isAction('/crm/employee-setting/update') ? \yii\helpers\Html::button('修改', ['class' => 'button-blue', 'onclick' => 'window.location.href=\'' . Url::to(["update"]) . '?id=' . $model['staff_code'] . '\'']) : ''*/
?>
<style>
    .width50{
        width: 50px;
    }
    .width200{
        width: 220px;
    }
    .width110{
        width: 110px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .width100{
        width: 100px;
    }
    .width150{
        width: 150px;
    }
    .width220{
        width: 220px;
    }
    .width270{
        width: 270px;
    }
</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title ?>
    </h1>
    <div class="border-bottom mb-10  pb-10">
        <?php if($model['status']=='启用'){?>
             <?= Html::button('修改', ['class' => 'button-mody', 'onclick' => 'window.location.href=\'' . Url::to(["update",'id'=>$model['staff_code']]) . '\'']) ?>
        <?php } ?>
        <?= Html::button('切换列表', ['class' => 'button-change', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <h2 class="head-second">
        销售员基本信息
    </h2>
    <div class="mb-10">
        <label class="label-width qlabel-align width50 ml-20">工号<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['staff_code'] ?></label>
        <label class="label-width qlabel-align width270">姓名<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['staff']['name'] ?></label>
        </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width50 ml-20">资位<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['staff']['job_level'] ?></label>
        <label class="label-width qlabel-align width270">联系电话<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['staff']['tel'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width50 ml-20">邮箱<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['staff']['email'] ?></label>
        <label class="label-width qlabel-align width270">所在部门<label>：</label></label>
        <label class="label-width text-left width200"><?= $info['organization'] ?></label>
    </div>
    <h2 class="head-second">
        销售员详细信息
    </h2>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">销售角色<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['sale_role'] ?></label>
        <label class="label-width qlabel-align width220">人力类型<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['sarole_type'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">所在营销区域<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['csarea'] ?></label>
        <label class="label-width qlabel-align width220">所在销售点<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['sts_sname'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">对应店长<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['dz_code'] ?><?= $model['dz_name'] ?></label>
        <label class="label-width qlabel-align width220">对应省长<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['sz_code'] ?><?= $model['sz_name'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">直属上司<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['leader'] ?></label>
        <label class="label-width qlabel-align width220">上司角色<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['lerole_sname'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width110 ml-20">个人销售提成系数<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['sale_qty'] ?>&nbsp;%</label>
        <label class="label-width qlabel-align" style="width: 210px">个人销售目标指数<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['sale_quota'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width110 ml-20" >允许销售商品类别<label>：</label></label>
        <label class="label-width text-left"><?= $model['category']['category_id'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width110 ml-20"  >是否客户经理人<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['isrule'] == 1 ? '是' : '否' ?></label>
        <label class="label-width qlabel-align " style="width: 210px">销售员状态<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['status'] ?></label>
    </div>


<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="13%" class="no-border vertical-center">销售角色：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['sale_role'] ?><!--</td>-->
<!--                <td width="4%" class="no-border vertical-center"></td>-->
<!--                <td width="13%" class="no-border vertical-center">人力类型：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['sarole_type'] ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="13%" class="no-border vertical-center">所在营销区域：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['csarea'] ?><!--</td>-->
<!--                <td width="4%" class="no-border vertical-center"></td>-->
<!--                <td width="13%" class="no-border vertical-center">所在销售点：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['sts_sname'] ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="13%" class="no-border vertical-center">对应省长：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['sz_code'] ?><!------><?//= $model['sz_name'] ?><!--</td>-->
<!--                <td width="4%" class="no-border vertical-center"></td>-->
<!--                <td width="13%" class="no-border vertical-center">对应店长：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['dz_code'] ?><!------><?//= $model['dz_name'] ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="13%" class="no-border vertical-center">直属上司：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['leader'] ?><!--</td>-->
<!--                <td width="4%" class="no-border vertical-center"></td>-->
<!--                <td width="13%" class="no-border vertical-center">上司角色：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['lerole_sname'] ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="15%" class="no-border vertical-center">个人销售提成系数：</td>-->
<!--                <td width="33%" class="no-border vertical-center">--><?//= $model['sale_qty'] ?><!--&nbsp;&nbsp;(&nbsp;%&nbsp;)-->
<!--                </td>-->
<!--                <td width="4%" class="no-border vertical-center"></td>-->
<!--                <td width="15%" class="no-border vertical-center">个人销售目标指数：</td>-->
<!--                <td width="33%" class="no-border vertical-center">--><?//= $model['sale_quota'] ?>
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="6%" class="no-border vertical-center">允许销售商品类型：</td>-->
<!--                <td width="35%" class="no-border vertical-center">--><?//= $model['category']['category_id'] ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
<!--    <div class="ml-70 mb-20">-->
<!--        <table width="90%" class="no-border vertical-center ml-25">-->
<!--            <tr class="no-border">-->
<!--                <td width="15%" class="no-border vertical-center">销售员状态：</td>-->
<!--                <td width="33%" class="no-border vertical-center">--><?//= $model['status'] ?><!--</td>-->
<!--                <td width="4%" class="no-border vertical-center"></td>-->
<!--                <td width="15%" class="no-border vertical-center">是否客户经理人：</td>-->
<!--                <td width="33%" class="no-border vertical-center">--><?//= $model['isrule'] == 1 ? '是' : '否' ?><!--</td>-->
<!--            </tr>-->
<!--        </table>-->
<!--    </div>-->
</div>
