<?php
/**
 * User: F1677929
 * Date: 2017/3/14
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);
?>
    <style>
        .mb-20 {
            margin-bottom: 20px;
        }

        .mt-30 {
            margin-top: 30px;
        }

        #linesty a:hover {
            text-decoration: underline
        }
    </style>
<?php ActiveForm::begin(['id' => 'import_form', 'action' => Url::to(['import']), 'options' => ['target' => 'progress', 'enctype' => 'multipart/form-data']]) ?>
    <h1 class="head-first">导入数据</h1>
    <div id="linesty" class="mb-20 text-center mt-30">
        <input id="import_input" class="easyui-validatebox" data-options="required:true,validType:'import'" type="file"
               name="UploadForm[file]"> &nbsp;&nbsp;
        <a style="font-size: 14px;" href="<?= Url::to(['down-template']) ?>">下载模板</a>
    </div>
    <div class="text-center mb-10">
        <button class="button-blue" type="submit" id="confirm_btn">导入</button>
        <button class="button-white" type="button" onclick="parent.$.fancybox.close()">取消</button>
    </div>
<?php ActiveForm::end() ?>
    <div id="progressbar_popup" style="display:none;">
        <h1 class="head-first">导入进度</h1>
        <div id="progressbar_div" style="display:none;width:400px;margin:0 50px;margin-top:30px;"></div>
        <iframe name="progress" frameborder="0" style="width:100%;height: 50px;"></iframe>
        <h3 style="display: none" id="import-ok" class="text-center">导入完成</h3>
        <div class="text-center display-none mt-10" id="import_over" style="position: relative;">
            <button class="button-blue" type="button" onclick="window.parent.location.reload()">完成</button>
            <a id="err_log" target="_blank" style="position:absolute;display:none;left:300px;top:5px;"
               href="<?= Url::current(["act" => "view-log"]) ?>">查看错误日志</a>
        </div>
    </div>
<?php
$js = <<<JS
 $(function(){
     $("#confirm_btn").click(function(){
         $("button").prop("disabled",false);
     });
     $("form").submit(function () {
         if(!$(this).form('validate')){
            return false;
         }
         $("#import_form").slideUp();
         $("#progressbar_popup").slideDown();
         $('#progressbar_div').progressbar({value:0});
     });
 });
JS;
$this->registerJs($js);
?>