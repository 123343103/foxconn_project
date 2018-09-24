<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?= $post ?>
<style>
    .ml-10{
        margin-left: 10px;
    }
    .ml-20{
        margin-left: 20px;
    }
    .ml-30{
        margin-left: 30px;
    }
    .mt-10{
        margin-top: 10px;
    }
    .mt-20{
        margin-top: 20px;
    }
</style>
<div class="content" style="margin-top: -20px;">
    <h1 class="head-first"><img id="img" src="<?= Url::to('@web/img/icon/house.png') ?>" alt="自定义我的桌面"
                                style="cursor: pointer; vertical-align: bottom; margin-left: -4px; height: 30px;">修改功能图标
    </h1>
    <?php ActiveForm::begin([
        'id' => 'auth_title',
    ]);
    $num = 0; ?>
    <?php foreach ($authority as $k => $v) { ?>
        <label for="" class="blue ml-20 mt-10 fs-15"><?= $k ?></label>
        </br>
        <?php foreach ($v as $kk => $vv) {
            $num++; ?>
            <div style="width:23%;display: inline-block;" class="mt-10">
                <img src="<?= Url::to("@web/img/desktop-icon/") . $vv['action_icon'] ?>"
                     style="width: 20px;height: 20px"
                     class="vertical-center ml-30 icon" id="<?= "icon_" . $num ?>">
                <input type="text" name="AuthTitle[<?= $num ?>][action_icon]" value="<?= $vv['action_icon'] ?>"
                       class="hiden" id="<?= "action_" . $num ?>">
                <input type="text" name="AuthTitle[<?= $num ?>][action_url]" value="<?= $vv['action_url'] ?>"
                       class="hiden">
                <span class="ml-10"><?= $vv['action_title'] ?></span>
            </div>
        <?php } ?>
        </br>
        <!--    <span class="mr-10">--><? //= $val ?><!--</span>-->
    <?php } ?>
    <div style="text-align:center;">
    <button type="submit" class="button-blue-big ml-320 mt-20">确定</button>
    <button type="button" class="button-white-big ml-60 mt-20" onclick='cansle()'">取消</button></div>
    <?php ActiveForm::end(); ?>
</div>

<script>
    $(function () {
        ajaxSubmitForm('#auth_title');
        $(".icon").on("click", function () {
            var id = this.id;
            var titleId = $(this).next("input")[0].id;
            $(".icon").fancybox({
                padding: [],
                fitToView: false,
                width: 800,
                height: 570,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
                href: "<?= Url::to(['select-icon'])?>" + "?id=" + id + "&titleId=" + titleId
            });
        });

    });
    function cansle() {
        window.location.href = "<?= Url::to(['/index']) ?>";
    }
</script>