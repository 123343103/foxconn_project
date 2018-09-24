<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/9/14
 * Time: 8:38
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;
$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label'=>'厂商计划列表','url'=>Url::to(['/ptdt/visit-plan/index'])];
$this->params['breadcrumbs'][] = ['label' => '厂商计划详情', 'url' => Url::to(['/ptdt/visit-plan/view','id'=>$model['pvp_planID']])];
$this->title = '厂商拜访计划';/*BUG修正 增加title*/
?>
<div class="content">
    <h1 class="head-first">
        厂商拜访计划
        <span class="head-code">编号：<?= $model['pvp_plancode'] ?></span>
    </h1>
    <div class="border-bottom mb-20 pb-10">
        <?= Menu::isAction('/ptdt/visit-plan/edit')?Html::button('修改', ['class' => 'button-blue width-80', 'onclick' => 'window.location.href=\'' . Url::to(["edit"]) . '?id=' . $model['pvp_planID'] . '\'']):'' ?>
        <?= Menu::isAction('/ptdt/visit-plan/delete')?Html::button('删除', ['class' => 'button-blue width-80','id' => 'delete']):'' ?>
        <?= Menu::isAction('/ptdt/visit-plan/index')?Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']):'' ?>
        <?= Menu::isAction('/ptdt/visit-plan/add')?Html::button('新增计划', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["/ptdt/visit-plan/add"]) . '?id=' . $model['firm_id'] . '\'']):'' ?>
        <?= Menu::isAction('/ptdt/visit-resume/add')?Html::button('新增履历', ['class' => 'button-blue width-100','id'=>'add_resume']):"" ?>

    </div>
    <div class="mb-30">
        <h2 class="head-second">
            <p>计划信息</p>
        </h2>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">计划类型:</td>
                <td class="no-border vertical-center" width="87%"><?= $model['planType']?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">计划拜访厂商:</td>
                <td width="26%" class="no-border vertical-center"><?= $model['firm_sname'] ?></td>
                <td width="31%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">厂商简称:</td>
                <td width="26%" class="no-border vertical-center"><?= $model['firm']['firm_shortname'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">厂商地址:</td>
                <td width="26%" class="no-border vertical-center"><?= $model['firm']['firmAddress']['fullAddress'] ?></td>
                <td width="31%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">厂商类型:</td>
                <td width="26%" class="no-border vertical-center"><?= $model['firm']['firmType'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">拜访人(商品经理人):</td>
                <td class="no-border vertical-center" width="18%"><?= $model['visitPerson']['code'] ?> &nbsp <?= $model['visitPerson']['name'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">职位:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['visitPerson']['job'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">联系电话:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['visitPerson']['mobile'] ?></td>
            </tr>
        </table>

        <?php
            foreach($model['accompany'] as $item){
        ?>
            <table width="90%" class="no-border vertical-center ml-25 mb-20">
                <tr class="no-border">
                    <td class="no-border vertical-center" width="13%">陪同人员:</td>
                    <td class="no-border vertical-center" width="18%"><?= $item['staff_name'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td class="no-border vertical-center" width="13%">职位:</td>
                    <td class="no-border vertical-center" width="18%"><?= $item['job_task'] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td class="no-border vertical-center" width="13%">联系电话:</td>
                    <td class="no-border vertical-center" width="18%"><?= $item['staff_mobile'] ?></td>
                </tr>
            </table>
        <?php } ?>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">计划日期:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['plan_date'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">时间:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['plan_time'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">地点:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['plan_place'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">厂商联系人:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['pvp_contact_man'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">职位:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['pvp_contact_position'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">联系电话:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['pvp_contact_mobile'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">拜访目的:</td>
                <td class="no-border vertical-center" width="87%"><?= $model['purpose'] ?> &nbsp; <?= $model['purpose_write'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">备注:</td>
                <td class="no-border vertical-center" width="87%"><?= $model['note'] ?></td>
            </tr>
        </table>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td width="13%" class="no-border vertical-center">建立计划日期:</td>
                <td width="26%" class="no-border vertical-center"><?= $model['create_at'] ?></td>
                <td width="31%" class="no-border vertical-center"></td>
                <td width="13%" class="no-border vertical-center">建立人:</td>
                <td width="26%" class="no-border vertical-center"><?= $model['staffPerson'] ?></td>
            </tr>
        </table>
    </div>
</div>
<script>
    $(function () {
        //新增拜访履历
        $("#add_resume").click(function () {
            var status = '<?= $model["status"] ?>';
            var purpose = '<?= $model['purpose'] ?>';
            if(status != '新增'){
                layer.alert("无法新增!",{icon:2,time:5000});
            }else{
                if(purpose== '拜访' ){
                    window.location.href = "<?=Url::to(['/ptdt/visit-resume/add'])?>?firmId=" + <?= $model['firm']['firm_id'] ?> + "&planId=" + <?= $model['pvp_planID'] ?>;
                }else{
                    window.location.href = "<?=Url::to(['/ptdt/firm-negotiation/create'])?>?firmId=" + <?= $model['firm']['firm_id'] ?> + "&planId=" + <?= $model['pvp_planID'] ?>;
                }
            }
        })
        $("#delete").on("click", function () {
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": <?= $model['pvp_planID'] ?>},
                url: "<?=Url::to(['/ptdt/visit-plan/delete-count']) ?>",
                success: function (msg) {
                    if (msg === 'false') {
                        layer.alert('无法删除', {icon: 2})
                    } else {
                        var index = layer.confirm("确定要删除这条记录吗?",
                            {
                                btn: ['确定', '取消'],
                                icon: 2
                            },
                            function () {
                                $.ajax({
                                    type: "get",
                                    dataType: "json",
                                    async: false,
                                    data: {"id": <?= $model['pvp_planID'] ?>},
                                    url: "<?=Url::to(['delete']) ?>",
                                    success: function (msg) {
                                        if (msg.flag === 1) {
                                            layer.alert(msg.msg, {
                                                icon: 1, end: function () {
                                                    location.href = '<?= Url::to(['index']) ?>'
                                                }
                                            });
                                        } else {
                                            layer.alert(msg.msg, {icon: 2})
                                        }
                                    },
                                    error: function (msg) {
                                        layer.alert(msg.msg, {icon: 2})
                                    }
                                })
                            },
                            function () {
                                layer.closeAll();
                            }
                        )
                    }
                }
            })

        });
    })
</script>
