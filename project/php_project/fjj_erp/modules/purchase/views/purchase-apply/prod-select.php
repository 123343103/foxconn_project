<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/12/9
 * Time: 上午 08:18
 */
\app\assets\JqueryUIAsset::register($this);
?>
<style>
    .datagrid-empty{line-height: 25px;}
</style>
<h1 class="head-first">添加商品</h1>

<div class="content">
    <form id="search-form" action="">
        <div>
            <div class="mb-10">
                <input type="hidden" id="filters" name="filters" value="<?= \Yii::$app->request->get('filters') ?>">
                <label style="width: 50px;" for="">类别：</label>
                <select style="width: 150px;" name="catg_id" id="category">
                    <option value=""></option>
                </select>
                <label style="width: 100px;" for="">料号/品名：</label>
                <input id="kwd" name="kwd" type="text" style="width: 180px;"
                       value="<?= \Yii::$app->request->get('kwd') ?>">
                <button type="button" class="button-blue" style="margin-left: 10px;" id="query">查询</button>
                <button type="button" class="button-white" style="margin-left: 10px;" id="reset">重置</button>
            </div>
        </div>
    </form>
    <div id="product-data" style="width: 100%;"></div>
    <div class="space-10"></div>
    <div id="selected-area" style="border:lightgrey 1px solid;padding:10px;height: 100px;overflow-y: auto;"></div>
    <div class="space-10"></div>
    <div class="text-center">
        <button class="button-blue ensure">确定</button>
        <button class="button-white cancel">取消</button>
    </div>
</div>

<script>
    $(function () {
        $.ajax({
            type: 'get',
            async: false,
            url: '<?=\yii\helpers\Url::to(['get-next-level', 'prompt' => 0])?>',
            success: function (data) {
                $("#category").html(data);
            }
        });
        $("#product-data").datagrid({
            url: "<?=\yii\helpers\Url::to(['prod-select'])?>",
            rownumbers: true,
            method: "get",
            idField: "part_no",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            queryParams: {
                pdt_status: 1,
                filters: $("#filters").val(),
                kwd: $("#kwd").val(),
                catg_id: $("#category").val()
            },
            columns: [[
                {field: "ck", checkbox: true, width: 50},
                {field: "part_no", title: "料号", width: 200},
                {field: "pdt_name", title: "品名", width: 200},
                {field: "tp_spec", title: "规格", width: 195}
            ]],
            onLoadSuccess: function (data) {
                datagridTip('#product-data');
                $(this).datagrid("resize");
                $(".datagrid-view").css("min-height","45px");
//                $("#selected-area").empty();
//                var rows_sel = $("#product-data").datagrid("getSelections");
//                var rows_chk = $("#product-data").datagrid("getChecked");
//                rows_sel.splice(0, rows_sel.length);
//                rows_chk.splice(0, rows_chk.length);

            },
            onSelect: function (index, row) {
                updateProduct();
            },
            onUnselect: function (index, row) {
                updateProduct();
            },
            onCheckAll: function () {
                updateProduct();
            },
            onUncheckAll: function () {
                updateProduct();
            }

        });


        $("#selected-area").on("click", ".remove", function () {
            var pk = $(this).parent(".item").data("pk");
            console.log(pk);
            $(this).parent(".item").remove();
            $(".datagrid-btable tbody tr").each(function () {
                var rows_sel = $("#product-data").datagrid("getSelections");
                var rows_chk = $("#product-data").datagrid("getChecked");
                var part_no = $(this).find("td").eq(1).find("div").text();
                $.each(rows_sel, function (index, row) {
                    if (row.part_no != part_no) {
                        if(row.part_no==pk) {
//                            console.log(rows_chk);
//                            console.log(index);
                            rows_sel.splice(index, 1);
                            rows_chk.splice(index, 1);
                            return false;
                        }
                    }
                });
                if (part_no == pk) {
//                    console.log(part_no);
//                    alert(123);
                    $(this).find("td").eq(0).find("input").attr("checked", false);
                    $(this).removeClass("datagrid-row-selected");
                    $(this).removeClass("datagrid-row-checked");
                    $(".datagrid-view2").find(".datagrid-htable").find("tbody").find("tr").eq(0).find("td").eq(0).find("input").attr("checked", false);
                    $.each(rows_sel, function (index, row) {
                        if (row.part_no == pk) {
//                            console.log(rows_chk);
//                            console.log(index);
                            rows_sel.splice(index, 1);
                            rows_chk.splice(index, 1);
                            return false;
                        }
                    });
                }
            });
            var rows = $("#product-data").datagrid("getRows");
            $.each(rows, function (index, row) {
                if (row.prt_pkid == pk) {
                    var rowIndex = $("#product-data").datagrid("getRowIndex", row);
                    $("#product-data").datagrid("unselectRow", rowIndex);
                    return false;
                }
            });
        });

        $("#query").click(function () {
            $("#product-data").datagrid("load", {
                catg_id: $("#category").val(),
                kwd: $("#kwd").val(),
                filters: $("#filters").val()
            });
        });

        $("#reset").click(function () {
            $("#kwd").val("");
            $("#category :selected").prop("selected", false);
            $("#product-data").datagrid("load", {
                pdt_status: 1,
                filters: $("#filters").val(),
                kwd: $("#kwd").val(),
                catg_id: $("#category").val()
            });
            $("#product-data").datagrid("clearSelections");
        });

        $(".ensure").click(function () {
            var rows = $("#product-data").datagrid("getSelections");
            if (rows.length == 0) {
                layer.alert("请至少选择一个商品", {icon: 2});
                return false;
            }
            parent.$.fancybox.close();
            try {
                parent.productSelectorCallback(rows);
            } catch (err) {
                console.log(err.message);
            }
        });

        $(".cancel").click(function () {
            parent.$.fancybox.close();
        });
    });

    function updateProduct() {
        var rows = $("#product-data").datagrid("getSelections");
        $("#selected-area").empty();
        $.each(rows, function (index, row) {
            var item = $("<div class='item'></div>").text(row.part_no).data("pk", row.part_no).css({
                width: "150px",
                "height": "30px",
                "line-height": "30px",
                "float": "left"
            });
            var remove = $("<span class='remove'>&times;</span>").css({"cursor": "pointer", "color": "red"});
            item.append(remove);
            $("#selected-area").append(item);
        });
    }
</script>