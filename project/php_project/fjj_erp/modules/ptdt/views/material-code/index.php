<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/20
 * Time: 上午 11:39
 */
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\hr\models\HrStaff;
$this->params['homeLike'] = ['label'=>'料号管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'料号申请列表'];
?>
<div class="content">
    <?php  echo $this->render('_form1', ['model' => $searchModel]); ?>
    <div class="table-content">
        <?php  echo $this->render('_action'); ?>
        <div class="space-10"></div>
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'prevPageLabel' => '<',
                'nextPageLabel' => '>',
                'firstPageLabel' => "首页",
                'lastPageLabel' => '尾页',
                'maxButtonCount' => 2,
            ],
            'options' => ['class' => 'text-center','id' => "requirement-index"],
            'summary' => "<div class='summary mr-10'>记录共<span style='color:#1e7fd0'>{totalCount}</span>条，<span style='color:#1e7fd0'>{pageCount}</span>页</div>",
            'tableOptions' => ['class' => 'table'],
            'layout' => "{items}\n<div class='text-right mt-10'>{summary}{pager}</div></div>",

            //'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header'=>'序号',
                ],
                [
                    'label'=>'申请单号',
                    'attribute' => 'm_code', //产生一个a标签,点击可排序*/
                    'value' => 'm_code'      //关联表
                ],
                [
                    'label'=>'大分头',
                    'attribute'=>'pro_other_name',
                    //'attribute' => 'note', //产生一个a标签,点击可排序*/
                    'value' => 'pro_other_name'      //关联表
                ],
                [
                    'label'=>'料号',
                    'attribute'=>'material_code',
                    'value'=>'material_code'
                ],

                [
                    'label'=>'品名',
                    'attribute' => 'pro_name', //产生一个a标签,点击可排序*/
                    'value' => 'pro_name'      //关联表
                ],
                [
                    'label'=>'规格',
                    'attribute' => 'pro_size', //产生一个a标签,点击可排序*/
                    'value' => 'pro_size'      //关联表
                ],
                [
                    'label'=>'库存单位',
                    'attribute' => 'pro_sku', //产生一个a标签,点击可排序*/
                    'value' => 'pro_sku'      //关联表
                ],
                [
                    'label'=>'申请者',
                    'attribute' => 'create_by', //产生一个a标签,点击可排序*/
                    'value' => 'create_by'      //关联表
                    /*yii::$app->user->identity->staff->staff_name*/
                ],
                [
                    'label'=>'申请日期',
                    'attribute' => 'create_time', //产生一个a标签,点击可排序*/
                    'value' => 'create_time'      //关联表
                ],
                [
                    'label'=>'状态',
                    'attribute'=>'m_status',
                    'value'=>function($dataProvider){
                        /*return $dataProvider->m_status==0?'废弃':'正常';*/
                        if($dataProvider->m_status==0){
                            return "驳回";
                        }else if($dataProvider->m_status==1){
                            return "待审核";
                        }else if($dataProvider->m_status==2){
                            return "已审核";
                        }
                    },
                ],
                [
                    'label'=>'备注',
                    /*'attribute' => 'create_at', //产生一个a标签,点击可排序*/
                    /*'value' => 'create_at'*/      //关联表*/
                ],

            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
<script>
    $(function () {
        "use strict";
        $(document).on("click", '#requirement-index tbody > tr', function () {
            $('.table-click-tr').removeClass('table-click-tr');
            $(this).addClass('table-click-tr');
        })

        $(".check").on("click", function () {
            var id = $(".table-click-tr").attr("data-key");
            if (id == null) {
                layer.alert("请点击选择一条料号信息",{icon:2,time:5000});
            } else {
                window.location.href = "<?=Url::to(['view'])?>?id=" + id;
            }
        });

        /*修改操作*/
        $(".modify").on("click",function(){
            var id = $(".table-click-tr").attr("data-key");
            if (id == null) {
                layer.alert("请点击选择一条料号信息",{icon:2,time:5000});
            } else {
                window.location.href = "<?=Url::to(['edit'])?>?id=" + id;
            }
        });
        /*删除操作*/
        $(".cancle").on("click", function () {
            var id = $(".table-click-tr").attr("data-key");
            if (id == null) {
                layer.alert("请点击选择一条料号信息",{icon:2,time:5000});
            } else {
                var index = layer.confirm("确定要删除这条记录吗?",
                    {
                        btn:['确定', '取消'],
                        icon:2
                    },
                    function () {
                        $.ajax({
                            type: "get",
                            dataType: "json",
                            data: {"id": id},
                            url: "<?=Url::to(['material-code/delete']) ?>",
                            success: function (msg) {
                                if( msg.flag === 1){
                                    layer.alert(msg.msg,{icon:1},function(){
                                        location.reload();
                                    });
                                    setTimeout(function(){ location.reload()},5000)
                                }else{
                                    layer.alert(msg.msg,{icon:2})
                                }
                            }
                        })
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            }
        });
        /*新增拜访履历*/
        $("#add_resume").click(function(){
            var planId = $(".table-click-tr").attr("data-key");
            if (planId == null) {
                layer.alert("请点击选择一条料号信息",{icon:2,time:5000});
            }else{
                $.ajax({
                    type:'get',
                    data:{'id':planId},
                    dataType:'json',
                    url:'<?= Url::to(['/ptdt/material-code/add']) ?>',
                    success:function (firmId) {
                        window.location.href="<?= Url::to(['/ptdt/material-code/add']) ?>&firmId="+firmId+"&planId="+planId;
                    }
                });
            }

        })

    });

</script>


