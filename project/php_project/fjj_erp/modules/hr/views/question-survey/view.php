<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/10/26
 * Time: 上午 10:23
 */
use yii\helpers\Url;
if(empty($data['opt'])) {
    $this->title = '问卷详情';
}
else
{
    $this->title = '问卷调查';
}
?>
<div style="height: 20px;"></div>
<h1 style="text-align: center;"><?= $data['invstcontents'][0]['invst_subj'] ?><span style="color:red;">
        <?php if ($data['invstcontents'][0]['yn_close'] != 0) { ?>
            (<?= 已关闭 ?>)
        <?php } ?>
</span></h1>
<div style="height: 10px;"></div>
<?php if (!empty($data['invstcontent'])&&empty($data['opt'])) { ?>
    <div style="width: 1007px;min-height: 300px;margin: 0 auto;">
        <p style="font-size: 14px;">欢迎您在<?= $data['invstcontent'][0]['invst_start'] ?>
            ~<?= $data['invstcontent'][0]['invst_end'] ?>期间参加本次问卷调查</p>
        <div style="height: 10px;"></div>
        <label class="label-width qlabel-align"><span style="color:red;">*</span>类别</label><label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
               value="<?= $data['invstcontent'][0]['bsp_sname'] ?>" style="vertical-align:middle; line-height: 25px;">
        <label class="label-width qlabel-align" style="margin-left: 100px;"><span
                style="color:red;">*</span>主办单位</label>
        <label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
               value="<?= $data['invstcontent'][0]['organization_name'] ?>" style="vertical-align:middle;line-height: 25px;">
        <label class="label-width qlabel-align" style="margin-left: 90px;">调查对象</label><label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" style="width: 250px;vertical-align:middle;line-height: 25px;"
               class="value-width qvalue-align"
               value="<?= $data['invstcontent'][0]['survey_objects'] ?>">
        <div style="width:100%;height: 3px;background: #E4E4E4;margin-top: 5px"></div>
        <div style="height: 10px;"></div>
        <?php $a = 1;
        foreach ($data['invstcontent'] as $key => $value) { ?>
            <div style="font-size: 14px;">
                <p><?= $a ?>
                    、<?= $data['invstcontent'][$key]['cnt_tpc'] ?><?php if ($data['invstcontent'][$key]['cnt_type'] != '文本') { ?>【<?= $data['invstcontent'][$key]['cnt_type'] ?>】<?php } ?></p>
                <?php foreach ($data['invstoptions'] as $keys => $values) { ?>
                    <?php if ($data['invstoptions'][$keys]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                        <?php if ($data['invstcontent'][$key]['cnt_type'] == '单选') { ?>
                            <?php if (!empty($data['opt'])) { ?>
                                <?php foreach ($data['opt'] as $keyss => $valuess) { ?>
                                    <?php if ($data['opt'][$keyss]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                                        <p><input name="radios"
                                                  type="radio" <?= $data['invstoptions'][$keys]['opt_code'] == $data['opt'][$keyss]['opt_code'] ? "checked " : null; ?>
                                                  value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/><?= $data['invstoptions'][$keys]['opt_code'] ?>
                                            .<?= $data['invstoptions'][$keys]['opt_name'] ?>
                                            <?php if ($data['invstoptions'][$keys]['opt_name'] == '其它' || $data['invstoptions'][$keys]['opt_name'] == '其他') { ?>
                                                <input type='text' id="other"
                                                       style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                            <?php } ?>
                                        </p>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <p><input name="radios"
                                          type="radio"
                                          value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/><?= $data['invstoptions'][$keys]['opt_code'] ?>
                                    .<?= $data['invstoptions'][$keys]['opt_name'] ?>
                                    <?php if ($data['invstoptions'][$keys]['opt_name'] == '其它' || $data['invstoptions'][$keys]['opt_name'] == '其他') { ?>
                                        <input type='text' id="other"
                                               style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                    <?php } ?>
                                </p>
                            <?php } ?>
                        <?php } else if ($data['invstcontent'][$key]['cnt_type'] == '多选') { ?>
                            <p><input name="multiselects" type="checkbox" style="vertical-align: middle;"
                                      data-cntid="<?= $data['invstcontent'][$key]['cnt_id'] ?>"
                                      value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/>
                                <?= $data['invstoptions'][$keys]['opt_code'] ?>
                                .<?= $data['invstoptions'][$keys]['opt_name'] ?>
                                <?php if ($data['invstoptions'][$keys]['opt_name'] == '其它' || $data['invstoptions'][$keys]['opt_name'] == '其他') { ?>
                                    <input type='text' id="other"
                                           style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                <?php } ?>
                            </p>
                        <?php } else
                            if ($data['invstcontent'][$key]['cnt_type'] == '判断') { ?>
                                <?php if (!empty($data['opt'])) { ?>
                                    <?php foreach ($data['opt'] as $key1 => $value1) { ?>
                                        <?php if ($data['opt'][$key1]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                                            <label><input name="judges"
                                                          type="radio" <?= $data['invstoptions'][$keys]['opt_code'] == $data['opt'][$key1]['opt_code'] ? "checked " : null; ?>
                                                          value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/>
                                                <?= $data['invstoptions'][$keys]['opt_name'] ?></label>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <label><input name="judges" type="radio"
                                                  value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/>
                                        <?= $data['invstoptions'][$keys]['opt_name'] ?></label>
                                <?php } ?>
                            <?php } ?>
                    <?php } ?>
                <?php } ?>
                <?php if ($data['invstcontent'][$key]['cnt_type'] == '文本') { ?>
                    <?php if (!empty($data['answ'])) { ?>
                        <?php foreach ($data['answ'] as $key2 => $value2) { ?>
                            <?php if ($data['answ'][$key2]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                                <textarea name="a" class="easyui-validatebox validatebox-text"
                                          style="width: 620px;height: 100px;"
                                          rows="3" maxlength="200"
                                          placeholder="最多输入200个字"
                                          data-options="validType:'maxLength[200]'"><?= $data['answ'][$key2]['answs'] ?></textarea>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <textarea name="a" class="easyui-validatebox validatebox-text"
                                  style="width: 620px;height: 100px;"
                                  rows="3" maxlength="200"
                                  placeholder="最多输入200个字" data-options="validType:'maxLength[200]'"></textarea>
                    <?php } ?>
                <?php }
                 ?>
            </div>
            <?php $a++;
        } ?>
        <p style="margin-top: 20px;">答题完毕，非常感谢您参加本次问卷调查！</p>
        <div style="height: 50px;"></div>
        <div class="text-center">
            <button class="button-white-big" onclick="history.go(-1);" type="button">返回列表</button>
        </div>
        <div style="height: 50px;"></div>
        <input id="invstid" type="hidden" value="<?= $invstid ?>">
        <input id="answ_id" type="hidden" value="<?= $answ_id ?>">
    </div>
<?php } else if(!empty($data['opt'])&&!empty($data['invstcontent'])){ ?>
    <div style="width: 1007px;min-height: 300px;margin: 0 auto;">
        <p style="font-size: 14px;">欢迎您在<?= $data['invstcontent'][0]['invst_start'] ?>
            ~<?= $data['invstcontent'][0]['invst_end'] ?>期间参加本次问卷调查</p>
        <div style="height: 10px;"></div>
        <label class="label-width qlabel-align"><span style="color:red;">*</span>工号</label><label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
               value="<?= $data['opt'][0]['staff_code'] ?>" style="vertical-align:middle;line-height: 25px;">
        <label class="label-width qlabel-align" style="margin-left: 100px;"><span
                style="color:red;">*</span>姓名</label>
        <label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
               value="<?= $data['opt'][0]['staff_name'] ?>" style="vertical-align:middle;line-height: 25px;">
        <label class="label-width qlabel-align" style="margin-left: 90px;">部门</label><label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" style="width: 250px; vertical-align:middle;line-height: 25px;"
               class="value-width qvalue-align"
               value="<?= $data['opt'][0]['dpt_name'] ?>">
        <div style="width:100%;height: 3px;background: #E4E4E4;margin-top: 5px"></div>
        <div style="height: 10px;"></div>
        <?php $a = 1;
        foreach ($data['invstcontent'] as $key => $value) { ?>
            <div style="font-size: 14px;">
                <p><?= $a ?>
                    、<?= $data['invstcontent'][$key]['cnt_tpc'] ?><?php if ($data['invstcontent'][$key]['cnt_type'] != '文本') { ?>【<?= $data['invstcontent'][$key]['cnt_type'] ?>】<?php } ?></p>
                <?php foreach ($data['invstoptions'] as $keys => $values) { ?>
                    <?php if ($data['invstoptions'][$keys]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                        <?php if ($data['invstcontent'][$key]['cnt_type'] == '单选') { ?>
                            <?php if (!empty($data['opt'])) { ?>
                                <?php foreach ($data['opt'] as $keyss => $valuess) { ?>
                                    <?php if ($data['opt'][$keyss]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                                        <p><input name="radios"
                                                  type="radio" <?= $data['invstoptions'][$keys]['opt_code'] == $data['opt'][$keyss]['opt_code'] ? "checked " : null; ?>
                                                  value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/><?= $data['invstoptions'][$keys]['opt_code'] ?>
                                            .<?= $data['invstoptions'][$keys]['opt_name'] ?>
                                            <?php if ($data['invstoptions'][$keys]['opt_name'] == '其它' || $data['invstoptions'][$keys]['opt_name'] == '其他') { ?>
                                                <input type='text' id="other"
                                                       style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                            <?php } ?>
                                        </p>
                                    <?php } ?>
                                <?php } ?>
                            <?php } else { ?>
                                <p><input name="radios"
                                          type="radio"
                                          value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/><?= $data['invstoptions'][$keys]['opt_code'] ?>
                                    .<?= $data['invstoptions'][$keys]['opt_name'] ?>
                                    <?php if ($data['invstoptions'][$keys]['opt_name'] == '其它' || $data['invstoptions'][$keys]['opt_name'] == '其他') { ?>
                                        <input type='text' id="other"
                                               style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                    <?php } ?>
                                </p>
                            <?php } ?>
                        <?php } else if ($data['invstcontent'][$key]['cnt_type'] == '多选') { ?>
                            <p><input name="multiselects" type="checkbox" style="vertical-align: middle;"
                                      data-cntid="<?= $data['invstcontent'][$key]['cnt_id'] ?>"
                                      value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/>
                                <?= $data['invstoptions'][$keys]['opt_code'] ?>
                                .<?= $data['invstoptions'][$keys]['opt_name'] ?>
                                <?php if ($data['invstoptions'][$keys]['opt_name'] == '其它' || $data['invstoptions'][$keys]['opt_name'] == '其他') { ?>
                                    <input type='text' id="other"
                                           style='border:none;border-bottom:black solid 1px;background: none;height: 20px'>
                                <?php } ?>
                            </p>
                        <?php } else
                            if ($data['invstcontent'][$key]['cnt_type'] == '判断') { ?>
                                <?php if (!empty($data['opt'])) { ?>
                                    <?php foreach ($data['opt'] as $key1 => $value1) { ?>
                                        <?php if ($data['opt'][$key1]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                                            <label><input name="judges"
                                                          type="radio" <?= $data['invstoptions'][$keys]['opt_code'] == $data['opt'][$key1]['opt_code'] ? "checked " : null; ?>
                                                          value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/>
                                                <?= $data['invstoptions'][$keys]['opt_name'] ?></label>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else { ?>
                                    <label><input name="judges" type="radio"
                                                  value="<?= $data['invstoptions'][$keys]['opt_code'] ?>"/>
                                        <?= $data['invstoptions'][$keys]['opt_name'] ?></label>
                                <?php } ?>
                            <?php } ?>
                    <?php } ?>
                <?php } ?>
                <?php if ($data['invstcontent'][$key]['cnt_type'] == '文本') { ?>
                    <?php if (!empty($data['answ'])) { ?>
                        <?php foreach ($data['answ'] as $key2 => $value2) { ?>
                            <?php if ($data['answ'][$key2]['cnt_id'] == $data['invstcontent'][$key]['cnt_id']) { ?>
                                <textarea name="a" class="easyui-validatebox validatebox-text"
                                          style="width: 620px;height: 100px;"
                                          rows="3" maxlength="200"
                                          placeholder="最多输入200个字"
                                          data-options="validType:'maxLength[200]'"><?= $data['answ'][$key2]['answs'] ?></textarea>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <textarea name="a" class="easyui-validatebox validatebox-text"
                                  style="width: 620px;height: 100px;"
                                  rows="3" maxlength="200"
                                  placeholder="最多输入200个字" data-options="validType:'maxLength[200]'"></textarea>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php $a++;
        } ?>
        <p style="margin-top: 20px;">答题完毕，非常感谢您参加本次问卷调查！</p>
        <div style="height: 50px;"></div>
        <div class="text-center">
            <button class="button-white-big" onclick="history.go(-1);" type="button">返回列表</button>
        </div>
        <div style="height: 50px;"></div>
        <input id="invstid" type="hidden" value="<?= $invstid ?>">
        <input id="answ_id" type="hidden" value="<?= $answ_id ?>">
        <input id="opt" type="hidden" value="<?= $data['opt']?>">
    </div>
<?php } else{?>
    <div style="width: 1007px;min-height: 300px;margin: 0 auto;">
        <p style="font-size: 14px;">欢迎您在<?= $data['invstcontents'][0]['invst_start'] ?>
            ~<?= $data['invstcontents'][0]['invst_end'] ?>期间参加本次问卷调查</p>
        <div style="height: 10px;"></div>
        <label class="label-width qlabel-align"><span style="color:red;">*</span>类别</label><label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
               value="<?= $data['invstcontents'][0]['bsp_sname'] ?>" style="vertical-align:middle;line-height: 25px;">
        <label class="label-width qlabel-align" style="margin-left: 100px;"><span
                style="color:red;">*</span>主办单位</label>
        <label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" class="value-width qvalue-align"
               value="<?= $data['invstcontents'][0]['organization_name'] ?>" style="vertical-align:middle;line-height: 25px;">
        <label class="label-width qlabel-align" style="margin-left: 90px;">调查对象</label><label>：</label>
        <input type="text" disabled name="CrmCustomerInfoSearch[cust_sname]" style="width: 250px;vertical-align:middle;line-height: 25px;"
               class="value-width qvalue-align"
               value="<?= $data['invstcontents'][0]['survey_objects'] ?>">
        <div style="width:100%;height: 3px;background: #E4E4E4;margin-top: 5px"></div>
        <div style="height: 10px;"></div>
        <h1 style="text-align: center;">该调查问卷暂没有问题可填写！</h1>
        <div style="height: 30px;"></div>
        <div class="text-center">
            <button class="button-white-big" onclick="history.go(-1);" type="button">返回列表</button>
        </div>
    </div>
<?php }?>
<script>
    $(function () {
        $("input[name=multiselects]").each(function () {
            var cntid = $(this).data("cntid");
            var opt_code = $(this).val();
            var checkboxs = $(this).context;
            $.ajax({
                url: "<?=Url::to(['get-data-answer'])?>",
                data: {
                    "invstid": $("#invstid").val(),
                    "answ_id": $("#answ_id").val()
                },
                dataType: "json",
                type:'get',
                success: function (data) {
                    console.log(data.opt);
                    for (var i = 0; i < data.invstcontent.length; i++) {
                        if (cntid == data.invstcontent[i].cnt_id) {
                            if (data.opt != null) {
                                for (var j = 0; j < data.opt.length; j++) {
                                    if (cntid == data.opt[j].cnt_id && opt_code == data.opt[j].opt_code) {
                                        checkboxs.checked = true;
                                    }
                                }
                            }
                            if (data.answ != null) {
                                for (var a = 0; a < data.answ.length; a++) {
                                    if (cntid == data.answ[a].cnt_id) {
                                        $("#other").val(data.answ[a].answs);
                                    }
                                }
                            }
                        }
                    }
                }
            })
        })
    })
</script>
