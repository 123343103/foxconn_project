<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2018/1/5
 * Time: 下午 04:05
 */

use app\assets\JqueryUIAsset;
use yii\widgets\ActiveForm;

//ajax引用jQuery样
JqueryUIAsset::register($this);
\app\assets\JeDateAsset::register($this);
?>
<style>
    .red-border {
        border: 1px solid #ffa8a8;
    }
</style>
<?php $form = ActiveForm::begin(["action" => "create-notice"]); ?>
<h1 class="head-first" style="width: 100%">收货通知</h1>
<div class="content" style="margin-top: -20px;">
    <div class="mb-10">
        <div class="inline-block" style="font-size: 13px;">
            <input type="hidden" name="RcpNotice[prch_date]" value="<?= $data[0]['prch_date'] ?>">
            <label class="label-width label-align">采购部门：</label>
            <span><?= $data[0]['organization_name'] ?></span>
            <input type="hidden" name="RcpNotice[prch_depno]" value="<?= $data[0]['organization_code'] ?>">
        </div>
        <div class="inline-block" style="margin-left: 150px;font-size: 13px;">
            <label class="label-width label-align">采购人：</label>
            <span><?= $data[0]['staff_name'] ?></span>
        </div>
    </div>
    <div class="mb-10">
        <div id="data" style="overflow-x:auto;width: 100%;max-height: 200px;overflow-y: auto">
            <table class="table" id="tb_table" style="width: 2500px;">
                <thead>
                <tr>
                    <th width="50">序号</th>
                    <th width="50"><input type="checkbox" id="boxall"></th>
                    <th width="150">料号</th>
                    <th width="150">品名</th>
                    <th width="100">规格</th>
                    <th width="100">品牌</th>
                    <th width="80">单位</th>
                    <th width="150">供应商代码</th>
                    <th width="230">供应商名称</th>
                    <th width="100">采购数量</th>
                    <th width="100">已到货数量</th>
                    <th width="130"><span class="red">*</span>预到货数量</th>
                    <th width="100"><span class="red">*</span>预到货时间</th>
                    <th width="150">收货中心</th>
                    <th width="230">备注</th>
                </tr>
                </thead>
                <tbody id="noticeinfo">
                <?php if (!empty($data)) { ?>
                    <?php foreach ($data as $key => $val) { ?>
                        <tr class="notices-tr">
                            <td><?= $key + 1 ?></td>
                            <td class="ch"><input type="checkbox" class="check"></td>
                            <td><?= $val['part_no'] ?>
                                <input type="hidden" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][part_no]" value="<?= $val['part_no'] ?> ">
                                <input type="hidden" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][prch_no]" value="<?= $val['prch_no'] ?>">
                                <input type="hidden" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][prch_area]" value="<?= $val['area_id'] ?>">
                                <input type="hidden" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][leg_id]" value="<?= $val['leg_id'] ?>">
                                <input type="hidden" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][prch_dt_id]"
                                       value=" <?= $val['prch_dt_id'] ?>">
                            </td>
                            <td><?= $val['pdt_name'] ?></td>
                            <td><?= $val['tp_spec'] ?></td>
                            <td><?= $val['brand'] ?></td>
                            <td><?= $val['unit'] ?></td>
                            <td><?= $val['spp_code'] ?>
                                <input type="hidden" style="background-color: #FFFFFF;" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][spp_id]" value="<?= $val['spp_id'] ?>">
                                <input type="hidden" style="background-color: #FFFFFF;" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][spp_code]" value="<?= $val['spp_code'] ?>">
                            </td>
                            <td><?= $val['spp_fname'] ?></td>
                            <td><?= $val['prch_num'] ?>
                                <input type="hidden" style="background-color: #FFFFFF;" disabled="disabled"
                                       name="RcpNoticeDt[<?= $key ?>][ord_num]"
                                       value="<?= round($val['prch_num'], 2) ?>">
                            </td>
                            <td><?= $val['delivery_num'] ?></td>
                            <td class="nu">
                                <input disabled="disabled" type="text" class="easyui-validatebox number num<?=$key?>"
                                       data-options="required:'true'" name="RcpNoticeDt[<?= $key ?>][delivery_num]"
                                       style="background-color: #FFFFFF;text-align: center;width: 130px;"
                                       onkeyup="value=value.replace(/[^0-9^.]/g,'')"
                                       value="<?= round($val['prch_num'], 2) - round($val['delivery_num'], 2) ?>">
                                <input type="hidden" class="key" value="<?=$key?>">
                                <span id="numInfos" style="display: none;" class="numtInfo">预到货数量不能为0</span>
                                <span id="numlens" style="display: none;" class="numlen">预到货数量必须大于0</span>
                            </td>
                            <td class="da">
                                <input disabled="disabled" type="text" name="RcpNoticeDt[<?= $key ?>][plan_date]"
                                       class=" easyui-validatebox deldate<?=$key?> text-center Wdate"
                                       data-options="required:'true'"
                                       id="plan_date" style="width: 100px;z-index: 9999;"
                                       onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy-MM-dd',minDate: '%y-%M-#{%d}' })"
                                       onfocus="this.blur()"
                                       value="">
                            </td>
                            <td><?= $val['rcp_name'] ?>
                                <input type="hidden" disabled="disabled"
                                name="RcpNoticeDt[<?=$key?>][rcp_id]" value="<?=$val['rcp_id']?>">
                                <input type="hidden" disabled="disabled"
                                 name="RcpNoticeDt[<?=$key?>][rcp_no]" value="<?=$val['rcp_no']?>">
                            </td>
                            <td class="rem">
                                <textarea rows="2" disabled="disabled" name="RcpNoticeDt[<?= $key ?>][remark]" style="width:230px;background-color: #FFFFFF;"
                                          placeholder="最多输入100个字"
                                          maxlength="100"></textarea>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-10" style="margin-left: 200px;">
        <button id="btn-save" class="button-blue-big" type="button">确定</button>
        <button id="cancel" style="margin-left: 20px" class="button-white-big" type="button"
                onclick="parent.$.fancybox.close()">取消
        </button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
