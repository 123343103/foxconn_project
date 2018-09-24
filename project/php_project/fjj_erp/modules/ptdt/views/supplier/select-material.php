<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
AppAsset::register($this);
?>

<div class="pop-head">
    <p>交易产品选择</p>
</div>
<div class="select-com">
    <div style="height:30px;">

        <?php $form = ActiveForm::begin([
            'action' => ['/ptdt/supplier/select-material'],
            'method' => 'get',
        ]); ?>
        <p class="float-left">
            <input type="text" name="PdFirmQuery[firmMessage]" class="width-200"><img id="img" src="<?= Url::to('@web/img/icon/search.png') ?>" title="搜索">
            <input type="submit" id="sub" style="display:none;">
        </p>
        <?= Html::resetButton('重置', ['class' => 'button-blue','style'=>'height:30px;margin-left:10px;' ,'type' => 'reset','onclick'=>'window.location.href=\''.Url::to(["select-material"]).'\'']) ?>
        <!--        <p class="float-right mt-5"><a href="--><?//= Url::to(['/ptdt/firm/create']); ?><!--" target="_blank"><button type="button" class="button-blue text-center" style="width:80px;">新增厂商</button></a></p>-->
    </div>
    <div class="space-10"></div>
    <?php ActiveForm::end(); ?>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'prevPageLabel' => '<',
            'nextPageLabel' => '>',
            'firstPageLabel' => "首页",
            'lastPageLabel' => '尾页',
            'maxButtonCount' => 2,
            'showSelectPage'=>false,
        ],
        'options' => ['id' => "requirement-index"],
        'tableOptions' => ['class' => 'table fancybox-table '],
        'layout' => "{items}\n<div class='text-right mt-10'>{pager}</div></div>",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'header'=>'序号'
            ],
            'material_code',
            'pro_main_type_id',
            'pro_name',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <div class="text-center mt-10">
        <button class="button-blue-big" id="check">确定</button>
        <button class="button-white-big ml-20" id="close">取消</button>
    </div>
    <div class="space-10"></div>
</div>
<script>
    $(function () {
        $(document).on("click", '#requirement-index tbody > tr', function () {
            $('.table-click-tr').removeClass('table-click-tr');
            $(this).addClass('table-click-tr');
            var id = $(this).attr("data-key");
            $("#check").click(function () {
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    data: {"id": id},
                    url: "<?= Url::to(['/ptdt/supplier/material-info']) ?>",
                    success:function(data){
//                        window.parent.$(".material_body_tr").remove();
                        var tdStr = "<tr data-key="+ data[0].m_id +" >";
                        tdStr += "<td>" + data[0].material_code + "</td>";
                        tdStr += "<td>" + data[0].pro_name + "</td>";
                        tdStr += "<td>" + data[0].pro_size + "</td>";
                        tdStr += "<td>" + data[0].pro_main_type_id + "</td>";
                        tdStr += "<td>" + data[0].pro_sku + "</td>";
                        tdStr += "<td>" + '<a onclick="delTr(this)">删除</a>' + "</td>";
                        tdStr += '<input type="hidden" name="material[]" value="' + data[0].m_id + '">';
                        tdStr += "</tr>";
                        window.parent.$(".material_body_tr").insertAfter(window.parent.$('#material_body'));
                        window.parent.$('#material_body').append(tdStr);
                        parent.$.fancybox.close();
                    }
                })
            })
        })
        $("#close").click(function(){
            parent.$.fancybox.close();
        })
        $("#img").click(function(){
            $("#sub").click();
        })


    })
</script>
