<?php
/**
 * User: F1677929
 * Date: 2017/9/6
 */
/* @var $this yii\web\View */
use yii\widgets\ActiveForm;
\app\assets\JqueryUIAsset::register($this);
?>
<style>
    .width-100 {
        width: 100px;
    }
    .width-200 {
        width: 200px;
    }
    .ml-10 {
        margin-left: 10px;
    }
</style>
<?php ActiveForm::begin()?>
<h1 class="head-first"><?=$this->title?></h1>
<div class="mb-10">
    <label class="width-100"><span style="color:red;">*</span>属性名称：</label>
    <input type="text" class="width-200 easyui-validatebox" data-options="required:true,validType:['length[0,30]'],tipPosition:'bottom'" name="BsCatgAttr[attr_name]" value="<?=$data['attr_name']?>">
</div>
<div class="mb-10">
    <label class="width-100">资料格式：</label>
    <select id="material_format" class="width-200" name="BsCatgAttr[attr_type]"
    <?php if($data['material_format_edit_flag'] === 0){?>
        disabled="disabled"
    <?php }?>
    >
        <option value="3" <?=$data['attr_type']==='3'?'selected':''?>>文字录入</option>
        <option value="0" <?=$data['attr_type']==='0'?'selected':''?>>多项选择</option>
        <option value="1" <?=$data['attr_type']==='1'?'selected':''?>>平铺选择</option>
        <option value="2" <?=$data['attr_type']==='2'?'selected':''?>>下拉选择</option>
    </select>
    <?php if(!empty($data['values']) && $data['attr_type']!=='3'){?>
        <button id="add_value_btn" type="button" class="button-blue ml-10">+添加值</button>
    <?php }?>
</div>
<?php if(!empty($data['values']) && $data['attr_type']!=='3'){?>
<div id="value_div" style="margin:0 15px 10px;">
    <table class="table" style="font-size:12px;">
        <thead>
        <tr>
            <th style="width:10%;">序号</th>
            <th style="width:60%;"><span style="color:red;">*</span><span style="color:white;">属性值</span></th>
            <th style="width:15%;">状态</th>
            <th style="width:15%;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data['values'] as $key=>$val){?>
            <tr>
                <td><?=$key+1?></td>
                <td>
                    <input type="hidden" name="values[<?=$key?>][RAttrValue][attr_val_id]" value="<?=$val['attr_val_id']?>">
                    <input type="text" style="width:100%;text-align:center;" class="easyui-validatebox" data-options="required:true,validType:['length[0,50]','tdSame'],tipPosition:'bottom'" name="values[<?=$key?>][RAttrValue][attr_value]" value="<?=$val['attr_value']?>">
                </td>
                <td>
                    <?php if($val['yn']==='1'){?>
                        <span>启用</span>
                    <?php }else{?>
                        <span>禁用</span>
                    <?php }?>
                    <input type="hidden" name="values[<?=$key?>][RAttrValue][yn]" value="<?=$val['yn']?>">
                </td>
                <td>
                    <?php if($val['yn']==='1'){?>
                        <a class="icon-remove-circle icon-large" title='禁用'></a>
                    <?php }else{?>
                        <a class="icon-ok-circle icon-large" title='启用'></a>
                    <?php }?>
                    <?php if($val['attr_value_edit_flag']===1){?>
                        <a class="icon-remove red ml-10" title='删除'></a>
                    <?php }?>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
</div>
<?php }?>
<div class="mb-10">
    <label class="width-100">是否必填：</label>
    <select class="width-200" name="BsCatgAttr[isrequired]">
        <option value="1" <?=$data['isrequired']==='1'?'selected':''?>>是</option>
        <option value="0" <?=$data['isrequired']==='0'?'selected':''?>>否</option>
    </select>
</div>
<div class="mb-10">
    <label class="width-100 vertical-top">备注：</label>
    <textarea style="width:300px;height:50px;" class="easyui-validatebox" data-options="validType:'length[0,200]',tipPosition:'bottom'" name="BsCatgAttr[attr_remark]"><?=$data['attr_remark']?></textarea>
</div>
<div style="text-align:center;margin-bottom:20px;">
    <button type="submit" class="button-blue">保存</button>
    <button type="button" class="button-white ml-10" onclick="parent.$.fancybox.close()">取消</button>
