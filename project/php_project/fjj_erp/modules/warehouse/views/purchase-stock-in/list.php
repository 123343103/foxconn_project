<?php
/**
 * User: F1677929
 * Date: 2017/12/16
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='采购入库列表';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search',['data'=>$data])?>
    <?=$this->render('_action')?>
    <div id="table1" style="width:100%;"></div>
    <div style="height:10px;"></div>
    <div id="table2" style="width:100%;"></div>
</div>
<script>
    $(function(){
        $("#table1").datagrid({
            url:"<?=Url::to(['list'])?>",
            rownumbers:true,
            method:"get",
            singleSelect:true,
            pagination:true,
            columns:[[
//                {field:"invh_code",title:"入库单号",width:150,formatter:function(value,rowData){
//                    return "<a onclick='event.stopPropagation();location.href=\"<?//=Url::to(['view'])?>//?id="+rowData.invh_id+"\"'>"+value+"</a>";
//                }},
//                {field:"invh_status",title:"单据状态",width:80},
//                {field:"in_whname",title:"入仓仓库",width:100},
//                {field:"prch_no",title:"关联采购单号",width:200},
//                {field:"prch_depno",title:"采购部门",width:100},
//                {field:"rcp_name",title:"收货中心",width:100},
//                {field:"cdate",title:"入库单日期",width:100},
//                {field:"update_by",title:"操作人",width:80},
//                {field:"udate",title:"操作日期",width:100}
                <?= $fields ?>
            ]],
            onLoadSuccess: function (data) {
                if(data.total==0){
                    $("#export_btn").hide().next().hide();
                }else{
                    $("#export_btn").show().next().show();
                }
                datagridTip("#table1");
                showEmpty($(this), data.total, 0);
                setMenuHeight();
                $("#table1").datagrid('clearSelections').datagrid('clearChecked');
                $("#put_away_btn").hide().next().hide();
                $("#table2").parents(".datagrid").hide();
            },
            onSelect: function (rowIndex, rowData) {
                if(rowData.invh_status=='待上架'){
                    $("#put_away_btn").show().next().show();
                }
                $("#table2").datagrid({
                    url:"<?=Url::to(['get-pno'])?>?id="+rowData.invh_id,
                    rownumbers:true,
                    method:"get",
                    singleSelect:true,
                    pagination:true,
                    columns:[[
                        {field:"part_no",title:"料号",width:150},
                        {field:"pdt_name",title:"品名",width:200},
                        {field:"tp_spec",title:"规格/型号",width:150},
                        {field:"brand",title:"品牌",width:100},
                        {field:"unit",title:"单位",width:100},
                        {field:"group_code",title:"供应商编码",width:100},
                        {field:"spp_fname",title:"供应商名称",width:100},
                        {field:"ord_num",title:"采购量",width:100},
                        {field:"real_quantity",title:"入库数量",width:100},
                        {field:"batch_no",title:"批次",width:100},
                        {field:"st_codes",title:"储位",width:200},
                        {field:"store_num",title:"存放数量",width:200},
                        {field:"up_date",title:"上架日期",width:100}
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip("#table2");
                        showEmpty($(this), data.total, 0);
                        setMenuHeight();
                    }
                });
            }
        });
    })
</script>