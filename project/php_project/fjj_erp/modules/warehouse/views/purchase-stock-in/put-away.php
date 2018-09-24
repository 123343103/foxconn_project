<?php
/**
 * User: F1677929
 * Date: 2017/12/25
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
?>
<div class="head-first">上架</div>
<?php ActiveForm::begin()?>
<div class="mb-10">
    <label style="width:150px;">入仓仓库：</label>
    <span style="width:200px;"><?=$data["arr1"]["wh_name"]?></span>
    <label style="width:150px;">仓库代码：</label>
    <span id="wh_code" style="width:200px;"><?=$data['arr1']['wh_code']?></span>
</div>
<div class="mb-10">
    <label style="width:150px;">仓库属性：</label>
    <span style="width:200px;"><?=$data['arr1']['wh_attr']?></span>
    <label style="width:150px;">操作员：</label>
    <span style="width:200px;"><?=$data['arr1']['staff_name']?></span>
</div>
<div class="mb-10">
    <label style="width:150px;">操作时间：</label>
    <span style="width:200px;"><?=date("Y-m-d")?></span>
</div>
<div style="margin:0 15px 10px;">
    <div>商品信息</div>
    <div style="overflow:auto;">
        <table class="table" style="width:1070px;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:200px;">品名</th>
                <th style="width:100px;">送货数量</th>
                <th style="width:80px;">单位</th>
                <th style="width:200px;"><span style="color:red;">*</span>储位</th>
                <th style="width:200px;"><span style="color:red;">*</span>存放数量</th>
                <th style="width:100px;"><span style="color:red;">*</span>上架日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data['arr2'] as $key=>$val){?>
                <tr>
                    <td><?=$key+1?></td>
                    <td>
                        <?=$val['part_no']?>
                        <input type="hidden" name="arr[<?=$key?>][InWhpdtDt][part_no]" value="<?=$val['part_no']?>">
                        <input type="hidden" name="arr[<?=$key?>][InWhpdtDt][invl_id]" value="<?=$val['invl_id']?>">
                    </td>
                    <td><?=$val['pdt_name']?></td>
                    <td><?=$val['real_quantity']?></td>
                    <td><?=$val['unit']?></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:'maxLength[50]'" readonly="readonly" name="arr[<?=$key?>][InWhpdtDt][st_codes]" value="<?=$val['st_codes']?>"></td>
                    <td><input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:['maxLength[50]','storeNumValidate'],delay:1000000,validateOnBlur:true" name="arr[<?=$key?>][InWhpdtDt][store_num]" value="<?=$val['store_num']?>"></td>
                    <td><input type="text" style="width:100%;text-indent:5px;" class="easyui-validatebox Wdate" data-options="required:true" readonly="readonly" onclick="WdatePicker({skin:'whyGreen',minDate:'<?=$val['inout_time']?>',maxDate:'%y-%M-%d',onpicked:function(){$(this).validatebox('validate')},oncleared:function(){$(this).validatebox('validate')}})" name="arr[<?=$key?>][InWhpdtDt][up_date]" value="<?=date('Y-m-d')?>"></td>
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

        //选择储位
        $("tbody tr").on("click","input:eq(2)",function(){
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
        $("tbody tr").on("click","input:eq(3)",function(){
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
                    var location=$(this).parents("tr").find("input:eq(2)").val();
                    var num=location.split(",").length;
                    var reg=new RegExp("^([1-9]([0-9]{1,11})?([.][0-9]{1,2})?,){"+num+"}$");
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
</script>
