<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>
<style>
    .label-width {
        width: 100px;
    }

    .value-width {
        width: 200px !important;
    }

    .width-700 {
        width: 700px;
    }

    .width-519 {
        width: 519px;
    }

    .value-s {
        width: 120px;
    }

    .margin {
        margin-left: 120px;
    }

    .ml-3 {
        margin-left: 3px;
    }

    .ml-20 {
        margin-left: 20px;
    }
</style>
<h2 class="head-second">
    基本信息
</h2>

<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
<input type="hidden" id="staff_id" value="<?= $model['staff_id'] ?>">
<div class="mb-10">
    <div class="mb-10">
        <div class="inline-block field-hrstaff-staff_name ">
            <label for="hrstaff-staff_name" class="label-width"><span class="red">*</span>姓名</label><label>：</label>
            <input type="text" maxlength="50" name="HrStaff[staff_name]" class="value-width easyui-validatebox "
                   data-options="required:'true'" id="hrstaff-staff_name" value="<?= $model['staff_name'] ?>">
        </div>
        <div class="inline-block field-hrstaff-english_name">
            <label for="hrstaff-english_name" class="label-width">英文名</label><label>：</label>
            <input type="text" maxlength="50" name="HrStaff[english_name]" class="value-width" id="hrstaff-english_name"
                   value="<?= $model['english_name'] ?>">
        </div>
        <div class="inline-block field-hrstaff-former_name">
            <label for="hrstaff-former_name" class="label-width">曾用名</label><label>：</label>
            <input type="text" maxlength="50" name="HrStaff[former_name]" class="value-width" id="hrstaff-former_name"
                   value="<?= $model['former_name'] ?>">
        </div>
    </div>

    <div class="mb-10">
        <div class="inline-block field-hrstaff-staff_sex">
            <label for="hrstaff-staff_sex" class="label-width">性别</label><label>：</label>
            <select name="HrStaff[staff_sex]" class="value-width" id="hrstaff-staff_sex">
                <option value="">请选择...</option>
                <option value="男" <?= $model['staff_sex'] === '男' ? 'selected' : null ?>>男</option>
                <option value="女" <?= $model['staff_sex'] === '女' ? 'selected' : null ?>>女</option>
            </select>
        </div>
        <div class="inline-block field-hrstaff-staff_nation">
            <label for="hrstaff-staff_nation" class="label-width">民族</label><label>：</label>
            <input type="text" maxlength="20" name="HrStaff[staff_nation]" class="value-width" id="hrstaff-staff_nation"
                   value="<?= $model['staff_nation'] ?>">
        </div>
        <div class="inline-block field-hrstaff-staff_married">
            <label for="hrstaff-staff_married" class="label-width">婚否</label><label>：</label>
            <select name="HrStaff[staff_married]" class="value-width" id="hrstaff-staff_married">
                <option value="">请选择...</option>
                <option value="1" <?= $model['staff_married'] == 1 ? 'selected' : null ?>>已婚</option>
                <option value="0" <?= $model['staff_married'] == 0 ? 'selected' : null ?>>未婚</option>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrstaff-card_id">
            <label for="hrstaff-card_id" class="label-width">身份证号码</label><label>：</label>
            <input type="text" maxlength="20" name="HrStaff[card_id]" class="value-width" id="hrstaff-card_id"
                   value="<?= $model['card_id'] ?>">
        </div>
        <div class="inline-block field-hrstaff-birth_date">
            <label for="hrstaff-birth_date" class="label-width">出生年月日</label><label>：</label>
            <input type="text" placeholder="YYYY-MM-DD" readonly="readonly" name="HrStaff[birth_date]"
                   class="select-date value-width" id="hrstaff-birth_date" value="<?= $model['birth_date'] ?>">
        </div>
        <div class="inline-block field-hrstaff-staff_age">
            <label for="hrstaff-staff_age" class="label-width">年龄</label><label>：</label>
            <input type="text" name="HrStaff[staff_age]" class="value-width" id="hrstaff-staff_age"
                   value="<?= $model['staff_age'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrstaff-residence_type">
            <label for="hrstaff-residence_type" class="label-width">户口类型</label><label>：</label>
            <select name="HrStaff[residence_type]" class="value-width" id="hrstaff-blood_type">
                <option value="">请选择...</option>
                <option value="深圳户口" <?= $model['blood_type'] === '深圳户口' ? 'selected' : null ?>>深圳户口</option>
                <option value="非深圳户口" <?= $model['blood_type'] === '非深圳户口' ? 'selected' : null ?>>非深圳户口</option>
            </select>
        </div>
        <div class="inline-block field-hrstaff-native_place">
            <label for="hrstaff-native_place" class="label-width">籍贯</label><label>：</label>
            <input type="text" maxlength="20" name="HrStaff[native_place]" class="value-width" id="hrstaff-native_place"
                   value="<?= $model['native_place'] ?>">
        </div>
        <div class="inline-block field-hrstaff-blood_type">
            <label for="hrstaff-blood_type" class="label-width">血型</label><label>：</label>
            <select name="HrStaff[blood_type]" class="value-width" id="hrstaff-blood_type">
                <option value="">请选择...</option>
                <option value="AB" <?= $model['blood_type'] === 'AB' ? 'selected' : null ?>>AB</option>
                <option value="O" <?= $model['blood_type'] === 'O' ? 'selected' : null ?>>O</option>
                <option value="B" <?= $model['blood_type'] === 'B' ? 'selected' : null ?>>B</option>
                <option value="A" <?= $model['blood_type'] === 'A' ? 'selected' : null ?>>A</option>
            </select>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrstaff-health_condition">
            <label for="hrstaff-health_condition" class="label-width">健康状况</label><label>：</label>
            <input type="text" maxlength="30" name="HrStaff[health_condition]" class="value-width"
                   id="hrstaff-health_condition" value="<?= $model['health_condition'] ?>">
        </div>
        <div class="inline-block field-hrstaff-political_status">
            <label for="hrstaff-political_status" class="label-width">政治面貌</label><label>：</label>
            <input type="text" maxlength="30" name="HrStaff[political_status]" class="value-width"
                   id="hrstaff-political_status" value="<?= $model['political_status'] ?>">
        </div>
        <div class="inline-block field-hrstaff-party_time">
            <label for="hrstaff-party_time" class="label-width">入党时间</label><label>：</label>
            <input type="text" name="HrStaff[party_time]" placeholder="YYYY-MM-DD" class="value-width select-date"
                   id="hrstaff-party_time" value="<?= $model['party_time'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrstaff-staff_tel">
            <label for="hrstaff-staff_tel" class="label-width">联系电话</label><label>：</label>
            <input type="text" maxlength="20" name="HrStaff[staff_tel]" class="value-width easyui-validatebox"
                   id="hrstaff-staff_tel" value="<?= $model['staff_tel'] ?>">
        </div>
        <div class="inline-block field-hrstaff-staff_mobile">
            <label for="hrstaff-staff_mobile" class="label-width">手机号码</label><label>：</label>
            <input type="text" maxlength="20" name="HrStaff[staff_mobile]" class="value-width easyui-validatebox"
                   data-options="validType:'mobile'" id="hrstaff-staff_mobile" value="<?= $model['staff_mobile'] ?>">
        </div>
        <div class="inline-block field-hrstaff-staff_email">
            <label for="hrstaff-staff_email" class="label-width">电子邮件</label><label>：</label>
            <input type="text" maxlength="50" name="HrStaff[staff_email]" class="value-width" id="hrstaff-staff_email"
                   value="<?= $model['staff_email'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block field-hrstaff-staff_qq">
            <label for="hrstaff-staff_qq" class="label-width">QQ</label><label>：</label>
            <input type="text" name="HrStaff[staff_qq]" class="value-width" id="hrstaff-staff_qq"
                   value="<?= $model['staff_qq'] ?>">
        </div>
        <div class="inline-block field-hrstaff-other_contacts">
            <label for="hrstaff-other_contacts" class="label-width">其他联系方式</label><label>：</label>
            <input type="text" maxlength="50" name="HrStaff[other_contacts]" class="value-width"
                   id="hrstaff-other_contacts" value="<?= $model['other_contacts'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="width-700 field-hrstaff-card_address display-flex">
            <label for="hrstaff-card_address" class="label-width">家庭地址</label><label>：</label>
            <input type="text" maxlength="255" name="HrStaff[card_address]" class="width-519 ml-3"
                   id="hrstaff-card_address" value="<?= $model['card_address'] ?>">
        </div>
    </div>
    <div class="mb-10">
        <div class="width-700 field-hrstaff-residence_address display-flex">
            <label for="hrstaff-residence_address" class="label-width">户口所在地</label><label>：</label>
            <input type="text" maxlength="50" name="HrStaff[residence_address]" class="width-519 ml-3"
                   id="hrstaff-residence_address" value="<?= $model['residence_address'] ?>">
        </div>
    </div>
