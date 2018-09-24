<?php
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
use yii\helpers\Url;

?>
<style>
    thead tr th p {
        color: white;
    }
    .value-width{
        width:200px !important;
    }
    .label-width{
        width:100px;
    }
    .label-widths{
        width:160px;
    }
    .ml-220{
        margin-left: 130px;
    }
    .ml-230{
        margin-left: 70px;
    }
    .add-bottom {
        margin-bottom: 5px;
    }
    .reds{margin-left: 176px;color: #ff0000;}
    .addline{width: 80px;height: 24px; margin-left: 0; cursor: pointer;}
    ._zadm{width: 400px;float: left}
    ._psss{width: 400px;}
</style>
<h1 class="head-first" xmlns="http://www.w3.org/1999/html">
    <?= $this->title ?>
</h1>
<h2 class="head-second text-left">
    盘点单信息
</h2>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div class="ml-10">
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>法人</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove djlx" data-options="required:'true'" name="PdtInventory[legal_code]" >
            <option value="">请选择...</option>
            <?php foreach ($downList['legal_code'] as $key => $val) { ?>
                <option  value="<?= $val['company_id'] ?>"><?= $val['company_name'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align">期别</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove djlx"  name="PdtInventory[stage]" >
            <option value="">全部</option>
            <option value="1">第一期</option>
            <option value="2">第二期</option>
            <option value="3">第三期</option>
            <option value="4">第四期</option>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align">币别</label><label>:</label>
        <select class="value-width value-align easyui-validatebox remove djlx" name="PdtInventory[curency_id]" id="">
<!--            <option value="">请选择...</option>-->
            <?php foreach ($downList['cur_id'] as $key => $val) { ?>
                <option value="<?= $val['cur_id'] ?>"><?= $val['cur_code'] ?></option>
            <?php } ?>
        </select>
        <label class="ml-220 label-width label-align add-bottom"><span class="red">*</span>仓库名称</label><label>:</label>
        <select id="_name" class="ckdm value-width value-align easyui-validatebox remove qgxs" data-options="required:'true'" name="PdtInventory[wh_code]">
            <option value="">请选择...</option>
            <?php foreach ($downList['wh_name'] as $key => $val) { ?>
                <option  value="<?=$val['wh_code']?>"><?= $val['wh_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">&nbsp;&nbsp;仓库代码</label><label>:</label>
            <input id="_code" class="value-width value-align easyui-validatebox" type="text"  readonly="readonly"
                   name="">
        <label class="ml-220 label-width label-align">&nbsp;&nbsp;库存截止时间</label><label>:</label>
         <input onclick="choicetimes()" class="Wdate value-width value-align easyui-validatebox"
                name="PdtInventory[expiry_date]">
    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom"><span class="red">*</span>初盘人</label><label>:</label>
        <input class="_users value-width value-align easyui-validatebox" data-options="required:'true'"  type="text"
               value="<?=$downList['staff']['staff_code']?>" placeholder="请输入工号">
        <input class="_users111"  type="hidden"
               name="PdtInventory[first_ivtor]" value="<?=$id?>">
        <label class="ml-220 label-width label-align add-bottom"><span class="red">*</span>初盘日期</label><label>:</label>
        <input class="Wdate value-width value-align easyui-validatebox" data-options="required:'true'" onclick="choicetime()"
               name="PdtInventory[first_date]" value="<?=date("Y-m-d")?>">

    </div>
    <div class="mb-10">
        <label class="label-width label-align add-bottom">复盘人</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" type="text"  readonly="readonly"
               name="PdtInventory[re_ivtor]">
        <label class="ml-220 label-width label-align">复盘日期</label><label>:</label>
        <input class="value-width value-align easyui-validatebox" type="date"  readonly="readonly"
               name="PdtInventory[re_date]">
    </div>
<h2 class="head-second text-left mt-30">
    盘点商品信息 <span class="text-right float-right">
                <a id="select_product">添加</a>
                <a id="delete_product">删除</a></span>
</h2>
<div class="mb-20 tablescroll" style="overflow-x: scroll">
    <table class="table" style="width: 1500px;">
        <thead>
        <tr>
            <th><p style="width:40px; ">序号</p></th>
            <th><p style="width:40px;"><input type="checkbox" id="checkAll"></p></th>
            <th><p style="width:150px"><span class="red">*</span>料号</p></th>
            <th><p style="width:150px">品名</p></th>
            <th><p style="width:150px">规格型号</p></th>
            <th><p style="width:150px">单位</p></th>
            <th><p style="width:150px">成本单价</p></th>
            <th><p style="width:150px">库存数量</p></th>
            <th><p style="width:150px"><span class="red">*</span>初盘数量</p></th>
            <th><p style="width:150px">复盘数量</p></th>
            <th><p style="width:150px">盈亏数量</p></th>
            <th><p style="width:150px">盈亏金额</p></th>
            <th><p style="width:150px">初盘备注</p></th>
            <th><p style="width:150px">复盘备注</p></th>
            <th><p style="width:150px">操作</p></th>
            <?php foreach ($columns as $key => $val) { ?>
                <th><p class="text-center width-150 "><?= $val["field_title"] ?></p></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody id="product_table">
        <tr>
            <td>1</td>
            <td><input type="checkbox"></td>
            <td><input type='text' class='_partno easyui-validatebox' name='prod[0][PdtInventoryDt][part_no]' data-options="required:'true'"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><input type="text" readonly="readonly" id="ivtnum"></td>
            <td><input type='text' data-options="required:'true'"  id="first" name='prod[0][PdtInventoryDt][first_num]'  class='_num1 easyui-validatebox'></td>
            <td><input type='text'  name='prod[0][PdtInventoryDt][re_num]'  readonly="readonly"  class='_num2 easyui-validatebox '></td>
            <td><input type='text'  name='prod[0][PdtInventoryDt][lose_num]'  readonly="readonly"  class='_num3 easyui-validatebox '></td>
            <td><input readonly="readonly" type="text" name="prod[0][PdtInventoryDt][lose_price]"></td>
<!--            <td><span class="_price"></span><input type='hidden' name='prod[0][BsReqDt][req_price]' class='subpri'></td>-->
<!--            <td><span class='totalspan'></span><input type='hidden' name='prod[0][BsReqDt][total_amount]' class='_total'></td>-->
<!--            <td><input type='hidden' name='prod[0][BsReqDt][exp_account]' value=''></td>-->
<!--            <td><input onclick='choicetime()' type='text' readonly data-options="required:'true'"  name='prod[0][BsReqDt][req_date]' class='_choicetime easyui-validatebox' ></td>-->
<!--            <td><input type='hidden' name='prod[0][BsReqDt][prj_no]' ></td>-->
<!--            <td></td>-->
<!--            <td><input type='hidden' name='prod[0][BsReqDt][org_price]' ></td>-->
<!--            <td><input type='hidden' name='prod[0][BsReqDt][rebat_rate]'></td>-->
            <td><input type='text' name='prod[0][PdtInventoryDt][remarks]' maxlength='100' data-options="required:'true'"  class='_remarks' placeholder="最多输入100字"></td>
            <td><input type='text' name='prod[0][PdtInventoryDt][remarks1]' maxlength='100' class='_remarks' readonly="readonly"></td>
            <td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a></td>
        </tr>
        </tbody>
    </table>
</div>
<div style="margin-top: 20px;"></div>
<p class="text-left mb-20">
    <input type="button" class="icon-plus button-white-big addline" onclick="add_product()" value="+ 添加行">
</p>
<div style="margin-top: 40px;"></div>
<div style="text-align:center;">
    <button class="button-blue-big" type="submit">保存</button>
    <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    //采购部门
    function purchurse_apar() {
        var url;
        url = '<?= Url::to(['select-depart']) ?>';
        $.fancybox({
            autoScale: true,
            padding: [],
            fitToView: false,
            width: 520,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: url
        });
    }
//    function check(){
//        var i=document.getElementById("first").value;
//        if ( isNaN(i) ) {
//            layer.alert("只能输入数字！",{icon: 2});
//        }
//    }
    //点击配送地点后面搜索图标弹框
    function send_addr() {
        var url;
        url = '<?= Url::to(['select-place']) ?>';
        $.fancybox({
//            autoScale: true,
//            padding: [],
            fitToView: false,
            width: 520,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: url
        });
    }
    $(function () {
        //获取仓库代码
     $(document).on("change",".ckdm",function () {
         var name=$("#_name").val();
        $("#_code").val(name);

        });
     //获取盈亏
        $(document).on("change","._num1",function () {
                $_num1 = $(this);
                $_num1.val($_num1.val().replace(/(\.\d{2})\d*$/,'\$1'));
                var numss1 = $_num1.parents("tr").find("td").eq(7).text();
                var numss = $_num1.val();
//                $_num1.val(numss.replace(/(\.\d{2})\d*$/,'\$1'));
            if ( isNaN(numss)||numss.substr(0,1)=='.'||numss<0) {
                layer.alert("只能输入不小于0的数字！",{icon: 2});
                $_num1.val("");
            }else {
                if (numss1 != "") {
                    if (numss != "") {
                        $_num1.parents("tr").find("._num3").text(numss - numss1);
                        $_num1.parents("tr").find("._num3").val(numss - numss1);
                    } else {
                        $_num1.parents("tr").find("._num3").text("");
                        $_num1.parents("tr").find("._num3").val("");
                    }
                }
            }
        });
        //保存和提交
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
            //验证商品数量，需求日期不为空
            $("#product_table").find("tr").each(function () {
                var part=$(this).find("._partno").val();
                var num=$(this).find("._num").val();
                var _time=$(this).find("._choicetime").val();
                if(part==""||num==""||_time==""||num<=0) {
                    if(part==""){
                        $(this).find("._partno").addClass("validatebox-invalid");
                    }else if(num==""||num<=0) {
                        $(this).find("._num").addClass("validatebox-invalid");
                    }else if(_time=="") {
                        $(this).find("._choicetime").addClass("validatebox-invalid");
                    }
                    return false;
                }else {
                    $(this).find("._partno").removeClass("validatebox-invalid");
                    $(this).find("._num").removeClass("validatebox-invalid");
                    $(this).find("._choicetime").removeClass("validatebox-invalid");
                }
            });
        });
        $(".Onlynum").numbervalid();
        ajaxSubmitForm("form","",function(data){
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
        });
        //时间控制
        $("#auth_stime").click(function(){
            alert();
            WdatePicker({
                skin:"whyGreen",
                dateFmt:"yyyy-MM-dd"
            });
        });
    });
    //选择商品
    $("#select_product").click(function(){
        //选择仓库
        if (($("#_name").val())==""){
            layer.alert("请先选择仓库信息!", {icon: 2});
        }
        //排除已选中的商品
        else {
            var $selectedRows = $("#purpdt_tbody").find("input");
            var selectedId = '';
            if ($selectedRows.length > 0) {
                $.each($selectedRows, function (i, n) {
                    if (n.value != '') {
                        selectedId += n.value + ',';
                    }
                });
                selectedId = selectedId.substr(0, selectedId.length - 1);
            }
            $.fancybox({
                width: 720,
                height: 500,
                padding: [],
                autoSize: false,
                type: "iframe",
                href: "<?=\yii\helpers\Url::to(['/warehouse/check-list/prod-select'])?>?filters=" + selectedId+'&wh='+$("#_name").val()
            });
        }
    });



    //拟采购商品
    var purpdtIndex=$("#product_table").find("tr").length;
    function addPurpdt(){
        var trStr="<tr>";
        trStr+="<td></td>";
        trStr+="<td><input type='checkbox'></td>";
        trStr+="<td><input type='text' class='easyui-validatebox  _partno' name='prod["+purpdtIndex+"][PdtInventoryDt][part_no]' data-options=\"required:'true'\"></td>"; //
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td><input id='ivnt' type='text'  readonly='readonly'  class='_num4 easyui-validatebox'></td>";
        trStr+="<td><input type='text' data-options=\"required:'true'\"  name='prod["+purpdtIndex+"][PdtInventoryDt][first_num]'  class='_num1 easyui-validatebox' id='first'></td>";
        trStr+="<td><input type='text'  name='prod["+purpdtIndex+"][PdtInventoryDt][re_num]'  readonly='readonly'  class='_num2 easyui-validatebox'></td>";
        trStr+="<td><input type='text'  name='prod["+purpdtIndex+"][PdtInventoryDt][lose_num]'  readonly='readonly'  class='_num3 easyui-validatebox'></td>";
        trStr+="<td><input type='text'  name='prod["+purpdtIndex+"][PdtInventoryDt][lose_price]'  readonly='readonly' ></td>";
        trStr+="<td><input type='text' name='prod["+purpdtIndex+"][PdtInventoryDt][remarks]' data-options=\"required:'true'\" maxlength='100' class='_remarks' placeholder='最多输入100字'></td>";
        trStr+="<td><input type='text' name='prod["+purpdtIndex+"][PdtInventoryDt][remarks1]' maxlength='100' class='_remarks' readonly='readonly'></td>";
        trStr+="<td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large reset_mpdt' title='重置' style='margin-left:20px;'></a></td>";
        trStr+="</tr>";
        var obj = $("#product_table").append(trStr).find("tr").each(function(index){
            $(this).find("td:first").html(index+1);
        });
        $.parser.parse(obj);
        purpdtIndex++;
    }
    //获取时间
    function choicetime() {
        WdatePicker({
            skin:"whyGreen",
            dateFmt:"yyyy-MM-dd",
            minDate:'%y-%M-{%d}'
        });
    }
    function choicetimes() {
        WdatePicker({
            skin:"whyGreen",
            dateFmt:"yyyy-MM-dd HH:mm",
            minDate:'%y-%M-{%d}'
        });
    }
    //获取料号
    function purpdtVal(rows){
        var _partno="";
        var obj2;
        $.each(rows,function(i,n){
            _partno+=n.part_no+",";
        });
        obj2=_partno.split(",");
        wh=$("#_name").val();
//        alert(obj2);
        if(obj2!=""&&obj2!=null)
        {
            $.ajax({
                url:"<?=Url::to(['get-purchase-product'])?>",
                data:{"id":obj2,"wh":wh},
                dataType:"json",
                success:function(data){
                    var arr=new Array();
                    $("#product_table").find("tr").each(function () {
                        var par=$(this).find("._partno").val();
                        arr.push(par);
                    });
                    $("#product_table").find("tr").each(function () {
                        var ls=$(this).find("._partno").val();
                        if(ls=="") {
                            $(this).remove();
                        }
                    });
                    for(var i=0;i<data.rows.length;i++)
                    {
                        $.each(data.rows[i],function(){
                            for (var j=0;j<data.rows[i].length;j++)
                            {
                                if((arr.indexOf(data.rows[i][j].part_no))<0)  //限制重复添加料号
                                {
                                    addPurpdt();
                                    var $trLast=$("#product_table").find("tr:last");
                                    $trLast.find("._partno").val(data.rows[i][j].part_no);
                                    $trLast.find("._partno").addClass("partnos");
                                    $trLast.find("td:eq(3)").text(data.rows[i][j].pdt_name);
                                    $trLast.find("td:eq(4)").text(data.rows[i][j].tp_spec);
                                    $trLast.find("td:eq(5)").text(data.rows[i][j].unit);
                                    $trLast.find("td:eq(7)").text(data.rows[i][j].invt_num).addClass("_num4");
                                    $trLast.find("._num").val("");
                                    $trLast.find("._choicetime").val("");

                                }else {
                                    layer.alert("料号"+data.rows[i][j].part_no+"已经添加过了,请重新选择",{icon:2});
                                }
                            }
                        })
                    }
                }
            })
        }
    }
    //调用函数
    function productSelectorCallback(rows) {
        purpdtVal(rows);
    }

    //+添加商品
    function add_product() {
        addPurpdt();
    }

    //重置和删除
    $(document).on("click",".icon-remove",function(){
        var $tbody=$(this).parents("tbody");
        var _len=$tbody.find("tr").length;

        if(_len>1){
            $(this).parents("tr").remove();
            $tbody.find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        }
        else{
            layer.alert("无法删除,最少添加一行商品信息!",{icon:2})
        }
    });
    $(document).on("click",".icon-repeat",function(){
        $(this).parents("tr").find("input").val('');
        $(this).parents("tr").find("td").eq(3).text('');
        $(this).parents("tr").find("td").eq(4).text('');
        $(this).parents("tr").find("td").eq(5).text('');
        $(this).parents("tr").find("td").eq(6).text('');
        $(this).parents("tr").find("td").eq(7).text('').addClass("_num4");
        $(this).parents("tr").find("._price").text('');
        $(this).parents("tr").find("._remarks").text('');
        $(this).parents("tr").find("._remarks1").text('');
//        $(this).parents("tr").find("td").eq(10).addClass("_num3");
        $(this).parents("tr").find(".totalspan").text('');
    });

    //全选
    $(document).on("click","#checkAll",function () {
        if ($(this).is(":checked")) {
            $('.table').find("td input[type='checkbox']").prop("checked", true);
        } else {
            $('.table').find("td input[type='checkbox']").prop("checked", false);
        }
    });

    //批量删除
    $("#delete_product").on('click', function () {
        $('#product_table input:checkbox:checked').each(function () {
            var $tbody=$(this).parents("tbody");
            var _length=$tbody.find("tr").length;
            if(_length>1){
                $(this).parents("tr").remove();
                $tbody.find("tr").each(function(index){
                    $(this).find("td:first").html(index+1);
                });
            }
            else{
                layer.alert("无法删除,最少添加一行商品信息!",{icon:2})
            }
        });
    });

    //单个料号新增
    $(document).on("change", "._partno", function () {
        var $pdt_no = $(this);
        var arrayObj = new Array();
        var wh=$("#_name").val();
        $pdt_no.validatebox();        //验证初始化
        var pdt_no = $(this).val();
        arrayObj.push(pdt_no);
        var row = $(this).parent().prev().prev().find("span").html();//行数
        var url = "<?= Url::to(['get-purchase-product'])?>";
        var arr=new Array();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"id": arrayObj,"wh":wh},
            url: url,
            beforeSend:function () {
                $("#product_table").find("tr").each(function () {
                    var par=$(this).find(".partnos").val();
                    arr.push(par);
                });
            },
            success: function (data) {
                if(data.rows[0].length>0)
                {
                    if((arr.indexOf(data.rows[0][0].part_no))<0) {
//                        var ss = data.rows[0][0].price;
//                        str = ss.substr(0, ss.length - 1);
                        $pdt_no.parent().parent().find(".pkid").val(data.rows[0][0].prt_pkid);
                        //                    $pdt_no.parent().parent().find("._partno").val(data.rows[0][0].pdt_name);
                        $pdt_no.parent().parent().find("._partno").addClass("partnos");
                        $pdt_no.parent().parent().find("td").eq(3).text(data.rows[0][0].pdt_name);
                        $pdt_no.parent().parent().find("td").eq(4).text(data.rows[0][0].tp_spec);

                        $pdt_no.parent().parent().find("td").eq(5).text(data.rows[0][0].unit);
                        $pdt_no.parent().parent().find("td").eq(7).text(data.rows[0][0].invt_num).addClass("_num4");
//                        $pdt_no.parent().parent().find("._price").text(str);
//                        $pdt_no.parent().parent().find("td").eq(10).addClass("_num3");
                    }else {
                        layer.alert("料号"+data.rows[0][0].part_no+"已经添加过了,请重新选择",{icon:2});
                    }
//                    $pdt_no.parent().parent().find("td").eq(10).addClass("_num3");
                }else {
                    layer.alert("未找到该料号!", {icon: 2});
                    $pdt_no.parent().parent().find("td").eq(3).text("");
                    $pdt_no.parent().parent().find("td").eq(4).text("");
                    $pdt_no.parent().parent().find("td").eq(5).text("");
                    $pdt_no.parent().parent().find("td").eq(6).text("");
                    $pdt_no.parent().parent().find("td").eq(7).text("").addClass("_num4");
                    $pdt_no.parent().parent().find("._price").text("");
//                    $pdt_no.parent().parent().find("td").eq(10).addClass("_num3");
                }
            },
            error: function (data) {
                layer.alert("请求错误!", {icon: 2});
            }
        })
    });


    //领用人
    $(document).on("change","._users",function () {
        $staffcode=$("._users").val();
//        alert($staffcode);
        var url = "<?= Url::to(['get-staff-info'])?>?code="+$staffcode;
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"code":$staffcode},
            url: url,
            success: function (data) {
                if(data!=false){
                    var ret = data.staff_code+"--"+data.staff_name;
                    $("._users").val(ret);
                    $("._tel").val(data.staff_mobile);
                    $("._tel").removeClass("validatebox-invalid");
                    $("._tel").attr("data-options","false");
                    $("._users111").val(data.staff_id);
                }else {
                    layer.alert("没有查到此工号",{icon: 2});
                }
            }
        })
    });

    //领用电话
//    $(document).on("change","._tel",function () {
//        $('._tel').validatebox({required: true, validType: 'tel_mobile_c'});
//    }).parser.parse();

    //采购部门模糊搜索
    $(document).on("change",".pur_apart",function () {
        var _text=$(".pur_apart").val().trim();
        $.fancybox({
            autoScale: true,
            padding: [],
            fitToView: false,
            width: 520,
            height: 350,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: '<?= Url::to(['select-depart']) ?>?keyWord='+_text
        });
    });

</script>