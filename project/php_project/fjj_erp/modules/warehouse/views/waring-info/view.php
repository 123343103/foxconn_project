<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/26
 * Time: 上午 10:11
 */
use app\classes\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

$id = Yii::$app->user->identity->staff_id;
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '库存预警信息查询', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '库存预警申请详情'];
$this->title = '库存预警申请详情';
?>

<style>
    .label-width{
        width: 80px;
    }
    .value-width
    {
        width: 140px;
    }
</style>
<div class="content">

    <h1 class="head-first">
        库存预警申请详情
        <span class="head-code">预警编号：<?= $model['rows'][0]['inv_id'] ?></span>
    </h1>
    <div class="mb-30">
        <div class="border-bottom" style="margin-bottom: 20px;">
                <?php if($model['rows'][0]['so_type']==1 || $model['rows'][0]['so_type']==30) { ?>
                <?= Menu::isAction('edit') ? Html::button('修改', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["edit"]) . '?biw_h_pkid=' . $model['rows'][0]['biw_h_pkid'] .  '\'']) : '' ?>
                <?= Menu::isAction('reviewer') ? Html::button('送审', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'id' => 'check']) : '' ?>
                <?php } ?>
            <?= Menu::isAction('index') ? Html::button('切换列表', ['class' => 'button-blue mb-10', 'style' => 'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) : '' ?>
        </div>
        <h2 class="head-second" style="text-align: left;margin-bottom: 10px;">
            申请信息
        </h2>
        <div style="width:100%;">
            <?php $int = 1 ?>
            <div class="mb-10" style="padding-left: 15px;">
                <span><label class="label-width">预警编号：</label></span>
                <span class="value-width"><?=$model['rows'][0]['inv_id']?></span>
                <span><label class="label-width">申请人：</label></span>
                <span><?=$model['rows'][0]['staff_code']?>&nbsp;&nbsp;<?=$model['rows'][0]['staff_name']?></span>
            </div>
            <div style="padding-left: 15px;">
                <span><label class="label-width">申请部门：</label></span>
                <span class="value-width"><?=$model['rows'][0]['organization_name']?></span>
                <span><label class="label-width">申请日期：</label></span>
                <span><?=date('Y/m/d',strtotime($model['rows'][0]['OPP_DATE']))?></span>
            </div>

        </div>
        <div class="space-10"></div>
        <h2 class="head-second" style="text-align: left;">
            库存预警设置信息
        </h2>
        <div id="data"></div>




    </div>
</div>
<script>
    $(function () {
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "inv_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
            fitColumns: true,
            columns: [[

                {field: "wh_name", title: "仓库名称", width: "10%"},
                {field: "pdt_name", title: "商品名称", width: "12%"},
                {field: "part_no", title: "商品料号", width: "15%"},
                {field: "tp_spec", title: "规格型号", width: "15%"},
                {field: "down_nums", title: "库存下限", width: "10%"},
                {field: "save_num", title: "安全库存", width: "10%"},
                {field: "invt_num", title: "现有库存", width: "10%"},
                {field: "up_nums", title: "库存上限", width: "10%"},
                {field: "remarks", title: "备注", width: "20%"},
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
            },

        });
//        $("#record").datagrid({
//            url: "<?//= Url::to(['/system/verify-record/load-record']);?>//?id=" + <?//= $id ?>//,
//            rownumbers: true,
//            method: "get",
//            idField: "vcoc_id",
//            loadMsg: false,
////                    pagination: true,
//            singleSelect: true,
////                    pageSize: 5,
////                    pageList: [5, 10, 15],
//            columns: [[
//                {
//                    field: "verifyOrg", title: "审核节点", width: 150
//                },
//                {field: "verifyName", title: "审核人", width: 150},
//                {
//                    field: "vcoc_datetime", title: "审核时间", width: 156
//                },
//                {field: "verifyStatus", title: "操作", width: 150},
//                {
//                    field: "vcoc_remark", title: "审核意见", width: 200
//                },
//                {
//                    field: "vcoc_computeip", title: "审核IP", width: 150
//                },
//
//            ]],
//            onLoadSuccess: function (data) {
//                showEmpty($(this),data.total,0);
//            }
//        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });
        //送审
        $("#check").on("click", function () {
            var id = <?=$model['rows'][0]['biw_h_pkid']?> + "-";
                var url = "<?=Url::to(['view'])?>?biw_h_pkid=" + id;
                $.fancybox({
                    href: "<?=Url::to(['reviewer'])?>?type=" + <?=$typeId?> + "&id=" + id + "&url=" + url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
        });

    })
    ;


</script>






