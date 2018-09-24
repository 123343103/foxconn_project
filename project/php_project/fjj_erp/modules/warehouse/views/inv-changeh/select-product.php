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
<div class="head-first">添加商品信息</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <input type="hidden" class="_pro" value="<?= $params['pro']?>"/>
        <input type="hidden" class="_row" value="<?= $params['row']?>"/>
        <?php ActiveForm::begin(['id' => 'product_form', 'method' => 'get', 'action' => Url::to(['select-product'])]); ?>
        <label class="width-60">仓库</label><label>:</label>
        <select name="whId" class="width-130 output_wh" <?= empty($params['whId']) ? null : "disabled" ?>>
            <option value="">---请选择---</option>
            <?php foreach ($downList['warehouse'] as $key => $val) { ?>
                <option data-id="<?= $val['wh_code'] ?>" data-value="<?= $val['wh_attrw'] == "Y" ? "自有" : "外租" ?>"
                    value="<?= $val['wh_id'] ?>" <?= $params['whId'] == $val['wh_code'] ? "selected" : null ?>><?= $val['wh_name'] ?></option>
            <?php } ?>
        </select>
        <input type="hidden" name="whId" value="<?= $params['whId']?>">
<!--        <input type="text" class="width-150" id="searchText" name="searchKeyword"-->
<!--               value="--><?//= $params['searchKeyword'] ?><!--">-->
<!--        <label class="width-60">储位</label>-->
<!--        <input type="text" class="width-150" id="" name=""-->
<!--               value="--><?//= $params['searchKeyword'] ?><!--">-->
        <input type="text" class="width-150" style="width: 250px;" id="searchText" name="kwd" placeholder="储位/料号/商品名称/规格型号"
               value="<?= $params['kwd'] ?>">
        <button type="submit" class="button-blue" id="search">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?= Url::to(['select-product']) ?>'">
            重置
        </button>
        <!--        <a href="-->
        <? //= Url::to(['/crm/crm-customer-info/create']) ?><!--" target="_blank" class="float-right text-center"-->
        <!--           style="width:80px;line-height:25px;background-color:#1f7ed0;color:#ffffff;">新增客户</a>-->
        <?php ActiveForm::end(); ?>
    </div>
    <table id="product_data" style="width:100%;"></table>
    <?php ActiveForm::begin(['id' => 'product_add', 'method' => 'get', 'action' => Url::to(['select-product'])]); ?>
    <tbody id="product_table">
    </tbody>
    <div class="space-20"></div>
    <?php ActiveForm::end(); ?>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_product">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<script>
    var b=0;
    $(function () {
        var s=$("._row").val();
        if(s!=""){
            parent.$("#product_table").find("tr").eq(s-1).remove();
        }
//        if(=!=""){
//            $("#searchText").val($("._pro").val());
//        }else {
//            $("#searchText").val('');
//        }
        var $dg = $("#product_data");
        $dg.datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            <?= !empty($params['whId']) ? "" : "singleSelect:true," ?>
            method: "get",
            idField: "cust_id",
//            check: true,
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            columns: [[
                {field: "", checkbox: true, width: 28},
                {field: "wh_name", title: "仓库", width: 150},
                {field: "part_no", title: "料号", width: 200},
                {field: "pdt_name", title: "商品名称", width: 220},
                {field: 'tp_spec',title:'规格型号',width:150},
                {field: 'batch_no',title:'批次',width:150},
                {field: "st_code", title: "储位", width: 150},
                {field: "invt_num", title: "现有库存", width: 96}
            ]],
            onLoadSuccess: function (data) {
//                $(".pagination-info").css("float", "left");
//                $(".pagination-info").css("margin", "0 0 0 -430px");
//                $(".pagination table").css("float", "left");
//                $(".pagination table").css("margin", "0 0 0 140px");
                $dg.datagrid('clearSelections');
                datagridTip("#product_data");
                showEmpty($(this), data.total, 1);
            }
        });

        //添加到下面
