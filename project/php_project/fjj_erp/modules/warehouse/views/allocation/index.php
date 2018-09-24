<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/20
 * Time: 上午 09:49
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\classes\Menu;
\app\assets\JeDateAsset::register($this);

$this->title = '调拨单列表';
$this->params['homeLike'] = ['label' => '倉儲物流管理', 'url' => ''];
$this->params['breadcrumbs'][] = ['label' => '调拨单列表', 'url' => ""];
?>
<?php $get = Yii::$app->request->get();
if (!isset($get['InvChangehSearch'])) {
    $get['InvChangehSearch'] = null;
}
?>
<style>
    .width-80 {
        width: 80px;
    }

    .width-150 {
        width: 150px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]
    ); ?>
    <div class="content">
        <div class="space-15"></div>
        <div class="mb-10">
            <input type="hidden" class="_usertype" value="<?= $usertype ?>">
            <input type="hidden" value="<?= Yii::$app->user->identity->staff_id ?>" id="staff_id"
                   name="HrStaff[staff_code]">
            <label class="width-80" for="partsearch-wh_name">调拨单号:</label>
            <input type="text" id="partsearch-wh_name" class="width-150" name="InvChangehSearch[chh_code]"
                   value="<?= $get['InvChangehSearch']['chh_code'] ?>"
            >
            <label class="width-80">制单日期</label>
            <input type="text" id="testpurchase-startdate" class="Wdate width-150" readonly="readonly"
                   name="InvChangehSearch[start_date]"
                   value="<?= $get['InvChangehSearch']['start_date'] ?>"
            >
            <label style="width: 40px;text-align: center">至</label>
             <input id="end-date" class="width-150 Wdate" readonly="readonly"
             name="InvChangehSearch[end_date]" value="<?= $get['InvChangehSearch']['end_date'] ?>">
            <!--            </div>-->
        </div>
        <div class="space-20"></div>
        <div class="mb-10">
            <label class="width-80" for="partsearch-yn">调拨类型:</label>
            <select id="partsearch-yn" class="width-150" name="InvChangehSearch[business_type_desc]">
                <option value="">请选择...</option>
                <?php foreach ($businessname as $val) { ?>
                    <option
                            value="<?= $val['business_type_desc'] ?>" <?= isset($get['InvChangehSearch']['business_type_desc']) && $get['InvChangehSearch']['business_type_desc'] == $val['business_type_desc'] ? "selected" : null ?>><?= $val['business_type_desc'] ?></option>
                <?php } ?>
            </select>
            <label class="width-80 ml-5" for="partsearch-rack_code">调出仓库</label>
            <select id="partsearch-yn" class="width-150" name="InvChangehSearch[Owh_name]">
                <option value="">请选择...</option>
                <?php foreach ($whname as $val) { ?>
                    <option
                        value="<?= $val['wh_id'] ?>" <?= isset($get['InvChangehSearch']['Owh_name']) && $get['InvChangehSearch']['Owh_name'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
            <label class="width-80 ml-5" for="partsearch-part_code">调入仓库:</label>
            <select id="partsearch-yn" class="width-150" name="InvChangehSearch[Iwh_name]">
                <option value="">请选择...</option>
                <?php foreach ($whname as $val) { ?>
                    <option
                            value="<?= $val['wh_id'] ?>" <?= isset($get['InvChangehSearch']['Iwh_name']) && $get['InvChangehSearch']['Iwh_name'] == $val['wh_id'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
        </div>
            <div class="space-20"></div>
            <div class="mb-10">
                <label class="width-80">出库状态:</label>
                <select id="o_status" class="width-150" name="InvChangehSearch[o_status]">
                    <option value="">请选择...</option>
                    <option value="1" <?= isset($get['InvChangehSearch']['o_status']) && $get['InvChangehSearch']['o_status'] == 1 ? "selected" : null ?>>待出库</option>
                    <option value="2" <?= isset($get['InvChangehSearch']['o_status']) && $get['InvChangehSearch']['o_status'] == 2 ? "selected" : null ?>>已出库</option>
                </select>
                <label class="width-80">入库状态:</label>
                <select id="in-status" class="width-150" name="InvChangehSearch[in_status]">
                    <option value="">请选择...</option>
                    <option value="3" <?= isset($get['InvChangehSearch']['in_status']) && $get['InvChangehSearch']['in_status'] == 3 ? "selected" : null ?>>待入库</option>
                    <option value="4" <?= isset($get['InvChangehSearch']['in_status']) && $get['InvChangehSearch']['in_status'] == 4 ? "selected" : null ?>>已入库</option>
                </select>
            <label class="width-80 ml-5" for="partsearch-st_code">审核状态:</label>
            <select id="partsearch-yn" class="width-150" name="InvChangehSearch[chh_status]">
                <option value="">请选择...</option>
<!--                <option-->
<!--                    value="新增" --><?//= isset($get['InvChangehSearch']['chh_statusH']) && $get['InvChangehSearch']['chh_statusH'] == '新增' ? "selected" : null ?><!-->-->
<!--                    新增-->
<!--                </option>-->
                <option
                    value="10" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '待提交' ? "selected" : null ?>>
                    待提交
                </option>
                <option
                    value="20" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '审核中' ? "selected" : null ?>>
                    审核中
                </option>
                <option
                    value="30" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '审核完成' ? "selected" : null ?>>
                    审核完成
                </option>
                <option
                    value="40" <?= isset($get['InvChangehSearch']['chh_status']) && $get['InvChangehSearch']['chh_status'] == '驳回' ? "selected" : null ?>>
                    驳回
                </option>
            </select>
            <input type="hidden" value="<?php echo Yii::$app->user->identity->staff_id ?>">
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-10','style'=>'margin-left:20px']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-20','style'=>'margin-left:10px', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
        </div>
        <div class="space-20"></div>
    </div>
    <div class="space-20 mb-10"></div>
    <div class="table-head">
        <p class="head">调拨单列表</p>
        <div class="float-right">
            <a id="add_btn">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="add-item-bgc " style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;display:none;" id="e1">&nbsp;|&nbsp;</p>
            <a id="edit_btn" style="display:none;">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="update-item-bgc " style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                </div>
            </a>
            <p style="float: left;display: none" id="e10">&nbsp;|&nbsp;</p>
            <a id="inware_btn" style="display: none">
                <div style="height: 23px;width: 100px;float: left;">
                    <p class="details-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">生成入库通知</p>
                </div>
            </a>
            <p style="float: left;" id="e2">&nbsp;|&nbsp;</p>
            <a id="check_btn" style="display:none;">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="submit-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
                </div>
            </a>
            <p style="float: left;display:none;" id="e3">&nbsp;|&nbsp;</p>
            <a id="delete_btn" style="display:none;">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="switch-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;删除</p>
                </div>
            </a>
            <p style="float: left;display: none" id="e6">&nbsp;|&nbsp;</p>
            <a id="export_btn">
                <div style="height: 23px;width: 55px;float: left;">
                    <p class="export-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
            <a id="return">
                <div style="height: 23px;width: 55px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <!--    <div id="load-content" class="overflow-auto"></div>-->
    <div class="space-30" style="height: 5px;"></div>

    <!--    <iframe id="count-data" style="width:100%;height: 200px;" src="" frameborder="0"></iframe>-->
    <!--    <div id="main_table"></div>-->
    <div id="data" style="width:100%;"></div>
    <div id="child_table_title"></div>
    <div id="child_table"></div>
</div>
<script>
    $(function () {
        var $mainTable = $("#data");
        var $childTableTitle = $("#child_table_title");
        var $childTable = $("#child_table");
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "chh_id",
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            pageSize: 10,
            pageList: [10, 20, 30, 40, 50],
            columns: [[
                {field: 'ck', checkbox: true},
                {field: "chh_code", title: "调拨单号", width: "150"},
                {field: "chh_status", title: "审核状态", width: "100"},
                {field: "chh_typeName", title: "调拨类型", width: "100"},
                {field: "organization", title: "调拨单位", width: "100"},
                {field: "wh_name", title: "调出仓库", width: "100"},
                {field: "o_status", title: "出库状态", width: "100"},
                {field: "wh_name2", title: "调入仓库", width: "100"},
                {field: "in_status", title: "入库状态", width: "100"},
//                {field: "whp_id", title: "调拨成本", width: "100"},
                {field: "create_at", title: "制单日期", width: "100"},
                {field: "create_by", title: "制单人", width: "100"},
                {
                    field: 'chh_id', title: '操作', width: 80, align:'center', formatter: function (value, rowData) {
                    var str = "<i>";
                    if (rowData.chh_status == "待提交" || rowData.chh_status == "驳回") {
                        <?php if(Menu::isAction('/warehouse/allocation/edit')){?>
                        str += "<a class='icon-trash icon-large' style='margin-right:15px;' title='删除' onclick='event.stopPropagation();deleteStockIn(" + value + ");'></a>";
                        <?php }?>
                        <?php if(Menu::isAction('/warehouse/allocation/delete')){?>
                        str += "<a class='icon-edit icon-large' style='margin-right:15px;' title='修改' onclick='location.href=\"<?=Url::to(['edit'])?>?id=" + value + "\";event.stopPropagation();'></a>";
                        <?php }?>
                    }
//                    str += "<a class='icon-eye-open icon-large' title='查看' onclick='location.href=\"<?//=Url::to(['views'])?>//?id=" + value + "\";event.stopPropagation();'></a>";
//                    str += "</i>";
                    return str;
                }
                }
            ]],
            onLoadSuccess: function () {
                datagridTip($mainTable);
                setMenuHeight();
                showEmpty($(this), data.total, 1);
//                alert(11);
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        if (rowData.chh_status != '待提交' && rowData.chh_status != '驳回') {
                            if(rowData.chh_status == '审核中'){
                                $("#e1").show();
                                $("#e2").hide();
                                $("#e3").hide();
                                $("#e6").hide();
                                $("#edit_btn").hide();
                                $("#check_btn").hide();
                                $("#delete_btn").hide();
                                $("#e10").show();
                                $("#inware_btn").hide();
                            }else{
                                $("#e1").show();
                                $("#e2").hide();
                                $("#e3").hide();
                                $("#e6").hide();
                                $("#edit_btn").hide();
                                $("#check_btn").hide();
                                $("#delete_btn").hide();
                                $("#inware_btn").show().next().show();
                            }

                        } else {
                            $("#e10").hide();
                            $("#inware_btn").hide();
                            $("#e1").show();
                            $("#e2").show();
                            $("#e3").show();
                            $("#e6").show();
                            $("#edit_btn").show();
                            $("#check_btn").show();
                            $("#delete_btn").show();
                        }
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                }else{
                    $childTableTitle.hide().next().hide();
                    for(var i=0;i<a1.length;i++) {
//                        alert(a1[i]['chh_status']);
                        if ((a1[i]['chh_status'] != '待提交') && (a1[i]['chh_status'] != '驳回')) {
                            if(a1[i]['chh_status'] == '审核中'){
                                $("#e1").hide();
                                $("#e2").hide();
                                $("#e3").hide();
                                $("#e6").hide();
                                $("#edit_btn").hide();
                                $("#check_btn").hide();
                                $("#delete_btn").hide();
                                $("#e10").show();
                                $("#inware_btn").hide();
                                break;
                            }else{
                                $("#e1").hide();
                                $("#e2").hide();
                                $("#e3").hide();
                                $("#e6").hide();
                                $("#edit_btn").hide();
                                $("#check_btn").hide();
                                $("#delete_btn").hide();
                                $("#e10").show();
                                $("#inware_btn").hide();
                                break;
                            }
                        }
                        else {
//                            $("#e10").hide();
                            $("#inware_btn").hide();
                            $("#e1").show();
                            $("#e2").show();
                            $("#e3").show();
                            $("#e6").show();
                            $("#edit_btn").show();
                            $("#check_btn").show();
                            $("#delete_btn").show();
                            $("#e10").hide();
                            $("#inware_btn").hide();
                        }
                    }
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
//                    isSelect=true;
//                    isCheck=true;
                    for(var d=0;d<a.length;d++) {
                        if ((a[d]['chh_status'] == '待提交') || (a[d]['chh_status'] == '驳回')) {
                            $("#e1").show();
                            $("#inware_btn").hide().next().hide();
//                            $("#e2").hide();
//                            $("#e3").show();
//                            $("#e6").show();
                            $("#edit_btn").show().next().show();
                            $("#check_btn").show().next().show();
                            $("#delete_btn").show().next().show();
                        }
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].chh_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    $childTableTitle.hide().next().hide();
                    $("#e1").show();
                    $("#edit_btn").hide().next().hide();
                    $("#check_btn").hide().next().hide();
                    $("#delete_btn").hide().next().hide();
                    $("#inware_btn").hide().next().hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
//                    $("#close").hide().next().hide();
//                    $("#delete").hide().next().hide();
                    $('#data').datagrid("unselectAll");
                    $("#load-content").hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else{
                    var a1 = $("#data").datagrid("getChecked");
                    for(var i=0;i<a1.length;i++) {
                        if ((a1[i]['chh_status'] != '待提交') && (a1[i]['chh_status'] != '驳回')) {
                            $("#e1").hide();
                            $("#e2").hide();
                            $("#e3").hide();
                            $("#e6").hide();
                            $("#edit_btn").hide();
                            $("#check_btn").hide();
                            $("#delete_btn").hide();
                            $("#e10").hide();
                            $("#inware_btn").hide();
                            break;
                        }
                        else {
                            $("#e1").show();
                            $("#e2").show();
                            $("#e3").show();
                            $("#e6").show();
                            $("#edit_btn").show();
                            $("#check_btn").show();
                            $("#delete_btn").show();
                        }
                    }
                }
            },
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.chh_id);
                $('#data').datagrid("uncheckAll");
                var a1 = $("#data").datagrid("getSelected");
                var id=a1.chh_id;
                if (rowData.chh_status != '待提交' && rowData.chh_status != '驳回') {
                    if(rowData.chh_status == '审核中'){
                        $("#e1").hide();
                        $("#e2").hide();
//                    $("#e10").show();
                        $("#e3").hide();
                        $("#e6").hide();
                        $("#edit_btn").hide();
                        $("#check_btn").hide();
                        $("#delete_btn").hide();
                        $("#inware_btn").hide();
                    }else{
                        $("#e1").show();
                        $("#edit_btn").hide().next().hide();
                        $("#check_btn").hide().next().hide();
                        $("#delete_btn").hide().next().hide();
                        $("#inware_btn").show().next().show();
                    }
                } else {
                    $("#e1").show();
                    $("#e2").show();
                    $("#e3").show();
                    $("#e6").show();
                    $("#edit_btn").show();
                    $("#check_btn").show();
                    $("#delete_btn").show();
                    $("#inware_btn").hide().next().hide();
                }
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
                $childTableTitle.addClass("table-head").css({
                    "margin-bottom": "5px",
                    "margin-top": "20px"
                }).html("<p class='head'>商品信息</p>");
                $childTable.datagrid({
                    url: "<?=Url::to(['load-product'])?>",
                    queryParams: {"id": rowData.chh_id},
                    rownumbers: true,
                    method: "get",
                    idField: "chh_id",
                    loadMsg: "加载数据请稍候。。。",
                    pagination: true,
                    singleSelect: false,
                    columns: [[
                        {field: "part_no", title: "料号", width: "150"},
                        {field: "pdt_name", title: "商品名称", width: "150"},
                        {field: "brand", title: "品牌", width: "100"},
                        {field: "tp_spec", title: "规格型号", width: "100"},
                        {field: "before_num1", title: "现有库存量", width: "100"},
                        {field: "chl_bach", title: "批次", width: "100"},
                        {field: "chl_num", title: "调拨数量", width: "100"},
                        {field: "st_id", title: "出仓储位", width: "100"},
                        {field: "st_id2", title: "入仓储位", width: "100"},
                        {field: "unit", title: "单位", width: "100"}
                    ]],
                    onLoadSuccess: function (messages) {
                        datagridTip($childTable);
                        showEmpty($(this), data.total, 1);
                    }
                });
            }
        });
        //申请时间
        $("#testpurchase-startdate").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                maxDate:"#F{$dp.$D('end-date',{d:-1})}"
            });
        });
        //结束时间
        $("#end-date").click(function(){
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd",
                minDate:"#F{$dp.$D('testpurchase-startdate',{d:1})}"
            });
        });
    });
    //输入id删除
    function deleteStockIn(id) {
        var val=$("._usertype").val();
        if(val==""||val==null){
            layer.alert("对不起,你不是管理员，无法删除", {icon: 2, time: 5000});
            return false;
        }else {
            layer.confirm('确定删除吗？', {icon: 2},
                function () {
                    $.ajax({
                        url: "<?=Url::to(['delete'])?>",
                        data: {
                            "id": id
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
//                            layer.alert(data.msg,{icon:1},function(){
//                                layer.closeAll();
//                                $("#main_table").datagrid('load').datagrid('clearSelections');
//                                $("#child_table_title").hide().next().hide();
//                            });\
                                layer.alert("删除成功！", {
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
    }
    //删除
    $("#delete_btn").on("click", function () {
        var val=$("._usertype").val();
        if(val==""||val==null){
            layer.alert("对不起,你不是管理员，无法删除", {icon: 2, time: 5000});
            return false;
        }else {
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
            } else {
                var selectId = $("#data").datagrid("getSelected")['chh_id'];
                layer.confirm("确定要删除这条信息吗?",
                    {
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {
                                "id": selectId
                            },
                            url: "<?=Url::to(['/warehouse/allocation/delete']) ?>",
                            success: function (msg) {
                                if (msg.flag === 1) {
                                    layer.alert("删除成功！", {icon: 1});
                                    $("#data").datagrid("reload", {
                                        url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                        onLoadSuccess: function () {
                                            $("#data").datagrid("clearChecked");
                                        }
                                    });
                                } else {
                                    layer.alert("删除失败！", {icon: 2});
                                }
                            },
                            error: function (msg) {
                                layer.alert("删除失败！", {icon: 2})
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
    //查看
    $("#view_btn").click(function () {
        var a = $("#data").datagrid("getSelected");
        if (a == null) {
            layer.alert("请点击选择一条信息", {icon: 2, time: 5000});
            return false;
        } else {
            var selectId = $("#data").datagrid("getSelected")['chh_id'];
            location.href = "<?=Url::to(['views'])?>?id=" + selectId;
        }
    });

    $("#add_btn").click(function () {
        location.href = "<?=Url::to(['add'])?>";
    });

    //修改
    $("#edit_btn").click(function () {
        var mainRow = $("#data").datagrid('getSelected');
        if (mainRow == null) {
            layer.alert('请点击选择一条信息！', {icon: 2, time: 5000});
            return false;
        }
        if (mainRow.chh_status == '待提交' || mainRow.chh_status == '驳回') {
            location.href = "<?=Url::to(['edit'])?>?id=" + mainRow.chh_id;
        }
    });


    //送审
    $("#check_btn").click(function () {
        var mainRow = $("#data").datagrid('getSelected');
        if (mainRow == null) {
            layer.alert('请点击选择一条信息！', {icon: 2, time: 5000});
            return false;
        }
        if (mainRow.chh_status == '待提交' || mainRow.chh_status == '驳回') {
//            layer.confirm("确定送审?", {icon: 2}, function () {
                var id = mainRow.chh_id;
//                alert(id);
                //var url = "<?=Url::to(['views'], true)?>?id=" + mainRow.chh_id;
                var url = "<?=Url::to(['index'], true)?>";
                var type = mainRow.chh_type;
//            alert(id+":"+type);
                $.fancybox({
                    href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
//            });
        }
    });

    //生成入库通知单
    $("#inware_btn").on("click",function(){
        //var ck=new Array();
        var a = $("#data").datagrid("getChecked");
        if(a[0]['o_status']=="已出库") {
            layer.alert("已经生成入库通知了,请重新选择!",{icon:2,time:5000});
        }else{
            if(a == null||a.length>1){
                layer.alert("请选择一条信息!",{icon:2,time:5000});
            } else {
                var id = $("#data").datagrid("getChecked")[0]['chh_id'];
                var index = layer.confirm("确定生成入库通知?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            async: false,
                            data: {"id": id},
                            url: "<?=Url::to(['/warehouse/allocation/in-ware']) ?>",
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
    });
    //导出
    $("#export_btn").click(function () {
        var index = layer.confirm("确定导出调拨单列表信息?",
            {
                btn: ['確定', '取消'],
                icon: 2
            },
            function () {
                if (window.location.href = "<?= Url::to(['index', 'export' => '1'])?>") {
                    layer.closeAll();
                } else {
                    layer.alert('导出列表信息发生错误', {icon: 0})
                }
            },
            function () {
                layer.closeAll();
            }
        )
    })

</script>
