<?php
/**
 * 需求詳情頁
 * F3858995
 * 2016/9/27
 */
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\classes\Menu;

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品开发计划'];
$this->params['breadcrumbs'][] = ['label' => '商品开发需求详情'];
$this->title = '商品开发需求详情';
?>
<div class="content">
    <h1 class="head-first">
        <?= $this->title ?>
        <span class="head-code">编号：<?= $model['pdq_code'] ?></span>
    </h1>
    <div class="border-bottom mb-20">
        <?php if ($model['pdq_status'] == 10) { ?>
            <?= Menu::isAction('/ptdt/product-dvlp/edit') ? Html::button('修改', ['class' => 'button-blue width-80', 'id' => 'edit']) : '' ?>
            <?= Menu::isAction('/ptdt/product-dvlp/delete') ? Html::button('删除', ['class' => 'button-blue', 'id' => 'delete']) : '' ?>
            <?= Menu::isAction('/ptdt/product-dvlp/check') ? Html::button('送审', ['class' => 'button-blue width-80', 'id' => 'check']) : '' ?>
        <?php }else{ ?>
        <?= Menu::isAction('/ptdt/product-dvlp/check') ? Html::button('审核记录', ['class' => 'button-blue width-80', 'id' => 'checkView']) : '' ?>
        <?php } ?>
        <?= Html::button('切换列表', ['class' => 'button-blue width-100', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        <div class="mb-10"></div>
    </div>
    <h2 class="head-second mt-20">
        商品经理人
    </h2>
    <div class="mb-100">
        <div>
            <label class='width-100'>开发中心</label><span
                    class="width-200"><?= $model['developCenterName'] ?></span>
            <label class='width-100'>开发部</label><span
                    class="width-200"><?= $model['developDepartmentName'] ?></span>
            <label class='width-100'>商品经理人</label><span
                    class="width-200"><?= $model['productManagerName']['name'] ?>
                -<?= $model['productManagerName']['code'] ?></span>
        </div>
        <div class="space-40"></div>
        <div>
            <label class='width-100'>需求类型</label><span
                    class="width-200"><?= $model['pdqSourceTypeName'] ?></span>
            <label class='width-100'>开发类型</label><span
                    class="width-200"><?= $model['developTypeName'] ?></span>
            <label class='width-100'>Commodity</label><span
                    class="width-200"><?= $model['commodityName'] ?>
        </div>
        <!--<div>
            <label class='width-100'>单据状态</label><span
                class="width-200"><? /*= $model->status */ ?></span>
            <label class='width-100'>计划建立人</label><span
                class="width-200"><? /*= $model->createBy->code."-".$model->createBy->name */ ?></span>
        </div>-->
    </div>
    <h2 class="head-second mt--40">
        商品基本信息
    </h2>
    <div class="margin-auto">
        <div>
            <label class='width-100'>商品类别</label>
            <span><?= $model['products'][0]['typeStr'] ?></span>
            <div class="space-10"></div>
            <label class='width-100'>商品品名</label><span
                    class="width-200 text-top"><?= $model['products'][0]['product_name'] ?></span>
            <label class='width-100'>规格型号</label><span
                    class="width-200 text-top"><?= $model['products'][0]['product_size'] ?></span>
            <label class='width-100'>商品定位</label><span
                    class="width-200 text-top"><?= $model['products'][0]['levelName'] ?></span>
            <div class="space-10"></div>
            <label class='width-100 '>商品要求</label><span
                    class="width-500 text-top"><?= $model['products'][0]['product_requirement'] ?></span>
            <div class="space-10"></div>
            <label class='width-100'>制程要求</label><span
                    class="width-500 text-top"><?= $model['products'][0]['product_process_requirement'] ?></span>
            <div class="space-10"></div>
            <label class='width-100'>品质要求</label><span
                    class="width-500 text-top"><?= $model['products'][0]['product_quality_requirement'] ?></span>
        </div>
        <div class="float-right" style="margin-top: -10px;">
            <?= Html::a('收起...', null, ['id' => 'retract', 'class' => 'display-none']) ?>
        </div>
        <div id="load_products"></div>
    </div>
    <div class="float-right">
        <?php if (count($model['products']) > 1) { ?>
            <?= Html::a('更多...', null, ['id' => 'more']) ?>
        <?php } ?>
    </div>
</div>
<script>
    $(function () {
        //获取商品信息
        var products =<?php
            unset($model['products'][0]);
            echo \yii\helpers\Json::encode($model['products'])
            ?>
                /*点击更多*/
            $("#retract").on("click", function () {
                $("#more").show();
                $("#retract").hide();
                $("#load_products").html("");
            })
        $("#more").on("click", function () {
            $("#more").hide();
            $("#retract").show();
            var mr20 = "<span class='mr-20'></span>";
            $.each(products, function (i, item) {
                var typeName0 = item.typeStr == undefined ? '' : item.typeStr;
                var product_name = item.product_name == undefined ? '' : item.product_name;
                var product_size = item.product_size == undefined ? '' : item.product_size;
                var levelName = item.levelName == undefined ? '' : item.levelName;
                var product_requirement = item.product_requirement == undefined ? '' : item.product_requirement;
                var product_process_requirement = item.product_process_requirement == undefined ? '' : item.product_process_requirement;
                var product_quality_requirement = item.product_quality_requirement == undefined ? '' : item.product_quality_requirement;

                var product_one = "<div class='space-10'></div><div class='nav-line'></div></div><div>" +
                    "<label class='width-100'>商品类别</label>" +
                    "<span>" + typeName0 + "</span>" +
                    "<div class='space-10'></div>" +
                    "<label class='width-100 text-top'>商品品名</label>" +
                    "<span class='width-200'>" + product_name + "</span>" +
                    "<label class='width-100 text-top'>规格型号</label>" +
                    "<span class='width-200'>" + product_size + "</span>" +
                    "<label class='width-100'>商品定位</label>" +
                    "<span class='width-200 text-top'>" + levelName + "</span>" +
                    "<div class='space-10'></div>" +
                    "<label class='width-100 '>商品要求</label>" +
                    "<span class='width-500 text-top'>" + product_requirement + "</span>" +
                    "<div class='space-10'></div>" +
                    "<label class='width-100'>制程要求</label>" +
                    "<span class='width-500 text-top'>" + product_process_requirement + "</span>" +
                    "<div class='space-10'></div>" +
                    "<label class='width-100'>品质要求</label>" +
                    "<span class='width-500 text-top'>" + product_quality_requirement + "</span></div>"
                $("#load_products").append(product_one)
            });
        });

        /*送审*/
        $("#check").on("click",function(){
            var id= <?= $model['pdq_id'] ?>;
            var url="<?=Url::to(['view'],true)?>?id="+id;
            var type=11;
            $.fancybox({
                href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:750,
                height:480
            });
        });

        //审核记录
        $("#checkView").on("click", function () {
            var id = <?=$model['pdq_id']?>;
            var status = <?=$model['pdq_status']?>;
            if (status == 10) {
                layer.alert("需求未送审,无法查看审核记录", {icon: 2, time: 5000});
            } else {
                window.location.href = "<?=Url::to(['check-view'])?>?id=" + id;
                return false;
            }
        });
        /*编辑*/
        $("#edit").on("click", function () {
            var selectId = <?=$model['pdq_id']?>;
            var pdq_status = <?=$model['pdq_status']?>;
            var url = "<?=Url::to(['edit'])?>?id=" + selectId;
            if (pdq_status == 20) {
                layer.alert("需求审核中,不能编辑", {icon: 2, time: 5000});
            } else {
                window.location.href = url;
            }
        });
        /*删除*/
        $("#delete").on("click", function () {
            selectId = <?=$model['pdq_id']?>;
            $.ajax({
                type: "get",
                dataType: "json",
                data: {"id": selectId},
                url: "<?=Url::to(['/ptdt/product-dvlp/delete-count']) ?>",
                success: function (msg) {
                    if (msg === false) {
                        layer.alert('无法删除', {icon: 2})
                    } else {
                        layer.confirm("确定要删除这条记录吗",
                            {
                                btn: ['确定', '取消'],
                                icon: 2
                            },
                            function () {
                                $.ajax({
                                    type: "get",
                                    dataType: "json",
                                    data: {"id": selectId},
                                    url: "<?=Url::to(['/ptdt/products-dvlp/delete']) ?>",
                                    success: function (msg) {
                                        if (msg.flag === 1) {
//                                    layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                                            layer.alert(msg.msg, {
                                                icon: 1, end: function () {
                                                    location.href = '<?= Url::to(['index']) ?>'
                                                }
                                            });
                                        } else {
                                            layer.alert(msg.msg, {icon: 2})
                                        }
                                    },
                                    error: function (msg) {
                                        layer.alert(msg.msg, {icon: 2})
                                    }
                                })
                            },
                            function () {
                                layer.closeAll();
                            }
                        )
                    }
                }
            })
        });
    })
</script>