<?php
/**
 * Created by PhpStorm.
 * User: G0007903
 * Date: 2017/10/31
 */
use app\assets\JqueryUIAsset;  //ajax引用jQuery样
use yii\helpers\Url;
JqueryUIAsset::register($this);
?>
<div class="content">
    <h2>问卷地址:</h2>
    <div id="number2"  style="margin-top: 10px;margin-left: 5px"><?=Yii::$app->ftpPath["httpIP"]?><?=Yii::$app->ftpPath["QST"]["father"]?><?=$data["invst_path"]?></div>
    <div style="margin-top: 30px;margin-left: 80px">
        <button id="cancel" class="button-white-big" type="button">关闭</button>
<!--        <button onclick="copyUrl2()" id="copy" class="button-white-big" type="button">复制</button>-->
    </div>
</div>
<script>
    $(function(){
        $("#cancel").click(function () {
            parent.$.fancybox.close();
        });
    });
</script>
