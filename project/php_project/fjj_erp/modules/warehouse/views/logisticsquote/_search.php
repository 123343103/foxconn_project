<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/7/19
 * Time: 上午 08:22
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\widgets\upload\UploadAsset::register($this);
\app\assets\JeDateAsset::register($this);
?>
<style>
    .panl {
        border: solid 1px #ddd;
        border-radius: 3px;
        overflow: hidden;
        z-index: 10000;
    }

    .label-width {
        width: 50px;
    }

    .label-widths {
        width: 80px;
    }

    .red-border {
        border: 1px solid #ffa8a8;
    }

    .tishi {
        z-index: 99;
        width: 150px;
        height: 25px;
        border: 1px solid #d2abab;
        border-radius: 5px;
        background-color: #FFFFCC;
        line-height: 25px;
        display: block;
        position: absolute;
        left: 460px;
        top:107px;
    }
    .receiptInfo{
        display: none;
    }
    /*#dialog {*/
        /*position:fixed;*/
        /*left:40%;*/
        /*top:100px;*/
        /*background-color:#ccc;*/
        /*width:200px;*/
        /*height:120px;*/
        /*display:none;*/
    /*}*/
</style>
<?php $form = ActiveForm::begin(["id" => "add-form"]); ?>
<!--<input type="hidden" id="msg" value="--><? //= $msg ?><!--">-->
<div class=" mb-10">
    <div class="inline-block">
        <label class="label-align label-width" for="Frcity">起始地：</label>
        <!--        <input type="button" value="请选择" id="btnstart"/>-->
        <input id="start" class="easyui-validatebox" data-options="required:'true'" type="text" name="startaddress"
               value="<?= $param['startaddress'] ?>"
               style="width: 100px;background-color: #ffffff;"  readonly/>
        <span id="startInfo" class="receiptInfo">起始地不能为空！</span>
        <input id="startproviceid" type="hidden" name="startproviceid"/>
        <input id="startcityid" type="hidden" name="startcityid"/>
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-width " for="Tocity">目的地：</label>
        <!--        <input type="button" value="请选择" id="btnend"/>-->
        <input id="end" class="easyui-validatebox" data-options="required:'true'" type="text"
               value="<?= $param['endddress'] ?>" name="endddress"
               style="width: 100px;background-color: #ffffff;" readonly/>
        <span id="endinfo" class="receiptInfo">目的地不能为空！</span>
        <input id="endproviceid" type="hidden" name="endproviceid"/>
        <input id="endcityid" type="hidden" name="endcityid"/>
        <div class="help-block"></div>
    </div>
    <div class="inline-block">
        <label class="label-align label-widths" for="soh_code">报价单号：</label>
        <input type="text" id="salesquotationno" value="<?= $param['salesquotationno'] ?>" class="width-130"
               name="salesquotationno"/>
        <div class="help-block"></div>
    </div>
    <div class="inline-block ">
        <label class="label-align label-widths" for="TRANSTYPE">运输类型：</label>
        <select id="transtype" name="transtype" class=""
                style="width: 100px;">
            <?php foreach ($trans as $key => $val) { ?>
                <option
                    value="<?= $val['tran_code'] ?>" <?= $param['transtype'] == $val['tran_code'] ? "selected" : null; ?>><?= $val['tran_sname'] ?></option>
            <?php } ?>
        </select>
        <div class="help-block"></div>
    </div>
    <div class="inline-block" style="margin-left: 10px">
        <button id="select" type="button" class="search-btn-blue" style="margin-left:5px;">查询</button>
        <button id="reset_btn" type="submit" class="reset-btn-yellow" style="margin-left:5px;">重置</button>
    </div>
