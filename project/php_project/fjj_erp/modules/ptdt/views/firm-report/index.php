<?php
use yii\helpers\Url;

$this->params['homeLike'] = ['label'=>'商品开发管理','url'=>''];
$this->params['breadcrumbs'][] = ['label' => '商品开发进程'];
$this->params['breadcrumbs'][] = ['label' => '厂商呈报列表'];
$this->title = '厂商呈报列表';
?>
<style>
    #tt1 .datagrid-header{
        height: 50px !important;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam,
    ]); ?>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
        <div class="space-30"></div>
        <div id="tt" class="easyui-tabs" style="width:980px;height:auto; display:none;">
            <div id="tt1" title="呈报详细信息" style="display:none;">
                <div id="report"></div>
            </div>
            <div title="销售商品信息" style="display:none;">
                <div id="product"></div>
            </div>
        </div>
    </div>
</div>
<script>
    var id;
    var childId;
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "pfr_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                <?= $columns ?>
            ]],
            onSelect: function (rowIndex, rowData) {    //选择触发事件
                if(rowData.pfr_id == null){
                    $("#data").datagrid('unselectRow',0);
                    return false;
                }
                $("#tt").show();
                var id = rowData['pfr_id'];
                var status = rowData['report_status'];
                $("#view,#analysis,#m-send").show();
                if(status == '10' || status == '20'){
                    $("#check,#delete,#edit").show();
                }else{
                    $("#check,#delete,#edit").hide();
                }
                if (status == '50') {
                    $(".status_50").show();
                } else {
                    $(".status_50").hide();
                }
                $("#report").datagrid({
                    url: "<?= Url::to(['/ptdt/firm-report/load-report']);?>?id=" + id,
                    rownumbers: true,
                    method: "get",
                    idField: "pfrc_id",
                    loadMsg: false,
                    pagination: true,
                    singleSelect: true,
                    pageSize: 5,
                    pageList: [5, 10, 15],
                    columns: [
                        [
                            {title: "呈报信息", colspan: 2},
                            {title: "厂商实力评估", colspan: 2},
                            {title: "授权项目", colspan: 7},
                            {title: "谈判事项", colspan: 1},
                            {field: "remark", title: "备注", width: 150, rowspan: 2}
                        ],
                        [
                            {field: "pfrc_code", title: "呈报编号", width: 150, formatter: function (val, row) {
                                if(status==40 || status==50 || status==10){
                                    return '<a href="<?= Url::to(['/ptdt/firm-report/view']) ?>?id='+ id +'&childId='+ row.pfrc_id +'">'+ val +'</a>';
                                }else{
                                    return '<a href="<?= Url::to(['/ptdt/firm-report/view']) ?>?id='+ id +'&childId='+ row.pfrc_id +'&type=2'+'">'+ val +'</a>';
                                }
                            }},
                            {field: "pfrc_date", title: "呈报日期", width: 150},
                            {
                                field: "firmPosition", title: "厂商地位", width: 150, formatter: function (val, row) {
                                return row.bsPubdata.firmPosition;
                            }
                            },
                            {
                                field: "pdna_annual_sales",
                                title: "年度营业额",
                                width: 150,
                                formatter: function (val, row) {
                                    return row.analysis.pdna_annual_sales;

                                }
                            },
                            {
                                field: "goodsLoction", title: "代理商品定位", rowspan: 1, formatter: function (val, row) {
                                return row.bsPubdata.goodsLoction;

                            }
                            },
                            {
                                field: "agentsGrade", title: "代理等级", width: 150, formatter: function (val, row) {
                                return row.bsPubdata.agentsGrade;

                            }
                            },
                            {
                                field: "authorizeArea",
                                title: "授权区域范围",
                                width: 150,
                                formatter: function (val, row) {
                                    return row.bsPubdata.authorizeArea;

                                }
                            },
                            {
                                field: "saleArea", title: "销售范围", width: 150, formatter: function (val, row) {
                                return row.bsPubdata.saleArea;

                            }
                            },
                            {
                                field: "time", title: "授权日期", width: 150, formatter: function (val, row) {

                                return row.authorize.pdaa_bdate?row.authorize.pdaa_bdate + " - " + row.authorize.pdaa_edate:'';
                            }
                            },
                            {field: "firmSettlement", title: "结算方式", width: 150},
                            {
                                field: "pdaa_delivery_day",
                                title: "交期",
                                width: 150,
                                formatter: function (val, row) {
                                    return row.authorize?row.authorize.pdaa_delivery_day:'';

                                }
                            },
                            {
                                field: "firmConcluse", title: "谈判结论", width: 150, formatter: function (val, row) {
                                return row.firmConcluse;
                            }
                            }

                        ]],
                    onSelect: function (rowIndex, rowData) {
                        if(rowData.pfrc_id == null){
                            $("#report").datagrid('unselectRow',0);
                            return false;
                        }
                        var id = rowData['pfrc_id'];
                        $("#product").datagrid({
                            url: "<?= Url::to(['/ptdt/firm-report/load-product']);?>?id=" + id,
                            rownumbers: true,
                            method: "get",
                            idField: "pfrd_id",
                            loadMsg: false,
                            pagination: true,
                            singleSelect: true,
                            pageSize: 5,
                            pageList: [5, 10, 15],
                            columns: [
                                [
                                    {field: "product_name", title: "品名", width: 150},
                                    {field: "product_size", title: "规格", width: 150},
                                    {field: "levelName", title: "商品定位", width: 150},
                                    {field: "price_max", title: "定价上限", width: 150},
                                    {field: "price_min", title: "定价下限", width: 150},
                                    {field: "price_average", title: "市场行情价", width: 150},
                                    {
                                        field: "margin_max",
                                        title: "利润率上限",
                                        width: 150,
                                        formatter: function (val, row) {
                                            return row.margin?row.margin[0]:'';
                                        }
                                    },
                                    {
                                        field: "margin_min",
                                        title: "利润率下限",
                                        width: 150,
                                        formatter: function (val, row) {
                                            return row.margin?row.margin[1]:'';
                                        }
                                    },
                                    {
                                        field: "yijie", title: "一阶", width: 150, formatter: function (val, row) {
                                        return row.typeName?row.typeName[0]:'';
                                    }
                                    },
                                    {
                                        field: "erjie", title: "二阶", width: 150, formatter: function (val, row) {
                                        return row.typeName?row.typeName[1]:'';
                                    }
                                    },
                                    {
                                        field: "sanjie", title: "三阶", width: 150, formatter: function (val, row) {
                                        return row.typeName?row.typeName[2]:'';

                                    }
                                    },
                                    {
                                        field: "sijie", title: "四阶", width: 150, formatter: function (val, row) {
                                        return row.typeName?row.typeName[3]:'';

                                    }
                                    },
                                    {
                                        field: "wujie", title: "五阶", width: 150, formatter: function (val, row) {
                                        return row.typeName?row.typeName[4]:'';

                                    }
                                    },
                                    {
                                        field: "liujie", title: "六阶", width: 150, formatter: function (val, row) {
                                        return row.typeName?row.typeName[5]:'';

                                    }
                                    },
                                ]],
                            onSelect:function(rowIndex,rowData){
                                if(rowData.pfrd_id == null){
                                    $("#product").datagrid('unselectRow',0);
                                    return false;
                                }
                            },
                            onLoadSuccess: function (data) {
                                showEmpty($(this),data.total,0);
                                datagridTip('#product');
                            }
                        })
                    },
                    onLoadSuccess: function (data) {
                        showEmpty($(this),data.total,0);
                        $("#report").datagrid("selectRow", 0);
                        datagridTip('#report');
                    }
                })
            },
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
                datagridTip('#data');
            }
        });

        $("#add").on("click", function () {
            window.location.href = "<?=Url::to(['add'])?>";
        });
        $("#edit").on("click", function () {
            var a = $("#data").datagrid('getSelected');
            if (a == null) {
                layer.alert("请点击选择一条厂商信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pfr_id'];
                var childId = $("#report").datagrid("getSelected")['pfrc_id'];
                var status = $("#data").datagrid("getSelected")['report_status'];
                if (status == '40' || status == '50') {
                    layer.alert("不允许修改", {icon: 2, time: 5000});
                } else {
                    window.location.href = "<?=Url::to(['update'])?>?childId=" + childId + "&id=" + id;
                }
            }
        });
        $("#view").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条厂商信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pfr_id'];
                var childId = $("#report").datagrid("getSelected")['pfrc_id'];
                var status = $("#data").datagrid("getSelected")['report_status'];
                if (status == '40' || status == '50' || status == '10') {
                    window.location.href = "<?=Url::to(['view'])?>?id=" + id + "&childId=" + childId;
                } else {
                    window.location.href = "<?=Url::to(['view'])?>?id=" + id + "&childId=" + childId + "&type=2";
                }
            }
        });
        $("#check").on('click',function(){
            var data = $("#data").datagrid("getSelected");

            if (data == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
                return false;
            }
            if(data['report_status'] == 40){
                layer.alert("需求正在审核中", {icon: 2, time: 5000});
                return false;
            }
            if(data['report_status'] == 50){
                layer.alert("需求已审核通过", {icon: 2, time: 5000});
                return false;
            }
            var id=data['pfr_id'];
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type=12;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        })
        $("#delete").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条厂商信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pfr_id'];
                var childId = $("#report").datagrid("getSelected")['pfrc_id'];
                if (id != null && childId == null) {
                    var index = layer.confirm("确定要删除这条记录吗?",
                        {
                            btn: ['确定', '取消'],
                            icon: 2
                        },
                        function () {
                            $.ajax({
                                type: "get",
                                dataType: "json",
                                async: false,
                                data: {"id": id},
                                url: "<?=Url::to(['/ptdt/firm-report/delete']) ?>",
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
                } else {
                    var index = layer.confirm("确定要删除这条记录吗?",
                        {
                            btn: ['确定', '取消'],
                            icon: 2
                        },
                        function () {
                            $.ajax({
                                type: "get",
                                dataType: "json",
                                async: false,
                                data: {"id": id, "childId": childId},
                                url: "<?=Url::to(['/ptdt/firm-report/delete']) ?>",
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
            }
        });
        $("#analysis").on("click", function () {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条呈报详细信息!", {icon: 2, time: 5000});
            } else {
                var id = $("#data").datagrid("getSelected")['pfr_id'];
                window.location.href = "<?=Url::to(['analysis'])?>?id=" + id;
            }
        });
    });
</script>