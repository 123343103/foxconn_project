<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/16
 * Time: 下午 03:44
 */
use yii\helpers\Url;
use app\assets\JqueryUIAsset;

JqueryUIAsset::register($this);

?>
<style>
    .button-white, .button-white-small {
        width: 70px;
        height: 25px;
        background-color: #ffffff;
        color: #1f7ed0;
        border: 1px solid #1f7ed0;
        margin-left: 10px;
    }
</style>
<div id="msgbox">
    <h2 class="head-first" style="margin-bottom: 10px;">发短信</h2>
    <p class="red" style="margin:0 10px;font-size: 1.17em;">无手机号码或号码格式错误的客户将会被过滤！</p>
    <div style="padding:10px;">
        <div class="has-add-wrap">
            <div class="mb-10" style="width:780px;">
                <span class="pull-left" style="line-height: 25px;">已选择的客户信息</span>
                <span class="pull-right">
                                <button type="button" class="add-btn button-blue">继续添加</button>
                                <button type="button" class="del-btn button-white">删除行</button>
                            </span>
                <div style="clear: both;"></div>
            </div>
            <div style="width:100%;" class="has-add-data"></div>
            <?php $form = \yii\widgets\ActiveForm::begin([
                'id' => 'msg-form',
                'action' => Url::to(['send-message', 'type' => 1]),
                'options' => [
                    'target' => 'feedback',
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>
            <input id="customers" type="hidden" name="customers">
            <input type="hidden" name="CrmActImessage[imesg_sentman]"
                   value="<?= \Yii::$app->user->identity->staff->staff_id; ?>">
            <input type="hidden" name="CrmActImessage[imesg_type]" value="1">
            <p class="mb-10">已选择客户的手机号：</p>
            <textarea class="has-add-mobile easyui-validatebox" data-options="required:true,validType:'multi_phone',tipPosition:'top'"
                      name="Select" id="" style="height: 50px;" readonly></textarea>
            <p class="mb-10">可录入其它需要发送信息的手机号：</p>
            <textarea name="Other" id="" style="height: 50px;" class="easyui-validatebox"
                      data-options="validType:'multi_phone',tipPosition:'bottom'"></textarea>
            <p class="mb-10"><span style="color: red">*</span>信件内容：</p>
            <textarea name="CrmActImessage[imesg_notes]" class="easyui-validatebox mb-10"
                      data-options="required:true,tipPosition:'top',validType:'maxLength[200]'" id="" style="height: 50px;"
                      maxlength="200"></textarea>
            <div class="text-center mb-10">
                <button type="submit" class="button-blue send">发送</button>
                <button type="button" class="button-white cancel">取消</button>
            </div>
            <?php $form->end(); ?>
        </div>
        <div class="add-wrap" style="display: none;">
            <div class="mb-10" style="width:780px;">
                    <span class="pull-left">
                        <span>请选择客户信息</span>
                        <input style="text-indent: 1em;" class="search-kwd" type="text" placeholder="公司名称或姓名">
                        <button type="button" class="search-btn button-blue">搜索</button>
                        <button type="button" class="search-reset button-white">重置</button>
                    </span>
                <span class="pull-right">
                        <button type="button" class="add-ensure button-blue">确定</button>
                        <button type="button" class="add-cancel button-white">返回</button>
                    </span>
                <div style="clear: both;"></div>
            </div>
            <div class="add-data"></div>
        </div>
        <div style="display: none;" class="feedback-wrap">
            <div class="mb-10">
                <button type="button" class="button-white return">确定</button>
            </div>
            <h3>结果反馈:</h3>
            <iframe name="feedback" frameborder="0" style="width:100%;height: 400px;overflow: scroll;"></iframe>
        </div>
    </div>
</div>

<script>
    $(function () {
        var re = /^1[3|4|5|7|8][0-9]\d{4,8}$/;
        $.extend($.fn.validatebox.defaults.rules, {
            multi_phone: {
                validator: function (value) {
                    var flag = true;
                    var pattern = /^1[3|4|5|7|8][0-9]\d{8}$/;
                    var arr = value.replace(/\s+/g, "").replace(/(^,)|(,$)/g, "").split(",");
                    for (var n = 0; n < arr.length; n++) {
                        if (!pattern.test(arr[n])) {
                            flag = false;
                            break;
                        }
                    }
                    return flag;
                },
                message: "号码格式错误"
            }
        });

        $("form").click(function () {
            $("button").prop("disabled", false);
        });

        $("form").submit(function () {
            if (!$(this).form('validate')) {
                return false;
            }
            $(".has-add-wrap").slideUp();
            $(".feedback-wrap").slideDown();
        });
        $(".feedback-wrap .return").click(function () {
            parent.$.fancybox.close();
            parent.$('#data').datagrid('reload');
//            $(".feedback-wrap").slideUp();
//            $(".has-add-wrap").slideDown();
//            $("button").prop("disabled", false);
        });
        var rows = parent.$(".main-table").datagrid("getChecked");
        $("#msgbox .has-add-data").datagrid({
            rownumbers: true,
            singleSelect: false,
            checkOnSelect: true,
            selectOnCheck: true,
            columns: [[
                {field: "ck", checkbox: true, width: 20},
                {field: "cust_sname", title: "公司名称", width: 196},
                {field: "cust_contacts", title: "姓名", width: 180},
                {field: "cust_position", title: "职位", width: 160},
                {field: "cust_tel2", title: "手机号码", width: 180}
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this), data.total, 0);
                $("#msgbox .has-add-data").datagrid("resize");
            }
        });
        for (var x = 0; x < rows.length; x++) {
            re.test(rows[x].cust_tel2) && $("#msgbox .has-add-data").datagrid("insertRow", {index: 1, row: rows[x]});
        }
        datagridTip($(".has-add-data"));
        updateCustomer();

        $("#msgbox .add-data").datagrid({
            url: "<?=$url ? $url : Url::to(['index'])?>",
            rownumbers: true,
            method: "get",
            idField: "cust_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize: 5,
            pageList: [5, 10, 15],
            singleSelect: false,
            checkOnSelect: true,
            selectOnCheck: true,
            columns: [[
                {field: "ck", checkbox: true, width: 20},
                {field: "cust_sname", title: "公司名称", width: 198},
                {field: "cust_contacts", title: "姓名", width: 180},
                {field: "cust_position", title: "职位", width: 180},
                {field: "cust_tel2", title: "手机号码", width: 180}
            ]],
            onLoadSuccess: function (data) {
                $("#msgbox .add-data").datagrid("resize");
                datagridTip($("#msgbox .add-data"));
                showEmpty($(this), data.total, 1);
            }
        });
        $("#msgbox .add-btn").click(function () {
            $("#msgbox .has-add-wrap").slideUp();
            $("#msgbox .add-wrap").slideDown();
            $("#msgbox .add-data").datagrid("clearSelections");
            $("#msgbox .add-data").datagrid("reload", {
                'customers': $('#customers').val()
            });
            $("#msgbox .add-data").datagrid("resize");
        });


        $("#msgbox .add-ensure").click(function () {
            var newRows = $("#msgbox .add-data").datagrid("getSelections");
            var oldRows = $("#msgbox .has-add-data").datagrid("getRows");
            $("#msgbox .has-add-wrap").slideDown();
            $("#msgbox .add-wrap").slideUp();


            for (var x = 0; x < newRows.length; x++) {
                var flag = true;
                for (var y = 0; y < oldRows.length; y++) {
                    if (oldRows[y].cust_id == newRows[x].cust_id || !re.test(newRows[x].cust_tel2)) {
                        flag = false;
                        break;
                    }
                }
                if (flag == true) {
                    $("#msgbox .has-add-data").datagrid("insertRow", {
                        index: 0,
                        row: newRows[x]
                    });
                    $(".datagrid-view1 .datagrid-body-inner").css("padding-bottom", 0);
                }
            }
            datagridTip($(".has-add-data"));
            updateCustomer();

            $("#msgbox .has-add-wrap").slideDown();
            $("#msgbox .add-wrap").slideUp();
        });


        $("#msgbox .search-btn").click(function () {
            var kwd = $("#msgbox .search-kwd").val();
            $("#msgbox .add-data").datagrid("reload", {
                keywords: kwd
            });
        });

        $("#msgbox .search-reset").click(function () {
            $("#msgbox .search-kwd").val("");
            $("#msgbox .add-data").datagrid("reload", {
                customers: $('#customers').val(),
                cust_contacts: ""
            });
        });

        $("#msgbox .del-btn").click(function () {
            var rows = $("#msgbox .has-add-data").datagrid("getSelections");
            for (var x = 0; x < rows.length; x++) {
                $("#msgbox .has-add-data").datagrid("deleteRow", $("#msgbox .has-add-data").datagrid("getRowIndex", rows[x]));
            }
            updateCustomer();
            $("#msgbox .has-add-data").datagrid("resize");
        });


        $("#msgbox .add-cancel").click(function () {
            $("#msgbox .has-add-wrap").slideDown();
            $("#msgbox .add-wrap").slideUp();
        });


        $(".cancel,.exit").click(function () {
            parent.$.fancybox.close();
        });


        function updateCustomer() {
            var rows = $("#msgbox .has-add-data").datagrid("getRows");

            var tmp = new Array();
            for (var x = 0; x < rows.length; x++) {
                re.test(rows[x].cust_tel2) && tmp.push(rows[x].cust_tel2);
            }
            $("#msgbox .has-add-mobile").val(tmp.join(",")).validatebox();

            var tmp = new Array();
            for (var x = 0; x < rows.length; x++) {
                re.test(rows[x].cust_tel2) && tmp.push(rows[x].cust_id);
            }
            $("#customers").val(tmp.join(","));
        }

    });
</script>


<style type="text/css">
    textarea {
        width: 780px;
        height: 50px;
    }

    button {
        font-size: 12px;
    }

    button:hover {
        cursor: pointer;
        border: 1px solid #0e0e0e;
    }

    .has-add-wrap .datagrid-wrap {
        width: 780px !important;
    }

    .has-add-wrap .datagrid-view {
        max-height: 180px !important;
    }

    .has-add-wrap .datagrid-view2 {
        width: 750px !important;
        left: 30px;
        right: 0px;
    }

    .has-add-wrap .datagrid-body {
        width: 750px !important;
        max-height: 150px !important;
        overflow-x: hidden !important;
    }
</style>