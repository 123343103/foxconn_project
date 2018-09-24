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

    .width-100{
        width:100px;
    }
    .label-width{
        width:80px;
    }

    .value-width{
        width:200px !important;
    }
    .ml-220{
        margin-left: 220px;
    }

    .width-600{
        width:708px;
    }

</style>
<div class="content">
    <h1 class="head-first">
        <?= $this->title; ?>
        <?php if(!empty($sih)){ ?>
            <span class="head-code">编号：<?= $sih['sih_code'] ?></span>
        <?php } ?>
    </h1>
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div>
        <h2 class="head-second">
            客户信息
            <span class="width-100">
                <?php if(empty($id)) { ?>
                    <a href="<?= Url::to(['select-customer']) ?>" id="select_customer">选择客户</a>
                <?php } ?>
            </span>
        </h2>
        <div class="customer display-none mb-10">
            <input type="hidden" name="CrmVisitRecord[sih_id]" class="sih_id" value="<?= $member['sih_id'] ?>">
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
            回访信息
        </h2>
        <?php if(\Yii::$app->controller->action->id == "create" || \Yii::$app->controller->action->id == "visit-create"){ ?>
            <div class="mb-10">
                <input type="hidden" name="CrmVisitRecordChild[sil_id]" class="sil_id" value="<?= $child['sil_id'] ?>">
                <label class="label-width label-align"><span class="red">*</span>拜访人</label><label>:</label>
                <input type="hidden" class="staff_code value-width value-align easyui-validatebox" data-options="required:'true'"
                       name="CrmVisitRecordChild[sil_staff_code]"
                       value="<?= $child['sil_staff_code'] ? $child['sil_staff_code'] : \Yii::$app->user->identity->staff->staff_code ?>">
                <input type="text" class="staff_code_name value-align value-width easyui-validatebox" data-options="required:'true',validType:'checkCode',delay:10000,validateOnBlur:'true'" data-url="<?= Url::to(['/hr/staff/get-staff-info']) ?>"
                       value="<?= $child['sil_staff_code'] ? $child['sil_staff_code'].'--;'.$child['visitPerson'] : \Yii::$app->user->identity->staff->staff_code.'--'.\Yii::$app->user->identity->staff->staff_name ?>">

                <label class="ml-220 label-width label-align"><span class="red">*</span>拜访类型</label><label>:</label>
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
            <label class="ml-220 label-width label-align">拜访类型</label><label>:</label>
            <span class="value-width value-align"><?= $child['visitTypeName'] ?></span>
        <?php } ?>
        <div class="mb-10">
            <label class="label-width label-align"><span class="red">*</span>开始时间</label><label>:</label>
            <input type="text" name="arriveDate" readonly="readonly" id="start_time" class="Wdate value-width value-align easyui-validatebox" data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', maxDate: '%y-%M-%d %H:%m,#F{$dp.$D(\'end_time\');}' })" value="<?= $child['start'] ?>" />
            <label class="ml-220 label-width label-align"><span class="red">*</span>结束时间</label><label>:</label>
            <input type="text" readonly="readonly" id="end_time" class="Wdate value-width value-align easyui-validatebox"  data-options="required:'true',validType:'timeCompare'"  onclick="WdatePicker({ onpicked: function (obj) { $(this).validatebox('validate');$('#start_time').validatebox('validate'); }, skin: 'whyGreen', dateFmt: 'yyyy-MM-dd HH:mm', minDate: '#F{$dp.$D(\'start_time\');}',maxDate:'%y-%M-%d %H:%m' })" name="leaveDate" value="<?= $child['end'] ?>" />
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top"><span class="red">*</span>拜访内容</label><label class="vertical-top">:</label>
            <textarea rows="3" name="CrmVisitRecordChild[sil_process_descript]" class="easyui-validatebox width-600" data-options="required:true,validType:'maxLength[200]'" maxlength="200" placeholder="最多输入200个字"><?= $child['sil_process_descript'] ?></textarea>
            <!--            <span class="red surplus">--><?//= isset($child['sil_process_descript'])?strlen($child['sil_process_descript']):'0'; ?><!--/200</span>-->
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top"><span class="red">*</span>反馈/总结</label><label class="vertical-top">:</label>
            <textarea rows="3" name="CrmVisitRecordChild[sil_interview_conclus]" class="easyui-validatebox width-600  value-align" data-options="required:true,validType:'maxLength[200]'" placeholder="最多输入200个字" maxlength="200"><?= $child['sil_interview_conclus'] ?></textarea>
            <!--            <span class="red surplus">--><?//= isset($child['sil_interview_conclus'])?strlen($child['sil_interview_conclus']):'0'; ?><!--/200</span>-->
        </div>
        <div class="mb-10">
            <label class="label-width label-align vertical-top">备注</label><label class="vertical-top">:</label>
            <textarea rows="3" name="CrmVisitRecordChild[remark]" class="width-600 value-align easyui-validatebox" data-options="validType:'maxLength[200]'" maxlength="200" placeholder="最多输入200个字"><?= $child['remark'] ?></textarea>
            <!--            <span class="red surplus"onkeyup="surplus(this,200);" >--><?//= isset($child['remark'])?strlen($child['remark']):'0'; ?><!--/200</span>-->
        </div>
    </div>
    <div style="margin-top: 40px;width:790px;" class="text-center">
