<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/8/1
 * Time: 上午 09:53
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \yii\widgets\ActiveForm;

?>
<h3 class="head-first">添加商品信息</h3>
<div class="content">
    <?php ActiveForm::begin(); ?>
    <div class="mb-20">
        <input type="hidden" class="_rows" value="<?= $params['row'] ?>">
        <label for="" class="width-100">关键字</label>
        <input type="text" class="width-200" placeholder="模糊搜索" name="kwd" id="kwd" value="<?= $params['kwd'] ?>">
        <button class="button-blue search ml-10" type="button">查询</button>
        <button class="button-white ml-10" type="reset">重置</button>
    </div>
    <?php ActiveForm::end(); ?>
    <div id="product-data" style="width:100%;"></div>
    <div class="mt-20 mb-20 text-center">
        <button class="button-blue ensure" type="button">确定</button>
        <button class="button-white cancel" type="button">取消</button>
    </div>
</div>
<script>
    $(function () {
        $("#product-data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "pdt_no",
            <?= !empty($params['wh_id']) ? "" : "singleSelect:true," ?>
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns: [[
                {field: "ck", checkbox: true},
                {field: "part_no", title: "料号", width: 140},
                {field: "pdt_name", title: "商品名称", width: 140},
                {field: "brand", title: "品牌", width: 100},
                {field: "tp_spec", title: "规格型号", width: 120},
                {field: "batch_no", title: "批次", width: 80},
                {field: "invt_nums", title: "批次", width: 80,hidden:'true'},
                {field: "st_code",title:"储位",width:80}
            ]],
            onLoadSuccess: function () {
                setMenuHeight();
            },
            onSelect: function (index, data) {
                $("#product-data").datagrid("clearChecked");
                $("#product-data").datagrid("checkRow", index);
            }
        });

        $(".ensure").click(function () {
            var products = parent.$("#products").val().split(",");
            var rows = $("#product-data").datagrid("getChecked");
            //删除商品信息中的空行
            parent.$("#product_tbody").find("tr").each(function () {
                var ls=$(this).find(".pdt_no").val();
                if(ls=="") {
                    $(this).remove();
                }
            });
//            var num = parent.$("#product_tbody").find("tr:last").data("id") + 1;
            var num = parent.$("#product_tbody").find("tr").length;
//            alert(num);
            if (rows.length > 0) {
                for (var x in rows) {
                    var str = "";
                    str = str + '<tr data-id=' + num + '> ' +
                        '<td align="center"></td> ' +
                        '<td align="center"> <input class="pdt_id" type="hidden"  value="' + rows[x].pdt_id + '">';
                    str = str + '<input type="hidden"  value="' + rows[x].brand + '">';
                    str = str + '<input type="hidden"  value="' + rows[x].bs_category_id + '"> ' +
                        '<input type="checkbox"> </td> ' +
                        '<td align="center"><input type="text" name="product[' + num + '][InvChangel][pdt_no]" ' +
                        'value="' + rows[x].part_no + '" style="width:100%;text-align:center;" ' +
                        'class="pdt_no easyui-validatebox" data-options="required:true"></td> ' +
                        '<td align="center">' + rows[x].pdt_name + '</td> ' +
                        '<td align="center">' + rows[x].brand + '</td> ' +
                        '<td align="center">' + rows[x].tp_spec + '</td>'+
                        '<td align="center"><input type="hidden" name="product[' + num + '][InvChangel][chl_bach]" ' +
                        'value="'+rows[x].batch_no+'"/>' + rows[x].batch_no + '</td>';
                    str = str + '<td align="center">' + rows[x].invt_num + '<input type="hidden" ' +
                        'name="product[' + num + '][InvChangel][before_num1]" value="' + rows[x].invt_num + '"></td> ' +
                        '<td align="center"><input type="text" class="width-100 chl_num" value="" ' +
                        'data-options="required:\'true\',validType:\'intnum\'"'+
                        'name="product[' + num + '][InvChangel][chl_num]"></td> '+
                        '<td align="center"><input type="hidden" class="width-100 Ost_id"  ' +
                        'name="product[' + num + '][InvChangel][st_id]" value="'+ rows[x].st_id +'"/>' + rows[x].st_code + '</td> ';
                    str = str + '<td align="center">' + rows[x].unit + '</td> ' +
                        '<td align="center"> <a class="delete_btn mr-20 icon-minus"></a> &nbsp;&nbsp;' +
                        '<a class="reset_btn icon-refresh"  onclick="refreshRow(this)"></a> </td> ' +
                        '</tr>';
                    num++;
                    var r=$("._rows").val();
                    if(r!=""){
                        parent.$("#product_tbody").find("tr").eq(r-1).remove();
                    }
                    parent.$("#product_tbody").append(str);
                    products.push(rows[x].pdt_id);
                    parent.$("#products").val(products.join(","));
                }

                if (products.length > 0) {
                    parent.$("#product_tbody+tfoot").hide();
                }

                for (var y = 0; y < parent.$("#product_tbody tr").size(); y++) {
                    parent.$("#product_tbody tr").eq(y).find("td:first").text(y + 1);
                    parent.$("#checkAll").prop("checked", parent.$("#product_tbody :checked").size() == parent.$("#product_tbody :checkbox").size() && parent.$("#product_tbody :checked").size() > 0);
                }
                parent.$("#product_tbody").find(".chl_num").validatebox({
                    required:true,
                    validType:'intnum'
                });
            }
            parent.$.fancybox.close();
        });
        $(".search").click(function () {
            $("#product-data").datagrid("load", {
                wh_id: $("#wh_id").val(),
                st_id: $("#st_id").val(),
                kwd: $("#kwd").val()
            })
        });
        $(".cancel").click(function () {
            parent.$.fancybox.close();
        });
    });
</script>
