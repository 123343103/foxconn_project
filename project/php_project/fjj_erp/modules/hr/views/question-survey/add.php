<?php
/**
 * User: F3859386
 * Date: 2017/10/20
 */
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use \yii\widgets\ActiveForm;
use app\assets\JeDateAsset;

JeDateAsset::register($this);
$this->title = '新增问卷调查';
$this->params['homeLike'] = ['label' => '人事资料'];//问卷列表
$this->params['breadcrumbs'][] = ['label' => '问卷列表', 'url' => 'index'];;
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .label-width {
        width: 80px;
    }

    .value-width {
        width: 600px;
    }

    .width-200 {
        width: 200px !important;
    }

    .select2-selection {
        width: 600px; /*分級分類輸入框寬度*/
        /*height: auto;!*分級分類輸入框高度樣式*!*/
        overflow: hidden;
    }
</style>
<?php $form = ActiveForm::begin(['id' => 'add_form']); ?>
<div class="content one_class">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <div style="background-color: #CFCFCF;height: 25px;line-height: 25px;padding-left: 15px;margin-bottom: 20px;">
        填写问卷信息（第一步）
    </div>
    <div class="mb-10">
        <input type="hidden" name="HrStaff[staff_code]" value="<?= Yii::$app->user->identity->staff_id ?>">
        <label class="label-align label-width"><span class="red">*</span>问卷类别：</label>
        <select id="invst_type" class="value-width value-align easyui-validatebox" name="BsQstInvst[invst_type]"
                data-options="required:true,validType:['length[0,50]','unique'],delay:1000000,validateOnBlur:true">
            <option value>请选择...</option>
            <?php foreach ($downList['questionType'] as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <span id="invst_type_err"></span>
    </div>
    <div class="mb-10">
        <label class="label-align label-width"><span class="red">*</span>问卷主题：</label>
        <input id="invst_subj" class="value-width value-align easyui-validatebox" type="text"
               name="BsQstInvst[invst_subj]"
               data-options="required:true,validType:['length[0,50]','unique'],delay:1000000,validateOnBlur:true"
               maxlength="49">
        <span id="invst_subj_err"></span>
    </div>
    <div class="mb-10">
        <label class="label-align label-width">主办单位：</label>
        <select id="invst_types" class="value-width value-align" name="BsQstInvst[invst_dpt]"
        >
            <!--            <option value>请选择...</option>-->
            <?php foreach ($downList['organization'] as $key => $val) { ?>
                <option
                    value="<?= $val['organization_id'] ?>" <?= isset($data['organization_code']) && $data['organization_code'] == $val['organization_code'] ? "selected" : null ?>><?= $val['organization_name'] ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-10">
        <label class="label-align label-width vertical-top">调查对象：</label>
        <?php echo Select2::widget([
            'name' => 'BsQstInvst[dpt_id]',
            'id' => 'invst_dpt',
//            'value' => ['red', 'green'], // initial value
            'data' => ArrayHelper::map($downList['organization'], 'organization_id', 'organization_name'),
            'options' => ['placeholder' => '请选择..', 'multiple' => true],
            'pluginOptions' => [
                'tags' => false,//输入时判断
                'maximumInputLength' => 10,
                'allowClear' => true,
            ],
        ]); ?>

    </div>
    <div class="mb-10">
        <label class="label-align label-width vertical-top"><span class="red">*</span>调查说明：</label>
        <textarea id="invst_dpts"
                  data-options="required:true,validType:['length[0,100]','unique'],delay:1000000,validateOnBlur:true"
                  class="value-align value-width" maxlength="100" rows="10" name="BsQstInvst[remarks]"
                  placeholder="最多输入100个字"></textarea>
        <span id="invst_dpts_err"></span>
    </div>
    <div class="mb-10">
        <label class="label-align label-width"><span class="red">*</span>调查时间：</label>
        <input type="text" name="BsQstInvst[invst_start]" readonly="readonly" id="start_time"
               class="Wdate width-200 value-align easyui-validatebox"
               data-options="required:'true',validType:'timeCompare'"/>
        至
        <input type="text" name="BsQstInvst[invst_end]" readonly="readonly" id="end_time"
               class="Wdate width-200 value-align easyui-validatebox"
               data-options="required:'true',validType:'timeCompare'"/>

    </div>
    <div class="mb-10">
        <label class="label-align label-width">题目数量：</label>
        <input id="number" value="" type="text"
               class="value-s qvalue-align Onlynum easyui-validatebox validatebox-text "
               data-options="validType:'int'"
               name="BsQstInvst[invst_nums]">
    </div>
    <div class="space-10"></div>
    <div style="margin-top: 20px;width:790px;" class="text-center">
        <button class="button-blue-big" type="button" id="btn_next">下一步</button>
        <button class="button-white-big" onclick="history.go(-1);" type="button">返回</button>
    </div>
</div>
<div class="content two_class" style="display:none;">
    <h1 class="head-first">
        <?= $this->title; ?>
    </h1>
    <div style="background-color: #CFCFCF;height: 25px;line-height: 25px;padding-left: 15px;margin-bottom: 20px;">
        填写问卷信息（第二步）
    </div>
    <h1 id="invst_subj2" style="text-align: center;"></h1>
    <div class="mb-10">
        <label class="label-align label-width">问卷类别：</label>
        <label id="invst_type1" class="label-align"></label>
    </div>
    <div class="mb-10">
        <label class="label-align label-width">主办单位：</label>
        <label id="invst_dpt2" class="label-align"></label>
    </div>
    <div class="mb-10">
        <label class="label-align label-width" style="float:left;">问卷说明：</label>
        <label id="invst_dpt1" class="label-align" style="width: 600px;text-align: left;"></label>
    </div>
    <div style="width:100%;height: 1px;border-bottom: 1px  dashed #ccc;"></div>
    <div style="height: 20px;"></div>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function () {
        $("input[type='text']").attr('onpaste', 'return false');//禁止粘贴
        $("textarea").attr('onpaste', 'return false');//禁止粘贴

        $(".Onlynum").numbervalid();//控制输入
        ajaxSubmitForm($("#add_form"), '', function (data) {
            if (data.flag == 2) {
                var url = "";
                layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        if (data.url != undefined) {
                            url = data.url + "?datas=" + data.datas;
                            window.open(url);
                        }
                    }
                });
            }
            else if (data.flag == 1) {
                layer.alert(data.msg, {
                    icon: 1,
                    end: function () {
                        if (data.url != undefined) {
                            location.href = data.url;
                        }
                    }
                });
            }
            else {
                layer.alert(data.msg, {icon: 0});
            }
        });
        /*时间验证*/
        $.extend($.fn.validatebox.defaults.rules, {
            timeCompare: {
                validator: function () {
                    var start_time = $('#start_time').val();
                    var end_time = $('#end_time').val();
                    if (start_time === '' || end_time === '') {
                        return true;
                    }
                    var diff = Date.parse(end_time.replace(/-/g, '/')) - Date.parse(start_time.replace(/-/g, '/'));
//                    var name = $(this).attr('id');
//                    if (name === 'start_time') {
//                        $.fn.validatebox.defaults.rules.timeCompare.message = '开始时间必须小于结束时间';
//                    }
//                    if (name === 'ent_time') {
//                        $.fn.validatebox.defaults.rules.timeCompare.message = '结束时间必须大于开始时间';
//                    }
                    return diff >= 0;
                },
                message: '时间错误'
            },
        });

    });

    var number = 0;

    //管控开始时间
    $("#start_time").click(function () {
        WdatePicker({
            onpicked: function (obj) {
                $(this).validatebox('validate');
            },
            skin: 'whyGreen',
//            minDate: setdate(),
            minDate: '%y-%M-{%d+1}',
            dateFmt: 'yyyy-MM-dd',
            isShowToday: false,
            maxDate: '#F{$dp.$D(\'end_time\');}'
        })
    });
    //结束时间
    $("#end_time").click(function () {
        if ($("#start_time").val() === '') {
            layer.alert('请先选择开始时间', {icon: 2});
            return false;
        }
        WdatePicker({
            onpicked: function (obj) {
                $(this).validatebox('validate');
                $('#start_time').validatebox('validate');
            },
            skin: 'whyGreen',
            isShowToday: false,
            dateFmt: 'yyyy-MM-dd',
            minDate: '#F{$dp.$D(\'start_time\');}'
        })
    });

    //日期框添加默认值
    function setdate() {
        var sd = new Date();
        sd.setDate(sd.getDate() + 1);
        var sy = sd.getFullYear();
        var sm = sd.getMonth() + 1;
        var sdd = sd.getDate();
        if (sm >= 1 && sm <= 9) {
            sm = "0" + sm;
        }
        if (sdd >= 0 && sdd <= 9) {
            sdd = "0" + sdd;
        }

        var ed = new Date();
        ed.setDate(ed.getDate() + 1);
        var ey = ed.getFullYear();
        var em = ed.getMonth() + 1;
        var edd = ed.getDate();
        if (em >= 1 && em <= 9) {
            em = "0" + em;
        }
        if (edd >= 0 && edd <= 9) {
            edd = "0" + edd;
        }
        return sy + "-" + sm + "-" + sdd;
    }
    //下一步
    $("#btn_next").click(function () {
        var falg = 0;
        if ($("#invst_type").val() == null || $("#invst_type").val() == "") {
            $("#invst_type").css("border", "1px solid #ffa8a8");
            $("#invst_type_err").text("类别不能为空");
            falg = 0;
        }
        else if ($("#invst_subj").val() == null || $("#invst_subj").val() == "") {
            $("#invst_subj").css("border", "1px solid #ffa8a8");
            $("#invst_type").css("border", "1px solid #ccc");
            $("#invst_type_err").text("");
            $("#invst_subj_err").text("主题不能为空");
            falg = 0;
        }
        else if ($("#invst_dpts").val() == null || $("#invst_dpts").val() == "") {
            $("#invst_dpts").css("border", "1px solid #ffa8a8");
            $(".selection").css("border", "1px solid #ccc");
            $("#invst_subj").css("border", "1px solid #ccc");
            $("#invst_type").css("border", "1px solid #ccc");
            $("#invst_types").css("border", "1px solid #ccc");
            $("#invst_type_err").text("");
            $("#invst_subj_err").text("");
            $("#invst_dpts_err").text("说明不能为空");
            falg = 0;
        }
        else if ($("#start_time").val() == null || $("#start_time").val() == "") {
            $("#start_time").css("border", "1px solid #ffa8a8");
            $("#invst_dpts").css("border", "1px solid #ccc");
            $(".selection").css("border", "1px solid #ccc");
            $("#invst_subj").css("border", "1px solid #ccc");
            $("#invst_type").css("border", "1px solid #ccc");
            $("#invst_types").css("border", "1px solid #ccc");
            $("#invst_type_err").text("");
            $("#invst_subj_err").text("");
            $("#invst_dpts_err").text("");
            falg = 0;
        }
        else if ($("#end_time").val() == null || $("#end_time").val() == "") {
            $("#end_time").css("border", "1px solid #ffa8a8");
            $("#start_time").css("border", "1px solid #ccc");
            $("#invst_dpts").css("border", "1px solid #ccc");
            $(".selection").css("border", "1px solid #ccc");
            $("#invst_subj").css("border", "1px solid #ccc");
            $("#invst_type").css("border", "1px solid #ccc");
            $("#invst_types").css("border", "1px solid #ccc");
            $("#invst_type_err").text("");
            $("#invst_subj_err").text("");
            $("#invst_dpts_err").text("");
            falg = 0;
        }
        else if ($("#number").val() == null || $("#number").val() == "") {
            $("#number").css("border", "1px solid #ffa8a8");
            $("#end_time").css("border", "1px solid #ccc");
            $("#start_time").css("border", "1px solid #ccc");
            $("#invst_dpts").css("border", "1px solid #ccc");
            $(".selection").css("border", "1px solid #ccc");
            $("#invst_subj").css("border", "1px solid #ccc");
            $("#invst_type").css("border", "1px solid #ccc");
            $("#invst_types").css("border", "1px solid #ccc");
            falg = 0;
        }
        else {
            $("#number").css("border", "1px solid #ccc");
            $("#end_time").css("border", "1px solid #ccc");
            $("#start_time").css("border", "1px solid #ccc");
            $("#invst_dpts").css("border", "1px solid #ccc");
            $(".selection").css("border", "1px solid #ccc");
            $("#invst_subj").css("border", "1px solid #ccc");
            $("#invst_type").css("border", "1px solid #ccc");
            $("#invst_types").css("border", "1px solid #ccc");
            $("#invst_type_err").text("");
            $("#invst_subj_err").text("");
            $("#invst_dpts_err").text("");
            falg = 1;
        }
//        if($("#start_time").val()==$("#end_time").val())
//        {
//            falg = 0;
//        }
        if ($("#number").val() <= 0 || $("#number").val() % 1 != 0) {
            falg = 0;
        }
        if (falg == 1) {
            $(".one_class").css("display", "none");
            $(".two_class").css("display", "block");
            $("#invst_subj2").html($("#invst_subj").val());
            if ($("#invst_types").val() == "" || $("#invst_types").val() == null) {
                $("#invst_types").find("option:selected").text("");
            }
            $("#invst_type1").html($("#invst_type").find("option:selected").text());
            $("#invst_dpt1").html($("#invst_dpts").val());
            $("#invst_dpt2").html($("#invst_types").find("option:selected").text());//主办单位
            var number = parseInt($("#number").val());
            var str = "";
            str += "<div class='th_class'>";
            for (var i = 0; i < number; i++) {
                str += "<div class='mb-10'>";
                str += "<p>" + (i + 1) + ".<input class='cnt_tpc' maxlength='100' name='product[" + i + "][InvstContent][cnt_tpc]' style='width: 890px;' type='text'></p>";
                str += "<span><input name='product[" + i + "][InvstContent][cnt_type]' type='radio' style='margin-left: 20px;' data-id='" + i + "' value='1'>单选</span>";
                str += "<span><input name='product[" + i + "][InvstContent][cnt_type]' type='radio' style='margin-left: 20px;' data-id='" + i + "' value='2'>多选</span>";
                str += "<span><input name='product[" + i + "][InvstContent][cnt_type]' type='radio' style='margin-left: 20px;' data-id='" + i + "' value='3'>文本框</span>";
                str += "<span><input name='product[" + i + "][InvstContent][cnt_type]' type='radio' style='margin-left: 20px;' data-id='" + i + "' value='4'>判断题</span>";
                str += "</div>";
            }
            str += "<div style='margin-top: 20px;width:790px;' class='text-center'>";
            str += "<button class='button-white-big' style='margin-right: 50px;' type='button' id='btn_look'>预览</button>";
            str += "<button class='button-blue-big' type='button' id='btn_save'>保存</button>";
            str += "<button id='back' class='button-white-big'  type='button'>返回</button>";
            str += "</div></div>";
            $(".two_class").append(str);
        }
        else {
//            layer.alert("数据有误！",{icon:0});
        }
    });
    //选择题目类型
    $(".two_class").delegate("input[type=radio]", "click", function () {
        var str1 = "";
        var str2 = "";
        var str3 = "";
        var str4 = "";
        var x = $(this).parent("span").parent("div").children('p')[1];
        var y = $(this).parent("span").parent("div").children('label')[0];
        var m = $(this).parent("span").parent("div").children('#optas')[0];
//        console.log(y);
        if ($(this).val() == "1") {//单选
            str4 = "<label class='label-align label-width' style='margin-left: 20px;'>选项数量：</label><input data-id='" + $(this).data("id") + "' style='width:100px;' id='optas'  class='value-align Onlynum' data-options='validType:&apos;int&apos;' type='text' value='4' >";
            str1 += "<p>";
            str1 += "<label style='margin-top: 10px;margin-left: 20px;' class='label-align'>A</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][0][opt_name]'  class='optas value-align'  type='text'  >";
            str1 += "<label style='margin-top: 10px;' class='label-align label-width'>B</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][1][opt_name]'  class='optas value-align'  type='text'  ></br>";
            str1 += "<label style='margin-top: 10px;margin-left: 20px;' class='label-align '>C</label><input maxlength='10' name='product[" + $(this).data("id") + "][InvstOptions][2][opt_name]'  class='optas value-align'  type='text'  >";
            str1 += "<label style='margin-top: 10px;' class='label-align label-width'>D</label><input maxlength='10' name='product[" + $(this).data("id") + "][InvstOptions][3][opt_name]'  class='optas value-align'  type='text'  ><input name='product[" + $(this).data("id") + "][InvstOptions][4][opt_name]' style='margin-left: 20px;vertical-align: middle;' type='checkbox' value='其他'>其他";
            str1 += "</p>";
            if (y != undefined) {
                y.parentNode.removeChild(y);
                m.parentNode.removeChild(m);
//                m.remove();
                y = $(this).parent("span").parent("div").children('label')[0];
                m = $(this).parent("span").parent("div").children('#optas')[0];
            }
            if (y == undefined) {
                $(this).parent("span").parent("div").append(str4);
            }
            if (x != undefined) {
//                x.remove();
                x.parentNode.removeChild(x);
                $(this).parent("span").parent("div").append(str1);
            }
            else {
                $(this).parent("span").parent("div").append(str1);
            }
        }
        else if ($(this).val() == "2") {//多选
            str4 = "<label class='label-align label-width' style='margin-left: 20px;'>选项数量：</label><input data-id='" + $(this).data("id") + "' style='width:100px;' id='optas'  class='value-align Onlynum' data-options='validType:&apos;int&apos;' type='text' value='4' >";
            str1 += "<p>";
            str1 += "<label style='margin-top: 10px;margin-left: 20px;' class='label-align'>A</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][0][opt_name]'  class='optas value-align'  type='text'  >";
            str1 += "<label style='margin-top: 10px;' class='label-align label-width'>B</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][1][opt_name]'  class='optas value-align'  type='text'  ></br>";
            str1 += "<label style='margin-top: 10px;margin-left: 20px;' class='label-align'>C</label><input maxlength='10' name='product[" + $(this).data("id") + "][InvstOptions][2][opt_name]'  class='optas value-align'  type='text'  >";
            str1 += "<label style='margin-top: 10px;' class='label-align label-width'>D</label><input maxlength='10' name='product[" + $(this).data("id") + "][InvstOptions][3][opt_name]'  class='optas value-align'  type='text'  ><input name='product[" + $(this).data("id") + "][InvstOptions][4][opt_name]' style='margin-left: 20px;vertical-align: middle;' type='checkbox' value='其他'>其他";
            str1 += "</p>";
            if (y != undefined) {
//                y.remove();
//                m.remove();
                y.parentNode.removeChild(y);
                m.parentNode.removeChild(m);
                y = $(this).parent("span").parent("div").children('label')[0];
            }
            if (y == undefined) {
                $(this).parent("span").parent("div").append(str4);
            }
            if (x != undefined) {
//                x.remove();
                x.parentNode.removeChild(x);
                $(this).parent("span").parent("div").append(str1);
            }
            else {
                $(this).parent("span").parent("div").append(str1);
            }
        }
        else if ($(this).val() == "3")//文本框
        {
            str2 += "<p>";
            str2 += "<textarea name='textareas' disabled class='' style='margin-left: 20px;width: 620px;height: 100px;' rows='3' maxlength='200'></textarea>";
            str2 += "</p>";
            if (y != undefined) {
//                y.remove();
//                m.remove();
                y.parentNode.removeChild(y);
                m.parentNode.removeChild(m);
            }
            if (x != undefined) {
//                x.remove();
                x.parentNode.removeChild(x);
                $(this).parent("span").parent("div").append(str2);
            }
            else {
                $(this).parent("span").parent("div").append(str2);
            }
        }
        else//判断
        {
            str3 += "<p class='mb-10'>";
            str3 += "<span><input name='radioTrue' type='radio' style='margin-left: 20px;' value='1'>正确</span>";
            str3 += "<span><input name='radioFase' type='radio' style='margin-left: 20px;' value='0'>错误</span>";
            str3 += "</p>";
            if (y != undefined) {
//                y.remove();
//                m.remove();
                y.parentNode.removeChild(y);
                m.parentNode.removeChild(m);
            }
            if (x != undefined) {
//                x.remove();
                x.parentNode.removeChild(x);
                $(this).parent("span").parent("div").append(str3);
            }
            else {
                $(this).parent("span").parent("div").append(str3);
            }
        }
        $(".Onlynum").numbervalid();//控制输入
    });

    //输入选项
    $(".two_class").delegate("#optas", "blur", function () {
        $(this).parent("div").children("p")[1].remove();
        var array = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
        var strs = "";
        strs += "<p>";
        for (var i = 1; i <= parseInt($(this).val()); i++) {
//            alert($(this).parent("div").children("span").children("input[type=radio]").val());
            if ((i) % 2 == 0 && i != parseInt($(this).val())) {
                strs += "<label class='label-align label-width' style='margin-top: 10px;'>" + array[i - 1] + "</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][" + (i - 1) + "][opt_name]'  class='optas value-align'  type='text'  >";
                strs += "</br>";
            }
            else if ((i) % 2 != 0) {
                strs += "<label class='label-align' style='margin-top: 10px;margin-left: 20px;'>" + array[i - 1] + "</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][" + (i - 1) + "][opt_name]'  class='optas value-align'  type='text'  >";
            }
            if (i == parseInt($(this).val())) {
                strs += "<label class='label-align label-width' style='margin-top: 10px;'>" + array[i - 1] + "</label><input maxlength='10' style='margin-top: 10px;' name='product[" + $(this).data("id") + "][InvstOptions][" + (i - 1) + "][opt_name]'  class='optas value-align'  type='text'  >";
                strs += "<input  name='product[" + $(this).data("id") + "][InvstOptions][" + (i) + "][opt_name]' style='vertical-align: middle;margin-left: 20px;' type='checkbox' value='其他'>其他";
            }
        }
        strs += "</p>";
        $(this).parent("div").append(strs);
    });
    //返回
    $(".two_class").delegate("#back", "click", function () {
//        if(number>0)
//        {
//            number--;
//        }
        $(".two_class").css("display", "none");
        $(".one_class").css("display", "block");
        $(".th_class").remove();
    });

    //预览
    $(".two_class").delegate("#btn_look", "click", function () {
        var u = navigator.userAgent;
        var data = $("form").serializeArray();
        var url = "";
        $.ajax({
            dataType: "json",
            data: data,
            url: "<?= Url::to(['looks'])?>",
            type: 'post',
            async: false,
            success: function (msg) {
                if (msg.flag == 2) {//u.indexOf('Firefox') > -1
                    if (msg.url != undefined) {
                        if(u.indexOf('Firefox') > -1) {//判断是否是Firefox
                            var newTab = window.open('about:blank');
                            url = msg.url + "?datas=" + msg.datas;
                            newTab.location.href = url;
                        }
                        else
                        {
                            url = msg.url + "?datas=" + msg.datas;
                            $('#btn_look').fancybox({
                                autoSize: false,
                                height: screen.availHeight,
                                width: screen.availWidth,
                                padding: [],
                                type: 'iframe',
                                href: url
                            });
                        }
                    }
                } else {
                    layer.alert("浏览失败！", {icon: 0});
                }
            },
            error: function (msg) {
                layer.alert("浏览失败！", {icon: 0});
            }
        });

    });
    //保存
    $(".two_class").delegate("#btn_save", "click", function () {
        $(this).prop("disabled", true);
        var sum = 0;

        $(".cnt_tpc").each(function () {
            if ($(this).val()) {
                sum++;
            }
        });
//        var data = $("form").serializeArray();
//        console.log(data.product);
        layer.confirm("问卷提交后将不能修改，是否确定提交该问卷?", {icon: 2},
            function () {
                layer.closeAll();
                if (sum > 0) {
                    $("form").submit();
                }
                else {
                    layer.alert("该问卷最少要填写一个问题才能保存！！", {icon: 0}, function () {
                        layer.closeAll();
//                            $("#btn_save").prop("disabled", false);
                    });
                }
            },
            function () {
                layer.closeAll();
//                    $("#btn_save").prop("disabled", false);
            }
        );
        $(this).prop("disabled", false);
    });

    //控制特殊字符的输入（禁止输入特殊字符）
    function showKeyPress(evt) {
        evt = (evt) ? evt : window.event;
        return checkSpecificKey(evt.keyCode);
    }
    function checkSpecificKey(keyCode) {
        var specialKey = "<>#$%\^*\'\{}/?;[]\+ ";//页面禁止输入的字符。[^u4e00-u9fa5w]
        var realkey = String.fromCharCode(keyCode);
        var flg = false;
        flg = (specialKey.indexOf(realkey) >= 0);
        if (flg) {
            //Confirmbox.alert('请勿输入特殊字符: ' + realkey);
            return false;
        }
        return true;
    }
    document.onkeypress = showKeyPress;
</script>
