<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
//echo "<pre>";print_r($data);die();
$this->params['homeLike'] = ['label' => '商品开发与管理'];
$this->params['breadcrumbs'][] = ['label' => '商品列表','url'=>Url::to(['index'])];
$status=\Yii::$app->request->get("status");
switch($status){
    case "checking":
        $this->params['breadcrumbs'][] = ['label' => '查看料号信息（审核中）'];
        break;
    case "selling":
        $this->params['breadcrumbs'][] = ['label' => '查看料号信息（銷售中）'];
        break;
    case "downshelf":
        $this->params['breadcrumbs'][] = ['label' => '查看料号信息（已下架）'];
        break;
    case "notupshelf":
        $this->params['breadcrumbs'][] = ['label' => '查看料号信息（未上架）'];
        break;
    default:
        break;
}
$this->title = '查看料号信息';/*BUG修正 增加title*/
?>
<div class="content">
<div class="mb-10">
   <h2 class="head-second mt-20 button-list">
       查看料号信息
       <button id="_return" class="button-blue" style="float: right;">返回</button>
   </h2>
   <div class="mb-10">
        <div id="partno-data"></div>
    </div>
 </div>

    <?php $form = ActiveForm::begin(['id' => 'add-form']); ?>

    <input type="hidden" id="submitType" name="submitType" value="">
    <input type="hidden" name="BsPartno[pdt_pkid]" value="<?=\Yii::$app->request->get('id')?>">
<div id="partno-info"></div>

    <div style="display: none">
        <?=\app\widgets\ueditor\Ueditor::widget([
            'id'=>'_editor',
            'name'=>'BsDetails[details]',
            "width"=>916,
            "height"=>300,
            "content"=>''
        ])?>
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

    <div id="currency-tmpl" style="width:100%;display: none;">
        <?=Html::dropDownList('BsPrice[currency][]','',$options['currency'],['style'=>'width:120px;']);?>
    </div>


