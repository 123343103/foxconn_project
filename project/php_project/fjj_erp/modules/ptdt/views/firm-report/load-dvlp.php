<?php

use yii\helpers\Url;

?>
<style>
    .datagrid-wrap{
        width:748px !important;
    }
</style>

<div class="pop-head">
    <p>选择商品</p>
</div>
<div class="content">
<!--    --><?php //echo $this->render('_search', [
//        'model' =>$model['downList']
//    ]); ?>

    <div class="table-content">
        <div id="data" style="width:750px;">
        </div>
        <div class="space-40"></div>
    </div>
    <div class="text-center mt-10">
        <button class="button-blue-big" id="check">确定</button>
        <button class="button-white-big ml-20" onclick="close_select()">取消</button>
    </div>
</div>

<script>
    $(function () {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers :true,
            method: "get",
            idField: "pdq_id",
            loadMsg: false,
            pagination: true,
            singleSelect: true,
            pageSize: 10,
            pageList: [10,20,30],
            columns: [[
                {field: "pdq_code", title: "需求单编号", width: 150},
                {field: "developCenter", title: "开发中心", width: 180},
                {field: "developDepartment", title: "开发部", width: 174},
                {field: "commodity", title: "Commodity", width: 180},
            ]],
            onDblClickRow:function(rowIndex,rowData){

                window.parent.$("#good-manager").css('display','block');
                window.parent.$("#centerName").text(rowData['developCenter']);
                window.parent.$("#departmentName").text(rowData['developDepartment']);
                window.parent.$("#commodity").text(rowData['commodity']);
                window.parent.$("#manager").text(rowData['productManager']+"/"+rowData['productCode']);
                reload();
            },
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });
    });
    $(function(){
        $("#check").click(function(){
            reload();
        })
    })
    function reload(){
        var id = $("#data").datagrid("getSelected")['pdq_id'];
        $.ajax({
            type: 'GET',
            dataType: 'json',
            data: {"id": id},
            url: "<?= Url::to(['/ptdt/firm-report/dvlp-info']) ?>",
            success:function(data){
                for(var i=0;i<data.length;i++){
//                    console.log(data[i]);
                    var tdStr = "<tr>";
                    tdStr += "<td>" + data[i].product_name + "</td>";
                    tdStr += "<td>" + data[i].product_size + "</td>";
                    tdStr += "<td>" + data[i].product_brand + "</td>";
                    tdStr += "<td></td>";
                    tdStr += "<td></td>";
                    tdStr += "<td>" + data[i].product_unit + "</td>";
                    tdStr += "<td></td>";
                    tdStr += "<td></td>";
                    tdStr += "<td></td>";
                    tdStr += "<td></td>";
                    tdStr += "<td></td>";
                    tdStr += "<td>" + data[i].levelName + "</td>";
                    tdStr += "<td></td>";
                    if(data[i].typeId[0] != undefined){
                        tdStr += "<td>" + data[i].typeName[0] + "</td>";
                        tdStr += "<td>" + data[i].typeName[1] + "</td>";
                        tdStr += "<td>" + data[i].typeName[2] + "</td>";
                        tdStr += "<td>" + data[i].typeName[3] + "</td>";
                        tdStr += "<td>" + data[i].typeName[4] + "</td>";
                        tdStr += "<td>" + data[i].typeName[5] + "</td>";
                    }else{
                        tdStr += "<td></td>";
                        tdStr += "<td></td>";
                        tdStr += "<td></td>";
                        tdStr += "<td></td>";
                        tdStr += "<td></td>";
                        tdStr += "<td></td>";
                    }
                    tdStr += "<td><a onclick='product_del(this)'>删除</a></td>";
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][demand_id]" value="' + data[i].requirement_id + '">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_name]" value="' + data[i].product_name + '">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_size]" value="' + data[i].product_size + '">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_brand]" value="' + data[i].product_brand + '">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][delivery_terms]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][payment_terms]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_unit]" value="' + data[i].product_unit + '">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][currency_type]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_max]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_min]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_range]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][price_average]" value="">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_level]" value="' + data[i].product_level_id + '">';
                    tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][profit_margin]" value="">';
                    if(data[i].typeId[0] != undefined){
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_1]" value="' + data[i].typeId[0] + '">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_2]" value="' + data[i].typeId[1] + '">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_3]" value="' + data[i].typeId[2] + '">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_4]" value="' + data[i].typeId[3] + '">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_5]" value="' + data[i].typeId[4] + '">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_6]" value="' + data[i].typeId[5] + '">';
                    }else{
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_1]" value="">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_2]" value="">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_3]" value="">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_4]" value="">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_5]" value="">';
                        tdStr += '<input type="hidden" name="PdFirmReportProduct[' + i + '][product_type_6]" value="">';
                    }
                    $('#table_body', window.parent.document).append(tdStr);
                }
                parent.$.fancybox.close();
            }
        })
    }
</script>