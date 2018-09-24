<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
\app\assets\JeDateAsset::register($this);

$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品列表'];
$this->params['breadcrumbs'][] = ['label' => '查看料号信息（销售中）'];
$this->params['breadcrumbs'][] = ['label' => '上架商品编辑审核详情'];
$this->title = '上架商品编辑审核详情';

?>
<style>
    .head-second{background-color: #ffffff}
    #tab_bar {  width: 950px;  height: 20px;  float: left;  }
    #tab_bar ul {  padding: 0px;  margin: 0px;  height: 23px;  text-align: center;  }
    #tab_bar li {  list-style-type: none;  float: left;  width: 85px;  height: 23px;  background-color: #1f7ed0;  margin-right: 5px;  line-height: 23px;  cursor: pointer;  color: #ffffff;  }
    .tab_css {  width: 990px; /*height: 200px;*/ /*background-color: darkgray;*/  height: auto;  display: none;  float: left;  margin-top: 20px;  }
    ._house{height: 370px;width:950px;margin:0 auto;overflow: auto}
    ._addrows{height: 30px;width: 100px;border: 1px solid #00b3ee}
    ._li{float: left;margin-right: 20px;width: 70px;height: 20px;text-align: center}
    #fullbg {  background-color:#333333;  left: 0;  opacity: 0.3;  position: absolute;  top: 0;  z-index: 3;  filter: alpha(opacity=50);  -moz-opacity: 0.5;  -khtml-opacity: 0.5;  }
    #fullbg2 {  background-color:#333333;  left: 0;  opacity: 0.3;  position: absolute;  top: 0;  z-index: 3;  filter: alpha(opacity=50);  -moz-opacity: 0.5;  -khtml-opacity: 0.5;  }
    #dialog {  background-color: #fff; /*border: 5px solid rgba(0,0,0, 0.4);*/  height: 140px;  left: 50%;  margin: -200px 0 0 -200px;  padding: 1px;  position: fixed !important; /* 浮动对话框 */  position: absolute;  top: 50%;  width: 476px;  z-index: 5; /*border-radius: 5px;*/  display: none;  }
    #dialog2 {  background-color: #fff;  border: 2px solid rgba(0,0,0, 0.4);  height: 250px;  left: 60%;  margin: -200px 0 0 -200px;  padding: 1px;  position: fixed !important; /* 浮动对话框 */  position: absolute;  top: 60%;  width: 400px;  z-index: 10; /*border-radius: 5px;*/  display: none;  }
    #dialog3 {  background-color: #fff;  border: 2px solid rgba(0,0,0, 0.4);  height: auto;  left: 65%;  margin: -200px 0 0 -200px;  padding: 1px;  position: fixed !important; /* 浮动对话框 */  position: absolute;  top: 70%;  width: 300px;  z-index: 15; /*border-radius: 5px;*/  display: none;  }
    #dialog p {  margin: 0 0 12px;  height: 24px;  line-height: 24px;  background: #0099FF;  }
    #dialog2 p {  margin: 0 0 12px;  height: 24px;  line-height: 24px;  background: #fff;  }
    #dialog3 p {  margin: 0 0 12px;  height: 24px;  line-height: 24px;  background: #fff;  }
    #dialog p.close {  text-align: right;  padding-right: 10px;  }
    #dialog2 p.close {  text-align: right;  padding-right: 10px;  }
    #dialog3 p.close {  text-align: right;  padding-right: 10px;  }
    #dialog p.close a {  color: #fff;  text-decoration: none;  }
    #dialog2 p.close a {    text-decoration: none;  }
    #dialog3 p.close a {    text-decoration: none;  }
    ._ueditor{margin-left: 70px;}
    .country-picker label:after{
        content:""
    }



    #tab4_content table{
        width: 100%;
    }
    #tab4_content label input{
        vertical-align: middle;
    }
    #tab4_content label:after{
        content: "";
    }
    #tab4_content tr{
        height: 50px;
        line-height: 50px;
    }

    #tab4_content table,#tab4_content tr,#tab4_content td{
        border: none;
    }

    td.editable input{
        text-align:center;
        border: none;
        outline: none;
        width: 100%;
        height: 30px;
    }
    td.editable input:read-only{
        background: transparent;
    }
    td.editable .validatebox-invalid{
        border:#ffa8a8 1px solid !important;
    }

    .label-width{
        width: 120px;
    }
    .value-width{
        width: 160px;
    }
    .hide{
        display: none;
    }
