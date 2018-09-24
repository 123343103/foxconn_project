<?php
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);
?>
<h3 class="head-first">拣货数量维护</h3>
<style>
    .width-40 {
        width: 40px;
    }

    .width-140 {
        width: 140px;
    }

    .width-80 {
        width: 80px;
    }

    .width-250 {
        width: 250px;
    }

    .width-150 {
        width: 150px
    }

    .width-200 {
        width: 200px;
    }
    .mt-20{
        margin-top: 20px;
    }
</style>
<div>
    <?php ActiveForm::begin([
        "id" => "maintenance-form"
    ]) ?>
    <div class="content" style="margin-top: 5px;margin-left: 15px;padding-top: 5px;">
        <input type="hidden" value="<?= $whinfo["wh_code"] ?>" id="wh_code"/>
        <input type="hidden" value="<?= $id ?>" id="pck_pkid" name="id"/>
        <div class="mb-30">
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="13%" class="no-border qlabel-align vertical-center">仓库名称：</td>
                    <td width="35%" class="no-border vertical-center"><?= $whinfo["wh_name"] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="13%" class="no-border qlabel-align vertical-center">仓库代码：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= $whinfo["wh_code"] ?></td>
                </tr>
            </table>
            <div class="space-20"></div>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="13%" class="no-border qlabel-align vertical-center">仓库属性：</td>
                    <td width="35%" class="no-border vertical-center"><?= $whinfo["wh_attr"] ?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="13%" class="no-border qlabel-align vertical-center">操作员：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?= yii::$app->user->identity->staff->staff_name ?></td>
                </tr>
            </table>
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="13%" class="no-border qlabel-align vertical-center">操作日期：</td>
                    <td width="87%" class="no-border vertical-center"><?php echo $showtime = date("Y/m/d"); ?></td>
                </tr>
            </table>
        </div>
        <div  class="mt-20">
            商品信息
        </div>
        <div class="mb-10" style="overflow: auto;">
            <div style="width:100%;overflow: auto;">
                <table class="table" width="100%">
                    <thead>
                    <tr style="height: 50px">
                        <th><p class="width-40">序号</p></th>
                        <th width="10%"><p class="width-140">料号</p></th>
                        <th width="10%"><p class="width-80">品名</p></th>
                        <th width="10%"><p class="width-80">需求出货数量</p></th>
                        <th width="10%"><p class="width-80">现有库存量</p></th>
                        <th width="5%"><p class="width-80">交易单位</p></th>
                        <th width="10%"><p class="width-150">批次</p></th>
                        <th width="10%"><p class="width-150"><span class="red">*</span>储位</p></th>
                        <th width="10%"><p class="width-200"><span class="red">*</span>拣货数量</p></th>
                        <th width="10%"><p class="width-150"><span class="red">*</span>拣货日期</p></th>
                        <th width="10%"><p class="width-150">备注</p></th>
                        <th width="10%"><p class="width-150">操作</p></th>
                    </tr>
                    </thead>
                    <tbody id="product_table" style="overflow: auto;width:1400px">
                    <?php foreach ($pickinfo as $key => $val) { ?>
                        <tr style="height: 50px;" data-id="<?= $val["part_no"] ?>"  class="<?= ($key + 1) ?>">
                            <td width="5%">
                                <p class="width-40"><?= ($key + 1) ?></p></td>
                            <td width="10%">
                                <p class="width-140 partno"><?= $val["part_no"] ?></p>
                            </td>
                            <td width="10%"  style="overflow: hidden"><p class="width-80"><?= $val["pdt_name"] ?></p></td>
                            <td class="partname" width="10%">
                                <p class="width-80"><?= ($val["nums"]) ?></p></td>
                            <td class="rackcode" width="5%">
                                <p class="width-80"><?= $val["invt_num"] ?></p></td>
                            <td class="lnum" width="10%">
                                <p class="width-80"><?= $val["unit"] ?></p></td>
                            <!--                            批次-->
                            <td class="lbach" width="10%"><input type="hidden" value="<?= $val['pck_dt_pkid'] ?>" class="pck_dt_pkid"
                                                                 name="BsPckDt[<?= $key ?>][pck_dt_pkid]"/>
                                <input type="hidden" value="" class="rack_code"
                                                                 name="BsPckDt[<?= $key ?>][rack_code]"/>
                                <input type="hidden" value="" class="part_name"
                                                                 name="BsPckDt[<?= $key ?>][part_name]"/>
                                <select class="easyui-validatebox width-80  bach"
                                                                  data-options="required:'true'"
                                                                  name="BsPckDt[<?= $key ?>][L_invt_bach]"></select>
                            </td>
                            <!--                            储位-->
                            <td class="stid" width="10%"><input type="hidden" value="" class="invt_num"
                                                                name="BsPckDt[<?= $key ?>][L_invt_num]"/>
                                <input type="text"  id="st_id" data-options="required:'true'" name="BsPckDt[<?= $key ?>][st_id]"
                                                                class="easyui-validatebox st"
                                                                data-id="<?= $val["part_no"]?>" onclick="checkst(this)" readonly="readonly"/>
                                <input type="hidden" value="" class="st_id">
                            </td>

                            <!--                            数量-->
                            <td width="10%"><input type="text" name="BsPckDt[<?= $key ?>][pck_nums]" id="pck_nums"
                                                   data-options="required:'true'" class="easyui-validatebox width-200" onblur="countpcknums(this)"
                                                   onkeyup="this.value=this.value.replace(/[^\r\n0-9\,]/g,'');" /></td>
                            <!--                            时间-->
                            <td width="10%"><input class=" no-border easyui-validatebox deldate text-center Wdate"
                                                   data-options="required:'true'" type="text" id="Deliverytime"
                                                   style="width: 150px;"
                                                   onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy/MM/dd',minDate: '%y/%M/#{%d}' })"
                                                   onfocus="this.blur()" value="<?= date('Y/m/d') ?>"
                                                   readonly="readonly" name="BsPckDt[<?= $key ?>][pack_date]"></td>
                            <!--                            备注-->
                            <td width="10%"><input type="text" name="BsPckDt[<?= $key ?>][marks]" id="marks"
                                                   class="easyui-validatebox" maxlength="2000"/>
                                <input type="hidden" name="BsPckDt[<?= $key ?>][shpn_pkid]"  value="<?=$val["shpn_pkid"]?>"
                                       class="easyui-validatebox"/></td>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][nums]"  value="<?=$val["nums"]?>"
                                   class="easyui-validatebox"/>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][sol_id]"  value="<?=$val["sol_id"]?>"
                                   class="easyui-validatebox"/>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][part_no]"  value="<?=$val["part_no"]?>"
                                   class="easyui-validatebox"/>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][req_num]"  value="<?=$val["req_num"]?>"
                                   class="easyui-validatebox"/>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][countnum]" id="countnum"
                                   class="easyui-validatebox"/>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][pdt_name]" value="<?= $val["pdt_name"] ?>"
                                   class="easyui-validatebox"/>
                            <input type="hidden" name="BsPckDt[<?= $key ?>][unit]" value="<?= $val["unit"] ?>"
                                   class="easyui-validatebox"/>
                            </td>
                            <!--                            操作-->
                            <td width="10%"><a class="icon-plus ml-20" onclick="add_contacts(this)"></a>&nbsp;&nbsp;
                                <a class="icon-minus ml-20" style="display: none" onclick="del_contacts(this)"></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-20 mb-20 text-center">
            <button id="submit" class="button-blue-big" type="submit">确定</button>
            <button id="cancel" class="button-white-big" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>
