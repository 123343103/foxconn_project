<?php
use yii\helpers\Url;

$this->title = '盘点单列表';
$this->params['homeLike'] = ['label' => '仓库物流管理'];
$this->params['breadcrumbs'][] = ['label' => '盘点单列表','url'=>'index'];
$this->params['breadcrumbs'][] = ['label' => '盘点单明细表'];
?>
<style>
    .space-10{
        width: 100%;
        height: 10px;
    }
</style>
<div class="content">
    <?=$this->render('_search',['data'=>$data])?>
    <div class="table-head">
        <p class="head">盘点单明细表</p>
        <div class="float-right">
            <a id="export_btn">
                <div style="height: 23px;float: left;">
                    <p class="export-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
            <a href="<?=Url::to(['/index/index'])?>">
                <div style="height: 23px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
    </div>
    <script>
        $(function () {
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "ivt_id",
                loadMsg: "加载数据请稍候。。。",
                selectOnCheck: false,
                checkOnSelect: false,
                pagination: true,
                singleSelect: true,
                columns: [[
                    {field: "ivt_id", title: "盘点单id", hidden: true},
                    {field: "ivt_code", title: "盘点单号", width: 130,formatter:function (value,rowDate) {
                        return '<a style="color: #2D64B3" href="view?id='+rowDate.ivt_id+'&code='+value+'">'+value+'</a>'
                    }},
                    {field: "company_name", title: "法人", width: 100},
                    {
                        field: "stage", title: "期别", width: 100},
                    {field: "wh_name", title: "仓库名称", width: 100},
                    {field: "wh_code", title: "仓库代码", width: 120},
                    {field: "expiry_date", title: "库存截止时间", width: 120},
                    {field: "part_no", title: "料号", width: 140},
                    {field: "pdt_name", title: "品名", width: 125},
                    {field: "tp_spec", title: "规格型号", width: 120},
                    {field: "unit", title: "单位", width: 80},
                    {field: "notax_price", title: "成本单价", width: 60},
                    {field: "invt_num", title: "库存数量", width: 60},
                    {field: "first_ivtor", title: "初盘人", width: 60},
                    {field: "first_num", title: "初盘数量", width: 60},
                    {field: "first_date", title: "初盘日期", width: 100},
                    {field: "re_ivtor", title: "复盘人", width: 60},
                    {field: "re_num", title: "复盘数量", width: 60},
                    {field: "re_date", title: "复盘日期", width: 120},
                    {field: "lose_num", title: "盈亏数量", width: 60},
                    {field: "lose_price", title: "盈亏金额", width: 60},
                    {field: "remarks", title: "初盘备注", width: 181},
                    {field: "remarks1", title: "复盘备注", width: 181},
                    {field: "ivt_status", title: "状态", width: 100}
                ]],
                onLoadSuccess: function (data) {
                    datagridTip('#data');
                }
            });
            //导出
            $('#export_btn').click(function () {
                var index = layer.confirm("确定要导出请购信息?",
                    {   fix:false,
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function(){
                        layer.closeAll();
                        var url="<?=Url::to(['export-detail'])?>";
                        url+='?ivt_code='+$("#ivt_code").val();
                        url+='&part_no='+$("#part_no").val();
                        url+='&stage='+$("#stage").val();
                        url+='&legal_code='+$("#legal_code").val();
                        url+='&wh_name='+$("#wh_name").val();
                        url+='&wh_code='+$("#wh_code").val();
                        url+='&pdt_name='+$("#pdt_name").val();
                        location.href=url;
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });
        })
    </script>