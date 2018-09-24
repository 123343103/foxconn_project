<?php
/**
 * User: F1677929
 * Date: 2017/6/30
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title='其他入库单审核';
$this->params['homeLike']=['label'=>'主页','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'审核列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <?php ActiveForm::end(); ?>
    <h1 class="head-first"><?=$this->title?></h1>
    <div class="mb-10">
        <?= Menu::isAction('/system/verify-record/audit-pass')?Html::button('通过', ['class' => 'button-blue width-80','id'=>'pass']):'' ?>
        <?= Menu::isAction('/system/verify-record/audit-reject')?Html::button('驳回', ['class' => 'button-blue width-80','id' => 'reject']):'' ?>
        <?= Menu::isAction('/system/verify-record/index')?Html::button('切换列表', ['class' => 'button-blue width-100', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']):'' ?>
    </div>
    <h1 class="head-second">其他入库单信息</h1>
    <div style="margin:0 0 20px 40px;">
        <span style="width:65px;">入库单号：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['invh_code']?></span>
        <span style="width:65px;">单据类型：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['business_type_desc']?></span>
        <span style="width:65px;">关联单号：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['invh_aboutno']?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:65px;">入仓仓库：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['wh_name']?></span>
        <span style="width:65px;">仓库代码：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['wh_code']?></span>
        <span style="width:65px;">仓库属性：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['warehouseAttr']?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:65px;">送货人：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['invh_sendperson']?></span>
        <span style="width:65px;">联系方式：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['invh_sendaddress']?></span>
        <span style="width:65px;">收货日期：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['invh_date']?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:65px;">收货人：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['consigneeCode'].$data['stockInInfo']['consigneeName']?></span>
        <span style="width:65px;">联系方式：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['consigneeMobile']?></span>
    </div>
    <div style="margin:0 0 20px 40px;">
        <span style="width:65px;">备注：</span>
        <span style="width:65px;vertical-align:middle;"><?=$data['stockInInfo']['invh_remark']?></span>
    </div>
    <div style="margin:0 0 30px 40px;">
        <span style="width:65px;">制单人：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['createBy']?></span>
        <span style="width:65px;">制单时间：</span>
        <span style="width:200px;"><?=$data['stockInInfo']['cdate']?></span>
    </div>
    <h1 class="head-second">入库商品信息</h1>
    <div style="overflow:auto;">
        <table class="table" style="width:1380px;">
            <thead>
            <tr>
                <th style="width:30px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:150px;">品名</th>
                <th style="width:100px;">品牌</th>
                <th style="width:150px;">规格型号</th>
                <th style="width:100px;">送货数量</th>
                <th style="width:100px;">实收数量</th>
                <th style="width:100px;">入仓数量</th>
                <th style="width:100px;">单位</th>
                <th style="width:100px;">包装方式</th>
                <th style="width:100px;">包装件数</th>
                <th style="width:100px;">存放储位</th>
                <th style="width:100px;">收货批次</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($data['productInfo'])){?>
                <?php foreach($data['productInfo'] as $key=>$val){?>
                    <tr>
                        <td><?=$key+1?></td>
                        <td><?=$val['pdt_no']?></td>
                        <td><?=$val['pdt_name']?></td>
                        <td><?=$val['BRAND_NAME_CN']?></td>
                        <td><?=$val['ATTR_NAME']?></td>
                        <td><?=$val['in_quantity']?></td>
                        <td><?=$val['real_quantity']?></td>
                        <td><?=$val['in_warehouse_quantity']?></td>
                        <td><?=$val['unit_name']?></td>
                        <td><?=$val['pack_type']?></td>
                        <td><?=$val['pack_quantity']?></td>
                        <td><?=$val['part_code']?></td>
                        <td><?=$val['batch_no']?></td>
                    </tr>
                <?php }?>
            <?php }?>
            </tbody>
        </table>
    </div>
    <?php if(!empty($data['checkInfo'])){?>
        <h1 class="head-second mt-30">签核记录</h1>
        <table class="table">
            <thead>
            <tr>
                <th style="width:30px;">#</th>
                <th style="width:150px;">签核节点</th>
                <th style="width:150px;">签核人员</th>
                <th style="width:150px;">签核日期</th>
                <th style="width:150px;">操作</th>
                <th style="width:150px;">签核意见</th>
                <th style="width:150px;">签核人IP</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data['checkInfo'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td><?=$val['organization_code']?></td>
                    <td><?=$val['staff_name']?></td>
                    <td><?=$val['vcoc_datetime']?></td>
                    <td><?=$val['checkStatus']?></td>
                    <td><?=$val['vcoc_remark']?></td>
                    <td><?=$val['vcoc_computeip']?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    <?php }?>
</div>
<script>
    $(function(){
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
    })
</script>
