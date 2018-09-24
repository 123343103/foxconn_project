<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/15
 * Time: 上午 11:50
 */
use yii\widgets\ActiveForm;

$this->params['homeLike'] = ['label' => '仓库物理管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库标准价格查询', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '新增仓库标准价格'];
$this->title = "新增仓库标准价格";
?>
<style>
    .width-100 {
        width: 100px;
    }

    .width-200 {
        width: 200px;
    }

    .width-400 {
        width: 400px;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mt-30 {
        margin-top: 30px;
    }

    .label-right {
        text-align: right;
    }

    .input-left {
        text-align: left;
    }

    .td-input {
        border: 0px;
        text-align: center;
        width: 100%;
    }

    .td-input-price {
        text-align: center;
        width: 49%;
    }

    .display {
        display: none;
    }
</style>
<?php $from = ActiveForm::begin(['id' => 'from']) ?>
<div class="content">

    <h1 class="head-first"> <?= $this->title ?></h1>

    <div class="mb-10"></div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="width-100 label-right"><span class="red">*</span>仓库名称:</label>
            <select name="WhPrice[wh_id]" class="width-200 input-left easyui-validatebox" data-options="required:true"
                    id="wh_name">
                <option>请选择</option>
                <?php foreach ($downList['BsWh'] as $key => $val) { ?>
                    <option value="<?= $val['wh_id'] ?>"><?= $val['wh_name'] ?></option>
                <?php } ?>
            </select>

        </div>
        <div class="inline-block display" id="wh_code">
            <label class="width-100 label-right">仓库代码:</label>
            <label id="wh_info_code"></label>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block display" id="wh_addr">
            <label class="width-100 label-right">仓库地址:</label>
            <label id="customerAddress"></label>
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="width-100 label-right"><span class="red">*</span>操作类型:</label>
            <select name="WhPrice[op_id]" class="width-200 input-left easyui-validatebox" data-options="required:true"
                    id="op_id">
                <option value>请选择</option>
                <?php foreach ($downList['type'] as $key => $val) { ?>
                    <option value="<?= $val['bsp_id'] ?>"><?= $val['bsp_svalue'] ?></option>
                <?php } ?>
            </select>
            <!--            <input type="text" class="width-150 input-left">-->
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="width-100 label-right">状态:</label>
            <select name="WhPrice[whp_status]" class="width-200 input-left">
                <option value="1">启用</option>
                <option value="0">禁用</option>

            </select>
            <!--            <input type="text" class="width-150 input-left">-->
        </div>
    </div>
    <div class="mb-10">
        <div class="inline-block">
            <label class="vertical-top width-100 label-right"">备注:</label>
            <textarea id="remarks" maxlength="100" class="width-400 value-left" name="WhPrice[whp_remark]"
                      placeholder="最多输入120字" rows="4"></textarea>
        </div>
    </div>
    <div class="mb-10" style=" overflow: auto;">
        <label>仓库费用组合:</label>
        <table class="table">
            <thead>
            <tr>
                <th width="10">序号</th>
                <th width="20">费用代码</th>
                <th width="20">费用名称</th>
                <th width="20">费用标准</th>
                <th width="20">描述</th>
                <th width="10">操作</th>
            </tr>
            </thead>
            <tbody id="contacts_table">
            <tr>
                <td>1</td>
                <td><input type="text" class="td-input" onblur="getBsWhPrice(this)"/></td>
                <td>
                    <select class="td-input price_name" onchange="getBsWhPrice1(this)" name="WhPricel[0][whpb_id]">
                        <option value="">请选择</option>
                        <?php foreach ($downList['BsWhPrice'] as $key => $val) { ?>
                            <option value="<?= $val['whpb_id'] ?>"><?= $val['whpb_sname'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><input type="text" class="td-input-price" name="WhPricel[0][whpb_num]"/><select
                        class="td-input-price" name="WhPricel[0][whpb_curr]">
                        <?php foreach ($downList['BsCurrency'] as $key => $val) { ?>
                            <option value="<?= $val['cur_id'] ?>"><?= $val['cur_code'] ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td><input type="text" class="td-input " disabled="disabled"/></td>
                <td><a onclick="reset(this)" class="icon-refresh  fs-18" title="重置"></a><a
                        onclick="vacc_del(this,'customer_table')" class="icon-minus-sign fs-18 ml-15" title="删除"></a>
                </td>
            </tr>
            </tbody>

        </table>
        <div class="mb-10"></div>
        <p class="text-left mt-10">
            <a class="icon-plus" onclick="add_contacts()"> 添加行</a>
        </p>
    </div>

</div>
<div class="mt-30 text-center" id="buttons">
    <button class="button-blue-big" id="submit">保存</button>
    <button class="button-white-big ml-20" type="button" id="back">取消</button>
</div>
<?php ActiveForm::end() ?>
<script>
    $(function () {
        $("#submit").click(function () {
            //检查选中的仓库和操作类型是否重复
            if (check() == 0) {
                ajaxSubmitForm($('form'));
            } else {
                alert("该仓库存在这种操作类型!");
                $("#submit").removeAttr("disabled");
            }
        })
        $('#wh_name').change(function () {
            var wh_id = $('#wh_name').val();
            if (wh_id != "") {
                $("#wh_code").removeClass("display");
                $("#wh_addr").removeClass("display");
                //通过仓库id查询仓库编码和仓库地址
                $.get({
                    url: "<?=\yii\helpers\Url::to(['get-wh'])?>",
                    data: {wh_id: wh_id},
                    dataType: "JSON",
                    success: function (data) {
                        var wh_info = eval(data);
                        var wh_code = wh_info[0].wh_code;
                        var customerAddress = wh_info[0].customerAddress;
                        $('#wh_info_code').html(wh_code);
                        $('#customerAddress').html(customerAddress);
                    }
                })

            } else {
                $("#wh_code").addClass("display");
                $("#wh_addr").addClass("display");

            }
        });
        $("#back").click(function () {
            location.href = "<?=\yii\helpers\Url::to(['index'])?>";
        });
    });
    //添加行
    function add_contacts() {
        var a = $("#contacts_table tr").length;
        var b = a;
        b += 1;
        var BsWhPrice = "";
        var BsCurrency = "";
        <?php foreach ($downList['BsWhPrice'] as $key => $val) { ?>
        BsWhPrice += " <option value=\"<?= $val['whpb_id'] ?>\"><?= $val['whpb_sname'] ?></option>";
        <?php }?>
        <?php foreach ($downList['BsCurrency'] as $key => $val) { ?>
        BsCurrency += "<option value = \"<?= $val['cur_id'] ?>\" ><?= $val['cur_code'] ?></option>";
        <?php } ?>
        var obj = $("#contacts_table").append(
                '<tr>' +
                '<td>' + b + '</td>' +
                '<td><input type="text" class="td-input"  onblur="getBsWhPrice(this)" /></td>' +
                '<td><select class="td-input price_name" onchange="getBsWhPrice1(this)" name="WhPricel[' + a + '][whpb_id]"> <option value="">请选择</option>' + BsWhPrice + '</select></td>' +
                '<td><input type="text" class="td-input-price"  name="WhPricel[' + a + '][whpb_num]"/><select class="td-input-price"  name="WhPricel[' + a + '][whpb_curr]">' + BsCurrency + '</select></td>' +
                '<td><input type="text" class="td-input" disabled="disabled"/></td>' +
                '<td><a onclick="reset(this)" class="icon-refresh  fs-18" title="重置"></a><a onclick="vacc_del(this,' + "'customer_table'" + ')" class="icon-minus-sign fs-18 ml-15" title="删除"></a></td>' +
                '</tr>'
            )
            ;
        $.parser.parse(obj);
    }

    /*重置表格信息*/
    function reset(obj) {
        var td = $(obj).parents("tr").find("td");
        $(obj).parents("tr").find("input").val("");
        $(obj).parents("tr").find("select").val("");
    }
    /*删除表格行*/
    function vacc_del(obj, id) {
        $(obj).parents("tr").remove();
        var a = $("#" + id + " tr").length;
        for (var i = 0; i < a; i++) {
            $('#' + id).find('tr').eq(i).find('td:first').text(i + 1);
        }
    }

    function getBsWhPrice(obj) {
        var whpb_code = obj.value;
        var td = $(obj).parents("tr").find("td");
        $.get({
            url: "<?=\yii\helpers\Url::to(['get-bs-wh-price'])?>",
            data: {value: whpb_code, column: 'whpb_code'},
            dataType: "JSON",
            success: function (data) {
                var bswhprice_info = eval(data);
                if (bswhprice_info.length > 0) {
                    var stcl_description = bswhprice_info[0].stcl_description//描述
                    var whpb_id = bswhprice_info[0].whpb_id//费用id
                    td.eq(2).find('select').val(whpb_id);
                    td.eq(4).find('input').val(stcl_description);
                } else {
                    td.eq(2).find('select').val("");
                    td.eq(4).find('input').val("");
                    alert("未找到该仓库代码,请确认是否输入有误!");

                }
            }
        });
        //赋值
    }

    function getBsWhPrice1(obj) {
        var whpb_id = $(obj).val();
        var td = $(obj).parents("tr").find("td");
        if (whpb_id != "") {
            $.get({
                url: "<?=\yii\helpers\Url::to(['get-bs-wh-price'])?>",
                data: {value: whpb_id, column: 'whpb_id'},
                dataType: "JSON",
                success: function (data) {
                    var bswhprice_info = eval(data);
                    var stcl_description = bswhprice_info[0].stcl_description//描述
                    var whpb_code = bswhprice_info[0].whpb_code//费用代码
                    td.eq(1).find('input').val(whpb_code);
                    td.eq(4).find('input').val(stcl_description);
                }
            });
        }
    }

    function check() {
        var count = 0;
        var op_id = $("#op_id").val();
        var wh_id = $("#wh_name").val();
        $.ajax({
            url: "<?=\yii\helpers\Url::to(['check'])?>",
            data: {"op_id": op_id, "wh_id": wh_id},
            async: false,
            dataType: "json",
            success: function (data) {
                count = data;
            }
        });
        return count;
    }
</script>