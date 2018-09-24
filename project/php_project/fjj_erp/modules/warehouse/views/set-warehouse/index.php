<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/7
 * Time: 下午 07:34
 */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = '仓库设置';

$this->params['homeLike'] = ['label' => '仓储物流管理'];
$this->params['breadcrumbs'][] = ['label' => '仓库设置'];
?>
<style>
    .photo{
        height: 23px;
        width: 55px;
        float: left;
    }
    .return{
        font-size: 14px;
        margin-top: 2px;
    }
    .f-l{
        float: left;
    }
    .width-85{
        width: 85px;
    }
    #setcharacter{
        height: 23px;
        width: 50px;
        float: left;
    }
</style>
<div class="content">
    <?php echo $this->render('_search', [
        //'get' => \Yii::$app->request->get(),
        'statusType' => $statusType,
        'downList' => $downlist,
        'queryParam' => $queryParam
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="head">仓库列表</p>
            <div style="float: right">
                <a id="add_btn">
                    <div class="photo" style="float: left">
                        <p class="add-item-bgc f-l"></p>
                        <p class="return">&nbsp;新增</p>
                    </div>
                </a>
                <p class="f-l">&nbsp;|&nbsp;</p>
                <a id="edit">
                    <div class="photo" style="float: left">
                        <p class="update-item-bgc f-l" ></p>
                        <p class="return">&nbsp;修改</p>
                    </div>
                </a>
                <p class="f-l" style="display: none">&nbsp;|&nbsp;</p>
                <a  id="sopen"  class="view width-85">
                    <div class="sopen" style="float: left">
                        <p class="submit-item-bgc f-l"></p>
                        <p style="width: 45px">&nbsp; 启用</p>
                    </div>
                </a>
                <p class="f-l" style="display: none">&nbsp;|&nbsp;</p>
                <a  id="scancel"  class="view width-85">
                    <div class="scancel" style="float: left">
                        <p class="setbcg9 f-l" ></p>
                        <p style="width: 45px">&nbsp; 禁用</p>
                    </div>
                </a>
                <p class="f-l" style="float: left;display: none">&nbsp;|&nbsp;</p>
                <a id="return">
                    <div class="photo" style="float: left">
                        <p class="return-item-bgc f-l"></p>
                        <p class="return">&nbsp;返回</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="space-10"></div>
<!--        <div style="clear: right;"></div>-->
        <div id="data" style="width:100%;"></div>
        <div id="sscode" style="display: none"></div>
        <div id="load-content_title"> </div>
        <div id="load-content" class="overflow-auto"></div>
    </div>
    <div class="space-30"></div>
</div>
<script>
    $(function () {
        "use strict";
        $("#edit").hide().next().hide();
        $("#sopen").hide().next().hide();
        $("#scancel").hide().next().hide();
        var id;
        var isCheck = false; //是否点击复选框
        var isSelect = false; //是否点击单条
        var onlyOne = false; //是否只选中单个复选框
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "wh_code",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            //设置复选框和行的选择状态不同步
            checkOnSelect: false,
            selectOnCheck: false,
//            fitColumns: true,
            columns: [[
                {field: 'ck', checkbox: true},
                <?=$data['table']?>
//                {field: "awh_code", title: "仓库代码", width: 150},
//                {field: "wh_name", title: "仓库名称", width: 150},
//                {field: "people", title: "法人", width: 100,formatter:function (value) {
//                    if(value=="0"){
//                        return "";
//                    }else {
//                        return value;
//                    }
//                }},
//                {field: "company", title: "创业公司", width: 100,formatter:function (value) {
//                    if(value==0){
//                        return "";
//                    }else {
//                        return value;
//                    }
//                }},
//                {field: "wh_addr", title: "仓库地址", width: 100},
//                {field: "wh_nature", title: "仓库性质", width: 100,formatter:function (value) {
//                    if(value=="100877"){
//                        return "保税";
//                    }else if(value=="100878"){
//                        return "非保税";
//                    }
//                }},
//                {field: "wh_attr", title: "仓库属性", width: 100,formatter:function (value) {
//                    if(value=="100880")
//                    {
//                        return "自有";
//                    }else if(value=="100879"){
//                        return "外租";
//                    }
//                    }
//                },
//                {field: "wh_type", title: "仓库类别", width: 100,formatter:function (value) {
//                    if(value=="100881"){
//                        return "普通仓库";
//                    }else if(value=="100882"){
//                        return "恒温恒湿仓库";
//                    }else if(value=="100883"){
//                        return "贵重物品仓库";
//                    }else if(value=="100884"){
//                        return "其它仓库";
//                    }
//                }},
//                {field: "wh_lev", title: "仓库级别", width: 80,formatter:function (value) {
//                    if(value=="100893"){
//                        return "一级";
//                    }else if(value=="100894"){
//                        return "二级";
//                    }else if(value=="100895"){
//                        return "三级";
//                    }else {
//                        return "其他";
//                    }
//                }},
//                {field:"wh_yn",title:"是否报废仓", width: 70,formatter:function(value){
//                    if(value=='N'){
//                        return "否";
//                    }
//                    if(value=='Y'){
//                        return "是";
//                    }
//                }},
//                {field:"yn_deliv",title:"是否自提点", width: 70,formatter:function(value){
//                    if(value=='0'){
//                        return "否";
//                    }
//                    if(value=='1'){
//                        return "是";
//                    }
//                }},
//                {field: "wh_state", title: "状态", width: 70,formatter:function (value) {
//                    if(value=="Y"){
//                        return "启用";
//                    }else {
//                        return "<span>禁用</span>";
//                    }
//                }},
//                {field: "opper", title: "操作人", width: 100},
//                {field: "opp_date", title: "操作时间", width: 150},
                {field: "wh_id", title: "操作", width:60, formatter: function (value, rowData, rowIndex) {
                    var str="";
                    if (rowData.wh_state == "启用") {
                        str+="<a class='operate icon-check-minus icon-large' title='禁用'></a>" +
                            "<input type='hidden' class='wh_codes'  >" +
                            "<input type='hidden' class='wh_yn' >";
                    } else {
                        str+="<a class='operate icon-check-sign icon-large' title='启用'></a>" +
                            "<input type='hidden' class='wh_codes'  >" +
                            "<input type='hidden' class='wh_yn' '>";
                    }
                    str+="<a href='<?=Url::to(['update-warehouse'])?>?id="+value+" ' class='icon-edit icon-large' style='margin-left:15px;' title='修改'></a>";
                    return str;
                }}
            ]],
            onLoadSuccess: function (data) {
                datagridTip($("#data"));
                showEmpty($(this), data.total, 1);
                $('#data').datagrid("clearSelections");
                $('#data').datagrid("clearChecked");
                //每一个仓库代码详情
                $(".viewitem").click(function () {
                    var id = $(this).html();
                       location.href="<?= Url::to(['view'])?>?id=" + id;
//                    });
                <?= Yii::$app->user->identity->staff_id ?>
                });
                //---end----
            },
            onCheck: function (rowIndex, rowData) {
                var aa = new Array();
                var _check = $("#data").datagrid("getChecked");
                if (_check.length == 1) {
                    if (_check[0]['wh_state'] == '禁用') {
                        $("#sopen").show().next().show();
                        $("#edit").show().next().show();
                        $("#scancel").hide().next().hide();
                    } else {
                        $("#sopen").hide().next().hide();
                        $("#scancel").show().next().show();
                        $("#edit").show().next().show();
                    }
                    isCheck = true;
                    if (isCheck && !isSelect) {
                        isSelect = false;
                        onlyOne = true;
                        $('#data').datagrid('selectRow', rowIndex);
                    }
                }
                else {
                    if (_check[0]['wh_state'] == '禁用') {
                        $("#sopen").show().next().show();
                        $("#edit").hide().next().hide();
                        $("#scancel").hide().next().hide();
                    } else {
                        $("#sopen").hide().next().hide();
                        $("#scancel").show().next().show();
                        $("#edit").hide().next().hide();
                    }
                    isCheck = true;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                }
            },
            onUncheck: function (rowIndex, rowData) {
                var a1 = $("#data").datagrid("getChecked");
                if (a1.length == 0) {
                    $("#edit").hide().next().hide();
                    $("#sopen").hide().next().hide();
                    $("#scancel").hide().next().hide();
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $('#data').datagrid("unselectAll");
                } else {
                    if (a1.length == 1) {
                        if (a1[0].wh_state == '启用') {
                            $("#sopen").show().next().show();
                            $("#edit").show().next().show();
                            $("#scancel").hide().next().hide();
                        } else {
                            $("#sopen").hide().next().hide();
                            $("#scancel").show().next().show();
                            $("#edit").show().next().show();
                        }
                        var b = $("#data").datagrid("getRowIndex", a1[0].wh_code);
                        $('#data').datagrid('selectRow', b);
                    }
                    else {
                    }
                }
            },
            onCheckAll: function (rowIndex, rowData) {
                $('#data').datagrid("unselectAll");
                $("#edit").hide().next().hide();
                $("#sopen").hide().next().hide();
                $("#scancel").hide().next().hide();
            },
            onUnselectAll: function (rowIndex, rowData) {
            },
            onUncheckAll: function (rowIndex, rowData) {
                isCheck = false;
                isSelect = false;
                onlyOne = false;
                $("#edit").hide().next().hide();
                $("#sopen").hide().next().hide();
                $("#scancel").hide().next().hide();
            },
            onSelect: function (rowIndex, rowData) {    //行选择触发事件
                var index = $("#data").datagrid("getRowIndex", rowData.wh_code);
                $('#data').datagrid("uncheckAll");
                var oderh = $("#data").datagrid("getSelected");
                var _id=oderh.wh_code;
                $("#sscode").empty();
                $("#sscode").append(_id);
                var idss=$("#sscode").find(".wcode").text();
//                alert(idss);
                if (isCheck && !isSelect && !onlyOne) {
                    onlyOne = true;
                    $('#data').datagrid('selectRow', index);
                } else {
                    isSelect = true;
                    onlyOne = true;
                    isCheck = false;
                }
                $('#data').datagrid('checkRow', rowIndex);
                $("#load-content_title").addClass("table-head mb-5 mt-20").html("<p class='head'>仓库管理员信息</p>");
                $("#load-content").datagrid({
                    url: "<?=Url::to(['/warehouse/set-warehouse/load-infor']) ?>?id=" + idss,
                    rownumbers: true,
                    method: "get",
                    idField: "wh_id",
                    loadMsg: false,
                    pagination: true,
                    singleSelect: false,
                    checkOnSelect:false,
                    pageSize: 5,
                    pageList: [5, 10, 15],
                    fitColumns: true,
                    columns: [[
                        {field: "wh_code", title: "仓库代码",width: 120},
                        {field: "staffname", title: "仓库管理员",width: 120},
                        {field: "adm_phone", title: "联系电话",width: 120},
                        {field: "adm_email", title: "邮箱",width: 120},
                    ]],
                    onLoadSuccess: function (data) {
                        datagridTip($("#load-content"));
                        showEmpty($(this),data.total,0);
                    }
                });
            }
        });
        
        //新增仓库信息(弹窗)add by jh
        $("#add_btn").click(function(){
            location.href="<?=Url::to(['create-warehouse'])?>";
        });
        //修改仓库信息
        $("#edit").on("click", function () {
            var row = $("#data").datagrid("getSelected");
            if (row == null) {
                layer.alert("请点击选择一条需求单信息", {icon: 2, time: 5000});
            } else {
                var id = row['wh_id'];
//                alert(id);
                window.location.href="<?= Url::to(['update-warehouse'])?>?id=" + id;
            }
        });

        //操作栏禁用启用
        $(".table-content").delegate(".operate","click", function () {
            var row = $("#data").datagrid('getSelected');
            if (row) {
                var YN = row['wh_state'];
                var openclose = "";
                if(YN == "启用"){
                    openclose = "确定将此数据禁用码?";
                }else{
                    openclose = "确定将此数据启用吗?";
                }
                layer.confirm(openclose, {
                    btn: ['确定', '取消'],
                    icon: 2
                }, function () {
                    $.ajax({
                        url: "<?=Url::to(['update-wh-state'])?>",
                        data: {
                            "id": row['wh_id'],
//                            "staff_id":<?//= Yii::$app->user->identity->staff_id ?>
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {icon: 1});
                                $("#data").datagrid("reload", {
                                    url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                    onLoadSuccess: function () {
                                        $("#data").datagrid("clearChecked");
                                    }
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    });
                }, function () {
                    layer.closeAll();
                });
            }
        });

        //关闭或者开启
        $(".table-content").delegate("#sopen","click", function () {
            var s=new Array();
            var row = $("#data").datagrid('getChecked');
            for(var i=0;i<row.length;i++)
            {
                s.push(row[i]['wh_id'])
            }
            if (row) {
                var openclose = "";
                    openclose = "确定将此数据启用吗?";
                layer.confirm(openclose, {
                    btn: ['确定', '取消'],
                    icon: 2
                }, function () {
                    $.ajax({
                        url: "<?=Url::to(['openss'])?>",
                        data: {
                            "id": s
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {icon: 1});
                                $("#data").datagrid("reload", {
                                    url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                    onLoadSuccess: function () {
                                        $("#data").datagrid("clearChecked");
                                    }
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    });
                }, function () {
                    layer.closeAll();
                });
            }
        });

        $(".table-content").delegate("#scancel","click", function () {
            var s=new Array();
            var row = $("#data").datagrid('getChecked');
            for(var i=0;i<row.length;i++)
            {
                s.push(row[i]['wh_id'])
            }
            if (row) {
                var openclose = "";
                openclose = "确定将此数据禁用?";
                layer.confirm(openclose, {
                    btn: ['确定', '取消'],
                    icon: 2
                }, function () {
                    $.ajax({
                        url: "<?=Url::to(['closess'])?>",
                        data: {
                            "id": s
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.flag == 1) {
                                layer.alert(data.msg, {icon: 1});
                                $("#data").datagrid("reload", {
                                    url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                                    onLoadSuccess: function () {
                                        $("#data").datagrid("clearChecked");
                                    }
                                });
                            } else {
                                layer.alert(data.msg, {icon: 2});
                            }
                        }
                    });
                }, function () {
                    layer.closeAll();
                });
            }
        });



        //数据导出
        $('#export').click(function () {
            var page = $("#data").datagrid("getPager").data("pagination").options.pageNumber;
            var rows = $("#data").datagrid("getPager").data("pagination").options.pageSize;
            var index = layer.confirm("确定导出仓库物流信息?",
                {
                    btn: ['确定', '取消'],
                    icon: 2
                },
                function () {
                    if (window.location.href = "<?= Url::to(['index', 'export' => '1'])?>&page=" + page + "&rows=" + rows) {
                        layer.closeAll();
                    } else {
                        layer.alert('导出仓库物流信息发生错误', {icon: 0})
                    }
                },
                function () {
                    layer.closeAll();
                }
            )
        });
        //返回
        $("#return").on("click", function () {
            window.location.href = "<?=Url::to(['/index/index'])?>";
        })

    });
</script>


