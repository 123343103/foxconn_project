<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2018/1/30
 * Time: 下午 02:26
 */
use yii\widgets\ActiveForm;

$this->title = '批量收款申请';
$this->params['homeLike'] = ['label' => '订单管理'];
$this->params['breadcrumbs'][] = ['label' => '批量绑定订单', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '批量收款申请'];
?>
<div class="content">
    <h2 class="head-first">批量收款申请</h2>
    <div>
        <p style="font-size: 14px;"><label class="red">温馨提示:上传文件请严格按照模板进行</label></p>
        <a style="font-size: 14px" id="batchApply" href="<?= \yii\helpers\Url::to(['down-template']) ?>">批量收款申请模板下载</a>
        <?php ActiveForm::begin(['id' => 'import-form', 'action' => \yii\helpers\Url::to(['import']), 'options' => ['enctype' => 'multipart/form-data']]) ?>
        <p style="margin-top: 10px;">
            <input type="file" onchange="Import()" name="UploadForm[file]" id="file">
        </p>
        <?php ActiveForm::end() ?>
        <div id="tip">
            <label class="red">多笔订单共用一笔流水</label> <span>要求：1.任意交易法人 2.一个订单只能对应一个流水，一个流水可以对应多个订单，订单之间用“;”隔开，流水号不能重复出现 3.收款金额大于或等于订单金额说明可以为空，订单金额减收款金额差额在20RMB内(含20)说明不能为空</span>
            <label class="red">同公司多订单对多流水</label> <span>要求：1.一次只能导入同一个交易法人、同一个公司的收款信息 2.一个订单对应多个流水或多个订单对应多个流水，导入的订单+流水编号保持唯一性 3.说明不能为空 </span>
        </div>
        <div id="table1" style="margin-top: 15px;">
            <table class="table">
                <tr>
                    <td>订单号</td>
                    <td>交易流水号</td>
                    <td>说明</td>
                </tr>
                <?php for ($i = 0; $i < count($data); $i++) { ?>
                    <tr>
                        <td><input type="text" style="width: 95%;" value="<?= $data[$i]['ord_no'] ?>"></td>
                        <td><input type="text" style="width: 95%;" value="<?= $data[$i]['TRANSID'] ?>"></td>
                        <td><input type="text" style="width: 95%;" value="<?= $data[$i]['remark'] ?>"></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div style="text-align: center;margin-top: 15px;" id="button">
            <button class="button-blue-big" style="width: 160px;" onclick="btnAllAdd()">多笔订单共用一笔流水</button>
            <button class="button-blue-big" style="width: 160px;margin-left: 20px" onclick="batchSavebtn()">同公司多订单对多流水</button>
            <button class="button-blue-big" style="margin-left: 20px;" onclick="history.go(-1)">返回</button>
        </div>
    </div>
</div>
<script>
    function Import() {
        $("#import-form").submit();
        $("#import-form").ajaxForm(
            function (data) {
                $("#file").hide();
                $("#table1").html(data);
            }
        );
    }
    function btnAllAdd() {
        var tr = $("table tr:gt(0)");
        var importList = "[";
        var i = 1;
        $.each(tr, function () {
            if (i == tr.length) {
                importList += "{\"order_no\":\"" + $($(this).children().eq(0).children()).val() + "\",\"transid\":\"" + $($(this).children().eq(1).children()).val() + "\",\"remark\":\"" + $($(this).children().eq(2).children()).val() + "\"}";
            }
            else {
                importList += "{\"order_no\":\"" + $($(this).children().eq(0).children()).val() + "\",\"transid\":\"" + $($(this).children().eq(1).children()).val() + "\",\"remark\":\"" + $($(this).children().eq(2).children()).val() + "\"},";
            }
            i++;
        })
        importList += "]";
        $.ajax({
            url: "<?=\yii\helpers\Url::to(['check'])?>",
            data: {importList: importList},
            dataType: "json",
            type: "post",
            success: function (data) {
                if (data.status == 0) {
                    if (data.isok == 0) {
                        alert("订单号:" + data.msg + "不存在!");
                    }
                    else if (data.isok == 1) {
                        alert("流水号:" + data.msg + "不存在!");
                    }
                    else if (data.isok == 2) {
                        alert("流水号或订单号不能为空!");
                    }
                    else {
                        alert(data.msg);
                    }
                }
                else {
                    $.ajax({
                        url: "<?=\yii\helpers\Url::to(['save-list'])?>?importList=" + importList + "&rbo_id=" + "<?=$rbo_id?>",
                        dataType: "json",
                        type: "get",
                        success: function (data) {
                            if(data.status==0)
                            {
                                alert(data.msg);
                            }
                            else {
                                $.fancybox({
                                    href: "<?=\yii\helpers\Url::to(['verify-save-list'])?>?importList=" + importList + "&rbo_id=" + "<?=$rbo_id?>",
                                    type: "iframe",
                                    padding: 0,
                                    autoSize: false,
                                    width: 750,
                                    height: 490,
                                    fitToView: false
                                })
                            }
                        }
                    })
                }
            }
        })
    }
    //同公司多订单对多流水
    function batchSavebtn() {
        var tr = $("table tr:gt(0)");
        var importList = "[";
        var i = 1;
        $.each(tr, function () {
            if (i == tr.length) {
                importList += "{\"order_no\":\"" + $($(this).children().eq(0).children()).val() + "\",\"transid\":\"" + $($(this).children().eq(1).children()).val() + "\",\"remark\":\"" + $($(this).children().eq(2).children()).val() + "\"}";
            }
            else {
                importList += "{\"order_no\":\"" + $($(this).children().eq(0).children()).val() + "\",\"transid\":\"" + $($(this).children().eq(1).children()).val() + "\",\"remark\":\"" + $($(this).children().eq(2).children()).val() + "\"},";
            }
            i++;
        })
        importList += "]";
        $.ajax({
            url: "<?=\yii\helpers\Url::to(['check-two'])?>",
            data: {importList: importList},
            dataType: "json",
            type: "post",
            success: function (data) {
                if (data.status == 0) {
                    if (data.isok == 0) {
                        alert("订单号:" + data.msg + "不存在!");
                    }
                    else if (data.isok == 1) {
                        alert("流水号:" + data.msg + "不存在!");
                    }
                    else if (data.isok == 2) {
                        alert("流水号或订单号不能为空!");
                    }
                    else {
                        alert(data.msg);
                    }
                }
                else {
                    $.ajax({
                        url: "<?=\yii\helpers\Url::to(['save-list-two'])?>?importList=" + importList,
                        dataType: "json",
                        type: "get",
                        success: function (data) {
                            if (data.status == 0) {
                                alert(data.msg);
                            }
                            else {
                                $.fancybox({
                                    href: "<?=\yii\helpers\Url::to(['verify-save-list-two'])?>?importList=" + importList + "&rbo_id=" + "<?=$rbo_id?>",
                                    type: "iframe",
                                    padding: 0,
                                    autoSize: false,
                                    width: 750,
                                    height: 490,
                                    fitToView: false
                                })
                            }
                        }
                    })
                }
            }
        })
    }
</script>



