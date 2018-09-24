<?php

Use  \yii\helpers\Url;

$this->params['homeLike'] = ['label' => '人事管理'];
$this->params['breadcrumbs'][] = ['label' => '人事信息列表'];
$this->params['breadcrumbs'][] = ['label' => '人事资料详情'];
$this->title = '人事资料详情：'.$model['staff_name'];
?>
<style>
    .label-width {
        width: 100px;
    }

    .value-width {
        width: 150px !important;
    }
</style>
<div class="content">
    <h1 class="head-first">
        人事资料详情&nbsp;&nbsp;&nbsp;&nbsp;工号：<?= $model['staff_code'] ?>
    </h1>
    <div class="mb-30">
        <h2 class="head-second">
            基本信息
        </h2>
        <div class="mb-10">
            <label class="label-width label-align">姓名：</label>
            <span class="value-width value-align"><?= $model['staff_name']?></span>
            <label class="label-width label-align">英文名：</label>
            <span class="value-align value-width"><?= $model['english_name']?></span>
            <label class="label-width label-align">曾用名：</label>
            <span class="width-value width-align"><?= $model['former_name'] ?></span>

        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">性别：</label>
            <span class="value-align value-width"><?= $model['staff_sex'] ?></span>
            <label class="label-width label-align">民族：</label>
            <span class="value-align value-width"><?= $model['staff_nation'] ?></span>
            <label class="label-width label-align">婚否：</label>
            <span class="value-align value-width"><?= $model['staff_married']==1?'已婚':"未婚" ?></span>

        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align ">身份证号码：</label>
            <span class="value-align value-width"><?= $model['card_id']?></span>
            <label class="label-width label-align">出生年月日：</label>
            <span class="value-align value-width"><?= $model['birth_date'] ?></span>
            <label class="label-width label-align">年龄：</label>
            <span class="value-align value-width"><?= $model['staff_age'] ?></span>
        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">户口类型：</label>
            <span class="value-align value-width"><?= $model['residence_type'] ?></span>
            <label class="label-width label-align">籍贯：</label>
            <span class="value-align value-width"><?= $model['native_place'] ?></span>
            <label class="label-width label-align">血型：</label>
            <span class="value-align value-width"><?= $model['blood_type'] ?></span>


        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">健康状况：</label>
            <span class="value-align value-width"><?= $model['health_condition'] ?></span>
            <label class="label-width label-align">政治面貌：</label>
            <span class="value-align value-width"><?= $model['political_status'] ?></span>
            <label class="label-width label-align">入党时间：</label>
            <span class="value-align value-width"><?= $model['party_time'] ?></span>
        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">联系电话：</label>
            <span class="value-align value-width"><?= $model['staff_tel'] ?></span>
            <label class="label-width label-align">手机号码：</label>
            <span class="value-align value-width"><?= $model['staff_mobile'] ?></span>
            <label class="label-width label-align">电子邮箱：</label>
            <span class="value-align value-width"><?= $model['staff_email'] ?></span>

        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">QQ：</label>
            <span class="value-align value-width"><?= $model['staff_qq'] ?></span>
            <label class="label-width label-align">其他联系方式：</label>
            <span class="value-align value-width"><?= $model['other_contacts'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width">家庭住址：</label>
            <span class="width-700 text-top"><?= $model['card_address'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width">户口所在地：</label>
            <span class="width-700 text-top"><?= $model['residence_address']?></span>
        </div>
    </div>
    <div>
        <h2 class="head-second">
            职位情况
        </h2>
        <div class="mb-10">
            <label class="label-width label-align">工号：</label>
            <span class="value-width value-align"><?= $model['staff_code'] ?></span>
            <label class="label-width label-align">部门：</label>
            <span class="value-width value-align"><?= $model['organization_code'] ?></span>
            <label class="label-width label-align">资位：</label>
            <span class="value-width value-align"><?= $model['job_level'] ?></span>
        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">管理职：</label>
            <span class="value-width value-align"><?= $model['position'] ?></span>
            <label class="label-width label-align">入职时间：</label>
            <span class="value-width value-align"><?= $model['employment_date'] ?></span>
            <label class="label-width label-align">职务：</label>
            <span class="value-width value-align"><?= $model['job_task'] ?></span>

        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">职称：</label>
            <span class="value-width value-align"><?= $model['job_title'] ?></span>
            <label class="label-width label-align">职称级别：</label>
            <span class="value-width value-align"><?= $model['job_title_level'] ?></span>
            <label class="label-width label-align">行政级别：</label>
            <span class="value-width value-align"><?= $model['administrative_level'] ?></span>
        </div>
        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">员工类型：</label>
            <span class="value-width value-align"><?= $model['staff_type'] ?></span>
            <label class="label-width label-align">考勤类型：</label>
            <span class="value-width value-align"><?= $model['attendance_type'] ?></span>
            <label class="label-width label-align">起薪时间：</label>
            <span class="value-width value-align"><?= $model['salary_date'] ?></span>
        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">年休假：</label>
            <span class="value-width value-align"><?= $model['annual_leave'] ?></span>
            <label class="label-width label-align">员工状态：</label>
            <span class="value-width value-align"><?= $model['staff_status']==10 ?'在职':'离职' ?></span>
            <label class="label-width label-align">工龄：</label>
            <span class="value-width value-align"><?= $model['staff_seniority'] ?></span>
        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">参加工作时间：</label>
            <span class="value-width value-align"><?= $model['work_time']?></span>
            <label class="label-width label-align">直属上级：</label>
            <span class="value-width value-align"><?= $model['superior'] ?></span>
            <label class="label-width label-align">直属下级：</label>
            <span class="value-width value-align"><?= $model['subordinate']?></span>
        </div>

        <div class="space-10"></div>
        <div class="mb-10">
            <label class="label-width label-align">开户行：</label>
            <span class="value-width value-align"><?= $model['opening_bank']?></span>
            <label class="label-width label-align">银行账户：</label>
            <span class="value-width value-align"><?= $model['bank_account'] ?></span>
        </div>
    </div>
    <h2 class="head-second">
        教育信息
    </h2>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">学历：</label>
        <span class="value-width value-align"><?= $model['staff_diploma'] ?></span>
        <label class="label-width label-align">学位：</label>
        <span class="value-width value-align"><?= $model['staff_degree'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">毕业学校：</label>
        <span class="value-width value-align"><?= $model['staff_school']?></span>
        <label class="label-width label-align">毕业日期：</label>
        <span class="value-width value-align"><?= $model['staff_graduation_date'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">毕业专业：</label>
        <span class="value-width value-align"><?= $model['staff_major'] ?></span>
        <label class="label-width label-align">计算机水平：</label>
        <span class="value-width value-align"><?= $model['computer_level'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">外语语种1：</label>
        <span class="value-width value-align"><?= $model['languages_0']?></span>
        <label class="label-width label-align">外语水平1：</label>
        <span class="value-width value-align"><?= $model['language_level_0'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">外语语种2：</label>
        <span class="value-width value-align"><?= $model['languages_1'] ?></span>
        <label class="label-width label-align">外语水平2：</label>
        <span class="value-width value-align"><?= $model['language_level_1'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">外语语种3：</label>
        <span class="value-width value-align"><?= $model['languages_2']?></span>
        <label class="label-width label-align">外语水平3：</label>
        <span class="value-width value-align"><?= $model['language_level_2']?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width ">特长：</label>
        <span class="width-663 text-top"><?= $model['specialty'] ?></span>
    </div>
    <h2 class="head-second">
        其他
    </h2>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">职务情况：</label>
        <span class="width-663 text-top"><?= $model['job_situation'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">社保情况：</label>
        <span class="width-663 text-top"><?= $model['insurance_situation'] ?></span>
    </div>
    <div class="space-10"></div>
    <div class="mb-10">
        <label class="label-width label-align">备注：</label>
        <span class="width-663 text-top"><?= $model['remark']?></span>
    </div>
    <div class="space-40 "></div>
    <div class="text-center">
        <button type="submit" class="button_sub button-blue-big" id="update">修改</button>
        <button class="button-white-big ml-20" type="button" id="bake">返回</button>
    </div>
<script>
    $(function () {
        $("#update").click(function () {
            window.location.href="<?=Url::to(['update','id'=>$model['staff_id']])?>";
        });
        $("#bake").click(function () {
            window.location.href="<?=Url::to(['index'])?>";
        })
    })
</script>