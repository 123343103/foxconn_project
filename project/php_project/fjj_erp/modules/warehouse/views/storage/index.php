<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\classes\Menu;

$this->title = '储位设置';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '储位设置'];
?>
<?php $get = Yii::$app->request->get();
if (!isset($get['PartSearch'])) {
    $get['PartSearch'] = null;
}
?>
<style>
    .table-head p a > span:nth-child(1) {
        border-bottom: 0 solid #CCCCCC;
    }

    .table-heads {
        /*width: 990px;*/
        height: 30px;
    }

    .table-heads p {
        font-size: 16px;
        float: left;
    }

    .table-heads .float-right {
        float: right;
    }

    .table-heads .float-right a {
        min-width: 60px;
        height: 30px;
    }

    .table-heads .float-right a:hover .span-t {
        color: #2C85D3;
    }

    .table-heads .float-right a span {
        float: left;
    }
    .f-l{
        float: left;
    }
    .search-div {
        width: 990px;
    }
    .width-70{
        width: 70px;
    }
    .width-150{
        width: 150px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .ml-60{
        margin-left: 60px;
    }
    .ico{
        height: 23px;
        width: 60px;
        float: left;
    }
    .add{
       font-size: 14px;
       margin-top: 2px;
    }
    .update{
        font-size: 14px;
        margin-top: 2px;
    }
    .width-115{
        width: 115px;
    }
    .st{
        height: 23px;
        width: 75px;
        float: left;
    }
    .stc{
      font-size: 14px;
      margin-top: 2px;
    }
    .return{
      font-size: 14px;
      margin-top: 2px;
    }
    .mt-4{
        margin-top: 4px;
    }
</style>
<div class="search-div">
    <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]
    ); ?>
    <div class="content">
        <div class="space-30"></div>
        <div class="mb-10">
            <div class="inline-block" style="margin-left: 2px">
                <label class="ml-5 width-70 qlabel-align" for="partsearch-wh_name">仓库名称:</label>
                <input class="width-150 qvalue-align" type="text" id="partsearch-wh_name" name="PartSearch[wh_name]"
                       value="<?= $get['PartSearch']['wh_name'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-20">
                <label class="width-70 ml-5 qlabel-align" for="partsearch-part_code">分区码:</label>
                <input class="width-150 qvalue-align" type="text" id="partsearch-part_code" name="PartSearch[part_code]"
                       value="<?= $get['PartSearch']['part_code'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-20">
                <label class="ml-5 width-70" for="partsearch-part_name">区位名称:</label>
                <input class="width-150 qvalue-align" type="text" id="partsearch-part_name" name="PartSearch[part_name]"
                       value="<?= $get['PartSearch']['part_name'] ?>"
                >
                <div class="help-block"></div>
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block ">
                <label class="width-70 qlabel-align" style="margin-left: 0"  for="partsearch-rack_code">货架码:</label>
                <input class="width-150 qvalue-align" style="margin-left: 2px" type="text" id="partsearch-rack_code"   name="PartSearch[rack_code]"
                       value="<?= $get['PartSearch']['rack_code'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-20">
                <label class="ml-5 width-70 qlabel-align" for="partsearch-st_code">储位码:</label>
                <input type="text" id="partsearch-st_code" class="width-150 qvalue-align" name="PartSearch[st_code]"
                       value="<?= $get['PartSearch']['st_code'] ?>"
                >
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-20">
                <label class="width-70 qlabel-align" for="partsearch-yn">状态:</label>
                <select class="width-150 qvalue-align" id="partsearch-yn" name="PartSearch[YN]">
                    <option value="3">全部</option>
                    <option
                            value="启用" selected="selected"<?= isset($get['PartSearch']['YN']) && $get['PartSearch']['YN'] == '启用' ? "selected" : null ?>>
                        启用
                    </option>
                    <option
                            value="禁用" <?= isset($get['PartSearch']['YN']) && $get['PartSearch']['YN'] == '禁用' ? "selected" : null ?>>
                        禁用
                    </option>
                </select>
                <div class="help-block"></div>
            </div>
            <div class="inline-block ml-60">
                <?= Html::submitButton('查询', ['class' => 'search-btn-blue']) ?>
                <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow','style'=>'margin-left:20px', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="table-heads">
        <p class="head">储位列表</p>
        <div class="float-right">
            <?= Menu::isAction('/warehouse/storage/add') ?
                "<a id='add'>
                    <div class='table-nav'>
                        <p class='add-item-bgc float-left'></p>
                        <p class='nav-font'>新增</p>
                    </div>
                </a>"
                : '' ?>
            <span class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
            <?= Menu::isAction('/warehouse/storage/update') ?
                " <a id='edit' class='display-none'>
                    <div class='table-nav'>
                        <p class='update-item-bgc float-left'></p>
                        <p class='nav-font'>修改</p>
                    </div>
           </a>"
                : '' ?>
            <span id="sp" class='display-none' style='float: left;'>&nbsp;|&nbsp;</span>
                <a id='enable' class='display-none' data-id="Y">
                    <div class='table-nav'>
                        <p class='submit-item-bgc f-l'></p>
                        <p class='nav-font'>启用</p>
                    </div>
               </a>
                <a id='disable' class='display-none' data-id="N">
                    <div class='table-nav'>
                        <p class='setbcg9 f-l'></p>
                        <p class='nav-font'>禁用</p>
                    </div>
                </a>
            <span style='float: left;'>&nbsp;|&nbsp;</span>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="mt-10">
        <div id="data">
        </div>
    </div>
    <div id="load-content" class="overflow-auto"></div>
