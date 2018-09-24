<?php
/**
 * Created by PhpStorm.
 * User: G0007903
 * Date: 2017/12/15
 */
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
use yii\helpers\Url;
?>
<style>
    td p {
        display: block;
        overflow: hidden;
        word-break: break-all;
        word-wrap: break-word;
    }

    thead tr th p {
        color: white;
    }
    .width200{
        width: 200px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .width100{
        width: 100px;
    }
    .width270{
        width: 150px;
    }
    .value-width{
        width:200px !important;
    }
</style>

<h1 class="head-first" xmlns="http://www.w3.org/1999/html">
    <?= $this->title ?>
    <span style="color: white;float: right;font-size:12px;margin-right:20px">盘点单号：<?=$model['ivt_code']?></span>
</h1>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<h2 class="head-second text-left">
    盘点单信息
</h2>
<div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">法人<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['company_name'] ?></label>
        <label class="label-width qlabel-align width270">期别<label>：</label></label>
        <select class="value-width value-align easyui-validatebox remove" name="PdtInventory[stage]" id="">
                <option value="">请选择...</option>
                <option value="1" <?php if($model['st']==1){echo selected;}?>>第一期</option>
                <option value="2" <?php if($model['st']==2){echo selected;}?>>第二期</option>
                <option value="3" <?php if($model['st']==3){echo selected;}?>>第三期</option>
                <option value="4" <?php if($model['st']==4){echo selected;}?>>第四期</option>
            </select>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">币别<label>：</label></label>
        <select class="value-width value-align easyui-validatebox remove" name="PdtInventory[curency_id]" id="">
            <option value="">请选择...</option>
            <?php foreach ($downList['cur_id'] as $key => $val) { ?>
                <option value="<?= $val['cur_id'] ?>" <?php if ($model['curency_id']==$val['cur_id']){echo selected;} else{echo "";} ?>><?= $val['cur_code'] ?></option>
            <?php } ?>
        </select>
        <label class="label-width qlabel-align width270">仓库名称<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['wh_name'] ?></label>
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">仓库代码<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['wh_code'] ?></label>
        <label class="label-width qlabel-align width270">库存截止时间<label>：</label></label>
        <input onclick="choicetimes()" class="Wdate label-width text-left width200"  name="PdtInventory[expiry_date]" value="<?= $model['expiry_date']?>">
    </div>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20">初盘人<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['first_ivtor'] ?></label>
        <label class="label-width qlabel-align width270">初盘日期<label>：</label></label>
        <label class="label-width text-left width200"><?= $model['first_date'] ?></label>
    <div class="mb-10">
        <label class="label-width qlabel-align width100 ml-20"><span class="red">*</span>复盘人<label>：</label></label>
        <input   type="hidden" class="_users111" name="PdtInventory[re_ivtor]" value="<?=$model['re']?>">
        <input data-options="required:true" placeholder="请输入工号"  class="_users label-width text-left width200 easyui-validatebox"  value="<?= $model['re_ivtor'] ?>">
        <label class="label-width qlabel-align width270"><span class="red">*</span>复盘日期<label>：</label></label>
        <input required="required" onclick="choicetime()" class="Wdate label-width text-left width200 easyui-validatebox"  name="PdtInventory[re_date]" value="<?= $model['re_date']?>">
    </div>
</div>
<h2 class="head-second text-left mt-30">
    盘点商品信息
</h2>
<div class="mb-20" style="overflow: auto">
    <div style="width:100%;overflow: auto;">
        <table class="table" style="width: 1400px;"->
            <thead>
            <th><p style="width:40px; ">序号</p></th>
            <th><p style="width:150px">料号</p></th>
            <th><p style="width:150px">品名</p></th>
            <th><p style="width:150px">规格型号</p></th>
            <th><p style="width:150px">单位</p></th>
            <th><p style="width:150px">成本单价</p></th>
            <th><p style="width:150px">库存数量</p></th>
            <th><p style="width:150px">初盘数量</p></th>
            <th><p style="width:150px">复盘数量</p></th>
            <th><p style="width:150px">盈亏数量</p></th>
            <th><p style="width:150px">盈亏金额</p></th>
            <th><p style="width:150px">初盘备注</p></th>
            <th><p style="width:150px">复盘备注</p></th>
            </thead>
            <tbody id="product_table" class="_product_table">
            <?php foreach ($pdtmodel as $key => $val) { ?>
                <tr style="height: 32px;">
                    <td><?= ($key + 1) ?></td>
                    <td><input style="width: 100%" type="text" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][part_no]' value="<?= $val["part_no"] ?>"></td>
                    <td><input style="width: 100%" type="text" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][pdt_name]' value="<?= $val["pdt_name"] ?>"></td>
                    <td><input style="width: 100%" type="text" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][tp_spec]' value="<?= $val["tp_spec"] ?>"></td>
                    <td><input style="width: 100%" type="text" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][unit]' value="<?= $val["unit"] ?>"></td>
                    <td><input style="width: 100%" type="text" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][notax_price]' value="<?= $val["notax_price"] ?>"></td>
                    <td><input style="width: 100%" type="text" id="ivtnum<?=$key?>" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][invt_num]' value="<?= $val["invt_num"] ?>"></td>
                    <td><input style="width: 100%" type="text" id="frnum<?=$key?>" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][first_num]' value="<?= $val["first_num"] ?>"></td>
                    <td><input style="width: 100%" type='text' onchange="change(this)" id="renum<?=$key?>" name='prod[<?= ($key) ?>][PdtInventoryDt][re_num]' value="<?= $val["re_num"] ?>"></td>
                    <td><input style="width: 100%" type='text' id="losenum<?=$key?>" readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][lose_num]' value="<?= $val["lose_num"] ?>"></td>
                    <td><input style="width: 100%" type='text' readonly="readonly" name='prod[<?= ($key) ?>][PdtInventoryDt][lose_price]' value="<?= $val["lose_price"] ?>"></td>
                    <td><input style="width: 100%" type='text'  name='prod[<?= ($key) ?>][PdtInventoryDt][remarks]' maxlength='100' class='_remarks'  readonly="readonly" value="<?= $val["remarks"]?>"></td>
                    <td><input style="width: 100%" type='text'  name='prod[<?= ($key) ?>][PdtInventoryDt][remarks1]' maxlength='100' class='_remarks' placeholder="最多输入100字"  value="<?= $val["remarks1"]?>"></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top: 20px;"></div>
