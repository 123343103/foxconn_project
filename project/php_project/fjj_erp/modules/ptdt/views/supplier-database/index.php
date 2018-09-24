<?php
use yii\helpers\Url;
use yii\grid\GridView;
$this->title = '供应商申请';
$this->params['homeLike'] = ['label'=>'供应商申请列表','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'供应商申请列表'];
?>
<div class="content">
    <?php  echo $this->render('_search', ['model' => $searchModel,'downList'=>$downList]); ?>

    <div class="tabe-clontent">
        <?php  echo $this->render('_action'); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'pager' => [
                'pagination' => ['pageSize' => 2],
                'prevPageLabel' => '<',
                'nextPageLabel' => '>',
                'firstPageLabel' => "首页",
                'lastPageLabel' => '尾页',
                'maxButtonCount' => 5,
            ],
            'summary' => "<div class='summary mr-10'>记录共<span style='color:#1e7fd0'>{totalCount}</span>条，<span style='color:#1e7fd0'>{pageCount}</span>页</div>",
            'layout' => "{items}\n<div class='text-right mt-10'>{summary}{pager}</div></div>",
            'columns' => [
                [
                    'header'=>'序号',
                    'class' => 'yii\grid\SerialColumn',
                ],
                [
                    'label'=>'供应商编号',
                    'value' => 'supplier_code'
                ],
                [
                    'label'=>'供应商名称',
                    'value' => 'supplier_shortname'
                ],
                [
                    'label'=>'供应商分类',
                    'value' => 'supplierType.bsp_svalue'
                ],
                [
                    'label'=>'厂商来源',
                    'value' => 'firm.firmSource.bsp_svalue'
                ],
                [
                    'label'=>'是否为集团供应商',
                    'value'=>function ($model){
                        return $model->firm->firm_issupplier==1?'是':'否';
                    },
                ],
                [
                    'label'=>'商品类型',
                    'value' =>'productType.type_name'
                ],
//                [
//                    'label'=>'新增人',
//                    'value' =>'firm.firmType.bsp_svalue'
//                ],
                [
                    'label'=>'新增人',
                    'value'=>'buildStaff.staff_name'
                ],
                [
                    'label'=>'新增时间',
                    'value' =>'create_at'
                ],
                [
                    'label'=>'状态',
                    'value' =>'status'
                ],

//                [
//                    'label' => '公司全称',
//                    'value' =>'firm.firm_sname'
//                ],
//                [
//                    'label' => '简称',
//                    'value' =>'firm.firm_shortname'
//                ],
//                [
//                    'label'=>'主营商品范围',
//                    'value' =>'firm.firm_salarea'
//                ],
//                [
//                    'label'=>'品牌',
//                    'value' =>'firm.firm_brand'
//                ],
//                [
//                    'label'=>'是否为集团供应商',
//                    'value'=>function ($model){
//                        return $model->firm->firm_issupplier==1?'是':'否';
//                    },
//                ],
//                [
//                    'label'=>'公司来源',
//                    'value' => 'firm.firmSource.bsp_svalue'
//                ],
//                [
//                    'label'=>'类型',
//                    'value' =>'firm.firmType.bsp_svalue'
//                ],
            ],
        ]); ?>
        <div class="space-30"></div>
        <div id="load-info" class="overflow-auto">

        </div>
    </div>
</div>

