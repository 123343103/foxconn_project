<?php
//dumpE(date("Y-m-d H:i:s"));
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = '待审核申请单列表';

?>
<style>
    .ml-25 {
        margin-left: 25px;
    }
</style>
<h1 class="head-fourth no-padding ml-20" style="margin-top:-20px;">
    <?= $this->title ?>
</h1>

<div class="table-head ml-25">
    <p class="float-left pt-5">
        <?= Html::a("<span class='text-center ml--5'>审核</span>", null, ['id' => 'audit']) ?>
        <?= Html::a("<span class='text-center ml--5 pl-10 pr-10'>批量审核</span>", null, ['id' => 'batch']) ?>
        <?= Html::a("<span class='text-center ml--5'>返回</span>", Url::to(['/index/index'])) ?>
    </p>
</div>
<div>
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div>
        <label class="width-100">表单类型</label>
        <select name="VerifyrecordSearch[but_code]" class="width-150" id="businessType">
            <option value="">---请选择---</option>
            <?php foreach ($type as $key => $val) { ?>
                <option
                    value="<?= $val['business_type_id'] ?>" <?= $queryParam['VerifyrecordSearch']['but_code'] && $queryParam['VerifyrecordSearch']['but_code'] == $val['business_type_id'] ? "selected" : null ?>><?= $val['business_type_desc'] ?></option>
            <?php } ?>
        </select>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="content">
    <div class="table-content mb-40">
        <div id="data"></div>
    </div>
    <div class="space-30"></div>
    <div>
        <div id="record"></div>
    </div>

</div>
<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "vco_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: 'ck', checkbox: true},
                {field: "businessType", title: "表单类型", width: 150},
                {
                    field: "vco_code", title: "申请单号", width: 150, formatter: function (val, row) {
                    return '<a href="<?= Url::to(['verify']) ?>?id=' + row.vco_id + '">' + val + '</a>';
                }
                },
                {
                    field: "applyName", title: "申请人", width: 150, formatter: function (val, row) {
                    if (row.applyPerson) {
                        return row.applyPerson.applyName;
                    } else {
                        return null;
                    }
                }
                },
                {
                    field: "vco_senddate", title: "申请日期", width: 150, formatter: function (val, row) {
                    return row.vco_senddate ? row.vco_senddate.substr(0, 10) : '';
                }
                },
                {
                    field: "applyOrg", title: "申请部门", width: 150, formatter: function (val, row) {
                    if (row.applyPerson) {
                        return row.applyPerson.applyOrg;
                    } else {
                        return null;
                    }
                }
                },
                {
                    field: "lastVerify", title: "上级审核人", width: 150, formatter: function (val, row) {
                    return row.lastVerify ? row.lastVerify : "/";
                }
                },
                {
                    field: "lastTime", title: "上级审核时间", width: 150, formatter: function (val, row) {
                    return row.lastTime ? row.lastTime : "/";
                }
                },
                {
                    field: "currentVerify", title: "当前审核人", width: 150, formatter: function (val, row) {
//                    return row.currentPerson?row.currentPerson:"/";
                    if (row.currentVerify) {
                        return '<span style="color:#f00;">' + row.currentVerify + '</span>';
                    } else {
                        return '<span style="color:#f00;">' + '/' + '</span>';
                    }

                }
                },
                {
                    field: "nextVerify", title: "下级审核人", width: 150, formatter: function (val, row) {
                    return row.nextVerify ? row.nextVerify : "/";
                }
                },
            ]],
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.vco_id);
                $('#data').datagrid("uncheckAll");
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
                var id = rowData['vco_id'];
                $("#record").datagrid({
                    url: "<?= \yii\helpers\Url::to(['/system/verify-record/load-record']);?>?id=" + id,
                    rownumbers: true,
                    method: "get",
                    idField: "vcoc_id",
                    loadMsg: false,
                    pagination: true,
                    singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
                    columns: [[
                        {field: "verifyOrg", title: "审核节点", width: 150},
                        {field: "verifyName", title: "审核人", width: 150},
                        {field: "vcoc_datetime", title: "审核时间", width: 156},
                        {field: "verifyStatus", title: "操作", width: 150},
                        {field: "vcoc_remark", title: "审核意见", width: 200},
                        {field: "vcoc_computeip", title: "审核IP", width: 150},

                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip('#record');
                        showEmpty($(this), data.total, 0);
                    }
                });
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].vco_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
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
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
            },
            onLoadSuccess: function (data) {
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            }
        });