<!--        <input class="button-blue-big sub display-none" type="submit" value="确定">-->
        <button class="button-blue-big" type="submit">确定</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    $(function () {
        <?php if(!empty($id)){ ?>
        $('.customer').removeClass('display-none');
        <?php } ?>
//        ajaxSubmitForm($("#add-form"));
        ajaxSubmitForm($('#add-form'),function(){
            var result;
            var code = $('.staff_code').val();
            var start = Date.parse($("#start_time").val().replace(/-/g,"/"));
            var end = Date.parse($("#end_time").val().replace(/-/g,"/"));
            var id = $('.cust_id').val();
            var childId = '<?= $childId ?>';
            if(id === ''){
                layer.alert("请选择客户!", {icon: 2, time: 5000});
                return false;
            }
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {'id':id,'childId':childId,"code": code,'start':start,'end':end},
                url: "<?=Url::to(['/crm/crm-return-visit/check-time']) ?>",
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
//        $('.but').click(function(){
//            var id = $('.cust_id').val();
//            if(id === ''){
//                layer.alert("请选择客户!", {icon: 2, time: 5000});
//                return false;
//            }
//            var code = $('.staff_code').val();
//            var start = Date.parse($("#start_time").val().replace(/-/g,"/"));
//            var end = Date.parse($("#end_time").val().replace(/-/g,"/"));
//            var childId = '<?//= $childId ?>//';
//            $.ajax({
//                type: "get",
//                dataType: "json",
//                async: false,
//                data: {'id':id,'childId':childId,"code": code,'start':start,'end':end},
//                url: "<?//=Url::to(['/crm/crm-return-visit/check-time']) ?>//",
//                success: function (data) {
//                    if(data === '0'){
//                        $('.sub').click();
//                        return ajaxSubmitForm($('#add-form'));
//                    }else{
//                        layer.alert("该时间段已有记录!", {icon: 2, time: 5000});
//                        return false;
//                    }
//                }
//            })
//        })

        $('.disName').on("change", function () {
            var $select = $(this);
            var $url = "<?=Url::to(['/ptdt/firm/get-district']) ?>";
            getNextDistrict($select, $url, "select");
        });

        //选择客户弹出框
        $("#select_customer").fancybox({
            padding: [],
            fitToView: false,
            width: 670,
            height: 570,
            autoSize: false,
            closeClick: false,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe'
        });

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
                        if (name === 'end_time') {
                            $.fn.validatebox.defaults.rules.timeCompare.message = '结束时间必须大于开始时间';
                        }
                        return diff > 0;
                    },
                    message: '时间错误'
                },
            }
        );
    });

//    function setStaff(obj) {
//        var url = "<?//= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>//";
//        staffInfo(obj, url);
//    }

</script>