</style>
<div class="content">
    <h1 class="head-first">上架商品编辑审核详情</h1>
    <div class="border-bottom mb-20 pb-10">
        <?php if($data["partno"]["check_status"]==2){ ?>
        <button type="button" id="edit" class="button-blue width-80">修改</button>
        <button type="button" id="check" class="button-blue width-80">送审</button>
        <button type="button" id="cancel" class="button-blue width-80">取消</button>
        <?php } ?>
        <button type="button" class="button-blue width-80" onclick="window.location.href='<?=Url::to(['index'])?>'">切换列表</button>
    </div>

<?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <div>
        <table width="90%" class="no-border vertical-center mb-10">
            <tbody>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="11%">商品類別：</td>
                <td class="no-border vertical-center" width="88%"><?=$data["pdt"]["cat_three_level"];?></td>
            </tr>
            </tbody>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tbody>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="11%">品名：</td>
                <td class="no-border vertical-center" width="22%"><?=empty($data["pdt"]["pdt_name"])?"/":$data["pdt"]["pdt_name"]?></td>
                <td class="no-border vertical-center label-align" width="11%">品牌：</td>
                <td class="no-border vertical-center" width="55%"><?=empty($options["brands"][$data["pdt"]["brand_id"]])?$options["brands"][$data["pdt"]["brand_id"]]:"/"?></td>
            </tr>
            </tbody>
        </table>

        <table width="90%" class="no-border vertical-center mb-10">
            <tbody>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="11%">商品标题：</td>
                <td class="no-border vertical-center" width="22%"><?=empty($data["pdt"]['pdt_title'])?"/":$data["pdt"]['pdt_title']?></td>
                <td class="no-border vertical-center label-align" width="11%">商品关键字：</td>
                <td class="no-border vertical-center" width="55%"><?=empty($data["pdt"]['pdt_keyword'])?"/":$data["pdt"]['pdt_keyword']?></td>
            </tr>
            </tbody>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tbody>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="11%">商品标签：</td>
                <td class="no-border vertical-center" width="22%"><?=empty($data["pdt"]["pdt_label"])?"/":$data["pdt"]["pdt_label"]?></td>
                <td class="no-border vertical-center label-align" width="11%">商品属性：</td>
                <td class="no-border vertical-center" width="55%"><?=empty($options["pdt_attribute"][$data["pdt"]["pdt_attribute"]])?"/":$options["pdt_attribute"][$data["pdt"]["pdt_attribute"]]?></td>
            </tr>
            </tbody>
        </table>


        <table width="90%" class="no-border vertical-center mb-10">
            <tbody>
            <tr class="no-border mb-10">
                <td class="no-border vertical-center label-align" width="11%">商品形态：</td>
                <td class="no-border vertical-center" width="22%"><?=empty($options["pdt_form"][$data["pdt"]["pdt_form"]])?"/":$options["pdt_form"][$data["pdt"]["pdt_form"]]?></td>
                <td class="no-border vertical-center label-align" width="11%">计量单位：</td>
                <td class="no-border vertical-center" width="55%"><?=empty($options["pdt_unit"][$data["pdt"]["unit"]])?"/":$options["pdt_unit"][$data["pdt"]["unit"]]?></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div id="partno-info"></div>

    <div class="space-10"></div>

    <h1 class="head-first">审核记录</h1>
    <div class="content">
        <div id="record" style="width: 100%;"></div>
    </div>

        <script>
            $.fn.district_picker=function(o){
                var methods={
                    init:function(){
                        function regenerate(){
                            $("body").find(".district-layer").remove();
                            $("body").find(".country-picker").remove();
                            var district_layer=$("<div class='district-layer'></div>").css({
                                "position":"fixed",
                                "left":"0px",
                                "right":"0px",
                                "top":"0px",
                                "bottom":"0px",
                                "background":"#000",
                                "opacity":"0.5",
                                "z-index":100
                            }).hide();

                            var country_picker_title=$("<div class='title'></div>").css({
                                "position":"absolute",
                                "width":"380px",
                                "height":"30px",
                                "line-height":"30px",
                                "text-indent":"10px",
                                "background":"#1f7ed0",
                                "color":"#fff",
                                "text-overflow":"ellipsis",
                                "overflow":"hidden",
                            }).text("国家选择");

                            var country_picker_close=$("<span class='close'></span>").css({
                                "position":"absolute",
                                "width":"20px",
                                "display":"block",
                                "text-align":"center",
                                "height":"30px",
                                "right":"0px",
                                "line-height":"30px",
                                "background":"#1f7ed0",
                                "color":"#fff",
                                "cursor":"pointer"
                            }).html("&times").click(function(){
                                $(this).parents(".country-picker").hide();
                                $(".district-layer").hide();
                            });



                            var country_picker_search=$("<div class='search'></div>").css({
                                "position":"absolute",
                                "top":"40px",
                                "width":"400px",
                                "height":"30px"
                            }).append("<label style='width:160px;'>国别（或国别拼音首字母）：</label><input type='text' />");

                            var country_list=$("<ul class='country-list'></ul>").css({
                                "position":"absolute",
                                "left":"0px",
                                "right":"0px",
                                "top":"70px",
                                "bottom":"0px",
                                "display":"block",
                                "line-height":"30px",
                            });

                            var country_picker=$("<div class='country-picker'></div>").css({
                                "position":"fixed",
                                "width":"400px",
                                "height":"300px",
                                "left":"50%",
                                "margin-left":"-200px",
                                "top":"50%",
                                "margin-top":"-150px",
                                "background":"#fff",
                                "z-index":101,
                                "border":"#1f7ed0 1px solid"
                            }).append(country_picker_title).append(country_picker_close).append(country_picker_search).append(country_list).hide();



                            var province_picker_title=$("<div class='title'></div>").css({
                                "position":"absolute",
                                "width":"380px",
                                "height":"30px",
                                "line-height":"30px",
                                "text-indent":"10px",
                                "background":"#1f7ed0",
                                "text-overflow":"ellipsis",
                                "overflow":"hidden",
                                "color":"#fff"
                            }).text("省份选择");

                            var province_picker_close=$("<span class='close'></span>").css({
                                "position":"absolute",
                                "width":"20px",
                                "display":"block",
                                "text-align":"center",
                                "height":"30px",
                                "right":"0px",
                                "line-height":"30px",
                                "background":"#1f7ed0",
                                "color":"#fff",
                                "cursor":"pointer"
                            }).html("&times").click(function(){
                                $(this).parents(".province-picker").hide();
                            });

                            var province_list=$("<ul class='province-list'></ul>").css({
                                "position":"absolute",
                                "left":"0px",
                                "right":"0px",
                                "top":"0px",
                                "bottom":"0px",
                                "display":"block",
                                "line-height":"30px",
                            });

                            var province_cancel=$("<button class='button-white' style='margin:0px 5px;'>取消</button>").click(function(){
                                $(this).parents(".province-picker").hide();
                            });

                            var province_bottom=$("<div></div>").css({
                                "position":"absolute",
                                "bottom":"0px",
                                "height":"30px",
                                "left":"0px",
                                "right":"0px",
                                "text-align":"center"
                            }).append(province_cancel);

                            var province_picker=$("<div class='province-picker'></div>").css({
                                "position":"absolute",
                                "width":"400px",
                                "height":"300px",
                                "left":"50px",
                                "top":"50px",
                                "background":"#fff",
                                "border":"#1f7ed0 1px solid",
                                "z-index":102
                            }).append(province_list).append(province_bottom).hide();




                            var city_picker_title=$("<div class='title'></div>").css({
                                "position":"absolute",
                                "width":"380px",
                                "height":"30px",
                                "line-height":"30px",
                                "text-indent":"10px",
                                "background":"#1f7ed0",
                                "color":"#fff",
                                "text-overflow":"ellipsis",
                                "overflow":"hidden"
                            }).text("城市选择");

                            var city_picker_close=$("<span class='close'></span>").css({
                                "position":"absolute",
                                "width":"20px",
                                "display":"block",
                                "text-align":"center",
                                "height":"30px",
                                "right":"0px",
                                "line-height":"30px",
                                "background":"#1f7ed0",
                                "color":"#fff",
                                "cursor":"pointer"
                            }).html("&times").click(function(){
                                $(this).parents(".city-picker").hide();
                            });

                            var city_list=$("<ul class='city-list'></ul>").css({
                                "position":"absolute",
                                "left":"0px",
                                "right":"0px",
                                "top":"0px",
                                "bottom":"0px",
                                "display":"block",
                                "line-height":"30px"
                            });

                            var city_all=$("<button class='button-blue' style='margin:0px 5px;'>全选</button>").click(function(){
                                $(this).toggleClass("all");
                                $(".city-list :checkbox").prop("checked",$(".city-list :checkbox").size()>$(".city-list :checked").size());
                            });
                            var city_ensure=$("<button class='button-blue' style='margin:0px 5px;'>确定</button>").click(function(){
                                $(".district-layer").hide();
                                $(".country-picker").hide();
                                return o(methods.getData());
                            });
                            var city_cancel=$("<button class='button-white' style='margin:0px 5px;'>取消</button>").click(function(){
                                $(this).parents(".city-picker").hide();
                            });

                            var city_bottom=$("<div></div>").css({
                                "position":"absolute",
                                "bottom":"0px",
                                "height":"30px",
                                "left":"0px",
                                "right":"0px",
                                "text-align":"center"
                            }).append(city_all).append(city_ensure).append(city_cancel);

                            var city_picker=$("<div class='city-picker'></div>").css({
                                "position":"absolute",
                                "width":"400px",
                                "height":"300px",
                                "left":"50px",
                                "top":"50px",
                                "background":"#fff",
                                "border":"#1f7ed0 1px solid",
                                "z-index":103
                            }).append(city_list).append(city_bottom).hide();

                            $("body").append(district_layer);
                            $("body").append(country_picker.append(province_picker.append(city_picker)));

                            $(".search input").change(function(){
                                var keywords=$(this).val();
                                $.ajax({
                                    url:'<?=Url::to(['district'])?>?keywords='+keywords+'&type=2',
                                    dataType:'json',
                                    success:function(res){
                                        $(".country-list").empty();
                                        for(var x in res){
                                            var li=$("<li data-id='"+x+"'>"+res[x]+"</li>").css({
                                                "display":"inline-block",
                                                "list-style":"none",
                                                "margin-left":"10px",
                                                "cursor":"pointer"
                                            });
                                            $(".country-list").append(li);
                                        }
                                        $(".district-layer").show();
                                        $(".country-picker").show();
                                    }
                                });
                            });

                            $(".country-list").delegate("li","click",function(){
                                var _this=$(this);
                                $(".province-list").empty();
                                $(this).addClass("active").siblings("li").removeClass("active");
                                $.ajax({
                                    url:'<?=Url::to(['district'])?>?type=2&district_id='+_this.data('id'),
                                    dataType:'json',
                                    success:function(res){
                                        for(var x in res){
                                            var li=$("<li data-id='"+x+"'>"+res[x]+"</li>").css({
                                                "display":"inline-block",
                                                "list-style":"none",
                                                "margin-left":"10px",
                                                "cursor":"pointer"
                                            });
                                            $(".province-list").append(li);
                                        }
                                        $(".province-picker").show();
                                    }
                                });
                            });

                            $(".province-list").delegate("li","click",function(){
                                var _this=$(this);
                                $(".city-list").empty();
                                $(this).addClass("active").siblings("li").removeClass("active");
                                $.ajax({
                                    url:'<?=Url::to(['district'])?>?type=2&district_id='+_this.data('id'),
                                    dataType:'json',
                                    success:function(res){
                                        for(var x in res){
                                            var li=$("<li data-id='"+x+"'><label><input style='vertical-align: middle;margin-right:5px;' type='checkbox'>"+res[x]+"</label></li>").css({
                                                "display":"inline-block",
                                                "list-style":"none",
                                                "margin-left":"10px",
                                                "cursor":"pointer"
                                            });
                                            $(".city-list").append(li);
                                        }
                                        $(".city-picker").show();
                                    }
                                });
                            });

                            $(".city-list").delegate("li","click",function(){
                                $(this).addClass("active").siblings("li").removeClass("active");
                            });

                            $.ajax({
                                url:'<?=Url::to(['district'])?>?district_id=0&type=2',
                                dataType:'json',
                                success:function(res){
                                    for(var x in res){
                                        var li=$("<li data-id='"+x+"'>"+res[x]+"</li>").css({
                                            "display":"inline-block",
                                            "list-style":"none",
                                            "margin-left":"10px",
                                            "cursor":"pointer"
                                        });
                                        $(".country-list").append(li);
                                    }
                                    $(".district-layer").show();
                                    $(".country-picker").show();
                                }
                            });
                        }

                        $(this).click(function(){
                            regenerate();
                        });
                    },
                    getData:function(){
                        var result=[];
                        var country={
                            id:$(".country-list li.active").data("id"),
                            name:$(".country-list li.active").text()
                        };
                        var province={
                            id:$(".province-list li.active").data("id"),
                            name:$(".province-list li.active").text(),
                        };
                        $(".city-list :checked").each(function(){
                            var city={
                                id:$(this).parents("li").data("id"),
                                name:$(this).parents("li").text()
                            };
                            result.push({
                                country:country,
                                province:province,
                                city:city
                            });
                        });
                        return result;
                    }
                };

                if(typeof o=="function"){
                    return methods.init.apply(this,arguments);
                }else{
                    $.error("error");
                }
            }

            $(".ship-add").district_picker(function(res){
                res.forEach(function(row,index){
                    var tr=$("<tr></tr>")
                        .append("<input type='hidden' name='BsShip[country_name][]' value='"+row.country.name+"' >")
                        .append("<input type='hidden' name='BsShip[province_name][]' value='"+row.province.name+"' >")
                        .append("<input type='hidden' name='BsShip[city_name][]' value='"+row.city.name+"' >")
                        .append("<input type='hidden' name='BsShip[country_no][]' value='"+row.country.id+"' >")
                        .append("<input type='hidden' name='BsShip[province_no][]' value='"+row.province.id+"' >")
                        .append("<input type='hidden' name='BsShip[city_no][]' value='"+row.city.id+"' >")
                        .append("<td>"+index+"</td>")
                        .append("<td>"+row.country.name+"</td>")
                        .append("<td>"+row.province.name+"</td>")
                        .append("<td>"+row.city.name+"</td>")
                            .append($("<td><a class='remove'>删除</a></td>"));
                    $(".ship-box").append(tr);
                    $(".ship-box tr").each(function(){
                        $(this).find("td").first().text(parseInt($(this).index())+1)
                    });
                });
                $(".ship-box").next("tfoot").css("display",$(".ship-box tr").size()>0?"none":"table-footer-group");
            });

            $(".ship-box").delegate(".remove","click",function(){
                $(this).parents("tr").remove();
                $(".ship-box tr").each(function(){
                    $(this).find("td").first().text(parseInt($(this).index())+1)
                });
                $(".ship-box").next("tfoot").css("display",$(".ship-box tr").size()>0?"none":"table-footer-group");
            });

            $(".deliv-add").district_picker(function(res){
                res.forEach(function(row,index){
                    var tr=$("<tr></tr>")
                        .append("<input type='hidden' name='BsDeliv[country_name][]' value='"+row.country.name+"' >")
                        .append("<input type='hidden' name='BsDeliv[province_name][]' value='"+row.province.name+"' >")
                        .append("<input type='hidden' name='BsDeliv[city_name][]' value='"+row.city.name+"' >")
                        .append("<input type='hidden' name='BsDeliv[country_no][]' value='"+row.country.id+"' >")
                        .append("<input type='hidden' name='BsDeliv[province_no][]' value='"+row.province.id+"' >")
                        .append("<input type='hidden' name='BsDeliv[city_no][]' value='"+row.city.id+"' >")
                        .append("<td>"+index+"</td>")
                        .append("<td>"+row.country.name+"</td>")
                        .append("<td>"+row.province.name+"</td>")
                        .append("<td>"+row.city.name+"</td>")
                        .append($("<td><a class='remove'>删除</a></td>"));
                    $(".deliv-box").append(tr);
                    $(".deliv-box tr").each(function(){
                        $(this).find("td").first().text(parseInt($(this).index())+1)
                    });
                });
                $(".deliv-box").next("tfoot").css("display",$(".deliv-box tr").size()>0?"none":"table-footer-group");
            });

            $(".deliv-box").delegate(".remove","click",function(){
                $(this).parents("tr").remove();
                $(".deliv-box tr").each(function(){
                    $(this).find("td").first().text(parseInt($(this).index())+1)
                });
                $(".deliv-box").next("tfoot").css("display",$(".deliv-box tr").size()>0?"none":"table-footer-group");
            });

        </script>
    </div>
