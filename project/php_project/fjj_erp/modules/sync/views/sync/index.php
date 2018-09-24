<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/2
 * Time: 上午 11:37
 */
use yii\helpers\Html;
?>
<style>
    .width-80{
        width: 80px;
    }
    .width-200{
        width: 200px;
    }
   .ml-80{
       margin-left: 80px;
   }
    .mt-10{
        margin-top: 10px;
    }
</style>
<div class="content">
    <div class="left" style="float: left;width:400px;">
        <form action="<?=\yii\helpers\Url::to(['sync'])?>" target="progress">
            <div>
                <label class="width-80" for="">同步阶段</label><label>：</label>
                <?=Html::dropDownList("step","",$steps,["prompt"=>"请选择","class"=>"width-200 easyui-validatebox","data-options"=>"required:true"])?>
            </div>
            <div>
                <label class="width-80" for="">同步表</label><label>：</label>
                <?=Html::dropDownList("table","",$tables,["prompt"=>"请选择","class"=>"width-200 mt-10 easyui-validatebox","data-options"=>"required:true"])?>
            </div>
            <div class="mt-10">
                <label class="width-80" for="">分页大小</label><label>：</label>
                <input name="size" class="width-200 easyui-validatebox" type="text" value="1000" data-options="required:true">
            </div>
            <div>
                <button type="submit" id="start" class="button-blue-big  ml-80 mt-10">开始</button>
            </div>
        </form>
    </div>
    <div class="right" style="float:left;">
        <iframe name="progress" id="progress" style="width:500px;height:100px;overflow: hidden;border: none;outline: none;"></iframe>
    </div>
    <div class="claerfix"></div>
</div>
