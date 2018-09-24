<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/12/25
 * Time: 上午 08:10
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\classes\Menu;
use app\assets\JeDateAsset;

JeDateAsset::register($this);

$this->title = '销售出库列表';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .width-80 {
        width: 80px;
    }

    .width-150 {
        width: 150px;
    }

    .width-120 {
        width: 120px;
    }
</style>
<div class="content">
    <?php ActiveForm::begin([
        "method" => "get",
        "action" => "index"
    ]); ?>
    <div class="mb-20">
        <label for="" class="width-80">出库单号：</label>
        <input type="text" class="width-120" name="o_whcode" id="whcode" value="<?= $Param['o_whcode'] ?>">
        <label for="" class="width-80">出库单状态：</label>
        <?= Html::dropDownList("o_whstatus", $Param['o_whstatus'], $options["o_whstatus"], ["prompt" => "全部", "class" => "width-120","id"=>"whstatus"]) ?>
        <label for="" class="width-80">法人名称：</label>
        <?= Html::dropDownList("company", $Param['company'], $options["company"], ["prompt" => "请选择", "class" => "width-120","id"=>"company"]) ?>
        <label for="" class="width-80">仓库名称： </label>
        <select name="o_whid" id="wh_name" class="width-120 easyui-validatebox" data-options="required:true">
            <option value="" data-code="0">全部</option>
            <?php foreach ($options['warehouse'] as $key => $val) { ?>
                <option value="<?= $val['wh_id'] ?>"
                        data-code="<?= $val['wh_code'] ?>" <?= !empty($Param['o_whid']) && $Param['o_whid'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div style="height: 10px;"></div>
    <div class="mb-20">
        <label for="" class="width-80">客户名称：</label>
        <input type="text" class="width-120" id="contacts" name="cust_contacts"
               value="<?= $Param['cust_contacts'] ?>">
        <label for="" class="width-80">客户订单号：</label>
        <input type="text" class="width-120" name="ord_no" id="ordno" value="<?= $Param['ord_no'] ?>">
        <label for="" class="width-80">出库单日期：</label>
        <input type="text" id="start_time" readonly="readonly"  class="Wdate width-120" name="o_start_date"
               value="<?= $Param['o_start_date'] ?>">
        至
        <input type="text" id="end_time" readonly="readonly" class="Wdate width-120" name="o_end_date"
               value="<?= $Param['o_end_date'] ?>">
        <button class="search-btn-blue ml-20" type="submit">查询</button>
        <button class="reset-btn-yellow" type="button" onclick="window.location.href='<?= Url::to(['index']) ?>'">重置
        </button>
    </div>
    <div style="height: 20px;"></div>
    <?php ActiveForm::end(); ?>
    <?= \app\widgets\toolbar\Toolbar::widget([
        'title' => '销售出库列表',
        'menus' => [
            [
                'label' => '出仓费用申请',
                'icon' => 'add-item-bgc',
//                'url'=>Url::to(['create']),
                'dispose' => 'default',
                'options' => ['style' => ['display' => 'none'], 'id' => 'cost-apply']
            ],
            [
                'label' => '生成物流订单',
                'icon' => 'add-item-bgc',
//                'url'=>Url::to(['create']),
                'dispose' => 'default',
                'options' => ['style' => ['display' => 'none'], 'id' => 'generating-order']
            ],
            [
                'label' => '查看物流进度',
                'icon' => 'update-item-bgc',
                'options' => ['id' => 'view-order', 'style' => ['display' => 'none']]
            ],
            [
                'label' => '取消发货',
                'icon' => 'audit-item-bgc',
                'options' => ['id' => 'cancel', 'style' => ['display' => 'none']]
            ],
            [
                'label' => '自提',
                'icon' => 'setting11',
                'options' => ['id' => 'since', 'style' => ['display' => 'none']]
            ],
            [
                'label' => '重新上架',
                'icon' => 'setting11',
                'options' => ['id' => 'again-shelves', 'style' => ['display' => 'none']]
            ],
            [
                'label' => '导出',
                'icon' => 'export-item-bgc',
                'options' => ['id' => 'export']
            ],
            [
                'label' => '返回',
                'icon' => 'return-item-bgc',
                'url' => Url::home(),
                'dispose' => 'default'
            ]
        ]
    ]); ?>
    <div style="height: 10px;"></div>
    <div id="data" style="width:100%;"></div>
    <div id="child_table_title"></div>
    <div id="child_table"></div>
</div>
<script>
    $(function () {
        //管控开始时间
        $("#start_time").click(function () {
            WdatePicker({
                skin: "whyGreen",
                maxDate: "#F{$dp.$D('end_time',{d:-1})}"
            });
//            WdatePicker({
//                skin: 'whyGreen',
////                minDate: '%y-%M-{%d+1}',
//                dateFmt: 'yyyy-MM-dd',
//                isShowToday: true,
//                maxDate: '#F{$dp.$D(\'end_time\');}'
//            })
        });
        //结束时间
        $("#end_time").click(function () {
            if ($("#start_time").val() === '') {
                layer.alert('请先选择开始时间', {icon: 2});
                return false;
            }
            WdatePicker({
                skin: "whyGreen",
                minDate: "#F{$dp.$D('start_time',{d:1})}"
            });
//            WdatePicker({
//                skin: 'whyGreen',
//                isShowToday: true,
//                dateFmt: 'yyyy-MM-dd',
//                minDate: '#F{$dp.$D(\'start_time\');}'
//            })
        });
        var $childTableTitle = $("#child_table_title");
        var $childTable = $("#child_table");
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "o_whpkid",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 10,
            pageList: [10, 20, 30],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns: [[
                {field: 'ck', checkbox: true},
                {field: "o_whcode", width: 150, title: "出库单号"},
                {
                    field: "o_whstatus", width: 100, title: "出库单状态", formatter: function (value, rowData) {
                    if (rowData.o_whstatus == "已取消") {
                        return "<a class='tip' style='color:#333333;'>已取消</a>";
                    }
                    else {
                        return rowData.o_whstatus;
                    }
                }
                },
                {field: "relate_packno", width: 100, title: "关联拣货单"},
                {field: "company_name", width: 100, title: "交易法人"},
                {field: "cust_sname", width: 100, title: "客户名称"},
                {field: "wh_name", width: 100, title: "出仓仓库"},
                {field: "ord_no", width: 150, title: "客户订单号"},
                {field: "business_type_desc", width: 100, title: "订单类型"},
                {field: "logistics_no", width: 100, title: "物流订单号"},
                {field: "all_address", width: 200, title: "收货地址"},
                {field: "reciver", width: 100, title: "收货人"},
                {field: "reciver_tel", width: 100, title: "收货人联系方式"},
                {field: "o_date", width: 100, title: "出库单日期"},
                {field: "staff_name", width: 100, title: "操作员"},
                {
                    field: "action", title: "操作", formatter: function (value, rowData) {
                    var str = "<i>";
                    if (rowData.o_whstatus == "待出库" || rowData.o_whstatus == "驳回" || rowData.o_whstatus == "待提交") {
                        <?php if(Menu::isAction('/warehouse/allocation/edit')){?>
                        str += "<a class='icon-check-minus icon-large' style='' title='取消' onclick='cancel(" + rowData.o_whpkid + ");event.stopPropagation();'></a>";
                        <?php }?>
                    }
//                    str += "<a class='icon-eye-open icon-large' title='查看' onclick='location.href=\"<?//=Url::to(['views'])?>//?id=" + value + "\";event.stopPropagation();'></a>";
                    str += "</i>";
                    return str;
                }
                }
            ]],
            onSelect: function (value, row) {
                var index = $("#data").datagrid("getRowIndex", row.o_whpkid);
                $(".datagrid-menu .hide").removeClass("hide");
                $('#data').datagrid("uncheckAll");
                $("#cancel-resaon").html(row.remarks);
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
                if (row.o_whstatus == '已取消' || row.o_whstatus == "<a class='tip' style='color:#cd0a0a'>已取消</a>") {
                    $("#again-shelves").show();
                    $("#generating-order").hide();
                    $("#cancel").hide();
                    $("#cost-apply").hide();
                    $("#view-order").hide();
                }
                else if (row.o_whstatus == '已出库') {
                    $("#generating-order").hide();
                    $("#cancel").hide();
                    $("#again-shelves").hide();
                    $("#view-order").show();
                    $("#cost-apply").show();
                }
                else if (row.o_whstatus == '待出库') {
                    $("#generating-order").show();
                    $("#cancel").show();
                    $("#again-shelves").hide();
                    $("#view-order").hide();
                    $("#cost-apply").show();
                }
                else if (row.o_whstatus == '待收货' || row.o_whstatus == '已收货') {
                    $("#again-shelves").hide();
                    $("#generating-order").hide();
                    $("#cancel").hide();
                    $("#view-order").show();
                    $("#cost-apply").show();
                }
//                else if(row.delivery_type=='0'&&row.o_whstatus=='已收货')
//                {
//                    $("#again-shelves").hide();
//                    $("#generating-order").hide();
//                    $("#cancel").hide();
//                    $("#view-order").hide();
//                    $("#cost-apply").show();
//                }
                else {
                    $("#again-shelves").hide();
                    $("#generating-order").hide();
                    $("#cancel").hide();
                    $("#view-order").hide();
                    $("#cost-apply").hide();

                }
                if (row.delivery_type == '0' && row.o_whstatus == '待出库') {
                    $("#generating-order").hide();
                    $("#since").show();
                }
                else {
                    $("#since").hide();
                }
                $childTableTitle.addClass("table-head mb-5 mt-20").html("<p>商品信息</p>").show().next().show();
                $childTable.datagrid({
                    url: "<?=Url::to(['load-product'])?>",
                    queryParams: {"id": row.o_whpkid},
                    rownumbers: true,
                    method: "get",
                    idField: "o_whdtid",
                    singleSelect: true,
//                    pagination:true,
                    columns: [[
                        {field: "part_no", title: "料号", width: "150"},
                        {field: "pdt_name", title: "品名", width: "150"},
                        {field: "tp_spec", title: "规格/型号", width: "150"},
                        {field: "req_num", title: "需求出库数量", width: "100"},
                        {field: "pck_nums", title: "拣货数量", width: "100"},
                        {field: "o_whnum", title: "出库数量", width: "100"},
                        {field: "unit_name", title: "交易单位", width: "100"},
                        {field: "price", title: "商品单价", width: "100"},
                        {field: "sum_price", title: "商品总价", width: "100"},
                        {field: "bdm_sname", title: "配送方式", width: "100"},
                        {field: "L_invt_bach", title: "批次", width: "100"},
                        {field: "consignment_date", title: "交期", width: "100"},
                        {field: "o_date", title: "出库日期", width: "100"},
                        {field: "remarks", title: "备注", width: "100"}
                    ]],
                    onLoadSuccess: function (data) {
//                        console.log(data);
                        datagridTip($childTable);
                        setMenuHeight();
                        showEmpty($(this), data.total, 0);
                    }
                });
            },
            onCheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    for (var d = 0; d < a.length; d++) {
                        if (a[d]['o_whstatus'] == '已取消' || a[d]['o_whstatus'] == "<a class='tip' style='color:#cd0a0a'>已取消</a>") {
                            $("#again-shelves").show();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#cost-apply").hide();
                            $("#view-order").hide();
                        }
                        else if (a[d]['o_whstatus'] == '已出库') {
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#again-shelves").hide();
                            $("#view-order").show();
                            $("#cost-apply").show();
                        }
                        else if (a[d]['o_whstatus'] == '待出库') {
                            $("#generating-order").show();
                            $("#cancel").show();
                            $("#again-shelves").hide();
                            $("#view-order").hide();
                            $("#cost-apply").show();
                        }
                        else if (a[d]['o_whstatus'] == '待收货' || a[d]['o_whstatus'] == '已收货') {
                            $("#again-shelves").hide();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#view-order").show();
                            $("#cost-apply").show();
                        }
                        else {
                            $("#again-shelves").hide();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#view-order").hide();
                            $("#cost-apply").hide();

                        }
                        if (a[d]['delivery_type'] == '0' && a[d]['o_whstatus'] == '待出库') {
                            $("#generating-order").hide();
                            $("#since").show();
                        }
                        else {
                            $("#since").hide();
                        }
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else if (a.length == 0) {
                    $("#again-shelves").hide();
                    $("#generating-order").hide();
                    $("#cancel").hide();
                    $("#view-order").hide();
                    $("#cost-apply").hide();
                    $("#since").hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else {
                    var a1 = $("#data").datagrid("getChecked");
                    $('#data').datagrid("unselectAll");
                    for (var i = 0; i < a1.length; i++) {
                        if ((a1[i]['o_whstatus'] != '待出库')) {
                            $("#again-shelves").hide();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#view-order").hide();
                            $("#cost-apply").hide();
                            $("#since").hide();
                            break;
                        }
                        else {
                            $("#generating-order").hide();
                            $("#cancel").show();
                            $("#again-shelves").hide();
                            $("#view-order").hide();
                            $("#cost-apply").hide();
                            $("#since").hide();
                        }
                    }
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    for (var d = 0; d < a.length; d++) {
                        if (a[d]['o_whstatus'] == '已取消' || a[d]['o_whstatus'] == "<a class='tip' style='color:#cd0a0a'>已取消</a>") {
                            $("#again-shelves").show();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#cost-apply").hide();
                            $("#view-order").hide();
                        }
                        else if (a[d]['o_whstatus'] == '已出库') {
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#again-shelves").hide();
                            $("#view-order").show();
                            $("#cost-apply").show();
                        }
                        else if (a[d]['o_whstatus'] == '待出库') {
                            $("#generating-order").show();
                            $("#cancel").show();
                            $("#again-shelves").hide();
                            $("#view-order").hide();
                            $("#cost-apply").show();
                        }
                        else if (a[d]['o_whstatus'] == '待收货' || a[d]['o_whstatus'] == '已收货') {
                            $("#again-shelves").hide();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#view-order").show();
                            $("#cost-apply").show();
                        }
                        else {
                            $("#again-shelves").hide();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#view-order").hide();
                            $("#cost-apply").hide();

                        }
                        if (a[d]['delivery_type'] == '0' && a[d]['o_whstatus'] == '待出库') {
                            $("#generating-order").hide();
                            $("#since").show();
                        }
                        else {
                            $("#since").hide();
                        }
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                    }
                } else if (a.length == 0) {
                    $("#again-shelves").hide();
                    $("#generating-order").hide();
                    $("#cancel").hide();
                    $("#view-order").hide();
                    $("#cost-apply").hide();
                    $("#since").hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else {
                    var a1 = $("#data").datagrid("getChecked");
                    for (var i = 0; i < a1.length; i++) {
                        if ((a1[i]['o_whstatus'] != '待出库')) {
                            $("#again-shelves").hide();
                            $("#generating-order").hide();
                            $("#cancel").hide();
                            $("#view-order").hide();
                            $("#cost-apply").hide();
                            $("#since").hide();
                            break;
                        }
                        else {
                            $("#generating-order").hide();
                            $("#cancel").show();
                            $("#again-shelves").hide();
                            $("#view-order").hide();
                            $("#cost-apply").hide();
                            $("#since").hide();
                        }
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
            },
            onUnselectAll: function (rowIndex, rowData) {
                $("#child_table_title").hide().next().hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#child_table_title").hide().next().hide();
            },
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            },
        });

        //取消发货
        $("#cancel").click(function () {
            var row = $("#data").datagrid("getSelected");
            if (!row) {
                layer.alert("请选择一条记录", {icon: 2});
            }
            else {
                $.fancybox({
                    type: "iframe",
                    padding: 0,
                    width: 400,
                    height: 300,
                    href: "<?=Url::to(['cancel'])?>?id=" + row.o_whpkid
                });
            }
        });

        //重新上架
        $("#again-shelves").click(function () {
            var row = $("#data").datagrid("getSelected");
            $.fancybox({
                href: "<?=Url::to(['put-away'])?>?id=" + row.o_whpkid,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 600
            });
        });

        //生成物流订单
        $("#generating-order").click(function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
                return false;
            } else {
                location.href = "<?=Url::to(['generating-logistics'])?>?id=" + row.o_whpkid;
            }
        });

        //出仓费用申请
        $("#cost-apply").click(function () {
//            var row=$("#data").datagrid("getSelected");
//            if (row == null) {
//                layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
//                return false;
//            } else {
//                location.href = "<?//=Url::to(['application-apply'])?>//?id=" + row.o_whpkid;
//            }
        });

        //查看物流进度
        $("#view-order").click(function () {
            var row = $("#data").datagrid("getSelected");
            $.fancybox({
                href: "<?=Url::to(['view-logistics-information'])?>?id=" + row.o_whpkid,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 600,
                height: 400
            });
        });

        //自提
        $("#since").click(function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
                return false;
            } else {
                layer.confirm('确定是否自提？', {icon: 2},
                    function () {
                        $.ajax({
                            url: "<?=Url::to(['since'])?>",
                            data: {
                                "id": row.o_whpkid
                            },
                            dataType: "json",
                            success: function (data) {
                                if (data.flag == 1) {
                                    layer.alert("操作成功！", {
                                        icon: 1, end: function () {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    layer.alert(data.msg, {icon: 2});
                                }
                            }
                        });
                    },
                    function () {
                        layer.closeAll();
                    }
                );
            }
        });

        //导出
        $("#export").click(function () {
            var index = layer.confirm("确定要导出销售出库信息?",
                {   fix:false,
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['export']) . '?' . http_build_query(Yii::$app->request->queryParams) ?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出拣货单信息发生错误', {icon: 0})
                    }
                   // layer.closeAll();
//                    var url="<?//=Url::to(['export'])?>//";
////                    url+='?export=1';
//                    url+='?o_whcode='+$("#whcode").val();
//                    url+='&o_whstatus='+$("#whstatus").val();
//                    url+='&company='+$("#company").val();
//                    url+='&o_whid='+$("#wh_name").val();
//                    url+='&cust_contacts='+$("#contacts").val();
//                    url+='&ord_no='+$("#ordno").val();
//                    url+='&o_start_date='+$("#start_time").val();
//                    url+='&o_end_date='+$("#end_time").val();
//                    location.href=url;
                },
                function () {
                    layer.closeAll();
                }
            );
        });
    });

    //取消
    function cancel(id) {
        $.fancybox({
            type: "iframe",
            padding: 0,
            width: 400,
            height: 300,
            href: "<?=Url::to(['cancel'])?>?id=" + id
        });
    }
</script>
