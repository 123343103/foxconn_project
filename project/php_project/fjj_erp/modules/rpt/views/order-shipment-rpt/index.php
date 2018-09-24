<?php
use yii\helpers\Url;
$this->title = '订单出货情况查询报表';
$this->params['homeLike'] = ['label'=>'报表管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'订单出货情况查询报表'];

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
        'department'=>$department

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
                ]],
            });

            /*导出订单明细报表信息*/
            $('#export').click(function () {

                var index = layer.confirm("确定导出订单出货情况查询报表?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?=                                 Url::to(['export', 'OrdShipRptSearch[csarea_name]' =>!empty($queryParam) ?  $queryParam['OrdShipRptSearch']['csarea_name'] : null,
'OrdShipRptSearch[cust_department]' =>!empty($queryParam) ?  $queryParam['OrdShipRptSearch']['cust_department'] : null,
 'OrdShipRptSearch[cust_shortname]' => !empty($queryParam) ? $queryParam['OrdShipRptSearch']['cust_shortname'] : null,'OrdShipRptSearch[cust_code]' => !empty($queryParam) ? $queryParam['OrdShipRptSearch']['cust_code'] : null,'OrdShipRptSearch[staff_name]' => !empty($queryParam) ? $queryParam['OrdShipRptSearch']['staff_name'] : null,'OrdShipRptSearch[ord_no]' => !empty($queryParam) ? $queryParam['OrdShipRptSearch']['ord_no'] : null,'OrdShipRptSearch[start_date]' => !empty($queryParam) ? $queryParam['OrdShipRptSearch']['start_date'] : null,'OrdShipRptSearch[end_date]' => !empty($queryParam) ? $queryParam['OrdShipRptSearch']['end_date'] : null])?>")
{
    layer.closeAll();
 }
 else {
layer.alert('导出订单出货情况查询报表发生错误', {icon: 0})
      }
 },
 function () {
                 layer.closeAll();
             }
             )
           });
});

</script>