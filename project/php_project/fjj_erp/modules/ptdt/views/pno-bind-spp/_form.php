<?php
/**
 * User: F1677929
 * Date: 2017/11/28
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
$this->params['homeLike']=['label'=>'商品开发管理'];
$this->params['breadcrumbs'][]=['label'=>'核价资料列表','url'=>'index'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?></h1>
    <?php ActiveForm::begin();?>
    <h2 class="head-second">料号信息</h2>
    <div class="mb-10">
        <?php if(empty($editData)){?>
            <label style="width:100px;"><span style="color:red;">*</span>料号：</label>
            <input id="part_no" type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:['pnoValidate'],delay:1000000" onchange="$(this).validatebox('validate')" data-url="<?=Url::to(['/ptdt/pno-bind-spp/get-pno-info'])?>" name="PdtpricePas[part_no]">
            <span style="width:96px;"><a id="select_pno">选择</a></span>
        <?php }else{?>
            <label style="width:100px;">料号：</label>
            <span style="width:300px;"><?=$editData['part_no']?></span>
            <input type="hidden" name="PdtpricePas[part_no]" value="<?=$editData['part_no']?>">
        <?php }?>
        <label style="width:100px;">品名：</label>
        <span id="pdt_name" style="width:300px;"><?=$editData['pdt_name']?></span>
    </div>
    <div class="mb-10">
        <label style="width:100px;">品牌：</label>
        <span id="brand" style="width:300px;"><?=$editData['brand']?></span>
        <label style="width:100px;">规格型号：</label>
        <span id="tp_spec" style="width:300px;"><?=$editData['tp_spec']?></span>
    </div>
    <div class="mb-10">
        <label style="width:100px;">计量单位：</label>
        <span id="unit" style="width:300px;"><?=$editData['unit']?></span>
        <?php if(empty($editData)){?>
            <label style="width:100px;"><label style="color:red;">*</label>材质：</label>
            <input id="material" type="text" style="width:200px;" name="PdtpricePas[material]" class="easyui-validatebox" data-options="required:true,validType:'maxLength[50]'">
        <?php }else{?>
            <label style="width:100px;">材质：</label>
            <span style="width:300px;"><?=$editData['material']?></span>
            <input type="hidden" name="PdtpricePas[material]" value="<?=$editData['material']?>">
        <?php }?>
    </div>
    <div class="mb-10">
        <label style="width:100px;">分级分类：</label>
        <span id="category" style="width:709px;"><?=$editData['category']?></span>
    </div>
    <h2 class="head-second">
        关联信息
        <span style="float:right;margin-right:15px;">
            <a id="list_btn" class="icon-reorder icon-large" title="添加供应商"></a>
            <a id="all_del_btn" class="icon-remove icon-large" title="删除选中供应商"></a>
        </span>
    </h2>
    <div style="margin:0 0 10px;overflow-x:auto;">
        <table class="table" style="width:1310px;">
            <thead>
            <tr>
                <th style="width:35px;">序号</th>
                <th style="width:25px;"><input class="th_ck" type="checkbox" style="vertical-align:middle;height:auto;"></th>
                <th style="width:100px;">供应商代码</th>
                <th style="width:100px;">供应商名称</th>
                <th style="width:80px;">付款条件</th>
                <th style="width:70px;">交货条件</th>
                <th style="width:60px;">交易币别</th>
                <th style="width:90px;">生效日期</th>
                <th style="width:90px;">有效期至</th>
                <th style="width:60px;">量价区间</th>
                <th style="width:200px;">数量区间</th>
                <th style="width:100px;">采购价</th>
                <th style="width:200px;">备注</th>
                <th style="width:100px;">操作</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div style="text-align:center;">
        <button class="button-blue-big" type="submit">保存</button>
        <button class="button-blue-big" type="submit" style="margin-left:40px;">提交</button>
        <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    var sub_flag=true;
    //料号回调方法--视图select-pno和本页面用到
    function selectPnoCallback(row,from){
        $("#part_no").val(row.part_no);
        $("#pdt_name").html(row.pdt_name);
        $("#brand").html(row.brand);
        $("#tp_spec").html(row.tp_spec);
        $("#unit").html(row.unit);
        $("#category").html(row.category);
        if(from=='pno_validate'){
            if(sub_flag==true){
                getSupplierByPno(row.part_no);
            }
        }else{
            if($("#part_no").hasClass("validatebox-invalid")){
                $("#part_no").validatebox("validate");
            }else{
                getSupplierByPno(row.part_no);
            }
        }
    }

    //添加供应商
    var payment_terms="<?php
        echo "<option value=''>请选择</option>";
        foreach($addData['payment_terms'] as $val){
            echo "<option value='{$val['bsp_svalue']}'>{$val['bsp_svalue']}</option>";
        }
        ?>";
    var trading_terms="<?php
        echo "<option value=''>请选择</option>";
        foreach($addData['trading_terms'] as $val){
            echo "<option value='{$val['bsp_svalue']}'>{$val['bsp_svalue']}</option>";
        }
        ?>";
    var currency="<?php
        foreach($addData['currency'] as $val){
            echo "<option value='{$val['bsp_svalue']}'>{$val['bsp_svalue']}</option>";
        }
        ?>";
    var index=10;
    function addTr(){
        var trStr="<tr id='"+index+"'>";
        trStr+="<td></td>";
        trStr+="<td><input class='td_ck' type='checkbox' style='vertical-align:middle;height:auto;'></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td><select style='width:100%;' name='arr["+index+"][PdtpricePas][payment_terms]' class='easyui-validatebox payment_terms' data-options='required:true'>"+payment_terms+"</select></td>";
        trStr+="<td><select style='width:100%;' name='arr["+index+"][PdtpricePas][trading_terms]' class='easyui-validatebox trading_terms' data-options='required:true,validType:\"trading_termsValidate\"'>"+trading_terms+"</select></td>";
        trStr+="<td><select style='width:100%;' class='currency' name='arr["+index+"][PdtpricePas][currency]'>"+currency+"</select></td>";
        trStr+="<td><input id='effective_date"+index+"' type='text' style='width:100%;' readonly='readonly' class='Wdate easyui-validatebox effective_date' data-options='required:true' onclick='WdatePicker({skin:\"whyGreen\",minDate:\"%y-%M-%d\",maxDate:\"#F{$dp.$D(expiration_date"+index+",{d:-1})}\",onpicked:function(){$(this).validatebox(\"validate\")},oncleared:function(){$(this).validatebox(\"validate\")}});' name='arr["+index+"][PdtpricePas][effective_date]'></td>";
        trStr+="<td><input id='expiration_date"+index+"' type='text' style='width:100%;' readonly='readonly' class='Wdate easyui-validatebox expiration_date' data-options='required:true' onclick='WdatePicker({skin:\"whyGreen\",minDate:\"#F{$dp.$D(effective_date"+index+",{d:1})}\",onpicked:function(){$(this).validatebox(\"validate\")},oncleared:function(){$(this).validatebox(\"validate\")}});' name='arr["+index+"][PdtpricePas][expiration_date]'></td>";
        trStr+="<td><input class='area_flag' type='checkbox' style='vertical-align:middle;height:auto;' checked='checked'><input type='hidden' name='arr["+index+"][PdtpricePas][flag]' value='1'></td>";
        trStr+="<td><div style='display:inline-block;'><input type='text' style='width:90px;text-align:center;' class='easyui-validatebox min_num' data-options='required:true,validType:[\"six_decimal\",\"maxLength[10]\"]' name='arr["+index+"][PdtpricePas][min_num]'>~<input type='text' style='width:90px;text-align:center;' class='max_num' name='arr["+index+"][PdtpricePas][max_num]' readonly='readonly'></div></td>";
        trStr+="<td><input type='hidden' class='supplier_code' name='arr["+index+"][PdtpricePas][supplier_code]'><input type='hidden' class='supplier_name' name='arr["+index+"][PdtpricePas][supplier_name]'><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox buy_price' data-options='required:true,validType:\"six_decimal\"' name='arr["+index+"][PdtpricePas][buy_price]'></td>";
        trStr+="<td><input type='text' style='width:100%;text-align:center;' class='remarks' maxlength='100' name='arr["+index+"][PdtpricePas][remarks]' placeholder='最多输入100字'></td>";
        trStr+="<td><a class='icon-remove icon-large del_tr' title='删除' style='padding:0 5px;'></a><a class='icon-truck icon-large add_trading_terms' title='添加交货条件' style='padding:0 5px;'></a><a class='icon-plus icon-large add_num_area' title='添加数量区间' style='padding:0 5px;' data-id='"+index+"'></a></td>";
        trStr+="</tr>";
        index++;
        if(arguments[0]){
            arguments[0].after(trStr);
            $.parser.parse(arguments[0].next());
            arguments[0].next().attr("class",arguments[0].attr("class"));
            var $oneTr=$("."+arguments[0].attr("class")+":first");
            arguments[0].next().find("td:eq(2)").html($oneTr.find("td:eq(2)").html());
            arguments[0].next().find("td:eq(3)").html($oneTr.find("td:eq(3)").html());
            arguments[0].next().find("input.supplier_code").val($oneTr.find("input.supplier_code").val());
            arguments[0].next().find("input.supplier_name").val($oneTr.find("input.supplier_name").val());
            return;
        }
        $("tbody").append(trStr);
        $.parser.parse($("tbody tr:last"));
    }
    function TrVal(rows,from){//默认添加供应商时调用
        if(from == 'pno'){
            $("tbody").children().remove();
            $("#material").val("");
        }
        $.each(rows,function(i,n){
            addTr();
            var $lastTr=$("tbody").find("tr:last");
            $lastTr.find("td:eq(2)").html(n.group_code?n.group_code:n.supplier_code);
            $lastTr.find("td:eq(3)").html(n.spp_fname?n.spp_fname:n.supplier_name);
            $lastTr.find("input.supplier_code").val(n.group_code?n.group_code:n.supplier_code);
            $lastTr.find("input.supplier_name").val(n.spp_fname?n.spp_fname:n.supplier_name);
            $lastTr.attr("class",n.group_code?n.group_code:n.supplier_code);
            if(from=='pno'){
                $("#material").val(n.material);
                $lastTr.find("select.payment_terms option[value="+n.payment_terms+"]").prop("selected",true);
                $lastTr.find("select.trading_terms option[value="+n.trading_terms+"]").prop("selected",true);
                $lastTr.find("select.currency option[value="+n.currency+"]").prop("selected",true);
                $lastTr.find("input.effective_date").val(n.effective_date);
                $lastTr.find("input.expiration_date").val(n.expiration_date);
                $lastTr.find("input.area_flag").prop("checked",n.flag=="1");
                $lastTr.find("input.area_flag").next().val(n.flag);
                if(n.flag=="0"){
                    $lastTr.find("input.min_num").validatebox({required:false});
                    $lastTr.find("input.min_num").attr("readonly","readonly");
                    $lastTr.find("td:last a.add_num_area").hide();
                }
                $lastTr.find("input.min_num").val(n.min_num);
                $lastTr.find("input.max_num").val(n.max_num);
                if(n.max_num){
                    $lastTr.find("input.max_num").removeAttr("readonly");
                    $lastTr.find("input.max_num").validatebox({required:true});
                }
                $lastTr.find("input.buy_price").val(n.buy_price);
                $lastTr.find("input.remarks").val(n.remarks);
            }
        });
    }
    function selectSupplierCallback(rows){//spp/views/supplier-pop-tpl/select-supplier.php用到此方法
        TrVal(rows,'spp');
        dealSerNum();
    }

    //处理序号
    function dealSerNum(){
        var i=1;
        $("tbody tr").each(function(){
            if($(this).find("td").length==$("thead tr th").length){
                $(this).find("td:first").html(i);
                i++;
                if($(this).find("td:first").attr("rowspan")==1){
                    $(this).find("a.del_tr").show();
                }
            }
        });
    }

    //根据料号获取对应供应商
    function getSupplierByPno(pno){
        $.ajax({
            url:"<?=Url::to(['get-supplier-by-pno'])?>",
            data:{"pno":pno},
            dataType:"json",
            success:function(data){
                TrVal(data,'pno');
                autoRowSpan();
                dealSerNum();
            }
        });
    }

    //document ready
    $(function(){
        var btnFlag;
        $("button[type='submit']").click(function(){
            sub_flag=false;
            btnFlag=$(this).text();
        });
        //ajax提交表单
        ajaxSubmitForm("form",
            function(){
                if($("tbody tr").length == 0){
                    layer.alert("请至少选择一个供应商",{icon:2});
                    return false;
                }
                return true;
            },
            function(data){
                if (data.flag == 1) {
                    if(btnFlag == '提交'){
                        var id=data.billId;
                        var url="<?=Url::to(['view'],true)?>?id="+id;
                        var type=data.billTypeId;
                        $.fancybox({
                            href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                            type:"iframe",
                            padding:0,
                            autoSize:false,
                            width:750,
                            height:480,
                            afterClose:function(){
                                location.href="<?=Url::to(['view'])?>?id="+id;
                            }
                        });
                    }else{
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                if (data.url != undefined) {
                                    parent.location.href = data.url;
                                }
                            }
                        });
                    }
                }
                if (data.flag == 0) {
                    if((typeof data.msg)=='object'){
                        layer.alert(JSON.stringify(data.msg),{icon:2});
                    }else{
                        layer.alert(data.msg,{icon:2});
                    }
                    $("button[type='submit']").prop("disabled", false);
                }
            }
        );

        //选择料号
        $("#select_pno").click(function(){
            $.fancybox({
                href:"<?=Url::to(['/ptdt/pno-bind-spp/select-pno'])?>",
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:520
            });
        });

        //料号验证并带出相关信息
        $.extend($.fn.validatebox.defaults.rules,{
            //料号验证
            pnoValidate:{
                validator:function(value){
                    var data=$.ajax({
                        url:$(this).data('url'),
                        data:{"code":value},
                        async:false,
                        cache:false
                    }).responseText;
                    if(data=='false'){
                        return false;
                    }else{
                        data=JSON.parse(data);
                        selectPnoCallback(data,'pno_validate');
                        return true;
                    }
                },
                message:'料号不存在'
            },
            //数量区间比较验证
            compareValidate:{
                validator:function(value){
                    var $currTr=$(this).parents("tr");
                    var minNum=$currTr.find("input.min_num").val();
                    if(minNum=="" || parseFloat(minNum)<parseFloat(value)){
                        return true;
                    }
                },
                message:'数量区间最大值要大于最小值'
            },
            //交货条件唯一验证
            trading_termsValidate:{
                validator:function(value){
                    var $code=$("."+$(this).parents("tr").attr("class")).not($(this).parents("tr"));
                    var flag=true;
                    $.each($code,function(){
                        if($(this).find("select.trading_terms").val()==value){
                            flag=false;
                            return false;
                        }
                    });
                    return flag;
                },
                message:'交货条件已存在'
            }
        });

        //选择供应商
        $("#list_btn").click(function(){
            if($("#part_no").hasClass("validatebox-invalid") || $("#part_no").val()==""){
                layer.alert("请先选择料号",{icon:2});
                return false;
            }
            //排除已选中的商品
            var $inputs=$("tbody tr").find("input.supplier_code");
            var idStr='';
            $.each($inputs,function(i,n){
                if(n.value != ''){
                    idStr+=n.value+'-';
                }
            });
            idStr=idStr.substr(0,idStr.length-1);
            $.fancybox({
                href:"<?=Url::to(['/spp/supplier-pop-tpl/select-supplier'])?>?filters="+idStr,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:600
            });
        });

        //处理复选框
        $(document).on("click",".th_ck",function(){
            $(document).find(".td_ck").prop("checked",$(this).prop("checked"));
        });
        $(document).on("click",".td_ck",function(){
            var num=$(document).find(".td_ck:not(:checked)").length;
            $(document).find(".th_ck").prop("checked",!num);
        });

        //处理删除
        $(document).on("click","a.del_tr",function(){
            var $currTr=$(this).parents("tr");
            var $oneLevel=$("."+$currTr.attr("class")+":first");
            var rowspan=$oneLevel.find("td:first").attr("rowspan");
            if(rowspan>=2){
                $oneLevel.find("td:lt("+minCol+")").attr("rowspan",(rowspan-1));
            }
            var $id=$("#"+$currTr.find("a.add_num_area").data("id"));
            if($id.find("td:eq(2)").text()==$oneLevel.find("td:eq(2)").text()){
                rowspan=$id.find("td:eq("+minCol+")").attr("rowspan");
                if(rowspan>=2){
                    $id.find("td:lt(10):gt("+(minCol-1)+")").attr("rowspan",(rowspan-1));
                }
            }else{
                rowspan=$id.find("td:eq(0)").attr("rowspan");
                if(rowspan>=2){
                    $id.find("td:lt("+(10-minCol)+")").attr("rowspan",(rowspan-1));
                }
            }
            if($currTr.prev().find("td:last a").length==0){
                $currTr.prev().find("td:last").html($currTr.find("td:last").html());
                if($currTr.prev().find("td:eq(2)").text()==$oneLevel.find("td:eq(2)").text() && $oneLevel.find("td:first").attr("rowspan")>1){
                    $oneLevel.find("a.del_tr").hide();
                }

                $currTr.prev().find("input.max_num").val("");
                $currTr.prev().find("input.max_num").validatebox({required:false});
                $currTr.prev().find("input.max_num").attr("readonly","readonly");
            }
            $currTr.remove();
            dealSerNum();
        });

        //处理批量删除
        $(document).on("click","#all_del_btn",function(){
            $("thead").find(".th_ck").prop("checked",false);
            var $allOneLevel=$("tbody").find(".td_ck:checked").parents("tr");
            $.each($allOneLevel,function(){
                $("."+$(this).attr("class")).remove();
            });
            dealSerNum();
        });

        //修改处理
        <?php if(Yii::$app->controller->action->id=="edit"){?>
        getSupplierByPno("<?=$editData['part_no']?>");
        <?php }?>

        //数量区间
        $(document).on("click","input.area_flag",function(){
            var $currTr=$(this).parents("tr");
            if($(this).is(":checked")){
                $currTr.find("input.min_num").removeAttr("readonly");
                $currTr.find("input.min_num").validatebox({required:true});
                $currTr.find("td:last a.add_num_area").show();
                $(this).next().val("1");
            }else{
                $currTr.find("input.min_num,input.max_num").val("");
                $currTr.find("input.min_num,input.max_num").validatebox({required:false});
                $currTr.find("input.min_num,input.max_num").attr("readonly","readonly");
                $currTr.find("td:last a.add_num_area").hide();
                $(this).next().val("0");

                if($(this).parents("td").attr("rowspan")>1){
                    var id=$currTr.attr("id");
                    var btn=$("tbody a[data-id='"+id+"']").parents("td").html();
                    $currTr.find("td:last").html(btn);
                    $currTr.find("a.add_num_area").hide();
                    //需要判断当前行是否是首行
                    var $oneTr=$("."+$currTr.attr("class")+":first");
                    var oneRowspan=$oneTr.find("td:eq(0)").attr("rowspan");
                    var rowspan;
                    if(id==$oneTr.attr("id")){
                        rowspan=$currTr.find("td:eq(5)").attr("rowspan");
                        $currTr.find("td:lt(10):gt(4)").attr("rowspan",1);
                        $currTr.find("a.del_tr").hide();
                    }else{
                        rowspan=$currTr.find("td:eq(0)").attr("rowspan");
                        $currTr.find("td:lt(5)").attr("rowspan",1);
                    }
                    $oneTr.find("td:lt(5)").attr("rowspan",(oneRowspan-(rowspan-1)));
                    $currTr.nextAll().filter(":lt("+(rowspan-1)+")").remove();
                }
            }
        });

        //添加交货条件
        $(document).on("click","a.add_trading_terms",function(){
            var $currTr=$(this).parents("tr");
            addTr($currTr);
            if($currTr.find("td:eq(2)").text()==$currTr.attr("class")){
                $currTr.find("td:last a.del_tr").hide();
            }
            autoRowSpanByTrClassVal();
        });

        //添加量价区间
        $(document).on("click","a.add_num_area",function(){
            var $currTr=$(this).parents("tr");

            //处理数量区间最大值
            $currTr.find("input.max_num").removeAttr("readonly");
            $currTr.find("input.max_num").validatebox({
                required:true,
                validType:["six_decimal","maxLength[10]","compareValidate"]
            });

            addTr($currTr);
            $currTr.next().find("td:last").html($currTr.find("td:last").html());
            $currTr.next().find("td:last a.del_tr").show();
            $currTr.find("td:last").html("");

            $currTr.next().find("input.min_num").validatebox({required:false});
            $currTr.next().find("input.min_num").attr("readonly","readonly");

            autoRowSpanByTrClassVal(10);
        });

        //根据每行class值合并
        var minCol=5;//每次点击添加一行时最少合并的列数
        function autoRowSpanByTrClassVal(colNum){
            var code="";
            $("tbody tr").each(function(){
                if($(this).attr("class")==code){
                    var $oneLevel=$("."+code+":first");
                    if($(this).find("td:eq(2)").text()==$oneLevel.find("td:eq(2)").text()){
                        $(this).find("td:lt("+(colNum?colNum:minCol)+")").remove();
                        var rowspan=$oneLevel.find("td:first").attr("rowspan");
                        if(rowspan==undefined){
                            $oneLevel.find("td:lt("+(colNum?colNum:minCol)+")").attr("rowspan",2);
                            return true;
                        }
                        $oneLevel.find("td:lt("+minCol+")").attr("rowspan",(parseInt(rowspan)+1));
                        if(colNum!=undefined){
                            var $id=$("#"+$(this).find("td:last a.add_num_area").data("id"));
                            if($id.find("td:eq(2)").text()==code){
                                rowspan=$id.find("td:eq("+minCol+")").attr("rowspan");
                                if(rowspan==undefined){
                                    $id.find("td:gt("+(minCol-1)+"):lt("+(colNum-minCol)+")").attr("rowspan",2);
                                }else{
                                    $id.find("td:gt("+(minCol-1)+"):lt("+(colNum-minCol)+")").attr("rowspan",(parseInt(rowspan)+1));
                                }
                            }else{
                                rowspan=$id.find("td:eq(0)").attr("rowspan");
                                if(rowspan==undefined){
                                    $id.find("td:lt("+(colNum-minCol)+")").attr("rowspan",2);
                                }else{
                                    $id.find("td:lt("+(colNum-minCol)+")").attr("rowspan",(parseInt(rowspan)+1));
                                }
                            }
                        }
                    }
                }
                code=$(this).attr("class");
            });
        }

        //数量区间最大值+1赋给下一区间最小值
        $(document).on("change",".max_num",function(){
            var $currTr=$(this).parents("tr");
            if($currTr.next().find("input.min_num").attr("readonly")=="readonly"){
//                $currTr.next().find("input.min_num").val(parseFloat($(this).val())+1);
                $currTr.next().find("input.min_num").val(parseFloat($(this).val()));
            }
        });
    });

    //修改时自动合并
    var colNum1=5;
    var colNum2=10;
    function autoRowSpan(){
        var val1="";
        var val2="";
        var $oneLevel;
        var $twoLevel;
        var rowspan1;
        var rowspan2;
        var rowspan3;
        $("tbody tr").each(function(){
            if($(this).find("td:eq(2)").text()==val1){
                //一级合并
                $oneLevel=$("."+val1+":first");
                rowspan1=$oneLevel.find("td:first").attr("rowspan");
                if(rowspan1==undefined){
                    rowspan1=1;
                }else{
                    rowspan1=parseFloat(rowspan1);
                }
                $oneLevel.find("td:lt("+colNum1+")").attr("rowspan",rowspan1+1);
                $(this).find("td:lt("+colNum1+")").remove();
                //二级合并
                if($(this).find("select.trading_terms").val()==val2){
                    if($oneLevel.find("select.trading_terms").val()==val2){
                        rowspan2=$oneLevel.find("td:eq("+colNum1+")").attr("rowspan");
                        if(rowspan2==undefined){
                            rowspan2=1;
                        }else{
                            rowspan2=parseFloat(rowspan2);
                        }
                        $oneLevel.find("td:lt("+colNum2+"):gt("+(colNum1-1)+")").attr("rowspan",rowspan2+1);
                        $(this).find("td:lt("+(colNum2-colNum1)+")").remove();
                    }else{
                        rowspan3=$twoLevel.find("td:first").attr("rowspan");
                        if(rowspan3==undefined){
                            rowspan3=1;
                        }else{
                            rowspan3=parseFloat(rowspan3);
                        }
                        $twoLevel.find("td:lt("+(colNum2-colNum1)+")").attr("rowspan",rowspan3+1);
                        $(this).find("td:lt("+(colNum2-colNum1)+")").remove();
                    }
                    //处理操作按钮
                    $(this).find("td:last").html($(this).prev().find("td:last").html());
                    $(this).prev().find("td:last").html("");
                }else{
                    val2=$(this).find("select.trading_terms").val();
                    $twoLevel=$(this);
                }
                //处理首行的删除按钮
                $oneLevel.find("a.del_tr").hide();
            }else{
                val1=$(this).find("td:eq(2)").text();
                val2=$(this).find("select.trading_terms").val();
            }
        });
    }
</script>