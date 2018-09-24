<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/6/23
 * Time: 下午 04:58 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\MultiSelectAsset;
MultiSelectAsset::register($this);
?>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "numadd"]); ?>
<style>
    .fancybox-wrap {
        top: 0px !important;
        left: 0px !important;
    }
</style>
<div class="no-padding width-800 " style="height: 500px">
    <div class="view-plan">
        <h2 class="head-first">选择商品</h2>
        <div class="space-10"></div>

        <div class="mb-20">
            <label for="ProductInfoSearch-invt_id" class="width-50  text-right">仓库</label>
            <select name="ProductInfoSearch[wh_id]" class="width-130" >
<!--                <option value="">--请选择--</option>-->
                <?php foreach ($StaffCode['whname'] as $val) {?>
                    <option value="<?=$val['wh_id'] ?>" <?= isset($search['ProductInfoSearch']['wh_id'])&&$search['ProductInfoSearch']['wh_id']==$val['wh_id']?"selected":null ?>><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>
            <label  class="width-100  text-right">商品类别</label>
            <select id="type_1" class="width-130 type" name="type_1">
                <option value="">--一阶--</option>
                <?php foreach ($downList['productTypes'] as $key => $val) { ?>
                    <option value="<?= $key ?>"  <?= isset($search['type_1'])&&$search['type_1']==$key?"selected":null ?>><?= $val ?></option>
                <?php } ?>
            </select>
            <select  id="type_2" class="width-130 type" name="type_2">
                <option value="">--二阶--</option>
<!--                <option value="" --><?//= isset($search['type_2'])&&$search['type_2']==$key?"selected":null ?><!-->--二阶--</option>-->
            </select>
            <select  id="type_3" class="width-130 type" name="type_3">
                <option value="">--三阶--</option>
<!--                <option value="" --><?//= isset($search['type_3'])&&$search['type_3']==$key?"selected":null ?><!-->--三阶--</option>-->
            </select>
        </div>
        <div class="mb-20">
            <label for="setinventorywarningsearch-staff_name" class="width-50  text-right">料号</label>
            <input type="text" class="staff_name width-130"    name="ProductInfoSearch[part_no]"  value="<?=$search['ProductInfoSearch']['part_no']?>"/>
            <label for="setinventorywarningsearch-staff_name" class="width-100  text-right">商品名称</label>
            <input type="text" class="staff_name width-130"     name="ProductInfoSearch[pdt_name]" value="<?=$search['ProductInfoSearch']['pdt_name']?>"/>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-80', 'type' => 'submit']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-40', 'type' => 'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["numadd"]).'?ProductInfoSearch[wh_id]=' . 1 .'\'']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <div class="table-content">
        <div id="data"></div>
    </div>
    <div class="space-20"></div>
    <div class="mb-20 text-center">
        <button class="button-blue-big"   id="check">确定</button>
        <button class="button-white-big ml-20 close" type="button"  onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>

<script>
//    $(document).ready(function(){
//        var whid=$("#wh_id").val();
//        window.location.href="<?//=Url::to(['numadd']).'?wh_id='?>//"+whid;
//        return;
//    });
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "invt_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize:5,
            pageList:[5,10,15],
            singleSelect: true,
            selectOnCheck:false,
            checkOnSelect:true,
            columns: [[
                {field: 'ck',checkbox:true},
                {field: "wh_name", title: "仓库", width: 190 },
                {field: "category_sname", title: "商品类别", width: 160},
                {field: "part_no", title: "料号", width: 190},
                {field: "pdt_name", title: "商品名称", width: 163},
            ]],
            onLoadSuccess: function (data) {
                $("#data").datagrid("unselectAll");
                $("#data").datagrid("uncheckAll");
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                showEmpty($(this),data.total,1);
            },

        });

        //商品分类联动菜单
        $('.type').on("change", function () {
            var $select = $(this);
            getTypeNext($select);
        });

        //分级分类
        function getTypeNext($select){
            getNextType($select, "<?=Url::to(['/ptdt/product-dvlp/get-product-type']) ?>", "select");
        }
        $("#check").click(function () {
            reload();
        });
        function reload(){
            var data = $("#data").datagrid("getChecked");
            //alert(data.length);
            var i =300;
               $.each(data, function(index, item){
                   var cate=item.category_sname==null?"":item.category_sname;
                   var pdt_name=item.pdt_name==null?"":item.pdt_name;
                   var BRAND_NAME_CN=item.BRAND_NAME_CN==null?"":item.BRAND_NAME_CN;
                   var pdt_model=item.pdt_model==null?"":item.pdt_model;
                   var up_nums=item.up_nums==null?"":item.up_nums;
                   var down_nums=item.down_nums==null?"":item.down_nums;
                   var invt_num=item.invt_num==null?"":item.invt_num;
                   var inv_id=item.inv_id==null?"":item.inv_id;
                        //names.push(item.wh_name);
                   var tdStr = "<tr class='vacc_body_tr'>";
                   tdStr += "<td  style='width: 5%'>" + "<input type='checkbox'   class='width-15 no-border text-center ck' name='selected' />"+ "</td>";
                   tdStr +=  "<td style='width:0%;display: none'>" +"<input type='text' onfocus=this.blur(); style='width: 0%' name='partnoArr["+i+"][BsInvWarnH][inv_id]' value='"+item.inv_id+"'/>" +"</td>";
                   tdStr +=  "<td style='width:0%;display: none'>" +"<input type='text' onfocus=this.blur(); style='width: 0%' name='partnoArr["+i+"][BsInvWarnH][wh_id]' value='"+item.wh_id+"'/>" +"</td>";
                   tdStr +=  "<td style='width:10%'>" +"<input type='text' class='no-border  text-center' onfocus=this.blur(); style='width: 100%'  value='"+item.wh_name+"'/>" +"</td>";
                   tdStr +=  "<td style='width:10%'>" +'<input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+cate+'"/>'+"</td>";
                   tdStr +=  "<td style='width:10%'>" +"<input type='text' class='no-border  text-center' onfocus=this.blur(); style='width: 100%'  name='partnoArr["+i+"][BsInvWarn][part_no]'  value='"+item.part_no+"' />" +"</td>";
                   tdStr += "<td style='width:10%'>" +'<input type="text" class=" no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+pdt_name+'"/>'+ "</td>";
                   tdStr += "<td style='width:10%'>" + '<input type="text" class="no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+BRAND_NAME_CN+'"/>' + "</td>";
                   tdStr += "<td style='width:10%'>" +'<input type="text" class=" no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+pdt_model+'"/>'+ "</td>";
                   tdStr += "<td style='width:10%'>" +'<input type="text" class=" no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+up_nums+'"/>'+ "</td>";
                   tdStr += "<td style='width:10%'>" +'<input type="text" class=" no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+invt_num+'"/>'+ "</td>";
                   tdStr += "<td style='width:10%'>" +'<input type="text" class=" no-border text-center" onfocus=this.blur(); style="width: 100%" value="'+down_nums+'"/>'+ "</td>";
                   tdStr+= "<td style='width:10%'>" +"<a onclick='vacc_onedel(this)'>删除</a>"+ "</td>";//<a onclick='deleteRole()' class='ml-20'><i class='icon-trash icon-l' title='删除'></i></a>
                   tdStr += "</tr>";
                  // window.parent.$(".vacc_body_tr").insertBefore(window.parent.$('#vacc_body'));
                   window.parent.$('#vacc_body').append(tdStr);
                  // console.log(tdStr);
                   i++;
                    });

            parent.$.fancybox.close();
        }



    })

</script>

