<?php
/**
 * User: F1677929
 * Date: 2017/11/10
 */
/* @var $this yii\web\View */
use app\classes\Menu;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
if(empty($vco_id)){
    $this->title='供应商申请详情';
    $this->params['homeLike']=['label'=>'供应商管理'];
    $this->params['breadcrumbs'][]=['label'=>'供应商列表','url'=>'index'];
    $this->params['breadcrumbs'][]=$this->title;
}else{
    $this->title='供应商审核';
    $this->params['homeLike']=['label'=>'主页'];
    $this->params['breadcrumbs'][]=['label'=>'审核列表','url'=>'index'];
    $this->params['breadcrumbs'][]=$this->title;
}
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
    <h1 class="head-first">
        供应商详情
        <?=empty($viewData['spp_code'])?"":"<span style='font-size:12px;color:white;float:right;margin-right:15px;'>编号：".$viewData['spp_code']."</span>"?>
    </h1>
    <?php if(empty($vco_id)){?>
        <div class="mb-10">
            <?php if($viewData['spp_status']==1 || $viewData['spp_status']==4){?>
                <?php if(Menu::isAction('/spp/supplier/edit')){?>
                    <button type="button" class="button-blue" onclick="location.href='<?=Url::to(['edit','id'=>$viewData['spp_id']])?>'">修改</button>
                <?php }?>
                <?php if(Menu::isAction('/spp/supplier/check')){?>
                    <button id="check_btn" type="button" class="button-blue">送审</button>
                <?php }?>
            <?php }?>
            <button type="button" class="button-blue" style="width:80px;" onclick="location.href='<?=Url::to(['index'])?>'">切换列表</button>
        </div>
    <?php }else{?>
        <?php $form = ActiveForm::begin(['id' => 'check-form']); ?>
        <input type="hidden" name="id" value="<?= $vco_id ?>">
        <?php ActiveForm::end(); ?>
        <div class="mb-10">
            <?= Html::button('通过', ['class' => 'button-blue','id'=>'pass']) ?>
            <?= Html::button('驳回', ['class' => 'button-blue','id' => 'reject']) ?>
            <?= Html::button('切换列表', ['class' => 'button-blue', 'style'=>'width:80px;', 'type' => 'button', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
    <?php }?>
    <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-down" style="font-size:25px;vertical-align:middle;"></i><span>供应商基本信息</span>
    </h2>
    <div>
        <div class="div_tab_display">
            <label>供应商全称：</label>
            <span><?=$viewData['spp_fname']?></span>
            <label>供应商简称：</label>
            <span><?=$viewData['spp_sname']?></span>
        </div>
        <div class="div_tab_display">
            <label>供应商集团简称：</label>
            <span><?=$viewData['spp_gsname']?></span>
            <label>品牌：</label>
            <span><?=$viewData['spp_brand']?></span>
        </div>
        <div class="div_tab_display">
            <label>Commodify：</label>
            <span><?=$viewData['commodify']?></span>
            <label>新增类型：</label>
            <span><?=$viewData['add_type']?></span>
        </div>
        <div class="div_tab_display">
            <label>供应商状态：</label>
            <span>
                <?php
                    if($viewData['isvalid'] === '0'){
                        echo '封存';
                    }elseif($viewData['isvalid'] === '1'){
                        echo '正常';
                    }else{
                        echo '未知';
                    }
                ?>
            </span>
            <label>集团供应商：</label>
            <span>
                <?php
                    if($viewData['group_spp'] == 'Y'){
                        echo '是';
                    }elseif($viewData['group_spp'] == 'N'){
                        echo '否';
                    }else{
                        echo '未知';
                    }
                ?>
            </span>
        </div>
        <div class="div_tab_display">
            <label>供应商类型：</label>
            <span><?=$viewData['spp_type']?><?=empty($viewData['spp_type_dsc'])?"":"(".$viewData['spp_type_dsc'].")"?></span>
            <label>供应商来源：</label>
            <span><?=$viewData['spp_source']?><?=empty($viewData['spp_source_dsc'])?"":"(".$viewData['spp_source_dsc'].")"?></span>
        </div>
        <div class="div_tab_display">
            <label>供应商地址：</label>
            <span style="width:750px;"><?=$viewData['sppAddr']?></span>
        </div>
    </div>
    <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>供应商详细信息</span>
    </h2>
    <div style="display:none;">
        <div class="div_tab_display">
            <label>供应商法人：</label>
            <span><?=$viewData['spp_legal_per']?></span>
            <label>供应商地位：</label>
            <span><?=$viewData['spp_position']?></span>
        </div>
        <div class="div_tab_display">
            <label>交易币别：</label>
            <span><?=$viewData['trade_cy']?></span>
            <label>年度营业额：</label>
            <span><?=$viewData['year_turn']?></span>
        </div>
        <div class="div_tab_display">
            <label>预计年销售额：</label>
            <span><?=$viewData['sale_turn']?></span>
            <label>预计年销售利润：</label>
            <span><?=$viewData['sale_profit']?></span>
        </div>
        <div class="div_tab_display">
            <label>交货条件：</label>
            <span><?=$viewData['delivery_cond']?></span>
            <label>付款条件：</label>
            <span><?=$viewData['pay_cond']?></span>
        </div>
        <div class="div_tab_display">
            <label>来源类别：</label>
            <span><?=$viewData['source_type']?></span>
        </div>
        <div class="div_tab_display">
            <label>主营范围：</label>
            <span style="width:750px;"><?=$viewData['main_business']?></span>
        </div>
        <div class="div_tab_display">
            <label>外部目标客戶：</label>
            <span style="width:750px;"><?=$viewData['target_customer']?></span>
        </div>
        <div class="div_tab_display">
            <label>客戶品质等级要求：</label>
            <span style="width:750px;"><?=$viewData['customer_quality']?></span>
        </div>
        <div class="mb-10 easyui-tabs" style="margin-left:30px;margin-right:30px;">
            <div title="供应商联系信息" style="display:none;">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width:40px;">序号</th>
                        <th style="width:222px;">联系人</th>
                        <th style="width:222px;">联系电话</th>
                        <th style="width:223px;">邮箱</th>
                        <th style="width:223px;">传真</th>
                    </tr>
                    </thead>
                    <tbody id="cont_tbody"></tbody>
                </table>
            </div>
            <div title="供应商主营商品" style="display:none;">
                <div style="overflow:auto;">
                    <table class="table" style="width:1060px;">
                        <thead>
                        <tr>
                            <th style="width:40px;">序号</th>
                            <th style="width:200px;">主营项目</th>
                            <th style="width:200px;">商品优势与不足</th>
                            <th style="width:200px;">销售渠道与区域</th>
                            <th style="width:100px;">年销售量(单位)</th>
                            <th style="width:100px;">市场份额(%)</th>
                            <th style="width:120px;">是否公开销售(Y/N)</th>
                            <th style="width:100px;">是否代理(Y/N)</th>
                        </tr>
                        </thead>
                        <tbody id="mpdt_tbody"></tbody>
                    </table>
                </div>
            </div>
            <div title="拟采购商品" style="display:none;">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width:40px;">序号</th>
                        <th style="width:168px;">商品料号</th>
                        <th style="width:218px;">商品名称</th>
                        <th style="width:168px;">规格型号</th>
                        <th style="width:218px;">商品类型</th>
                        <th style="width:118px;">单位</th>
                    </tr>
                    </thead>
                    <tbody id="purpdt_tbody"></tbody>
                </table>
            </div>
        </div>
    </div>
    <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>代理事项</span>
    </h2>
    <div style="display:none;">
        <div class="div_tab_display">
            <label>是否取得代理授权：</label>
            <span>
                <?php
                    if($viewData['agency_auth'] == 'Y'){
                        echo '是';
                    }elseif($viewData['agency_auth'] == 'N'){
                        echo '否';
                    }else{
                        echo '未知';
                    }
                ?>
            </span>
            <label>授权期限：</label>
            <span><?=$viewData['auth_stime']?><?=empty($viewData['auth_stime'])?'':'至'.$viewData['auth_etime']?></span>
        </div>
        <div class="div_tab_display">
            <label>代理等级：</label>
            <span><?=$viewData['agency_level']?></span>
            <label>授权商品类别：</label>
            <span><?=$viewData['auth_product']?></span>
        </div>
        <div class="div_tab_display">
            <label>授权区域：</label>
            <span><?=$viewData['auth_area']?></span>
            <label>授权范围：</label>
            <span><?=$viewData['auth_scope']?></span>
        </div>
        <div class="div_tab_display">
            <label>供应商主谈人：</label>
            <span><?=$viewData['spp_neg']?></span>
            <label>供应商主谈人职务：</label>
            <span><?=$viewData['spp_neg_p']?></span>
        </div>
        <div class="div_tab_display">
            <label>富金机主谈人：</label>
            <span><?=$viewData['fox_neg']?></span>
            <label>富金机主谈人分机：</label>
            <span><?=$viewData['fox_neg_t']?></span>
        </div>
        <div class="div_tab_display">
            <label>新增需求说明：</label>
            <span style="width:750px;"><?=$viewData['requ_desc']?></span>
        </div>
        <div class="div_tab_display">
            <label>优势：</label>
            <span style="width:750px;"><?=$viewData['advantage']?></span>
        </div>
        <div class="div_tab_display">
            <label>商机：</label>
            <span style="width:750px;"><?=$viewData['business']?></span>
        </div>
        <div class="div_tab_display">
            <label>未取得受理原因：</label>
            <span style="width:750px;"><?=$viewData['cause']?></span>
        </div>
    </div>
    <?php if($viewData['data_from']==1 && $viewData['spp_status']!=1){?>
        <h2 class="head-second" style="cursor:pointer;text-indent:5px;">
            <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>签核记录</span>
        </h2>
        <div style="margin:0 30px;display:none;">
            <table class="table">
                <thead>
                <tr>
                    <th style="width:30px;">#</th>
                    <th style="width:150px;">签核节点</th>
                    <th style="width:150px;">签核人员</th>
                    <th style="width:150px;">签核日期</th>
                    <th style="width:150px;">操作</th>
                    <th style="width:150px;">签核意见</th>
                    <th style="width:150px;">签核人IP</th>
                </tr>
                </thead>
                <tbody id="check_tbody"></tbody>
            </table>
        </div>
    <?php }?>
</div>
<script>
    $(function(){
        //模块显示隐藏
        $("h2").click(function(){
            if($(this).next().is(":visible")){
                $(this).next().hide();
                $(this).find("i").removeClass().addClass("icon-caret-right");
                setMenuHeight();
            }else{
                $(this).next().show();
                $(this).find("i").removeClass().addClass("icon-caret-down");
                if($(this).find("span").html() == '供应商详细信息'){
                    $(".easyui-tabs").tabs("resize");
                }
                setMenuHeight();
            }
        });

        //获取供应商联系人
        $.ajax({
            url:"<?=Url::to(['/spp/supplier/get-contacts'])?>",
            data:{"id":<?=$viewData['spp_id']?>},
            dataType:"json",
            success:function(data){
                $.each(data.rows,function(i,n){
                    var trStr="<tr>";
                    trStr+="<td>"+(i+1)+"</td>";
                    trStr+="<td>"+n.name+"</td>";
                    trStr+="<td>"+n.mobile+"</td>";
                    trStr+="<td>"+n.email+"</td>";
                    trStr+="<td>"+n.fax+"</td>";
                    trStr+="</tr>";
                    $("#cont_tbody").append(trStr);
                })
            }
        });

        //获取供应商主营商品
        $.ajax({
            url:"<?=Url::to(['/spp/supplier/get-main-product'])?>",
            data:{"id":<?=$viewData['spp_id']?>},
            dataType:"json",
            success:function(data){
                $.each(data.rows,function(i,n){
                    var trStr="<tr>";
                    trStr+="<td>"+(i+1)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.mian_pdt+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.pdt_ad+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+n.pdt_sca+"</td>";
                    trStr+="<td>"+n.sale_quan+"</td>";
                    trStr+="<td>"+(n.market_share==null?'':n.market_share)+"</td>";
                    trStr+="<td>"+n.open_sale+"</td>";
                    trStr+="<td>"+n.agency+"</td>";
                    trStr+="</tr>";
                    $("#mpdt_tbody").append(trStr);
                })
            }
        });

        //获取供应商拟采购商品
        $.ajax({
            url:"<?=Url::to(['/spp/supplier/get-purchase-product'])?>",
            data:{"id":<?=$viewData['spp_id']?>},
            dataType:"json",
            success:function(data){
                $.each(data.rows,function(i,n){
                    var trStr="<tr>";
                    trStr+="<td>"+(i+1)+"</td>";
                    trStr+="<td>"+n.part_no+"</td>";
                    trStr+="<td>"+n.pdt_name+"</td>";
                    trStr+="<td>"+n.tp_spec+"</td>";
                    trStr+="<td>"+n.category+"</td>";
                    trStr+="<td>"+n.unit+"</td>";
                    trStr+="</tr>";
                    $("#purpdt_tbody").append(trStr);
                })
            }
        });

        //获取签核记录
        <?php if($viewData['data_from']==1 && $viewData['spp_status']!=1){?>
        $.ajax({
            url:"<?=Url::to(['/spp/supplier/get-check-record'])?>",
            data:{"billId":<?=$viewData['spp_id']?>,'billTypeId':<?=$viewData['type_id']?>},
            dataType:"json",
            success:function(data){
                $.each(data.rows,function(i,n){
                    var trStr="<tr>";
                    trStr+="<td>"+(i+1)+"</td>";
                    trStr+="<td>"+n.organization_code+"</td>";
                    trStr+="<td>"+n.staff_name+"</td>";
                    trStr+="<td>"+(n.vcoc_datetime==null?'':n.vcoc_datetime)+"</td>";
                    trStr+="<td>"+(n.checkStatus==null?'':n.checkStatus)+"</td>";
                    trStr+="<td style='word-break:break-all;'>"+(n.vcoc_remark==null?'':n.vcoc_remark)+"</td>";
                    trStr+="<td>"+(n.vcoc_computeip==null?'':n.vcoc_computeip)+"</td>";
                    trStr+="</tr>";
                    $("#check_tbody").append(trStr);
                })
            }
        });
        <?php }?>

        //送审
        $("#check_btn").click(function(){
            var id="<?=$viewData['spp_id']?>";
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type="<?=$viewData['type_id']?>";
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480,
                afterClose:function(){
                    location.reload();
                }
            });
        });

        //审核操作
        <?php if(!empty($vco_id)){?>
        $("#pass").on("click", function () {
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/pass-opinion','id'=>$vco_id,'hvinvtype'=>$viewData['type_id']])?>",
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
        $("#reject").on("click", function () {
            $.fancybox({
                href: "<?=Url::to(['/system/verify-record/opinion','id'=>$vco_id,'hvinvtype'=>$viewData['type_id']])?>",
                type: 'iframe',
                padding: 0,
                autoSize: false,
                width: 435,
                height: 280,
                fitToView: false
            });
        });
        <?php }?>
    })
</script>