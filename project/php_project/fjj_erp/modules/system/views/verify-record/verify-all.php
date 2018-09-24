<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/17
 * Time: 9:57
 */
use yii\widgets\ActiveForm;
//dumpE($arr);
?>
<style>
    .width-650{
        width:650px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .mb-20{
        margin-bottom: 20px;
    }
    .mt-30{
        margin-top: 30px;
    }
</style>
<?php $form = ActiveForm::begin([
    'id'=>'verify'
]); ?>
<h2 class="head-first">签核意见</h2>
<div>
    <div class="width-650 ml-20">
        <div id="data"></div>
    </div>
    <div class="inline-block mt-30">
        <input type="hidden" name="id" class="vcoId">
        <input type="hidden" name="batchaudit" value="1">
        <label class="width-80">签核意见<label>：</label></label>
        <textarea rows="3" name="VerifyrecordChild[vcoc_remark]" class="text-top" style="width:608px;"></textarea>
    </div>
<!--    -->
    <div class="mb-20 text-center mt-30">
        <button class="button-blue-big" type="submit" id="verify-agree">同意</button>
        <button class="button-blue-big" type="submit" id="verify-reject">驳回</button>
        <button class="button-white-big" style="margin-left: 0" type="button" onclick="close_select();">取消</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    var str = <?= $results ?>;
//    console.log(str);
    $(function(){
        $("#data").datagrid({
            rownumbers: true,
            method: "get",
            idField: "vco_id",
            loadMsg: false,
//            pagination: true,
            singleSelect: false,
//            pageSize: 5,
//            pageList: [5, 10, 15],
            columns: [[
                {field: '',checkbox:true},
                {field: "businessType", title: "表单类型", width: 150},
                {field: "vco_code", title: "申请单号", width: 150},
                {field: "applyName", title: "申请人", width: 150,formatter:function(val,row){
                    if(row.applyPerson){
                        return row.applyPerson.applyName;
                    }else{
                        return null;
                    }
                }},
                {field: "vco_senddate", title: "申请日期", width: 150},
                {field: "applyOrg", title: "申请部门", width: 150,formatter:function(val,row){
                    if(row.applyPerson){
                        return row.applyPerson.applyOrg;
                    }else{
                        return null;
                    }
                }},
            ]],
            onLoadSuccess: function (data) {
                var rowData = data.rows;
                $.each(rowData,function(index,val){
                    $("#data").datagrid("selectRow", index);
                });
            }
        });
        $('#data').datagrid('loadData', str);

        $("#verify-agree").on('click',function(){
            var item = $("#data").datagrid('getChecked');
            var arr = [];
            $.each(item,function(index,val){
                arr.push(val.vco_id) ;
            })
            var str = arr.join(',');
            $(".vcoId").val(str);
            $("#verify").attr('action','<?= \yii\helpers\Url::to(['/system/verify-record/audit-pass']) ?>');
            return ajaxSubmitForm($("#verify"));
        });
        $("#verify-reject").on('click',function(){
            var item = $("#data").datagrid('getChecked');
            var arr = [];
            $.each(item,function(index,val){
                arr.push(val.vco_id) ;
            })
//                console.log(arr.join(','));
            var str = arr.join(',');
            $(".vcoId").val(str);
            $("#verify").attr('action','<?= \yii\helpers\Url::to(['/system/verify-record/audit-reject']) ?>');
            return ajaxSubmitForm($("#verify"));
        })
    })
</script>
