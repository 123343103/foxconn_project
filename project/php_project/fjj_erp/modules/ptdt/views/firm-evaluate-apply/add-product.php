<?php
/**
 * User: F1677929
 * Date: 2016/11/3
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
$get = Yii::$app->request->get();
if (!isset($get['PdFirmEvaluateApplySearch'])){
    $get['PdFirmEvaluateApplySearch'] = null;
}
?>
<div class="head-first">新增采购商品</div>
<div style="padding:0 20px;">
    <div class="overflow-auto">
        <div id="get_product_button" style="line-height:30px;width:100px;text-align:center;cursor:pointer;border:1px solid #1f7ed0;border-bottom:none;float:left;color:#1f7ed0;">商品库获取</div>
        <div id="add_product_button" style="line-height:30px;width:100px;text-align:center;cursor:pointer;border-bottom:1px solid #1f7ed0;float:left;">新增商品信息</div>
        <div style="height:30px;width:558px;border-bottom:1px solid #1f7ed0;float:left;"></div>
    </div>
    <div style="border:1px solid #1f7ed0;border-top:none;padding:10px 10px 20px;">
        <div id="get_product">
            <div class="mb-10">
                <form action="<?= Url::to(['add-product']) ?>" method="get" id="product_form">
                    <span>关键词：</span>
                    <input type="text" name="PdFirmEvaluateApplySearch[searchKeyword]" class="width-200" style="height: 30px;" value="<?= $get['PdFirmEvaluateApplySearch']['searchKeyword'] ?>">
                    <img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" alt="搜索" style="cursor: pointer; vertical-align: bottom; margin-left: -3px;">
                    <button id="reset" type="button" class="button-blue" style="height: 30px;">重&nbsp;置</button>
                </form>
            </div>
            <div id="data" style="width: 100%;"></div>
            <div class="text-center mt-20">
                <button type="button" class="button-blue-big" id="confirm_get_product">确&nbsp;定</button>
                <button type="button" class="button-white-big ml-20 cancel">取&nbsp;消</button>
            </div>
        </div>
        <div id="add_product" style="display:none;">
            <div class="mb-20">
                <label class="width-80">商品名称</label>
                <input type="text" class="width-150" id="pro_name">
                <label class="width-80">规格型号</label>
                <input type="text" class="width-150" id="pro_size_model">
                <label class="width-80">品牌</label>
                <input type="text" class="width-150" id="pro_brand">
            </div>
            <div class="mb-20">
                <label class="width-80">交货条件</label>
                <select class="width-150" id="pro_delivery_condition">
                    <option value="">请选择</option>
                    <option value="1">上门领取</option>
                    <option value="2">快递送货</option>
                </select>
                <label class="width-80">付款条件</label>
                <select class="width-150" id="pro_pay_condition">
                    <option value="">请选择</option>
                    <option value="1">货到付款</option>
                </select>
                <label class="width-80">交易单位</label>
                <select class="width-150" id="pro_trade_unit">
                    <option value="">请选择</option>
                    <option value="1">个</option>
                    <option value="2">顿</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-80">定价上限</label>
                <input type="text" class="width-150" id="pro_price_max">
                <label class="width-80">定价下限</label>
                <input type="text" class="width-150" id="pro_price_min">
                <label class="width-80">交易币别</label>
                <select class="width-150" id="pro_trade_currency">
                    <option value="">请选择</option>
                    <option value="1">RMB</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-80">量价区间</label>
                <input type="text" class="width-150" id="pro_price_interval">
                <label class="width-80">市场均价</label>
                <input type="text" class="width-150" id="pro_market_price">
            </div>
            <div class="mb-20">
                <label class="width-80">利润率</label>
                <input type="text" style="width: 65px;" id="pro_profit_min">
                <span>至</span>
                <input type="text" style="width: 66px;" id="pro_profit_max">
                <label class="width-80">商品定位</label>
                <select class="width-150" id="pro_position">
                    <option value="">请选择</option>
                    <?php foreach ($productData['productPosition'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-80">一阶</label>
                <select name="" id="type_1" class="width-150 type">
                    <option value="">请选择</option>
                    <?php foreach ($productData['oneCategory'] as $key => $val) { ?>
                        <option value="<?= $key ?>"><?= $val ?></option>
                    <?php } ?>
                </select>
                <label class="width-80">二阶</label>
                <select name="" id="type_2" class="width-150 type">
                    <option value="">请选择</option>
                </select>
                <label class="width-80">三阶</label>
                <select name="" id="type_3" class="width-150 type">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="mb-20">
                <label class="width-80">四阶</label>
                <select name="" id="type_4" class="width-150 type">
                    <option value="">请选择</option>
                </select>
                <label class="width-80">五阶</label>
                <select name="" id="type_5" class="width-150 type">
                    <option value="">请选择</option>
                </select>
                <label class="width-80">六阶</label>
                <select name="" id="type_6" class="width-150 type">
                    <option value="">请选择</option>
                </select>
            </div>
            <div class="text-center">
                <button type="button" class="button-blue-big" id="confirm_add_product">确&nbsp;定</button>
                <button type="button" class="button-white-big ml-20 cancel">取&nbsp;消</button>
            </div>
        </div>
    </div>
</div>
<script>
    function editCategory($select,pid,cid){
        if(pid==""){
            return false;
        }
        $.ajax({
            url:"<?=Url::to(['/ptdt/product-dvlp/get-product-type'])?>",
            data:{"id":pid},
            dataType:"json",
            success:function(data){
                $.each(data,function(i,n){
                    $select.append("<option value='"+n.type_id+"'>"+n.type_name+"</option>");
                    $select.children("option[value='"+cid+"']").attr("selected",true);
                })
            }
        })
    }

    $(function () {
        //级联清除选项
        function clearOption($select ) {
            if ($select == null) {
                $select = $("#type_1")
            }
            console.log($select);
            if($select.length!=0){
                $select.html("<option value=''>请选择</option>");
                if($select.next().length==0){
                    console.log($select.parent().next().children(":first"));
                    if($select.parent().next().children(":first")[0].localName=="label"){
                        $select=$select.parent().next().children(":first");
                    }else{
                        return false;
                    }
                }
                if($select.next()[0].localName=="label"){
                    $select=$select.next();
                    console.log($select);
                }
                if($select.next()[0].localName=="select"){
                    clearOption($select.next());
                }

            }
        }
        /**
         * 分类级联
         * @param $select  //第一个select
         * @param url     // "<?=Url::to(['/ptdt/product-dvlp/get-product-type']) ?>",
         */
        function getNextType($select,url,selectStr) {
            var id = $select.val();
            if (id == "") {
                clearOption($select);
                return;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"id": id},
                url: url,
                success: function (data) {
                    if($select.next().length==0){
                        console.log($select.parent().next().children(":first"));
                        if($select.parent().next().children(":first")[0].localName=="label"){
                            $select=$select.parent().next().children(":first");
                        }else{
                            return false;
                        }
                    }
                    if($select.next()[0].localName=="label"){
                        $select=$select.next();
                    }
                    var $nextSelect = $select.next(selectStr);
                    clearOption($nextSelect);
                    if ($nextSelect.length != 0)
                        for (var x in data) {
                            $nextSelect.append('<option value="' + data[x].type_id + '" >' + data[x].type_name + '</option>')
                        }
                }

            })
        }
        //搜索
        $("#img").on("click", function () {
            $("#product_form").submit();
        });

        //重置
        $("#reset").on("click", function () {
            window.location.href = "<?= Url::to(['add-product']) ?>";
        });

        //显示数据
        $("#data").datagrid({
            url: "<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
            rownumbers: true,
            method: "get",
            idField: "pdt_id",
            pagination: true,
            singleSelect: true,
            pageSize: 8,
            pageList: [8],
            columns: [[
                {field:"bs_category_id",title:"Commodity",width:170},
                {field:"pdt_name",title:"商品名称",width:170},
                {field:"pdt_model",title:"规格型号",width:170},
                {field:"brandid",title:"品牌",width:162}
            ]],
            onLoadSuccess:function(){
                if(parent.aa==1){
                    $("#add_product_button").click();
                    parent.aa=0;
                    $("#pro_name").val(parent.$proTr.find(".in_pro_name").val());
                    $("#pro_size_model").val(parent.$proTr.find(".in_pro_size_model").val());
                    $("#pro_brand").val(parent.$proTr.find(".in_pro_brand").val());
                    $("#pro_delivery_condition option").each(function(){
                        if(parent.$proTr.find(".in_delivery_condition").val()==$(this).val()){
                            $(this).attr("selected",true);
                        }
                    });
                    $("#pro_pay_condition option").each(function(){
                        if(parent.$proTr.find(".in_pay_condition").val()==$(this).val()){
                            $(this).attr("selected",true);
                        }
                    });
                    $("#pro_trade_unit option").each(function(){
                        if(parent.$proTr.find(".in_trade_unit").val()==$(this).val()){
                            $(this).attr("selected",true);
                        }
                    });
                    $("#pro_price_max").val(parent.$proTr.find(".in_price_max").val());
                    $("#pro_price_min").val(parent.$proTr.find(".in_price_min").val());
                    $("#pro_trade_currency option").each(function(){
                        if(parent.$proTr.find(".in_trade_currency").val()==$(this).val()){
                            $(this).attr("selected",true);
                        }
                    });
                    $("#pro_price_interval").val(parent.$proTr.find(".in_price_interval").val());
                    $("#pro_market_price").val(parent.$proTr.find(".in_market_price").val());
                    $("#pro_profit_min").val(parent.$proTr.find(".in_profit_min").val());
                    $("#pro_profit_max").val(parent.$proTr.find(".in_profit_max").val());
                    $("#pro_pro_position option").each(function(){
                        if(parent.$proTr.find(".in_pro_position").val()==$(this).val()){
                            $(this).attr("selected",true);
                        }
                    });
                    $("#type_1 option").each(function(){
                        if(parent.$proTr.find(".in_one_category").val()==$(this).val()){
                            $(this).attr("selected",true);
                        }
                    });
                    editCategory($("#type_2"),parent.$proTr.find(".in_one_category").val(),parent.$proTr.find(".in_two_category").val());
                    editCategory($("#type_3"),parent.$proTr.find(".in_two_category").val(),parent.$proTr.find(".in_three_category").val());
                    editCategory($("#type_4"),parent.$proTr.find(".in_three_category").val(),parent.$proTr.find(".in_four_category").val());
                    editCategory($("#type_5"),parent.$proTr.find(".in_four_category").val(),parent.$proTr.find(".in_five_category").val());
                    editCategory($("#type_6"),parent.$proTr.find(".in_five_category").val(),parent.$proTr.find(".in_six_category").val());
//                    $("#type_2 option").each(function(){
//                        if(parent.$proTr.find(".in_two_category").val()==$(this).val()){
//                            $(this).attr("selected",true);
//                        }
//                    });
//                    $("#type_3 option").each(function(){
//                        if(parent.$proTr.find(".in_three_category").val()==$(this).val()){
//                            $(this).attr("selected",true);
//                        }
//                    });
                    console.log(parent.$proTr.find(".in_four_category").val());
//                    $("#type_4 option").each(function(){
//                        if(parent.$proTr.find(".in_four_category").val()==$(this).val()){
//                            $(this).attr("selected",true);
//                        }
//                    });
//                    $("#type_5 option").each(function(){
//                        if(parent.$proTr.find(".in_five_category").val()==$(this).val()){
//                            $(this).attr("selected",true);
//                        }
//                    });
//                    $("#type_6 option").each(function(){
//                        if(parent.$proTr.find(".in_six_category").val()==$(this).val()){
//                            $(this).attr("selected",true);
//                        }
//                    });
                }
            }
        });

        //确定获取商品
        $("#confirm_get_product").on("click",function () {
            var obj = $("#data").datagrid('getSelected');
            if (obj == null) {
                parent.layer.alert('请选择一条信息！',{icon:2,time:5000});
            } else {
                var tr = "<tr>";
                tr += "<td>&nbsp;</td>";
                tr += "<td>" + obj.pdt_no + "</td>";
                tr += "<td>" + obj.pdt_name + "</td>";
                tr += "<td>" + obj.pdt_model + "</td>";
                tr += "<td>" + obj.CATEGORY_ID + "</td>";
                tr += "<td>" + obj.unit + "</td>";
                tr += "<td><a class='edit_get_product'>修改</a>&nbsp;<a onclick='deleteTr(this)'>删除</a></td>";
                tr += "</tr>";
                if(parent.$proTr==null){
                    $("#product_tbody",window.parent.document).append(tr);
                }else{
                    parent.$proTr.replaceWith(tr);
                    parent.$proTr=null;
                }
                $("#product_tbody tr",window.parent.document).each(function(i){
                    $(this).children("td:first").text(i+1);
                });
                parent.$.fancybox.close();
            }
        });
        //确定新增商品
        $("#confirm_add_product").click(function(){
            var tr = "<tr>";
            tr += "<td>&nbsp;</td>";
            tr += "<td>&nbsp;</td>";
            tr += "<td>" + $("#pro_name").val() + "</td>";
            tr += "<td>" + $("#pro_size_model").val() + "</td>";
            tr += "<td>" + "&nbsp;" + "</td>";
            if($("#pro_trade_unit option:selected").val()==""){
                tr+='<td></td>';
            }else{
                tr += "<td>" + $("#pro_trade_unit option:selected").text() + "</td>";
            }
            tr += "<td><a class='edit_add_product'>修改</a>&nbsp;<a onclick='deleteTr(this)'>删除</a></td>";
            tr+='<input class="in_pro_name" type="hidden" name="BsVendorPurchase['+parent.m+'][pro_name]" value="'+$("#pro_name").val()+'">';
            tr+='<input class="in_pro_size_model" type="hidden" name="BsVendorPurchase['+parent.m+'][pro_size_model]" value="'+$("#pro_size_model").val()+'">';
            tr+='<input class="in_pro_brand" type="hidden" name="BsVendorPurchase['+parent.m+'][pro_brand]" value="'+$("#pro_brand").val()+'">';
            tr+='<input class="in_delivery_condition" type="hidden" name="BsVendorPurchase['+parent.m+'][delivery_condition]" value="'+$("#pro_delivery_condition").val()+'">';
            tr+='<input class="in_pay_condition" type="hidden" name="BsVendorPurchase['+parent.m+'][pay_condition]" value="'+$("#pro_pay_condition").val()+'">';
            tr+='<input class="in_trade_unit" type="hidden" name="BsVendorPurchase['+parent.m+'][trade_unit]" value="'+$("#pro_trade_unit").val()+'">';
            tr+='<input class="in_price_max" type="hidden" name="BsVendorPurchase['+parent.m+'][price_max]" value="'+$("#pro_price_max").val()+'">';
            tr+='<input class="in_price_min" type="hidden" name="BsVendorPurchase['+parent.m+'][price_min]" value="'+$("#pro_price_min").val()+'">';
            tr+='<input class="in_trade_currency" type="hidden" name="BsVendorPurchase['+parent.m+'][trade_currency]" value="'+$("#pro_trade_currency").val()+'">';
            tr+='<input class="in_price_interval" type="hidden" name="BsVendorPurchase['+parent.m+'][price_interval]" value="'+$("#pro_price_interval").val()+'">';
            tr+='<input class="in_market_price" type="hidden" name="BsVendorPurchase['+parent.m+'][market_price]" value="'+$("#pro_market_price").val()+'">';
            tr+='<input class="in_profit_min" type="hidden" name="BsVendorPurchase['+parent.m+'][profit_min]" value="'+$("#pro_profit_min").val()+'">';
            tr+='<input class="in_profit_max" type="hidden" name="BsVendorPurchase['+parent.m+'][profit_max]" value="'+$("#pro_profit_max").val()+'">';
            tr+='<input class="in_pro_position" type="hidden" name="BsVendorPurchase['+parent.m+'][pro_position]" value="'+$("#pro_position").val()+'">';
            tr+='<input class="in_one_category" type="hidden" name="BsVendorPurchase['+parent.m+'][one_category]" value="'+$("#type_1").val()+'">';
            tr+='<input class="in_two_category" type="hidden" name="BsVendorPurchase['+parent.m+'][two_category]" value="'+$("#type_2").val()+'">';
            tr+='<input class="in_three_category" type="hidden" name="BsVendorPurchase['+parent.m+'][three_category]" value="'+$("#type_3").val()+'">';
            tr+='<input class="in_four_category" type="hidden" name="BsVendorPurchase['+parent.m+'][four_category]" value="'+$("#type_4").val()+'">';
            tr+='<input class="in_five_category" type="hidden" name="BsVendorPurchase['+parent.m+'][five_category]" value="'+$("#type_5").val()+'">';
            tr+='<input class="in_six_category" type="hidden" name="BsVendorPurchase['+parent.m+'][six_category]" value="'+$("#type_6").val()+'">';
            tr += "</tr>";
            if(parent.$proTr==null){
                $("#product_tbody",window.parent.document).append(tr);
            }else{
                parent.$proTr.replaceWith(tr);
                parent.$proTr=null;
            }
            $("#product_tbody tr",window.parent.document).each(function(i){
                $(this).children("td:first").text(i+1);
            });
            parent.$.fancybox.close();
            parent.m++;
        });

        //取消
        $(".cancel").on("click", function () {
            parent.$.fancybox.close();
        });

        //商品库与新增商品切换
        $("#get_product_button").click(function () {
            $(this).css({"border":"1px solid #1f7ed0","border-bottom":"none","color":"#1f7ed0"}).siblings().css({"border":"none","border-bottom":"1px solid #1f7ed0","color":"black"});
            $("#add_product").hide();
            $("#get_product").show();
        });
        $("#add_product_button").click(function () {
            $(this).css({"border":"1px solid #1f7ed0","border-bottom":"none","color":"#1f7ed0"}).siblings().css({"border":"none","border-bottom":"1px solid #1f7ed0","color":"black"});
            $("#get_product").hide();
            $("#add_product").show();
        });

        //一阶到六阶
        //商品分类联动菜单