</div>
<?php ActiveForm::end()?>
<script>
    $(function(){
        //资料格式切换
        $("#material_format").change(function(){
            $(document).find("#add_value_btn,#value_div").remove();
            if(this.value!=='3'){
                $(this).after("<button id='add_value_btn' type='button' class='button-blue ml-10'>+添加值</button>");
                $(this).parent().after(
                    "<div id='value_div' style='margin:0 15px 10px;'>"+
                    "<table class='table' style='font-size:12px;'>"+
                    "<thead>"+
                    "<tr>"+
                    "<th style='width:10%'>序号</th>"+
                    "<th style='width:60%'><span style='color:red;'>*<span><span style='color:white;'>属性值</span></th>"+
                    "<th style='width:15%'>状态</th>"+
                    "<th style='width:15%'>操作</th>"+
                    "</tr>"+
                    "</thead>"+
                    "<tbody>"+
                    "<tr>"+
                    "<td>1</td>"+
                    "<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='required:true,validType:[\"length[0,50]\",\"tdSame\"],tipPosition:\"bottom\"' name='values[0][RAttrValue][attr_value]'></td>"+
                    "<td>启用<input type='hidden' name='values[0][RAttrValue][yn]' value='1'></td>"+
                    "<td></td>"+
                    "</tr>"+
                    "</tbody>"+
                    "</table>"+
                    "</div>"
                );
                $.parser.parse('tr');
            }
        });

        //添加属性值
        var m=100;
        $(document).on("click","#add_value_btn",function(){
            $("#value_div").find("tbody").append(
                "<tr>"+
                "<td></td>"+
                "<td><input type='text' style='width:100%;text-align:center;' class='easyui-validatebox' data-options='required:true,validType:[\"length[0,50]\",\"tdSame\"],tipPosition:\"bottom\"' name='values["+m+"][RAttrValue][attr_value]'></td>"+
                "<td><span>启用</span><input type='hidden' name='values["+m+"][RAttrValue][yn]' value='1'></td>"+
                "<td><a class='icon-remove red' title='删除'></a></td>"+
                "</tr>"
            ).find("tr").each(function(i){
                $(this).find("td:first").text(i+1);
            });
            $.parser.parse("tr:last");
            m++;
            if($("tbody tr:first td:last a").length ===0){
                $("tbody tr").each(function(){
                    $(this).find("td:last").html("<a class='icon-remove red' title='删除'></a>");
                });
            }
        });

        //删除属性值
        $(document).on("click",".icon-remove",function(){
            $(this).parents("tr").remove();
            $("#value_div").find("tbody").find("tr").each(function(i){
                $(this).find("td:first").text(i+1);
            });
            if($("tbody tr").length === 1){
                <?php if(empty($data)){?>
                $("tbody td:last").html("");
                <?php }else{?>
                $("tbody td:last").find(".icon-remove").remove();
                <?php }?>
            }
        });

        //ajax提交表单
        ajaxSubmitForm("form",
            function(){
                if($("#material_format").val()!=='3' && $("tbody > tr").length===$("tbody .icon-ok-circle").length){
                    parent.layer.alert("请设置有效属性值！",{icon:2});
                    return false;
                }else{
                    return true;
                }
            },
            function(data){
                if(data.flag==1){
                    parent.$(".fancybox-overlay").hide();
                    parent.$(".fancybox-wrap").hide();
                    parent.layer.alert(data.msg,{
                        icon:1,
                        end:function(){
                            <?php if(empty($data)){?>
//                            parent.$("#attr_table").datagrid('load');
                            <?php }else{?>
//                            parent.$("#attr_table").datagrid('reload');
                            <?php }?>
                            parent.location.reload();
                            parent.$.fancybox.close();
                        }
                    });
                }
                if(data.flag==0){
                    parent.layer.alert(data.msg,{icon:2});
                    $("button[type='submit']").prop("disabled",false);
                }
        });

        //启用属性值
        $(document).on("click",".icon-ok-circle",function(){
            $(this).parent().prev().children("span").html("启用");
            $(this).parent().prev().children("input").val("1");
            $(this).removeClass("icon-ok-circle").addClass("icon-remove-circle").attr("title","禁用");
        });

        //禁用属性值
        $(document).on("click",".icon-remove-circle",function(){
            $(this).parent().prev().children("span").html("禁用");
            $(this).parent().prev().children("input").val("0");
            $(this).removeClass("icon-remove-circle").addClass("icon-ok-circle").attr("title","启用");
        });
    })
</script>