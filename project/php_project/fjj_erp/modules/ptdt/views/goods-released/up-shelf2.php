<?php
use yii\bootstrap\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
\app\assets\JeDateAsset::register($this);
\app\widgets\ueditor\UeditorAsset::register($this);



$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '发布新商品','url'=>'index'];
$this->params['breadcrumbs'][] = ['label' => '维护料号信息'];
$this->title ='维护料号信息';

?>
<div class="content">
    <h1 class="head-first">
        <?= Html::encode($this->title) ?>
    </h1>


    <style>
        .head-second{background-color: #ffffff}
        #tab_bar {  width: 950px;  height: 20px;  float: left;  }
        #tab_bar ul {  padding: 0px;  margin: 0px;  height: 23px;  text-align: center;  }
        #tab_bar li {  list-style-type: none;  float: left;  width: 85px;  height: 23px;  background-color: #1f7ed0;  margin-right: 5px;  line-height: 23px;  cursor: pointer;  color: #ffffff;  }
        .tab_css {  width: 990px; /*height: 200px;*/ /*background-color: darkgray;*/  height: auto;  display: none;  float: left;  margin-top: 20px;  }
        ._basic{height:1000px;}
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
        .required label:before{
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
    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>
    <input type="hidden" id="submitType" name="submitType" value="">
    <input type="hidden" name="BsPartno[pdt_pkid]" value="<?=\Yii::$app->request->get('id')?>">
    <div class="mb-10">
        <div id="partno-data"></div>
    </div>

    <div id="partno-info"></div>


    <div style="display: none">
        <?=\app\widgets\ueditor\Ueditor::widget([
            'id'=>'_editor',
            'name'=>'BsDetails[details]',
            "width"=>880,
            "height"=>300,
            "content"=>''
        ])?>
    </div>
    <?php ActiveForm::end()?>

    <div style="display: none">
        <div class="warr-tab-item">
            <div class="mb-10">
                <label class="label-width label-align">出厂年限：</label>
                <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]" readonly>
            </div>
        </div>
        <div class="warr-tab-item">
            <div class="mb-10">
                <label class="label-width label-align">出厂年限：</label>
                <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]"  readonly>
            </div>
            <div class="mb-10">
                <label class="label-width label-align">库存：</label>
                <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'" name="BsMachine[stock_num]"  maxlength="11" >
            </div>
            <div class="mb-10">
                <label class="label-width label-align">新旧程度：</label>
                <input type="text" class="value-width value-align" name="BsMachine[recency]"   maxlength="50">
            </div>
        </div>
        <div class="warr-tab-item">
            <div class="mb-10">
                <label class="label-width label-align">出厂年限：</label>
                <input type="text" class="value-width value-align Wdate" onclick="WdatePicker({skin: 'whyGreen', dateFmt: 'yyyy',maxDate:'%y-%M-%d' })"  name="BsMachine[out_year]"  readonly>
            </div>
            <div class="mb-10">
                <label class="label-width label-align">租期：</label>
                <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[rentals]"  maxlength="11">&nbsp;月
            </div>
            <div class="mb-10">
                <label class="label-width label-align">租金：</label>
                <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[rental_unit]"  maxlength="11">&nbsp;RMB/月
            </div>
            <div class="mb-10">
                <label class="label-width label-align">押金：</label>
                <input type="text" class="value-width value-align easyui-validatebox" data-options="validType:'int'"  name="BsMachine[deposit]" maxlength="11">&nbsp;RMB
            </div>
        </div>
    </div>

    <div id="currency-tmpl" style="width:100%;display: none;">
        <?=Html::dropDownList('BsPrice[currency][]','',$options['currency'],['style'=>'width:120px;']);?>
    </div>


    <script>

        $(function(){

            $("#add-form").on("beforeSubmit", function () {

                if($("#supplier").text()==""){
                    layer.alert("商品无供应商无法提交！",{icon:2});
                    return false;
                }

                if($("#_byself").css("display")=="block"){
                    if($("#_byself :checked").size()==0){
                        layer.alert("请至少选择一个自提仓库！",{icon:2});
                        return false;
                    }

                    if($(".city_all:checked").index()==1){
                        layer.alert("全国免邮不能上门自提！",{icon:2});
                        return false;
                    }
                }

                var flag=true;
                for(var m=0;m<$("#attr-box .req").length;m++){
                    var el=$("#attr-box .req").eq(m);
                    if(el.find(":text").size()==0 && el.find(":checkbox").size()==0 && el.find(":radio").size()==0 && el.find("select").size()==0){
                        flag=false;break;
                    }
                    if(el.find(":text").size()>0 && el.find(":text").val()==""){
                        flag=false;break;
                    }

                    if(el.find(":checkbox").size()>0 && el.find(":checked").size()==0){
                        flag=false;break;
                    }

                    if(el.find(":radio").size()>0 && el.find(":checked").size()==0){
                        flag=false;break;
                    }

                    if(el.find("select").size()>0 && el.find("select").val()==""){
                        flag=false;break;
                    }
                }

                if(flag==false){
                    layer.alert("请完善规格参数！",{icon:2});
                    return false;
                }

                if($(".ship-box tr").size()==0){
                    layer.alert("请完善发货地信息！",{icon:2});
                    return false;
                }
                if($(".city_part:checked").size()>0 && $(".deliv-box tr").size()==0){
                    layer.alert("请完善免运费收货地信息！",{icon:2});
                    return false;
                }

                if (!$(this).form('validate')) {
                    var i=$(".validatebox-invalid:first").parents(".tab_css").index(".tab_css");
                    $(".tab_li").eq(i).trigger("click");
                    $("button[type='submit']").prop("disabled", false);
                    return false;
                }


                $(".price-box select").css("background","rgb(235, 235, 228)").prop("disabled",false);

                var options = {
                    dataType: 'json',
                    success: function (data) {
                        if (data.flag == 1) {
                                if($("#submitType").val()=="check"){
                                    layer.confirm("确定送审料号为："+$("#partno").text()+"的商品吗？",{icon:2},function () {
                                        layer.closeAll();
                                        var row=$("#partno-data").datagrid("getSelected");
                                        var id =data.l_prt_pkid;
                                        var url = "<?=Url::to(['index'], true)?>";
                                        var type = "<?= $busTypeId ?>";
                                        var staff="<?=\Yii::$app->user->identity->staff->staff_id?>";
                                        $.fancybox({
                                            href: "<?=Url::to(['reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url+"&staff="+staff,
                                            type: "iframe",
                                            padding: 0,
                                            autoSize: false,
                                            width: 750,
                                            height: 480
                                        });
                                    },function(){
                                        $("button[type='submit']").prop("disabled", false);
                                        layer.closeAll();
                                    });
                                }else{
                                    layer.alert(data.msg,{icon:1,end:function(){
                                        if(data.url){
                                            window.location.href=data.url;
                                        }
                                    }});
                                }
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
                $("#add-form").ajaxSubmit(options);
                return false;
            });


            $("#partno-data").datagrid({
                url: "<?=\yii\helpers\Url::to(['partno-list', 'id' => \Yii::$app->request->get('id')])?>",
                rownumbers: true,
                method: "get",
                idField: "prt_pkid",
                loadMsg: "加载数据请稍候。。。",
                singleSelect: true,
                checkOnSelect: true,
                selectOnCheck: false,
                columns: [[
                    {field: 'pdt_name', title: '品名', width: 310},
                    {field: 'catg_three_level', title: '类别', width:310},
                    {field: 'part_no', title: '相关料号', width:310}
                ]],
                onLoadSuccess:function(data){
                    $("#partno-data").datagrid("selectRow",0);
                },
                onSelect: function (index, row) {
                    if(row){
                        $.ajax({
                            url:"<?=\yii\helpers\Url::to(['partno-ajax-form'])?>?id=<?=\Yii::$app->request->get('id')?>&partno="+row.part_no+"&isDevice="+row.isDevice,
                            type:"get",
                            success:function(res){
                                $("#partno-info").html(res);
                                $("#partno").text(row.part_no);
                                $("#partno_hidden").val(row.part_no);
                                $(".save").click(function(){
                                    $("#submitType").val("save");
                                });
                                $(".sub").click(function(){
                                    $("#submitType").val("check");
                                });
                                $("#editor").append($("#_editor").clone(true));
                                UE.getEditor("_editor").addListener( 'ready', function( editor ) {
                                    UE.getEditor("_editor").setContent($("#editor_content").html());
                                } );
                                $.parser.parse();
                            }
                        });
                    }
                }
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








            //是否自提
            $("[name='BsPartno[isselftake]']").click(function () {
                $("#_byself").css("display",$(this).val()==1?"block":"none");
            });

            //免运费控制
            $("#country_free,#country_nofree").click(function () {
                $("#_addadress").removeAttr("style");
            });
            $("#country_part_free,#country_part_nofree").click(function () {
                $("#_addadress").css("display","none");
            });



            //包装信息-重置
            $(".pack-box").on("click",".reset",function(){
                $(this).parents("tr").find(".editable input").val("");
                $(this).parents("tr").find(":checked").prop("checked",false);
                $.parser.parse($(this).parents("tr"));
            });

            //延保方案-新增
            $(".add-warr").click(function(){
                var tr=$("<tr>" +
                    "<td></td>" +
                    "<td class='editable'><input type='text' class='easyui-validatebox' data-options="+"validType:'decimal[10,1]'"+"  name='BsWarr[wrr_prd][]' maxlength='10'></td>" +
                    "<td class='editable'><input type='text' class='easyui-validatebox' data-options="+"validType:'int'"+"  name='BsWarr[wrr_fee][]' maxlength='30'></td>" +
                    "<td><a class='remove'>删除</a></td>" +
                    "</tr>");
                $(".warr-box").append(tr);
                $(".warr-box tr").each(function(){
                    $(this).find("td").first().text(parseInt($(this).index())+1)
                });
                $(".warr-box").next("tfoot").css("display",$(".warr-box tr").size()>0?"none":"table-footer-group");
                $.parser.parse(tr);
            });
            //延保方案-删除
            $(".warr-box").on("click",".remove",function(){
                $(this).parents("tr").remove();
                $(".warr-box tr").each(function(){
                    $(this).find("td").first().text(parseInt($(this).index())+1)
                });
                $(".warr-box").next("tfoot").css("display",$(".warr-box tr").size()>0?"none":"table-footer-group");
            });


            $("body").delegate(".warr-tab :radio","click",function(){
                var index=$(this).index();
                $(".warr-tab-content").empty().html($(".warr-tab-item").eq(index-1).clone());
                $.parser.parse($(".warr-tab-content"));
            });
            $(".warr-tab :checked").click();



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

</div>
