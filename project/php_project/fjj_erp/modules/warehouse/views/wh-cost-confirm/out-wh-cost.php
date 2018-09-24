<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/20
 * Time: 上午 08:49
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '仓库物理管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库标准价格查询', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '新增出仓费用'];
$this->title = "新增出仓费用";
?>
<style>
    .width-100 {
        width: 100px;
    }

    .width-150 {
        width: 150px;
    }

    .width-200 {
        width: 200px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .mt--10 {
        margin-top: -10px;
    }

    .btn-min {
        width: 20px;
        margin-right: -5px;
    }

    .btn-add {
        width: 20px;
        margin-left: -5px;
    }
</style>
<?php $from = ActiveForm::begin(['id' => 'from']) ?>
<div class="content">
    <h1 class="head-first"> <?= $this->title ?></h1>
    <h2 class="head-second">出仓信息</h2>
    <div class="ml-10">
        <label class="width-150">出库单号:</label><?= $model['OWhpdt']['o_whcode'] ?>
    </div>
    <div class="ml-10">
        <label class="width-150">仓库名称:</label><?= $model['OWhpdt']['wh_name'] ?>
        <label class="width-200">仓库代码:</label><?= $model['OWhpdt']['wh_code'] ?>
    </div>
    <div class="ml-10">
        <label class="width-150">法人简称:</label><?= $model['OWhpdt']['company_name']?>
        <label class="width-200">出库时间:</label><?= $model['OWhpdt']['o_date'] ?>
    </div>
    <div class="mb-20"></div>
    <h2 class="head-second">费用信息</h2>
    <table class="table mt--10">
        <thead>
        <tr>
            <th width="5%">序号</th>
            <th width="15%">费用名称</th>
            <th width="10%">费用标准</th>
            <th width="15%">作业量</th>
            <th width="10%">单项费用总计</th>
            <th width="10%">币别</th>
            <th width="30%">备注</th>
            <th width="5%">操作</th>
        </tr>
        </thead>
        <tbody id="contacts_table">
        <?php $a = 1;
        foreach ($model['WhPrice'] as $val1) { ?>
            <tr>
                <td>
                    <?= $a ?>
                    <input type="text" value="<?= $val1['whp_id'] ?>" name='IcInvCostlist[<?= $a - 1 ?>][whp_id]'
                           style="display: none">
                </td>
                <td>
                    <select style="width: 95%" name='IcInvCostlist[<?= $a - 1 ?>][whpl_id]'>
                        <?php foreach ($model['WhPrice'] as $val) { ?>
                            <option
                                value="<?= $val['whpl_id'] ?>" <?= $val1['whpb_sname'] == $val['whpb_sname'] ? 'selected="selected"' : '' ?>><?= $val['whpb_sname'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <?= $val1['whpb_num'] ?>
                </td>
                <td>
                    <input class="btn-min" name="" type="button" value="-" onclick="minus(this)"/>
                    <input type="text" class="form-control" onblur="rcount(this)"
                           style="width: 100px; text-align: center"
                           value="0">
                    <input class="btn-add" name="" type="button" value="+" onclick="add(this)"/>
                </td>
                <td>
                    <label class="rcount" style="text-align: center">0</label>
                    <input name='IcInvCostlist[<?= $a - 1 ?>][invcl_nprice]' value="0"
                           style="display: none"/>
                </td>
                <?php if ($a == 1) { ?>
                    <td rowspan="<?= count($model['WhPrice']) ?>" id="rowspan">
                        <select style="width: 95%;text-align: center">
                            <?php foreach ($model['BsCurrency'] as $key => $val) { ?>
                                <option
                                    value="<?= $val['cur_id'] ?>" <?= 'RMB' == $val['cur_code'] ? 'selected="selected"' : '' ?>><?= $val['cur_code'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                <?php } ?>
                <td>
                    <input type="text" style="width: 95%" name='IcInvCostlist[<?= $a - 1 ?>][subitem_remark]'>
                </td>
                <td>
                    <a onclick="vacc_del(this)" class="icon-minus-sign fs-18 ml-15" title="删除"></a>
                </td>
            </tr>
            <?php $a++;
        } ?>
        <tr>
            <td>总计</td>
            <td colspan="8" style="text-align: center">
                <label id="count">0</label>
                <label id="curr">RMB</label>
            </td>
        </tr>
        </tbody>

    </table>
    <p class="text-left mt-10">
        <a class="icon-plus" onclick="add_contacts()">添加费用</a>
    </p>

</div>
<div class="mt-30 text-center" id="buttons">
    <button class="button-blue-big" type="submit" id="save">保存</button>
    <button class="button-white-big ml-20" type="submit" id="submit">提交</button>
    <button class="button-white-big ml-20" type="button" id="back">取消</button>
</div>
<?php ActiveForm::end() ?>
<script>
    var count =<?= count($model['WhPrice']) ?>;
    $(function () {
        //ajax提交表单
        var btnFlag = '';
        $("button[type='submit']").click(function () {
            btnFlag = $(this).text();
        });
        ajaxSubmitForm("form", "", function (data) {
            console.log(data);
            if (data.flag == 1) {
                if (btnFlag == '提交') {
                    var id = data.billId;
                    var url = "<?=Url::to(['index'], true)?>";
                    var type = data.billTypeId;
                    $.fancybox({
                        href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                        type: "iframe",
                        padding: 0,
                        autoSize: false,
                        width: 750,
                        height: 480,
                        afterClose: function () {
                            location.href = "<?=Url::to(['index'])?>";
                        }
                    });
                } else {
                    layer.alert(data.msg, {
                        icon: 1,
                        end: function () {
                            if (data.url != undefined) {
                                parent.location.href = data.url;
                            }
                        }
                    });
                }
            }
            if (data.flag == 0) {
                if ((typeof data.msg) == 'object') {
                    layer.alert(JSON.stringify(data.msg), {icon: 2});
                } else {
                    layer.alert(data.msg, {icon: 2});
                }
                $("button[type='submit']").prop("disabled", false);
            }
        });
    })
    //减
    function minus(obj) {
        var td = $(obj).parents("tr").find('td');
        var num = td.eq(3).find('input:eq(1)').val();
        if (num == 0) {
            alert("最小作业量为零!");
        } else {
            num--;
            td.eq(3).find('input:eq(1)').val(num);
            var price = td.eq(2).html();
            var rcount = price * num * 100000 / 100000;
            td.eq(4).find('label').html(rcount);
            td.eq(4).find('input').val(rcount);
            var s = 0;
            $(".rcount").each(function () {
                var price = $(this).html();
                s = (parseFloat(s) * 100000 + parseFloat(price) * 100000) / 100000;
            })
            $("#count").html(s);
        }

    }
    //加
    function add(obj) {
        var td = $(obj).parents("tr").find('td');
        var num = td.eq(3).find('input:eq(1)').val();
        num++;
        td.eq(3).find('input:eq(1)').val(num);

        var price = td.eq(2).html();
        var rcount = price * num * 100000 / 100000;
        td.eq(4).find('label').html(rcount);
        td.eq(4).find('input').val(rcount);
        var s = 0;
        $(".rcount").each(function () {
            var price = $(this).html();
            s = (parseFloat(s) * 100000 + parseFloat(price) * 100000) / 100000;
        })
        $("#count").html(s);
    }
    //输入框失焦方法
    function rcount(obj) {
        var td = $(obj).parents("tr").find('td');
        var num = td.eq(3).find('input:eq(1)').val();
        var price = td.eq(2).html();
        var rcount = price * num * 100000 / 100000;
        td.eq(4).find('label').html(rcount);
        td.eq(4).find('input').val(rcount);
        var s = 0;
        $(".rcount").each(function () {
            var price = $(this).html();
            s = (parseFloat(s) * 100000 + parseFloat(price) * 100000) / 100000;
        })
        $("#count").html(s);
    }

    function vacc_del(obj) {
        var td = $(obj).parents("tr").find('td');
//        }else {???
//            alert(1);
//        }
        if ($(obj).parents("tr").index() == 0) {
            //删除第一行
            $('#rowspan').attr('rowspan', --count);
            $(obj).parents("tr").remove();
            var i = 1;
            var length = $('#contacts_table').find('tr').length;
            var counts = 0;
            $('#contacts_table').find('tr').each(function () {
                //第一次进入循环添加一个td
                if (i == 1) {
                    var str = '<select style="width: 95%;text-align: center">'
                    <?php foreach ($model['BsCurrency'] as $key => $val) { ?>
                    str += '<option value="<?= $val['cur_id'] ?>" <?= 'RMB' == $val['cur_code'] ? 'selected="selected"' : '' ?>><?= $val['cur_code'] ?></option>'
                    <?php } ?>
                    str += ' </select>'

                    $(this).find('td:eq(4)').after('<td  id="rowspan" rowspan="' + (--length) + '">' + str + '</td>');
                }
                //最后一行不改
                if (i != (length + 1)) {
                    $(this).find('td:eq(0)').html(i);
                    var rPrice = $(this).find('td:eq(4)').find('label').html();
                    counts = (parseFloat(counts) * 100000 + parseFloat(rPrice) * 100000) / 100000;
                }
                i++;
            });
            $("#count").html(counts);

        } else {
            //删除的不是第一行
            $('#rowspan').attr('rowspan', --count);
            $(obj).parents("tr").remove();
            var i = 1;
            var length = $('#contacts_table').find('tr').length;
            var counts = 0;
            $('#contacts_table').find('tr').each(function () {
                //最后一行不改
                if (i != length) {
                    $(this).find('td:eq(0)').html(i);
                    var rPrice = $(this).find('td:eq(4)').find('label').html();
                    counts = (parseFloat(counts) * 100000 + parseFloat(rPrice) * 100000) / 100000;
                }
                i++;
            });
            $("#count").html(counts);
        }
    }

    //添加一行
    function add_contacts() {
        $('#rowspan').attr('rowspan', ++count);
        var a = $("#contacts_table tr").length;
        var b = a;
        b += 1;
        var sname = "";
        var cur = ""
        var num;
        <?php foreach ($model['WhPrice'] as $val) { ?>
        sname += "<option value=\"<?= $val['whpl_id'] ?>\" <?= $val1['whpb_sname'] == $val['whpb_sname'] ? 'selected=\"selected\"' : '' ?>><?= $val['whpb_sname'] ?></option>";
        num = parseFloat(<?= $val['whpb_num'] ?>).toFixed(6);
        <?php }?>
        <?php foreach ($model['BsCurrency'] as $key => $val) { ?>
        cur += "<option value=\"<?= $val['cur_id'] ?>\" <?= 'RMB' == $val['cur_code'] ? 'selected=\"selected\"' : '' ?>><?= $val['cur_code'] ?></option>";
        <?php } ?>
        var td = "";
        if (a == 1) {
            td = '<td rowspan="1" id="rowspan"> <select style="width:95%;text - align:center">' + cur + '</select></td>';
        }
        var obj = $("#contacts_table").find('tr:eq(' + (a - 1) + ')').before(
                '<tr>' +
                '<td>' + a + '  <input type="text" value="<?= $val1['whp_id'] ?>" name="IcInvCostlist[' + (a - 1) + '][whp_id]"style = "display: none"></td>' +
                '<td><select style="width: 95%" name="IcInvCostlist[' + (a - 1) + '][whpl_id]">' + sname + '</select></td>' +
                '<td>' + num + '</td>' +
                '<td> <input class="btn-min" name="" type="button" value="-" onclick="minus(this)"/><input type="text"  class="form-control" onblur="rcount(this)" style="width: 100px; text-align: center" value="0"><input class="btn-add" name="" type="button" value="+" onclick="add(this)"/></td>' +
                '<td><label class="rcount" style="text-align: center">0</label> <input name="IcInvCostlist[' + (a - 1) + '][invcl_nprice]"  value="0" style="display: none"/></td>' + td +
                '<td><input type="text" style="width: 95%" name="IcInvCostlist[' + a + '][subitem_remark]"></td>' +
                '<td><a onclick="vacc_del(this)" class="icon-minus-sign fs-18 ml-15" title="删除"></a></td>' +
                '</tr>'
            )
            ;
        $.parser.parse(obj);
    }
</script>