<?php ActiveForm::end()?>


<div style="display: none">
    <div class="warr-tab-item">
        <div class="mb-10">
            <label class="label-width label-align">出厂年限：</label>
            <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" value="<?=$data['machine']['out_year']?>" readonly>
        </div>
    </div>
    <div class="warr-tab-item">
        <div class="mb-10">
            <label class="label-width label-align">出厂年限：</label>
            <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" value="<?=$data['machine']['out_year']?>" readonly>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">库存：</label>
            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'" name="BsMachine[stock_num]" value="<?=$data['machine']['stock_num']?>" maxlength="11" >
        </div>
        <div class="mb-10">
            <label class="label-width label-align">新旧程度：</label>
            <input type="text" class="value-width value-align" name="BsMachine[recency]" value="<?=$data['machine']['recency']?>"  maxlength="50">
        </div>
    </div>
    <div class="warr-tab-item">
        <div class="mb-10">
            <label class="label-width label-align">出厂年限：</label>
            <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" value="<?=$data['machine']['out_year']?>" readonly>
        </div>
        <div class="mb-10">
            <label class="label-width label-align">租期：</label>
            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[rentals]" value="<?=$data['machine']['rentals']?>" maxlength="11">&nbsp;月
        </div>
        <div class="mb-10">
            <label class="label-width label-align">租金：</label>
            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[rental_unit]" value="<?=$data['machine']['rental_unit']?>" maxlength="11">&nbsp;RMB/月
        </div>
        <div class="mb-10">
            <label class="label-width label-align">押金：</label>
            <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[deposit]" value="<?=$data['machine']['deposit']?>" maxlength="11">&nbsp;RMB
        </div>
    </div>
