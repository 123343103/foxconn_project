<?php
/**
 * User: F1677929
 * Date: 2016/11/22
 */
?>
<h2 style="text-align: center; margin-bottom: 10px;">厂商评鉴申请表</h2>
<table class="table-small" style="width: 95%; margin: 0 auto; font-size: 12px;">
    <tbody>
        <tr>
            <td>采购部门</td>
            <td><?= $evaluateApplyData['applicantInfo']['organization'] ?></td>
            <td>课别</td>
            <td><?= $evaluateApplyData['applicant_class'] ?></td>
            <td>申请人</td>
            <td><?= $evaluateApplyData['applicantInfo']['staff_name'] ?></td>
        </tr>
        <tr>
            <td>分机</td>
            <td><?= $evaluateApplyData['applicantInfo']['staff_tel'] ?></td>
            <td>E-mail</td>
            <td><?= $evaluateApplyData['applicantInfo']['staff_email'] ?></td>
            <td>申请日期</td>
            <td><?= $evaluateApplyData['apply_date'] ?></td>
        </tr>
        <tr>
            <td>厂商名称</td>
            <td colspan="5"><?= $evaluateApplyData['firmInfo']['firm_sname'] ?></td>
        </tr>
        <tr>
            <td>厂商地址</td>
            <td colspan="5"><?= $evaluateApplyData['firmInfo']['firmAddress']['fullAddress'] ?></td>
        </tr>
        <tr>
            <td>厂商负责人</td>
            <td><?= $evaluateApplyData['firmInfo']['firm_compprincipal'] ?></td>
            <td>手机</td>
            <td><?= $evaluateApplyData['firmInfo']['firm_comptel'] ?></td>
            <td>E-mail</td>
            <td><?= $evaluateApplyData['firmInfo']['firm_compmail'] ?></td>
        </tr>
        <tr>
            <td>厂商联络人</td>
            <td><?= $evaluateApplyData['firmInfo']['firm_contaperson'] ?></td>
            <td>手机</td>
            <td><?= $evaluateApplyData['firmInfo']['firm_contaperson_mobile'] ?></td>
            <td>E-mail</td>
            <td><?= $evaluateApplyData['firmInfo']['firm_contaperson_mail'] ?></td>
        </tr>
        <tr>
            <td>预评鉴日期</td>
            <td colspan="5"><?= $evaluateApplyData['predict_evaluate_date'] ?></td>
        </tr>
        <tr>
            <td colspan="6" style="word-break: break-all; text-align: left;">理由/原因：<?= $evaluateApplyData['apply_reason'] ?></td>
        </tr>
    </tbody>
</table>