//        $('.type').on("change", function () {
//            var $select = $(this);
//            getNextTypeClass($select);
//        });
        $('.type').on("change", function () {
            var $select = $(this);
            getNextType($select, "<?=Url::to(['/ptdt/product-dvlp/get-product-type']) ?>", "select");
        });
        //递归清除级联选项
//        function clearOption($select) {
//            var select = $select.attr('id');
//            var arrNub = select.split("_");
//            var Nub = parseInt(arrNub[1]) + 1;
//            var nextSelect = "type_" + Nub;
//            var next = $("#" + nextSelect)
//            if (Nub < 7 && select != 'type_1') {
//                $select.html('<option value=>请选择</option>');
//                $("#type_6").html('<option value=>请选择</option>');
//                clearOption(next);
//            }
//        }
//        function getNextTypeClass($select) {
//            var id = $select.val();
//            if (id == "") {
//                clearOption($select);
//                return;
//            }
//            $.ajax({
//                type: "get",
//                dataType: "json",
//                async: false,
//                data: {"id": id},
//                url: "<?//=Url::to(['/ptdt/product-dvlp/get-product-type'])?>//",
//                success: function (data) {
//                    var select = $select.attr('id');
//                    var arrNub = select.split("_");
//                    var Nub = parseInt(arrNub[1]) + 1;
//                    if (Nub == 7) {
//                        return
//                    }
//                    var nextSelect = "type_" + Nub;
//                    var next = $("#" + nextSelect)
//                    clearOption(next);
//                    next.html('<option value>请选择</option>')
//                    for (var x in data) {
//                        next.append('<option value="' + data[x].type_id + '" >' + data[x].type_name + '</option>')
//                    }
//                },
//                error: function (data) {
//                    layer.alert(data.msg, {icon: 2})
//                }
//            })
//        }
    });
</script>