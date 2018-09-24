<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/16
 * Time: 9:15
 */
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
use \yii\widgets\ActiveForm;

MultiSelectAsset::register($this);
$this->title = '我的客户';
$this->params['homeLike'] = ['label' => '客户关系管理'];
$this->params['breadcrumbs'][] = ['label' => '我的客户'];
?>
<style>
    .change {
        width: 480px;
        min-height: 130px;
        margin-right: 15px;
        margin-top: 15px;
    }

    .label-width {
        width: 80px;
    }

    .value-width {
        width: 200px;
    }
</style>
<div class="content">
    <div style="height:50px;">
        <div class="text-center float-left mr-10">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>
            <div class="inline-block">
                <label class="label-width qlabel-align" for="">关键字：</label>
                <input type="text" class="value-width qvalue-align" name="searchKeyword"
                       value="<?= $queryParam['searchKeyword'] ?>" placeholder="&nbsp;请输入查询信息">
            </div>
            <div class="inline-block">
                <button type="submit" class="search-btn-blue">查询</button>
                <button type="button" class="reset-btn-yellow"
                        onclick="window.location.href='<?= Url::to(['index']) ?>'">重置
                </button>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="float-right">
            <a href="<?= Url::to(['/crm/crm-customer-manage/module', 'id' => $manageId]) ?>" class="module">
                <div class='table-nav' style="width: 80px">
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>模块设置</p>
                </div>
                <p class="float-left">&nbsp;|&nbsp;</p>
            </a>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav' style="width: 80px">
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>
        </div>
    </div>

    <div class="mb-10" id="customer-manage">
        <div class="panel-header title-color">
            <div class="panel-title">
                <span class="mr-10">客户信息</span>
            </div>
            <div class="panel-tool">
                <a title="新增客户" href="<?= Url::to(['/crm/crm-customer-info/create']); ?>"><i
                        class="icon-plus-sign-alt"></i></a>
                <a title="栏位设置" href="<?= Url::to(['/crm/crm-customer-manage/sys-list']) ?>?id=200000027&str=客户信息"
                   class="update"><i class="icon-edit"></i></a>
            </div>
        </div>
        <div id="data"></div>
    </div>
    <div class="space-20"></div>
    <div class="overflow-auto">
        <div id="crd" class="mb-20 change float-left display-none">
            <div class="panel-header title-color">
                <div class="panel-title">
                    <span class="mr-10">CRD/PRD</span>
                </div>
                <div class="panel-tool">
                    <a style="color:grey;" title="新增需求" href="javascript:void(0)" ><i  class="icon-plus-sign-alt"></i></a>

<!--                    <a title="新增需求" href="--><?//= Url::to(['/ptdt/product-dvlp/add']); ?><!--"><i-->
<!--                            class="icon-plus-sign-alt"></i></a>-->
<!--                    <a title="栏位设置"-->
<!--                       href="--><?//= Url::to(['/crm/crm-customer-manage/sys-list']) ?><!--?id=1000000065&str=CRD/PRD"-->
<!--                       class="update"><i class="icon-edit"></i></a>-->
                    <a style="color:grey;" title="栏位设置" href="javascript:void(0)"><i class="icon-edit"></i></a>
                    <a title="删除模块" onclick="cancle_module('crd');"><i class="icon-remove panel-tool-a"></i></a>
                </div>
            </div>
            <div id="productDvlp"></div>
        </div>

        <div id="myApply" class="mb-20  change float-left display-none">
            <div class="panel-header title-color">
                <div class="panel-title">
                    <span class="mr-10">我的申请</span>
                </div>
                <div class="panel-tool">
                    <a title="栏位设置" href="<?= Url::to(['/crm/crm-customer-manage/sys-list']) ?>?id=1000000064&str=我的申请"
                       class="update"><i class="icon-edit"></i></a>
                    <a title="删除模块" onclick="cancle_module('myApply');" id="myApply"><i
                            class="icon-remove panel-tool-a"></i></a>
                </div>
            </div>
            <div id="apply">
            </div>
        </div>

        <div id="crmVisitPlan" class="mb-20 change float-left display-none">
            <div class="panel-header title-color">
                <div class="panel-title">
                    <span class="mr-10">拜访计划</span>
                </div>
                <div class="panel-tool">
                    <a title="新增计划" id="plan-create" href="<?= Url::to(['/crm/crm-visit-plan/create']); ?>"><i class="icon-plus-sign-alt"></i></a>
                    <a title="栏位设置" href="<?= Url::to(['/crm/crm-customer-manage/sys-list']) ?>?id=200000028&str=拜访计划" class="update"><i class="icon-edit"></i></a>
                    <a title="删除模块" onclick="cancle_module('crmVisitPlan');"><i class="icon-remove panel-tool-a"></i></a>
                </div>
            </div>
            <div id="visitPlan">
            </div>
        </div>

        <div id="tradeOrder" class="mb-20 change float-left display-none">
            <div class="panel-header title-color">
                <div class="panel-title">
                    <span class="mr-10">最近交易订单</span>
                </div>
                <div class="panel-tool">
