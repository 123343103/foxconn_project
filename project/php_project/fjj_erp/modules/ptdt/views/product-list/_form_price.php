<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/6
 * Time: 下午 03:15
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h2 class="head-first"> 修改价格</h2>
<div class="content">
    <?php $form=ActiveForm::begin(["id"=>"price-form"]);?>
    <div id="price-data" style="width:800px;">
        <table class="table">
            <thead>
            <th width="200">最小值</th>
            <th width="200">最大值</th>
            <th width="200">价格</th>
            <th width="200">币别</th>
            <th width="200">操作</th>
            </thead>
            <tbody>
            <?php foreach ($data["price"] as $k=>$price){ ?>
                <tr>
                    <td>
                        <?php if($k==0){ ?>
                            <input type="text" class="easyui-validatebox minqty" data-options="required:true,validType:['decimal[19,2]','minqty']"  name="BsPrice[minqty][]" value="<?=$price['minqty']?>" readonly/>
                        <?php }else{ ?>
                            <input type="text" class="easyui-validatebox minqty"  name="BsPrice[minqty][]" value="<?=$price['minqty']?>" readonly />
                        <?php } ?>
                    </td>
                    <td>
                        <input type="text" class="easyui-validatebox maxqty" data-options="required:true,validType:['decimal[19,2]','maxqty']" name="BsPrice[maxqty][]" value="<?=$price["maxqty"]?>" />
                    </td>
                    <td><input type="text" class="easyui-validatebox" data-options="required:true,validType:'price'" name="BsPrice[price][]" value="<?=$price["price"]?>" maxlength="20" /></td>
                    <td><?=Html::dropDownList("BsPrice[currency][]",$price["currency_id"],$options["currency"],["style"=>"width:100px"])?></td>
                    <td>
                        <?php if($k>0){ ?>
                            <a class="remove" style="display: none;" href="javascript:void(0)">删除</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            <?php if(count($data["price"])==0){ ?>
                <tr>
                    <td><input type="text" class="easyui-validatebox minqty" data-options="required:true,validType:['decimal[19,2]','minqty']"  name="BsPrice[minqty][]" value="<?=$data['minOrder']?>" readonly /></td>
                    <td><input type="text" class="easyui-validatebox maxqty" data-options="required:true,validType:['decimal[19,2]','maxqty']" name="BsPrice[maxqty][]" value="<?=$price["maxqty"]?>" /></td>
                    <td><input type="text"  class="easyui-validatebox" data-options="required:true,validType:'price'" name="BsPrice[price][]" value="<?=$price["price"]?>" /></td>
                    <td><?=Html::dropDownList("BsPrice[currency][]",$price["currency_id"],$options["currency"],["style"=>"width:100px"])?></td>
                    <td></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <div class="space-10"></div>
        <div>
            <span class="red">提示：价格面议请填：-1</span>
        </div>
        <div class="space-10"></div>
        <div style="text-align: center;">
            <button class="button-blue new" type="button">新增区间</button>
            <button class="button-blue save" style="margin-left: 40px;" type="submit">保存</button>
            <button class="button-white cancel" type="button">取消</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<div class="currency-tmpl" style="display: none;">
    <?=Html::dropDownList("BsPrice[currency][]","",$options["currency"],["style"=>"width:100px"])?>
</div>

<style type="text/css">
    #price-data input,#price-data select{
        outline: none;
        text-align: center;
        width:100px;
    }
</style>
<script>
    $(function(){
        var minOrder="<?=$data['minOrder']?>";
        var qty="<?=$data['pdt_qty']?>";
        $.extend($.fn.validatebox.defaults.rules, {
            minqty: {
                validator: function(value,param){
                    return parseFloat(value).toFixed(2)==minOrder;
                },
                message: '最小值必须等于最小订购量'
            },
            maxqty: {
                validator: function(value,param){
                    var min=parseFloat($(this).parents("tr").find('.minqty').val());
                    return value>min && value%qty==0;
                },
                message: '最大值必须大于最小值且为销售包装内商品数量整数倍'
            },
            price: {
                validator: function(value,param){
                    var pattern=/^\d{1,14}(\.\d{1,5})?$/;
                    return ($.isNumeric(value) && value>0 && pattern.test(value)) || parseInt(value)==-1;
                },
                message: '价格必须为大于等于0的数字(最多14位整数,最多5位小数)或-1(面议),'
            }

        });
        $(".maxqty:last").prop("readonly",true);
        $(".remove:last").show();
        $("#price-form select").css("background","rgb(235, 235, 228)").prop("disabled",true);
        $.parser.parse($("#price-form"));
        $(".maxqty:last").validatebox({
            required:false,
            validType:['decimal[19,2]','maxqty']
        });
        ajaxSubmitForm($("#price-form"),function(){
            $("#price-form select").css("background","rgb(235, 235, 228)").prop("disabled",false);
            return true;
        },function(res){
            $.fancybox.close();
            layer.alert(res.msg,{icon:2});
        });

        $("#price-form").on("input",".maxqty",function(){
            if($(this).validatebox("isValid")){
                var v=parseFloat($(this).val());
                $(this).parents("tr").next().find(".minqty").val((v*100+qty*100)/100);
            }
        });

        $("#price-form").on("click",".remove",function(){
            $(this).parents("tr").remove();
            $(".remove:last").show();
            $(".maxqty:last").validatebox({
                required:false,
                validType:['decimal[19,2]','maxqty']
            });
            $(".maxqty:last").val("").prop("readonly",true);
        });

        $("#price-form .new").click(function(){
//            if(!$("#price-form tbody tr").last().find(".maxqty").val()){
//                layer.alert("请填写最后一条记录的最大值",{icon:2});
//                return;
//            }
            if($("#price-form").form("validate")){
                var min=parseFloat($("#price-form tbody tr").last().find(".maxqty").val());
                if(min){
                    var min=(min*100+qty*100)/100;
                }else{
                    var min="";
                }
                $("#price-form tbody .remove").hide();
                $("#price-form tbody").append("<tr><td><input type='text' class='easyui-validatebox minqty'  name='BsPrice[minqty][]' style='width:100px;' value='"+min+"' readonly /></td><td><input type='text' class='easyui-validatebox maxqty' data-options='required:true,validType:\"maxqty\"' name='BsPrice[maxqty][]' style='width:100px;' /></td><td><input type='text' class='easyui-validatebox' data-options='required:true,validType:\"price\"' name='BsPrice[price][]' style='width:100px;' maxlength='20' /></td><td>"+$(".currency-tmpl").html()+"</td><td><a class='remove' href='javascript:void(0)'>删除</a></td></tr>");
                $.parser.parse($("#price-form"));
            }
            $(".maxqty").validatebox({
                required:true,
                validType:['decimal[19,2]','maxqty']
            });
            $(".maxqty:last").validatebox({
                required:false,
                validType:['decimal[19,2]','maxqty']
            });
            $(".maxqty").prop("readonly",false);
            $(".maxqty:last").prop("readonly",true);
            $("#price-form select").css("background","rgb(235, 235, 228)").prop("disabled",true);

        });

        $("#price-form .cancel").click(function(){
            $.fancybox.close();
        });
    });
</script>

