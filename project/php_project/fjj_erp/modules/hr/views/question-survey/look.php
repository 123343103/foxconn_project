<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/11/13
 * Time: 下午 02:43
 */
use yii\helpers\Url;

$this->title = '预览问卷详情';
?>
<div style="height: 20px;"></div>
<h1 style="text-align: center;"><?= $data['BsQstInvst']['invst_subj'] ?><span style="color:red;">
</span></h1>
<div style="height: 10px;"></div>
<?php if (empty($data['product'])) { ?>

<?php } else {
    $i = 0; ?>
    <?php foreach ($data['product'] as $val) {
        if (!empty($val['InvstContent']['cnt_tpc'])) {
            $i++;
        }
    } ?>
    <?php if ($i == 0) { ?>
        <div style="width: 1007px;min-height: 300px;margin: 0 auto;">
                <label class="label-width qlabel-align"><span style="color:red;">*</span>类别</label><label>：</label>
                <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                       value="<?= $dataProvider['bsp_id'][0]['bsp_svalue'] ?>">
                <label class="label-width qlabel-align" style="margin-left: 100px;"><span
                        style="color:red;">*</span>主办单位</label>
                <label>：</label>
                <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                       value="<?= $dataProvider['organization_name'][0]['organization_name'] ?>">
                <label class="label-width qlabel-align" style="margin-left: 90px;">调查对象</label><label>：</label>
                <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" style="width: 250px;"
                       class="value-width qvalue-align"
                       value="<?= $dataProvider['datas']?>">
            <H3 style="text-align: center;margin-top: 20px;">该问卷调查暂无问题！</H3>
            <div style="height: 50px;"></div>
            <div class="text-center">
                <button class="button-white-big" id="btn_close" type="button">关闭</button>
            </div>
            <div style="height: 50px;"></div>
        </div>
    <?php } else { ?>
        <div style="width: 1007px;min-height: 300px;margin: 0 auto;">
            <p style="font-size: 14px;">欢迎您在<?= $data['BsQstInvst']['invst_start'] ?>
                ~<?= $data['BsQstInvst']['invst_end'] ?>期间参加本次问卷调查</p>
            <div style="height: 10px;"></div>
            <label class="label-width qlabel-align"><span style="color:red;">*</span>类别</label><label>：</label>
            <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                   value="<?= $dataProvider['bsp_id'][0]['bsp_svalue'] ?>">
            <label class="label-width qlabel-align" style="margin-left: 100px;"><span
                    style="color:red;">*</span>主办单位</label>
            <label>：</label>
            <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
                   value="<?= $dataProvider['organization_name'][0]['organization_name'] ?>">
            <label class="label-width qlabel-align" style="margin-left: 90px;">调查对象</label><label>：</label>
            <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" style="width: 250px;"
                   class="value-width qvalue-align"
                   value="<?= $dataProvider['datas'] ?>">
            <div style="width:100%;height: 3px;background: #E4E4E4;margin-top: 5px"></div>
            <div style="height: 10px;"></div>
            <?php $a = 1;
            $array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');//定义选项字母
            foreach ($data['product'] as $value) { ?>
                <?php if ($value['InvstContent']['cnt_type'] == '1') {
                    $b = 0; ?>
                    <div style="font-size: 14px;">
                        <p><?= $a ?>
                            、<?= $value['InvstContent']['cnt_tpc'] ?>【单选】</p>
                        <?php foreach ($value['InvstOptions'] as $values) { ?>
                            <p style="text-indent:2em;"><input name="radios<?= $a?>" type="radio"/><?= $array[$b] ?>.<?= $values['opt_name'] ?>
                                <?php if (trim($values['opt_name']) == '其它'||trim($values['opt_name']) == '其他') { ?>
                                    <input type='text' id="other"
                                           style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                <?php } ?>
                            </p>
                            <?php $b++;
                        } ?>
                    </div>
                <?php } else if ($value['InvstContent']['cnt_type'] == '2') {
                    $b = 0; ?>
                    <div style="font-size: 14px;">
                        <p><?= $a ?>
                            、<?= $value['InvstContent']['cnt_tpc'] ?>【多选】</p>
                        <?php foreach ($value['InvstOptions'] as $values) { ?>
                            <p style="text-indent:2em;"><input name="multiselects<?= $a?>" type="checkbox" style="vertical-align: middle;"/>
                                <?= $array[$b] ?>
                                .<?= $values['opt_name'] ?>
                                <?php if (trim($values['opt_name']) == '其它'||trim($values['opt_name']) == '其他') { ?>
                                    <input type='text' id="other"
                                           style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                <?php } ?>
                            </p>
                            <?php $b++;
                        } ?>
                    </div>
                <?php } else if ($value['InvstContent']['cnt_type'] == '3') { ?>
                    <div style="font-size: 14px;">
                        <p><?= $a ?>
                            、<?= $value['InvstContent']['cnt_tpc'] ?></p>
                        <textarea name="a<?= $a?>" class="easyui-validatebox validatebox-text"
                                  style="width: 620px;height: 100px; text-indent:2em;"
                                  rows="3" maxlength="200"
                                  placeholder="最多输入200个字" data-options="validType:'maxLength[200]'"></textarea>
                    </div>
                <?php } else if ($value['InvstContent']['cnt_type'] == '4') { ?>
                    <div style="font-size: 14px;">
                        <p><?= $a ?>
                            、<?= $value['InvstContent']['cnt_tpc'] ?></p>
                        <label style="text-indent:2em;"><input name="judges<?= $a?>" type="radio" value="0"/>错误</label>
                        <label><input name="judges<?= $a?>" type="radio" value="0"/>正确</label>
                    </div>
                <?php } else{ $a--;?>
<!--                    <p style="font-size: 14px;margin-top: 30px;">--><?//= $a ?><!--、您没有选择该题目的类型，则无法显示该问题</p>-->
                    <?php }?>
                <?php $a++;
            } ?>
            <p style="margin-top: 20px;">答题完毕，非常感谢您参加本次问卷调查！</p>
            <div style="height: 50px;"></div>
            <div class="text-center">
                <button class="button-white-big"  id="btn_close" type="button">关闭</button>
            </div>
            <div style="height: 50px;"></div>
            <input id="invstid" type="hidden" value="<?= $invstid ?>">
            <input id="staff_id" type="hidden" value="<?= $staff_id ?>">
        </div>
    <?php } ?>
<?php } ?>
<script>
    //关闭页面
    $("#btn_close").click(function () {
//        var u = navigator.userAgent;
//        if(u.indexOf('Trident') > -1) {
//            history.go(-1);
//        }
//        else
//        {
//            window.opener = null;
//            window.open('', '_self');
//            window.close();
//        }
        parent.$.fancybox.close();
    })
</script>

