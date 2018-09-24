<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\TreeAsset;
use app\assets\MultiSelectAsset;
$this->title = '销售订单明细查询报表';
$this->params['homeLike'] = ['label'=>'报表管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'销售订单明细查询报表'];
?>
<style>
    .label-width {
        width: 80px;
    }

    .space-20 {
        height: 20px;
    }

    .width-100 {
        width: 100px;
    }

    .value-width {
        width: 150px;
    }

    .width-80 {
        width: 80px;
    }

    .width-130 {
        width: 130px;
    }

    .ml-25 {
        margin-left: 25px;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        'downList' => $downList,
        'queryParam' => $queryParam,
        'department'=>$department,
    ]); ?>
    <div class="space-10"></div>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
    <script>
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() .                   Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "ord_id",
                loadMsg: false,
                pagination: true,
                singleSelect: true,
                checkOnSelect: false,
                selectOnCheck: false,
                columns: [[
                    <?= $columns ?>
//                    {field:'caerea',title:'营销区域',width:'140'},
//                    {field:'organization_name',title:'销售部门                       ',width:'140'},
//                    {field:'sts_sname',title:'销售点',                              width:'140'},
//                    {field:'district_name',title:'省/直辖市                          ',width:'140'},
//                    {field:'cust_code',title:'客户代码',                             width:'140'},
//                    {field:'cust_shortname',title:'客户简称                          ',width:'140'},
//                    {field:'business_value',title:'订单类型                         ',width:'140'},
//                    {field:'os_name',title:'状态',width:'140'},
//                    {field:'company_name',title:'法人',                             width:'140'},
//                    {field:'staff_code',title:'业务人员',                            width:'140'},
//                    {field:'staff_name',title:'姓名',                               width:'140'},
//                    {field:'ord_date',title:'订单日期',                              width:'140'},
//                    {field:'ord_no',title:'订单号',width:'160'},
//                    {field:'pac_sname',title:'付款方式',                             width:'140'},
//                    {field:'category_sname',title:'商品类别                          ',width:'200'},
//                    {field:'part_no',title:'料号',width:'140'},
//                    {field:'pdt_name',title:'商品名称',                              width:'140'},
//                    {field:'tp_spec',title:'商品规格/型号                           ',width:'140'},
//                    {field:'unit',title:'单位',width:'80'},
//                    {field:'sapl_quantity',title:'订货数                           ',width:'140'},
//                    {field:'uprice_tax_o',title:'单价',                             width:'140'},
//                    {field:'bsp_svalue',title:'币别',                               width:'140'},
//                    {field:'tprice_tax_o',title:'金额',                             width:'140'},
//                    {field:'tprice_tax_c',title:'本币金额                           ',width:'140'}
                ]],
            });

            /*导出订单明细报表信息*/
            $('#export').click(function () {

                var index = layer.confirm("确定导出销售订单明细列?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?=                                 Url::to(['export', 'OrdDelRptSearch[csarea_name]' =>!empty($queryParam) ?  $queryParam['OrdDelRptSearch']['csarea_name'] : null,
 'OrdDelRptSearch[ord_status]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['ord_status'] : null, 'OrdDelRptSearch[cust_department]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['cust_department'] : null, 'OrdDelRptSearch[category_sname]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['category_sname'] : null,
 'OrdDelRptSearch[tp_spec]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['tp_spec'] : null,
 'OrdDelRptSearch[cust_shortname]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['cust_shortname'] : null,'OrdDelRptSearch[cust_code]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['cust_code'] : null,'OrdDelRptSearch[staff_name]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['staff_name'] : null,'OrdDelRptSearch[ord_no]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['ord_no'] : null,'OrdDelRptSearch[start_date]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['start_date'] : null,'OrdDelRptSearch[end_date]' => !empty($queryParam) ? $queryParam['OrdDelRptSearch']['end_date'] : null])?>")
{
    layer.closeAll();
 }
 else {
layer.alert('导出订单明细报表发生错误', {icon: 0})
      }
 },
 function () {
                 layer.closeAll();
             }
             )
           });
});

</script>