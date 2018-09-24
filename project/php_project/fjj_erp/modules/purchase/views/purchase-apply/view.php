<?php
use yii\helpers\Html;
use yii\helpers\Url;
use \app\classes\Menu;

$this->title = '请购单详情';
$this->params['homeLike'] = ['label' => '采购管理'];
$this->params['breadcrumbs'][] = ['label' => '商品请购列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
//dumpE($data);
?>
<style type="text/css">
    td p {
        display: block;
        overflow: hidden;
        word-break: break-all;
        word-wrap: break-word;
    }

    thead tr th p {
        color: white;
    }
    .width50{
        width: 50px;
    }
    .width200{
        width: 220px;
    }
    .width110{
        width: 110px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .width100{
        width: 100px;
    }
    .width150{
        width: 150px;
    }
    .width220{
        width: 220px;
    }
    .width270{
        width: 270px;
    }
    .head-second + div {
        display: none;
    }
</style>
<style type="text/css" media=print>
    @media print {
        .noprint{
            display: none;
        }
    }
</style>
<div class="content">
    <div class="mb-30">
        <h2 class="head-first">
            <?=$this->title ?>
            <span style="color: white;float: right;font-size:12px;margin-right:20px">请购单号：<?=$model['req_no']?></span>
        </h2>
        <div class="border-bottom mb-20">
            <?php if(empty($model['yn_can'])){?>
                <?php if($model['req_status']==30||$model['req_status']==34){?>
                    <?= Html::button('修改', ['class' => 'button-mody','id'=>'_edit']) ?> <!--'onclick' => 'window.location.href=\'' . Url::to(["edit",'id'=>$model['req_id']]) . '\'' -->
                    <?= Html::button('送审', ['class' => 'button-check', 'id'=>'check_btn']) ?> <!--\'' . Url::to(["update",'id'=>$model['req_id']]) . '\'-->
                <?php } ?>
            <?php } ?>
            <?= Html::button('切换列表', ['class' => 'button-change','onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
            <?= Html::button('打印', ['class' => 'button-blue width-80', 'onclick'=>'btnPrints()']) ?>
        </div>
<!--        <h2 class="head-second">-->
<!--            请购单信息-->
<!--        </h2>-->
        <!--startprint-->
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">请购单信息</a>
        </h2>
        <div>
        <div class="mb-10">
            <input type="hidden" id="_regid" value="<?= $id ?>">
            <label class="label-width qlabel-align width100 ml-20">请购单号<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['req_no'] ?></label>
            <label class="label-width qlabel-align width270">申请日期<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['app_date']?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">请购形式<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['req_rqf'] ?></label>
            <label class="label-width qlabel-align width270">单据类型<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['req_dct'] ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">采购区域<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['area_id'] ?></label>
            <label class="label-width qlabel-align width270">所属法人<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['leg_id']?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">请购部门<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['spp_dpt_id'] ?></label>
            <label class="label-width qlabel-align width270">申请人<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['app_id'] ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">联系方式<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['contact'] ?></label>
            <label class="label-width qlabel-align width270">配送地点<label>：</label></label>
            <label class="label-width text-left vertical-center  width200"><?= $model['addr']?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">采购方式<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['req_type'] ?></label>
            <label class="label-width qlabel-align width270">费用类型<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['cst_type'] ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">币别<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['cur_id'] ?></label>
            <label class="label-width qlabel-align width270">合同协议号<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['agr_code'] ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">e商贸部门<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['e_dpt_id'] ?></label>
            <label class="label-width qlabel-align width270">来源<label>：</label></label>
            <label class="label-width text-left vertical-center width200"><?= $model['scrce'] ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">是否领用人<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_lead'] == 1 ? '是' : '否' ?></label>
            <label class="label-width qlabel-align width270">多部门领用<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_mul_dpt'] == 1 ? '是' : '否'  ?></label>
        </div>
        <?php if($model['yn_lead']==0){ ?>
            <div class="mb-10 fd">
                    <label class="label-width qlabel-align width100 ml-20">领用人<label>：</label></label>
                    <label class="label-width text-left vertical-center width200"><?= $model['recer'] ?></label>
                    <label class="label-width qlabel-align width270">联系方式<label>：</label></label>
                    <label class="label-width text-left vertical-center width200"><?= $model['rec_cont'] ?></label>
            </div>
        <?php } ?>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">总务备品<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_aff'] == 1 ? '是' : '否'  ?></label>
            <label class="label-width qlabel-align width270">是否三方贸易<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_three'] == 1 ? '是' : '否' ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">是否设备部预算<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_eqp_budget'] == 1 ? '是' : '否'?></label>
            <label class="label-width qlabel-align width270">是否已做低值易耗品判断<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_low_value'] == 1 ? '是' : '否' ?></label>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">是否做固资管控<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_fix_cntrl'] == 1 ? '是' : '否'  ?></label>
            <label class="label-width qlabel-align width270">是否来自需求单<label>：</label></label>
            <label class="label-width text-left width200"><?= $model['yn_req'] == 1 ? '是' : '否'  ?></label>
        </div>
        <?php if($model['req_rqf_id']=='100911'){?>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">专案代码<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['prj_code'] ?></label>
            </div>
         <?php } ?>
        <?php if($model['req_dct_id']!='109018'){ ?>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">采购部门<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['req_dpt_id']?></label>
            </div>
        <?php } ?>
        <div class="mb-10">
            <label class="label-width qlabel-align width100 ml-20">请购原因/用途<label>：</label></label>
            <label class="label-width text-left vertical-center" style="width:700px"><?= $model['remarks'] ?></label>
        </div>
        <?php if($model['yn_can']!=0){?>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">取消原因<label>：</label></label>
                <label class="label-width text-left vertical-center" style="width:700px"><?= $model['can_rsn'] ?></label>
            </div>
        <?php } ?>
        </div>
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-right"></i>
            <a href="javascript:void(0)">商品信息</a>
        </h2>
        <div class="mb-20" style="overflow: auto">
        <div style="width:100%;overflow: auto;">
            <table class="table" style="width: 2500px;">
            <thead>
            <tr style="height: 50px">
                <th width="50">序号</th>
                <th width="100">料号</th>
                <th width="100">品名</th>
                <th width="100">规格</th>
                <th width="100">品牌</th>
                <th width="100">单位</th>
                <th width="100">请购量</th>
<!--                <th width="100">单价</th>-->
<!--                <th width="100">供应商代码</th>-->
<!--                <th width="100">金额</th>-->
                <th width="100">费用科目</th>
                <th width="100">需求日期</th>
                <th width="100">专案编号</th>
<!--                <th width="100">剩余预算</th>-->
<!--                <th width="100">原币单价</th>-->
<!--                <th width="100">退税率</th>-->
                <th width="100">备注</th>
            </tr>
            </thead>
            <tbody id="product_table">
            <?php foreach ($pdtmodel as $key => $val) { ?>
                <tr style="height: 50px;">
                    <td><p class="width-40"><?= ($key + 1) ?></p></td>
                    <td><p class="width-80"><?= $val["part_no"] ?></p></td>
                    <td><p class="width-80"><?= $val["pdt_name"] ?></p></td>
                    <td><p class="width-80"><?= $val["tp_spec"] ?></p></td>
                    <td><p class="width-80"><?= $val["brand"] ?></p></td>
                    <td><p class="width-80"><?= $val["unit"] ?></p></td>
                    <td><p class="width-80"><?= $val["req_nums"] ?></p></td>
<!--                    <td><p class="width-80">--><?//=number_format( $val["req_price"],5)?><!--</p></td>-->
<!--                    <td><p class="width-80">--><?//= $val["spp_id"] ?><!--</p></td>-->
<!--                    <td><p class="width-80">--><?//=number_format($val["total_amount"],2) ?><!--</p></td>-->
                    <td><p class="width-80"><?= $val["bs_req_dt"] ?></p></td>
                    <td><p class="width-80"><?= $val["req_date"] ?></p></td>
                    <td><p class="width-80"><?= $val["prj_no"] ?></p></td>
<!--                    <td><p class="width-80">--><?//= $val["sonl_remark"] ?><!--</p></td>-->
<!--                    <td><p class="width-80">--><?//= $val["org_price"] ?><!--</p></td>-->
<!--                    <td><p class="width-80">--><?//= $val["rebat_rate"] ?><!--</p></td>-->
                    <td><p class="width-80"><?= $val["remarks"] ?></p></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
        <!--endprint-->
        <!--startprint-->
        <div class="mb-20" style="overflow: auto; margin-top: 10px">
            <?php if (!empty($verify)){ ?>
            <div >
                <h2 class="head-second color-1f7ed0">
                    <i class="icon-caret-right"></i>
                    <a href="javascript:void(0)">签核信息</a>
                </h2>
                <div>
                <table class="mb-30 product-list" style="width:990px;">
                    <thead>
                    <tr>
                        <th class="width-60">#</th>
                        <th class="width-70">签核节点</th>
                        <th class="width-60">签核人员</th>
                        <th>签核日期</th>
                        <th class="width-60">操作</th>
                        <th>签核意见</th>
                        <th>签核人IP</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($verify as $key=>$val){ ?>
                        <tr>
                            <th><?= $key+1 ?></th>
                            <th><?= $val['verifyOrg'] ?></th>
                            <th><?= $val['verifyName'] ?></th>
                            <th><?= $val['vcoc_datetime'] ?></th>
                            <th><?= $val['verifyStatus'] ?></th>
                            <th><?= $val['vcoc_remark'] ?></th>
                            <th><?= $val['vcoc_computeip'] ?></th>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        </div>
    </div>
        <!--endprint-->
        <script>
            $(function () {
                $(".head-second").next("div:eq(0)").css("display", "block");
                $(".head-second>a").click(function () {
                    $(this).parent().next().slideToggle();
                    $(this).prev().toggleClass("icon-caret-down");
                    $(this).prev().toggleClass("icon-caret-right");
                    $(".retable").datagrid("resize");
                });
                var app_id="<?=$model["app_id"]?>";
                var recer="<?=$model["recer"]?>";
                if(app_id==recer){
                    $(".fd").css("display",'none');
                }

                //送审20171127wxt
                $("#check_btn").click(function () {
                    var id=$("#_regid").val();
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=54;
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
                //修改
                $("#_edit").click(function () {
                    var _id=$("#_regid").val();
                    window.location.href="<?=Url::to(['edit'])?>?id="+_id;
                });

            });
            /*表格打印*/
            function btnPrints(){
                var _id=$("#_regid").val();
                $.fancybox({
                    padding: [],
                    fitToView: true,
                    width:1000,
                    height: 800,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe',
                    href: "<?= Url::to(['prview'])?>?id="+_id
                });
//                $('.content').jqprint({
//                    debug: false,
//                    importCSS: true,
//                    printContainer: true,
//                    operaSupport: false
//                })
//                bdhtml=window.document.body.innerHTML;
//                sprnstr="<!--startprint-->";
//                eprnstr="<!--endprint-->";
//                prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17);
//                prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
//                window.document.body.innerHTML=prnhtml;
//                window.print();
//                // 还原界面
//                window.document.body.innerHTML = bdhtml
//                window.location.reload();
            }
        </script>

