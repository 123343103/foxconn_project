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
$this->title='收货通知详情';
$this->params['homeLike']=['label'=>'仓储物流管理'];
$this->params['breadcrumbs'][]=['label'=>'商品收货通知列表','url'=>'list'];
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
    <h1 class="head-first">收货通知详情</h1>
    <div class="mb-10">
        <button type="button" class="button-blue" style="width:80px;" onclick="location.href='<?=Url::to(['list'])?>'">切换列表</button>
    </div>
    <h2 class="head-second">收货基本信息</h2>
    <div class="div_tab_display">
        <label>收货通知单号：</label>
        <span><?=$viewData['rcpnt_no']?></span>
        <label>关联单号：</label>
        <span><?=$viewData['prch_no']?></span>
    </div>
    <div class="div_tab_display">
        <label>单据状态：</label>
        <span><?=$viewData['rcpnt_status']?></span>
        <label>单据类型：</label>
        <span><?=$viewData['rcpnt_type']?></span>
    </div>
    <?php if($viewData['rcpnt_type']=="采购"){?>
        <div class="div_tab_display">
            <label>采购部门：</label>
            <span><?=$viewData['prch_depno']?></span>
            <label>收货中心：</label>
            <span><?=$viewData['rcp_name']?></span>
        </div>
    <?php }?>
    <?php if($viewData['rcpnt_type']=="调拨"){?>
        <div class="div_tab_display">
            <label>调拨类型：</label>
            <span><?=$viewData['chh_type']?></span>
            <label>调拨单位：</label>
            <span><?=$viewData['depart_id']?></span>
        </div>
        <div class="div_tab_display">
            <label>调出仓库：</label>
            <span><?=$viewData['o_wh_name']?></span>
            <label>调出仓库代码：</label>
            <span><?=$viewData['o_wh_code']?></span>
        </div>
        <div class="div_tab_display">
            <label>出库状态：</label>
            <span><?=$viewData['o_status']?></span>
            <label>调入仓库：</label>
            <span><?=$viewData['i_wh_name']?></span>
        </div>
        <div class="div_tab_display">
            <label>调入仓库代码：</label>
            <span><?=$viewData['i_wh_code']?></span>
            <label>入库状态：</label>
            <span><?=$viewData['in_status']?></span>
        </div>
    <?php }?>
    <?php if($viewData['rcpnt_type']=="移仓"){?>
        <div class="div_tab_display">
            <label>出仓仓库：</label>
            <span><?=$viewData['o_wh_name']?></span>
            <label>出仓仓库代码：</label>
            <span><?=$viewData['o_wh_code']?></span>
        </div>
        <div class="div_tab_display">
            <label>出仓仓库属性：</label>
            <span><?=$viewData['o_wh_attr']?></span>
            <label>入库仓库：</label>
            <span><?=$viewData['i_wh_name']?></span>
        </div>
        <div class="div_tab_display">
            <label>入库仓库代码：</label>
            <span><?=$viewData['i_wh_code']?></span>
            <label>入库仓库属性：</label>
            <span><?=$viewData['i_wh_attr']?></span>
        </div>
    <?php }?>
    <div class="div_tab_display">
        <label>通知人：</label>
        <span><?=$viewData['creator']?></span>
        <label>通知日期：</label>
        <span><?=$viewData['creat_date']?></span>
    </div>
    <div class="div_tab_display">
        <label>操作人：</label>
        <span><?=$viewData['operator']?></span>
        <label>操作日期：</label>
        <span><?=$viewData['operate_date']?></span>
    </div>
    <?php if(!empty($viewData['cancel_reason'])){?>
        <div class="div_tab_display">
            <label>取消原因：</label>
            <span style="width:750px;"><?=$viewData['cancel_reason']?></span>
        </div>
    <?php }?>
    <h2 class="head-second">收货商品基本信息</h2>
    <div style="overflow:auto;">
        <table class="table"
            <?php
            if($viewData['rcpnt_type']=="采购"){
                echo "style='width:1440px;'";
            }
            if($viewData['rcpnt_type']=="调拨"){
                echo "style='width:1140;'";
            }
            if($viewData['rcpnt_type']=="移仓"){
                echo "style='width:1040px;'";
            }
            ?>
        >
            <thead>
            <tr>
                <th style="width:40px;">序号</th>
                <th style="width:150px;">料号</th>
                <th style="width:200px;">品名</th>
                <th style="width:150px;">规格型号</th>
                <th style="width:100px;">品牌</th>
                <th style="width:100px;">单位</th>
                <?php if($viewData['rcpnt_type']=="采购"){?>
                    <th style="width:200px;">供应商编码</th>
                    <th style="width:200px;">供应商名称</th>
                    <th style="width:100px;">采购量</th>
                    <th style="width:100px;">送货数量</th>
                    <th style="width:100px;">预计到货日期</th>
                <?php }?>
                <?php if($viewData['rcpnt_type']=="调拨"){?>
                    <th style="width:100px;">批次</th>
                    <th style="width:100px;">现有库存量</th>
                    <th style="width:100px;">调拨数量</th>
                    <th style="width:100px;">出仓储位</th>
                <?php }?>
                <?php if($viewData['rcpnt_type']=="移仓"){?>
                    <th style="width:100px;">批次</th>
                    <th style="width:100px;">移仓前储位</th>
                    <th style="width:100px;">移仓数量</th>
                <?php }?>
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
            data:{"code":"<?=$viewData['rcpnt_no']?>"},
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
                    <?php if($viewData['rcpnt_type']=="采购"){?>
                    trStr+="<td style='word-break:break-all;'>"+(n.group_code==null?'':n.group_code)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.spp_fname==null?'':n.spp_fname)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.ord_num==null?'':n.ord_num)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.delivery_num==null?'':n.delivery_num)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.plan_date==null?'':n.plan_date)+"</td>";
                    <?php }?>
                    <?php if($viewData['rcpnt_type']=="调拨"){?>
                    trStr+="<td style='word-break:break-all;'>"+(n.ord_id==null?'':n.ord_id)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.invt_num==null?'':n.invt_num)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.delivery_num==null?'':n.delivery_num)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.before_stno==null?'':n.before_stno)+"</td>";
                    <?php }?>
                    <?php if($viewData['rcpnt_type']=="移仓"){?>
                    trStr+="<td style='word-break:break-all;'>"+(n.ord_id==null?'':n.ord_id)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.before_stno==null?'':n.before_stno)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.chwh_num==null?'':n.chwh_num)+"</td>";
                    <?php }?>
                    trStr+="</tr>";
                    $("tbody").append(trStr);
                })
            }
        });
    })
</script>