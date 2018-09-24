<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/20
 * Time: 上午 08:48
 */
use app\classes\Menu;
use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '仓库费用确认单', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '仓库费用确认单'];
$this->title = '仓库费用确认单';
?>
<style>
    .mb-10 {
        margin-bottom: 10px;
    }

    .display {
        display: none;
    }

    #btn_pdt {
        width: 100px;
        background-color: #FFFFFF;
    }

    #btn_cost {
        width: 100px;
        border-left: 0px;
        background-color: #a4a4a4;
    }


</style>
<div class="content">
    <?= $this->render("_search", [
        "get" => Yii::$app->request->get(),
        'downList' => $downList]) ?>
    <div class="mb-10"></div>
    <div class="table-head">
        <p class="head">费用确认列表</p>
        <div class="float-right">
            <?= Menu::isAction('/warehouse/wh-print/create') ? "<a id='export'>
                <div class='table-nav'>
                    <p class='add-item-bgc float-left'></p>
                    <p class='nav-font'>导出</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left">&nbsp;|&nbsp;</p>
            <?= Menu::isAction('/warehouse/wh-print/update') ? "<a id='sendverify' style='display:none;'>
                <div class='table-nav'>
                    <p class='update-item-bgc float-left'></p>
                    <p class='nav-font'>送审</p>
                </div>
            </a>"
                : '' ?>
            <p class="float-left" id="sendverify1" style='display:none;'>&nbsp;|&nbsp;</p>
            <a href="<?= Url::to(['/index/index']) ?>">
                <div class='table-nav'>
                    <p class='return-item-bgc float-left'></p>
                    <p class='nav-font'>返回</p>
                </div>
            </a>

        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
        <div class="mb-10"></div>
        <div id="price_list_title"></div>
        <div class="mb-10"></div>

        <div id="div_pdt_list">
            <div id="pdt_list" style="width:100%;"></div>
        </div>
        <div id="div_price_list">
            <div id="price_list" style="width:100%;"></div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#data').datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "whp_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            pageSize: 10,
            pageList: [10, 20, 30],
            columns: [[
                {field: 'ck', checkbox: true},
                <?= $fields ?>
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#data");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#data").datagrid('clearSelections').datagrid('clearChecked');
                $("#update").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) { //行选择触发事件

                $("#data").datagrid("checkRow",rowIndex);
                var index = $("#data").datagrid("getSelected");
                var invh_id = index['invh_id'];
                var state = index['audit_status']
                $("#price_list_title").addClass("table-head mb-5 mt-20").html("<p class='head'><input onclick='pdt()' value='商品信息' id='btn_pdt' type='button'/><input value ='费用' id='btn_cost' type = 'button' onclick = 'cost()'/></p>");
                if (state == '待提交' || state == '驳回') {

                    $("#sendverify").show();
                    $("#sendverify1").show();
                }
                $("#pdt_list").datagrid({
                    url: "<?= Yii::$app->request->getHostInfo() . Url::to(['get-pdt']) ?>?invh_id=" + invh_id,
                    rownumbers: true,
                    method: "get",
                    idField: "whpl_id",
                    loadMsg: "加载中...",
                    checkOnSelect: false,
                    selectOnCheck: false,
                    pagination: true,
                    singleSelect: true,
                    columns: [[ <?= $fields_child ?>
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#price_list_title");
                        showEmpty($(this), data.total, 1);
                        setMenuHeight();
                        $("#price_list_title").datagrid('clearSelections').datagrid('clearChecked');

                    }
                    ,
                });


                // $("#div_price_list").hide();

            },
        })
        ;
        $('#create').click(function () {
            location.href = "<?= Url::to(['create']) ?>";
        });
        $('#update').click(function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var whp_id = row['whp_id'];
                update(whp_id);
            }
            // location.href = "<? //=Url::to(['update'])?>//?whp_id=" + whp_id;
        });
        //送审
        $('#sendverify').click(function () {
            var rows = $("#data").datagrid("getChecked");
            if (rows.length == 0) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
                return false;
            }
            var WhpIdArry = new Array();
            $.each(rows, function (i) {
                WhpIdArry.push(rows[i].invch_id);
            });
            var invch_id = WhpIdArry.join(",");

//            alert(invch_id);
//            console.log(invch_id);
//            return false;
            var id =invch_id;
            var url="<?=Url::to(['index'],true)?>";
            var type=59;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });
        //导出

        $('#export').click(function () {
            layer.confirm("确定导出信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = " <?=Url::to(['index', 'export' => '1'])?>") {
                        layer.closeAll();
                    } else {
                        layer.alert('导出信息出错!', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            );
        });

    })
    function pdt() {
        $('#div_pdt_list').show();
        $("#div_price_list").hide();
        $('#btn_pdt').css('background-color', '#FFFFFF');
        $('#btn_cost').css('background-color', '#a4a4a4');

    }
    function cost() {
        // alert(2);
        var index = $("#data").datagrid("getSelected");
        var invh_id = index['invh_id'];
        $("#price_list").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Url::to(['get-cost']) ?>?invh_id=" + invh_id,
            rownumbers: false,
            method: "get",
            idField: "whpl_id",
            loadMsg: "加载中...",
            checkOnSelect: false,
            selectOnCheck: false,
            pagination: true,
            singleSelect: true,
            columns: [[
                {
                    field: "whpb_sname", title: "费用类型", width: "34%", formatter: function (value, row, index) {
                    var a = '<input type="text" value="' + row.invcl_id + '" class="display" name="IcInvCostlist[invcl_id]"/>';
                    return value + a;
                }
                },
                {
                    field: "invcl_nprice", title: "费用", width: "33%", formatter: function (value, row, index) {
                    var a = '<input class="display" type="text" value="' + value + '" style="width: 50%" name="IcInvCostlist[invcl_nprice]"/><select class="display" style = "width: 50%" ><option value = "1" > RMB </option></select>';
                    return '<label>' + value + '  RMB</label>' + a;
                }
                },
                {
                    field: "handle", title: "操作", width: "33%", formatter: function (value, row, index) {

                    return '<a onclick="updatePrice(this)">修改</a><a onclick="submit(this)" class="display">确定</a>'
                }
                }
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#price_list_title");
                showEmpty($(this), data.total, 1);
                setMenuHeight();
                $("#price_list_title").datagrid('clearSelections').datagrid('clearChecked');

            }
            ,
        });
        $('#div_pdt_list').hide();
        $("#div_price_list").show();

        $('#btn_pdt').css('background-color', '#a4a4a4');
        $('#btn_cost').css('background-color', '#FFFFFF');
    }

    //修改
    function updatePrice(obj) {
        var td = $(obj).parents("tr").find("td");
        td.eq(1).find('label').addClass("display");
        td.eq(1).find('select').removeClass("display");
        td.eq(1).find('input').removeClass("display");
        td.eq(2).find('a').removeClass("display");
        td.eq(2).find('a:first').addClass("display");
    }
    //修改后确认
    function submit(obj) {
        var index = $("#data").datagrid("getSelected");
        var invh_id = index['invh_id'];
        var td = $(obj).parents("tr").find("td");
        td.eq(1).find('label').removeClass("display");
        td.eq(1).find('select').addClass("display");
        td.eq(1).find('input').addClass("display");
        td.eq(2).find('a').removeClass("display");
        td.eq(2).find('a:eq(1)').addClass("display");
        var invcl_id = td.eq(0).find('input').val();
        var invcl_nprice = td.eq(1).find('input').val();
//        var whpb_num = td.eq(2).find('input').val();
//        var whpb_curr = td.eq(2).find('select').val();
        $.get({
            url: "<?=Url::to(['update-nprice'])?>",
            data: {invcl_id: invcl_id, invcl_nprice: invcl_nprice},
            dataType: "JSON",
            success: function (res) {
                if (res.flag == 1) {
                    layer.alert(res.msg, {icon: 2});
                    $("#price_list").datagrid("reload", {
                        url: "<?=Yii::$app->request->getHostInfo() . Url::to(['get-cost']) ?>?invh_id=" + invh_id,
                    });
                } else {
                    layer.alert(res.msg, {icon: 2})
                }
            }
        })
    }
</script>
