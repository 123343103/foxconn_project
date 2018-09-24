<?php
/**
 * User: F1677929
 * Date: 2017/12/19
 */
/* @var $this yii\web\View */
use app\classes\Menu;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title='采购入库详情';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=['label'=>'采购入库列表','url'=>'list'];
$this->params['breadcrumbs'][]=$this->title;
?>
<style>
    .div_tab_display {
        display: table;
        margin-bottom: 5px;
    }
    .div_tab_display > label {
        display: table-cell;
        vertical-align: middle;
        width: 150px;
    }
    .div_tab_display > span {
        display: table-cell;
        vertical-align: middle;
        width: 300px;
        word-break: break-all;
    }
</style>
<div class="content">
    <h1 class="head-first">收货单详情</h1>
    <div class="mb-10">
        <button type="button" class="button-blue" style="width:80px;" onclick="location.href='<?=Url::to(['list'])?>'">切换列表</button>
    </div>
    <h2 class="head-second">入库基本信息</h2>
    <div class="div_tab_display">
        <label>入库单编号：</label>
        <span><?=$viewData['invh_code']?></span>
        <label>关联采购单号：</label>
        <span><?=$viewData['prch_no']?></span>
    </div>
    <div class="div_tab_display">
        <label>单据状态：</label>
        <span><?=$viewData['invh_status']?></span>
        <label>入仓仓库：</label>
        <span><?=$viewData['wh_name']?></span>
    </div>
    <div class="div_tab_display">
        <label>仓库代码：</label>
        <span><?=$viewData['wh_code']?></span>
        <label>仓库属性：</label>
        <span><?=$viewData['wh_attr']?></span>
    </div>
    <div class="div_tab_display">
        <label>采购部门：</label>
        <span><?=$viewData['prch_depno']?></span>
        <label>收货中心：</label>
        <span><?=$viewData['rcp_name']?></span>
    </div>
    <div class="div_tab_display">
        <label>入库单日期：</label>
        <span><?=$viewData['cdate']?></span>
        <label>操作人：</label>
        <span><?=$viewData['update_by']?></span>
    </div>
    <div class="div_tab_display">
        <label>操作日期：</label>
        <span><?=$viewData['udate']?></span>
    </div>
    <h2 class="head-second">收货商品基本信息</h2>
    <div style="overflow:auto;">
        <table class="table" style="width:1540px;">
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:200px;">品名</th>
                <th style="width:150px;">规格/型号</th>
                <th style="width:100px;">品牌</th>
                <th style="width:100px;">单位</th>
                <th style="width:100px;">采购方式</th>
                <th style="width:100px;">采购量</th>
                <th style="width:100px;">入库数量</th>
                <th style="width:100px;">批次</th>
                <th style="width:200px;">储位</th>
                <th style="width:200px;">存放数量</th>
                <th style="width:100px;">上架日期</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script>
    $(function(){
        $.ajax({
            url:"<?=Url::to(['get-pno'])?>",
            data:{"id":<?=$viewData['invh_id']?>},
            dataType:"json",
            success:function(data){
                $.each(data.rows,function(i,n){
                    for(var a in n){
                        if(n[a]){
                            n[a]=n[a].decode();
                        }
                    }
                    var trStr="<tr>";
                    trStr+="<td>"+(i+1)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.part_no+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.pdt_name+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.tp_spec+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.brand+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.unit+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.req_type==null?'':n.req_type)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.ord_num==null?'':n.ord_num)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.real_quantity==null?'':n.real_quantity)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.batch_no==null?'':n.batch_no)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.st_codes==null?'':n.st_codes)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.store_num==null?'':n.store_num)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.inout_time==null?'':n.inout_time)+"</td>";
                    trStr+="</tr>";
                    $("tbody").append(trStr);
                })
            }
        });
    })
</script>