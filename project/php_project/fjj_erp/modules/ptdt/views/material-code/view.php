<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/11/17
 * Time: 下午 02:47
 */
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
$this->params['homeLike'] = ['label' => '料号管理'];
$this->params['breadcrumbs'][] = ['label' => '料号管理列表'];
$this->params['breadcrumbs'][] = ['label' => '料号申请详情'];

/* @var $this yii\web\View */
/* @var $model app\modules\hr\models\Staff */
$this->title = '申请单号：'.$model->m_code;
?>
<div class="content">
    <h1 class="head-first">
        新增料号申请<span class="head-first " style="font-size: small;margin-bottom: 0;">编号：<?= $model->material_code ?></span>
    </h1>
    <h2 class="head-second">
        料号基本信息
    </h2>
    <div>
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_main_type_id']?></label>
            <span class="width-180"><?= $model->pro_main_type_id?></span>
            <label class="width-132"><?= $model->attributeLabels()['pro_other_name']?></label>
            <span class="width-180"><?= $model->pro_other_name?></span>
            <label class="width-180"><?= $model->attributeLabels()['material_code']?></label>
            <span class="width-200"><?= $model->material_code?></span>
        </div>
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_second_type_id']?></label>
            <span class="width-180"><?= $model->pro_second_type_id?></span>
            <label class="width-500"><?= $model->attributeLabels()['pro_brand']?></label>
            <span class="width-180"><?= $model->pro_brand?></span>
        </div>
        <!--三阶-->
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_third_type_id']?></label>
            <span class="width-180"><?= $model->pro_third_type_id?></span>
            <label class="width-500"><?= $model->attributeLabels()['pro_name']?></label>
            <span class="width-180"><?= $model->pro_name?></span>
        </div>
        <!--四阶-->
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_fourth_type_id']?></label>
            <span class="width-180"><?= $model->pro_fourth_type_id?></span>
            <label class="width-500"><?= $model->attributeLabels()['pro_size']?></label>
            <span class="width-180"><?= $model->pro_size?></span>
        </div>
        <!--五阶-->
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_fifth_type_id']?></label>
            <span class="width-180"><?= $model->pro_fifth_type_id?></span>
            <label class="width-500"><?= $model->attributeLabels()['pro_sku']?></label>
            <span class="width-180"><?= $model->pro_sku?></span>
        </div>
        <!--六阶-->
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_sixth_type_id']?></label>
            <span class="width-180"><?= $model->pro_sixth_type_id?></span>
            <label class="width-500"><?= $model->attributeLabels()['source_code']?></label>
            <span class="width-180"><?= $model->source_code?></span>
        </div>
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_serial_number']?></label>
            <span class="width-180"><?= $model->pro_serial_number?></span>
        </div>

        <div class="space-10"></div>
        <hr size="1px">
        <div class="space-30"></div>
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['pro_level']?></label>
            <span class="width-500"><?= $model->pro_level?></span>
            <label class="width-180"><?= $model->attributeLabels()['other_group_code']?></label>
            <span class="width-180"><?= $model->other_group_code?></span>
        </div>
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['group_code']?></label>
            <span class="width-180"><?= $model->group_code?></span>
            <label class="width-500"><?= $model->attributeLabels()['birth_year']?></label>
            <span class="width-180"><?= $model->birth_year?></span>
        </div>
        <div class="mb-20">
            <label class="width-100"><?= $model->attributeLabels()['status']?></label>
            <span class="width-180"><?= $model->status?></span>
            <label class="width-500"><?= $model->attributeLabels()['pro_pic_name']?></label>
            <span class="width-180"><?= $model->pro_pic_name?></span>
        </div>
        <div class="space-20"></20>
    </div>

    <div class="space-20"></div>
    <div class="text-center mb-20">
        <?= Html::submitButton('确&nbsp认' ,['class' =>'button-blue-big','id'=>'submit']) ?>&nbsp;
        <?= Html::resetButton('取&nbsp消', ['class' => 'button-white-big ml-20','id'=>'goback' ]) ?>
    </div>

</div>
