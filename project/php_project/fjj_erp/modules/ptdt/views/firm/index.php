<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/**
 *
 */
$this->title = '厂商信息列表';
$this->params['homeLike'] = ['label' => '商品开发管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '厂商信息列表', 'url' => ""];

$get = Yii::$app->request->get();
if(!isset($get['PdFirmQuery'])){
    $get['PdFirmQuery']=null;
}
?>

<div class="content">
    <div class="search-div">
        <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]
        ); ?>
        <div class="mb-10">
            <div class="inline-block ">
                <label class="width-120 ml-5" for="pdfirmquery-firm_sname">厂商注册名称</label>
                <input type="text" id="pdfirmquery-firm_sname" class="width-150" name="PdFirmQuery[firm_sname]"
                       value="<?=$get['PdFirmQuery']['firm_sname'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ">
                <label class="width-100" for="pdfirmquery-firm_brand">品牌</label>
                <input type="text" id="pdfirmquery-firm_brand" class="width-150" name="PdFirmQuery[firm_brand]"
                       value="<?=$get['PdFirmQuery']['firm_brand'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block">
                <label class="width-100" for="pdfirmquery-firm_type">厂商类型</label>
                <select id="pdfirmquery-firm_type" class="width-150" name="PdFirmQuery[firm_type]">
                    <option value="">请选择</option>
                    <?php foreach ($firmType as $key => $val) {?>
                        <option value="<?=$key ?>" <?= isset($get['PdFirmQuery']['firm_type'])&&$get['PdFirmQuery']['firm_type']==$key?"selected":null ?>><?= $val ?></option>
                    <?php } ?>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="space-10"></div>
        <div class="mb-10 ml-5">
            <div class="inline-block">
                <label class="width-120" for="pdfirmquery-firm_issupplier">是否为集团供应商</label>
                <select id="pdfirmquery-firm_issupplier" class="width-150" name="PdFirmQuery[firm_issupplier]">
                    <option value="">请选择...</option>
                    <option value="1" <?= isset($queryParam['firm_issupplier'])&&$queryParam['firm_issupplier']=='1'?"selected":null ?>>是</option>
                    <option value="0" <?= isset($queryParam['firm_issupplier'])&&$queryParam['firm_issupplier']=='0'?"selected":null ?>>否</option>
                </select>
                <div class="help-block"></div>
            </div>
            <div class="inline-block">
                <label class="width-100" for="pdfirmquery-firm_status">厂商状态</label>
                <select id="pdfirmquery-firm_status" class="width-150" name="PdFirmQuery[firm_status]">
                    <option value="">请选择</option>
                    <option value="10" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='10'?"selected":null ?>>新增厂商</option>
                    <option value="20" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='20'?"selected":null ?>>拜访中</option>
                    <option value="30" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='30'?"selected":null ?>>拜访完成</option>
                    <option value="40" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='40'?"selected":null ?>>谈判中</option>
                    <option value="50" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='50'?"selected":null ?>>谈判完成</option>
                    <option value="60" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='50'?"selected":null ?>>呈报中</option>
                    <option value="70" <?= isset($queryParam['firm_status'])&&$queryParam['firm_status']=='50'?"selected":null ?>>开发完成</option>
                </select>
                <div class="help-block"></div>
            </div>
           <div class="inline-block">
            <label class="width-100" for="pdfirmquery-firm_category_id">分级分类</label>
            <select id="pdfirmquery-firm_category_id" class="width-150" name="PdFirmQuery[firm_category_id]">
                <option value="">请选择</option>
                <?php foreach ($firmCategoryToValue as $key => $val) {?>
                    <option value="<?=$key ?>" <?= isset($get['PdFirmQuery']['firm_category_id'])&&$get['PdFirmQuery']['firm_category_id']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>
            <div class="help-block"></div>
        </div>
            <?= Html::submitButton('查询', ['class' => 'button-blue ml-10']) ?>
            <?= Html::resetButton('重置', ['class' => 'button-blue', 'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["index"]).'\'']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="space-40 mb-10"></div>
    <div class="table-head">
        <div class="table-head">
            <p class="head">厂商信息列表</p>
            <p class="float-right">
                <?=\app\classes\Menu::isAction("/ptdt/firm/create")?Html::a("<span class='text-center ml--5'>新增</span>",Url::to(['create'])):""?>
                <?=\app\classes\Menu::isAction("/ptdt/firm/update")?Html::a("<span class='text-center ml--5'>修改</span>","javascript:void(0)",["id"=>"update"]):""?>
                <?=\app\classes\Menu::isAction("/ptdt/firm/delete")?Html::a("<span class='text-center ml--5'>删除</span>","javascript:void(0)",["id"=>"delete"]):""?>
                <?=\app\classes\Menu::isAction("/ptdt/firm/view")?Html::a("<span class='text-center ml--5'>详情</span>","javascript:void(0)",["id"=>"viewOne"]):""?>
                <?=\app\classes\Menu::isAction("/ptdt/visit-plan/add")?Html::a("<span class='text-center width-100 ml--5'>新增拜访计划</span>","javascript:void(0)",["id"=>"add-plan"]):""?>
                <?=\app\classes\Menu::isAction("/ptdt/visit-resume/add")?Html::a("<span class='text-center width-100 ml--5'>新增拜访履历</span>","javascript:void(0)",["id"=>"add-resume"]):""?>
                <?=\app\classes\Menu::isAction("/ptdt/firm-negotiation/create")?Html::a("<span class='text-center width-100 ml--5'>新增谈判履历</span>","javascript:void(0)",["id"=>"add-negotiation"]):""?>
                <?= Html::a("<span class='text-center ml--5'>返回</span>", \yii\helpers\Url::to(['/index/index'])) ?>
            </p>
        </div>
    </div>
    <div class="space-10"></div>

    <div id="data">
    </div>
    <div id="load-content" class="overflow-auto"></div>
</div>
<script>
    $(function () {
        "use strict";
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            idField: "firm_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [5,10,15],
            columns: [[
                <?= $columns ?>
//                {field: "firmType", title: "类型"},
//                {field: "firm_code", title: "单据编号"},
//                {field: "firm_sname", title: "注册公司名称", width: 80},
//                {field: "firm_shortname", title: "简称", width: 60},
//                {field: "firm_brand", title: "品牌", width: 60},
//                {field:"firm_type", title: "公司类型", width: 100, formatter: function (value, row, index) {
//                    if (row.firmType) {
//                        return row.firmType;
//                    } else {
//                        return '';
//                    }
//                }
//                },
//                {field:"firmSource", title: "来源", width: 100, formatter: function (value, row, index) {
//                    if (row.firmSource) {
//                        return row.firmSource;
//                    } else {
//                        return '';
//                    }
//                }
//                },
//                {field:"firm_position", title: "地位", width: 100, formatter: function (value, row, index) {
//                    if (row.firmPosition) {
//                        return row.firmPosition;
//                    } else {
//                        return '';
//                    }
//                }
//                },
//                {field:"firm_issupplier", title: "是否为集团供应商", width: 60, formatter: function (value, row, index) {
//                    if (row.firm_issupplier == 1) {
//                        return "是";
//                    } else {
//                        return "否";
//                    }
//                }
//                },
//                {field: "category", title: "分级分类", width: 150},
//                {field:"firm_status", title: "厂商状态", width: 60, formatter: function (value, row, index) {
//                    if (row.firm_status == 10) {
//                        return "新增厂商";
//                    } else if(row.firm_status == 20){
//                        return "拜访中";
//                    }else if(row.firm_status == 30){
//                        return '拜访完成';
//                    }else if (row.firm_status == 40){
//                        return '谈判中';
//                    }else if (row.firm_status == 50){
//                        return '谈判完成';
//                    }else if (row.firm_status == 60){
//                        return '呈报中';
//                    }else if (row.firm_status == 70){
//                        return '开发完成';
//                    }
//                }
//                },
            ]],
            onSelect: function (rowIndex, rowData) {    //选择触发事件
                var id = rowData['firm_id'];
            },
            onLoadSuccess: function (data) {
                setMenuHeight();
                showEmpty($(this),data.total,0);
                // datagrid 加省略号提示信息
                datagridTip('#data');
            }
        });
        //增刪改查操作
//        selectable(true);
        /**
         * 增加
         * 1.選擇器
         * 2.url
         */
        //createButton($("#create"),"<?=Url::to(['create'])?>",{parameters: "pdnId"});
        //刪除
//        deleteById($("#delete"),"<?//=Url::to(['delete']) ?>//");
        $("#delete").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条信息",{icon:2,time:5000});
            } else {
                var selectId = $("#data").datagrid("getSelected")['firm_id'];
                $.ajax({
                    type: "get",
                    dataType: "json",
                    data: {"id": selectId},
                    url: "<?=Url::to(['/ptdt/firm/delete-count']) ?>",
                    success: function (msg) {
                        if( msg === 'false'){
                            layer.alert('无法删除',{icon:2})
                        }else{
                            layer.confirm("确定要删除这条信息吗?",
                                {
                                    btn:['确定', '取消'],
                                    icon:2
                                },
                                function () {
                                    $.ajax({
                                        type: "get",
                                        dataType: "json",
                                        data: {"id": selectId},
                                        url: "<?=Url::to(['/ptdt/firm/delete']) ?>",
                                        success: function (msg) {
                                            if( msg.flag === 1){
                                                layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                            }else{
                                                layer.alert(msg.msg,{icon:2})
                                            }
                                        },
                                        error :function(msg){
                                            layer.alert(msg.msg,{icon:2})
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
            }
        });
        //更新
        //updateButton($("#update"),"<?=Url::to(['update'])?>",{parameters: "id"});
        $("#update").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['firm_id'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        })
        //查看
        $("#viewOne").on("click",function(){
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['firm_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        })

        //新增廠商拜訪計劃
        $("#add-plan").click(function(){
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                window.location.href="<?= Url::to(['/ptdt/visit-plan/add']) ?>";
            }else{
                var planId = $("#data").datagrid("getSelected")['firm_id'];
                window.location.href="<?= Url::to(['/ptdt/visit-plan/add']) ?>?id="+planId;
            }
        })

        $("#add-resume").on("click",function () {
            var b = $("#data").datagrid("getSelected");
            if (b == null){
                window.location.href="<?= Url::to(['/ptdt/visit-resume/add']) ?>";
            }else {
                var resumeId = $("#data").datagrid('getSelected')['firm_id'];
                window.location.href = "<?=Url::to(['/ptdt/visit-resume/add'])?>?firmId="+resumeId;
            }
        });

        $("#add-negotiation").on("click",function () {
            var c = $("#data").datagrid("getSelected");
            if (c == null){
                window.location.href="<?= Url::to(['/ptdt/firm-negotiation/create']) ?>";
            }else {
                var negotiationId = $("#data").datagrid('getSelected')['firm_id'];
                window.location.href = "<?=Url::to(['/ptdt/firm-negotiation/create'])?>?firmId="+negotiationId ;
            }
        });
    });
</script>