</div>
<h2 class="head-second">职位情况</h2>
<div class="mb-10">
    <div class="inline-block">
        <label for="hrstaff-staff_code" class="label-width"><span
                class="red">*</span>工号</label><label>：</label>
        <input type="text" maxlength="11" name="HrStaff[staff_code]" class="value-width easyui-validatebox re"
               data-options="required:'true'"
               id="hrstaff-staff_code" value="<?= $model['staff_code'] ?>">
    </div>
    <div class="inline-block">
        <label for="hrstaff-organization_code" class="label-width"><span
                class="red">*</span>部门</label><label>：</label>
        <input class="form-control value-width easyui-validatebox" readonly="readonly" data-options="required:'true'"
               id="hrstaff-organization_name" value="<?= $model['organization_name'] ?>">
        <input name="HrStaff[organization_code]" class="form-control value-width easyui-validatebox hiden"
               id="hrstaff-organization_code" value="<?= $model['organizationCode'] ?>">
    </div>
    <div class="inline-block">
        <label for="hrstaff-job_level" class="label-width">资位</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[job_level]" class="value-width" id="hrstaff-job_level"
               value="<?= $model['job_level'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block">
        <label for="hrstaff-position" class="label-width"><span class="red">*</span>管理职</label><label>：</label>
        <select name="HrStaff[position]" class="form-control value-width easyui-validatebox "
                data-options="required:'true'" id="hrstaff-position">
            <option value="">请选择</option>
            <?php foreach ($downList['staffTitle'] as $val) { ?>
                <option
                    value="<?= $val['title_id'] ?>" <?= $model['position'] == $val['title_name'] ? "selected" : null ?>><?= $val['title_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="inline-block field-hrstaff-employment_date">
        <label for="hrstaff-employment_date" class="label-width">入职时间</label><label>：</label>
        <input type="text" placeholder="YYYY-MM-DD" readonly="readonly" name="HrStaff[employment_date]"
               class="select-date value-width" id="hrstaff-employment_date" value="<?= $model['employment_date'] ?>">
    </div>
    <div class="inline-block field-hrstaff-job_task">
        <label for="hrstaff-job_task" class="label-width">职系</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[job_task]" class="value-width" id="hrstaff-job_task"
               value="<?= $model['job_task'] ?>">
    </div>

</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-job_title">
        <label for="hrstaff-job_title" class="label-width">职称</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[job_title]" class="value-width" id="hrstaff-job_title"
               value="<?= $model['job_title'] ?>">
    </div>
    <div class="inline-block field-hrstaff-job_title_level">
        <label for="hrstaff-job_title_level" class="label-width">职称级别</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[job_title_level]" class="value-width"
               id="hrstaff-job_title_level" value="<?= $model['job_title_level'] ?>">
    </div>
    <div class="inline-block field-hrstaff-administrative_level">
        <label for="hrstaff-administrative_level" class="label-width">行政级别</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[administrative_level]" class="value-width"
               id="hrstaff-administrative_level" value="<?= $model['administrative_level'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-staff_type">
        <label for="hrstaff-staff_type" class="label-width">员工类型</label><label>：</label>
        <input type="text" maxlength="30" name="HrStaff[staff_type]" class="value-width" id="hrstaff-staff_type"
               value="<?= $model['staff_type'] ?>">
    </div>
    <div class="inline-block field-hrstaff-attendance_type">
        <label for="hrstaff-attendance_type" class="label-width">考勤类型</label><label>：</label>
        <input type="text" maxlength="30" name="HrStaff[attendance_type]" class="value-width"
               id="hrstaff-attendance_type" value="<?= $model['attendance_type'] ?>">
    </div>
    <div class="inline-block field-hrstaff-salary_date">
        <label for="hrstaff-salary_date" class="label-width">起薪时间</label><label>：</label>
        <input type="text" placeholder="YYYY-MM-DD" readonly="readonly" name="HrStaff[salary_date]"
               class="select-date value-width" id="hrstaff-salary_date" value="<?= $model['salary_date'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-annual_leave">
        <label for="hrstaff-annual_leave" class="label-width">年休假</label><label>：</label>
        <input type="text" name="HrStaff[annual_leave]" class="value-width" id="hrstaff-annual_leave"
               value="<?= $model['annual_leave'] ?>">
    </div>
    <div class="inline-block field-hrstaff-staff_status">
        <label for="hrstaff-staff_status" class="label-width">员工状态</label><label>：</label>
        <select name="HrStaff[staff_status]" class="value-width" id="hrstaff-staff_status">
            <option value="">请选择...</option>
            <option value="10" <?= $model['staff_status'] == 10 ? "selected" : null ?>>在职</option>
            <option value="20" <?= $model['staff_status'] == 20 ? "selected" : null ?>>离职</option>
        </select>
    </div>
    <div class="inline-block field-hrstaff-staff_seniority">
        <label for="hrstaff-staff_seniority" class="label-width">工龄</label><label>：</label>
        <input type="text" name="HrStaff[staff_seniority]" class="value-width" id="hrstaff-staff_seniority"
               value="<?= $model['staff_seniority'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-work_time">
        <label for="hrstaff-work_time" class="label-width">参加工作时间</label><label>：</label>
        <input type="text" placeholder="YYYY-MM-DD" readonly="readonly" name="HrStaff[work_time]"
               class="select-date value-width" id="hrstaff-work_time" value="<?= $model['work_time'] ?>">
    </div>
    <div class="inline-block field-hrstaff-superior">
        <label for="hrstaff-superior" class="label-width">直属上级</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[superior]" class="value-width" id="hrstaff-superior"
               value="<?= $model['superior'] ?>">
    </div>
    <div class="inline-block field-hrstaff-subordinate">
        <label for="hrstaff-subordinate" class="label-width">直属下级</label><label>：</label>
        <input type="text" maxlength="50" name="HrStaff[subordinate]" class="value-width" id="hrstaff-subordinate"
               value="<?= $model['subordinate'] ?>">
    </div>
</div>

<div class="mb-10">
    <div class="inline-block field-hrstaff-opening_bank">
        <label for="hrstaff-opening_bank" class="label-width">开户行</label><label>：</label>
        <input type="text" maxlength="40" name="HrStaff[opening_bank]" class="value-width" id="hrstaff-opening_bank"
               value="<?= $model['opening_bank'] ?>">
    </div>
    <div class="inline-block field-hrstaff-bank_account">
        <label for="hrstaff-bank_account" class="label-width">银行账户</label><label>：</label>
        <input type="text" maxlength="40" name="HrStaff[bank_account]" class="value-width" id="hrstaff-bank_account"
               value="<?= $model['bank_account'] ?>">
    </div>
</div>
<div class="space-10"></div>
<h2 class="head-second">教育信息</h2>
<div class="mb-10">
    <div class="inline-block field-hrstaff-staff_diploma">
        <label for="hrstaff-staff_diploma" class="label-width">学历</label><label>：</label>
        <select name="HrStaff[staff_diploma]" class="value-width" id="hrstaff-staff_status">
            <option value="">请选择...</option>
            <option value="小学" <?= $model['staff_diploma'] == '小学' ? "selected" : null ?>>在职</option>
            <option value="初中" <?= $model['staff_diploma'] == '初中' ? "selected" : null ?>>初中</option>
            <option value="高中" <?= $model['staff_diploma'] == '高中' ? "selected" : null ?>>高中</option>
            <option value="中专/技校" <?= $model['staff_diploma'] == '中专/技校' ? "selected" : null ?>>中专/技校</option>
            <option value="大专" <?= $model['staff_diploma'] == '大专' ? "selected" : null ?>>大专</option>
            <option value="本科" <?= $model['staff_diploma'] == '本科' ? "selected" : null ?>>本科</option>
            <option value="硕士" <?= $model['staff_diploma'] == '硕士' ? "selected" : null ?>>硕士</option>
            <option value="博士" <?= $model['staff_diploma'] == '博士' ? "selected" : null ?>>博士</option>
        </select>
    </div>
    <div class="inline-block field-hrstaff-computer_level">
        <label for="hrstaff-staff_degree" class="label-width">学位</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[staff_degree]" class="value-width" id="hrstaff-staff_degree"
               value="<?= $model['staff_degree'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-staff_school">
        <label for="hrstaff-staff_school" class="label-width">毕业学校</label><label>：</label>
        <input type="text" maxlength="30" name="HrStaff[staff_school]" class="value-width" id="hrstaff-staff_school"
               value="<?= $model['staff_school'] ?>">
    </div>
    <div class="inline-block field-hrstaff-staff_graduation_date">
        <label for="hrstaff-staff_graduation_date" class="label-width">毕业日期</label><label>：</label>
        <input type="text" placeholder="YYYY-MM-DD" readonly="readonly" name="HrStaff[staff_graduation_date]"
               class="select-date value-width" id="hrstaff-staff_graduation_date"
               value="<?= $model['staff_graduation_date'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-staff_major">
        <label for="hrstaff-staff_major" class="label-width">专业</label><label>：</label>
        <input type="text" maxlength="30" name="HrStaff[staff_major]" class="value-width" id="hrstaff-staff_major"
               value="<?= $model['staff_major'] ?>">
    </div>
    <div class="inline-block field-hrstaff-computer_level">
        <label for="hrstaff-computer_level" class="label-width">计算机水平</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[computer_level]" class="value-width" id="hrstaff-computer_level"
               value="<?= $model['computer_level'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-languages_0">
        <label for="hrstaff-languages_0" class="label-width">外语语种1</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[languages_0]" class="value-width" id="hrstaff-languages_0"
               value="<?= $model['languages_0'] ?>">
    </div>
    <div class="inline-block field-hrstaff-language_level_0">
        <label for="hrstaff-language_level_0" class="label-width">外语水平1</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[language_level_0]" class="value-width"
               id="hrstaff-language_level_0" value="<?= $model['language_level_0'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-languages_1">
        <label for="hrstaff-languages_1" class="label-width">外语语种2</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[languages_1]" class="value-width" id="hrstaff-languages_1"
               value="<?= $model['languages_1'] ?>">
    </div>
    <div class="inline-block field-hrstaff-language_level_1">
        <label for="hrstaff-language_level_1" class="label-width">外语水平2</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[language_level_1]" class="value-width"
               id="hrstaff-language_level_1" value="<?= $model['language_level_1'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-languages_2">
        <label for="hrstaff-languages_2" class="label-width">外语语种3</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[languages_2]" class="value-width" id="hrstaff-languages_2"
               value="<?= $model['languages_2'] ?>">
    </div>
    <div class="inline-block field-hrstaff-language_level_2">
        <label for="hrstaff-language_level_2" class="label-width">外语水平3</label><label>：</label>
        <input type="text" maxlength="20" name="HrStaff[language_level_2]" class="value-width"
               id="hrstaff-language_level_2" value="<?= $model['language_level_2'] ?>">
    </div>
</div>
<div class="mb-10">
    <div class="inline-block field-hrstaff-specialty display-flex">
        <label for="hrstaff-specialty" class="label-width">特长</label><label>：</label>
        <input type="text" maxlength="100" name="HrStaff[specialty]" class="width-519 ml-3" id="hrstaff-specialty"
               value="<?= $model['specialty'] ?>">
    </div>
</div>
<div class="space-10"></div>
<h2 class="head-second">其他</h2>
<div class="display-flex field-hrstaff-job_situation">
    <label for="hrstaff-job_situation" class="label-width">职务情况</label><label>：</label>
    <textarea rows="4" maxlength="255" name="HrStaff[job_situation]" class="textarea-w800"
              id="hrstaff-job_situation"><?= $model['job_situation'] ?></textarea>

    <div style="margin-left:105px" class="error-notice"></div>
</div>
<div class="display-flex field-hrstaff-insurance_situation">
    <label for="hrstaff-insurance_situation" class="label-width">社保情况</label><label>：</label>
    <textarea rows="4" maxlength="255" name="HrStaff[insurance_situation]" class="textarea-w800"
              id="hrstaff-insurance_situation"><?= $model['insurance_situation'] ?></textarea>
</div>
<div class="display-flex">
    <label for="hrstaff-remark" class="label-width">备注</label><label>：</label>
    <textarea rows="4" maxlength="255" name="HrStaff[remark]" class="textarea-w800"
              id="hrstaff-remark"><?= $model['remark'] ?></textarea>
</div>
<div class="space-10"></div>
<div class="text-center">
    <button class="button-blue-big" type="submit">确认</button>
    &nbsp;
    <button onclick="history.go(-1)" class="button-white-big ml-20" type="button">返回</button>
</div>

<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        ajaxSubmitForm($("#add-form"));

        $("#hrstaff-staff_code").mouseleave(function () {
            $("#hrstaff-staff_code").validatebox({
                required: true,
                delay: 100000,
                validType: ["staff_code", "remote['<?= Url::to(['staff-validation'])?>" + '?code=' + $("#hrstaff-staff_code").val() + "&id=<?= $model['staff_id']?>" + "']"],
                invalidMessage: '工号已存在',
                missingMessage: '工号不能为空',
            })
        });

        $("#hrstaff-staff_email").validatebox({
            validType: "email"
        });

        //2填1
        $("#hrstaff-staff_tel,#hrstaff-staff_mobile").change(function () {
            var tel = $("#hrstaff-staff_tel").val();
            var mobile = $("#hrstaff-staff_mobile").val();
            if (tel == '' && mobile == '') {
                $("#hrstaff-staff_tel,#hrstaff-staff_mobile").validatebox({required: true});
            } else {
                $("#hrstaff-staff_tel,#hrstaff-staff_mobile").validatebox({required: false});
            }
        }).change();
        $("#hrstaff-organization_name").click(function () {
            $.fancybox({
                href: "<?=Url::to(['select-depart'])?>",
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 800,
                height: 520
            });
        });
    })
</script>
