<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/7/5
 * Time: 下午 01:48
 */

use app\assets\JqueryUIAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '商品选择';
$param = Yii::$app->request->queryParams;
?>
<?php

JqueryUIAsset::register($this);

?>


<style>
    .label-width {
        width: 80px;
    }

    .value-width {
        width: 160px;
    }
</style>
<div class="pop-head">
    <p>商品选择</p>
</div>
<div class="content">
    <div>
        <?php $form = ActiveForm::begin(
            [
                'action' => ['add'],
                'method' => 'get',
                'id' => 'warm-search'
            ]
        ); ?>
        <div style="float: left">
            <div>
                <input style="display: none" name="id" value="<?= $id ?>">
                <label for="wh_code" class="label-width"><span class="red">*</span>仓&nbsp&nbsp库：</label>
                <select name="wh_id" class="value-width easyui-validatebox" data-options="required:'true'" id="wh_id">
                    <option value="">--请选择--</option>
                    <?php foreach ($downList['whname'] as $val) { ?>
                        <?php if ($get['wh_id'] == $val['wh_id']) { ?>
                            <option selected="selected" value="<?= $val['wh_id'] ?>"
                                    name="wh_id"><?= $val['wh_name'] ?></option>
                        <?php } else { ?>
                            <option value="<?= $val['wh_id'] ?>" name="wh_id"><?= $val['wh_name'] ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                <input type="text" id="partno" style="margin-left: 10px;width: 220px;" placeholder="料号/商品名称/规格型号"
                       name="searchText" value="<?= $param['searchText'] ?>">
            </div>
        </div>
        <div>
            <?= Html::submitButton('查询', ['class' => 'search-btn-blue ml-80', 'type' => 'submit', 'style' => 'margin-left:10px', 'id' => 'btnSearch']) ?>
            <?= Html::resetButton('重置', ['class' => 'reset-btn-yellow ml-40', 'type' => 'reset', 'onclick' => 'window.location.href=\'' . Url::to(["add"]) . '\'']) ?>
        </div>
        <?php $form->end(); ?>
        <!--        --><?php //$form = ActiveForm::begin(
        //            [
        //                'action' => ['add'],
        //                'method' => 'post',
        //            ]
        //        ); ?>
        <div class="space-10"></div>
        <div id="data" style="width: 100%;"></div>

        <div class="mt-60" style="text-align: center;padding-top: 10px;">
            <button type="button" class="ml-300  button-blue-big" id="add">确定</button>
            <!--            <button type="submit" class="ml-100 button-blue-big">保存</button>-->

            <button type="button" class="button-white-big ml-30"
                    onclick="parent.$.fancybox.close()">取消
            </button>
        </div>
    </div>
    <!--    --><?php //$form->end(); ?>
</div>

<script>
    $(function () {
        $("#warm-search").on("beforeSubmit", function () {
            var wh_id = $("#wh_id").val();
            if (wh_id == "") {
                $("#btnSearch").attr("disabled", false);
                alert("仓库为必选条件!");
                return false;
            }
        })
        "use strict";
        var htmltr = "";
        var arr = "";
        var wh_id1 = "";
        wh_id1 = $("#wh_id", window.parent.document).val();
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "inv_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
            fitColumns: true,
            columns: [[
                {field: 'ck', checkbox: true, align: 'left', width: "3%"},
                {field: "wh_name", title: "仓库", width: "15%"},
                {field: "part_no", title: "料号", width: "30%"},
                {field: "pdt_name", title: "商品名称", width: "20%"},
                {field: "tp_spec", title: "规格型号", width: "15%"},
                {field: "invt_num", title: "现有库存", width: "13%"},
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var id = rowData['inv_id'];
                //设置选中事件，清除之前多行选择
                $("#data").datagrid("uncheckAll");
            }
        });

        var p = $('#data').datagrid('getPager');    //分页
        $(p).pagination({
            pageSize: 10,
            beforePageText: '第',
            afterPageText: '页   共 {pages} 页',
            displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录'
        });

        //定价申请详情
        $("#add").on("click", function () {
            var rows = $("#data").datagrid("getChecked");
            if (rows.length == 0) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
                return false;
            } else {
                var array = new Array();
                for (var i = 0; i < rows.length; i++) {
                    array.push(rows[i].wh_id);
                }
                var bool = true;
                for (var j = 0; j < array.length; j++) {
                    if (array[j] != array[0]) {
                        bool = false;
                    }
                }
                if (!bool) {
                    alert("每次只能操作同个仓库下的商品,请检查后重新选择!");
                    $("#data").datagrid("clearChecked");
                    return false;
                }
                var trLength = $("#table_body tr", window.parent.document).length;
                if (trLength > 0) {
                    var tdData = $("#table_body tr", window.parent.document).first().children().eq(2).attr('data');
                    if (rows[0].wh_id != tdData) {
                        alert("必须与上次操作的仓库为同一仓库!");
                        return false;
                    }
                }
                var tdPartNo = $("#table_body tr", window.parent.document);
                var arrayOne = new Array();
                if (tdPartNo.length > 0) {
                    $(tdPartNo).each(function () {
                        var part_no = $(this).children().eq(4).text();
                        arrayOne.push(part_no);
                    })
                    var rowIndex=[];
                    for(var i=0;i<rows.length;i++)
                    {
                        for (var j=0;j<arrayOne.length;j++)
                        {
                                if(rows[i].part_no==arrayOne[j])
                                {
                                    rowIndex.push(i);
                                }
                        }
                    }
                    for (var k=0;k<rowIndex.length;k++)
                    {
                        if (k==0)
                        {
                            rows.splice(rowIndex[k],1);
                        }
                        else{
                                rows.splice(rowIndex[k]-k,1);
                        }

                    }
                }
                if(rows.length>0)
                {
                    for (var i = 0; i < rows.length; i++) {
                        var j=i+1+tdPartNo.length;
                        var html = "<tr>";
                        html += "<td>" + j +"</td><td><input type='checkbox' name='chk'></td><td data=" + rows[i].wh_id + ">" + rows[i].wh_name + "</td><td>" + rows[i].pdt_name + "</td><td>" + rows[i].part_no + "</td><td>" + rows[i].tp_spec+ "</td><td><input type='number' style='width:120px;' min='0' onkeyup='inputOnkey(this)'></td>";
                        html += "<td><input type='number' style='width: 120px;' min='0' onkeyup='inputOnkey(this)'></td><td>" + rows[i].invt_num + "</td><td><input type='number' style='width: 120px;' min='0' onkeyup='inputOnkey(this)'></td><td><input type='text' style='width: 230px;'></td>";
                        html += "<td><a title='刪除' class='icon-minus-sign fs-18' onclick='deleteAccompanyPerson(this)'></a><a class='icon-refresh  fs-18' style='margin-left: 20px;' title='重置' onclick='resetAccompanyPerson(this)'></a></td>"
                        html += "</tr>";
                        $("#table_body", window.parent.document).append(html);
                    }
                }
                parent.$.fancybox.close();
                if ($("#table_body tr", window.parent.document).length > 0) {
                    $("#save", window.parent.document).attr("disabled", false);
                    $("#submit", window.parent.document).attr("disabled", false);
                }
            }
        });


    });

</script>