<!--                    <a title="栏位设置" href="--><?//= Url::to(['/crm/crm-customer-manage/sys-list']) ?><!--?id=200000030&str=最近交易订单"-->
<!--                       class="update"><i class="icon-edit"></i></a>-->
                    <a style="color:gray;" title="栏位设置" href="javascript:void(0)"><i class="icon-edit"></i></a>
                    <a title="删除模块" onclick="cancle_module('tradeOrder');" id="tradeOrder"><i
                            class="icon-remove panel-tool-a"></i></a>
                </div>
            </div>
            <div id="lastSaleOrder">
            </div>
        </div>

        <div id="crmVisitRecord" class="mb-20 change float-left display-none">
            <div class="panel-header title-color">
                <div class="panel-title">
                    <span class="mr-10">拜访记录</span>
                </div>
                <div class="panel-tool">
                    <a title="新增拜访记录" id="visit-create" href="<?= Url::to(['/crm/crm-visit-record/add']); ?>"><i  class="icon-plus-sign-alt"></i></a>
                    <a title="栏位设置" href="<?= Url::to(['/crm/crm-customer-manage/sys-list']) ?>?id=200000029&str=拜访记录" class="update"><i class="icon-edit"></i></a>
                    <a title="删除模块" onclick="cancle_module('crmVisitRecord');"><i class="icon-remove panel-tool-a"></i></a>
                </div>
            </div>
            <div id="visitRecord">
            </div>
        </div>

        <div id="quote" class="mb-20 change float-left  display-none">
            <div class="panel-header title-color">
                <div class="panel-title">
                    <span class="mr-10">最近报价</span>
                </div>
                <div class="panel-tool">
<!--                    <a title="栏位设置" href="--><?//= Url::to(['/crm/crm-customer-manage/sys-list']) ?><!--?id=200000031&str=最近报价"-->
<!--                       class="update"><i class="icon-edit"></i></a>-->
                    <a style="color:grey;" title="栏位设置" href="javascript:void(0)"><i class="icon-edit"></i></a>
                    <a title="删除模块" onclick="cancle_module('quote');" id="quote"><i
                            class="icon-remove panel-tool-a"></i></a>
                </div>
            </div>
            <div id="quotedPrice">
            </div>
        </div>

    </div>
</div>
<script>
    $(function () {

        <?php if(!empty($module)){ ?>
        <?php foreach ($module as $k => $v){ ?>
        $('#<?php echo $v['module'] ?>').show();
        <?php } ?>
        <?php } ?>



        $(".change:visible").each(function(i){
            if(i%2==0){
                $(this).css("clear","left");
            }
        });


        /*列表-start-*/
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                <?= $columns ?>
                {
                    field: "action", title: "操作", width: 180, formatter: function (val, row, index) {
                    var editable = !row.apply_code && row.apply_status != 10 && row.apply_status != 20 && row.apply_status != 30;
                    return editable ? '<a href="<?= Url::to(['/crm/crm-customer-info/update']) ?>?id=' + row.cust_id + '"><i class="icon-edit" title="修改" style="font-size: 12px;color:#1f7ed0;"></i></a>' : '';
                }
                },
            ]],

