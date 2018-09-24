<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?= $post ?>
<style>
    .ml-44 {
        margin-left: 44px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .mt-20 {
        margin-top: 20px;
    }

    .mt-40 {
        margin-top: 40px;
    }
</style>
<h1 class="head-first"><img id="img" src="<?= Url::to('@web/img/icon/house.png') ?>" alt="自定义我的桌面"
                            style="cursor: pointer; vertical-align: bottom; margin-left: -4px; height: 30px;">选择图标
</h1>
<?php ActiveForm::begin([
    'id' => 'custom_desktop',
]); ?>
<?php foreach ($filesnames as $k => $v) { ?>
    <?php if ($k != 0 && $k != 1) { ?>
        <div style="width:10%;display: inline-block;" class="ml-44">
            <img class="confirm_icon mt-20" src="<?= Url::to("@web/img/desktop-icon/$v") ?>"
                 style="width: 40px;height:40px;">
            <span class="hiden mt-10"><?= $v ?></span>
        </div>
        <!--    <span class="mr-10">--><? //= $val ?><!--</span>-->
    <?php } ?>
<?php } ?>
<div style="text-align:center;">

    <button type="button" class="button-white-big ml-320 mt-40" onclick='cansle()'
    >取消
    </button>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(function () {
        var id = "<?= $id ?>";
        var titleId = "<?= $titleId ?>";
        //确定
        $(".confirm_icon").click(function () {
            var filename = $(this).next("span").html();
            var img_url = "<?= Url::to("@web/img/desktop-icon/") ?>" + filename;
            parent.document.getElementById(id).src = img_url;
            parent.document.getElementById(titleId).value = filename;
            parent.$.fancybox.close();
        });
    });
    function cansle() {
        parent.$.fancybox.close();
    }
</script>