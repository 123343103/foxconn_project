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
    $this->title='入库单详情页';
    $this->params['homeLike']=['label'=>'仓储物流管理'];
    $this->params['breadcrumbs'][]=['label'=>'其他入库单列表','url'=>'list'];
    $this->params['breadcrumbs'][]=$this->title;
}else{
    $this->title='其他入库审核';
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
    <h1 class="head-first">入库单详情页</h1>
    <?php if(empty($vco_id)){?>
        <div class="mb-10">
            <?php if(empty($viewData['inout_type'])){?>
            <?php if($viewData['invh_status']=="待提交" || $viewData['invh_status']=="驳回"){?>
                <?php if(Menu::isAction('/warehouse/other-stock-in/edit')){?>
                    <button type="button" class="button-blue" onclick="location.href='<?=Url::to(['edit','id'=>$viewData['invh_id']])?>'">修改</button>
                <?php }?>
                <?php if(Menu::isAction('/warehouse/other-stock-in/check')){?>
                    <button id="check_btn" type="button" class="button-blue">送审</button>
                <?php }?>
            <?php }?>
            <?php }?>
            <button type="button" class="button-blue" style="width:80px;" onclick="location.href='<?=Url::to(['list'])?>'">切换列表</button>
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
    <h2 class="head-second">其他入库单信息</h2>
    <?php if($viewData['inout_type']==2){?>
        <div class="div_tab_display">
            <label>入库单号：</label>
            <span><?=$viewData['invh_code']?></span>
            <label>关联单号：</label>
            <span><?=$viewData['invh_aboutno']?></span>
        </div>
        <div class="div_tab_display">
            <label>单据状态：</label>
            <span><?=$viewData['invh_status']?></span>
            <label>单据类型：</label>
            <span>调拨</span>
        </div>
        <div class="div_tab_display">
            <label>调拨类型：</label>
            <span><?=$viewData['chh_type']?></span>
            <label>调拨单位：</label>
            <span><?=$viewData['depart_name']?></span>
        </div>
        <div class="div_tab_display">
            <label>调出仓库：</label>
            <span><?=$viewData['o_wh_name']?></span>
            <label>调出仓库代码：</label>
            <span><?=$viewData['o_wh_code']?></span>
        </div>
        <div class="div_tab_display">
            <label>调入仓库：</label>
            <span><?=$viewData['i_wh_name']?></span>
            <label>调入仓库代码：</label>
            <span><?=$viewData['i_wh_code']?></span>
        </div>
        <div class="div_tab_display">
            <label>出库状态：</label>
            <span><?=$viewData['o_status']?></span>
            <label>入库状态：</label>
            <span><?=$viewData['in_status']?></span>
        </div>
    <?php }elseif($viewData['inout_type']==3){?>
        <div class="div_tab_display">
            <label>入库单号：</label>
            <span><?=$viewData['invh_code']?></span>
            <label>关联单号：</label>
            <span><?=$viewData['invh_aboutno']?></span>
        </div>
        <div class="div_tab_display">
            <label>单据状态：</label>
            <span><?=$viewData['invh_status']?></span>
            <label>单据类型：</label>
            <span>移仓</span>
        </div>
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
    <?php }else{?>
        <div class="div_tab_display">
            <label>入库单号：</label>
            <span><?=$viewData['invh_code']?></span>
            <label>关联单号：</label>
            <span><?=$viewData['invh_aboutno']?></span>
        </div>
        <div class="div_tab_display">
            <label>单据状态：</label>
            <span><?=$viewData['invh_status']?></span>
            <label>单据类型：</label>
            <span><?=$viewData['inout_flag_val']?></span>
        </div>
        <div class="div_tab_display">
            <label>入仓仓库：</label>
            <span><?=$viewData['wh_name']?></span>
            <label>仓库代码：</label>
            <span><?=$viewData['wh_code']?></span>
        </div>
        <div class="div_tab_display">
            <label>仓库属性：</label>
            <span><?=$viewData['wh_attr']?></span>
            <label>预收货日期：</label>
            <span><?=$viewData['recive_date']?></span>
        </div>
        <div class="div_tab_display">
            <label>送货人：</label>
            <span><?=$viewData['invh_sendperson']?></span>
            <label>联系方式：</label>
            <span><?=$viewData['invh_sendaddress']?></span>
        </div>
        <div class="div_tab_display">
            <label>收货人：</label>
            <span><?=$viewData['invh_reperson']?></span>
            <label>联系方式：</label>
            <span><?=$viewData['invh_repaddress']?></span>
        </div>
        <div class="div_tab_display">
            <label>备注：</label>
            <span style="width:750px;"><?=$viewData['invh_remark']?></span>
        </div>
        <?php if(!empty($viewData['can_reason'])){?>
            <div class="div_tab_display">
                <label>取消原因：</label>
                <span style="width:750px;"><?=$viewData['can_reason']?></span>
            </div>
        <?php }?>
    <?php }?>
    <h2 class="head-second">入库商品信息</h2>
    <div id="datagrid1" style="width:100%;"></div>
    <?php if(empty($viewData['inout_type']) && $viewData['invh_status']!="待提交"){?>
        <h2 class="head-second" style="margin-top:20px;">签核记录</h2>
        <div id="datagrid2" style="width:100%;"></div>
    <?php }?>
</div>
<script>
    $(function(){
        var columns=[];
        if("<?=$viewData['inout_type']?>"=="2"){//调拨
            columns=[[
                {field:"part_no",title:"料号",width:150},
                {field:"pdt_name",title:"品名",width:200},
                {field:"brand",title:"品牌",width:100},
                {field:"tp_spec",title:"规格/型号",width:150},
                {field:"ord_id",title:"批次",width:100},
                {field:"invt_num",title:"现有库存量",width:100},
                {field:"delivery_num",title:"调拨数量",width:100},
                {field:"before_stno",title:"出仓储位",width:100},
                {field:"unit",title:"单位",width:100},
                {field:"real_quantity",title:"入库数量",width:100},
                {field:"st_codes",title:"储位",width:200},
                {field:"inout_time",title:"上架日期",width:100}
            ]];
        }else{
            if("<?=$viewData['inout_type']?>"=="3"){//移仓
                columns=[[
                    {field:"part_no",title:"料号",width:150},
                    {field:"pdt_name",title:"品名",width:200},
                    {field:"brand",title:"品牌",width:100},
                    {field:"tp_spec",title:"规格/型号",width:150},
                    {field:"ord_id",title:"批次",width:100},
                    {field:"before_stno",title:"移仓前储位",width:100},
                    {field:"chwh_num",title:"移仓数量",width:100},
                    {field:"unit",title:"单位",width:100},
                    {field:"real_quantity",title:"入库数量",width:100},
                    {field:"st_codes",title:"储位",width:200},
                    {field:"inout_time",title:"上架日期",width:100}
                ]];
            }else{//新增
                columns=[[
                    {field:"part_no",title:"料号",width:150},
                    {field:"pdt_name",title:"品名",width:200},
                    {field:"brand",title:"品牌",width:100},
                    {field:"tp_spec",title:"规格/型号",width:150},
                    {field:"batch_no",title:"收货批次",width:100},
                    {field:"in_quantity",title:"送货数量",width:100},
                    {field:"real_quantity",title:"预实收数量",width:100},
                    {field:"unit",title:"单位",width:100},
                    {field:"pack_type",title:"包装方式",width:100},
                    {field:"pack_num",title:"包装件数",width:100},
                    {field:"st_codes",title:"储位",width:200}
                ]];
            }
        }
        $("#datagrid1").datagrid({
            url:"<?=Url::to(['/warehouse/other-stock-in/get-products'])?>",
            queryParams:{
                "id":"<?=$viewData['invh_id']?>",
                "inout_type":"<?=$viewData['inout_type']?>"
            },
            rownumbers:true,
            method:"get",
            singleSelect:true,
//            pagination:true,
            columns:columns,
            onLoadSuccess: function (data) {
                datagridTip("#datagrid1");
                showEmpty($(this), data.total, 0);
                setMenuHeight();
            }
        });

        //获取签核记录
        <?php if(empty($viewData['inout_type']) && $viewData['invh_status']!="待提交"){?>
        $("#datagrid2").datagrid({

            url:"<?=Url::to(['/warehouse/other-stock-in/get-check-record'])?>",
            queryParams:{
                "billId":<?=$viewData['invh_id']?>,
                'billTypeId':<?=$viewData['inout_flag']?>
            },
            rownumbers:true,
            method:"get",
            singleSelect:true,
//            pagination:true,
            columns:[[
                {field:"organization_code",title:"签核节点",width:150},
                {field:"staff_name",title:"签核人员",width:150},
                {field:"vcoc_datetime",title:"签核日期",width:150},
                {field:"checkStatus",title:"操作",width:150},
                {field:"vcoc_remark",title:"签核意见",width:159},
                {field:"vcoc_computeip",title:"签核人IP",width:150}
            ]],
            onLoadSuccess: function (data) {
                datagridTip("#datagrid2");
                showEmpty($(this), data.total, 0);
                setMenuHeight();
            }
        });
        <?php }?>

        //送审
        $("#check_btn").click(function(){
            var id="<?=$viewData['invh_id']?>";
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type="<?=$viewData['inout_flag']?>";
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
                href: "<?=Url::to(['/system/verify-record/pass-opinion','id'=>$vco_id,'hvinvtype'=>$viewData['inout_flag']])?>",
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
                href: "<?=Url::to(['/system/verify-record/opinion','id'=>$vco_id,'hvinvtype'=>$viewData['inout_flag']])?>",
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