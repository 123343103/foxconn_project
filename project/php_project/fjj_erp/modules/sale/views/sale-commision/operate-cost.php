<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Progress;

$this->params['homeLike'] = ['label'=>'销售管理','url' => Url::to([''])];
$this->params['breadcrumbs'][] = ['label' => '销售汇总表（计算提成金额）', 'url' => Url::to([''])];

// 配置各个角色提成系数(这里需要优化)
$client_manager = !empty($roles[0]['sarole_qty']) ? $roles[0]['sarole_qty'] : 'empty';      // 客户经理
$salesman = !empty($roles[1]['sarole_qty']) ? $roles[1]['sarole_qty'] : 'empty';            // 业务员
$store_manager = !empty($roles[2]['sarole_qty']) ? $roles[2]['sarole_qty'] : 'empty';       // 店长
$provence_manager = !empty($roles[3]['sarole_qty']) ? $roles[3]['sarole_qty'] : 'empty';    // 省长
$assistant = !empty($roles[4]['sarole_qty']) ? $roles[4]['sarole_qty'] : 'empty';           // 业务助理
$product_manager = !empty($roles[5]['sarole_qty']) ? $roles[5]['sarole_qty'] : 'empty';     // 商品经理
?>
<div class="content">
    <?= $this->render('_searchSum', [
        'stores' => $stores,
    ]) ?>

    </br>
    <div id="data"></div>
    </br>
    <?= Html::button('上一步', ['class' => 'button-blue-big ml-50', 'id' => 'showDiv2', 'onclick'=>'window.location.href="'.Url::to(['store-cost']).'"']) ?>
    <?= Html::button('计算', ['class' => 'button-blue-big ml-50', 'id' => 'showDiv', 'href'=>'#inline']) ?>
</div>

<!--导入弹窗-->
<div style="display:none">
    <div id="inline" style="width:500px; height:150px; overflow:auto">
        <div class="pop-head">
            <p>正在计算。。。</p>
        </div>
        <div class="mt-40">

            <?php $form = ActiveForm::begin([
                'action' => ['/sale/sale-commision/calculate'],
                'method' => 'get',
            ]); ?>

            <div class="progress progress-striped active">
                <?php $form1 = Progress::begin(); ?>
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 20%;">
                    <span class="sr-only">20% 完成</span>
                </div>
                <?php $form1 = Progress::end(); ?>
            </div>
        </div>
        <div class="space-10"></div>
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

                {field: "operation_cost", title: "业务费用", width: 80},

                {field: "indirect_cost", title: "间接人力薪资", width: 50},
                {field: "direct_cost", title: "直接人力薪资", width: 100},

                {field: "fixed_cost", title: "固定费用", width: 50},

                {field: "profit", title: "利润", width: 50},
                {field: "profit_margin", title: "利润率", width: 60},
                {field: "ticheng", title: "提成金额", width: 60},
                {field: "ticheng1", title: "提成1", width: 60},
                {field: "ticheng2", title: "提成2", width: 60},
                {field: "ticheng3", title: "提成3", width: 60},
                {field: "ticheng4", title: "提成4", width: 60},
                {field: "sale_cost1", title: "业务助理", width: 60},
                {field: "sale_cost1", title: "客户经理", width: 60},
                {field: "sale_cost1", title: "商品经理", width: 60},
                {field: "sale_cost1", title: "销售", width: 60},
                {field: "sale_cost1", title: "店长", width: 60},
                {field: "sale_cost1", title: "省长", width: 60},
                {field: "sale_cost1", title: "店长减项", width: 60},
                {field: "sale_cost1", title: "省长减项", width: 60}
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
            title:'数据导入导出',
        })

        $("#showDiv").click(function () {
            setTimeout(function () {
                $(".progress-bar-success").width("50%");
            },1000);
            setTimeout(function () {
                $(".progress-bar-success").width("90%");
            },2000);
            setTimeout(function () {
                $("#data").datagrid({
                    url: "<?= Url::to(['/sale/sale-commision/calculate','SaleDetailsSumSearch[month]'=>!empty($queryParam) ?$queryParam['SaleDetailsSumSearch']['month']:null,'SaleDetailsSumSearch[store]'=>!empty($queryParam) ?$queryParam['SaleDetailsSumSearch']['store']:null,'SaleDetailsSumSearch[seller]'=>!empty($queryParam) ?$queryParam['SaleDetailsSumSearch']['seller']:null])?>",
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

                        {field: "operation_cost", title: "业务费用", width: 80},

                        {field: "indirect_cost", title: "间接人力薪资", width: 50},
                        {field: "direct_cost", title: "直接人力薪资", width: 100},

                        {field: "fixed_cost", title: "固定费用", width: 50},

                        {field: "profit", title: "利润", width: 50},
                        {field: "profit_margin", title: "利润率", width: 60},
                        {field: "ticheng", title: "提成金额", width: 60, formatter: function (value, row, index) {
                                if (row.ticheng1 || row.ticheng2 || row.ticheng3 || row.ticheng4) {
                                    return row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4;
                                } else {
                                    return null;
                                }
                            }
                        },
                        {field: "ticheng1", title: "提成1", width: 60},
                        {field: "ticheng2", title: "提成2", width: 60},
                        {field: "ticheng3", title: "提成3", width: 60},
                        {field: "ticheng4", title: "提成4", width: 60},
                        {field: "assistant", title: "业务助理", width: 60, formatter: function (value, row, index) {
                                if ('<?= $assistant ?>' != 'empty') {
                                    return (row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4)*<?= $assistant ?>;
                                }
                            }
                        },
                        {field: "client_manager", title: "客户经理", width: 60, formatter: function (value, row, index) {
                                if ('<?= $client_manager ?>' != 'empty') {
                                    return (row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4)*<?= $client_manager ?>;
                                }
                            }
                        },
                        {field: "product_manager", title: "商品经理", width: 60, formatter: function (value, row, index) {
                                if ('<?= $product_manager ?>' != 'empty') {
                                    return (row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4)*<?= $product_manager ?>;
                                }
                            }
                        },
                        {field: "salesman", title: "销售", width: 60, formatter: function (value, row, index) {
                                if ('<?= $salesman ?>' != 'empty') {
                                    return (row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4)*<?= $salesman ?>;
                                }
                            }
                        },
                        {field: "store_manager", title: "店长", width: 60, formatter: function (value, row, index) {
                                if ('<?= $store_manager ?>' != 'empty') {
                                    return (row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4)*<?= $store_manager ?>;
                                }
                            }
                        },
                        {field: "provence_manager", title: "省长", width: 60, formatter: function (value, row, index) {
                                if ('<?= $provence_manager ?>' != 'empty') {
                                    return (row.ticheng1 + row.ticheng2 + row.ticheng3 + row.ticheng4)*<?= $provence_manager ?>;
                                }
                            }
                        },
                        {field: "default", title: "店长减项", width: 60},
                        {field: "default", title: "省长减项", width: 60}
                    ]],
                    onLoadSuccess: function () {
                        setMenuHeight();
                        $(".progress-bar-success").width("100%");
                        $("#showDiv").attr('disabled',true);
                        parent.$.fancybox.close();
                    }
                });
            },3000);
        })
    })
</script>
