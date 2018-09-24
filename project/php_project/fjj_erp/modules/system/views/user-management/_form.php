<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\hr\models\HrStaff;
use app\assets\MultiSelectAsset;


MultiSelectAsset::register($this);


/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<? //= dumpE($model) ?>
<? //= dumpE($staff) ?>
<style>
    .width-120 {
        width: 120px;
    }

    .width-110 {
        width: 117px;
    }

    .width-130 {
        width: 130px;
    }

    .width-150 {
        width: 150px;
    }

    .width-200 {
        width: 200px;
    }

    .width-230 {
        width: 230px;
    }

    .width-523 {
        width: 523px;
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mb-20 {
        margin-bottom: 20px;
    }

    .ml-15 {
        margin-left: 15px;
    }

    .ml-25 {
        margin-left: 25px;
    }

    .ml-60 {
        margin-left: 60px;
    }

    .ml-70 {
        margin-left: 70px;
    }

    .ml-80 {
        margin-left: 80px;
    }

    .ml-90 {
        margin-left: 85px;
    }

    .ml-320 {
        margin-left: 320px;
    }

    label:after {
        content: "：";
    }

    .validatebox-invalid {
        border-color: #ffa8a8;
    }
</style>
<h2 class="head-second">
    用户基本信息
</h2>
<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'add-form',
//        'validateOnBlur'=>false,//关闭失去焦点验证
        'enableAjaxValidation' => $model->isNewRecord ? true : false, //开启Ajax验证
        'validationUrl' => Url::to(['/system/user-management/ajax-validation']),
//        'enableClientValidation'=>false, //关闭客户端验证
        'fieldConfig' => [
            'template' => "<div class='width-120 display-style'>{label}</div><div class='display-style'>\n{input}\n{error}\n</div>",
            'options' => ['class' => 'div-row', false],
            'labelOptions' => ['class' => 'width-120'],
            'inputOptions' => ['class' => 'width-250'],
            'errorOptions' => ['class' => 'error-notice'],
        ],
    ]); ?>

    <div class="mb-20">
        <?= $form->field($model, 'user_account', ['inputOptions' => ['class' => 'width-200', 'readonly' => $model->isNewRecord ? false : 'readonly']])->textInput(['maxlength' => true]) ?>
        <label class="label-width qlabel-align  width-230"><span class="red">*</span>用户类型</label>
        <select name="User[user_type]" class="width-200 easyui-validatebox"
                data-options="required:'true',delay:10000,validateOnBlur:true"
                style="margin-left: -3px" id="user_type">
            <?php if (empty($model->user_status)) { ?>
                <option value="">---请选择---</option>
            <?php } ?>
            <?php foreach ($bspnamelist as $key => $val) { ?>
                <option
                    value="<?= $val['bsp_id'] ?>" <?= $model->user_type == $val['bsp_id'] ? "selected" : null ?>><?= $val['bsp_svalue'] ?></option>
            <?php } ?>
        </select>
        <span class='TypeErr'></span>
    </div>
    <div class="mb-20">
        <label class="label-width qlabel-align  width-120"><span class="red">*</span>用户工号</label>
        <input type="text" id="user_code" value="<?= $staff['staff_code'] != null ? $staff['staff_code'] : null ?>"
               style="margin-left: -3px" <?= $staff['staff_code'] != null ? "readonly" : null ?>
               class="width-200 easyui-validatebox"
               data-options="required:'true',delay:10000,validateOnBlur:true"/>
        <input type="hidden" name="User[staff_id]" id="staff_id"
               value="<?= $model->staff_id ? $model->staff_id : 0 ?>" class="width-130 staff_id"/>
        <span id="staff_name" style="width: 75px;"><?= $staff['staff_name'] ? $staff['staff_name'] : null ?></span>
        <!--                    <input type="text" class="width-200 staff_name" style="margin-left: -3px"-->
        <!--                           value="-->
        <? //= $staff['staff_name'] ? $staff['staff_name'] : null ?><!--" disabled/>-->
        <label class="label-width qlabel-align width-150">所在部门</label>
        <input type="text" value="<?= $staff['organization'] ? $staff['organization'] : null ?>"
               class="width-200 staff_organization" disabled/>
    </div>
    <div class="mb-20">
        <label class="label-width qlabel-align width-110"><span class="red">*</span>手机</label>
        <input type="text" name="User[user_mobile]" id="mobile"
               value="<?= $model->user_mobile ? $model->user_mobile : null ?>"
               class="width-200 staff_mobile easyui-validatebox add-require validatebox-text"
               placeholder="请输入 136xxxxxxxx"
               data-options="required:true,validType:['unique'],delay:1000000,validateOnBlur:true,validType:'mobile'"/>
        <label class="label-width qlabel-align width-230">其他联系方式</label>
        <input type="text" id="other_tel" value="<?= $model->other_tel ? $model->other_tel : null ?>"
               name="User[other_tel]"
               class="width-200 other_contacts"/>
        <span class="TelErrorPrompt"></span>
    </div>
    <div class="mb-20">
        <label class="label-width qlabel-align width-110"><span class="red">*</span>邮箱</label>
        <input type="text" value="<?= $model->user_email ? $model->user_email : null ?>" name="User[user_email]"
               class="width-200 staff_email easyui-validatebox add-require validatebox-text"
               placeholder="请输入 xxx@xxx.com" maxlength="50" id="email"
               data-options="required:'true',validType:['unique'],delay:1000000,validateOnBlur:true,validType:'email'"/>
        <label class="label-width qlabel-align width-230">是否有效</label>
        <select name="User[user_status]" class="width-200 easyui-validatebox" data-options="required:'true'"
                style="margin-left: -3px">
            <option value="10" <?= $model->user_status == 10 ? "selected" : null ?>>是</option>
            <option value="20" <?= $model->user_status == 20 ? "selected" : null ?>>否</option>
            <?php if (!empty($model->user_status)) { ?>
                <option value="0" <?= $model->user_status == 0 ? "selected" : null ?>>离职不能在激活</option>
            <?php } ?>
        </select>
    </div>
    <input type="hidden" id="staff" value="<?= $staff['staff_code'] ?>">
    <div class="mb-20">
        <label style="float:left;margin-left: 1px;" class="width-110">备注</label>
        <textarea id="remarks" style="min-width:633px;max-width: 633px;min-height:100px;max-height:100px;"
                  name="User[remarks]"><?= $model->remarks ? $model->remarks : null ?></textarea>
    </div>
    <h2 class="head-second">
        用户角色设置
    </h2>
    <div class="mb-20">
        <?php foreach ($roles as $key => $val) { ?>
            <span style="margin-left: 30px;"><input type="checkbox" name="role"
                                                    style="vertical-align: middle;" <?php foreach ($role as $keys => $values) { ?><?= $val['role_pkid'] == $role[$keys]['role_pkid'] ? "checked" : null;
                } ?> value="<?= $val['role_pkid'] ?>"><?= $val['role_name'] ?></span>
        <?php } ?>
    </div>
    <h2 id="data_setup" class="head-second special" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>数据权限设置</span>
    </h2>
    <div id="data_setups" class="mb-20" style="margin-left: 40px;display:none;">
        <ul id="datas" class="easyui-tree"
            checkbox="true">
        </ul>
        <div id="cover"></div>
        <div id="coverShow" style="margin: 0 auto;width: 130px;height: 100px;">
            <table align="center" border="0" cellspacing="0" cellpadding="0"
                   style="border-collapse: collapse; height: 30px; min-height: 30px;">
                <tr>
                    <td height="30" style="font-size: 12px;">数据加载中，请稍后......</td>
                </tr>

            </table>
        </div>
    </div>
    <h2 id="commodity_setup" class="head-second special" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>商品权限设置</span>
    </h2>
    <div id="commodity_setups" class="mb-20" style="margin-left: 40px;display:none;">
        <ul id="Commodity_category" class="easyui-tree"
            checkbox="true">
        </ul>
        <div id="cover1"></div>
        <div id="coverShow1" style="margin: 0 auto;width: 130px;height: 100px;">
            <table align="center" border="0" cellspacing="0" cellpadding="0"
                   style="border-collapse: collapse; height: 30px; min-height: 30px;">
                <tr>
                    <td height="30" style="font-size: 12px;">数据加载中，请稍后......</td>
                </tr>

            </table>
        </div>
    </div>
    <h2 id="wh_setup" class="head-second special" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>仓库权限设置</span>
    </h2>
    <div id="wh_setups" class="mb-20" style="margin-left: 40px;display:none;">
        <ul id="wh_part" class="easyui-tree"
            checkbox="true">
        </ul>
        <div id="cover2"></div>
        <div id="coverShow2" style="margin: 0 auto;width: 130px;height: 100px;">
            <table align="center" border="0" cellspacing="0" cellpadding="0"
                   style="border-collapse: collapse; height: 30px; min-height: 30px;">
                <tr>
                    <td height="30" style="font-size: 12px;">数据加载中，请稍后......</td>
                </tr>

            </table>
        </div>
    </div>
    <h2 class="head-second special" style="cursor:pointer;text-indent:5px;">
        <i class="icon-caret-right" style="font-size:25px;vertical-align:middle;"></i><span>用户厂区设置</span>
    </h2>
    <div class="mb-20" style="margin-left: 40px;display:none;">
        <label style="float:left;margin-left: 1px;" class="label-width qlabel-align">绑定厂区</label>
        <div id="selected-area" style="width: 633px;overflow-y:scroll;height: 35px;border: 1px solid #ccc;"></div>
        <div style="width: 694px;height: 200px;overflow-y:scroll;margin-top: 20px;border: 1px solid #ccc;">
            <?php foreach ($factory as $key => $val) { ?>
                <span style="margin-left: 30px;">
                    <input type="checkbox" name="factory"
                           style="vertical-align: middle;"
                        <?php foreach ($area as $keys => $values) { ?><?= $val['factory_id'] == $area[$keys]['area_pkid'] ? "checked" : null;
                        } ?>
                           data-name="<?= $val['factory_name'] ?>"
                           value="<?= $val['factory_id'] ?>">
                    <?= $val['factory_name'] ?>
                </span>
            <?php } ?>
        </div>
    </div>
    <input type="hidden" id="user_id" value="<?= $user_id ?>">
    <div class="ml-320">
        <?= Html::button($model->isNewRecord ? '确认' : '更新', ['class' => 'button-blue-big sbtn']) ?>&nbsp;
        <?= Html::button('返回', ['class' => 'button-white-big ml-20', 'onclick' => 'history.go(-1)']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    $(function ($) {
        ajaxSubmitForm($("#add-form"));
//        $(".sbtn").click(function () {
//            test();
//        });
        $(".sbtn").click(function () {
            setTimeout("$('.sbtn').attr('disabled',false)", 100);
            var user_account = $("#user-user_account").val();
            var user_code = $("#user_code").val();
            var mobile = $("#mobile").val();
            var email = $("#email").val();
            var user_type = $("#user_type").val();
            if (user_account.length == 0) {
                $(".error-notice").text("帐号不能为空。");
            }
            if (user_code.length == 0) {
                $("#user_code").addClass("validatebox-invalid");
            }
            if (mobile.length == 0) {
                $("#mobile").addClass("validatebox-invalid");
            }
            if (email.length == 0) {
                $("#email").addClass("validatebox-invalid");
            }
            if (user_type.length == 0) {
                $("#user_type").addClass("validatebox-invalid");
            }

            var i = 0;
            $(".validatebox-invalid").each(function () {
                i++;
            });
            var error_notice = $(".error-notice").text();
            if (error_notice.length > 0) {
                i++;
            }
            if (i == 0) {
                submit();
//                alert(123);
            }
            else {
                layer.alert("你填写的内容有误，请填写正确!", {icon: 0});
            }
        });

        //输入工号显示数据
        $("#user_code").blur(function () {
            $.ajax({
                dataType: "json",
                data: {
                    'staff_code': $(this).val().trim(),
                    'code': $("#staff").val()
                },
                url: "<?= Url::to(['get-staff'])?>",
                type: 'get',
                success: function (data) {
//                    console.log(data);
                    if (data == 1) {
                        $("#staff_name").html("工号无效").css('color', 'red');
                        $("#user_code").addClass("validatebox-invalid");
                        $(".staff_organization").val("");
                        $(".staff_mobile").val("");
                        $(".other_contacts").val("");
                        $(".staff_email").val("");
                        $(".staff_id").val("");
                        $(".other_contacts").removeClass("validatebox-invalid");
                        $(".TelErrorPrompt").html(" ");
                        $(".staff_mobile").removeClass("validatebox-invalid");
                        $(".staff_email").removeClass("validatebox-invalid");

                    } else if (data == 2) {
                        $("#staff_name").html("工号已存在").css('color', 'red');
                        $("#user_code").addClass("validatebox-invalid");
                        $(".staff_organization").val("");
                        $(".staff_mobile").val("");
                        $(".other_contacts").val("");
                        $(".staff_email").val("");
                        $(".staff_id").val("");
                        $(".other_contacts").removeClass("validatebox-invalid");
                        $(".TelErrorPrompt").html(" ");
                        $(".staff_mobile").removeClass("validatebox-invalid");
                        $(".staff_email").removeClass("validatebox-invalid");
                    }
                    else if (data == 0) {
                        $("#staff_name").html("<?= $staff['staff_name'] ? $staff['staff_name'] : null ?>").css('color', '#000');
                        $(".staff_organization").val("<?= $staff['organization'] ? $staff['organization'] : null ?>");
                        $(".staff_mobile").val("<?= $model->user_mobile ? $model->user_mobile : null ?>");
                        $(".other_contacts").val("<?= $model->other_tel ? $model->other_tel : null ?>");
                        $(".staff_email").val("<?= $model->user_email ? $model->user_email : null ?>");
                        $(".other_contacts").removeClass("validatebox-invalid");
                        $(".TelErrorPrompt").html(" ");
                        $(".staff_mobile").removeClass("validatebox-invalid");
                        $(".staff_email").removeClass("validatebox-invalid");
                        $(".staff_id").val("<?= $model->staff_id ? $model->staff_id : 0 ?>");
                    }
                    else {
                        $("#user_code").removeClass("validatebox-invalid");
                        $("#staff_name").html(data.staff_name).css('color', '#000');
                        $(".staff_organization").val(data.organization);
                        $(".staff_mobile").val(data.staff_mobile);
                        $(".staff_mobile").removeClass("validatebox-invalid");
                        $(".other_contacts").val(data.other_contacts);
                        $(".other_contacts").removeClass("validatebox-invalid");
                        $(".TelErrorPrompt").html(" ");
                        $(".staff_email").val(data.staff_email);
                        $(".staff_email").removeClass("validatebox-invalid");
                        $(".staff_id").val(data.staff_id);
                    }
                },
                error: function (data) {
                }
            })
        });

        //模块显示隐藏
        $(".special").click(function () {
            if ($(this).next().is(":visible")) {
                $(this).next().hide();
                $(this).find("i").removeClass().addClass("icon-caret-right");
                setMenuHeight();
            } else {
                $(this).next().show();
                $(this).find("i").removeClass().addClass("icon-caret-down");
                if ($(this).find("span").html() == '供应商详细信息') {
                    $(".easyui-tabs").tabs("resize");
                }
                setMenuHeight();
            }
        });

        $("input[name='factory']").each(function () {
            var name = $(this).data('name');
            var id = $(this).val();
            var item = $("<div class='item'></div>").text(name).attr("data-id", id).css({
                width: "80px",
                "height": "30px",
                "line-height": "30px",
                "float": "left"
            });
            var remove = $("<span class='remove'>&times;</span>").css({"cursor": "pointer", "color": "red"});
            item.append(remove);
            if ($(this).is(':checked')) {
                $("#selected-area").append(item);
            }
        });

        //选择厂区显示在上面的显示框中
        $("input[name='factory']").click(function () {
            var name = $(this).data('name');
            var id = $(this).val();
            var item = $("<div class='item'></div>").text(name).attr("data-id", id).css({
                width: "80px",
                "height": "30px",
                "line-height": "30px",
                "float": "left"
            });
            var remove = $("<span class='remove'>&times;</span>").css({"cursor": "pointer", "color": "red"});
            item.append(remove);
            if ($(this).is(':checked')) {
                $("#selected-area").append(item);
            }
            else {
//                $("#selected-area").empty();
                $(".item").each(function () {
//                    alert($(this).data("id"));
                    if ($(this).data("id") == id) {
//                        alert($(this).data("id"));
                        $(this).remove();
                    }
                });
            }
        });
        //点击红色×
        $("#selected-area").on("click", ".remove", function () {
            var id = $(this).parent(".item").data("id");
            $(this).parent(".item").remove();
            $("input[name='factory']").each(function () {
                if (id == $(this).val()) {
                    $(this).attr("checked", false)
                }
            });
        });

    });
    var userid = '';
    if ($("#user_id").val().length==0) {
        userid = 0;
    }
    else {
        userid = $("#user_id").val();
    }
    $("#data_setup").click(function () {
        //数据权限树
        $("#datas").tree({
            url: '<?= Url::to(["tree"]) ?>?userid=' + userid,
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit();
                $("#datas").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");
            },
            onSelect: function (node) {
                if (node.state == "closed")
                    $(this).tree('expand', node.target);
                else
                    $(this).tree('collapse', node.target);
            }, onloaderror: function (arguments) {
                alert(arguments);

            }
        });
    });

    //商品权限树
    $("#commodity_setup").click(function () {
        $("#Commodity_category").tree({
            url: '<?= Url::to(["trees"]) ?>?userid=' + userid,
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit1();
                $("#Commodity_category").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");
            },
            onSelect: function (node) {
                if (node.state == "closed")
                    $(this).tree('expand', node.target);
                else
                    $(this).tree('collapse', node.target);
            }, onloaderror: function (arguments) {
                alert(arguments);

            }
        });
    });

    //仓库权限树
    $("#wh_setup").click(function () {
        $("#wh_part").tree({
            url: '<?= Url::to(["get-wh-tree"]) ?>?user_id=' + userid,
            lines: true,
            onLoadSuccess: function () {
                hidden_coverit2();
                $("#wh_part").tree("collapseAll");
                $(".tree-icon,.tree-file").removeClass("tree-icon tree-file");
                $(".tree-icon,.tree-folder").removeClass("tree-icon tree-folder tree-folder-open tree-folder-closed");
            },
            onSelect: function (node) {
                if (node.state == "closed")
                    $(this).tree('expand', node.target);
                else
                    $(this).tree('collapse', node.target);
            }, onloaderror: function (arguments) {
                alert(arguments);

            }
        });
    });

    //保存
    function submit() {
        var type_name = $("#user_type").find("option:selected").text();
        var data = $("form").serializeArray();
        var data_rid = [];
        var Commodity_rid = [];
        var Role_rid = [];
        var factory_id = [];
        var part_id=[];
        var wh_id=[];
        var whpk_id=[];

        //用户角色
        $("input[name='role']").each(function () {
            if ($(this).is(':checked')) {
                Role_rid.push($(this).val());
            }
        });
        //数据权限选择id
        $("#datas .tree-checkbox1").each(function () {
            var s = $(this).parent().find(".tree-title");
            var a = s.find(".level").text();
            if (a == 1 || a == 2) {
                data_rid.push(s.find(".catgid").text());
            }
        });
        //  商品权限选择id
        $("#Commodity_category .tree-checkbox1").each(function () {
            var s = $(this).parent().find(".tree-title");
            var a = s.find(".level").text();
            if (a == 3 || a == 2||a == 4||a==1) {
                Commodity_rid.push(s.find(".catgid").text());
            }
        });

        //仓库权限多个id
        $("#wh_part .tree-checkbox1").each(function () {
            var s = $(this).parent().find(".tree-title");
            var a = s.find(".level").text();
            if (a == 2) {
                part_id.push(s.find(".part_id").text());
                whpk_id.push(s.find(".wh_id").text());
            }
            if(a==1)
            {
                wh_id.push(s.find(".wh_id").text())
            }
            for(var i=0;i<wh_id.length;i++)
            {
                for(var j=0;j<whpk_id.length;j++)
                {
                    if(wh_id[i]==whpk_id[j])
                    {
                        wh_id.splice(i,1);
                        break;
                    }
                }
            }
        });

        //厂区
        $("input[name='factory']").each(function () {
            if ($(this).is(':checked')) {
                factory_id.push($(this).val());
            }
        });
        if (data != null) {
            $.ajax({
                dataType: "JSON",
                url: "<?= Url::to(["save"])?>",
                async: false,
                data: {
                    user_id: $("#user_id").val(),
                    type_name: type_name,
                    data: data,
                    Role_rid: Role_rid.join(),
                    data_rid: data_rid.join(),
                    Commodity_rid: Commodity_rid.join(),
                    wh_id:wh_id.join(),
                    part_id:part_id.join(),
                    whpk_id:whpk_id.join(),
                    factory_id: factory_id.join()
                },
                type: 'POST',
                success: function (datas) {
                    console.log(datas);
//                    window.location.href = "<?//=Url::to(['test'])?>//?data="+datas.datas;
                    if (datas == 1) {
                        layer.alert("保存成功", {icon: 2}, function () {
                            window.location.href = "<?=Url::to(['index'])?>";
                        });
                    } else {
                        layer.alert("保存出现错误,保存失败!", {icon: 2})
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(textStatus);
                }
            });

        } else {
            layer.alert("请选择需要关联的三阶", {icon: 2});
            return false;
        }
    }

    //联系电话
    $("#other_tel").blur(function () {
        var str = $(this).val();
        if (str.length == 0) {
//            $("#other_tel").addClass("redborder");
//            $(".TelErrorPrompt").html("你输入的联系方式有错误").css("color", "red");
        } else {
            var phone = /^0?1[3|4|5|8][0-9]\d{8}$/;
            var tels = /^0\d{2,3}-[1-9]\d{6,7}$/;
            var telss = /^[\(|（]0\d{2,3}[\)|）]-[1-9]\d{6,7}$/;
            if (!$("#other_tel").val().match(phone) && !$("#other_tel").val().match(tels) && !$("#other_tel").val().match(telss)) {
//                customerphoneFlag = false;
                $("#other_tel").addClass("validatebox-invalid");
                $(".TelErrorPrompt").html("你输入的联系方式有错误").css("color", "red");
            }
        }
    });
    $("#other_tel").focus(function () {
        $(this).removeClass("validatebox-invalid");
        $(".TelErrorPrompt").html(" ");
    });

    //数据权限树
    function hidden_coverit() {
        var cover = document.getElementById("cover");
        var covershow = document.getElementById("coverShow");
        cover.style.display = 'none';
        covershow.style.display = 'none';
    }
    //商品权限树
    function hidden_coverit1() {
        var cover = document.getElementById("cover1");
        var covershow = document.getElementById("coverShow1");
        cover.style.display = 'none';
        covershow.style.display = 'none';
    }

    //仓库权限树
    function hidden_coverit2() {
        var cover = document.getElementById("cover2");
        var covershow = document.getElementById("coverShow2");
        cover.style.display = 'none';
        covershow.style.display = 'none';
    }

    function test()
    {
        var part_id=[];
        var wh_id=[];
        var whpk_id=[];
        $("#wh_part .tree-checkbox1").each(function () {
            var s = $(this).parent().find(".tree-title");
            var a = s.find(".level").text();
            if (a == 2) {
                part_id.push(s.find(".part_id").text());
                whpk_id.push(s.find(".wh_id").text());
            }
            if(a==1)
            {
                wh_id.push(s.find(".wh_id").text())
            }
            for(var i=0;i<wh_id.length;i++)
            {
                for(var j=0;j<whpk_id.length;j++)
                {
                    if(wh_id[i]==whpk_id[j])
                    {
                        wh_id.splice(i,1);
                        break;
                    }
                }
            }
        });

        console.log("wh_id:"+wh_id);
        console.log("part_id:"+part_id);
        console.log("whpk_id:"+whpk_id);
    }

</script>

