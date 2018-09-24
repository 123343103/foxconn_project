<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/7
 * Time: 下午 07:44
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<style>
    .width-62{
        width: 80px;
    }
    .width-48{
        width: 48px;
    }
    .width-120{
        width: 150px;
    }
    .width-100{
        width: 80px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div>
    <div>
        <div class="inline-block mb-10">
            <label class="qlabel-align width-62"  for="wh_name">仓库名称：</label>
            <input class="qvalue-align width-120" type="text" id="wh_name" style="margin-left: 1px" name="wh_name"
                   value="<?=$queryParam['wh_name']?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block mb-10">
            <label class="qlabel-align width-100"  for="wh_code">仓库代码：</label>
            <input class="qvalue-align width-120" type="text" id="wh_code" name="wh_code"
                   value="<?=$queryParam['wh_code']?>">
            <div class="help-block"></div>
        </div>
        <div class="inline-block mb-10">
            <label class="qlabel-align width-62"  for="part_no">仓库级别：</label>
            <select name="wh_lev" class="value-width qvalue-align width-120">
                <option value="" style="width: 130px;">请选择</option>
                <?php foreach ($downList['wh_lev'] as $key => $val) {?>
                    <option value="<?= $val['bsp_id'] ?>" <?= isset($queryParam['wh_lev'])&&$queryParam['wh_lev']==$val['bsp_id']?"selected":null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <div class="help-block"></div>
        </div>

        <div class="inline-block mb-10">
            <label class="qlabel-align width-62"  for="part_no">仓库属性：</label>
            <select name="wh_attr" class="value-width qvalue-align width-120">
                <option value="" style="width: 130px;">请选择</option>
                <?php foreach ($downList['wh_attr'] as $key => $val) {?>
                    <option value="<?= $val['bsp_id'] ?>" <?= isset($queryParam['wh_attr'])&&$queryParam['wh_attr']==$val['bsp_id']? "selected" :null ?>><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <div class="inline-block ">
                <label class="qlabel-align width-100"  for="wh_type">仓库类别：</label>
                <select name="wh_type" class="value-width qvalue-align width-120">
                    <option value="" style="width: 130px;">请选择</option>
                    <?php foreach ($downList['wh_type'] as $key => $val) {?>
                        <option value="<?= $val['bsp_id'] ?>" <?= isset($queryParam['wh_type'])&&$queryParam['wh_type']==$val['bsp_id']? "selected" :null ?>><?= $val['bsp_svalue'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="inline-block mb-10">
                <label class="qlabel-align width-100" >是否报废仓：</label>
                <select  name="wh_yn" class="easyui-validatebox validateboxs width-120" >
                    <option value="" style="width: 130px;">请选择</option>
                    <option value="N" <?= $queryParam['wh_yn']=="N"?"selected":null; ?>>否</option>
                    <option value="Y" <?= $queryParam['wh_yn']=="Y"?"selected":null; ?>>是</option>
                </select>
            </div>
        </div>
        <div class="inline-block mb-10">
            <div class="inline-block mb-10">
                <label class="qlabel-align width-62" >是否自提点：</label>
                <select  name="yn_deliv" class="easyui-validatebox validateboxs width-120" >
                    <option value="" style="width: 130px;">请选择</option>
                    <option value="0" <?= $queryParam['yn_deliv']=="0"?"selected":null; ?>>否</option>
                    <option value="1" <?= $queryParam['yn_deliv']=="1"?"selected":null; ?>>是</option>
                </select>
            </div>
            <div class="inline-block ">
            <label class="qlabel-align width-100"  for="wh_state">状态：</label>
            <select class="width-120" style="margin-left: 2px" name="wh_state">
                <option value="Y" <?= $queryParam['wh_state']=="Y"?"selected":null; ?>>启用</option>
                <option value="1" <?= $queryParam['wh_state']=="1"?"selected":null; ?>>全部</option>
                <option value="N" <?= $queryParam['wh_state']=="N"?"selected":null; ?>>禁用</option>
            </select>
                <?= Html::submitButton('查询', ['id'=>'search', 'class' => 'search-btn-blue ', 'type' => 'submit','style'=>'margin-left:17px']) ?>
                <?= Html::resetButton('重置', ['id'=>'reset', 'class' => 'reset-btn-yellow ','style'=>'margin-left:10px', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["index"]) . '\'']) ?>
        </div>
    </div>
    </div>

</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $('.type').on("change", function () {
            var $select = $(this);
            getMyNextType($select, "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>", "select");
        });
    });
    /**
     * 分类级联
     * @param $select  //第一个select
     * @param url     // "<?=Url::to(['/ptdt/product-library/get-product-type']) ?>",
     */
    function getMyNextType($select, url, selectStr) {
        var id = $select.val();
        if (id == "") {
            clearOption($select);
            return;
        }
        $.ajax({
            type: "get",
            dataType: "json",
            async: false,
            data: {"id": id},
            url: url,
            success: function (data) {
                var $nextSelect = $select.next(selectStr);
                clearOption($nextSelect);
                $nextSelect.html('<option value>请选择</option>');
                if ($nextSelect.length != 0)
                    for (var x in data) {
                        $nextSelect.append('<option value="' + data[x].category_id + '" >' + data[x].category_name + '</option>');
                    }
            }

        })
    }

</script>


