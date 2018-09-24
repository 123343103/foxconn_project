<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/21
 * Time: 下午 01:47
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<h1 class="head-first">选择税别/税率</h1>
<?php ActiveForm::begin(['id' => 'select-form']);?>
<div style="margin:0 15px">
    <div class="mb-10">
        <input id="taxcode" value="<?=$taxno['tax_no']?>" type="text" style="width:200px;" placeholder="税别代码/名称">
        <button id="query_btn" type="submit" class="search-btn-blue" style="margin-left:10px;">查询</button>
        <button id="reset_btn" type="submit" class="reset-btn-yellow" style="margin-left:10px;">重置</button>
    </div>
    <div id="tax_info" style="width:100%;"></div>
    <div style="text-align:center;">
        <button type="button" class="button-blue" id="confirm_btn">确定</button>
        <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
    </div>
</div>
<?php ActiveForm::end();?>
<script>
    $(function () {
      $("#tax_info").datagrid({
          url:"<?= Yii::$app->request->getHostInfo() . Yii::$app->request->url ?>",
          rownumbers:true,
          method:"get",
          singleSelect: true,
          pagination:true,
          pageSize: 5,
          pageList: [5, 10, 15],
          idField:"tax_pkid",
          columns:[[
              {field:"tax_no",title:"税别代码",width:160},
              {field:"tax_name",title:"税别名称",width:160},
              {field:"tax_value",title:"税率",width:200}
          ]],
          onLoadSuccess:function(data){
              showEmpty($(this),data.total,1);
          },
          onDblClickRow:function (index,data) {
              var rows=$("#tax_info").datagrid("getChecked");
              parent.addTax(rows,"");//第一个参数为税别/税率，第二个参数为关联收货中心，这里为空
              parent.$.fancybox.close();
          }
//          onSelect:function (index,data) {
//              alert(data.tax_no);
//          }
      })
        //确定
        $("#confirm_btn").click(function(){
            var rows=$("#tax_info").datagrid("getChecked");
            if(rows.length == 0){
                layer.alert("请至少选择一个税别/税率",{icon:2});
                return false;
            }
            parent.addTax(rows,"");//第一个参数为税别/税率，第二个参数为关联收货中心，这里为空
            parent.$.fancybox.close();
        });
        $("#query_btn").click(function () {
           var tax_no= $("#taxcode").val();
            $("#select-form").attr('action','<?=Url::to(['rate-select'])?>?tax_no='+tax_no);
        });
        //重置
        $("#reset_btn").click(function () {
            $("#taxcode").val("");
            var tax_no="";
            $("#select-form").attr('action','<?=Url::to(['rate-select'])?>?tax_no='+tax_no);
        });
    })
</script>