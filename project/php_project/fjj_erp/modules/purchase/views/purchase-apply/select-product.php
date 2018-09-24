<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
<style>
    .table span {
        background-color: #1f7ed0;
        font-weight: 100;
        color: #fff;
    }
</style>
<div class="head-first">选择商品</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id' => 'product_form', 'method' => 'get', 'action' => Url::to(['select-product'])]); ?>
        <label class="width-60">关键词</label>
        <input type="text" class="width-200" id="searchText" name="searchKeyword"
               value="<?= $params['searchKeyword'] ?>">
        <button type="button" class="button-blue" id="search">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?= Url::to(['select-product']) ?>'">
            重置
        </button>
        <?php ActiveForm::end(); ?>
    </div>
    <table id="product_data" style="width:100%;"></table>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_product">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    $(function () {
        var $dg = $("#product_data");
        var fieldArr = new Array();
        <?php foreach ($columns as $key => $val) { ?>
        fieldArr[<?= $key ?>] = "<?= $val["field_field"] ?>";
        <?php } ?>
        var row = "<?= $params['row'] ?>";

        $dg.datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
//            check: true,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10],
            columns: [[
                {field: "", checkbox: true, width: 28},
                {field: "pdt_no", title: "料号", width: 140},
                {field: "pdt_name", title: "品名", width: 120},
                {field: "wh_name", title: "品牌", width: 100},
                {field: "invt_num", title: "规格", width: 100},
                {field: "invt_num1", title: "采购方式", width: 50},
                {field: "invt_num2", title: "单位", width: 60},
                {field: "invt_num3", title: "单价", width: 89}
            ]],
            onLoadSuccess: function (data) {
                $(".pagination-info").css("float", "left");
                $(".pagination-info").css("margin", "0 0 0 -430px");
                $(".pagination table").css("float", "left");
                $(".pagination table").css("margin", "0 0 0 140px");
                $dg.datagrid('clearSelections');
                datagridTip("#product_data");
                showEmpty($(this), data.total, 1);
            }
        });

        //添加到parent
        $("#confirm_product").click(function(){
            var rows=$dg.datagrid('getChecked');
            if(rows.length==0){
                layer.alert('请选择商品！',{icon:2});
                return false;
            }
            $.each(parent.$(".pdt_id"),function(i,n){
                if(n.value==''){
                    $(n).parents("tr").remove();
                }
            });
            $.each(rows,function(i,n){
                var tr="<tr>";
                tr+="<td></td>";
                tr+="<td><input type='checkbox' style='height:auto;vertical-align:middle;'></td>";
                tr+="<input class='pdt_id' type='hidden' name='product["+parent.num+"][IcInvl][pdt_id]' value='"+n.pdt_id+"'></td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='pdt_no easyui-validatebox' data-options='required:true' data-url='<?=Url::to(['/warehouse/other-stock-in/get-product-info'])?>' value='"+n.pdt_no+"'></td>";
                tr+="<td class='pdt_name'>"+n.pdt_name+"</td>";
                tr+="<td class='BRAND_NAME_CN'>"+n.BRAND_NAME_CN+"</td>";
                tr+="<td class='ATTR_NAME'>"+n.ATTR_NAME+"</td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"length[0,20]\"' name='product["+parent.num+"][IcInvl][batch_no]'></td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"six_decimal\"' name='product["+parent.num+"][IcInvl][in_quantity]'></td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"six_decimal\"' name='product["+parent.num+"][IcInvl][real_quantity]'></td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"six_decimal\"' name='product["+parent.num+"][IcInvl][in_warehouse_quantity]'></td>";
                tr+="<td class='unit_name'>"+n.unit_name+"</td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"length[0,20]\"' name='product["+parent.num+"][IcInvl][pack_type]'></td>";
                tr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"six_decimal\"' name='product["+parent.num+"][IcInvl][pack_quantity]'></td>";
                tr+="<td><select class='lor_id' style='width:100%;' name='product["+parent.num+"][IcInvl][lor_id]'><option value=''>请选择</option>";
//                if(parent.locationInfo.length>0){
//                    $.each(parent.locationInfo,function(i,n){
//                        tr+="<option value='"+n.st_id+"'>"+n.st_code+"</option>";
//                    })
//                }
                tr+="</select></td>";
                tr+="<td class='operate_td'><a class='icon-minus delete_btn mr-20'></a><a class='icon-repeat reset_btn'></a></td>";
                tr+="</tr>";
                parent.num++;
                parent.$("tbody").append(tr);
//                parent.$("#product_tbody").append(tr);
//                parent.$.parser.parse(parent.$("#product_tbody").find("tr:last"));
            });
//            parent.$("#product_tbody").find("tr").each(function(index){
//                $(this).find("td:first").text(index+1);
//            });
//            parent.$(".content").find("input[type='checkbox']").prop("checked",false);
//            if(parent.$("#product_tbody").find("tr").length==1){
//                parent.$(".operate_td").html("<a class='icon-repeat reset_btn'></a>");
//            }else{
//                parent.$(".operate_td").html("<a class='icon-minus delete_btn mr-20'></a><a class='icon-repeat reset_btn'></a>");
//            }
            parent.$.fancybox.close();
        });

        // 改变重新计算总价格
        $('input[type!="hidden"],select,textarea', $("#product_add")).on("change", function () {
            totalPrices();
        });
    });
    function add_product(product) {
        var a = $("#product_table tr").length;
        var b = parent.$("#product_table tr:last-child").find("td.hiden").children().data("id") + a + 1;
        var obj = $("#product_table").append(
            '<tr>' +
            '<td class="hiden"><span data-id="' + b + '"></span></td>' +
            '<td>' + (a + 1) + '</td>' +
            '<td>' + '<p class="color-w"><input type="checkbox"></p>' + '</td>' +
            '<td><input class="height-30 width-150  text-center  pdt_no" type="text" data-options="required:true"  maxlength="20" placeholder="请输入" value="' + product.pdt_no + '"><input class="hiden pdt_id" name="orderL[' + b + '][pdt_id]" value="' + product.pdt_id + '"/></td>' +
            '<td><input class="height-30 width-150  text-center sapl_quantity" type="text" data-options="required:true"  maxlength="20" name="orderL[' + b + '][sapl_quantity]" placeholder="请输入数量"></td>' +
            '<td><input class="height-30 width-150  text-center price" type="text" data-options="required:true"  maxlength="20" name="orderL[' + b + '][uprice_ntax_o]" placeholder="请输入单价"></td>' +
            '<td><input class="height-30 width-150  text-center cess" type="text" data-options="required:true" name="orderL[' + b + '][cess]" placeholder="请输入"></td>' +
            '<td><input class="height-30 width-150  text-center discount" type="text" data-options="required:true" name="orderL[' + b + '][discount]" placeholder="请输入" value="100"></td>' +
            '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="orderL[' + b + '][transport]">' +
            '<option value="">请选择...</option>' +
            <?php foreach ($downList["transport"] as $key => $val) { ?>
            '<option value="' + "<?= $val["tran_id"] ?>" + '">' + "<?= $val["tran_sname"] ?>" + '</option>' +
            <?php } ?>
            '</select></td>' +
            '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="orderL[' + b + '][distribution]">' +
            '<option value="">请选择...</option>' +
            <?php foreach ($downList["dispatching"] as $key => $val) { ?>
            '<option value="' + "<?= $val["bdm_id"] ?>" + '">' + "<?= $val["bdm_sname"] ?>" + '</option>' +
            <?php } ?>
            '</select></td>' +
            '<td><input class="height-30 width-150  text-center freight" type="text" data-options="required:true" name="orderL[' + b + '][freight]" placeholder="请输入"></td>' +
            '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="orderL[' + b + '][whs_id]">' +
            '<option value="">请选择...</option>' +
            <?php foreach ($downList["warehouse"] as $key => $val) { ?>
            '<option value="' + "<?= $val["wh_id"] ?>" + '">' + "<?= $val["wh_name"] ?>" + '</option>' +
            <?php } ?>
            '</select></td>' +
            '<td><input class="height-30 width-150 select-date-time easyui-validatebox " readonly="readonly" data-options="required:true" name="orderL[' + b + '][request_date]" placeholder="请选择"></td>' +
            '<td><input class="height-30 width-150 select-date-time easyui-validatebox " readonly="readonly" data-options="required:true" name="orderL[' + b + '][consignment_date]" placeholder="请选择"></td>' +
            '<td><input class="height-30 width-150  text-center" type="text" name="orderL[' + b + '][sapl_remark]" placeholder="请输入"></td>' +
            <?php foreach ($columns as $key => $val) { ?>
            '<td class="width-150 ' + "<?= $val["field_field"] ?>" + '"><p class="width-150 text-center ' + "<?= $val["field_field"] ?>" + '">' + ((product.<?= $val["field_field"] ?>) ? (product.<?= $val["field_field"] ?>) : "") + '</p></td>' +
            <?php } ?>
            '<td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
            '</tr>'
        );
        $(".select-date-time").click(function () {
            jeDate({
                dateCell: this,
                isToday: false,
                zIndex: 8831,
                format: "YYYY-MM-DD hh:mm",
                skinCell: "jedatedeep",
                isTime: true,
                ishmsVal: true,
                okfun: function (elem) {
                    $(elem).change();
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun: function (elem) {
                    $.parser.parse(obj);
                    return $(elem).click();
                },
                //清空时间
                clearfun: function (elem, val) {
                    $(".use_day,.use_hour,.use_minute").val('');
                    $(elem).change();
                },
            })
        });
        $.parser.parse(obj);
        $('input[type!="hidden"],select,textarea', $("#product_add")).each(function () {
            $(this).validatebox();//验证初始化
        });
        $('input[type!="hidden"],select,textarea', $("#product_add")).on("change", function () {
            $(this).validatebox();//验证初始化
            totalPrices();
        });
    }
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
        var SelectArr = $(obj).parents("tr").find("select");
        for (var i = 0; i < SelectArr.length; i++) {
            SelectArr[i].options[0].selected = true;
        }
    }
    function vacc_del(obj, id) {
        $(obj).parents("tr").remove();
//        console.log($(obj).parents("tr").find('td').eq(0).text());
//        alert($("#contacts_table tr").length);
        var a = $("#" + id + " tr").length;
        for (var i = 0; i < a; i++) {
            $('#' + id).find('tr').eq(i).find('td:first').text(i + 1);
        }
    }
    function contains(arr, obj) {
        var i = arr.length;
        while (i--) {
            if (arr[i] === obj) {
                return true;
            }
        }
        return false;
    }
    //计算总价格
    function totalPrices() {
        var trs = $("#product_table").children();
        var allinput = true;
        var ttfreight = 0;//总运费
        var ttprice = 0;//商品总金额(含税)
        var total = 0;//订单总金额(含税)
        for (i = 0; i < trs.length; i++) {
            var quantity = $(trs[i]).find("input.sapl_quantity").val();//数量
            var price = $(trs[i]).find("input.price").val();//单价未税
            var cess = $(trs[i]).find("input.cess").val();//税率
            var discount = $(trs[i]).find("input.discount").val();//折扣率
            var freight = $(trs[i]).find("input.freight").val();//运费
            if (quantity && price && cess && discount && freight) {
                quantity = parseInt(quantity);
                price = parseInt(price);
                cess = parseInt(cess);
                discount = parseInt(discount);
                freight = parseInt(freight);

                price_hs = price + price * (cess / 100);   //税

                var ttprice_ws = price * quantity;  //未税总价
                var prices = price_hs * quantity;    //含税总价
                var price_off = price_hs * quantity * discount / 100;   //折扣后金额

                $(trs[i]).find("p.price_hs").html(price_hs);
                $(trs[i]).find("p.ttprice_ws").html(ttprice_ws);
                $(trs[i]).find("p.ttprice").html(prices);
                $(trs[i]).find("p.price_off").html(price_off);

                ttfreight = ttfreight + freight;
                ttprice = ttprice + prices;
                total = total + ttfreight + ttprice;
            }
        }
        console.log('总运费',ttfreight);
        console.log('商品总金额',ttprice);
        console.log('订单总金额',total);
    }
</script>