<?php
use yii\helpers\Url;
use yii\grid\GridView;
$this->title = '供应商资料库';
$this->params['homeLike'] = ['label'=>'供应商管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'供应商列表'];
?>

<div class="content">
    <?php  echo $this->render('_search', ['model' => $searchModel,'downList'=>$downList]); ?>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div id="data"></div>
        <div id="order_child_title"></div>
        <div id="order_child_title2"></div>
        <div id="order_child" style="width:100%;"></div>
    </div>
    <div class="easyui-tabs mt-20" style="visibility: hidden;">
        <p class="head">商品信息</p>
        <div title="主营商品">
            <div id="main-product"></div>
        </div>
        <div title="联系信息">
            <div id="contact-info"></div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "saph_id",
            loadMsg: false,
            pagination: true,
            singleSelect: false,
            checkOnSelect: true,
            columns: [[
                {field:"",checkbox:true,width:200},
                {field: "bus_code", title: "订单编号", width: 150},
                {field: "saph_status", title: "订单状态", width: 80},
                {field: "saph_type", title: "订单类型", width: 80},
                {field: "cdate", title: "下单时间", width: 80},
                {field: "cust_id", title: "客户名称", width: 80},
                {field: "cust_id", title: "客户代码", width: 80},
                {field: "pat_id", title: "交易法人", width: 80},
                {field: "pac_id", title: "付款方式", width: 80},
                {field: "customerManager", title: "客户经理人", width: 80},
                {field: "sell_delegate", title: "操作", width: 100}
                <?= $columns ?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
            },
            onSelect: function (rowIndex, rowData) {
                var row = $("#data").datagrid("getSelected");
                var rows = $("#data").datagrid("getSelections");
                if (rows.length != 1) {
                    $(".easyui-tabs").css("visibility","hidden");
                    return false;
                }
                $(".easyui-tabs").css("visibility","visible");
                var id = row['soh_id'];
                $("#main-product").datagrid({
                    url: "<?=Url::to(['visit-log']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "sil_id",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["visit-log"];?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#visit-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
                $("#contact-info").datagrid({
                    url: "<?=Url::to(['act-info']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "acth_code",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["act-info"];?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#act-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
            },
            onUnselect: function (index,row) {
                var row = $("#data").datagrid("getSelected");
                var rows = $("#data").datagrid("getSelections");
                if (rows.length != 1) {
                    $(".easyui-tabs").css("visibility","hidden");
                    return false;
                }
                console.log(row,rows.length);
                $(".easyui-tabs").css("visibility","visible");
                var id = row['soh_id'];
                $("#main-product").datagrid({
                    url: "<?=Url::to(['visit-log']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "sil_id",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["visit-log"];?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#visit-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
                $("#contact-info").datagrid({
                    url: "<?=Url::to(['act-info']);?>?id="+row.cust_id,
                    rownumbers: true,
                    method: "get",
                    idField: "acth_code",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
                        <?=$columns["act-info"];?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#act-data"));
                        showEmpty($(this),data.total,0);
                    }
                });
            }
        });

        // 编辑
        $("#edit").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['supplier_id'];
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });

        // 查看
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条数据!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['supplier_id'];
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });
    });
</script>