<script>
    var _this = "";
    $(function () {
        ajaxSubmitForm($("#maintenance-form"));
        var tb = document.getElementById("product_table");
        var wh_code = $("#wh_code").val();
        var url = "<?=\yii\helpers\Url::to(['linvt-bach'])?>";
        for (var i = 0; i < tb.rows.length; i++) {
            var partno = $(tb).children("tr").eq(i).children("td").eq(1).children("p").text();
            var tt = $(tb).children("tr").eq(i).children("td").eq(6).children(".bach");
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"wh_code": wh_code, "partno": partno},
                url: url,
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        tt.append("<option value='" + data[i].batch_no + "'>" + data[i].batch_no + "</option>");
                    }
                }
            })

        }
        $.each($("tbody tr"),function (i,n) {
            $(this).find("#countnum").val($(this).find("#pck_nums").val());
        })
    })
    function setstid(st_code,st_id, invt_num, part_name, rack_code) {
        _this.val(st_code);
//        _this.parent(".stid").siblings('.lbach').children('.p_bach').html(l_bs_invt_list);
//        _this.parent(".stid").siblings('.lbach').children('.bach').val(l_bs_invt_list);
//        _this.parent(".stid").siblings('.lnum').children('.invt_num').val(L_invt_num);
        _this.parent(".stid").children('.invt_num').val(invt_num);
        _this.parent(".stid").children('.st_id').val(st_id);
        _this.parent(".stid").siblings('.lbach').children('.rack_code').val(rack_code);
        _this.parent(".stid").siblings('.lbach').children('.part_name').val(part_name);
    }

    function checkst(obj) {
        _this = $(obj);
        $(obj).val("");
        var tr = $(obj).parent("td").parent("tr");
        var classname=$(tr).attr("class");//获取当前tr的类名
        var alltr=document.getElementsByClassName(classname);//获取所有类名跟当前类名相同的tr

//        var st_id=stidAry.toString();
        var wh_code = $("#wh_code").val();
        var part_no = $(obj).data("id");
        var lbach = $(obj).parent(".stid").siblings(".lbach").children(".bach").val();
        //查找在批次相同的情况下已经选择过的储位
        var stidAry = new Array();
        for (var n=0;n<alltr.length;n++){
            var onelbach=$(alltr).eq(n).find(".bach").val();
            var stcode=$(alltr).eq(n).find("#st_id").val();
            if(onelbach==lbach){
                if ($.inArray(stcode, stidAry) === -1) {
                    stidAry.push(stcode);
                }
            }
        }
        //encodeURI()为了兼容IE自动将文字变为乱码时，将文字编码
        $.fancybox({
            type: "iframe",
            padding: 0,
            width: 700,
            height: 600,
            href: "<?=\yii\helpers\Url::to(['select-stinfo'])?>?wh_code=" + wh_code + "&part_no="
            + part_no + "&lbach=" + encodeURI(lbach)+"&st_code="+stidAry
        });
    };
    //删除一行
    function del_contacts(obj) {
        var tr = $(obj).parent("td").parent("tr");
        var classname=$(tr).attr("class");//获取当前tr的类名
        var alltr=document.getElementsByClassName(classname);//获取所有类名跟当前类名相同的tr
        var a=alltr.length;//总长度
        var b=$(obj).parent('td').parent('tr').index();//要删除的div的索引
        if(b==1&&a==2){//删除的为第二行并且只有两行
            $(obj).parent('td').parent('tr').prev().children('td').last().children("a:eq(0)").css("display", "");
            $(obj).parent('td').parent('tr').prev().children('td').last().children("a:eq(1)").css("display", "none");
            for(var index = 5; index >= 0; index--){
                alltr[0].cells[index].rowSpan = alltr[0].cells[index].rowSpan - 1;
            }
            $(obj).parent('td').parent('tr').remove();
        }else {
            if(b+1==a&&a>2){//删除的为最后一行，并且总行数>2
                $(obj).parent('td').parent('tr').prev().children('td').last().children("a:eq(0)").css("display", "");
                for(var index = 5; index >= 0; index--){
                    alltr[0].cells[index].rowSpan = alltr[0].cells[index].rowSpan - 1;
                }
                $(obj).parent('td').parent('tr').remove();
            }else{
                for(var index = 5; index >= 0; index--){
                    alltr[0].cells[index].rowSpan = alltr[0].cells[index].rowSpan - 1;
                }
                $(obj).parent('td').parent('tr').remove();
            }
        }
    }
    //添加一行
    function add_contacts(obj) {
        $(obj).siblings("a").css("display","");
        var tr = $(obj).parent("td").parent("tr");
        var classname=$(tr).attr("class");//获取当前tr的类名
        var alltr=document.getElementsByClassName(classname);//获取所有类名跟当前类名相同的tr
        var onetr=alltr[0];//获取第一个tr;
        var ctr = $(onetr).clone();
        $.parser.parse(ctr);
        $(ctr).find("#st_id").val("");
        $(ctr).find("#pck_nums").val("");
        $(ctr).find("#marks").val("");
        tr.after(ctr);
        for (var i=0;i<alltr.length;i++){
            if(i==0){
                $(alltr).eq(i).children("td").last().children("a").first().css("display","none");
                $(alltr).eq(i).children("td").last().children("a").last().css("display","none");
            }
            else if(i!=alltr.length-1){
                $(alltr).eq(i).children("td").last().children("a").first().css("display","none");
                $(alltr).eq(i).children("td").last().children("a").last().css("display","");
            }else {
                $(alltr).eq(i).children("td").last().children("a").first().css("display","");
                $(alltr).eq(i).children("td").last().children("a").last().css("display","");
            }
        }
        //合并单元格
        for (var index = 5; index >= 0; index--) {
            autoRowSpan("product_table", index,alltr,onetr);
        }
        $.each($("tbody tr"),function(m,n){
            $(this).find("select").attr("name","BsPckDt["+m+"][L_invt_bach]");
            $(this).find("input:eq(0)").attr("name","BsPckDt["+m+"][pck_dt_pkid]");
            $(this).find("input:eq(1)").attr("name","BsPckDt["+m+"][rack_code]");
            $(this).find("input:eq(2)").attr("name","BsPckDt["+m+"][part_name]");
            $(this).find("input:eq(3)").attr("name","BsPckDt["+m+"][L_invt_num]");
            $(this).find("input:eq(5)").attr("name","BsPckDt["+m+"][st_id]");
            $(this).find("input:eq(6)").attr("name","BsPckDt["+m+"][pck_nums]");
            $(this).find("input:eq(7)").attr("name","BsPckDt["+m+"][pack_date]");
            $(this).find("input:eq(8)").attr("name","BsPckDt["+m+"][marks]");
            $(this).find("input:eq(9)").attr("name","BsPckDt["+m+"][shpn_pkid]");
            $(this).find("input:eq(10)").attr("name","BsPckDt["+m+"][nums]");
            $(this).find("input:eq(11)").attr("name","BsPckDt["+m+"][sol_id]");
            $(this).find("input:eq(12)").attr("name","BsPckDt["+m+"][part_no]");
            $(this).find("input:eq(13)").attr("name","BsPckDt["+m+"][req_num]");
            $(this).find("input:eq(14)").attr("name","BsPckDt["+m+"][countnum]");
            $(this).find("input:eq(15)").attr("name","BsPckDt["+m+"][pdt_name]");
            $(this).find("input:eq(16)").attr("name","BsPckDt["+m+"][unit]");
        })
    }
    function autoRowSpan(product_table, col,alltr,onetr) {
        var partno = "";
        var tb = document.getElementById(product_table);
        for (var i=1;i<alltr.length;i++){
            partno=$(onetr).data("id");//获取第一个tr的partno
            var tdrows=alltr[i].cells.length;
            if(partno != null){
                if (tdrows>6) {
                    alltr[i].deleteCell(col);
                    alltr[0].cells[col].rowSpan = alltr[0].cells[col].rowSpan + 1;
                }
            }
        }
//        for (var i = row; i < tb.rows.length; i++) {
//            partno = $(tb).children("tr").eq(i).data("id");
//            var tdrows=$(tb).children("tr").eq(i).children("td").length;
//            if (col == 0) {
//                if (partno != null &&tdrows>7 ) {
//                    if (partno == lastpartno) {
//                        tb.rows[i].deleteCell(col);
//                        tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
//                        pos++;
//                    }
//                    else {
//                        lastpartno = partno;
//                        pos = 1;
//                    }
//                }
//            }
//            else {
//                if (partno != null&&tdrows>7) {
//                    if (partno == lastpartno) {
//                        tb.rows[i].deleteCell(col);
//                        tb.rows[i - pos].cells[col].rowSpan = tb.rows[i - pos].cells[col].rowSpan + 1;
//                        pos++;
//                    } else {
//                        lastpartno = partno;
//                        pos = 1;
//                    }
//                }
//            }
//
//        }

    }
    function countpcknums(obj) {
        var tr = $(obj).parent("td").parent("tr");
        var classname=$(tr).attr("class");//获取当前tr的类名
        var alltr=document.getElementsByClassName(classname);//获取所有类名跟当前类名相同的tr
        var countnum=0;
        var onepicknum=0;
        var strs= new Array(); //定义一数组
        for(var n=0;n<alltr.length;n++){
            var onenum=$(alltr).eq(n).find("#pck_nums").val();
            if(onenum!=null){
                strs=onenum.split(',');
                if(strs.length>1){
                    for (var j=0 ;j<strs.length;j++){
                        onepicknum+=parseInt(strs[j]);
                    }
                    countnum+=onepicknum;
                }else {
                    countnum+=parseInt(onenum);
                }
            }
        }
        $(alltr).find("#countnum").val(countnum);
    }
</script>