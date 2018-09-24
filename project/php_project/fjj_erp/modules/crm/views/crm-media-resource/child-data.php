<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/3
 * Time: 上午 10:26
 */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>

<div class="table-head">
     <p class="mb-10">媒体服务内容</p>
<!--    <div id="child-data"></div>-->
    <table id="child-data" style="width: 100%;display: none;">
        <thead>
            <tr>
                <th rowspan="2" width="150"  field="medicc_srdate">服务时间</th>
                <th rowspan="2" width="150"  field="medicc_porjects">服务项目</th>
                <th rowspan="2" width="150"  field="medicc_srtype">服务形式</th>
                <th rowspan="2" width="150"  field="medicc_srcycle">服务周期</th>
                <th rowspan="2" width="150"  field="medicc_srcost">服务费用</th>
                <th rowspan="2" width="150"  field="medicc_srtype">服务形式</th>
                <th colspan="3" width="300" field="a">预期效果</th>
                <th colspan="8" width="600" field="b">实际效果</th>
                <th rowspan="2" width="150" field="medicc_remark">备注</th>
            </tr>
            <tr>
                <th width="150"  field="medicc_expectqty">展现量</th>
                <th width="150"  field="medicc_clickrate">点击率</th>
                <th width="150"  field="medicc_uv">UV</th>

                <th width="150"  field="medicc_realqty">展现量</th>
                <th width="150"  field="medicc_realclickrate">点击率</th>
                <th width="150"  field="medicc_realuv">UV</th>
                <th width="150"  field="medicc_avgclickrate">平均点击价</th>
                <th width="150"  field="medicc_400tel">400电话</th>
                <th width="150"  field="medicc_tq">TQ咨询</th>
                <th width="150"  field="medicc_rememqty">注册会员</th>
                <th width="150"  field="medicc_cpa">CPA</th>
            </tr>
        </thead>
    </table>
</div>

<style>
    body{
        background: #fff;
    }
</style>

<script>
    $(function(){
        $("#child-data").datagrid({
            url: "<?=Url::current()?>",
            rownumbers: true,
            method: "get",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize:2,
            pageList:[2],
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            onLoadSuccess: function (data) {
                datagridTip($("#child-data"));
                showEmpty($(this),data.total,0);
            }
        });
    });
</script>




<style type="text/css">
    .datagrid-header{
        height: 52px !important;
    }
    .datagrid-htable{
        height: 54px !important;
    }
</style>