//        $("#search").click(function () {
//            var searchText = $("#searchText").val();
//            $('#product_data').datagrid('options').url = "<?//=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>//" + "?searchKeyword=" + searchText;
//            $dg.datagrid({
//                pageNumber: 1
//            });
//            $dg.datagrid('reload');
//            $dg.datagrid('getColumnOption', 'pdt_no').width = 208;
//            $dg.datagrid('getColumnOption', 'pdt_name').width = 228;
//            $dg.datagrid('getColumnOption', 'wh_name').width = 158;
//            $dg.datagrid('getColumnOption', 'invt_num').width = 104;
//            $dg.datagrid();
//            $dg.datagrid('clearSelections');
//        });
        //添加到下面
//        $("#confirm_product").click(function () {
//            var product = $dg.datagrid('getChecked');
//                     console.log(product.pdt_name);
//            if (product.length == 0) {
//                parent.layer.alert('请选择商品！', {icon: 2, time: 5000});
//                return false;
//            }
//            var a = parent.$("#product_table tr").length;
//            var b = parent.$("#product_table tr:last-child").find("td.hiden").children().data("id") + a + 1;
//            for (i = 0; i < product.length; i++) {
////                var n = a+1;
//                var obj = parent.$("#product_table").append(
//                    '<tr>' +
//                    '<td class="hiden"><span data-id="' + b + '"></span></td>' +
//                    '<td>' + '' + '</td>' +
//                    '<td>'+'<span class="width-40"><input type="checkbox"></span'+'</td>'+
//                    '<td><input class="height-30 width-150  text-center  pdt_no" type="text" data-options="required:true"  maxlength="20" placeholder="请输入" value="' + product[i].pdt_no + '"><input class="hiden pdt_id" name="changeL[' + b + '][pdt_id]" value="' + product[i].pdt_id + '"/></td>' +
//                    '<td><input class="height-30 width-150  text-center pdt_name" type="text" data-options="required:true"  maxlength="20" value="' + product[i].pdt_name + '" name="changeL[' + b + '][pdt_name]" placeholder="请输入品名"></td>' +
//                    '<td><input class="height-30 width-150  text-center category_sname" type="text" data-options="required:true"  maxlength="20" value="' + product[i].category_sname + '" name="changeL[' + b + '][category_sname]" placeholder="请输入类别"></td>' +
//                    '<td><input class="height-30 width-150  text-center specification" type="text" data-options="required:true"  maxlength="20" value="' + product[i].specification + '" name="changeL[' + b + '][specification]" placeholder="请输入规格"></td>' +
//                    '<td><input class="height-30 width-150  text-center unit_name" type="text" data-options="required:true"  maxlength="20" value="' + product[i].unit_name + '" name="changeL[' + b + '][unit_name]" placeholder="单位"></td>' +
//                    '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="changeL[' + b + '][transport]">' +
//                    '<option value="">请选择...</option>' +
//                    <?php //foreach ($downList["part"] as $key => $val) { ?>
//                    '<option value="' + "<?//= $val["part_code"] ?>//" + '">' + "<?//= $val["part_name"] ?>//" + '</option>' +
//                    <?php //} ?>
//                    '</select></td>' +
//                    '<td><input class="height-30 width-150  text-center invt_num" type="text" data-options="required:true" name="changeL[' + b + '][invt_num]" placeholder="请输入" value="' + product[i].invt_num + '"></td>' +
//                    '<td><input class="height-30 width-150  text-center chl_num" type="text" data-options="required:true" name="changeL[' + b + '][chl_num]" placeholder="请输入" value=""></td>' +
//                    '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="changeL[' + b + '][distribution]">' +
//                    '<option value="">请选择...</option>' +
//                    <?php //foreach ($downList["scrap"] as $key => $val) { ?>
//                    '<option value="' + "<?//= $key ?>//" + '">' + "<?//= $val ?>//" + '</option>' +
//                    <?php //} ?>
//                    '</select></td>' +
//                    '<td><input class="height-30 width-250  text-center chh_remark" type="text" data-options="required:true" name="changeL[' + b + '][freight]" placeholder="请输入"></td>' +
//                    '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="changeL[' + b + '][transport]">' +
//                    '<option value="">请选择...</option>' +
//                    <?php //foreach ($downList["warehouse"] as $key => $val) { ?>
//                    '<option value="' + "<?//= $val["wh_id"] ?>//" + '">' + "<?//= $val["wh_name"] ?>//" + '</option>' +
//                    <?php //} ?>
//                    '</select></td>' +
//                    '<td><select class="height-30 width-150  text-center" type="text" data-options="required:true" name="changeL[' + b + '][transport]">' +
//                    '<option value="">请选择...</option>' +
//                    <?php //foreach ($downList["part"] as $key => $val) { ?>
//                    '<option value="' + "<?//= $val["part_code"] ?>//" + '">' + "<?//= $val["part_name"] ?>//" + '</option>' +
//                    <?php //} ?>
//                    '</select></td>' +
//                    '<td><input class="height-30 width-150 " readonly="readonly" name="changeL[' + b + '][consignment_date]"></td>' +
//                    '<td><input class="height-30 width-150  text-center" type="text" name="changeL[' + b + '][sapl_remark]" placeholder="请输入"></td>' +
//                    '<td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
//                    '</tr>'
//                );
//            }
//            parent.$.fancybox.close();
//            var len = parent.$('table tr').length;
//            for (var i = 1; i < len; i++) {
//                //序号重排
//                parent.$('table tr:eq(' + i + ') td:first').next().text("");
//                parent.$('table tr:eq(' + i + ') td:first').next().append('<span class="width-40">' + i + '</span>');
//            }
//        });
        $("#confirm_product").click(function(){
            var product = $dg.datagrid('getChecked');
//            console.log(product.pdt_name);
            if (product.length == 0) {
                parent.layer.alert('请选择商品！', {icon: 2, time: 5000});
                return false;
            }

            //批量添加删除空行
            parent.$("#product_table").find("tr").each(function(){
                var _pro=$(this).find(".pdt_no").val();
                if(_pro==""){
                    $(this).remove();
                }
            });

        $.each(product,function(i,n){
            if(product[i].catg_name3!=""){
                category_sname=product[i].catg_name3;
            }else if(product[i].catg_name2!=""){
                category_sname=product[i].catg_name2;
            }else{
                category_sname=product[i].catg_name;
            }
            //现有库存保留2位小数
            var _nownum=parseFloat(product[i].invt_num);
            _nownum=_nownum.toFixed(2);
//            alert(_nownum);
            
            for(a in n){
                if(n[a]==null||n[a]==''){
                    n[a]='/';
                }
            }
            var tr=
                '<tr>' +
                '<td>' + '' + '</td>' +
                '<td>'+'<span class="width-40"><input type="checkbox"></span'+'</td>'+
                '<td><input class=" pdt_no" type="text" ' +
                'data-options="required:true"  maxlength="20" placeholder="请输入" value="' + product[i].part_no + '">' +
                '<input class="hiden pdt_id" name="changeL[' + parent.b + '][pdt_no]" value="' + product[i].part_no + '"/></td>' +
                '<td><span> '+ product[i].pdt_name +'</span><input class="pdt_name" type="hidden" ' +
                'value="' + product[i].pdt_name + '" ' +
                'name="changeL[' + parent.b + '][pdt_name]"></td>' +
                '<td><span>' + category_sname + '</span><input class="category_sname" type="hidden" ' +
                ' value="' + category_sname + '" ' +
                'name="changeL[' + parent.b + '][category_sname]"></td>' +
                '<td><span class="">' + product[i].tp_spec + '</span><input class=" specification" type="hidden" ' +
                'value="' + product[i].tp_spec + '" ' +
                'name="changeL[' + parent.b + '][specification]" ></td>' +
                '<td><span class="">' + product[i].batch_no + '</span><input class=" specification" type="hidden" ' +
                'value="' + product[i].batch_no + '" ' +
                'name="changeL[' + parent.b + '][chl_bach]" ></td>' +
                '<td><span class="">' + product[i].unit + '</span><input class=" unit_name" type="hidden" ' +
                ' value="' + product[i].unit + '" ' +
                'name="changeL[' + parent.b + '][unit_name]"></td>' +
                '<td><span class="">' + product[i].st_code + '</span><input class=" st_td" type="hidden" ' +
                ' value="' + product[i].st_id + '" ' +
                'name="changeL[' + parent.b + '][st_id]"></td>' +
                '<td><span class="invt_num">' + _nownum + '</span><input ' +
                'class="invt_num" type="hidden" ' +
                'name="changeL[' + parent.b + '][before_num1]" ' +
                ' value="' + product[i].invt_num + '"></td>' +
                '<td><input class="height-30 width-150  text-center chl_num easyui-validatebox" maxlength="10" type="text" ' +
                'data-options="required:\'true\',validType:\'intnum\'" ' +
                'name="changeL[' + parent.b + '][chl_num]" placeholder="请输入" value=""></td>' +
                '<td><select class="height-30 width-150  text-center" type="text" ' +
                'data-options="required:true" name="changeL[' + parent.b + '][mode]">' +
                '<option value="">请选择...</option>' +
                <?php foreach ($downList["scrap"] as $key => $val) { ?>
                '<option value="' + "<?= $key ?>" + '">' + "<?= $val ?>" + '</option>' +
                <?php } ?>
                '</select></td>' +
                '<td><input class="height-30 width-250  text-center chh_remark" type="text" ' +
                'data-options="required:true" name="changeL[' + parent.b + '][chh_remark]" placeholder="请输入"></td>' +
                '<td><select class="height-30 width-150  text-center input_wh" type="text" ' +
                'data-options="required:true" name="changeL[' + parent.b + '][wh_id2]">' +
                '<option value="">请选择...</option>' +
                <?php foreach ($downList["warehousebf"] as $key => $val) { ?>
                '<option value="' + "<?= $val["wh_id"] ?>" + '">' + "<?= $val["wh_name"] ?>" + '</option>' +
                <?php } ?>
                '</select></td>' +
                '<td><input class="height-30 wd100  text-center  store2" readonly="readonly" type="text" ' +
                'data-options="required:true" maxlength="20" placeholder="请点击选择"value="">' +
                '<input type="hidden" class="_store2id" name="changeL[' + parent.b + '][st_id2]"/></td>' +
                '<td> <span class=""></span><input class="" type="hidden" name=""></td>' +
                '<td><input class="height-30 width-150  text-center easyui-validatebox" maxlength="10" type="text" ' +
                'name="changeL[' + parent.b + '][deal_price]" data-options="required:\'true\',validType:\'intnum\'" ' +
                'placeholder="请输入"></td>' +
                '<td><a class="width-150" onclick="reset(this)">重置</a>  <a onclick="vacc_del(this,' + "'product_table'" + ')">删除</a></td>' +
                '</tr>';
            parent.b++;
            parent.$("#product_table").append(tr);
            parent.$.parser.parse(parent.$("#product_table").find("tr:last"));
        });
        parent.$("#product_table").find("tr").each(function(index){
            $(this).find("td:first").text(index+1);
        });
            parent.$.fancybox.close();
        });
        //添加到parent
//        $("#confirm_product").click(function () {
//            var tr = $("#product_table tr");
//            //追加到指定行
//            if (row != "") {
//                tr.insertAfter(parent.$("#product_table tr")[(row - 1)]);
//                parent.$("#product_table tr")[(row - 1)].remove();
//            } else {
//                tr.appendTo(parent.$("#product_table"));
//            }
//            parent.$.fancybox.close();
//            $(".select-date-time").unbind();
//            var len = parent.$('table tr').length;
//            for (var i = 1; i < len; i++) {
//                //序号重排
//                parent.$('table tr:eq(' + i + ') td:first').next().text("");
//                parent.$('table tr:eq(' + i + ') td:first').next().append('<span class="width-40">' + i + '</span>');
//            }
//            parent.dateTime();
////            parent.totalPrices();
//        });
//        $('input[type!="hidden"],select,textarea', $("#product_add")).on("change", function () {
//            totalPrices();
//        });

    });

</script>