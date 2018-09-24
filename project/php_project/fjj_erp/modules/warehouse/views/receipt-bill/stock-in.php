<?php
/**
 * User: F1677929
 * Date: 2017/12/25
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<div class="head-first">入库</div>
<?php ActiveForm::begin()?>
<div class="mb-10">
    <?php if($data["arr1"]["rcpg_type"]=="采购"){?>
        <label style="width:150px;"><span style="color:red;">*</span>入仓仓库：</label>
        <select id="wh_name" style="width:200px;" class="easyui-validatebox" data-options="required:true" name="RcpGoods[in_whcode]">
            <option value="">--请选择--</option>
            <?php foreach($data["arr3"] as $key=>$val){?>
                <option value="<?=$key?>"><?=$val?></option>
            <?php }?>
        </select>
    <?php }?>
    <?php if($data["arr1"]["rcpg_type"]=="调拨" || $data["arr1"]["rcpg_type"]=="移仓"){?>
        <label style="width:150px;">入仓仓库：</label>
        <input type="hidden" name="RcpGoods[in_whcode]" value="<?=$data["arr1"]["wh_name"]?>">
        <span style="width:200px;"><?=$data["arr1"]["wh_name"]?></span>
    <?php }?>
    <label style="width:150px;">仓库代码：</label>
    <span id="wh_code" style="width:200px;"><?=$data['arr1']['wh_code']?></span>
</div>
<div class="mb-10">
    <label style="width:150px;">仓库属性：</label>
    <span id="wh_attr" style="width:200px;"><?=$data['arr1']['wh_attr']?></span>
    <label style="width:150px;">单据类型：</label>
    <span style="width:200px;"><?=$data['arr1']['rcpg_type']?></span>
</div>
<div class="mb-10">
    <label style="width:150px;">操作员：</label>
    <span style="width:200px;"><?=$data['arr1']['staff_name']?></span>
    <label style="width:150px;">操作时间：</label>
    <span style="width:200px;"><?=date("Y-m-d")?></span>
</div>
<div style="margin:0 15px 10px;">
    <div>商品信息</div>
    <div style="overflow:auto;">
        <table class="table" style="width:1270px;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:200px;">品名</th>
                <th style="width:100px;">送货数量</th>
                <th style="width:80px;">单位</th>
                <th style="width:100px;"><span style="color:red;">*</span>入库数量</th>
                <th style="width:100px;"><span style="color:red;">*</span>批次</th>
                <th style="width:200px;">储位</th>
                <th style="width:200px;">存放数量</th>
                <th style="width:100px;"><span style="color:red;">*</span>入库日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data['arr2'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td>
                        <?=$val['part_no']?>
                        <input type="hidden" name="arr[<?=$key?>][InWhpdtDt][part_no]" value="<?=$val['part_no']?>">
                        <input type="hidden" name="arr[<?=$key?>][InWhpdtDt][detail_id]" value="<?=$val['detail_id']?>">
                    </td>
                    <td><?=$val['pdt_name']?></td>
                    <td><?=$val['rcpg_num']?></td>
                    <td><?=$val['unit']?></td>
                    <?php if($data['arr1']['rcpg_type']=="采购"){?>
                        <td> <input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:['two_decimal']" name="arr[<?=$key?>][InWhpdtDt][real_quantity]" value="<?=$val['rcpg_num']?>"></td>
                        <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:'maxLength[10]'" name="arr[<?=$key?>][InWhpdtDt][batch_no]"></td>
                    <?php }?>
                    <?php if($data['arr1']['rcpg_type']=="调拨" || $data['arr1']['rcpg_type']=="移仓"){?>
                        <td><input type="hidden" name="arr[<?=$key?>][InWhpdtDt][real_quantity]" value="<?=$val['rcpg_num']?>"><?=$val['rcpg_num']?></td>
                        <td><input type="hidden" name="arr[<?=$key?>][InWhpdtDt][batch_no]" value="<?=$val['ord_id']?>"><?=$val['ord_id']?></td>
                    <?php }?>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="validType:'maxLength[50]'" readonly="readonly" name="arr[<?=$key?>][InWhpdtDt][st_codes]"></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="validType:['maxLength[50]','storeNumValidate'],delay:1000000,validateOnBlur:true" name="arr[<?=$key?>][InWhpdtDt][store_num]"></td>
                    <td><input type="text" style="width:100%;text-indent:5px;" class="easyui-validatebox Wdate" data-options="required:true" readonly="readonly" onclick="WdatePicker({skin:'whyGreen',minDate:'<?=substr($data['arr1']['rcpg_date'],0,10)?>',maxDate:'%y-%M-%d',onpicked:function(){$(this).validatebox('validate')},oncleared:function(){$(this).validatebox('validate')}})" name="arr[<?=$key?>][InWhpdtDt][inout_time]" value="<?=date('Y-m-d')?>"></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div style="text-align:center;">
    <button type="submit" class="button-blue">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        ajaxSubmitForm("form","",
            function(data){
                if(data.flag==1){
                    parent.$(".fancybox-overlay").hide();
                    parent.$(".fancybox-wrap").hide();
                    parent.layer.alert(data.msg,{
                        icon:1,
                        end:function(){
                            parent.$("#table1").datagrid('reload');
                            parent.$.fancybox.close();
                        }
                    });
                }
                if(data.flag==0){
                    parent.layer.alert(data.msg,{icon:2});
                    $("button[type='submit']").prop("disabled",false);
                }
            }
        );

        //选择仓库
        $("#wh_name").change(function(){
            $("tbody tr").find("input:eq(4)").val("");//仓库改变时，清空储位
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

        //选择储位
        $("tbody tr").on("click","input:eq(4)",function(){
            var wh_code=$("#wh_code").text();
            if(wh_code==""){
                layer.alert("请选择仓库",{icon:2});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['/warehouse/receipt-bill/select-location'])?>?code="+wh_code,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:600,
                margin:0
            });
            clickFlag=this;
        });

        //存放数量
        $("tbody tr").on("click","input:eq(5)",function(){
            var num=$(this).parents("tr").find("input:eq(2)").val();
            if(num==""){
                layer.alert("请输入入库数量",{icon:2});
                return false;
            }
            var location=$(this).parents("tr").find("input:eq(4)").val();
            if(location==""){
                layer.alert("请选择储位",{icon:2});
                return false;
            }
        });

        //验证
        $.extend($.fn.validatebox.defaults.rules,{
            storeNumValidate:{
                validator:function(value){
                    var location=$(this).parents("tr").find("input:eq(4)").val();
                    var num=location.split(",").length;
                    var reg=new RegExp("^([1-9]([0-9]{1,11})?([.][0-9]{1,2})?,){"+num+"}$");
                    if(reg.test($.trim(value)+",")){
                        var arr=$.trim(value).split(",");
                        var result=0;
                        $.each(arr,function(i,n){
                            result+=parseFloat(n);
                        });
                        var total=$(this).parents("tr").find("input:eq(2)").val();
                        total=parseFloat(total);
                        if(result==total){
                            return true;
                        }else{
                            $.fn.validatebox.defaults.rules.storeNumValidate.message="存放数量总和应等于入库数量";
                        }
                    }else{
                        $.fn.validatebox.defaults.rules.storeNumValidate.message="存放数量应与储位一一对应";
                    }
                }
            }
        });
    });

    var clickFlag="";//储存点击的那个储位
    //储位回调
    function selectLocationCallback(rows){
        var codeStr="";
        $.each(rows,function(i,n){
            codeStr+=n.st_code+",";
        });
        codeStr=codeStr.substr(0,codeStr.length-1);
        $(clickFlag).val(codeStr);
    }
</script>
