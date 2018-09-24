<?php
/**
 * User: G0007903
 * Date: 2017/10/20
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title='问卷列表';
$this->params['homeLike']=['label'=>'人事管理'];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search',['data'=>$data])?>
    <div class="table-head mb-10">
        <p>问卷列表:</p>
        <div class="float-right">
            <a id="add">
                <div style="height: 23px;float: left">
                    <p class="add-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                </div>
            </a>
            <p style="float: left;">&nbsp;|&nbsp;</p>
            <a id="close" class="display-none">
                <div style="height: 23px;float: left;">
                    <p class="setting11" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;关闭</p>
                </div>
            </a>
            <p style="float: left;display: none">&nbsp;|&nbsp;</p>
            <a id="delete" class="display-none">
                <div style="height: 23px;float: left;">
                    <p class="delete-item-bgc" style="float: left"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;删除</p>
                </div>
            </a>
            <p style="float: left;display:none">&nbsp;|&nbsp;</p>
            <a href="<?=Url::to(['/index/index'])?>">
                <div style="height: 23px;float: left">
                    <p class="return-item-bgc" style="float: left;"></p>
                    <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                </div>
            </a>
        </div>
    </div>
    <div id="data" style="width: 990px;"></div>
</div>
<script>
    $(function() {
        var id;
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers:true,
            method: "get",
            idField: "invst_id",
            loadMsg: "加载中...",
            pagination: true,
            singleSelect: true,
            selectOnCheck: false,
            checkOnSelect: false,
            columns: [[
                {field: 'ck', checkbox: true},
                {field: "yn_de", title: "删除状态",hidden: true},
                {field: "invst_id", title: "类别ID",hidden: true},
                {field: "invst_state", title: "问卷状态(默認0未開始，1進行中，2結束，3已統計)",hidden:true },
                {field: "yn_close", title: "是否关闭。默認0未關閉，1關閉",hidden:true },
                {field: "invst_subj", title: "问卷主题",width: 181},
                {field: "bsp_svalue", title: "问卷类别", width: 100},
                {field: "organization_name", title: "主办单位", width: 120},
                {field: "dd", title: "调查对象", width: 290},
                {field: "clo_nums", title: "答卷数量", width: 50},
                {field: "opper", title: "操作", width: 140,align:'left', formatter: function (value, rowData, rowIndex) {
                    var str="<i>";
                    str+="<a title='统计结果'; href='<?=Url::to(['qsn-count-result']);?>?id="+rowData['invst_id']+" ' class='icon-bar-chart icon-large'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    str+=" <a title='答卷列表'; href='<?=Url::to(['responses-list'])?>?id="+rowData['invst_id']+" ' class='icon-list-ul icon-large'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    if (rowData.yn_close==1||rowData.invst_state!=1){
                        str+='';
                    }
                    else {
                    str+="<a class='operate icon-ban-circle icon-large' title='关闭问卷'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    }
                    if (rowData.yn_de==1||rowData.invst_state!=0){
                        str+='';
                    }
                    else {
                        str += "<a class='deleterow icon-trash icon-large' title='删除问卷'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    }
                    if (rowData.yn_close==1||rowData.invst_state!=1){
                        str+='';
                    }
                    else {
                        str += "<a class='getaddr icon-file-text-alt icon-large' title='获取地址'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                    }
                    str+="</i>";
                    return str;
                }}
            ]],
            onSelect: function (rowIndex, rowData) {
                var index = $("#data").datagrid("getRowIndex", rowData.invst_id);
                $('#data').datagrid("uncheckAll");
                var a1 = $("#data").datagrid("getSelected");
                    if((a1.invst_state!=0)||(a1.yn_de==1)){
                        $("#delete").hide().next().hide();
                    }else {
                        $("#delete").show().next().show();
                    }
                    if((a1.invst_state!=1)||(a1.yn_close==1)){
                        $("#close").hide().next().hide();
                    }else {
                        $("#close").show().next().show();
                    }
                if (isCheck && !isSelect && !onlyOne) {
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
//                $("#close").show().next().show();
//                $("#delete").show().next().show();
                $('#data').datagrid('checkRow', index);
            },
            onCheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                        for(var i=0;i<a1.length;i++){
                            if((a1[i]['invst_state']!=0)||(a1[i]['yn_de']==1)){
                                $("#delete").hide().next().hide();
                            }else {
                                $("#delete").show().next().show();
                            }
                            if((a1[i]['invst_state']!=1)||(a1[i]['yn_close']==1)){
                                $("#close").hide().next().hide();
                            }else {
                                $("#close").show().next().show();
                            }
                        }
                    }
                } else {
                    for(var j=0;j<a1.length;j++){
                    if((a1[j]['invst_state']!=0)||(a1[j]['yn_de']==1)){
                        $("#delete").hide().next().hide();
                        break;
                    }else {
                        $("#delete").show().next().show();
                    }
                    }
                    for(var k=0;k<a1.length;k++){
                        if((a1[k]['invst_state']!=1)||(a1[k]['yn_close']==1)){
                            $("#close").hide().next().hide();
                            break;
                        }else {
                            $("#close").show().next().show();
                        }
                    }
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
//                    $("#close").show().next().show();
//                    $("#delete").show().next().show();
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a = $("#data").datagrid("getChecked");
                if (a.length == 1) {
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        var b = $("#data").datagrid("getRowIndex", a[0].invst_id);
                        $('#data').datagrid('selectRow', b);
                    }
                } else if (a.length == 0) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#close").hide().next().hide();
                    $("#delete").hide().next().hide();
                    $('#data').datagrid("unselectAll");
                    $("#load-content").hide();
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                }
                else{
                    var a1 = $("#data").datagrid("getChecked");
                    for(var i=0;i<a1.length;i++){
                        if((a1[i]['invst_state']!=0)||(a1[i]['yn_de']==1)){
                            $("#delete").hide().next().hide();
                            break;
                        }else {
                            $("#delete").show().next().show();
                        }
                        if((a1[i]['invst_state']!=1)||(a1[i]['yn_close']==1)){
                            $("#close").hide().next().hide();
                            break;
                        }else {
                            $("#close").show().next().show();
                        }
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#close").hide().next().hide();
                $("#delete").hide().next().hide();
            },
            onLoadSuccess: function (data) {
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                $("#close").hide().next().hide();
                $("#delete").hide().next().hide();
                datagridTip('#data');
                showEmpty($(this), data.total, 1);
            }
        });
    });
   //新增窗口
   $("#add").click(function () {
       location.href="<?=Url::to(['add'])?>"
   });
    $("#return").click(function () {
        window.location.href = "<?=Url::to(['/index/index'])?>";
    });
    //问卷详情
    $("#viewitem").click(function () {
        window.location.href=<?=Url::to('view')?>;
    });
    //统计结果
    $("#statistical-results").click(function () {
        location.href="<?=Url::to('')?>"
    });
    //答卷列表
    $("#responses-list").click(function () {
        location.href="<?=Url::to('')?>"
    });

//关闭问卷
    $(".content").delegate(".operate","click", function () {
        var id = $("#data").datagrid("getSelected");
        $.fancybox({
            href:"<?=Url::to(['clo-reason'])?>?id="+id.invst_id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:800,
            height:520
        });
    });
    //关闭按钮
    var dd=[];
    $("#close").on("click",function(){
        var rows = $("#data").datagrid('getChecked');
        if(rows=="") {
            layer.alert("请选择一条或多条问卷信息!",{icon:2,time:5000});
        } else{
            for(var i=0;i<rows.length;i++){
                dd.push(rows[i]['invst_id']);
                if(rows[i]['invst_state']!=1||rows[i]['yn_close']==1){
                    layer.alert("问卷未开始或问卷已关闭,无法操作!",{icon:2,time:5000});
                    return false;
                }
            }
            $.fancybox({
                href:"<?=Url::to(['clo-reason'])?>?id="+dd,
                type:"iframe",
                padding:0,
                autoSize:false,
                width:800,
                height:520
            });
        }
    });
    //获取地址
    $(".content").delegate(".getaddr","click", function () {
        var id = $("#data").datagrid("getSelected");
        $.fancybox({
            href:"<?=Url::to(['get-addr'])?>?id="+id.invst_id,
            type:"iframe",
            padding:0,
            autoSize:false,
            width:400,
            height:200
        });
    });
     //删除问卷
    $(".content").delegate(".deleterow","click", function () {
        var id = $("#data").datagrid("getSelected")['invst_id'];
        var state=$("#data").datagrid("getSelected")['invst_state'];
        var yn=$("#data").datagrid("getSelected")['yn_de'];
        if (state!=0){
            layer.alert("该问卷已经开始,无法删除!",{icon:2,time:5000});
        } else if (yn==1){
            layer.alert("该问卷已经删除,无法重复删除!",{icon:2,time:5000});
        }
        else {
            var index = layer.confirm("确定要删除这条问卷吗?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    $.ajax({
                        dataType: "json",
                        data: {"id": id},
                        url: "<?=Url::to(['/hr/question-survey/delete']) ?>",
                        success: function (msg) {
                            if (msg.flag === 1) {
                                layer.alert(msg.msg, {
                                    icon: 1, end: function () {
                                        location.reload();
                                    }
                                });
                            } else {
                                layer.alert(msg.msg, {icon: 2})
                            }
                        },
                        error: function (msg) {
                            layer.alert(msg.msg, {icon: 2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            );
        }
    });
    //批量删除
    var aa=[];
    $("#delete").on("click",function(){
     var rows = $("#data").datagrid('getChecked');
     if(rows=="") {
         layer.alert("请选择一条或多条问卷信息!",{icon:2,time:5000});
      } else{
             for(var i=0;i<rows.length;i++){
                 aa.push(rows[i]['invst_id']);
                 if(rows[i]['invst_state']!=0||rows[i]['yn_de']==1){
                     layer.alert("存在已开始或已经删除的问卷,无法删除!",{icon:2,time:5000});
                     return false;
                 }
             }
             var index = layer.confirm("确定要删除这条问卷吗?",
                {
                    btn:['确定', '取消'],
                    icon:2
                },
                function () {
                    $.ajax({
//                        type: "get",
                        dataType: "json",
//                        async: false,
                        data: {"id": aa},
                        url: "<?=Url::to(['/hr/question-survey/deletes']) ?>",
                        success: function (msg) {
                            if( msg.flag === 1){
                                layer.alert(msg.msg,{icon:1,end:function(){location.reload();}});
                            }else{
                                layer.alert(msg.msg,{icon:2})
                            }
                        },
                        error :function(msg){
                            layer.alert(msg.msg,{icon:2})
                        }
                    })
                },
                function () {
                    layer.closeAll();
                }
            )
        }
    })
</script>
