<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/26
 * Time: 上午 10:11
 */

use yii\helpers\Url;

$this->params['homeLike'] = ['label' => '仓储物流管理', 'url' => ""];
$this->params['breadcrumbs'][] = ['label' => '库存预警信息查询', 'url' => 'index'];
$this->params['breadcrumbs'][] = ['label' => '修改库存预警申请'];
$this->title = '修改库存预警申请';
?>


<div class="content">

    <h1 class="head-first">
        修改库存预警申请
    </h1>
    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'edit',
        'enableAjaxValidation' => true,
        'action' => ['/warehouse/waring-info/edit?inv_id=' . $model->part_no . '&wh_id=' . $model->wh_code]
    ]) ?>
    <div class="mb-30">

        <h2 class="head-second" style="text-align: left;margin-top: -10px;margin-bottom: 10px;">
            库存预警设置
        </h2>
        <div style="width: 100%;overflow-x: auto">
            <table class="product-list" style="width: 1300px;">
                <thead>
                <tr>
                    <th style="width:5%">序号</th>
                    <th style="width:10%">仓库名称</th>
                    <th style="width:11%">商品名称</th>
                    <th style="width:14%">商品料号</th>
                    <th style="8%">规格型号</th>
                    <th style="width:8%">库存下限</th>
                    <th style="width:8%">安全库存</th>
                    <th style="width:8%">现有库存</th>
                    <th style="width:8%">库存上限</th>
                    <th style="width:15%">备注</th>
                </tr>
                </thead>
                <tbody id="tbody">
                <?php $int = 1 ?>
                <?php foreach ($priceList as $key => $val) { ?>
                    <tr>
                        <td> <?= $int ?>
                        </td>
                        <td><?= $val->wh_name ?><input style="display: none" name="biw_h_pkid[]" class="biw_h_pkid"
                                                       value="<?= $val->biw_h_pkid ?>"><input style="display: none"
                                                                                              class="wh_id"
                                                                                              value="<?= $val->wh_id ?>">
                        </td>
                        <td><?= $val->pdt_name ?></td>
                        <td><?= $val->part_no ?><input style="display: none" name="part_no[]"
                                                       value="<?= $val->part_no ?>">
                        </td>
                        <td><?= $val->tp_spec ?></td>
                        <td><input style="width:95%" name="down_nums[]" value="<?= $val->down_nums ?>" type="number"
                                   min="0" onkeyup="inputOnkey(this)"></td>
                        <td><input style="width:95%" name="save_num[]" value="<?= $val->save_num ?>" type="number"
                                   min="0" onkeyup="inputOnkey(this)"></td>
                        <td><?= $val->invt_num ?></td>
                        <td><input style="width:95%" name="up_nums[]" value="<?= $val->up_nums ?>" type="number" min="0"
                                   onkeyup="inputOnkey(this)"></td>
                        <td><input style="width:95%" name="remarks[]" value="<?= $val->remarks ?>"></td>
                    </tr>
                    <?php $int = $int + 1 ?>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="mb-20 text-center" style="margin-top: 10px;">
        <button class="button-blue-big" type="button" id="save">保存</button>
        <?php if ($priceList[0]->so_type != 20) { ?>
            <button class="button-blue-big ml-20 close" type="button" id="submit" style="margin-left: 42px;">提交</button>
        <?php } ?>
        <button class="button-white-big ml-20 close" type="button" id="back">取消</button>
    </div>
    <?php $form->end(); ?>

    <script>
        function inputOnkey(obj) {
            if (obj.value.length == 1) {
                obj.value = obj.value.replace(/[^1-9]/g, '')
            } else {
                obj.value = obj.value.replace(/\D/g, '')
            }
        }
        $("#back").on("click", function () {
            window.location.href = "<?=Url::to(['index'])?>";
        });

        $("#save").on("click", function () {
            if (check()) {
                var s = save("update");
                if (s.status == 1) {
                    layer.alert(s.msg, {icon: 2});
                    window.location = "view?biw_h_pkid=" + s.data;
                } else {
                    layer.alert(s.msg, {icon: 2});
                }
            }

        });
        $("#submit").on("click", function () {
            if (check()) {
                var warmInfo = "[";
                var trList = $("#tbody").children("tr");
                for (var i = 0; i < trList.length; i++) {
                    var tdArr = trList.eq(i).find("td");
                    warmInfo += "{\"biw_h_pkid\":" + "\"" + tdArr.eq(1).find("input").val() + "\"" + "," + "\"part_no\":" + "\"" + tdArr.eq(3).find("input").val() + "\"" + "," + "\"down_nums\":" + "\"" + tdArr.eq(5).find("input").val() + "\"" + ",";
                    warmInfo += "\"save_num\":" + "\"" + tdArr.eq(6).find("input").val() + "\"" + "," + "\"up_nums\":" + "\"" + tdArr.eq(8).find("input").val() + "\"" + "," + "\"remarks\":" + "\"" + tdArr.eq(9).find("input").val() + "\"" + "}";
                    if (i < trList.length - 1) {
                        warmInfo += ",";
                    }
                }
                warmInfo += "]";
            }
            $.ajax({
                type: "post",
                url: "<?=Url::to(['save'])?>",
                data: {
                    warmInfo: warmInfo,
                    action: 1
                },
                success: function (data) {
                    var data = eval('(' + data + ')');
                    if (data.status == 1) {
                        var type =<?=$typeId?>;
                        var id = data.data;
                        var url = "<?=Url::to(['view'])?>?biw_h_pkid=" + data.data;
                        $.fancybox({
                            href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 750,
                            height: 480,
                            afterClose:function () {
                                location.href="<?=Url::to(['view'])?>?biw_h_pkid="+id;
                            }
                        });
                    }

                }
            })
        });
        function save(action) {
            var tf = {};
            var warmInfo = "[";
            var trList = $("#tbody").children("tr");
            for (var i = 0; i < trList.length; i++) {
                var tdArr = trList.eq(i).find("td");
                warmInfo += "{\"biw_h_pkid\":" + "\"" + tdArr.eq(1).find("input").val() + "\"" + "," + "\"part_no\":" + "\"" + tdArr.eq(3).find("input").val() + "\"" + "," + "\"down_nums\":" + "\"" + tdArr.eq(5).find("input").val() + "\"" + ",";
                warmInfo += "\"save_num\":" + "\"" + tdArr.eq(6).find("input").val() + "\"" + "," + "\"up_nums\":" + "\"" + tdArr.eq(8).find("input").val() + "\"" + "," + "\"remarks\":" + "\"" + tdArr.eq(9).find("input").val() + "\"" + "}";
                if (i < trList.length - 1) {
                    warmInfo += ",";
                }
            }
            warmInfo += "]";
            $.ajax({
                type: 'POST',
                url: "<?= Url::to(["save"])?>",
                async: false,
                data: {
                    action: 0,
                    warmInfo: warmInfo
                },
                success: function (data) {
//                    alert(data);
                    var data = eval('(' + data + ')');
                    if (data['status'] == 0) {
                        tf = {"status": "0", "msg": data.msg};
                    } else {
                        tf = {"status": "1", "msg": "保存成功", "data": data.data};
                    }
                },
                error: function (xhr, type) {
                    tf = {"status": 0, "msg": xhr.error};
                }
            })
            return tf;
        }
        //        function save(action) {
        //            var rt;
        //
        //            var biw_h_pkid = new Array();
        //            var part_no = new Array();
        //            var down_nums = new Array();
        //            var save_num = new Array();
        //            var up_nums = new Array();
        //            var remarks = new Array();
        //            var trList = $("#tbody").children("tr");
        //            for (var i = 0; i < trList.length; i++) {
        //                var tdArr = trList.eq(i).find("td");
        //                biw_h_pkid[i] = tdArr.eq(1).find("input").val();//庫存預警PKID
        //                part_no[i] = tdArr.eq(2).find("input").val();//料号
        //                down_nums[i] = tdArr.eq(4).find("input").val();//库存下限
        //                save_num[i] = tdArr.eq(5).find("input").val();//安全库存
        //                up_nums[i] = tdArr.eq(7).find("input").val();//库存上限
        //                remarks[i] = tdArr.eq(8).find("input").val();//备注
        //            }
        //            $.ajax({
        //                type: 'POST',
        //                url: "<?//= Url::to(["save"])?>//",
        //                async: false,
        //                data: {
        //                    action: action,
        //                    biw_h_pkid: biw_h_pkid,
        //                    part_no: part_no,
        //                    down_nums: down_nums,
        //                    save_num: save_num,
        //                    up_nums: up_nums,
        //                    remarks: remarks
        //                },
        //                success: function (data) {
        //                    if (data == 1) {
        //                        rt = true;
        //                    } else {
        //                        rt = false;
        //                    }
        //                },
        //                error: function (xhr, type) {
        //                    rt = false;
        //                }
        //            });
        //            return rt;
        //
        //        }

        function getTypeByWhID(wh_id) {
            var tf = false
            $.ajax({
                type: 'POST',
                url: "<?= Url::to(["gettypebywhid"])?>",
                async: false,
                data: {
                    wh_id: wh_id
                },
                success: function (data) {
                    tf = data;
                },
                error: function (xhr, type) {
                    alert("出现异常!");
                }
            });
            return tf;
        }

        function check() {
            var biw_h_pkid = new Array();
            var part_no = new Array();
            var down_nums = new Array();
            var save_num = new Array();
            var up_nums = new Array();
            var remarks = new Array();
            var trList = $("#tbody").children("tr");
            for (var i = 0; i < trList.length; i++) {
                var j=i+1;
                var tdArr = trList.eq(i).find("td");
                biw_h_pkid[i] = tdArr.eq(1).find("input").val();//庫存預警PKID
                part_no[i] = tdArr.eq(3).find("input").val();//料号
                down_nums[i] = tdArr.eq(5).find("input").val();//库存下限
                save_num[i] = tdArr.eq(6).find("input").val();//安全库存
                up_nums[i] = tdArr.eq(8).find("input").val();//库存上限
                remarks[i] = tdArr.eq(9).find("input").val();//备注
                if (down_nums[i] == "" || down_nums[i] == null) {
                    layer.alert("第" + j + "行库存下限不能为空!", {icon: 2});
                    return false;
                    break;
                }
                if (save_num[i] == "" || save_num[i] == null) {
                    layer.alert("第" + j +"行安全库存不能为空!", {icon: 2});
                    return false;
                    break;
                }
                if (up_nums[i] == "" || up_nums[i] == null) {
                    layer.alert("第" + j + "行库存上限不能为空!", {icon: 2});
                    return false;
                    break;
                }
                if (parseFloat(down_nums[i]) > parseFloat(save_num[i])) {
                    layer.alert("第" + j + "行的安全库存必须大于库存下限!", {icon: 2});
                    return false;
                    break;
                }
                if (parseFloat(save_num[i]) > parseFloat(up_nums[i])) {

                    layer.alert("第" + j + "行的库存上限必须大于安全库存!", {icon: 2});
                    return false;
                    break;
                }
            }
            return true;
        }
    </script>

