<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/14
 * Time: 下午 02:50
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \yii\widgets\ActiveForm;
?>
<style>
    .width-50 {
        width: 50px;
    }

    .width-100 {
        width: 100px;
    }
    .width-120 {
        width: 150px;
    }
    .mb-20{
        margin-bottom: 20px;
    }
</style>
<h3 class="head-first">添加商品信息</h3>
<div class="content">
    <?php ActiveForm::begin();?>
    <div class="mb-20">
        <label for="" class="width-50">仓库:</label>
            <?php foreach ($options['warehouse'] as $key=>$val){ if($val['wh_id']==$id){?>
                <input class="width-120" value="<?= $val['wh_name']?>" disabled >
            <?php }}?>
        <label for="" class="width-50">储位:</label>
        <select name="st_code" id="st_code" class="width-120">
            <option value="">请选择</option>
            <?php foreach ($options['bsst'] as $key=>$val){?>
                <option value="<?= $val['st_code']?>"><?= $val['st_code']?></option>
            <?php }?>
        </select>
        <input type="text" class="width-120" placeholder="料号/品名" name="kwd" id="kwd" value="<?= $part_no?>">
        <button class="button-blue search" type="button">查询</button>
        <button class="button-white reset" type="reset">重置</button>
        <input type="hidden" id="wh_id" value="<?= $id?>">
        <input type="hidden" id="part_no" value="<?= $part_no?>">
    </div>
    <?php ActiveForm::end();?>
    <div id="product-data" style="width:100%;"></div>
    <div class="mt-20 mb-20 text-center">
        <button class="button-blue ensure" type="button">确定</button>
        <button class="button-white cancel" type="button">取消</button>
    </div>
</div>
<script>
    $(function(){
        $("#product-data").datagrid({
            url:"<?=Url::to(['select-product'])?>?id="+$("#wh_id").val()+"&part_no="+$("#part_no").val(),
            rownumbers: true,
            method: "get",
            idField: "invt_id",
            loadMsg: "加载数据请稍候。。。",
            pagination:true,
            pageSize:5,
            pageList:[5,10,15],
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns:[[
                {field:"ck",checkbox:true},
                {field:"part_no",title:"料号",width:150},
                {field:"pdt_name",title:"品名",width:150},
                {field:"tp_spec",title:"规格型号",width:100},
                {field:"st_code",title:"储位",width:100},
                {field:"batch_no",title:"批次",width:100},
                {field:"invt_num",title:"库存数量",width:100}
            ]],
            onSelect:function(index,data){
                $("#product-data").datagrid("clearChecked");
                $("#product-data").datagrid("checkRow",index);
            }
        });

        $(".ensure").click(function(){
            var products=parent.$("#products").val().split(",");
//            var st_id=parent.$("#st_id").val().split(",");
            var rows=$("#product-data").datagrid("getChecked");
            if(rows.length>0){
                for(var x in rows){
                    if(products.filter(function(i){return i==rows[x].invt_id}).length>0){
                        continue;
                    }
                    parent.$("#data").append('<tr>\
                        <td align="center"></td>\
                        <td align="center">\
                        <input type="checkbox">\
                        <input type="hidden" class="invt_id" name="OWhpdtDt[invt_id][]" value="'+rows[x].invt_id+'">\
                        </td>\
                        <td align="center"><input class="part_no" name="OWhpdtDt[part_no][]" value="'+rows[x].part_no+'" data-options="required:true,validateOnBlur:true,delay: 1000000"></td>\
                        <td align="center">'+rows[x].pdt_name+'</td>\
                        <td align="center">'+rows[x].tp_spec+'</td>\
                        <td align="center">'+rows[x].st_code+'</td>\
                        <td align="center">'+rows[x].batch_no+'</td>\
                        <td align="center">'+rows[x].unit_name+'</td>\
                        <td align="center">'+rows[x].invt_num+'</td>\
                        <td align="center"><input class="number" name="OWhpdtDt[o_whnum][]" data-options="required:true,validateOnBlur:true"  class="width-50" style="text-align:center;" type="text"></td>\
                        <td align="center"><textarea name="OWhpdtDt[remarks][]" class="width-100"  style=""></textarea></td>\
                        <td align="center">\
                        <a onclick="delRow(this)"><i class="icon-minus"></i></a>&nbsp;&nbsp;<a onclick="refreshRow(this)"><i class="icon-refresh ml-20"></i></a>\
                        </td>\
                        </tr>\
                        ');
                    products.push(rows[x].invt_id);
                    parent.$("#products").val(products.join(","));
//                    st_id.push(rows[x].st_id);
//                    parent.$("#st_id").val(st_id.join(","));
                }

                if(products.length>0) {
                    parent.$("#data+tfoot").hide();
                }

                for(var y=0;y<parent.$("#data tr").size();y++){
                    parent.$("#data tr").eq(y).find("td:first").text(y+1);
                    parent.$("#checkAll").prop("checked",parent.$("#data :checked").size()==parent.$("#data :checkbox").size() && parent.$("#data :checked").size()>0);
                }
                parent.$("#data .part_no").validatebox({
                    required: true,
                    validType: ["productCodeValidate"],
                    delay: 1000000
                });
                parent.$("#data .number").validatebox({
                    required: true,
                    validType: ["judgenumber","isnumber"],
                    delay: 1000000
                });
            }

            parent.$.fancybox.close();
        });
        $(".search").click(function(){
            $("#product-data").datagrid("load",{
//                wh_id:$("#wh_id").val(),
                st_code:$("#st_code").val(),
                kwd:$("#kwd").val()
            })
        });
        $(".reset").click(function(){
            $("#product-data").datagrid("load",{})
        });
        $(".cancel").click(function(){
            parent.$.fancybox.close();
        });
    });
</script>
