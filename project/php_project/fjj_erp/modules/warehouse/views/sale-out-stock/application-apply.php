<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2018/1/3
 * Time: 下午 03:13
 */
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '出仓费用申请';
$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '销售出库列表', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => '出仓费用申请'];
?>
<style>
    .content {
        font-size: 14px;
    }

    .span-width {
        width: 300px;
    }

    .label-width {
        width: 110px;
    }

    .width-150 {
        width: 150px;
    }

    .div-margin {
        margin-left: 50px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }
</style>
<div class="content">
    <h2 class="head-first">
        新增出仓费用
    </h2>
    <h2 class="head-second color-1f7ed0">出仓信息</h2>
    <div class="mb-10">
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">出库单号：</label>
                <span class="span-width"><?= $model['owhpdt']['o_whcode'] ?></span>
            </div>
        </div>
    </div>
    <div class="mb-10">
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">仓库名称：</label>
                <span class="span-width"><?= $model['owhpdt']['wh_name'] ?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">仓库代码：</label>
                <span class="span-width"><?= $model['owhpdt']['wh_code'] ?></span>
            </div>
        </div>
    </div>
    <div class="mb-20">
        <div class="mb-10 div-margin">
            <div class="inline-block">
                <label class="label-width label-align">法人简称：</label>
                <span class="span-width"><?= $model['owhpdt']['company_name'] ?></span>
            </div>
            <div class="inline-block">
                <label class="label-width label-align">出库时间：</label>
                <span class="span-width"><?= date("Y-m-d", strtotime($model['owhpdt']['o_date'])) ?></span>
            </div>
        </div>
    </div>
    <h2 class="head-second color-1f7ed0">费用信息</h2>
    <div class="mb-10" style="width: 100%;">
        <table class="table">
            <thead>
            <tr>
                <th width="50">序号</th>
                <th width="100">费用名称</th>
                <th width="50">费用标准</th>
                <th width="100">作业量</th>
                <th width="50">费用总计</th>
                <th width="50">币别</th>
                <th width="200">备注</th>
                <th width="50">操作</th>
            </tr>
            </thead>
            <tbody id="cost-info">
            <?php foreach ($model['cost'] as $key => $val) { ?>
                <?php if ($key == 0) { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><select style="width: 100px;">
                                <?php foreach ($model['costs'] as $keyss => $value) { ?>
                                    <option value="<?=$value['whpb_id']?>" <?= $value['whpb_id']==$val['whpb_id']?"select":null ?>><?=$value['whpb_sname']?></option>
                                <?php } ?>
                            </select></td>
                        <td><?=$val['whpb_num']?></td>
                        <td><input type="text" style="width: 100px;"></td>
                        <td><?=$val['whpb_num']?></td>
                        <td rowspan="10">
                            <select style="width: 100px;">
                                <?php foreach ($cur as $keys => $vals) { ?>
                                    <option value="<?= $vals['cur_id'] ?>"><?= $vals['cur_code'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><input type="text"></td>
                        <td></td>
                    </tr>
                <?php } else { ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><select style="width: 100px;">
                                <?php foreach ($model['costs'] as $keyss => $value) { ?>
                                    <option value="<?=$value['whpb_id']?>" <?= $value['whpb_id']==$val['whpb_id']?"select":null ?>><?=$value['whpb_sname']?></option>
                                <?php } ?>
                            </select></td>
                        <td><?=$val['whpb_num']?></td>
                        <td><input type="text" style="width: 100px;"></td>
                        <td><?=$val['whpb_num']?></td>
                        <td><input type="text"></td>
                        <td><i><a class='icon-check-minus icon-large cost-delete' style='' title='删除'></a></i></td>
                    </tr>
                <?php }
            } ?>
            </tbody>
            <tbody id="total-cost">
            <tr>
                <td>总计</td>
                <td colspan="7">100.00</td>
            </tr>
            </tbody>
        </table>
        <div style="height: 10px;"></div>
        <a id="add-cost">添加费用</a>
    </div>
    <div class="mb-10">
        <div class="text-center" style="margin-top: 30px;">
            <button type="button" class="button-blue-big apply-form">提交
                <button>
                    <button class="button-white-big" onclick="window.history.go(-1)" type="button">返回</button>
        </div>
    </div>
</div>
<script>
    $(function () {
        var trs = $("#cost-info").find('tr');
        trs.eq(0).find("td").eq(5).attr("rowspan", trs.length);
    });
    //添加费用
    $("#add-cost").click(function () {
        var tr = $("#cost-info").find('tr');
        var str = "";
        str += "<tr>";
        str += "<td>" + (tr.length + 1) + "</td>";
        str += "<td><select style='width: 100px;'> <option>1</option> <option>2</option> </select></td></td>";
        str += "<td>200.00</td>";
        str += "<td><input type='text' style='width: 100px;'></td>";
        str += "<td>200.00</td>";
        str += "<td><input type='text'></td>";
        str += "<td><i><a class='icon-check-minus icon-large cost-delete' style='' title='删除' ></a></i></td>";
        str += "</tr>";
        $("#cost-info").append(str);
        tr.eq(0).find("td").eq(5).attr("rowspan", tr.length + 2);
    });

    //删除费用
    $("#cost-info").delegate(".cost-delete", "click", function () {
        var tr = $(this).parents("td").parents("tr").parents("#cost-info").find("tr");
        var td = tr.eq(0).find("td").eq(5);
        var sum = tr.length - 1;
        $(this).parents("td").parents("tr").remove();
        td.attr("rowspan", sum);
        $("#cost-info").find("tr").each(function (index) {
            $(this).find("td:first").text(index + 1);
        });
    })
</script>
