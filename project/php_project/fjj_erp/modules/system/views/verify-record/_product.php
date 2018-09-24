<?php
/**
 * User: F1678086
 * Date: 2017/3/10
 * Time: 8:39
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \app\classes\Menu;

$this->title = "商品开发需求审核";
$this->params['homeLike'] = ['label' => '审核'];
$this->params['breadcrumbs'][] = ['label' => '待审核申请单列表', 'url' => Url::to(['/system/verify-record/index'])];
$this->params['breadcrumbs'][] = ['label' => '开发需求审核', 'url' => Url::to(['/system/verify-record/verify','id'=>$id])];
?>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <h1 class="head-first">
        <?= $this->title ?>
        <span class="head-code">编号：<?= $result['vco_code'] ?></span>
    </h1>
    <div class="border-bottom mb-20 pb-10">
        <?= Html::button('通过', ['class' => 'button-blue width-80','id'=>'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue width-80','id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>

    </div>
    <h1 class="head-second">商品经理人</h1>
    <div class="mb-20">
        <label class="width-120">开发中心</label>
        <span class="width-200"><?= $model->developCenterName ?></span>
        <label class="width-120">开发部</label>
        <span class="width-200"><?= $model->developDepartmentName ?></span>
        <label class="width-120">商品经理人</label>
        <span class="width-200"><?= $model->productManagerName->name ?>/<?= $model->productManagerName->code ?></span>
    </div>
    <div class="mb-20">
        <label class="width-120">需求类型</label>
        <span class="width-200"><?= $model->pdqSourceTypeName ?></span>
        <label class="width-120">开发类型</label>
        <span class="width-200"><?= $model->developTypeName ?></span>
        <label class="width-120">Commodity</label>
        <span class="width-200"><?= $model->commodityName ?></span>
    </div>
    <h1 class="head-second">商品基本信息</h1>
    <div class="mb-20">
        <table class="product-list" style="width:990px;">
            <thead>
            <tr>
                <th>序号</th>
                <th>商品名称</th>
                <th>商品规格型号</th>
                <th>商品定位</th>
                <th>一阶</th>
                <th>二阶</th>
                <th>三阶</th>
                <th>四阶</th>
                <th>五阶</th>
                <th>六阶</th>
                <th>商品要求</th>
                <th>制程要求</th>
                <th>品质要求</th>
                <th>备注</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model->products as $key => $val) { ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= $val->product_name ? $val->product_name : "" ?></td>
                    <td><?= $val->product_size ? $val->product_size : "" ?></td>
                    <td><?= $val->levelName ? $val->levelName : "" ?></td>
                    <td><?= $val->typeName[0] ? $val->typeName[0] : "" ?></td>
                    <td><?= $val->typeName[1] ? $val->typeName[1] : "" ?></td>
                    <td><?= $val->typeName[2] ? $val->typeName[2] : "" ?></td>
                    <td><?= $val->typeName[3] ? $val->typeName[3] : "" ?></td>
                    <td><?= $val->typeName[4] ? $val->typeName[4] : "" ?></td>
                    <td><?= $val->typeName[5] ? $val->typeName[5] : "" ?></td>
                    <td><?= $val->product_requirement ? $val->product_requirement : "" ?></td>
                    <td><?= $val->product_process_requirement ? $val->product_process_requirement : "" ?></td>
                    <td><?= $val->product_quality_requirement ? $val->product_quality_requirement : "" ?></td>
                    <td><?= $val->other_des ? $val->other_des : "" ?></td>
                </tr>
            <?php } ?>
            </tbody>
            </thead>
        </table>
    </div>
    <h1 class="head-second text-center">签核记录</h1>
    <div class="mb-20" style="width:990px;">
        <div id="record"></div>
    </div>
    <div class="mb-20">
        <label class="vertical-top">签核意见</label>
        <textarea style="width:920px;" name="remark" id="" cols="3" rows="3"></textarea>
    </div>
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(function () {
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
            onLoadSuccess: function () {
                showEmpty($(this),data.total,0);
            }
        });
//        $("#pass").one("click", function () {
//            $("#check-form").attr('action','<?//= \yii\helpers\Url::to(['/system/verify-record/audit-pass']) ?>//');
//            return ajaxSubmitForm($("#check-form"));
//        });
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
</script>

