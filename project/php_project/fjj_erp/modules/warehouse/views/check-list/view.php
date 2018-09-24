<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '盘点单详情';
$this->params['homeLike'] = ['label' => '仓库物流管理'];
$this->params['breadcrumbs'][] = ['label' => '盘点单列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
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
            <span style="color: white;float: right;font-size:12px;margin-right:20px">盘点单号：<?=$model['ivt_code']?></span>
        </h2>
        <div class="border-bottom mb-20">
                <?php if($model['state']==1){?>
                    <?= Html::button('修改', ['class' => 'button-mody','id'=>'_edit']) ?>
                    <?= Html::button('送审', ['class' => 'button-check', 'id'=>'send']) ?>
                    <?= Html::button('切换列表', ['class' => 'button-check', 'id'=>'check_btn','Url'=>"check_btn"]) ?>
                <?php } ?>
                <?php if($model['state']==4){?>
                    <?= Html::button('修改', ['class' => 'button-mody','id'=>'_edit']) ?>
                    <?= Html::button('送审', ['class' => 'button-check', 'id'=>'send']) ?>
                    <?= Html::button('切换列表', ['class' => 'button-check', 'id'=>'check_btn','Url'=>"check_btn"]) ?>
                <?php } ?>
            <?php if($model['state']==2){?>
                <?= Html::button('修改', ['class' => 'button-mody','id'=>'_edit']) ?>
                <?= Html::button('添加复盘信息', ['class' => 'button-check','style'=>'width:90px', 'id'=>'add-msg']) ?>
                <?= Html::button('切换列表', ['class' => 'button-check', 'id'=>'check_btn']) ?>
            <?php } ?>
                <?php if(($model['state']==0)||($model['state']==3)){?>
                    <?= Html::button('切换列表', ['class' => 'button-check', 'id'=>'check_btn']) ?>
                <?php } ?>
                <?php if($model['state']==5){?>
                    <?= Html::button('切换列表', ['class' => 'button-check', 'id'=>'check_btn']) ?>
                <?php } ?>
        </div>

        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-down"></i>
            <a href="javascript:void(0)">盘点单信息</a>
        </h2>
        <div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">法人<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['company_name'] ?></label>
                <label class="label-width qlabel-align width270">期别<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['stage']?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">币别<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['cur_code'] ?></label>
                <label class="label-width qlabel-align width270">仓库名称<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['wh_name'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">仓库代码<label>：</label></label>
                <label class="label-width text-left width200"><?= $model['wh_code'] ?></label>
                <label class="label-width qlabel-align width270">库存截止时间<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['expiry_date'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">初盘人<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['first_ivtor']?></label>
                <label class="label-width qlabel-align width270">初盘日期<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['first_date'] ?></label>
            </div>
            <div class="mb-10">
                <label class="label-width qlabel-align width100 ml-20">复盘人<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['re_ivtor'] ?></label>
                <label class="label-width qlabel-align width270">复盘日期<label>：</label></label>
                <label class="label-width text-left vertical-center width200"><?= $model['re_date'] ?></label>
            </div>
        <h2 class="head-second color-1f7ed0">
            <i class="icon-caret-right"></i>
            <a href="javascript:void(0)">盘点商品信息</a>
        </h2>
        <div class="mb-20" style="overflow: auto">

            <div style="width:100%;overflow: auto;">
                <table class="table" style="width: 2500px;">
                    <thead>
                    <tr style="height: 50px">
                        <th width="50">序号</th>
                        <th width="100">料号</th>
                        <th width="100">品名</th>
                        <th width="100">规格型号</th>
                        <th width="100">单位</th>
                        <th width="100">成本单价</th>
                        <th width="100">库存数量</th>
                        <th width="100">初盘数量</th>
                        <th width="100">复盘数量</th>
                        <th width="100">盈亏数量</th>
                        <th width="100">盈亏金额</th>
                        <th width="100">初盘备注</th>
                        <th width="100">复盘备注</th>
                    </tr>
                    </thead>
                    <tbody id="product_table">
                    <?php foreach ($pdtmodel as $key => $val) { ?>
                        <tr style="height: 50px;">
                            <td><p class="width-40"><?= ($key + 1) ?></p></td>
                            <td><p class="width-80"><?= $val["part_no"] ?></p></td>
                            <td><p class="width-80"><?= $val["pdt_name"] ?></p></td>
                            <td><p class="width-80"><?= $val["tp_spec"] ?></p></td>
                            <td><p class="width-80"><?= $val["unit"] ?></p></td>
                            <td><p class="width-80"><?= $val["notax_price"] ?></p></td>
                            <td><p class="width-80"><?= $val["invt_num"] ?></p></td>
                            <td><p class="width-80"><?= $val["first_num"]?></p></td>
                            <td><p class="width-80"><?= $val["re_num"]?></p></td>
                            <td><p class="width-80"><?= $val["lose_num"] ?></p></td>
                            <td><p class="width-80"><?= $val["lose_price"] ?></p></td>
                            <td><p class="width-80"><?= $val["remarks"] ?></p></td>
                            <td><p class="width-80"><?= $val["remarks1"] ?></p></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</div>
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

                //送审20171218wxt
                $("#send").click(function () {
//                    alert(<?//=$downlist['business'][0]['business_type_id']?>//);
                    var id=<?=$model['ivt_id']?>;
                    var code='<?=$model['ivt_code']?>';
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=<?=$downlist['business'][0]['business_type_id']?>;
                    $.fancybox({
                        href:"<?=Url::to(['/warehouse/check-list/reviewer'])?>?type="+type+"&id="+id+"&url="+url+"&code="+code,
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
                $("#check_btn").click(function () {
                    window.location.href="<?=Url::to(['index'])?>";
                });
                //修改
                $("#_edit").click(function () {
                    var _id="<?= $model['ivt_id']?>";
                    var code="<?=$model['ivt_code']?>";
                    window.location.href="<?=Url::to(['edit'])?>?id="+_id+'&code='+code;
                });
                //添加复盘信息
                $("#add-msg").click(function () {
                    var id = "<?=$model['ivt_id']?>";
                    var code = "<?=$model['ivt_code']?>";
                    var url="<?=Url::to(['add-msg'])?>";
                    url+='?id='+id;
                    url+='&code='+code;
                    $.fancybox({
                        href: url,
                        type: "iframe",
                        padding: 0,
                        autoSize: false,
                        width: 800,
                        height: 600
                    });
                });
            });
        </script>

