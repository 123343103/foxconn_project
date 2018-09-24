<?php
$this->params['homeLike'] = ['label' => '人事管理'];
$this->params['breadcrumbs'][] = ['label' => '人事信息列表'];
$this->params['breadcrumbs'][] = ['label' => '人事资料详情'];
$this->title = '人事资料详情：' . $model['staff_name'];
?>
<div class="content">
    <h1 class="head-first">
        个人基本资料&nbsp;&nbsp;&nbsp;&nbsp;工号：<?= $model['staff_code'] ?>
    </h1>
    <div class="space-30"></div>
    <div class="mb-30">
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">姓名:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_name'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">英文名:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_name'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">曾用名:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['former_name'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">工号:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_code'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">部门:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['organization_name'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">资位:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['job_level'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">性别:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_sex'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">民族:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_nation'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">婚否:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_married'] == 1 ? '已婚' : "未婚" ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">身份证号码:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['card_id'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">出生年月日:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['birth_date'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">年龄:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_age'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">户口类型:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['residence_type'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">籍贯:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['native_place'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">血型:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['blood_type'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">健康状况:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['health_condition'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">政治面貌:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['political_status'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">入党时间:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['party_time'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">联系电话:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_tel'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">手机号码:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_mobile'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">电子邮箱:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_email'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">QQ:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['staff_qq'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%">其他联系方式:</td>
                <td class="no-border vertical-center" width="18%"><?= $model['other_contacts'] ?></td>
                <td width="4%" class="no-border vertical-center"></td>
                <td class="no-border vertical-center" width="13%"></td>
                <td class="no-border vertical-center" width="18%"></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">家庭住址:</td>
                <td class="no-border vertical-center" width="87%"><?= $model['card_address'] ?></td>
            </tr>
        </table>
        <div class="space-10"></div>
        <table width="90%" class="no-border vertical-center ml-25 mb-20">
            <tr class="no-border">
                <td class="no-border vertical-center" width="13%">户口所在地:</td>
                <td class="no-border vertical-center" width="87%"><?= $model['residence_address'] ?></td>
            </tr>
        </table>
    </div>
    <div>
        <div class="space-20"></div>
        <div class="text-center">
            <button onclick="history.go(-1)" class="button-white-big ml-20" type="button">返回</button>
        </div>
