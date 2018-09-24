<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/11
 * Time: 上午 09:59
 */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title='报废单详情页';
$this->params['homeLike']=['label'=>'仓储物流管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'库存报废','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=['label'=>'报废申请查询','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .width-150{width: 150px;}
    .width-40{width: 40px;}
</style>

<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
<!--    <input type="hidden" name="id" value="--><?//= $id ?><!--">-->
    <input type="hidden" class="_ids" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h1 class="head-first"><?=$this->title?></h1>
    <div class="border-bottom mb-20 pb-10">
        <?= Html::button('通过', ['class' => 'button-blue width-80','id'=>'pass']) ?>
        <?= Html::button('驳回', ['class' => 'button-blue width-80','id' => 'reject']) ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
    </div>
    <h1 class="head-second">报废申请信息</h1>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center" width="13%">报废单号:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['chh_code'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center" width="13%">报废单状态:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['chh_status'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center" width="13%">报废类别:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['chh_typeName'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center" width="13%">报废仓库:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['wh_name'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center" width="13%">申请人:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['create_by'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center" width="13%">申请日期:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['create_at'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <table width="90%" class="no-border vertical-center ml-25 mb-20">
        <tr class="no-border">
            <td class="no-border vertical-center" width="13%">制单人:</td>
            <td class="no-border vertical-center" width="18%"><?= $model[0]['review_by'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
            <td class="no-border vertical-center" width="13%">制单时间:</td>
            <td class="no-border vertical-center" width="40%"><?= $model[0]['review_at'] ?></td>
            <td class="no-border vertical-center" width="4%"></td>
        </tr>
    </table>
    <h1 class="head-second">入库商品信息</h1>
    <div style="overflow:auto;">
        <table class="table" style="width: 2500px">
            <thead>
            <tr>
                <th class="width-40">序号</th>
                <th class="width-150">料号</th>
                <th class="width-150">品名</th>
                <th class="width-150">类别</th>
                <th class="width-150">规格型号</th>
                <th class="width-150">批次</th>
                <th class="width-150">单位</th>
                <th class="width-150">当前储位</th>
                <th class="width-150">现有库存</th>
                <th class="width-150">报废数量</th>
                <th class="width-150">报废方式</th>
                <th class="width-250">报废原因</th>
                <th class="width-150">存放库存</th>
                <th class="width-150">存放储位</th>
                <th class="width-150">资产元值(元)</th>
                <th class="width-150">处理价(元)</th>
                <?php foreach ($columns as $key => $val) { ?>
                    <th><p class="text-center width-150 color-w"><?= $val["field_title"] ?></p></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($model[1])){?>
                <?php foreach($model[1] as $key=>$val){?>
                    <?php if($val['mode']==0){
                        $mode="垃圾回收";
                    }else if($val['mode']==1){
                        $mode="销毁";
                    }else if($val['mode']==2){
                        $mode="废料变卖";
                    }else if($val['mode']==4){
                        $mode="低价转让";
                    }?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$val['pdt_no']?></td>
                        <td><?=$val['pdt_name']?></td>
                        <td><?=$val['catg_name']?></td>
                        <td><?=$val['tp_spec']?></td>
                        <td><?=$val['chl_bach']?></td>
                        <td><?=$val['unit']?></td>
                        <td><?=$val['st_id']?></td>
                        <td><?=$val['before_num1']?></td>
                        <td><?= sprintf("%.2f", $val['chl_num'])?></td>
                        <td><?= $mode?></td>
                        <td><?=$val['chh_remark']?></td>
                        <td><?=$val['wh_id2']?></td>
                        <td><?=$val['st_id2']?></td>
                        <td><?=$val['']?></td>
                        <td><?=$val['deal_price']?></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <div class="mb-20" style="overflow: auto; margin-top: 10px">
        <?php if (!empty($verify)){ ?>
        <div>
            <h2 class="head-second color-1f7ed0">
                <i class="icon-caret-right"></i>
                <a href="javascript:void(0)">签核信息</a>
            </h2>
            <div>
                <table class="mb-30 product-list" style="width:990px;">
                    <thead>
                    <tr>
                        <th class="width-60">#</th>
                        <th class="width-70">签核节点</th>
                        <th class="width-60">签核人员</th>
                        <th>签核日期</th>
                        <th class="width-60">操作</th>
                        <th>签核意见</th>
                        <th>签核人IP</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($verify as $key => $val) { ?>
                        <tr>
                            <th><?= $key + 1 ?></th>
                            <th><?= $val['verifyOrg'] ?></th>
                            <th><?= $val['verifyName'] ?></th>
                            <th><?= $val['vcoc_datetime'] ?></th>
                            <th><?= $val['verifyStatus'] ?></th>
                            <th><?= $val['vcoc_remark'] ?></th>
                            <th><?= $val['vcoc_computeip'] ?></th>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    $(function(){
//        $("#pass").on("click", function () {
//            layer.confirm("是否通过?",
//                {
//                    btn: ['确定', '取消'],
//                    icon: 2
//                },
//                function () {
//                    $.ajax({
//                        type: "post",
//                        dataType: "json",
//                        data: $("#check-form").serialize(),
//                        url: "<?//= Url::to(['/system/verify-record/audit-pass']) ?>//",
//                        success: function (msg) {
//                            if (msg.flag == 1) {
//                                layer.alert(msg.msg, {icon: 1}, function () {
//                                    parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                                });
//                            } else {
//                                layer.alert(msg.msg, {icon: 2})
//                            }
//                        },
//                        error: function (data) {
////                            console.log('data: ',data)
//                        }
//                    })
//                },
//                function () {
//                    layer.closeAll();
//                }
//            )
//        });
//        $("#reject").on("click", function () {
//            layer.confirm("是否驳回?",
//                {
//                    btn: ['确定', '取消'],
//                    icon: 2
//                },
//                function () {
//                    $.ajax({
//                        type: "post",
//                        dataType: "json",
//                        data: $("#check-form").serialize(),
//                        url: "<?//= Url::to(['/system/verify-record/audit-reject']) ?>//",
//                        success: function (msg) {
//                            if (msg.flag == 1) {
//                                layer.alert(msg.msg, {icon: 1}, function () {
//                                    parent.window.location.href = '<?//= Url::to(['index']) ?>//'
//                                });
//                            } else {
//                                layer.alert(msg.msg, {icon: 2})
//                            }
//                        }
//                    })
//                },
//                function () {
//                    layer.closeAll();
//                }
//            )
//        });

        //驳回
        $("#reject").on("click", function () {
            var idss = $("._ids").val();
            $.fancybox({
                href: "<?=Url::to(['opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });

        //通过
        $("#pass").on("click", function () {
            var idss = $("._ids").val();
            $.fancybox({
                href: "<?=Url::to(['pass-opinion'])?>?id=" + idss,
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
    })
</script>