//            onClickCell:function(index,field,value){
//                if(field == 'cust_sname'){
//                    view(index);
//                }
//            },
            onSelect: function (rowIndex, rowData) {
                refresh(rowData.cust_id);
            },
            onLoadSuccess: function (data) {
                datagridTip('#data');//鼠标移动到显示文字内容
                showEmpty($(this), data.total, 0, 1);
                setMenuHeight();
            }
        });
        /*列表-start-*/
        eval(refresh($id = ''));
        function refresh($id) {
            /*CRD/PRD */
            $("#productDvlp").datagrid({
                url: "<?= Url::to(['/crm/crm-customer-manage/requirement-product']);?>?id=" + $id,
                rownumbers: true,
                method: "get",
                idField: "pdq_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    <?=$prdColumns?>
                    {
                        field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        return '<a href="<?= Url::to(['/ptdt/product-dvlp/edit']) ?>?id=' + row.product_id + '"><i title="修改" class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></i></a>';
                    }
                    }
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#productDvlp');
                    showEmpty($(this), data.total, 0, 1, 1);
                    setMenuHeight();
                }
            });

            /*我的申请*/
            $("#apply").datagrid({
                url: "<?= Url::to(['/crm/crm-customer-manage/verify']);?>?id=" + $id,
                rownumbers: true,
                method: "get",
                idField: "capply_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    <?=$applyColumns?>
//                    {
//                        field: "action", title: "操作", width: 100, formatter: function (val, row) {
////                        return '<a href="<?////= Url::to(['/crm/crm-customer-apply/customer-info']) ?>////?id='+row.cust_id +'"><span class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></span></a>';
//                        return '<a id="verify" onclick="action_verify(\'' + row.status + '\'' + ',' + row.cust_id + ')"><i class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></i></a>';
//                    }
//                    },
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#apply');
                    showEmpty($(this), data.total, 0, 1);
                    setMenuHeight();
                }
            });

            /*拜访计划*/
            $("#visitPlan").datagrid({
                url: "<?= Url::to(['/crm/crm-customer-manage/visit-plan']);?>?id=" + $id,
                rownumbers: true,
                method: "get",
                idField: "svp_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    <?= $planColumns ?>
//                    {
//                        field: "action", title: "操作", width: 100, formatter: function (val, row, index) {
//                        return '<a href="<?//= Url::to(['/crm/crm-visit-plan/update']) ?>//?id='+row.svp_id+'"><span class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></span></a>';
//                    }
//                    },
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#visitPlan');
                    showEmpty($(this), data.total, 0, 1);
                    setMenuHeight();
                }
            });

            /*最近交易订单信息*/
            $("#lastSaleOrder").datagrid({
                url: "<?= Url::to(['/crm/crm-customer-manage/last-sale-order']);?>?id=" + $id,
                rownumbers: true,
                method: "get",
                idField: "soh_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    <?= $orderColumns ?>
                    {
                        field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        return '<a href="<?= Url::to(['/crm/product-dvlp/edit']) ?>?id=' + row.soh_id + '"><i class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></i></a>';
                    }
                    },
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#lastSaleOrder');
                    showEmpty($(this), data.total, 0, 1);
                    setMenuHeight();
                }
            });

            /*拜访记录*/
            $("#visitRecord").datagrid({
                url: "<?= Url::to(['/crm/crm-customer-manage/visit-record']);?>?id=" + $id,
                rownumbers: true,
                method: "get",
                idField: "sil_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    <?= $recordColumns ?>
//                    {
//                        field: "action", title: "操作", width: 100, formatter: function (val, row,index) {
//                        return '<a href="<?//= Url::to(['/crm/crm-visit-record/edit']) ?>//?childId='+row.sil_id +'"><span class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></span></a>';
//                    }
//                    }
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#visitRecord');
                    showEmpty($(this), data.total, 0, 1);
                    setMenuHeight();
                }
            });
            /*报价单信息*/
            $("#quotedPrice").datagrid({
                url: "<?= Url::to(['/crm/crm-customer-manage/last-sale-quotedprice']);?>?id=" + $id,
                rownumbers: true,
                method: "get",
                idField: "sapl_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                pageSize: 5,
                pageList: [5, 10, 15],
                columns: [[
                    <?= $priceColumns ?>
                    {
                        field: "action", title: "操作", width: 100, formatter: function (val, row) {
                        return '<a href="<?= Url::to(['/crm/product-dvlp/edit']) ?>?id=' + row.sapl_id + '"><i class="icon-edit" style="font-size: 12px;color:#1f7ed0;"></i></a>';
                    }
                    },
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#quotedPrice');
                    showEmpty($(this), data.total, 0, 1);
                    setMenuHeight();
                }
            });
        }

        $(".update").fancybox({
            padding: [],
            fitToView: false,
            width: 700,
            height: 550,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });


        $(".module").fancybox({
            padding: [],
            fitToView: false,
            width: 500,
            height: 300,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });


        $("#visit-create").click(function(event){
            event.preventDefault();
            var row=$("#data").datagrid("getSelected");
            var url=$(this).attr("href")+"?customerId="+row.cust_id;
            window.location.href=url;
        });

        $("#plan-create").click(function(event){
            event.preventDefault();
            var row=$("#data").datagrid("getSelected");
            var url=$(this).attr("href")+"?customerId="+row.cust_id;
            window.location.href=url;
        });

    })

    function cancle_module(name) {
        var id = '<?= Yii::$app->user->identity->staff_id; ?>';
        layer.confirm("确定要删除这个模块吗?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                $.ajax({
                    type: "get",
                    dataType: "json",
                    async: false,
                    data: {"id": id, 'name': name},
                    url: '<?= Url::to(["module-delete"]) ?>',
                    success: function (msg) {
                        if (msg.flag === 1) {
                            layer.alert(msg.msg, {
                                icon: 1, end: function () {
                                    location.reload();
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

    function action_verify(status, id) {
        if (status == '待提交' || status == '驳回') {
            location.href = '<?= Url::to(['/crm/crm-customer-apply/customer-info']) ?>?id=' + id;
        } else {
            layer.alert('无法修改', {icon: 2})
        }
    }

</script>