</div>
<script>
    var isCheck = false; //是否点击复选框
    var isSelect = false; //是否点击单条
    var onlyOne = false; //是否只选中单个复选框
    $(function () {
        //严格模式
        "use strict";

        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "st_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            pageSize: 10,
            pageList: [10, 20, 30, 40, 50],
            columns: [[
                {field: 'ck', checkbox: true},
                {field: "wh_name", title: "仓库名称", width: "100"},
                {field: "part_code", title: "分区码", width: "100"},
                {field: "part_name", title: "区位名称", width: "100"},
                {field: "rack_code", title: "货架码", width: "100"},
                {field: "st_code", title: "储位码", width: "100"},
                {field: "YNs", title: "状态", width: "100", class: "st_yn"},
                {field: "remarks", title: "备注", width: "303"},
                {field: "OPPER", title: "操作人", width: "80"},
                {field: "OPP_DATE", title: "操作时间", width: "278"},
                {field: "st_id", title: "操作", width:60, formatter: function (value, rowData, rowIndex) {
//                    console.log(value);
//                    console.log(rowData);
//                    console.log(rowIndex);
                    var str="";
                    if (rowData.YN == "启用") {
//                        str = "<a class='operate'><p style='margin:0 auto;' class='open-close' title='关闭仓库' ></p></a><input type='hidden' class='wh_codes' value='" + rowData.wh_code + "' ><input type='hidden' class='wh_yn' value='" + rowData.wh_state + "'>"
                        str+="<a class='operate icon-check-minus icon-large' title='禁用'></a><input type='hidden' class='wh_codes' value='" + rowData.wh_code + "' ><input type='hidden' class='wh_yn' value='" + rowData.wh_state + "'>";
                    } else {
                        str+="<a class='operate icon-check-sign icon-large' title='启用'></a><input type='hidden' class='wh_codes' value='" + rowData.wh_code + "' ><input type='hidden' class='wh_yn' value='" + rowData.wh_state + "'>";
//                        str = "<a class='operate'><p style='margin:0 auto;' class='open-close' title='开启仓库' ></p></a><input type='hidden' class='wh_codes' value='" + rowData.wh_code + "' ><input type='hidden' class='wh_yn' value='" + rowData.wh_state + "'>"
                    }
                    str+="<a  class='icon-edit icon-large' title='修改' onclick='updateStorage(" + rowData.st_id + ")' style='margin-left:15px;'></a>";
                    return str;
                }}
            ]],
            onCheck: function (rowIndex, rowData) {  //checkbox 选中事件
                //设置选中事件，清除之前单行选择
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        if(rowData.YN=='启用'){
                            $("#sp").removeClass("display-none");
                            $("#disable").removeClass("display-none");
                        }else {
                            $("#sp").removeClass("display-none");
                            $("#enable").removeClass("display-none")
                        }
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                } else {
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                    $("#edit").hide();
                    $("#edit").prev().hide();

                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].st_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#edit").hide();
                    $("#edit").prev().hide();
                    $("#disable").addClass("display-none");
                    $("#enable").addClass("display-none");
                    $("#sp").addClass("display-none");
                    $('#data').datagrid("unselectAll");
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
            },
            onLoadSuccess: function () {
                setMenuHeight();
                datagridTip("#data");
            },
            onCheckAll: function (rowIndex, rowData) {  //checkbox 全选中事件
                //设置选中事件，清除之前单行选择
                var data =rowIndex[0];
                $("#data").datagrid("unselectAll");
                if(data.YN=="启用"){
                    $("#sp").removeClass("display-none");
                    $("#disable").removeClass("display-none");
                }else {
                    $("#sp").removeClass("display-none");
                    $("#enable").removeClass("display-none");
                    $("#disable").addClass("display-none");
                 }
                $("#edit").hide();
                $("#edit").prev().hide();
            },
            onUncheckAll: function (rowIndex, rowData) {
                $("#disable").addClass("display-none");
                $("#enable").addClass("display-none");
                $("#sp").addClass("display-none");
                isCheck = false;
                isSelect = false;
                onlyOne = false;
            },
            onSelect: function (rowIndex,rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.st_id);
                $('#data').datagrid("uncheckAll");
                if (isCheck && !isSelect && !onlyOne) {
                    var a = $("#data").datagrid("getChecked");
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;isCheck = false;
                }
                $('#data').datagrid('checkRow', index);
                if(rowData.YN=='启用'){
                    $("#sp").removeClass("display-none");
                    $("#disable").removeClass("display-none");
                    $("#enable").addClass("display-none");
                }else{
                    $("#sp").removeClass("display-none");
                    $("#enable").removeClass("display-none")
                    $("#disable").addClass("display-none");
                }
                $("#edit").show();
                $("#edit").prev().show();
            }
        });
        var partsearchyn="<?=$get['PartSearch']['YN']?>";
        if(partsearchyn=="3"){
            $('#partsearch-yn').val(3);
        }
    });

    $(".mt-10").delegate(".operate", "click", function () {
        var row = $("#data").datagrid('getSelected');
        if (row) {
            var YN = row['YN'];
            var part_id = row['part_id'];
            var openclose = "";
            if (YN == '启用') {
                openclose = "确定将此数据禁用？";
            } else {
                openclose = "确定将此数据启用吗？";
            }

            layer.confirm(openclose, {
                btn: ['确定', '取消'],
                icon: 2
            }, function () {
                $.ajax({
                    url: "<?=Url::to(['update-stid'])?>",
                    data: {
                        "st_id": row.st_id,
                        "staff_id":<?= Yii::$app->user->identity->staff_id ?>
                    },
                    dataType: "json",
                    success: function (data) {
//                console.log(data);
                        if (data.flag == 1) {
                            layer.alert(data.msg, {icon: 1});
                            $("#data").datagrid("reload", {
                                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                onLoadSuccess: function () {
                                    $("#data").datagrid("clearChecked");
                                }
                            });
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                    }
                });
            }, function () {
                layer.closeAll();
            });
        }
    });
    //批量设置启用状态
    $("#enable").on("click",function () {
        var arr = [];
        var id;
        var obj = $("#data").datagrid("getChecked");
        var openclose = "";
        var YN=$(this).data("id");
        for (var i = 0; i < obj.length; i++) {
            arr.push(obj[i].st_id);
        }
        id = arr.join(',');
        openclose = "确定将此数据启用吗？";
        layer.confirm(openclose, {
            btn: ['确定', '取消'],
            icon: 2
        }, function () {
            $.ajax({
                url: "<?=Url::to(['bulk-enable'])?>",
                data: {
                    "id": id,
                    "yn":YN,
                    "opper":<?= Yii::$app->user->identity->staff_id ?>
                },
                dataType: "json",
                success: function (data) {
//                console.log(data);
                    if (data.flag == 1) {
                        layer.alert(data.msg, {icon: 1});
                        $("#data").datagrid("reload", {
                            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                            onLoadSuccess: function () {
                                $("#data").datagrid("clearChecked");
                            }
                        });
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            });
        }, function () {
            layer.closeAll();
        });
    });
    //批量设置禁用状态
    $("#disable").on("click",function () {
        var arr = [];
        var id;
        var obj = $("#data").datagrid("getChecked");
        var openclose = "";
        var YN=$(this).data("id");
        for (var i = 0; i < obj.length; i++) {
            arr.push(obj[i].st_id);
        }
        id = arr.join(',');
        openclose = "确定将此数据禁用？";
        layer.confirm(openclose, {
            btn: ['确定', '取消'],
            icon: 2
        }, function () {
            $.ajax({
                url: "<?=Url::to(['bulk-enable'])?>",
                data: {
                    "id": id,
                    "yn":YN,
                    "opper":<?= Yii::$app->user->identity->staff_id ?>
                },
                dataType: "json",
                success: function (data) {
//                console.log(data);
                    if (data.flag == 1) {
                        layer.alert(data.msg, {icon: 1});
                        $("#data").datagrid("reload", {
                            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                            onLoadSuccess: function () {
                                $("#data").datagrid("clearChecked");
                            }
                        });
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            });
        }, function () {
            layer.closeAll();
        });
    })

    //添加
//    $("#add").click(function () {
//        location.href="<?//=Url::to('add')?>//";
//    });
    $("#add").on("click",function () {
        $.fancybox({
            padding: [],
            fitToView: true,
            width:600,
            height: 500,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['add'])?>"
        });
    })
//    $("#add").on("click", function () {
//        $.fancybox({
//            type: "iframe",    //自适应高度
//            width: document.body.clientWidth ,
//            height: document.body.clientHeight ,
//            autoSize: false,
//            margin:13,
//            href: "<?//=Url::to(['add'])?>//",
//            padding: 0
//        });
//    });
    //设置状态
//    $("#setcharacter").on("click", function () {
//        $.fancybox({
//            type: "iframe",
//            width: 600,
//            height: 300,
//            autoSize: false,
//            href: "<?//=Url::to(['set-character'])?>//",
//            padding: 0
//        });
//    });

    $("#setcharacter").on("click", function () {
        var row = $("#data").datagrid("getSelected");
        if (!row) {
            layer.alert("请点击选择一条信息", {icon: 2})
            return false;
        }
        $.fancybox({
            type: "iframe",
            width: 600,
            height: 300,
            autoSize: false,
            href: "<?=Url::to(['set-character'])?>?st_id=" + row.st_id,
            padding: 0
        });
    });
    //点击儲位码显示详情
    $(".mt-10").delegate(".details", "click", function () {
        var row = $("#data").datagrid("getSelected");
        $.fancybox({
            padding: [],
            fitToView: true,
            width:500,
            height:450,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['views'])?>?st_id=" + row.st_id
        });
    });
    //修改
    $("#edit").on("click", function () {
        var row = $("#data").datagrid("getSelected");
        updateStorage(row.st_id)
    });
    function updateStorage($id) {
        $.fancybox({
            padding: [],
            fitToView: true,
            width: 600,
            height: 550,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= Url::to(['update'])?>?st_id=" + $id
        });
    }
    //导出
    $("#export").on("click", function () {
//        window.location.href = "<?//=Url::to(['index', 'export' => 1])?>//";
        var page = $("#data").datagrid("getPager").data("pagination").options.pageNumber;
        var rows = $("#data").datagrid("getPager").data("pagination").options.pageSize;
        var index = layer.confirm("确定导出商品信息?",
            {
                btn: ['确定', '取消'],
                icon: 2
            },
            function () {
                if (window.location.href = "<?= Url::to(['index', 'export' => '1'])?>") {
                    layer.closeAll();
                } else {
                    layer.alert('导出商品信息发生错误', {icon: 0})
                }
            },
            function () {
                layer.closeAll();
            }
        )
    });

    //返回
    $("#return").on("click", function () {
        window.location.href = "<?=Url::home()?>";
    });
</script>