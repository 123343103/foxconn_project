<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

/**
 * F3858995
 * 2016/10/20
 */
$this->params['homeLike'] = ['label' => '报表管理'];
$this->params['breadcrumbs'][] = ['label' => '仓储物流报表'];
$this->params['breadcrumbs'][] = ['label' => '库存报表'];
$this->title="库存报表";
?>
<div class="content">
    <?=$this->render('_search')?>
    <?=$this->render('_action')?>
    <div id="data" style="width:100%;"></div>
</div>
<script>
    $(function() {
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "stock_id",
            loadMsg: '数据加载中......',
            pagination: true,
            singleSelect: true,
            columns:[[
                {field:"ck",checkbox:true},
//                {field:"rcp_no",title:"编码",width:150,formatter:function(value,rowData){
//                    return "<a onclick='viewFun("+rowData.rcp_id+",event)'>"+value+"</a>";
//                }},
                {field:"people",title:"法人",width:200},
                {field:"company",title:"创业公司",width:100},
                {field:"wh_name",title:"仓库名称",width:100},
                {field:"part_no",title:"鸿海料号",width:200},
                {field:"pdt_name",title:"商品名称",width:100},
                {field:"tp_spec",title:"规格",width:200},
                {field:"tp_spec",title:"仓库代码",width:200},
                {field:"st_code",title:"储位",width:100},
                {field:"mydate",title:"最后异动日期",width:200},
                {field:"unit_name",title:"单位",width:200},
                {field:"invt_num",title:"库存量",width:200},
                {field:"invt_amounts",title:"库存金额",width:200},
                {field:"currency",title:"币别",width:50},
                {field:"remarks",title:"备注",width:150}
//                {field:"rcp_id",title:"操作",width:60,formatter:function(value,rowData){
//                    var str="<i>";
//                    if(rowData.rcp_status=='启用'){
//                        str+="<a class='icon-check-minus icon-large' title='禁用' onclick='event.stopPropagation();operationFun("+value+",\"disabled\");'></a>";
//                    }
//                    if(rowData.rcp_status=='禁用'){
//                        str+="<a class='icon-check-sign icon-large' title='启用' onclick='event.stopPropagation();operationFun("+value+",\"enabled\");'></a>";
//                    }
//                    str+="<a class='icon-edit icon-large' style='margin-left:15px;' title='修改' onclick='editFun("+value+",event)'></a>";
//                    str+="</i>";
//                    return str;
//                }}
                ]],
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                showEmpty($(this),data.total,0);
            }
        });

    })
</script>