//        $("#data").datagrid({
//            url: "<?//=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>//",
//            rownumbers: true,
//            method: "get",
//            idField: "prch_dt_id",
//            loadMsg: "加载数据请稍候。。。",
//            pagination: true,
//            // singleSelect: false,
//            columns: [[
//                {field: 'ck', checkbox: true},
//                {
//                    field: "part_no", title: "料号", width: 181,
//                    formatter: function (value, rowData, rowIndex) {
//                        return '<span>' + rowData.part_no + '</span>' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][part_no]" value="' + rowData.part_no + '">' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][prch_no]" value="' + rowData.prch_no + '">' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][prch_area]" value="' + rowData.area_id + '">' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][leg_id]" value="' + rowData.leg_id + '">' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][prch_dt_id]" value="' + rowData.prch_dt_id + '">';
//                    }
//                },
//                {field: "pdt_name", title: "品名", width: 181},
//                {field: "tp_spec", title: "规格", width: 181},
//                {field: "brand", title: "品牌", width: 181},
//                {field: "unit", title: "单位", width: 80},
//                {
//                    field: "spp_code", title: "供应商代码", width: 180,
//                    formatter: function (value, rowData, rowIndex) {
//                        console.log(rowData);
//                        return '<span>' + rowData.spp_code + '</span>' +
//                            '<input type="hidden" style="background-color: #FFFFFF;" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][spp_id]" value="' + rowData.spp_id + '">';
//                    }
//                },
//                {field: "spp_fname", title: "供应商名称", width: 230},
//                {
//                    field: "prch_num", title: "采购数量", width: 130,
//                    formatter: function (value, rowData, rowIndex) {
//                        return '<span>' + parseFloat(rowData.prch_num).toFixed(2) + '</span>' +
//                            '<input type="hidden" style="background-color: #FFFFFF;" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][ord_num]" value="' + parseFloat(rowData.prch_num).toFixed(2) + '">';
//                    }
//                },
//                {
//                    field: "delivery_num", title: "已到货数量", width: 130,
//                    formatter: function (value, rowData, rowIndex) {
//                        return '<span>' + parseFloat(rowData.delivery_num).toFixed(2) + '</span>';
//                    }
//                },
//                {
//                    field: "num", title: "预到货数量", width: 130, formatter: function (value, rowData, rowIndex) {
//                    var num = parseFloat(rowData.prch_num - rowData.delivery_num).toFixed(2);//采购数量-到货数量=剩余的采购数量
//                    return '<input disabled="disabled" type="text" class="easyui-validatebox num" ' +
//                        'data-options="required:\'true\'" name="RcpNoticeDt[' + rowIndex + '][delivery_num]" ' +
//                        'style="background-color: #FFFFFF;text-align: center;width: 130px;" ' +
//                        'value="' + num + '" disabled="disabled">' +
//                        '<span id="numInfo" style="display: none;" class="numtInfo">采购数量不能为0</span>';
//                }
//                },
//                {
//                    field: "plan_date", title: "预到货时间", width: 181, formatter: function (value, rowData, rowIndex) {
//                    return '<input disabled="disabled" type="text" name="RcpNoticeDt[' + rowIndex + '][plan_date]" ' +
//                        'class=" easyui-validatebox deldate text-center Wdate"' +
//                        ' data-options="required:\'true\'"' +
//                        ' id="plan_date" style="width: 150px;z-index: 9999;" ' +
//                        'onclick="WdatePicker({skin: \'whyGreen\', dateFmt: \'yyyy-MM-dd\',minDate: \'%y-%M-#{%d}\' })" ' +
//                        'onfocus="this.blur()"' +
//                        ' value="">';
//                }
//                },
//                {
//                    field: "rcp_name", title: "收货中心", width: 100,
//                    formatter: function (value, rowData, rowIndex) {
//                        return '<span>' + rowData.rcp_name + '</span>' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][rcp_id]" value="' + rowData.rcp_id + '">' +
//                            '<input type="hidden" disabled="disabled" ' +
//                            'name="RcpNoticeDt[' + rowIndex + '][rcp_no]" value="' + rowData.rcp_no + '">';
//                    }
//                },
//                {
//                    field: "remark", title: "备注", width: 181, formatter: function (value, rowData, rowIndex) {
//                    return '<input disabled="disabled" type="text" style="background-color: #FFFFFF;"' +
//                        ' name="RcpNoticeDt[' + rowIndex + '][remark]" value="" maxlength="100">';
//                }
//                }
//            ]],
//            onLoadSuccess: function (messages) {
//                showEmpty($(this), data.total, 1);
//                $("input[type='text']").click(function () {
//                    return false;
//                });
//                // setMenuHeight();
//            },
//            onCheck: function (index, row) {
//                $("#datagrid-row-r1-2-" + index).find("input").removeAttr("disabled");
//                // $("#data").datagrid("clearSelections");
//            },
//            onUncheck: function (index, row) {
//                $("#datagrid-row-r1-2-" + index).find("input:not(:first)").attr("disabled", "disabled");
//            },
//            onSelect: function (index, row) {
//                $("#datagrid-row-r1-2-" + index).find("input").removeAttr("disabled");
//            }
//        });
//        $("#data").delegate('.num', 'mouseover', function () {
//            alert("a");
        //});
