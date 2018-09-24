<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2018/1/4
 * Time: 下午 02:56
 */

use app\classes\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '审核管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库费用确认单审核'];
$this->title = '仓库费用确认单审核';
?>

<?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
<style>
    .label-width{

        width: 80px;
    }
    .value-width{
        width: 160px;
    }
</style>
<div class="content">

    <input type="hidden" name="id" value="<?= $id ?>">
    <?php foreach ($model as $key => $val) { ?>


    <?php } ?>

    <h1 class="head-first">
        仓库预警信息
        <span class="head-code">编号：<?= $inv_id ?></span>
    </h1>
    <div class="mb-30" style="width: 100%;">
        <div class="border-bottom mb-10">
            <?= Menu::isAction('/system/verify-record/audit-pass') ? Html::button('通过', ['class' => 'button-blue width-80', 'id' => 'pass']) : '' ?>
            <?= Menu::isAction('/system/verify-record/audit-reject') ? Html::button('驳回', ['class' => 'button-blue width-80', 'id' => 'reject']) : '' ?>
            <?= Menu::isAction('/system/verify-record/index') ? Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
        </div>
        <div class="space-10"></div>
        <div class="space-10"></div>
        <h2 class="head-second" style="text-align: left;margin-top: -10px;margin-bottom: 10px;">
            库存预警信息详情
        </h2>

        <table class="product-list" style="width:100%">
            <thead>
            <tr>
                <th style="width:3%">序号</th>
                <th style="width:15%">仓库名称</th>
                <th style="width:15%">商品料号</th>
                <th style="width:8%">商品名称</th>
                <th style="width:8%">库存下限</th>
                <th style="width:8%">安全库存</th>
                <th style="width:8%">现有库存</th>
                <th style="width:8%"
                ">库存上限</th>
                <th style="width:15%">备注</th>
            </tr>
            </thead>
            <tbody>
            <?php $int = 1 ?>
            <?php foreach ($model as $key => $val) { ?>
                <tr>

                </tr>
                <?php $int = $int + 1 ?>
            <?php } ?>
            </tbody>
            </thead>
        </table>


        <h2 class="head-second" style="margin-top: 10px;margin-bottom: 10px;">
            审核路径
        </h2>
        <div class="mb-30" style="width: 100%;">
            <div class="mb-20" style="width:100%;">
                <div id="record" style="width: 100%;"></div>
            </div>
        </div>

    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        "use strict";
        $("#record").datagrid({
            url: "<?= Url::to(['/system/verify-record/load-record']);?>?id=" + <?= $id ?>,
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {
                    field: "verifyOrg", title: "审核节点", width: 150
                },
                {field: "verifyName", title: "审核人", width: 150},
                {
                    field: "vcoc_datetime", title: "审核时间", width: 156
                },
                {field: "verifyStatus", title: "操作", width: 150},
                {
                    field: "vcoc_remark", title: "审核意见", width: 200
                },
                {
                    field: "vcoc_computeip", title: "审核IP", width: 150
                },

            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });
        $("#pass").on("click", function () {
            layer.confirm("是否通过?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        data: $("#check-form").serialize(),
                        url: "<?= Url::to(['/system/verify-record/audit-pass']) ?>",
                        success: function (msg) {
                            if (msg.flag == 1) {
                                layer.alert(msg.msg, {icon: 1}, function () {
                                    parent.window.location.href = '<?= Url::to(['index']) ?>'
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (data) {
//                            console.log('data: ',data)
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });
//        $("#reject").one("click", function () {
//            $("#check-form").attr('action','<?//= Url::to(['/system/verify-record/audit-reject']) ?>//');
//            return ajaxSubmitForm($("#check-form"));
//        });
        $("#reject").on("click", function () {
            layer.confirm("是否驳回?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        data: $("#check-form").serialize(),
                        url: "<?= Url::to(['/system/verify-record/audit-reject']) ?>",
                        success: function (msg) {
                            if (msg.flag == 1) {
                                layer.alert(msg.msg, {icon: 1}, function () {
                                    parent.window.location.href = '<?= Url::to(['index']) ?>'
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        });





    })
    ;

</script>






