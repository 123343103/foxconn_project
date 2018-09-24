<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 下午 06:28
 */

use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;
JqueryUIAsset::register($this);

?>
<style>
.ml-10{
    margin-left: 10px;
}
    .mb-10{
        margin-bottom: 10px;
    }
    .width-100{
        width: 100px;
    }
    .width-200{
        width: 200px;
    }
    .mt-10{
        margin-top: 10px;
    }
    .width-400{
        width: 400px;
    }
    .counter{
        font-size: 10px;
        color: red;
        margin-top: -15px;
        margin-left: 508px;
    }
    .mt-30{
        margin-top: 30px;
    }
    .ml-120{
        margin-left: 120px;
    }
    .lest{
        display: none;
        margin-left: 20px
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-20{
        width: 100%;
        height: 20px;
    }
    .content{padding: 0px;}
</style>
<div class="content">
<div class="pop-head">
    <p id="viewTitle">区位详情</p>
</div>
        <div class="space-20"></div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="no-border vertical-center width-100 label-align" for="partsearch-wh_name">仓库名称：</label>
                <span class="no-border vertical-center value_align" style="width: 300px"><?= $model[0]['wh_name']?></span>
<!--                <select class="width-200 value-align" name="BsPart[wh_name]" id="wh_id" disabled="disabled">-->
<!--                    <option value="">全部</option>-->
<!--                    --><?php //foreach ($downList['whname'] as $val) { ?>
<!--                        --><?php //if ($model['wh_code'] == $val['wh_code']) { ?>
<!--                            <option selected="selected" value="--><?//= $val['wh_code'] ?><!--"-->
<!--                                    name="wh_code">--><?//= $val['wh_name'] ?><!--</option>-->
<!--                        --><?php //} else { ?>
<!--                            <option value="--><?//= $val['wh_code'] ?><!--" name="wh_code">--><?//= $val['wh_name'] ?><!--</option>-->
<!--                        --><?php //} ?>
<!---->
<!--                    --><?php //} ?>
<!---->
<!---->
<!--                </select>-->
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_code">仓库代码：</label>
                <span><?= $model[0]['wh_code']?></span>
<!--                <input type="text" maxlength="30" id="whCode" name="BsPart[wh_code]" disabled="disabled"-->
<!--                        class=" easyui-validatebox width-200 value-align" value="--><?//= $model['wh_code'] ?><!--">-->
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_code">区位码：</label>
                <span><?= $model[0]['part_code']?></span>
<!--                <input type="text" maxlength="30" id="partCode" name="BsPart[part_code]" disabled="disabled"-->
<!--                       class=" easyui-validatebox width-200 value-align" value="--><?//= $model['part_code'] ?><!--">-->
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-part_name">区位名称：</label>
                <span><?= $model[0]['part_name']?></span>
<!--                <input type="text" maxlength="30" id="partName" name="BsPart[part_name]" disabled="disabled"-->
<!--                       class="easyui-validatebox width-200 value-align" value="--><?//= $model['part_name'] ?><!--">-->
            </div>
        </div>
        <div class="mb-10">
            <div class="inline-block">
                <label class="width-100 label-align" for="partsearch-yn">状态：</label>
                <span><?= $model[0]['YNS']?></span>
<!--                <select id="partsearch-yn" class="width-200 value-align" name="BsPart[YN]" disabled="disabled">-->
<!--                    --><?php //if ($model['YN'] == 0) { ?>
<!--                        <option value="1">启用</option>-->
<!--                        <option value="0" selected="selected">禁用</option>-->
<!--                    --><?php //} else { ?>
<!--                        <option value="1" selected="selected">启用</option>-->
<!--                        <option value="0">禁用</option>-->
<!--                    --><?php //} ?>
<!--                </select>-->
            </div>
        </div>
        <div class="mt-10">
            <div class="inline-block field-partsearch-pm_desc">
                <label class="no-border vertical-center width-100 label-align" for="partsearch-remarks">备注：</label>
                <span class="no-border vertical-center value_align" style="width: 300px;"><?= $model[0]['remarks'] ?></span>
            </div>
        </div>
    </div>

</div>