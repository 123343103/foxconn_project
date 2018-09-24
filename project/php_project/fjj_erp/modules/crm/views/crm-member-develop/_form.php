<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/23
 * Time: 10:31
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\assets\JeDateAsset;
JeDateAsset::register($this);
?>
<style>

    .label-width{
        width:80px;
    }
    .value-width{
        width:200px !important;
    }
    .width-300{
        width:300px;
    }
    .ml-50{
        margin-left:220px;
    }

</style>
<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<div>
    <h2 class="head-second">
        客户信息
    </h2>
    <div class="mb-10">
        <input type="hidden" name="CrmVisitRecord[cust_id]" class="cust_id" value="<?= $member['cust_id'] ?>">
        <table width="90%" class="no-border vertical-center mb-10">
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">公司名称<label>：</label></td>
                <td class="no-border vertical-center cust_sname" width="18%"><?= $member['cust_sname'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">公司简称<label>：</label></td>
                <td class="no-border vertical-center cust_sname" width="18%"><?= $member['cust_sname'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">公司电话<label>：</label></td>
                <td class="no-border vertical-center cust_tel1" width="18%"><?= $member['cust_tel1'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">联系人<label>：</label></td>
                <td class="no-border vertical-center cust_contacts" width="18%"><?= $member['cust_contacts'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">职位<label>：</label></td>
                <td class="no-border vertical-center cust_position" width="18%"><?= $member['cust_position'] ?></td>
                <td class="no-border vertical-center qlabel-align" width="5%">联系方式<label>：</label></td>
                <td class="no-border vertical-center cust_tel2" width="18%"><?= $member['cust_tel2'] ?></td>
            </tr>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center qlabel-align" width="5%">详细地址<label>：</label></td>
                <td class="no-border vertical-center qvalue-align cust_adress" colspan="3"
                    width="89.2%"><?= $member['district'][0]['district_name'] . $member['district'][1]['district_name'] . $member['district'][2]['district_name'] . $member['district'][3]['district_name'] . $member['cust_adress'] ?></td>
            </tr>
        </table>
    </div>
</div>
<div>
    <h2 class="head-second">
        拜访信息
    </h2>
    <?php if(\Yii::$app->controller->action->id == "visit-create"){ ?>
    <div class="mb-10">
        <input type="hidden" name="CrmVisitRecordChild[sil_id]" class="sil_id" value="<?= $child['sil_id'] ?>">
        <label class="label-width label-align"><span class="red">*</span>拜访人</label><label>:</label>
        <input type="hidden" class="staff_code value-width value-align easyui-validatebox" data-options="required:'true'"
               name="CrmVisitRecordChild[sil_staff_code]"
               value="<?= $child['sil_staff_code'] ? $child['sil_staff_code'] : \Yii::$app->user->identity->staff->staff_code ?>">
        <input type="text" class="staff_code_name value-align value-width easyui-validatebox" data-options="required:'true',validType:'checkCode',delay:10000,validateOnBlur:'true'" onblur="setStaff(this)" data-url="<?= Url::to(['/hr/staff/get-staff-info']) ?>"
               value="<?= $child['sil_staff_code'] ? $child['sil_staff_code'].'--;'.$child['visitPerson'] : \Yii::$app->user->identity->staff->staff_code.'--'.\Yii::$app->user->identity->staff->staff_name ?>">

        <label class="ml-50 label-width label-align"><span class="red">*</span>拜访类型</label><label>:</label>
        <select name="CrmVisitRecordChild[sil_type]" class="value-width value-align easyui-validatebox"
                data-options="required:'true'">
            <option value="">请选择</option>
            <?php if (!empty($downList['visitType'])) { ?>
                <?php foreach ($downList['visitType'] as $key => $val) { ?>
                    <option
                        value="<?= $key ?>" <?= $child['sil_type'] == $key ? "selected" : null; ?>><?= $val ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    </div>
    <?php }else{ ?>
        <label class="label-width label-align">拜访人</label><label>:</label>
        <input type="hidden" class="staff_code value-width value-align easyui-validatebox" data-options="required:'true'"
               name="CrmVisitRecordChild[sil_staff_code]"
               value="<?= $child['sil_staff_code'] ?>">
        <span class="value-width value-align"><?= $child['sil_staff_code'].'&nbsp;'.$child['visitPerson'] ?></span>
        <label class="ml-50 label-width label-align">拜访类型</label><label>:</label>
        <span class="value-width value-align"><?= $child['visitTypeName'] ?></span>
    <?php } ?>
<!--    <div class="mb-10">-->
<!--        <label class="label-width label-align"><span class="red">*</span>开始时间</label><label>:</label>-->
<!--        <input class="value-width value-align easyui-validatebox" id="start_time" data-options="required:'true',validType:['timeCompare','startCompare']" name="arriveDate" onfocus="this.blur()"  value="--><?//= $child['start'] ?><!--">-->
<!--        <label class="ml-50 label-width label-align"><span class="red">*</span>结束时间</label><label>:</label>-->
<!--        <input class="value-width value-align easyui-validatebox" id="end_time" data-options="required:'true',validType:['timeCompare','endCompare']"-->
<!--               name="leaveDate" onfocus="this.blur()" value="--><?//= $child['end'] ?><!--">-->
<!---->
<!--    </div>-->
    <div class="mb-10">
        <label class="label-width label-align"><span class="red">*</span>开始时间</label><label>:</label>
        <input type="text" name="arriveDate" readonly="readonly" id="start_time" class="Wdate value-width value-align easyui-validatebox" data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', maxDate: '%y-%M-%d %H:%m,#F{$dp.$D(\'end_time\');}' })" value="<?= $child['start'] ?>" />
        <label class="ml-50 label-width label-align"><span class="red">*</span>结束时间</label><label>:</label>
        <input type="text" readonly="readonly" id="end_time" class="Wdate value-width value-align easyui-validatebox"  data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate');$('#start_time').validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', minDate: '#F{$dp.$D(\'start_time\');}',maxDate:'%y-%M-%d %H:%m' })" name="leaveDate" value="<?= $child['end'] ?>" />
    </div>

    <div class="mb-10">
        <label class="label-width label-align vertical-top"><span class="red">*</span>拜访内容</label><label class="vertical-top">:</label>
        <textarea style="width:710px;height: 50px;" name="CrmVisitRecordChild[sil_process_descript]"
                  class="easyui-validatebox" data-options="required:true,validType:'maxLength[200]'"
                  maxlength="200" placeholder="最多输入200个字"><?= $child['sil_process_descript'] ?></textarea>
    </div>
    <div class="mb-10">
        <label class="label-width label-align vertical-top"><span class="red">*</span>反馈/总结</label><label class="vertical-top">:</label>
        <textarea style="width:710px;height: 50px;" name="CrmVisitRecordChild[sil_interview_conclus]"
                  class="easyui-validatebox" data-options="required:true,validType:'maxLength[200]'"
                  maxlength="200" placeholder="最多输入200个字"><?= $child['sil_interview_conclus']?></textarea>
    </div>
    <div class="mb-10">
        <label class="label-width label-align vertical-top">备注</label><label class="vertical-top">:</label>
        <textarea style="width:710px;height: 50px;" name="CrmVisitRecordChild[remark]" class="easyui-validatebox" data-options="validType:'maxLength[200]'"
                  maxlength="200" placeholder="最多输入200个字"><?= $child['remark'] ?></textarea>
    </div>
</div>


<div style="margin-top: 40px;width:790px;" class="text-center">
    <button class="button-blue-big" type="submit" value="确定">确定</button>
    <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
</div>
<?php ActiveForm::end() ?>
</div>
<script>
    $(function () {
        ajaxSubmitForm($('#add-form'),function(){
            var result;
            var code = $('.staff_code').val();
            var start = Date.parse($("#start_time").val().replace(/-/g,"/"));
            var end = Date.parse($("#end_time").val().replace(/-/g,"/"));
            var id = $('.cust_id').val();
            var childId = '<?= $childId ?>';
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {'id':id,'childId':childId,"code": code,'start':start,'end':end},
                url: "<?=Url::to(['/crm/crm-member-develop/check-time']) ?>",
                success: function (data) {
                    if(data === '0'){
                        result = true;
                    }else{
                        layer.alert("您该时间段已有记录!", {icon: 2, time: 5000});
                        result = false;
                    }
                }
            })
            return result;
        })

        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });

        //选择客户弹出框
        $("#select_customer").fancybox({
            padding: [],
            fitToView: false,
            width: 800,
            height: 570,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });

        var ctype = '<?= $ctype ?>';
        /*编辑客户信息-start-*/
        $("#updateCust").on('click', function () {
            var id = $(".cust_id").val();
            var url = "<?= Url::to(['update']) ?>?id=" + id + "&type=1";
            if (id == '') {
                layer.alert("请选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                if (ctype == '6' || ctype == '3') {
                    url += "&ctype=1";
                }
                $("#updateCust").fancybox({
                    href: url,
                    padding: [],
                    fitToView: false,
                    width: 800,
                    height: 550,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                });
            }
        })
        /*编辑客户信息-end-*/
        /*新增提醒消息 -Start-*/
        $("#addReminder").on('click', function () {
            var id = $(".cust_id").val();
            if (id == '') {
                layer.alert("请选择一条客户信息!", {icon: 2, time: 5000});
            } else {
                $("#addReminder").attr('href', '<?= Url::to(['create-reminders']) ?>?id=' + id);
                $("#addReminder").fancybox({
                    padding: [],
                    fitToView: false,
                    width: 700,
                    height: 450,
                    autoSize: false,
                    closeClick: false,
                    openEffect: 'none',
                    closeEffect: 'none',
                    type: 'iframe'
                });
            }

        });
        /*新增提醒消息 -end-*/

//        $('#start_time').jeDate({
//            format: "YYYY-MM-DD hh:mm",
//            maxDate: $.nowDate({DD: 0}).substring(0, 10) + ' 23:59:59',
//            okfun: function (obj) {
//                $('#end_time').validatebox('validate');
//                $(obj.elem[0]).validatebox('validate');
//            },
//            clearfun: function (elem, val) {
//                $(elem.elem[0]).validatebox('validate');
//            }
//        });
//        $('#end_time').jeDate({
//            format: "YYYY-MM-DD hh:mm",
//            maxDate: $.nowDate({DD: 0}).substring(0, 10) + ' 23:59:59',
//            okfun: function (obj) {
//                $('#start_time').validatebox('validate');
//                $(obj.elem[0]).validatebox('validate');
//            },
//            clearfun: function (elem, val) {
//                $(elem.elem[0]).validatebox('validate');
//            }
//        });
        $.extend($.fn.validatebox.defaults.rules, {
                timeCompare: {
                    validator: function () {
                        var start_time = $('#start_time').val();
                        var end_time = $('#end_time').val();
                        if (start_time === '' || end_time === '') {
                            return true;
                        }
                        var diff = Date.parse(end_time.replace(/-/g,'/')) - Date.parse(start_time.replace(/-/g,'/'));
                        var name = $(this).attr('id');
                        if (name === 'start_time') {
                            $.fn.validatebox.defaults.rules.timeCompare.message = '开始时间必须小于结束时间';
                        }
                        if (name === 'ent_time') {
                            $.fn.validatebox.defaults.rules.timeCompare.message = '结束时间必须大于开始时间';
                        }
                        return diff > 0;
                    },
                    message: '时间错误'
                },
                startCompare: {
                    validator: function () {
                        var start_time = $('#start_time').val();
                        if (start_time === '') {
                            return true;
                        }
                        var diff = Date.parse(start_time.replace(/-/g,'/')) - new Date().getTime();
                        $.fn.validatebox.defaults.rules.startCompare.message = '时间必须小于当前时间';
                        return diff < 0;
                    },
                    message: '时间必须小于当前时间'
                },
                endCompare: {
                    validator: function () {
                        var end_time = $('#end_time').val();
                        if (end_time === '') {
                            return true;
                        }
                        var diff = Date.parse(end_time.replace(/-/g,'/')) - new Date().getTime();
                        $.fn.validatebox.defaults.rules.endCompare.message = '时间必须小于当前时间';
                        return diff < 0;
                    },
                    message: '时间必须小于当前时间'
                },
            }
        );

    })
    function setStaff(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj,url);
    }

</script>
