<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\TreeAsset;
use app\assets\MultiSelectAsset;
$this->title = '销售订单汇总查询报表';
$this->params['homeLike'] = ['label'=>'报表管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'销售订单汇总查询报表'];
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

                var index = layer.confirm("确定导出销售订单汇总报表?",
                    {
                        btn: ['確定', '取消'],
                        icon: 2
                    },
                    function () {
                        if (window.location.href = "<?=                                 Url::to(['export', 'OrdSumRptSearch[csarea_name]' =>!empty($queryParam) ?  $queryParam['OrdSumRptSearch']['csarea_name'] : null,
'OrdSumRptSearch[ord_status]' =>!empty($queryParam) ?  $queryParam['OrdSumRptSearch']['ord_status'] : null,
'OrdSumRptSearch[sts_sname]' =>!empty($queryParam) ?  $queryParam['OrdSumRptSearch']['sts_sname'] : null,
 'OrdSumRptSearch[cust_shortname]' => !empty($queryParam) ? $queryParam['OrdSumRptSearch']['cust_shortname'] : null,'OrdSumRptSearch[cust_code]' => !empty($queryParam) ? $queryParam['OrdSumRptSearch']['cust_code'] : null,'OrdSumRptSearch[staff_name]' => !empty($queryParam) ? $queryParam['OrdSumRptSearch']['staff_name'] : null,'OrdSumRptSearch[ord_no]' => !empty($queryParam) ? $queryParam['OrdSumRptSearch']['ord_no'] : null,'OrdSumRptSearch[start_date]' => !empty($queryParam) ? $queryParam['OrdSumRptSearch']['start_date'] : null,'OrdSumRptSearch[end_date]' => !empty($queryParam) ? $queryParam['OrdSumRptSearch']['end_date'] : null])?>")
{
    layer.closeAll();
 }
 else {
layer.alert('导出订单汇总报表发生错误', {icon: 0})
      }
 },
 function () {
                 layer.closeAll();
             }
             )
           });
});

</script>