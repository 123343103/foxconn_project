<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="search-div">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get'
    ]); ?>
    <div class="mb-20">
        <div class="inline-block ">
        <label for="setinventorywarningsearch-staff_name" class="width-50  text-right">仓库</label>
                <select name="SetInventoryWarningSearch[wh_id]" class="width-120" id="setinventorywarningsearch-wh_code" >
                    <option value="">--请选择--</option>
                    <?php foreach ($StaffCode['whname'] as $val) {?>
                        <option value="<?=$val['wh_id'] ?>"  <?= isset($params['SetInventoryWarningSearch']['wh_id'])&&$params['SetInventoryWarningSearch']['wh_id']==$val['wh_id']?"selected":null ?>>  <?= $val['wh_name'] ?>  </option>
                    <?php } ?>
             </select>
            </div>
        <label for="setinventorywarningsearch-staff_code" class="width-100  text-right">预警人员</label>
        <input name="SetInventoryWarningSearch[staff_code]" id="setinventorywarningsearch-staff_code"  class="width-120" value="<?= $opper?>"/>
        <label for="SetInventoryWarningSearch-category_sname" class="width-100  text-right">商品类别</label>
        <select name="SetInventoryWarningSearch[category_id]" class="width-150" id="SetInventoryWarningSearch-category_sname">
            <option value="">--请选择--</option>categoryname
            <?php foreach ($StaffCode['categoryname'] as $val) { ?>
                <option value="<?=$val['category_id'] ?>"  <?= isset($params['SetInventoryWarningSearch']['category_id'])&&$params['SetInventoryWarningSearch']['category_id']==$val['category_id']?"selected":null ?>>  <?= $val['category_sname'] ?></option>
            <?php } ?>
        </select>


    </div>

    <div class="mb-30">
        <label for="SetInventoryWarningSearch-part_no" class="width-50  text-right">料号</label>
        <input type="text" name="SetInventoryWarningSearch[part_no]" class="width-120" id="SetInventoryWarningSearch-part_no" value="<?=$params['SetInventoryWarningSearch']['part_no']?>">
        <label for="SetInventoryWarningSearch-so_nbr" class="width-100  text-right">状态</label>
        <select name="SetInventoryWarningSearch[so_type]" class="width-120" id="SetInventoryWarningSearch-so_type" >
            <option value="">全部</option>
            <option value="40">审核完成</option>
            <option value="10">待提交</option>
            <option value="50">驳回</option>
            <option value="20">审核中</option>
        </select>
        <input type="checkbox" style="height: 11px;margin-left: 40px" name="ck"/><span style="color: blue">是否显示已删除的内容</span>

        <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-80', 'type' => 'submit']) ?>
        <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-40', 'type' => 'reset','id'=>'reset','onclick'=>'window.location.href=\''.\yii\helpers\Url::to(["index"]).'\'']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div class="space-20"></div>
<script>
    $(function () {
        $(':checkbox[name=ck]').click(function () {
            window.location.href = "<?=\yii\helpers\Url::to(['index'])?>" ;
        });
        var selector="<?=$search["so_type"]?>";
        if(selector!=""&&selector!=null){
            $("#SetInventoryWarningSearch-so_type").val(selector);
        }
//        $("#reset").click(function () {
//            $("#setinventorywarningsearch-staff_code").val("");
//            window.location.href = "<?//=Url::to(['index'])?>//";
//        })
    })
</script>