//        $("#audit").on('click',function(){
//            $("#audit").removeAttr('href');
//            var a = $("#data").datagrid("getSelected");
//            var b = $("#data").datagrid("getChecked");
//            if (a == null && b.length == 0) {
//                layer.alert("请点击选择一条审核信息!", {icon: 2, time: 5000});
//                return false;
//
//            }else if(b.length == 1 && a == null){
//                $.each(b, function (index, val) {
//                    id = val.vco_id;
//                });
//                $("#audit").attr("href", "<?//= Url::to(['/system/verify-record/verify-one']) ?>//?id=" +id);
//                $("#audit").fancybox({
//                    padding: [],
//                    fitToView: false,
//                    width: 750,
//                    height: 530,
//                    autoSize: false,
//                    closeClick: false,
//                    openEffect: 'none',
//                    closeEffect: 'none',
//                    type: 'iframe'
//                });
//            }else if(b.length == 1 && a !=null){
//                var id = $("#data").datagrid("getSelected")['vco_id'];
//                $("#audit").attr("href", "<?//= Url::to(['/system/verify-record/verify-one']) ?>//?id=" +id);
//                $("#audit").fancybox({
//                    padding: [],
//                    fitToView: false,
//                    width: 750,
//                    height: 530,
//                    autoSize: false,
//                    closeClick: false,
//                    openEffect: 'none',
//                    closeEffect: 'none',
//                    type: 'iframe'
//                });
//            }else{
//                layer.alert('请选择一条申请',{icon:2});
//                return false;
//            }
//        });
        $("#audit").on('click', function () {
            $("#audit").removeAttr('href');
            var a = $("#data").datagrid("getSelected");
            if (a == null) {
                layer.alert("请点击选择一条审核信息!", {icon: 2, time: 5000});
                return false;
            } else {
                var id = $("#data").datagrid("getSelected")['vco_id'];
                $("#audit").attr("href", "<?= Url::to(['/system/verify-record/verify-one']) ?>?id=" + id);
                $("#audit").fancybox({
                    padding: [],
                    fitToView: false,
                    width: 750,
                    height: 530,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                });
            }
        });
        $("#batch").on('click', function () {
            $("#batch").removeAttr('href');
            var item = $("#data").datagrid('getChecked');
            if (item.length == 0) {
                layer.alert("请点击选择一条审核信息!", {icon: 2, time: 5000});
                return false;
            } else {
                var arr = [];
                $.each(item, function (index, val) {
                    if(val.bus_code !== 'credit'){
                        arr.push(val.vco_id);
                    }
                })
                if(arr.length == 0){
                    layer.alert("账信审核不能批量!", {icon: 2, time: 5000});
                    return false;
                }
                var str = arr.join(',');
                if (item.length == 1) {
                    $("#audit").click();
                    return false;
                } else {
                    $("#batch").attr("href", "<?= Url::to(['/system/verify-record/verify-all']) ?>?str=" + str);
                    $("#batch").fancybox({
                        padding: [],
                        fitToView: false,
                        width: 700,
                        height: 530,
                        autoSize: false,
                        closeClick: false,
                        openEffect: 'none',
                        closeEffect: 'none',
                        type: 'iframe'
                    });
                }
            }
        })
        $("#businessType").on('change', function () {
            $(this).submit();
        });
    });

</script>