</div>
<script>
    $.fn.loading=function(o){
        methods={
            start:function(){
                var left=$(this).offset().left;
                var top=$(this).offset().top;
                var width=$(this).width();
                var height=100;
                var ele=$("<div class='loading'></div>").css({position:"absolute",left:left+"px",top:top+"px",width:width+"px",height:height+"px","line-height":height+"px","text-align":"center"});
                var img=$("<img  src='data:image/gif;base64,R0lGODlhZAAUAKUkAJCapZCbpZGbppOdp5agqpahqZqjrZ2nr6GqsqOstaSttaiwuKqzuq62vrC4vrK6wLa9w7zDycDGzMbM0crP08rP1M3S1s7T19HV2dLW2tTY3Njb39/i5ODj5eLl5+Ll6Obo6unr7ers7uzv8P///////////////////////////////////////////////////////////////////////////////////////////////////////////////yH/C05FVFNDQVBFMi4wAwEAAAAh+QQJDQA/ACwAAAAAZAAUAAAG4cCfcEgsGo/IpHLJbDqf0Kh0Sq1ar9isdsvter9MkMcDLns9h4DaoDG7rxy1XG05jj4i5T2f3C/9fXh6gk4Gc3IDRhdyEUiLao1HjwGRkoyOl5aQTXGHcnVDI4cdRqJzpEWmcqipo6WuradMGJ5yE0QZh7dFuXO7uLpHvbZGw2q/Q8YByEgbtWoURB+HF0bTc9VF13LZ2tTW397YTQPPIUUNcgpI6WrrR+0B7/Dq7PX07k4TtRDCExVKMvwLOJAgwCQCDyJJCEXCoQdvIlYZMaEiCIkYM2rcyLGjx48gQz4JAgAh+QQJDQA/ACwAAAAAZAAUAAAG+sCfcEgsGo/IpHLJbDqf0Kh0Sq1ar9isdsvter9ZkMcDLk9HnhDScwi4DRqznAlxBxajIsfOtxxHHyJKgIKDgYaFSIRQD3wBCEUGjm4DRhd2EUiXbpmamJ6coAGdTCCTAXFCe6cBfkMjjh1GsHyys7G3tbl2tksYpxNDv6zBQxmOxUTHfMnKyEbLds1C0W7TSKuOFEMbrAHbQx+OF0bifOTl4+nn63boTG18A3lDA6xqRA12Ckj6bvz99gX8NzAAwCYeCNgZkGrIhFMQjmSYUEHJxIoWKWbEiOSilAkg6RWR4OjBnJNVRoCcAAKly5cwY8qcSbOmzZtCggAAIfkECQ0APwAsAAAAAGQAFAAABv7An3BILBqPyKRyyWw6n9CodEqtWq/YrHbL7Xq/YCPI4wmboyNPCOk5BN4Gzdk8+oiSkHdgMSpy9IAWR3V3SoSGdoiFTxd6EUcPgAEIRQaSbwNGjW+PSJsBnZqOnqNOI5IdRSCXAXJCf6wBgkOngKlGtXq3RLlvu7SoThmSE0UYrMVCx7HJQsOAzUTP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' />").css({width:100+"px"});
                img.appendTo(ele);
                ele.appendTo($("body"));
            },
            end:function(){
                $(".loading").remove();
            }
        };
        if(methods[o]){
            methods[o].apply(this);
        }else{
            $.error("params error");
        }
    };
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
                        layer.alert(data.msg, {
                            icon: 1,
                            end: function () {
                                if($("#submitType").val()=="check"){
                                    var row=$("#partno-data").datagrid("getSelected");
                                    var id =data.l_prt_pkid;
                                    var url = "<?=Url::to(['index'], true)?>";
                                    if("<?=$status?>"=="downshelf"){
                                        code="pdtreupshelf";
                                    }
                                    if("<?=$status?>"=="notupshelf"){
                                        code="pdtsel";
                                    }
                                    var staff="<?=\Yii::$app->user->identity->staff->staff_id?>";
                                    $.ajax({
                                        type:"get",
                                        url:"<?=\yii\helpers\Url::to(['get-bus-type'])?>?code="+code,
                                        success:function(data){
                                            var type = data;
                                            $.fancybox({
                                                href: "<?=Url::to(['reviewer'])?>?type=" + type + "&id=" + id + "&url=" + url+"&staff="+staff,
                                                type: "iframe",
                                                padding: 0,
                                                autoSize: false,
                                                width: 750,
                                                height: 480
                                            });
                                        }
                                    });
                                }else{
                                    if(data.url){
                                        window.location.href=data.url;
                                    }
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




        $("#partno-data").datagrid({
            url: "<?=\yii\helpers\Url::to(['partno-list', 'id' => \Yii::$app->request->get('id'),'status'=>$status])?>",
            rownumbers: true,
            method: "get",
            idField: "prt_pkid",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns: [[
                {field: 'pdt_no', title: '商品编号', width: 100},
                {field: 'pdt_name', title: '品名', width: 100},
                {field: 'cat_three_level', title:'类别', width: 200},
                {field: 'part_no', title: '相关料号', width: 150},
                {field: 'price', title: '价格', width: 150},
                {field:'action',title:'操作',width:200,formatter:function(value,row,index){
                    return "<i><a href='javascript:void(0)' onclick='editPartno("+row.prt_pkid+")'>维护料号信息</a></i>";
                }}
            ]],
            onLoadSuccess: function (data) {
                if(data.rows.length>0){
                    $("#partno-data").datagrid("selectRow", 0);
                }
                datagridTip($("#partno-data"));
                showEmpty($(this),data.total,0);
                $("#partno-data").datagrid("resize");
            },
            onSelect: function (index, row) {
                if(row){
                    var id=row.prt_pkid;
                    editPartno(id);
                }
            }
        });

        $("#edit_partno").click(function(){
            var row=$("#partno-data").datagrid("getSelected");
            window.location.href="<?=Url::to(['edit-partno'])?>?id="+row.prt_pkid;
        });
        //tab标签信息
        var myclick = function(v) {
            var llis = $(".tab_li");
            for(var i = 0; i < llis.length; i++) {
                var lli = llis[i];
                if(lli == document.getElementById("tab" + v)) {
                    lli.style.backgroundColor = "#1f7ed0";
                } else {
                    lli.style.backgroundColor = "#1f7ed0";
                }
            }
        };

    var divs = document.getElementsByClassName("tab_css");
    for(var i = 0; i < divs.length; i++) {

        var divv = divs[i];

        if(divv == document.getElementById("tab" + v + "_content")) {
            divv.style.display = "block";
        } else {
            divv.style.display = "none";
        }
    }

    //商品下架
    $("#_downprod").click(function () {
        var row=$("#partno-data").datagrid("getSelected");
        $.fancybox({
            padding:[],
            autoSize: false,
            fitToView: false,
            height: 450,
            width: 540,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= \yii\helpers\Url::to(['down-shelf'])?>?id="+row.prt_pkid+"&partno="+row.part_no+"&url="
        })
    });

    //审核进度页面
    $("#check_info").click(function(){
        var row=$("#partno-data").datagrid("getSelected");
        window.location.href="<?= \yii\helpers\Url::to(['check-info'])?>?id="+row.prt_pkid;
    });

    //返回
    $("#_return").click(function () {
        window.location.href="<?=\yii\helpers\Url::to(['index'])?>";
    });
    });


    //审核进度弹窗
    function checkInfo(id){
        var event=event || window.event;
        if(!event){
            event=arguments.callee.caller.arguments[0];
        }
        event && event.stopPropagation();
        $.fancybox({
            padding:[],
            type:'iframe',
            width:800,
            height:400,
            autoSize: false,
            href:'<?=Url::to(['check-info-popup'])?>?id='+id
        });
    }



    //料号信息维护
    function editPartno(id){
        $("#partno-info").empty();
        $("#partno-info").loading("start");
        var event=event || window.event;
        if(!event){
            event=arguments.callee.caller.arguments[0];
        }
        event && event.stopPropagation();
        $.ajax({
            url:"<?=\yii\helpers\Url::to(['partno-ajax-form'])?>?id=" + id+"&status=<?=$status?>&type=2",
            type:"get",
            success:function(res){
                $("#partno-info").loading("end");
                $("#add-form").attr("action","<?=Url::to(['redo-upshelf'])?>?id="+id);
                $("#partno-info").html(res);
//                $("#partno").text(row.part_no);
//                $("#partno_hidden").val(row.part_no);
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








    //商品下架
    function downshelf(id,partno){
        $.fancybox({
            padding:[],
            autoSize: false,
            fitToView: false,
            height: 450,
            width: 540,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none',
            type: 'iframe',
            href: "<?= \yii\helpers\Url::to(['down-shelf'])?>?id="+id+"&partno="+partno
        });
    }

    //上架
    function upshelf(id){
        $.ajax({
            type:"get",
            url:"<?=\yii\helpers\Url::to(['get-bus-type','code'=>'pdtsel'])?>",
            success:function(data){
                var type = data;
                var url="<?=\yii\helpers\Url::to(['index'])?>";
                $.fancybox({
                    href: "<?=\yii\helpers\Url::to(['reviewer'])?>?type=" + type + "&id=" + id+"&url="+url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 750,
                    height: 480
                });
            }

        });
    }

    //重新上架
    function redoUpshelf(id){
        $.ajax({
            type:"get",
            url:"<?=\yii\helpers\Url::to(['get-bus-type','code'=>'pdtreupshelf'])?>",
            success:function(data){
                var type =data;
                $.ajax({
                    type:"get",
                    url:"<?=\yii\helpers\Url::to(['redo-upshelf'])?>?id="+id,
                    dataType:"json",
                    success:function(data){
                        var id=data.l_prt_pkid;
                        var url="<?=\yii\helpers\Url::to(['index'])?>";
                        $.fancybox({
                            href: "<?=\yii\helpers\Url::to(['reviewer'])?>?type=" + type + "&id=" + id+"&url="+url,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 750,
                            height: 480
                        });
                    }
                });
            }
        });
    }



    //审核进度
    function checkProgress(id){
        $.fancybox({
            padding:[],
            href: "<?=\yii\helpers\Url::to(['check-info-popup'])?>?id="+id,
            type: "ajax"
        });
    }

</script>