</div>
<!--<div class="mb-10">-->
<!--</div>-->
<div id="dialog">
    <div id="ProvinceFrame" class="panl"
         style="display:none; overflow:visible;  width:450px; border: solid 3px #ddd; background-color: #FFFFFF; position:absolute;top: 140px;margin-left:100px">
        <div class="oms-panel-closemark"></div>
        <div class="ProvinceInfo">
            <?php foreach ($district as $key => $val) { ?>
                <span id="startp" style="width: 80px;height: 20px;" class="oms-district-province"
                      data-proname="<?= $val['district_name'] ?>"
                      data-id="<?= $val['district_id'] ?>"><a id="startp"><?= $val['district_name'] ?></a></span>
            <?php } ?>
        </div>
    </div>
    <div id="CityFrame" class="panl"
         style="display:none; position:absolute; border: solid 3px #ddd;width:220px;background-color:#FFFFFF;">
    </div>
</div>


<div id="ProvinceFrameend" class="panl"
     style="display:none; overflow:visible;  width:450px; border: solid 3px #ddd; background-color: #FFFFFF; position:absolute;top: 140px;margin-left:260px">
    <div class="oms-panel-closemark-end"></div>
    <div class="ProvinceInfoend">
        <?php foreach ($district as $key => $val) { ?>
            <span id="endp" style="width: 80px;height: 20px;" class="oms-district-province-end"
                  data-pro="<?= $val['district_name'] ?>"
                  data-id="<?= $val['district_id'] ?>"><a id="endp"><?= $val['district_name'] ?></a></span>
        <?php } ?>
    </div>
</div>
<div id="CityFrameend" class="panl"
     style="display:none; position:absolute; border: solid 3px #ddd;width:220px;background-color:#FFFFFF;">
</div>
<?php ActiveForm::end(); ?>
<script>
    $(function () {
        $("#start").on('mouseover', function () {
            if ($(this).val() == null || $(this).val() == "") {
                $(this).myHoverTip("startInfo");
            }
            else {
                $("#startInfo").remove();
            }
        });
        $("#end").on('mouseover', function () {
            if ($(this).val() == null || $(this).val() == "") {
                $(this).myHoverTip("endinfo");
            }
            else {
                $("#endinfo").remove();
            }
        });
        var proname = "";
        var proid = "";
        var pronameend = "";
        var proidend = "";
        //-----------strat起始地---------------
        var n = 0;
        $("#start").on('click', function () {
           // openDialog(event);
            //n++;
           // if (n % 2 != 0) {
                $("#ProvinceFrame").css('display', 'block');
                $("#ProvinceFrameend").css('display', 'none');//隐藏目的地地址panl
            //}
            //else {
               // $("#ProvinceFrameend").css('display', 'none');//隐藏目的地地址panl
               // $("#ProvinceFrame").css('display', 'none');//显示起始地地址panl
               // $("#CityFrameend").css('display', 'none');
                //$("#CityFrame").css('display', 'none');
                //  $("#ProvinceFrame").css('display', 'block');
            //}
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
                        $("#CityFrame").append("<span id='startc' class='oms-district-province-city' data-name=" + da[i]['district_name'] + " data-pid=" + da[i]['district_id'] + "" +
                            " style='width: 70px; height: 20px;'><a id='startc'>" + da[i]['district_name'] + "</a></span>");
                    }
                }
            })
        });
        $("#CityFrame").delegate(".oms-district-province-city", "click", function () {
            $("#start").removeClass('red-border');
            var pname = $(this).data('name');
            var pid = $(this).data('pid');//获取市ID
            $("#start").val(proname + pname);//省市名称
            $("#startproviceid").val(proid);//省id
            $("#startcityid").val(pid);//市id
            $("#ProvinceFrame").css('display', 'none');
            $("#CityFrame").css('display', 'none');
            $("#CityFrame").children('.oms-district-province-city').remove();

        });
        //---------end起始地-----------------