</div>




<script>

    $(function(){

        $.extend($.fn.validatebox.defaults.rules, {
            minOrder: {
                validator: function(value,param){
                    var qty=parseInt($("#pdt_qty").val());
                    return value && value>=qty && value%qty==0;
                },
                message: '必须大于或等于销售包装内商品数量且为整数倍'
            }
        });


        $.ajax({
            type:"get",
            url:"<?=Url::to(['partno-ajax-info','id'=>\Yii::$app->request->get('id')])?>",
            success:function(data){
                $("#partno-info").html(data);
            }
        });

        $("#edit").click(function(){
            window.location.href="<?=Url::to(['edit-partno','id'=>\Yii::$app->request->get('id')])?>";
        });

        $("#check").click(function(){
            var id ="<?=$data['partno']['l_prt_pkid']?>";
            var url = "<?=Url::to(['index'], true)?>";
            var type = 53;
            $.fancybox({
                href: "<?=Url::to(['reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url,
                type: "iframe",
                padding: 0,
                autoSize: false,
                width: 750,
                height: 480
            });
        });

        $("#record").datagrid({
            url: "<?= \yii\helpers\Url::to(['/system/verify-record/load-record']);?>?id=<?=$verifyId?>",
            rownumbers: true,
            method: "get",
            idField: "vcoc_id",
            loadMsg: false,
//                    pagination: true,
            singleSelect: true,
//                    pageSize: 5,
//                    pageList: [5, 10, 15],
            columns: [[
                {field: "verifyOrg", title: "审核节点", width: 100},
                {field: "verifyName", title: "审核人", width: 100},
                {field: "vcoc_datetime", title: "审核时间", width: 200},
                {field: "verifyStatus", title: "操作", width: 100},
                {field: "vcoc_remark", title: "审核意见", width:200},
                {field: "vcoc_computeip", title: "审核IP", width: 170}

            ]],
            onLoadSuccess: function (data) {
                datagridTip('#record');
                showEmpty($(this), data.total, 0);
            }
        });

        $("#add-form").on("beforeSubmit", function () {

            if($(".ship-box tr").size()==0){
                layer.alert("请完善发货地信息！",{icon:2});
                return false;
            }
            if($("#city_part_yes,#city_part_no").prop("checked") && $(".deliv-box tr").size()==0){
                layer.alert("请完善免运费收货地信息！",{icon:2});
                return false;
            }

            if (!$(this).form('validate')) {
                $("button[type='submit']").prop("disabled", false);
                return false;
            }

            var options = {
                dataType: 'json',
                success: function (data) {
                    if (data.flag == 1) {
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                if (data.url != undefined) {
                                    parent.location.href = data.url;
                                }
                            }
                        });
                    }
                    if (data.flag == 0) {
                        if ((typeof data.msg) == 'object') {
                            layer.alert(JSON.stringify(data.msg), {icon: 2});
                        } else {
                            layer.alert(data.msg, {icon: 2});
                        }
                        $("button[type='submit']").prop("disabled", false);
                    }
                },
                error: function (data) {
                    layer.alert(data.responseText, {
                        icon: 2
                    });
                }
            };

            layer.confirm("确定修改料号吗？",{icon:2},function(){
                $("#add-form").ajaxSubmit(options);
            },function(){
                $("button[type='submit']").prop("disabled", false);
                layer.closeAll();
            });

            return false;
        });


        $(".select-year").click(function () {
            jeDate({
                dateCell: this,
                zIndex:8831,
                format: "YYYY",
                skinCell: "jedatedeep",
                isTime: false,
                okfun:function(elem) {
                    $(elem).change();
                },
                //点击日期后的回调, elem当前输入框ID, val当前选择的值
                choosefun:function(elem) {
                    $(elem).change();
                }
            })
        });

    });

    var myclick = function(v) {
        var llis = $(".tab_li");
        for(var i = 0; i < llis.length; i++) {
            var lli = llis[i];
            if(lli == document.getElementById("tab" + v)) {
                lli.style.backgroundColor = "#1f7ea0";
            } else {
                lli.style.backgroundColor = "#1f7ed0";
            }
        }
        var divs = document.getElementsByClassName("tab_css");
        for(var i = 0; i < divs.length; i++) {

            var divv = divs[i];

            if(divv == document.getElementById("tab" + v + "_content")) {
                divv.style.display = "block";
            } else {
                divv.style.display = "none";
            }
        }

    };



</script>