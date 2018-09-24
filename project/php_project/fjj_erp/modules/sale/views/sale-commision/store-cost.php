<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Progress;
$this->params['homeLike'] = ['label'=>'销售管理','url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '销售汇总表（导入业务费用）', 'url' => Url::to([''])];
?>
<div class="content">
    <?= $this->render('_searchSum', [
        'stores' => $stores,
    ]) ?>

    </br>
    <div id="data"></div>
    </br>
    <?= Html::button('导入业务费用', ['class' => 'button-blue-big ml-50', 'id' => 'showDiv', 'href'=>'#inline']) ?>
    <?= Html::button('上一步', ['class' => 'button-blue-big ml-50', 'id' => 'showDiv2', 'onclick'=>'window.location.href="'.Url::to(['seller-cost']).'"']) ?>
    <?= Html::button('下一步', ['class' => 'button-blue-big ml-50', 'onclick'=>'window.location.href="'.Url::to(['operate-cost']).'"']) ?>
</div>

<!--导入弹窗-->
<div style="display:none">
    <div id="inline" style="width:500px; height:260px; overflow:auto">
        <div class="pop-head">
            <p>导入业务费用</p>
        </div>
        <div class="mt-40">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'action'=>['import-operate-cost'],
                'id'=>'fileForm',
                'fieldConfig' => [
                    'errorOptions'=>['class'=>'error-notice mt-10'],
                    'labelOptions'=>['class'=>'width-100'],
                    'inputOptions'=>['class' => 'width-200']
                ]
            ]); ?>
            <div class="ml-40">
                <div class="inline-block field-uploadForm">
                    <input type="hidden" name="UploadForm[file]" value=""><input type="file" id="uploadForm" class="width-200" name="UploadForm[file]">
                    <div class="error-notice mt-10"></div>
                </div>
                <?= Html::submitButton('确认',['class' =>'button-blue ml-20']) ?>&nbsp;
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<script>
    $(function () {
        //严格模式
        "use strict";

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "sdl_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10,20,30],
            columns: [[
                {field: "sdl_type", title: "省", width: 50},
                {field: "sroreinfo", title: "销售点", width: 60, formatter: function (value, row, index) {
                        if (row.storeInfo) {
                            return row.storeInfo.sts_sname;
                        } else {
                            return null;
                        }
                    }
                },
                {field: "sds_saname", title: "姓名", width: 135},
                {field: "roleinfo", title: "角色", width: 85, formatter: function (value, row, index) {
                        if (row.roleInfo) {
                            return row.roleInfo.sarole_sname;
                        } else {
                            return null;
                        }
                    }
                },
                {field: "bill_camount", title: "销单总金额"},
                {field: "sale_cost", title: "当期销单成本", width: 80},
                {field: "gross_profit", title: "毛利", width: 60},
                {field: "change_cost", title: "变动成本", width: 80}, // 第一步销售汇总数据结束

                {field: "default", title: "业务费用", width: 80},

                {field: "indirect_cost", title: "间接人力薪资", width: 50},
                {field: "direct_cost", title: "直接人力薪资", width: 100},

                {field: "fixed_cost", title: "固定费用", width: 50},

                {field: "default", title: "利润", width: 50},
                {field: "default", title: "利润率", width: 60},
                {field: "default", title: "提成金额", width: 60},
                {field: "default", title: "提成1", width: 60},
                {field: "default", title: "提成2", width: 60},
                {field: "default", title: "提成3", width: 60},
                {field: "default", title: "提成4", width: 60},
                {field: "default", title: "业务助理", width: 60},
                {field: "default", title: "客户经理", width: 60},
                {field: "default", title: "商品经理", width: 60},
                {field: "default", title: "销售", width: 60},
                {field: "default", title: "店长", width: 60},
                {field: "default", title: "省长", width: 60},
                {field: "default", title: "店长减项", width: 60},
                {field: "default", title: "省长减项", width: 60}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            }
        });

        ajaxSubmitForm($("#fileForm"));
        $("#showDiv").fancybox({
            padding : [],
            centerOnScroll:true,
            titlePosition:'over',
            title:'数据导入导出'
        });

    })
</script>
