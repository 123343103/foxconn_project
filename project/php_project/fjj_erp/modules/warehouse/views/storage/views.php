<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/15
 * Time: 下午 02:55
 */

?>
<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;
use app\assets\MultiSelectAsset;

JqueryUIAsset::register($this);
MultiSelectAsset::register($this);
?>
<style>
    .multi-select {
        position: relative;
        width: 200px;
        height: 25px;
        margin-left: 100px;
        margin-top: -25px;
    }

    .multi-select-title {
        height: 25px;
        line-height: 25px;
        position: absolute;
        margin-left: 4px;
        display: block;
    }

    .multi-select ul {
        position: absolute;
        width: 180px;
        padding: 0px 10px;
        height: 250px;
        top: 28px;
        border: #ccc 1px solid;
        overflow-x: hidden;
        overflow-y: auto;
        display: none;
        background: #f0f1f4;
    }

    .multi-select label {
        line-height: 25px;
    }

    .multi-select label:after, .multi-select label:before {
        content: ""
    }

    .multi-select label span {
        vertical-align: top;
    }

    .font-16 {
        font-size: 16px;
    }

    .bold {
        font-weight: bold;
    }
    .ml-50{
        margin-left: 50px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .width-100{
        width: 100px;
    }
    .mb-40{
        margin-bottom: 40px;
    }
    .ml-100{
        margin-left: 100px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .space-20{
        width: 100%;
        height: 20px;
    }
    .width-250{
        width: 250px;
    }
    .width-140{
        width: 140px;
    }
    .width-140{
        width: 140px;
    }
</style>
<div class="no-padding " >
<div class="pop-head">
    <p>储位详情</p>
</div>
    <div class="space-20"></div>
    <div class="ml-50">
        <div class="mb-10">
            <label  class="label-width qlabel-align width-100 vertical-center">仓库名称:</label>
            <span class="width-250 vertical-center" style="line-height: 24px"><?= $model["wh_name"] ?></span>
        </div>
        <div class="mb-10">
            <label class="qlabel-align width-100">分&nbsp;区&nbsp;码&nbsp;:</label>
            <span class="width-250"><?= $model["part_code"] ?></span>
        </div>
        <div class="mb-10">
            <label class="qlabel-align width-100">区位名称:</label>
            <span class="width-250"><?= $model["part_name"] ?></span>
        </div>
        <div class="mb-10">
            <label class="qlabel-align width-100" >货&nbsp;架&nbsp;码&nbsp;:</label>
            <span class="width-250"><?= $model["rack_code"] ?></span>
        </div>
        <div class="mb-10">
            <label class="qlabel-align width-100" >储&nbsp;位&nbsp;码&nbsp;:</label>
            <span class="width-250"><?= $model["st_code"] ?></span>
        </div>
        <div class="mb-10">
            <label class="qlabel-align width-100">状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态:</label>
            <span class="width-250"><?= $model["YN"] ?></span>
        </div>
        <div class="mb-10">
            <label class="label-width qlabel-align width-100 vertical-center" >备&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;注:</label>
            <span class="width-250 vertical-center" style="line-height: 24px"><?= $model["remarks"] ?></span>
        </div>
        <div class="mb-10">
            <label class="width-100 qlabel-align width-100" >操&nbsp;作&nbsp;人&nbsp;:</label>
            <span style="width:65px"><?= $model["OPPER"] ?></span>
            <label class="width-100 label-align" for="partsearch-wh_name">操作时间:</label>
            <span class="width-140"><?= $model["OPP_DATE"] ?></span>
        </div>
    </div>
</div>
<script>
    $("#edit").click(function () {
        location.href = "<?=Url::to(['update'])?>?st_id="+ $("#st_id").val();
//        $.fancybox({
//            type: "iframe",
//            width: 600,
//            height: 600,
//            autoSize: false,
//            href: "<?//=Url::to(['update'])?>//?st_id=" + $("#st_id").val(),
//            padding: 0
//        });
    })
    $("#close").click(function () {
        location.href="<?=Url::to(['index'])?>"
    })
</script>

