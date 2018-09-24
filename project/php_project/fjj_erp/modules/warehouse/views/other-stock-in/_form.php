<?php
/**
 * User: F1677929
 * Date: 2017/12/14
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
\app\assets\JeDateAsset::register($this);
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=['label'=>'其他入库单列表','url'=>'list'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?></h1>
    <?php ActiveForm::begin();?>
    <h2 class="head-second">其他入库单信息</h2>
    <div class="mb-10">
        <label style="width:150px;">入库单号：</label>
        <span style="width:200px;"><?=empty($editData)?"系统自动生成":$editData['invh_code']?></span>
        <label style="width:250px;"><span style="color:red;">*</span>单据类型：</label>
        <?php if(empty($editData)){?>
            <select style="width:200px;" name="InWhpdt[inout_flag]" class="easyui-validatebox" data-options="required:true">
                <option value="">--请选择--</option>
                <?php foreach($addData['billType'] as $key=>$val){?>
                    <option value="<?=$key?>"><?=$val?></option>
                <?php }?>
            </select>
        <?php }else{?>
            <span style="width:200px;"><?=$editData['business_value']?></span>
        <?php }?>
    </div>
    <div class="mb-10">
        <label style="width:150px;">关联单号：</label>
        <input type="text" style="width:200px;" name="InWhpdt[invh_aboutno]" class="easyui-validatebox" data-options="validType:'maxLength[20]'" maxlength="20" value="<?=$editData['invh_aboutno']?>">
        <label style="width:250px;"><span style="color:red;">*</span>入仓仓库：</label>
        <?php if(empty($editData)){?>
            <select id="wh_id" style="width:200px;" name="InWhpdt[wh_code]" class="easyui-validatebox" data-options="required:true">
                <option value="">--请选择--</option>
                <?php foreach($addData['warehouse'] as $key=>$val){?>
                    <option value="<?=$key?>"><?=$val?></option>
                <?php }?>
            </select>
        <?php }else{?>
            <span style="width:200px;"><?=$editData['wh_name']?></span>
        <?php }?>
    </div>
    <div class="mb-10">
        <label style="width:150px;">仓库代码：</label>
        <span id="wh_code" style="width:200px;"><?=$editData['wh_code']?></span>
        <label style="width:250px;">仓库属性：</label>
        <span id="wh_attr" style="width:200px;"><?=$editData['wh_attr']?></span>
    </div>
    <div class="mb-10">
        <label style="width:150px;"><span style="color:red;">*</span>送货人：</label>
        <input type="text" style="width:200px;" name="InWhpdt[invh_sendperson]" class="easyui-validatebox" data-options="required:true,validType:'maxLength[20]'" maxlength="20" value="<?=$editData['invh_sendperson']?>">
        <label style="width:250px;"><span style="color:red;">*</span>联系方式：</label>
        <input type="text" style="width:200px;" name="InWhpdt[invh_sendaddress]" class="easyui-validatebox" data-options="required:true,validType:'tel_mobile_c'" value="<?=$editData['invh_sendaddress']?>" placeholder="请输入手机或座机号码">
    </div>
    <div class="mb-10">
        <label style="width:150px;">预收货日期：</label>
        <input type="text" style="width:200px;" name="InWhpdt[recive_date]" value="<?=$editData['recive_date']?>" class="Wdate" onclick="WdatePicker({skin:'whyGreen',minDate:'%y-%M-%d'});" readonly="readonly">
        <label style="width:250px;"><span style="color:red;">*</span>收货人：</label>
        <input class="staff_id" type="hidden" name="InWhpdt[invh_reperson]" value="<?=empty($editData)?$addData['staffInfo']['staff_id']:$editData['invh_reperson']?>">
        <input type="text" style="width:200px;" class="easyui-validatebox" data-options="required:true,validType:'staffCode',delay:10000000" data-url="<?=Url::to(['/hr/staff/get-staff-info'])?>" onchange="$(this).validatebox('validate')" value="<?=empty($editData)?$addData['staffInfo']['staff_code'].'--'.$addData['staffInfo']['staff_name']:$editData['staff_info']?>">
    </div>
    <div class="mb-10">
        <label style="width:150px;"><span style="color:red;">*</span>联系方式：</label>
        <input type="text" style="width:200px;" name="InWhpdt[invh_repaddress]" class="easyui-validatebox" data-options="required:true,validType:'tel_mobile_c'" value="<?=$editData['invh_repaddress']?>" placeholder="请输入手机或座机号码">
    </div>
    <div class="mb-10">
        <label style="width:150px;vertical-align:top;">备注：</label>
        <textarea style="width:657px;height:60px;" name="InWhpdt[invh_remark]" class="easyui-validatebox" data-options="validType:'maxLength[200]'" maxlength="200"><?=$editData['invh_remark']?></textarea>
    </div>
    <h2 class="head-second">
        入库商品信息
        <span style="float:right;margin-right:15px;">
            <a id="list_btn" class="icon-reorder icon-large"></a>
            <a id="delete_btn" class="icon-remove icon-large"></a>
        </span>
    </h2>
    <div style="overflow:auto;">
        <table class="table" style="width:1450px;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:30px;"><input type="checkbox" style="vertical-align:middle;height:auto;"></th>
                <th style="width:200px;">料号</th>
                <th style="width:200px;">商品名称</th>
                <th style="width:100px;">品牌</th>
                <th style="width:200px;">规格型号</th>
                <th style="width:100px;">收货批次</th>
                <th style="width:100px;">送货数量</th>
                <th style="width:100px;">预实收数量</th>
                <th style="width:100px;">单位</th>
                <th style="width:100px;">包装方式</th>
                <th style="width:100px;">包装件数</th>
                <th style="width:80px;">操作</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div style="margin-left:30px;"><a id="add_tr">+添加商品</a></div>
    <div style="text-align:center;">
        <button class="button-blue-big" type="submit">保存</button>
        <button class="button-blue-big" type="submit" style="margin-left:40px;">提交</button>
        <button class="button-white-big" type="button" onclick="history.go(-1)">返回</button>
    </div>
    <?php ActiveForm::end();?>
</div>
<script>
    //添加商品
    var packType="<?php //提前处理包装方式
        foreach($addData['packType'] as $key=>$val){
            echo "<option value='{$key}'>{$val}</option>";
        }
        ?>";
    var index=0;
    function addTr(){
        var trStr="<tr>";
        trStr+="<td></td>";
        trStr+="<td><input type='checkbox' style='vertical-align:middle;height:auto;'></td>";
        trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:[\"tdSame\",\"pnoValidate\"],delay:1000000,validateOnBlur:true' name='arr["+index+"][InWhpdtDt][part_no]' data-url='<?=Url::to(['/warehouse/other-stock-in/get-pno'])?>'></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td></td>";
        trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='required:true,validType:\"maxLength[20]\"' name='arr["+index+"][InWhpdtDt][batch_no]'></td>";
        trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='required:true,validType:\"two_decimal\"' name='arr["+index+"][InWhpdtDt][in_quantity]'></td>";
        trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='required:true,validType:[\"two_decimal\",\"compareSizeValidate\"]' name='arr["+index+"][InWhpdtDt][real_quantity]'></td>";
        trStr+="<td></td>";
        trStr+="<td><select style='width:100%;' name='arr["+index+"][InWhpdtDt][pack_type]'>"+packType+"</select></td>";
        trStr+="<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='validType:\"two_decimal\"' name='arr["+index+"][InWhpdtDt][pack_num]'></td>";
        trStr+="<td><a class='icon-remove icon-large' title='删除'></a><a class='icon-repeat icon-large' title='重置' style='margin-left:15px;'></a></td>";
        trStr+="</tr>";
        $("tbody").append(trStr).find("tr").each(function(index){
            $(this).find("td:first").html(index+1);
        });
        $.parser.parse($("tbody tr:last"));
        index++;
    }

    //添加商品-其他页面回调
    function productSelectorCallback(rows){
        $.each(rows,function(i,n){
            addTr();
            var $trLast=$("tbody tr:last");
            $trLast.find("input:eq(1)").val(n.part_no);
            $trLast.find("td:eq(3)").text(n.pdt_name);
            $trLast.find("td:eq(4)").text(n.brand);
            $trLast.find("td:eq(5)").text(n.tp_spec);
            $trLast.find("td:eq(9)").text(n.unit);
        })
    }

    //document ready
    $(function(){
        //入仓仓库
        $("#wh_id").change(function(){
            var id=$(this).val();
            if(id==""){
                $("#wh_code").text("");
                $("#wh_attr").text("");
                return false;
            }
            $.ajax({
                url:"<?=Url::to(['get-warehouse-info'])?>",
                data:{"id":id},
                dataType:"json",
                success:function(data){
                    $("#wh_code").text(data.wh_code);
                    $("#wh_attr").text(data.wh_attr);
                }
            })
        });

        //ajax提交表单
        var btnFlag='';
        $("button[type='submit']").click(function(){
            btnFlag=$(this).text();
        });
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

        //选择料号
        $("#list_btn").click(function(){
            //排除已选中的商品
            var rows=$("tbody tr").find("input:eq(1)");
            var filters='';
            $.each(rows,function(i,n){
                filters+=n.value+',';
            });
            filters=filters.substr(0,filters.length-1);
            var url="<?=Url::to(['select-pno'])?>";
            var idField="part_no";
            $.fancybox({
                width:720,
                height:500,
                padding:[],
                autoSize:false,
                type:"iframe",
                href:"<?=\yii\helpers\Url::to(['/ptdt/product-list/product-selector'])?>?filters="+filters+"&url="+url+"&idField="+idField
            });
        });

        //复选框
        $(document).on("click","th input[type='checkbox']",function(){
            $(document).find("td input[type='checkbox']").prop("checked",$(this).prop("checked"));
        });
        $(document).on("click","td input[type='checkbox']",function(){
            var num=$(document).find("td input[type='checkbox']:not(:checked)").length;
            $(document).find("th input[type='checkbox']").prop("checked",!num);
        });

        //重置
        $(document).on("click",".icon-repeat",function(){
            $(this).parents("tr").find("input,select").val('');
            var tds=$(this).parents("tr").find("td:gt(2):lt(8)");
            $.each(tds,function(i,n){
                if($(n).children("input").length==0){
                    $(n).html('');
                }
            })
        });

        //删除
        $(document).on("click","table .icon-remove",function(){
            $(this).parents("tr").remove();
            $("tbody").find("tr").each(function(index){
                $(this).find("td:first").text(index+1);
            });
        });
        $(document).on("click","#delete_btn",function(){
            if($(document).find("td input[type='checkbox']:checked").length==0){
                layer.alert('请选择要删除的商品！',{icon:2});
                return false;
            }
            $(document).find("td input[type='checkbox']:checked").parents("tr").remove();
            $(document).find("th input[type='checkbox']").prop("checked",false);
            $("tbody").find("tr").each(function(index){
                $(this).find("td:first").text(index+1);
            });
        });

        //添加商品
        $("#add_tr").click(function(){
            addTr();
        });

        //验证
        $.extend($.fn.validatebox.defaults.rules,{
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
                        var currTr=$(this).parents("tr");
                        currTr.find("input:eq(1)").val(data.part_no);
                        currTr.find("td:eq(3)").text(data.pdt_name);
                        currTr.find("td:eq(4)").text(data.brand);
                        currTr.find("td:eq(5)").text(data.tp_spec);
                        currTr.find("td:eq(9)").text(data.unit);
                        return true;
                    }
                },
                message:'料号不存在'
            },
            compareSizeValidate:{
                validator:function(value){
                    var num=$(this).parents("tr").find("input:eq(3)").val();
                    if(num==""){
                        return false;
                    }
                    num=parseFloat(num);
                    return parseFloat(value) <= num;
                },
                message:"预实收数量<=送货数量"
            }
        });

        <?php if(Yii::$app->controller->action->id=="edit"){?>
        $.ajax({
            url:"<?=Url::to(['get-products'])?>",
            data:{"id":<?=$editData['invh_id']?>},
            dataType:"json",
            success:function(data){
                $.each(data.rows,function(i,n){
                    for(var a in n){
                        if(n[a]){
                            n[a]=n[a].decode();
                        }
                    }
                    addTr();
                    var $trLast=$("tbody tr:last");
                    $trLast.find("input:eq(1)").val(n.part_no);
                    $trLast.find("td:eq(3)").text(n.pdt_name);
                    $trLast.find("td:eq(4)").text(n.brand);
                    $trLast.find("td:eq(5)").text(n.tp_spec);
                    $trLast.find("input:eq(2)").val(n.batch_no);
                    $trLast.find("input:eq(3)").val(n.in_quantity);
                    $trLast.find("input:eq(4)").val(n.real_quantity);
                    $trLast.find("td:eq(9)").text(n.unit);
                    $trLast.find("select:eq(0) option[value="+n.pack_type_id+"]").prop("selected",true);
                    $trLast.find("input:eq(5)").val(n.pack_num);
                });
            }
        });
        <?php }?>
    })
</script>