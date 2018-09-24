<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/11/24
 * Time: 上午 09:37
 */
use \yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '系统平台设置'];
$this->params['breadcrumbs'][] = ['label' => '菜单管理', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '操作设置'];
$this->title = '操作设置';
?>
<style>
    .check-div {
        float: left;
        margin-left: 55px;
        margin-top: -3px;
    }
</style>
<div class="space-10"></div>
<?php $form = ActiveForm::begin(['action' => ['opera-set'], 'method' => 'post', 'id' => 'opera-set']); ?>
<div class="content">
    <h2 class="head-first">操作设置</h2>
    <input type="hidden" value="<?= $menu_pkid ?>" name="menu_pkid">
    <p style="padding-left: 50px;margin-top:20px;font-size: 13px;">绑定菜单：<?= $menu_name ?></p>
    <p style="margin-top: 20px;padding-left: 50px;font-size: 15px"><b>请选择操作按钮：</b></p>
    <div style="width: 635px;height: 207px;border: 1px solid grey;margin-left: 35px;overflow-y: auto">
        <table style="width: 100%;max-height: 600px;border-collapse:separate; border-spacing:5px;" class="no-border">
            <tbody>
            <?php $n = ceil(count($data) / 4);
            $k = 0;
            for ($i = 0;
                 $i < $n;
                 $i++) { ?>
                <tr class="no-border">
                    <?php if (count($data) % 4 != 0 && $i == $n - 1) { ?>
                        <?php for ($j = 0; $j < count($data) % 4; $j++) { ?>
                            <td class="no-border vertical-center"
                                style="float: left;width: 25%;text-align: center;font-size: 15px">
                                <div class="check-div"><input type="checkbox"
                                                              name="Check[<?= $data[$k]['btn_pkid'] ?>]" <?php foreach ($allBtn as $value) {
                                        if ($value['btn_pkid'] == $data[$k]['btn_pkid']) echo 'checked';
                                    } ?>
                                                              value="<?= $data[$k]['btn_pkid'] ?>">
                                </div>
                                <div style="float: left;"><?= $data[$k]['btn_name'] ?></div>
                            </td>
                            <?php $k++;
                        } ?>

                    <?php } else { ?>
                        <?php for ($j = 0; $j < 4; $j++) { ?>
                            <td class="no-border vertical-center"
                                style="float: left;width: 25%;text-align: center;font-size: 15px">
                                <div class="check-div"><input type="checkbox"
                                                              name="Check[<?= $data[$k]['btn_pkid'] ?>]" <?php foreach ($allBtn as $value) {
                                        if ($value['btn_pkid'] == $data[$k]['btn_pkid']) echo 'checked';
                                    } ?>
                                                              value="<?= $data[$k]['btn_pkid'] ?>">
                                </div>
                                <div style="float: left;"><?= $data[$k]['btn_name'] ?></div>

                            </td>
                            <?php $k++;
                        } ?>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div style="margin-top: 20px;">
        <button class="button-blue-big" style="margin-left: 235px;" type="submit">确定</button>
        <button class="button-white-big" id="returnBtn" onclick="history.go(-1);" type="button">返回</button>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(function () {
        ajaxSubmitForm($("#opera-set"));
    })
</script>
<!--<script>-->
<!--    $(function () {-->
<!--        $("#returnBtn").on('click', function () {-->
<!--            window.location = 'index';-->
<!--        })-->
<!--    })-->
<!--</script>-->

