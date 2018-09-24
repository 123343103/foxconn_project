<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/12/28
 * Time: 上午 11:20
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<style>
    .red-border {
        border: 1px solid #ffa8a8;
    }
</style>
<div class="head-first">重新上架</div>
<?php ActiveForm::begin()?>
<div class="mb-10" style="font-size: 13px;">
    <label style="width:100px;">仓库名称：</label>
    <span style="width:170px;"><?=$data["arr1"]["wh_name"]?></span>
    <label style="width:80px;">仓库代码：</label>
    <span id="wh_code" style="width:150px;"><?=$data['arr1']['wh_code']?></span>
    <input type="hidden" value="<?=$data['arr1']['wh_id']?>" name="wh_id">
    <input type="hidden" value="<?=$data['arr1']['wh_code']?>" name="wh_code">
    <input type="hidden" value="<?=$data['arr1']['wh_name']?>" name="wh_name">
    <input type="hidden" value="<?=$data['arr1']['o_whcode']?>" name="o_whcode">
</div>
<div class="mb-10" style="font-size: 13px;">
    <label style="width:100px;">仓库属性：</label>
    <span style="width:170px;"><?=$data['arr1']['wh_attr']?></span>
    <label style="width:80px;">操作员：</label>
    <span style="width:150px;"><?=$data['arr1']['staff_name']?></span>
    <label style="width:80px;">操作日期：</label>
    <span style="width:100px;"><?=date("Y-m-d")?></span>
</div>
<!--<div class="mb-10">-->
<!--    <label style="width:150px;">操作日期：</label>-->
<!--    <span style="width:200px;">--><?//=date("Y-m-d")?><!--</span>-->
<!--</div>-->
<div style="margin:30px 15px 10px 10px; font-size: 13px;">
    <div style="margin-bottom: 10px;margin-left: 25px;">商品信息</div>
    <div style="overflow:auto;margin-left: 20px;">
        <table class="table" style="width:1070px;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:200px;">品名</th>
                <th style="width:100px;">送货数量</th>
                <th style="width:80px;">单位</th>
                <th style="width:200px;">批次</th>
                <th style="width:200px;"><span style="color:red;" title="*">*</span>储位</th>
                <th style="width:200px;"><span style="color:red;" title="*">*</span>存放数量</th>
                <th style="width:120px;"><span style="color:red;" title="*">*</span>上架日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data['arr2'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td>
                        <?=$val['part_no']?>
                        <input type="hidden" name="arr[<?=$key?>][part_no]" value="<?=$val['part_no']?>">
                        <input type="hidden" name="arr[<?=$key?>][o_whdtid]" value="<?=$val['o_whdtid']?>">
                        <input type="hidden" name="arr[<?=$key?>][o_whnum]" value="<?=$val['o_whnum']?>">
                        <input type="hidden" name="arr[<?=$key?>][st_id]" value="<?=$val['st_id']?>">
                        <input type="hidden" name="arr[<?=$key?>][pdt_name]" value="<?=$val['pdt_name']?>">
                        <input type="hidden" name="arr[<?=$key?>][unit_name]" value="<?=$val['unit_name']?>">
                    </td>
                    <td><?=$val['pdt_name']?></td>
                    <td><?=$val['o_whnum']?></td>
                    <td><?=$val['unit_name']?></td>
                    <td><input type="hidden" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="validType:'maxLength[50]'" readonly="readonly" name="arr[<?=$key?>][L_invt_bach]" value="<?=$val['L_invt_bach']?>"><?=$val['L_invt_bach']?></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox st_id" data-options="validType:'maxLength[50]'" readonly="readonly" name="arr[<?=$key?>][st_codes]" value="<?=$val['st_id']?>"></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox store_num" data-options="validType:['maxLength[50]','storeNumValidate'],delay:1000000,validateOnBlur:true" name="arr[<?=$key?>][store_num]" value="<?=$val['pck_nums']?>"></td>
                    <td><input type="text" style="width:100%;text-indent:5px;" class="easyui-validatebox Wdate" data-options="required:true" readonly="readonly" onclick="WdatePicker({skin:'whyGreen',minDate:'<?=substr($data['arr1']['rcpg_date'],0,10)?>',maxDate:'%y-%M-%d',onpicked:function(){$(this).validatebox('validate')},oncleared:function(){$(this).validatebox('validate')}})" name="arr[<?=$key?>][inout_time]" value="<?=date('Y-m-d')?>"></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </div>
</div>
<div style="text-align:center;">
    <button type="button" id="save" class="button-blue">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        $("#save").click(function () {
            var flag = setstyles("st_id");//储位
            var flag1=setstyles("store_num");//存放数量
            if (flag > 0 || flag1 > 0) {
                return false;
            }
            else {
                $("form").submit();
                //$(this).attr("disabled",'disabled');
                $("#add-form").attr('action', '<?=Url::to(['put-away'])?>');
            }
        });
        ajaxSubmitForm("form","",
            function(data){
                if(data.flag==1){
                    parent.$(".fancybox-overlay").hide();
                    parent.$(".fancybox-wrap").hide();
                    parent.layer.alert(data.msg,{
                        icon:1,
                        end:function(){
                            parent.$("#data").datagrid('reload');
                            parent.$("#generating-order").hide();
                            parent.$("#cancel").hide();
                            parent.$("#again-shelves").hide();
                            parent.$("#view-order").hide();
                            parent.$("#cost-apply").hide();
                            parent.$.fancybox.close();
                        }
                    });
                }
                if(data.flag==0){
                    parent.layer.alert(data.msg,{icon:2});
                    $("button[type='button']").prop("disabled",false);
                }
            }


        );

        //选择储位
        $("tbody tr").on("click",".st_id",function(){
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
                height:500,
                margin:0
            });
            clickFlag=this;
        });

        //存放数量
        $("tbody tr").on("click",".store_num",function(){
            var location=$(this).parents("tr").find("input:eq(2)").val();
            if(location==""){
                layer.alert("请选择储位",{icon:2});
                return false;
            }
        });

        //验证
        $.extend($.fn.validatebox.defaults.rules,{
            storeNumValidate:{
                validator:function(value){
                    var location=$(this).parents("tr").find(".st_id").val();
                    var num=location.split(",").length;
                    var reg=new RegExp("^([0-9]([0-9]{1,11})?([.][0-9]{1,2})?,){"+num+"}$");
                    if(reg.test($.trim(value)+",")){
                        var arr=$.trim(value).split(",");
                        var result=0;
                        $.each(arr,function(i,n){
                            result+=parseFloat(n);
                        });
                        var total=$(this).parents("tr").find("td:eq(3)").text();
                        total=parseFloat(total);
                        if(result==total){
                            return true;
                        }else{
                            $.fn.validatebox.defaults.rules.storeNumValidate.message="存放数量总和应等于送货数量";
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

    function setstyles(classname) {
        flag = 0;
        var _classname = $("." + classname + "");
        _classname.each(function () {
            if ($(this).val() == null || $(this).val() == "" || $(this).val() == 0) {
                //交货时间
                    $(this).attr('class', "red-border " + classname + " easyui-validatebox");
                    $(this).focus();
                    flag += 1;
            }
        });
        return flag;
    }
</script>
