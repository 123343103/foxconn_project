<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/14
 * Time: 下午 04:07
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<style>
    .width-130 {
        width: 130px;
    }
    .width-160 {
        width: 160px;
    }
    .width-80 {
        width: 80px;
    }

    .width-180 {
        width: 180px;
    }
</style>
<?php $form = ActiveForm::begin(['method' => "get", "action" => "index"]); ?>
<div class="mb-10" style="margin-left: 20px">
    <div class="inline-block">
        <label class="label-align label-width">料号：</label>
        <input type="text" class="width-180" id="partno" value="<?= $param['part_no'] ?>"
               name="part_no"/>
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width">采购数量：</label>
        <input type="text" id="qty" class="width-80" value=""/>
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width">起运地：</label>
        <select class="width-160" id="shipinfo" title="">
        </select>
<!--        <input type="text" class="width-130" value=""-->
<!--               name="startaddress"/>-->
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width">配送地：</label>
        <input type="text" id="endaddress" class="width-130" value="" readonly/>
        <input type="hidden" name="ToProvince" id="ToProvince" value="">
        <input type="hidden" name="ToCity" id="ToCity" value="">
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <button id="select" type="button" class="search-btn-blue" style="margin-left:5px;">试算</button>
        <button id="reset_btn" type="button" class="reset-btn-yellow" style="margin-left:5px;">重置</button>
    </div>
</div>
<div id="ProvinceFrame" class="panl"
     style="display:none; overflow:visible;  width:450px; border: solid 3px #ddd; background-color: #FFFFFF; position:absolute;top: 180px;margin-left:650px">
    <div class="oms-panel-closemark"></div>
    <div class="ProvinceInfo">
        <?php foreach ($district as $key => $val) { ?>
            <span style="width: 80px;height: 20px;" class="oms-district-province" id="endp"
                  data-proname="<?= $val['district_name'] ?>"
                  data-id="<?= $val['district_id'] ?>"><a id="endp"><?= $val['district_name'] ?></a></span>
        <?php } ?>
    </div>
</div>
<div id="CityFrame" class="panl"
     style="display:none; position:absolute; border: solid 3px #ddd;width:220px;background-color:#FFFFFF;">
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $("#endaddress").on('click', function () {
            $("#ProvinceFrame").css('display', 'block');
        });
        $(".oms-district-province").on('click', function () {
            $("#CityFrame").children('.oms-district-province-city').remove();//先清除所有的市节点，再重新获取新的节点
            $("#CityFrame").css('display', 'block');
            proid = $(this).data('id');//获取起始地省id
            proname = $(this).data('proname');//获取起始地省名称
            var vLeft = $(this).offset().left;
            if (vLeft <= 0) vLeft = 0;

            var vTop = 0;
            if (($(this).offset().top + $("#CityFrame").height()) > ($(window).height() + $("html")[0].scrollTop)) {
                vTop = $(this).offset().top - $("#CityFrame").height();
                if (vTop < 0) {
                    vTop = $(this).offset().top - $("#CityFrame").height() / 2;
                }
            }
            else {
                vTop = $(this).offset().top;
            }

            $("#CityFrame").css({
                top: vTop,
                left: vLeft
            }).show();
            //alert(pid);
            $.ajax({
                url: "<?=Url::to(['child'])?>",
                data: {"id": proid},
                dataYype: "json",
                type: "get",
                async: true,
                success: function (data) {
                    var da = jQuery.parseJSON(data);//返回的数组转换成Json数据
                    //alert(da.length);
                    for (var i = 0; i < da.length; i++) {
                        $("#CityFrame").append("<span id='endc' class='oms-district-province-city' data-name=" + da[i]['district_name'] + " data-pid=" + da[i]['district_id'] + "" +
                            " style='width: 70px; height: 20px;'><a id='endc'>" + da[i]['district_name'] + "</a></span>");
                    }
                }
            })
        });
        $("#CityFrame").delegate(".oms-district-province-city", "click", function () {
            var pname = $(this).data('name');
            var pid = $(this).data('pid');//获取市ID
            $("#endaddress").val(proname + pname);//省市名称
            $("#ToProvince").val(proid);//省id
            $("#ToCity").val(pid);//市id
            $("#ProvinceFrame").css('display', 'none');
            $("#CityFrame").css('display', 'none');
            $("#CityFrame").children('.oms-district-province-city').remove();

        });
        //控制地址面板的隐藏与显示
        $(document).click(function (e) {
            var v_id = $(e.target).attr('id');
            if(v_id!="endaddress" && v_id!="endc" &&  v_id!="endp")
            {
                $("#ProvinceFrame").css('display', 'none');//显示起始地地址panl
                $("#CityFrame").css('display', 'none');
            }
            if(v_id!="endp")
            {
                $("#CityFrame").css('display', 'none');
            }
        });
        $("#reset_btn").click(function () {
            $("#partno").val("");
            $("#qty").val("");
            $("#shipinfo").val("");
            $("#endaddress").val("");
        });
    });
</script>
