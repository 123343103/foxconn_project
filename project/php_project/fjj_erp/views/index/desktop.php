<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \app\classes\Menu;

?>
<?= $post ?>
<div class="content container" style="margin-top: -20px;">
    <h1 class="head-first"><img id="img" src="<?= Url::to('@web/img/icon/house.png') ?>" alt="自定义我的桌面"
                                style="cursor: pointer; vertical-align: bottom; margin-left: -4px; height: 30px;">自定义我的桌面
        <?= Menu::isAction('/index/edit-icon') ? Html::a("<span class='head-code'>修改图标</span>", null, ['id' => 'edit']) : '' ?>
    </h1>
    <?php ActiveForm::begin([
        'id' => 'custom_desktop',
    ]); ?>
    <?php foreach ($authority as $k => $v) { ?>
        <label for="" class="blue ml-20 mt-20 fs-15"><?= $k ?></label>
        </br>
        <?php foreach ($v as $kk => $vv) {
            if (in_array($vv['action_url'], $select)) {
                $selected = 'checked';
            } else {
                $selected = false;
            }
            ?>
            <div style="width:23%;display: inline-block;">
                <label class="ml-20" for=""><?= $vv['action_title'] ?></label>
                <input type="checkbox" name="desktop[]" <?= $selected ?> value="<?= $vv['action_url'] ?>"
                       style="vertical-align: top;">
            </div>
        <?php } ?>
        </br>
        <!--    <span class="mr-10">--><? //= $val ?><!--</span>-->
    <?php } ?>
    <div class="space-20"></div>
    <button type="submit" class="button-blue-big ml-320 mt-20">确定</button>
    <button type="button" class="button-white-big ml-60 mt-20" onclick='cansle()'
    >取消
    </button>
    <?php ActiveForm::end(); ?>
</div>

<script>
    $(function () {
        ajaxSubmitForm('#custom_desktop');


        $("#edit").on("click", function () {
            window.location.href = "<?= Url::to(['/index/edit-icon']) ?>";
        });
    });
    function cansle() {
        window.location.href = "<?= Url::to(['/index']) ?>";
    }
</script>