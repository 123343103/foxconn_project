<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/12/16
 * Time: 下午 03:03
 */
use yii\widgets\ActiveForm;

?>
<h3 class="head-first">生成拣货单</h3>
<style>
    .width-40{
        width: 40px;
    }
    .width-140{
        width: 140px;
    }
    .width-80{
        width: 80px;
    }
    .mt-20{
        margin-top: 20px;
    }
</style>
<div>
    <?php ActiveForm::begin([
        "id" => "create-pick"
    ]) ?>
    <div class="content">
        <input type="hidden" value="<?=$param['id']?>" name="id">
        <div class="mb-10">
<!--            <table width="90%" class="no-border vertical-center ml-25">-->
<!--                <tr class="no-border">-->
<!--                    <input type="hidden" name="BsPck[wh_id]" value="--><?//=$model["wh_id"]?><!--"/>-->
<!--                    <input type="hidden" name="BsPck[urg]" value="--><?//=$model["urg"]?><!--"/>-->
<!--                    <td width="13%" class="no-border qlabel-align vertical-center">出仓仓库：</td>-->
<!--                    <td width="35%" class="no-border vertical-center">--><?//= $model["wh_name"] ?><!--</td>-->
<!--                    <td width="4%" class="no-border vertical-center"></td>-->
<!--                    <td width="13%" class="no-border qlabel-align vertical-center">仓库代码：</td>-->
<!--                    <td width="35%"-->
<!--                        class="no-border vertical-center">--><?//= $model["wh_code"] ?><!--</td>-->
<!--                </tr>-->
<!--            </table>-->
<!--            <table width="90%" class="no-border vertical-center ml-25">-->
<!--                <tr class="no-border">-->
<!--                    <td width="13%" class="no-border qlabel-align vertical-center">仓库属性：</td>-->
<!--                    <td width="35%" class="no-border vertical-center">--><?//= $model["bsp_svalue"] ?><!--</td>-->
<!--                    <td width="4%" class="no-border vertical-center"></td>-->
<!--                    <td width="13%" class="no-border qlabel-align vertical-center">操作员：</td>-->
<!--                    <td width="35%"-->
<!--
<!--                </tr>-->
<!--            </table>-->
            <table width="90%" class="no-border vertical-center ml-25">
                <tr class="no-border">
                    <td width="13%" class="no-border  vertical-center">操作员：</td>
                    <td width="35%" class="no-border vertical-center"><?=yii::$app->user->identity->staff->staff_name?></td>
                    <td width="4%" class="no-border vertical-center"></td>
                    <td width="13%" class="no-border qlabel-align vertical-center">操作日期：</td>
                    <td width="35%"
                        class="no-border vertical-center"><?php echo $showtime=date("Y/m/d"); ?></td>
                </tr>
            </table>
        </div>
        <div  class="mt-20">
            商品信息
        </div>
        <div class="mb-20" style="overflow: auto;margin-top: 10px">
            <table class="table" >
                <thead>
                <tr style="height: 50px">
                    <th class="width-40"><p>序号</p></th>
                    <th class="width-140"><p>料号</p></th>
                    <th class="width-140"><p>品名</p></th>
                    <th class="width-80"><p>需求出货数量</p></th>
                    <th class="width-80"><p>交易单位</p></th>
                    <th class="width-80"><p><span class="red">*</span>出仓仓库</p></th>
                    <th class="width-40"><p><span class="red">*</span> 拣货数量</p></th>
                    <th class="width-80"><p>备注</p></th>
                </tr>
                </thead>
                <tbody id="product_table">
                <?php foreach ($productinfo as $key => $val) { ?>
                    <tr style="height: 50px;">
                        <td><input type="hidden" value="<?=$val["sol_id"] ?>" name="BsPckDt[<?= $key ?>][sol_id]"/><input type="hidden" value="<?=$val["shpn_pkid"] ?>" name="BsPckDt[<?= $key ?>][shpn_pkid]"/><input type="hidden" value="<?=$val["nums"] ?>" name="BsPckDt[<?= $key ?>][nums]"/><input type="hidden" value="<?=$val["part_no"] ?>" name="BsPckDt[<?= $key ?>][part_no]"/><p class="width-40"><?= ($key + 1) ?></p></td>
                        <td><p class="width-140" style="width: auto"><?=$val["part_no"] ?></p></td>
                        <td  style="overflow: hidden"><p class="width-140"><?= $val["pdt_name"] ?></p></td>
                        <td><input type="hidden" value="<?=$val["nums"] ?>" name="BsPckDt[<?= $key ?>][req_num]"/><p class="width-80" id="nums"><?= $val["nums"] ?></p></td>
                        <td><p class="width-80"><?= $val["unit"] ?></p></td>
                        <?php if($val["wh_id"]!=""||$val["wh_id"]!=null){?>
                            <td><input disabled="disabled" value="<?=$val["wh_name"] ?>"/><input hidden="hidden" value="<?=$val["wh_id"] ?>" name="BsPckDt[<?= $key ?>][wh_id]"/></td>
                        <?php } else{ ?>
                            <td>
                                <select class="easyui-validatebox width-80  wh_id"
                                        data-options="required:'true'"
                                        name="BsPckDt[<?= $key ?>][wh_id]" data-options="validType:'number',required:'true'">
                                    <option value="">--请选择--</option>
                                </select>
                            </td>
                        <?php } ?>
                        <td><input type="text" name="BsPckDt[<?= $key ?>][pck_nums]" value="<?= $val["nums"] ?>" id="pck_nums" data-options="validType:'two_decimal',required:'true'" class="easyui-validatebox"/></td>
                        <td><input type="text" name="BsPckDt[<?= $key ?>][marks]"  class="easyui-validatebox" maxlength="2000"/></p></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="mt-20 mb-20 text-center">
            <button id="submit" class="button-blue-big" type="submit">确定</button>
            <button id="cancel" class="button-white-big" onclick="parent.$.fancybox.close()">取消</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script >
    $(function () {
        ajaxSubmitForm($("#create-pick"));
        var tb = document.getElementById("product_table");
        var url = "<?=\yii\helpers\Url::to(['select-whid'])?>";
        var staff_id="<?= yii::$app->user->identity->staff->staff_id ?>";
        for (var i = 0; i < tb.rows.length; i++) {
            var partno = $(tb).children("tr").eq(i).children("td").eq(1).children("p").text();
            var tt = $(tb).children("tr").eq(i).children("td").eq(5).children(".wh_id");
            $.ajax({
                type: "get",
                dataType: "json",
                async: false,
                data: {"staff_id": staff_id, "partno": partno},
                url: url,
                success: function (data) {
                    for (var i = 0; i < data.length; i++) {
                        tt.append("<option value='" + data[i].wh_id + "'>" + data[i].wh_name + "</option>");
                    }
                }
            })
        }
//        $("#pck_nums").blur(function () {
//            var pck_nums=$(this).val();
//            var nums=$("#nums").html();
//            if(pck_nums>nums){
//                layer.alert("拣货数量不能大于需求出货数量!", {icon: 2});
//                return false;
//            }
//        })
//        $("#submit").on("click",function () {
//            var pck_nums= $("#pck_nums").val();
//            var nums=$("#nums").html();
//            if(pck_nums>nums){
//                layer.alert("拣货数量不能大于需求出货数量!", {icon: 2});
//                return false;
//            }
//        })
    })
</script>