<div style="margin-bottom: 40px;"></div>
<div style="text-align:center;">
    <button class="button-blue-big" type="submit">保存</button>
    <button class="button-blue-big" type="submit" style="margin-left:40px;">提交</button>
    <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
</div>
<?php ActiveForm::end()?>
<script>
    //盈亏数量
    function change(object) {
        if ( isNaN($(object).val())||$(object).val().substr(0,1)=='.'||$(object).val()<0 ) {
            layer.alert("只能输入不小于0的数字！",{icon: 2});
            $(object).val("");
        }
        var tr=$("#product_table tr");
        $(tr).each(function(){
            var ivtnum=$(this).children().eq(6).find('input').val();
            var renum=$(this).children().eq(8).find('input').val();
//            $(this).children().eq(8).find('input').val((renum-0).toFixed(2));
            var frnum=$(this).children().eq(7).find('input').val();
            var losenum=$(this).children().eq(9).find('input').val();
            if(ivtnum!="") {
                if (renum != "") {
                    $(this).children().eq(8).find('input').val((renum - 0).toFixed(2));
                    $(this).children().eq(9).find('input').val((renum - ivtnum).toFixed(2));
                }
                else if (frnum != "") {
                    $(this).children().eq(9).find('input').val((frnum - ivtnum).toFixed(2));
                } else {
                    $(this).children().eq(9).find('input').val("");
                }
            }

        })
//        for(var j=0;j<tr.length;j++)
//        {
//
//        }


//
//        $("#renum").val($("#renum").val().replace(/(\.\d{2})\d*$/,'\$1'));
//        if(($("#ivtnum").val())!="") {
//            if ($("#renum").val() != "") {
//                var a = $("#ivtnum").val();
//                var b = $("#renum").val();
//                $("#losenum").val(b - a);
//            }
//            else if ($("#frnum").val() != "") {
//                var a1 = $("#ivtnum").val();
//                var b1 = $("#frnum").val();
//                $("#losenum").val(b1 - a1);
//            } else {
//                $("#losenum").val("");
//            }
//        }
    }
    $(function () {
        //提交
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
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
//            alert(<?//=$downList['business'][0]['business_type_id']?>//);
            if (data.flag == 1) {
                if(btnFlag == '提交'){
                    var id=<?=$model['ivt_id']?>;
                    var code='<?=$model['ivt_code']?>';
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=<?=$downList['business'][0]['business_type_id']?>;
                    $.fancybox({
                        href:"<?=Url::to(['/warehouse/check-list/reviewer'])?>?type="+type+"&id="+id+"&url="+url+"&code="+code,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:750,
                        height:480,
                        afterClose:function(){
                            location.href="<?=Url::to(['view'])?>?id="+id+'&code='+code;
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
        //修改收縮樣式修改
        $(".head-second").next("div:eq(0)").css("display", "block");
        $(".head-second>a").click(function () {
            $(this).parent().next().slideToggle();
            $(this).prev().toggleClass("icon-caret-down");
            $(this).prev().toggleClass("icon-caret-right");
            $(".retable").datagrid("resize");
        });
        //当选择不是领用人时弹框
        $('input:radio[name="BsReq[yn_lead]"]').change(function(){
            if($(this).is(":checked")) {
//                 alert($(this).val());
                var la = $(this).val();
                if (la == 0) {
                    $("._lyperson").show();
                    $('._users').validatebox({required: true, validType: 'regCard'});
                    $('._tel').validatebox({required: true, validType: 'tel_mobile_c'});
                } else {
                    $("._lyperson").hide();
                    $(".yyyyy").hide();
                    $("._users111").val("");
                    $("._tel").val("");
                    $("._users").validatebox({required: false});
                    $("._tel").validatebox({required: false});
                }
            }
        });
        //选择商品
        $("#select_product").click(function(){
            //排除已选中的商品
            var $selectedRows=$("#purpdt_tbody").find("input");
            var selectedId='';
            if($selectedRows.length > 0){
                $.each($selectedRows,function(i,n){
                    if(n.value != ''){
                        selectedId+=n.value+',';
                    }
                });
                selectedId=selectedId.substr(0,selectedId.length-1);
            }
            $.fancybox({
                width:720,
                height:500,
                padding:[],
                autoSize:false,
                type:"iframe",
                href:"<?=\yii\helpers\Url::to(['/warehouse/check-list/prod-select'])?>?filters="+selectedId
            });
        });
    });
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
    //拟采购商品
    var purpdtIndex=$("._product_table").find("tr").length;
    //添加一行tr
    function addPurpdt(){
        var trStr="<tr>";
        trStr+="<td></td>";
        trStr+="<td><input type='checkbox'></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][prt_pkid]' class='pkid'><input type='text' " +
        "class='easyui-validatebox  _partno' data-options=\"required:'true'\" ></td>"; //
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td><input type='text' maxlength='10' name='prod["+purpdtIndex+"][BsReqDt][req_nums]' value='' placeholder='请输入数量' class='_num easyui-validatebox' data-options=\"required:'true',validType:'intnum'\"></td>";
        trStr+="<td><span class='_price'></span><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][req_price]' class='subpri'></td>";
        trStr+="<td><span class='totalspan'></span><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][total_amount]' class='_total'></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][exp_account]' value=''></td>";
        trStr+="<td><input onclick='choicetime()' type='text' readonly='true' name='prod["+purpdtIndex+"][BsReqDt][req_date]' class='_choicetime easyui-validatebox ' data-options=\"required:'true'\"></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][prj_no]' ></td>";
        trStr+="<td></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][org_price]' ></td>";
        trStr+="<td><input type='hidden' name='prod["+purpdtIndex+"][BsReqDt][rebat_rate]'></td>";
        trStr+="<td><input type='text' name='prod["+purpdtIndex+"][BsReqDt][remarks]' maxlength='100'  class='_remarks'></td>";
        trStr+="<td><input type='text' name='prod["+purpdtIndex+"][BsReqDt][remarks1]' maxlength='100' placeholder=\"最多输入100字\" class='_remarks'></td>";
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
            minDate:'%y-%M-{%d+1}'
        });
    }
    function choicetimes() {
        WdatePicker({
            skin:"whyGreen",
            dateFmt:"yyyy-MM-dd HH:ss",
            minDate:'%y-%M-{%d+1}'
        });
    }
    function purpdtVal(rows){
        var _partno="";
        var obj2;
        $.each(rows,function(i,n){
            _partno+=n.part_no+",";
        });
        obj2=_partno.split(",");
        if(obj2!=""&&obj2!=null)
        {
            $.ajax({
                url:"<?=Url::to(['get-purchase-product'])?>",
                data:{"id":obj2},
                dataType:"json",
                success:function(data){
                    var arr=new Array();
                    $("#product_table").find("tr").each(function () {
                        var par=$(this).find("._partno").val();
                        arr.push(par);
                    });
                    $("#product_table").find("tr").each(function () {
                        var ls=$(this).find(".pkid").val();
                        if(ls=="") {
                            $(this).remove();
                        }
                    });
                    for(var i=0;i<data.rows.length;i++)
                    {
                        $.each(data.rows[i],function(){
                            for (var j=0;j<data.rows[i].length;j++)
                            {
                                if((arr.indexOf(data.rows[i][j].part_no))<0)
                                {
                                    var ss=data.rows[i][j].price;
                                    str = ss.substr(0,ss.length-1);
                                    addPurpdt();
                                    var $trLast=$("#product_table").find("tr:last");
                                    $trLast.find("input").val(data.rows[i][j].prt_pkid);
                                    $trLast.find("._partno").val(data.rows[i][j].part_no);
                                    $trLast.find("._partno").addClass("partnos");
                                    $trLast.find("td:eq(3)").text(data.rows[i][j].pdt_name);
                                    $trLast.find("td:eq(4)").text(data.rows[i][j].tp_spec);
                                    $trLast.find("td:eq(5)").text(data.rows[i][j].brand_name_cn);
                                    $trLast.find("td:eq(6)").text(data.rows[i][j].unit);
//                                    $trLast.find("td:eq(7)").text(data.rows[i][j].invt_num);
                                    $trLast.find("._num").val("");
                                    $trLast.find("._price").text(str);
                                    $trLast.find(".subpri").val(data.rows[i][j].price);
//                                    $trLast.find("._spp").text(data.rows[i][j].spp_code);
//                                    $trLast.find(".subspp").val(data.rows[i][j].spp_id);
                                    $trLast.find("td:eq(10)").text("");
                                    $trLast.find("._choicetime").val("");
                                    $trLast.find("td:eq(12)").text("");
                                    $trLast.find("td:eq(13)").text("");
                                    $trLast.find("td:eq(14)").text("");
                                    $trLast.find("td:eq(15)").text("");
                                    $trLast.find("._remarks").val("");
                                    $trLast.find("._remarks1").val("");
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
    //删除
    $(document).on("click",".icon-remove",function(){
        var $tbody=$(this).parents("tbody");
        var _len=$tbody.find("tr").length;
        if(_len>1){
            $(this).parents("tr").remove();
            $tbody.find("tr").each(function(index){
                $(this).find("td:first").html(index+1);
            });
        }
    });
    //重置
    $(document).on("click",".icon-repeat",function(){
        $(this).parents("tr").find("input").val('');
        $(this).parents("tr").find("td").eq(3).text('');
        $(this).parents("tr").find("td").eq(4).text('');
        $(this).parents("tr").find("td").eq(5).text('');
        $(this).parents("tr").find("td").eq(6).text('');
//        $(this).parents("tr").find("td").eq(7).text('');
        $(this).parents("tr").find("._price").text('');
        // $(this).parents("tr").find("td").eq(10).text('');
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
        });
    });
    //单笔料号新增
    $(document).on("change", "._partno", function () {
        var $pdt_no = $(this);
        var arrayObj = new Array();
        $pdt_no.validatebox();        //验证初始化
        var pdt_no = $(this).val();
        arrayObj.push(pdt_no);
        var row = $(this).parent().prev().prev().find("span").html();//行数
        var url = "<?= Url::to(['get-purchase-product'])?>";
        var arr=new Array();
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"id": arrayObj},
            url: url,
            beforeSend:function () {
                $("#product_table").find("tr").each(function () {
                    var par=$(this).find(".partnos").val();
                    arr.push(par);
                });
            },
            success: function (data) {
                var arr=new Array();
                $("#product_table").find("tr").each(function () {
                    var par=$(this).find(".partnos").val();
                    arr.push(par);
                });
                if(data.rows[0].length>0)
                {
                    if((arr.indexOf(data.rows[0][0].part_no))<0) {
                        var ss = data.rows[0][0].price;
                        str = ss.substr(0, ss.length - 1);
                        $pdt_no.parent().parent().find(".pkid").val(data.rows[0][0].prt_pkid);
                        $pdt_no.parent().parent().find("._partno").addClass("partnos");
                        $pdt_no.parent().parent().find("td").eq(3).text(data.rows[0][0].pdt_name);
                        $pdt_no.parent().parent().find("td").eq(4).text(data.rows[0][0].tp_spec);
                        $pdt_no.parent().parent().find("td").eq(5).text(data.rows[0][0].brand_name_cn);
                        $pdt_no.parent().parent().find("td").eq(6).text(data.rows[0][0].unit);
                        //                    $pdt_no.parent().parent().find("td").eq(7).text(data.rows[0][0].invt_num);
                        $pdt_no.parent().parent().find("._price").text(str);
                        $pdt_no.parent().parent().find(".subpri").val(data.rows[0][0].price);
                    }else {
                        layer.alert("料号"+data.rows[0][0].part_no+"已经添加过了,请重新选择",{icon:2});
                    }
                    //$pdt_no.parent().parent().find("td").eq(10).text(data.rows[0][0].spp_code);
                }else {
                    layer.alert("未找到该料号!", {icon: 2});
                    $pdt_no.parent().parent().find("td").eq(3).text("");
                    $pdt_no.parent().parent().find("td").eq(4).text("");
                    $pdt_no.parent().parent().find("td").eq(5).text("");
                    $pdt_no.parent().parent().find("td").eq(6).text("");
                    $pdt_no.parent().parent().find("._price").text("");
                    // $pdt_no.parent().parent().find("td").eq(9).text("");
                    //$pdt_no.parent().parent().find("td").eq(10).text("");
                }
            },
            error: function (data) {
                layer.alert("请求错误!", {icon: 2});
            }
        })
    });
    //获取金额
    $(document).on("change","._num",function () {
        var numss=0;
        $_num = $(this);
//        var _bsinv=$_num.parents("tr").find("td").eq(7).text();
        var num=$(this).val();
//        if(parseInt(num)>parseInt(_bsinv))
//        {
//            layer.alert("请购数量必须小于库存量",{icon:2});
//            $("._num").css("border-color","#ffa8a8");
//        }else {
//            $("._num").css("border-color","#cccccc");
        var _price=$_num.parents("tr").find("._price").text();
//            alert(_price);
        var _value=num*_price;
        _value=Math.round(_value * 100) * 0.01;
        $_num.parents("tr").find(".totalspan").text(_value.toFixed(2));
        $_num.parents("tr").find("._total").val(_value.toFixed(2));
        $("#product_table").find("tr").each(function () {
            if(($(this).find("td").eq(9).text())!=""){
                _rows=parseFloat($(this).find("td").eq(9).text());
                numss=numss+_rows;
            }
        });
        $("._totalallprice").text(numss.toFixed(2));
        $("._totalallprice").val(numss.toFixed(2));
//        }
    });
    //领用人
    $(document).on("change","._users",function () {
        $staffcode=$("._users").val();
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
//                    alert($("._users111").val());
                }else {
                    layer.alert("没有查到此工号",{icon: 2});
                }
            }
        })
    });
    //领用电话
    $(document).on("change","._tel",function () {
        $('._tel').validatebox({required: true, validType: 'tel_mobile_c'});
    });
    //采购部门模糊搜索
    $(document).on("change",".pur_apart",function () {
        var _text=$(".pur_apart").val().trim();
        if(_text==""){
            $(".pur_apart").validatebox({required: true});
        }
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