//        document.onclick=function () {
//            $("#ProvinceFrameend").css('display', 'none');//隐藏目的地地址panl
//            $("#ProvinceFrame").css('display', 'none');//显示起始地地址panl
//            $("#CityFrameend").css('display', 'none');
//            $("#CityFrame").css('display', 'none');
//        };
        //==============start 用于影藏或显示地址信息=============
        $(document).click(function (e) {
            var v_id = $(e.target).attr('id');
            console.log(v_id);
            if(v_id!="start"  && v_id!="startc" && v_id!="end" && v_id!="endc" && v_id!="startp" && v_id!="endp")
            {
                $("#ProvinceFrameend").css('display', 'none');//隐藏目的地地址panl
                $("#ProvinceFrame").css('display', 'none');//显示起始地地址panl
                $("#CityFrameend").css('display', 'none');
                $("#CityFrame").css('display', 'none');
            }
            if(v_id!="startp" && v_id!="endp")
            {
                $("#CityFrameend").css('display', 'none');
                $("#CityFrame").css('display', 'none');
            }
        });
        //==============end 用于影藏或显示地址信息==============
        //---------strat目的地--------------
        var m = 0;
        $("#end").click(function () {
           // m++;
            //判断是否是奇数次单击
           // if (m % 2 != 0) {
                $("#ProvinceFrame").css('display', 'none');//隐藏起始地地址panl
                $("#ProvinceFrameend").css('display', 'block');//显示目的地地址panl
           // }
//            else {
//                $("#ProvinceFrameend").css('display', 'none');//隐藏目的地地址panl
//                $("#ProvinceFrame").css('display', 'none');//显示起始地地址panl
//                $("#CityFrameend").css('display', 'none');
//                $("#CityFrame").css('display', 'none');
//                //$("#ProvinceFrameend").css('display', 'block');//显示目的地地址panl
//            }
        });
        $(".oms-district-province-end").on('click', function () {
            $("#CityFrameend").children('.oms-district-province-city-end').remove();//先清除所有的市节点，再重新获取新的节点
            $("#CityFrameend").css('display', 'block');
            proidend = $(this).data('id');//获取目的地省级id
            pronameend = $(this).data('pro');//获取目的地省级名称
            var vLeft = $(this).offset().left;
            if (vLeft <= 0) vLeft = 0;

            var vTop = 0;
            if (($(this).offset().top + $("#CityFrameend").height()) > ($(window).height() + $("html")[0].scrollTop)) {
                vTop = $(this).offset().top - $("#CityFrameend").height();
                if (vTop < 0) {
                    vTop = $(this).offset().top - $("#CityFrameend").height() / 2;
                }
            }
            else {
                vTop = $(this).offset().top;
            }
            $("#CityFrameend").css({
                top: vTop,
                left: vLeft
            }).show();
            //alert(pid);
            $.ajax({
                url: "<?=Url::to(['child'])?>",
                data: {"id": proidend},
                dataYype: "json",
                type: "get",
                async: true,
                success: function (data) {
                    var da = jQuery.parseJSON(data);//返回的数组转换成Json数据
                    //alert(da.length);
                    for (var i = 0; i < da.length; i++) {
                        $("#CityFrameend").append("<span  id='endc' class='oms-district-province-city-end' data-name=" + da[i]['district_name'] + " data-pid=" + da[i]['district_id'] + "" +
                            " style='width: 70px; height: 20px;'><a id='endc'>" + da[i]['district_name'] + "</a></span>");
                    }
                }
            })
        });
        //点击市名称将其填写到目的地标签后
        $("#CityFrameend").delegate(".oms-district-province-city-end", "click", function () {
            $("#end").removeClass('red-border');
            var pname = $(this).data('name');//获取市名称
            var pidend = $(this).data('pid');//获取市id
            $("#end").val(pronameend + pname);//省市名称
            $("#endproviceid").val(proidend);//省ID
            $("#endcityid").val(pidend);//市ID
            $("#ProvinceFrameend").css('display', 'none');
            $("#CityFrameend").css('display', 'none');
            $("#CityFrameend").children('.oms-district-province-city-end').remove();
        });
        //--------end目的地---------------
        $.fn.myHoverTip = function (divId) {
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
                    div.css("width", '100px');
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


    });
</script>