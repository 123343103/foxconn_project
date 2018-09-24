<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/8/1
 * Time: 上午 09:53
 */
use yii\helpers\Url;
use yii\helpers\Html;
use \yii\widgets\ActiveForm;

?>
<h3 class="head-first">添加商品信息</h3>
<div class="content">
    <?php ActiveForm::begin(); ?>
    <div class="mb-20">
        <label for="" class="width-100">关键字</label>
        <input type="text" class="width-200" placeholder="模糊搜索" name="kwd" id="kwd">
        <button class="button-blue search ml-10" type="button">查询</button>
        <button class="button-white ml-10" type="reset">重置</button>
    </div>
    <?php ActiveForm::end(); ?>
    <div id="product-data" style="width:100%;"></div>
    <div class="mt-20 mb-20 text-center">
        <button class="button-blue ensure" type="button">确定</button>
        <button class="button-white cancel" type="button">取消</button>
    </div>
</div>
<script>
    $(function () {
        $("#product-data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "pdt_no",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns: [[
                {field:"ck",checkbox:true},
                {field:"part_no",title:"料号",width:150},
                {field:"pdt_name",title:"品名",width:150},
                {field:"tp_spec",title:"规格型号",width:100},
                {field:"wh_name",title:"仓库",width:100},
                {field:"st_code",title:"储位",width:100},
                {field:"L_invt_num",title:"库存数量",width:100},
                {field:"L_invt_bach",title:"批次",width:100}
            ]],
            onLoadSuccess: function (data) {
                setMenuHeight();
                datagridTip("#product-data");
                showEmpty($(this),data.total,1);
            },
            onSelect: function (index, data) {
                $("#product-data").datagrid("clearChecked");
                $("#product-data").datagrid("checkRow", index);
            }
        });


        //确定
        $(".ensure").click(function () {
            var rows=$("#product-data").datagrid('getChecked');
            if(rows.length == 0){
                parent.layer.alert('请选择商品！',{icon:2,time:5000});
                return false;
            }
            parent.AddValue(rows);
            parent.$.fancybox.close();
        });
        $(".search").click(function () {
            $("#product-data").datagrid("load", {
                wh_id: $("#wh_id").val(),
                st_id: $("#st_id").val(),
                kwd: $("#kwd").val()
            })
        });
        $(".cancel").click(function () {
            parent.$.fancybox.close();
        });
    });
</script>