//       $("#btn-save").click(function () {
//           var rows=$("#data").datagrid("getChecked");
//          // addNotice(rows);
//           $.fancybox.close();
//            //var id=rows.part_no;
//            //$("#add-form").attr('action','<?//=Url::to(['rate-select'])?>//?tax_no='+tax_no);
//       });
    });
    //    function addNotice(row) {
    //        console.log(row);
    //    }
    $(function () {
        //$(".table").find("td").removeAttr("title");
        var flag = true;
        $(".check").click(function () {
            $(this).checked = flag;
            // console.log($(this).attr('checked',flag));
            flag = !flag;
            if ($(this)[0].checked == true) {
                $(this).parent().parent('.notices-tr').find("input[type='hidden']").removeAttr("disabled");
                $(this).parent().parent('.notices-tr').find("input[type='text']").removeAttr("disabled");
                $(this).parent().parent('.notices-tr').find("textarea").removeAttr("disabled");
            }
            else {
                $(this).parent().parent('.notices-tr').find("input:not(:first)").attr("disabled", "disabled");
                $(this).parent().parent('.notices-tr').find("input:not(:first)").attr("disabled", "disabled");
                $(this).parent().parent('.notices-tr').find("textarea").attr("disabled", "disabled");

            }
        });
        //全选
        $("#boxall").click(function () {
            $(this).checked=flag;
            flag=!flag;
            var _check=$(".check");
           //当全选复选框被选中
            if($(this)[0].checked==true)
            {
                //循环选中所有的复选框
                _check.each(function () {
                    $(this)[0].checked=true;
                });
                $(".notices-tr").find("input[type='hidden']").removeAttr("disabled");
                $('.notices-tr').find("input[type='text']").removeAttr("disabled");
                $('.notices-tr').find("textarea").removeAttr("disabled");
            }
            //当全选复选框被取消
            else {
                //循环取消所有的复选框
                _check.each(function () {
                    $(this)[0].checked=false;
                });
                $('.notices-tr').find("input:not(:first)").attr("disabled", "disabled");
                $('.notices-tr').find("input:not(:first)").attr("disabled", "disabled");
                $('.notices-tr').find("textarea:not(:first)").attr("disabled", "disabled");
            }
        });
        //当文本框失去焦点判断
        $(".number").blur(function () {
            var _nums = $(this).val();//当前输入的预到货数量
            if (_nums == 0 || _nums == "" || _nums == 0.00 ||_nums<0) {
                $(".number").removeClass('red-border');
                $(this).addClass('red-border');//数量等于空或0时添加红色错误边框提醒
            }
            else {
                $(this).removeClass('red-border');
            }
        });
        //保存
        $("#btn-save").click(function () {
            var _check = $(".check");
            var n = 0;//用于判断是否选择数据
            var flag=0;
            var flag1=0;
            _check.each(function () {
                if ($(this)[0].checked == true) {
                   var key= $(this).parent().siblings('.nu').find('.key').val();
                    n++;
                    flag += setstyle("num"+key+"", "");//采购数量为空验证
                    flag1 += setstyle("deldate"+key+"", "");//交货时间为空验证
                }
            });
            if (n > 0) {
                if(flag>0||flag1>0)
                {
                    return false;
                }
                else {
                    $("form").submit();
                }
            }
            else {
                layer.alert("请至少选择一条数据！", {icon: 2});
                return false;
            }
        });
        //未选中时填写收货数量，预到货时间，备注
        $(".nu,.da,.rem").click(function () {
           var _ch= $(this).siblings('.ch').find('.check');
            if(_ch[0].checked == false)
            {
                layer.alert("请先选中此行再填写！", {icon: 2});
            }
        });
        //敲击按键时触发
        $(".number").bind("keypress", function (event) {
            event = event || window.event;
            var getValue = $(this).val();
            //控制第一个不能输入小数点"."
            if (getValue.length == 0 && event.which == 46) {
                //alert(1)
                event.preventDefault();
                return;
            }
            //控制只能输入一个小数点"."
            if (getValue.indexOf('.') != -1 && event.which == 46) {
                event.preventDefault();
                //alert(1)
                return;
            }
//            var reg = /^(\-)*(\d+)\.(\d\d\d).*$/;
//            if (reg.test($(this).val())) {
//                event.preventDefault();
//                return;
//            }
        });
        //文本框验证
        $.fn.myHoverTips1 = function (divId) {
            var div = $("#" + divId); //要浮动在这个元素旁边的层
            div.css("position", "absolute");//让这个层可以绝对定位
            var self = $(this); //当前对象
            self.hover(function () {
                    div.css("display", "block");
                    var p = self.position(); //获取这个元素的left和top
                    var x = p.left + self.width();//获取这个浮动层的left
                    var docWidth = $(document).width();//获取网页的宽
                    if (x > docWidth - div.width() - 20) {
                        x = p.left - div.width();
                    }
                    div.css("left", x + 10);
                    div.css("top", p.top);
                    div.css("z-index", '99');
                    if (divId == "numlens") {
                        div.css("width", '200px');
                    }
                    else {
                        div.css("width", '130px');
                    }
                    div.css("height", '25px');
                    div.css("border", '1px solid #d2abab');
                    div.css("border-radius", "5px");
                    div.css("background-color", '#FFFFCC ');
                    div.css("line-height", '25px ');
                    div.show();
                },
                function () {
                    div.css("display", "none");
                }
            );
            return this;
        };
        ajaxSubmitForm("form");
    });
    //对文本框进行验证
    function setstyle(classname, inputclass) {
        flag = 0;
        var name=classname.substr(0,7);
        var _classname = $("." + classname + "");
        _classname.each(function () {
            if ($(this).val() == null || $(this).val() == "" || $(this).val() == 0) {
                //交货时间
                if (name == 'deldate') {
                    $(this).attr('class', "easyui-validatebox " + classname + " " + inputclass + " text-center Wdate");
                    $(this).focus();
                    flag += 1;
                }
                else {
                    $(this).attr('class', "red-border " + classname + " " + inputclass + " easyui-validatebox");
                    $(this).focus();
                    flag += 1;
                }
            }
        });
        return flag;
    }
</script>
