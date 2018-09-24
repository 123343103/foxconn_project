<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '库存预警/报废通知员详情', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '库存预警'];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员审核列表'];
$this->params['breadcrumbs'][] = ['label' => '库存预警人员申请审核'];
$this->title = '库存预警/报废通知人员详情';
?>
<div class="content">
    <?php $form =ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h2 class="head-first">
        <?= $this->title; ?>
    </h2>
    <div class="border-bottom mb-20 pb-10">
        <?= Menu::isAction('/system/verify-record/audit-pass')?Html::button('通过', ['class' => 'button-blue width-80','id'=>'pass']):'' ?>
        <?= Menu::isAction('/system/verify-record/audit-reject')?Html::button('驳回', ['class' => 'button-blue width-80','id' => 'reject']):'' ?>
        <?= Menu::isAction('/system/verify-record/index')?Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']):'' ?>
    </div>
    <div class="mb-30">
        <h2 class="head-second" style="text-align: center">
            <p>预警人员基本信息</p>
        </h2>
        <table width="80%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">工号:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_code']?></td>

                <td class="no-border vertical-center" width="13%">姓名:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_name']?></td>

                <td class="no-border vertical-center" width="13%">手机:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_mobile']?></td>
            </tr>
        </table>

        <table width="80%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">邮箱:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['staff_email']?></td>
                <td class="no-border vertical-center" width="13%">操作人:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['OPPER']?></td>
                <td class="no-border vertical-center" width="13%">最后操作时间:</td>
                <td class="no-border vertical-center" width="20%"><?= $hrstaffinfo['OPP_DATE']?></td>
            </tr>
        </table>

    </div>
    <div class="mb-30">
        <h2 class="head-second" style="text-align: center">
            <p>预警人员仓库信息</p>
        </h2>
        <div class="mb-10" >
            <label class="width-120 no-after" style="text-align: left">所负责的商品信息：</label>
            <table class="mb-10 " style="text-align: center">
                <tr class="height-30">
                    <th class="width-140">序号</th>
                    <th class="width-140">仓库</th>
                    <th class="width-140">商品类别</th>
                    <th class="width-140">料号</th>
                    <th class="width-140">商品名称</th>
                    <th class="width-140">品牌</th>
                    <th class="width-140">规格型号</th>
                    <th class="width-140">库存上限</th>
                    <th class="width-140">现有库存</th>
                    <th class="width-140">库存下限</th>
                </tr>
                <tbody>

                <?php foreach ($whinfo as $key=>$val){ ?>
                    <tr class="height-30">
                        <td><?= $key+=1 ?></td>
                        <td><?= $val['wh_name']?></td>
                        <td><?= $val['category_sname']?></td>
                        <td><?= $val['part_no']?></td>
                        <td><?= $val['pdt_name']?></td>
                        <td><?= $val['BRAND_NAME_CN']?></td>
                        <td><?= $val['pdt_model']?></td>
                        <td><?= $val['up_nums']?></td>
                        <td><?= $val['invt_num']?></td>
                        <td><?= $val['down_nums']?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div >
        <label>备注</label>
        <span><?= $hrstaffinfo['remarks']?></span>
    </div>
    <h2 class="head-three">
        <i class="icon-caret-down"></i>
        <a>审核路径</a>
    </h2>
    <div class="mb-30">
        <div class="mb-20" style="width:990px;">
            <div id="record"></div>
        </div>
    </div>

<div>
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
    $(".head-three").next("div:eq(0)").css("display", "block");
    $(".head-three>a").click(function () {
        $(this).parent().next().slideToggle();
        $(this).prev().toggleClass("icon-caret-right");
        $(this).prev().toggleClass("icon-caret-down");
    });
})
</script>

