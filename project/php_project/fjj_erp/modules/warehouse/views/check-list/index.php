<?php
use yii\helpers\Url;

$this->title = '盘点单列表';
$this->params['homeLike'] = ['label' => '仓库物流管理'];
$this->params['breadcrumbs'][] = ['label' => '盘点单列表'];
?>
<style>
    .space-10{
        width: 100%;
        height: 10px;
    }
</style>
<div class="content">
    <?=$this->render('search',['data'=>$data])?>
    <?php echo $this->render('_action'); ?>
    <script>
    </script>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
        <div id="child-title"></div>
        <div id="messages"></div>
    </div>
    <script>
        $(function () {
            $("#edit_btn").hide().next().hide();
            $("#addmes_btn").hide().next().hide();
            $("#cancel_btn").hide().next().hide();
            var id;
            var isCheck = false; //是否点击复选框
            var isSelect = false; //是否点击单条
            var onlyOne = false; //是否只选中单个复选框
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "ivt_id",
                loadMsg: "加载数据请稍候。。。",
                selectOnCheck:false,
                checkOnSelect:false,
                pagination: true,
                singleSelect:true,
                columns: [[
                    {field: 'ck', checkbox: true},
                    {field: "ivt_id", title: "盘点单id",hidden:true},
//                    {field: "ivt_code", title: "盘点单号",width: 130
//                        ,formatter:function (value,rowDate) {
//                        return '<a style="color: #2D64B3" href="view?id='+rowDate.ivt_id+'&code='+value+'">'+value+'</a>'
//                    }
//                    },
//                    {field: "company_name", title: "法人", width: 100},
//                    {field: "stage", title: "期别", width: 50},
//                    {field: "wh_name", title: "仓库名称", width: 100},
//                    {field: "wh_code", title: "仓库代码", width: 130},
//                    {field: "expiry_date", title: "库存截止时间", width: 120},
//                    {field: "first_ivtor", title: "初盘人", width: 60},
//                    {field: "first_date", title: "初盘日期", width: 100},
//                    {field: "re_ivtor", title: "复盘人", width: 60},
//                    {field: "re_date", title: "复盘日期", width: 80},
//                    {field: "ivt_status", title: "状态", width: 70},
                    <?=$data['table']?>
                    {field:'oper',title:'操作',width:60,align:'center',formatter: function (value, rowData, rowIndex) {
                        if (rowData.ivt_status=='待提交'||rowData.ivt_status=='信息完善中'||rowData.ivt_status=='驳回') {
                            var str = "<i style='margin-left: 6px'>";
                            str += "<a class='operate icon-check-minus  icon-large' title='取消'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                            str += "<a class='edit icon-edit icon-large'  title='修改'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                            str += "</i>";
                            return str;
                        }
                    }}
                ]],
                onLoadSuccess: function (data) {
                    $('#data').datagrid("clearSelections");
                    $('#data').datagrid("clearChecked");
                    datagridTip('#data');
                },
                onSelect: function (rowIndex, rowData) {
//                    $(this).datagrid('clearSelections');
                    $("#child-title").show().next().show();
                    var index = $("#data").datagrid("getRowIndex", rowData);
                    $('#data').datagrid("uncheckAll");
                    var a1 = $("#data").datagrid("getSelected");
                    var id=$(a1.ivt_code).text();
                    if((a1.ivt_status=='待提交')||(a1.ivt_status=='驳回')){
                        $("#censorship_btn").show().next().show();
                    }
                    else {
                        $("#censorship_btn").hide().next().hide();
                    }
                    if(a1.ivt_status=='信息完善中'){
                        $("#edit_btn").show().next().show();
                        $("#addmes_btn").show().next().show();
                        $("#cancel_btn").show().next().show();
                    }
                    else if((a1.ivt_status=='待提交')||(a1.ivt_status=='驳回')){
                        $("#edit_btn").show().next().show();
                        $("#addmes_btn").hide().next().hide();
                        $("#cancel_btn") .show().next().show();
                    }else{
                        $("#edit_btn").hide().next().hide();
                        $("#addmes_btn").hide().next().hide();
                        $("#cancel_btn").hide().next().hide();
                    }
                    if (isCheck && !isSelect && !onlyOne) {
                        var a = $("#data").datagrid("getChecked");
                        onlyOne = true;
                        $('#data').datagrid('selectRow', index);
                    } else {
                        isSelect = true;
                        onlyOne = true;
                        isCheck = false;
                    }
                    $('#data').datagrid('checkRow', index);
                    $("#child-title").addClass("table-head").css({
                        "margin-bottom": "5px",
                        "margin-top": "20px"
                    }).html("<p class='head'>盘点商品信息表</p>");
                    $("#messages").datagrid({
                        url: "<?= Url::to(['/warehouse/check-list/commodity']) ?>?id=" + id,
                        rownumbers: true,
                        method: "get",
                        idField: "ivtdt_id",
                        loadMsg: "加载数据请稍候。。。",
                        pagination: true,
                        singleSelect: false,
                        columns: [[
                            {field: "ivtdt_id", title: "盘点商品ID", hidden: true},
                            {field: "part_no", title: "料号", width: 181},
                            {field: "pdt_name", title: "品名", width: 181},
                            {field: "tp_spec", title: "规格型号", width: 90},
                            {field: "unit", title: "单位", width: 90},
                            {field: "notax_price", title: "成本单价", width: 80},
                            {field: "invt_num", title: "库存数量", width: 80},
                            {field: "first_num", title: "初盘数量", width: 80},
                            {field: "re_num", title: "复盘数量", width: 80},
                            {field: "lose_num", title: "盈亏数量", width: 80},
                            {field: "lose_price", title: "盈亏金额", width: 80},
                            {field: "remarks", title: "初盘备注", width: 181},
                            {field: "remarks1", title: "复盘备注", width: 181}
                        ]],
                        onLoadSuccess: function (messages) {
                            datagridTip('#messages');
                            showEmpty($(this), data.total, 1);
                        }
                    });
                },
                onCheck: function (rowIndex, rowData) {
                    var a1 = $("#data").datagrid("getChecked");
//                    console.log(a1.length);
                    if (a1.length == 1) {
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            for(var m=0;m<a1.length;m++){
                                if((a1[m]['ivt_status']=='待提交')||(a1[m]['ivt_status']=='驳回')){
                                    $("#censorship_btn").show().next().show();
                                }
                                else{
                                    $("#censorship_btn").hide().next().hide();
                                }
                            }
                            for(var d=0;d<a1.length;d++){
                                if(a1[d]['ivt_status']=='信息完善中'){
                                    $("#edit_btn").show().next().show();
                                    $("#addmes_btn").show().next().show();
                                    $("#cancel_btn").show().next().show();
                                }
                                else if((a1[d]['ivt_status']=='待提交')||(a1[d]['ivt_status']=='驳回')){
                                    $("#edit_btn").show().next().show();
                                    $("#cancel_btn").show().next().show();
                                }
                                else if((a1[d]['ivt_status']=='待提交')||(a1[d]['ivt_status']=='驳回')||(a1[d]['ivt_status']=='信息完善中')){
                                    $("#cancel_btn").show().next().show();
                                }
                            }
                            isSelect = false;
                            onlyOne = true;
                            $('#data').datagrid('selectRow', rowIndex);
                        }
                    }
                    else {
                        $("#edit_btn").hide().next().hide();
                        $("#addmes_btn").hide().next().hide();
                        for(var g=0;g<a1.length;g++) {
                            if ((a1[g]['ivt_status'] !='待提交')&&(a1[g]['ivt_status'] !='驳回')) {
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#censorship_btn").show().next().show();
                            }
                        }
                        for(var i=0;i<a1.length;i++) {
                            if ((a1[i]['ivt_status'] !='信息完善中')&&(a1[i]['ivt_status'] !='待提交')&&(a1[i]['ivt_status'] !='驳回')) {
                                $("#cancel_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#cancel_btn").show().next().show();
                            }
                        }
                        isCheck = true;
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                    }
                },
                onUncheck: function (rowIndex, rowData) {
                    var a = $("#data").datagrid("getChecked");
                    if (a.length == 1) {
                        for(var e=0;e<a.length;e++){
                            if((a[e]['ivt_status']!='待提交')&&(a[e]['ivt_status']!='驳回')){
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else{
                                $("#censorship_btn").show().next().show();
                            }
                        }
                        for(var d=0;d<a.length;d++){
                            if(a[d]['ivt_status']=='信息完善中'){
                                $("#edit_btn").show().next().show();
                                $("#addmes_btn").show().next().show();
                                $("#cancel_btn").show().next().show();
                            }
                            else if((a[d]['ivt_status']=='待提交')||(a[d]['ivt_status']=='驳回')){
                                $("#edit_btn").show().next().show();
                                $("#addmes_btn").hide().next().hide();
                                $("#cancel_btn").show().next().show();
                            }
                        }
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            isSelect = false;
                            onlyOne = true;
                            var b = $("#data").datagrid("getRowIndex", a[0].ivt_id);
                            $('#data').datagrid('selectRow', b);
                        }
                    } else if (a.length == 0) {
                        $("#edit_btn").hide().next().hide();
                        $("#addmes_btn").hide().next().hide();
                        $("#cancel_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
                        isCheck = false;
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
                        $("#load-content").hide();
                        if (isCheck && !isSelect) {
                            isSelect = false;
                            onlyOne = false;
                            $('#data').datagrid("unselectAll");
                        }
                    }
                    else{
                        $("#edit_btn").hide().next().hide();
                        $("#addmes_btn").hide().next().hide();
                        var a1 = $("#data").datagrid("getChecked");
                        for(var j=0;j<a1.length;j++) {
                            if ((a1[j]['ivt_status'] != '待提交')&&(a[j]['ivt_status']!='驳回')) {
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#censorship_btn").show().next().show();
                            }
                        }
                        for(var i=0;i<a1.length;i++) {
                            if ((a1[i]['ivt_status'] != '待提交') && (a1[i]['ivt_status'] != '驳回')&&(a1[i]['ivt_status'] != '信息完善中')) {
                                $("#cancel_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#cancel_btn").show().next().show();
                            }
                        }
                    }
                },
                onCheckAll: function (rowIndex, rowData) {
                    $('#data').datagrid("unselectAll");
                },
                onUnselectAll: function (rowIndex, rowData) {
                    $("#child-title").hide().next().hide();
                },
                onUncheckAll: function (rowIndex, rowData) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#child-title").hide().next().hide();
                }
            });


            //新增请购单
            $('#add_btn').click(function () {
                window.location.href="<?=Url::to('create')?>"
            });
            //送审
            var cx=[];
            $("#censorship_btn").on("click",function(){
                var rows = $("#data").datagrid('getChecked');
                if(rows.length>1){
                    layer.alert("只能同时选择一条盘点信息送审!",{icon:2,time:5000});
                }else {
                    var id=rows[0].ivt_id;
                    var code=$(rows[0].ivt_code).text();
                    var url="<?=Url::to(['view'],true)?>?id="+id;
                    var type=<?=$downlist['business'][0]['business_type_id']?>;
                    $.fancybox({
                        href: "<?=Url::to(['/warehouse/check-list/reviewer'])?>?type="+type+"&id="+id+"&url="+url+"&code="+code,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:750,
                        height:480,
                        afterClose:function(){
                            location.reload();
                        }
                    });
                }
            });
            //取消
            var dd1=[];
            $(".content").delegate(".operate","click", function () {
                var id = $("#data").datagrid("getSelected");
                $.fancybox({
                    href: "<?=Url::to(['can-rsn'])?>?id=" + id.ivt_id,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 520
                });
            });
            //取消按钮
            var dd=[];
            var dd2=[];
            $("#cancel_btn").on("click",function(){
                var rows = $("#data").datagrid('getChecked');
                    for(var i=0;i<rows.length;i++) {
                        dd.push(rows[i]['ivt_id']);
                    }
                        $.fancybox({
                            href: "<?=Url::to(['can-rsn'])?>?id=" + dd,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 520
                        });

            });
            //修改
            $(".content").delegate(".edit","click", function () {
                var row=$("#data").datagrid("getSelected");
                    var id =row['ivt_id'];
                    var code=$(row.ivt_code).text();
                var url="<?=Url::to(['edit'])?>";
                url+='?id='+id;
                url+='&code='+code;
//                alert(url);
                location.href=url;
            });
            //修改按钮
            var ff=[];
            $("#edit_btn").click(function () {
                var t=[];
                var rows = $("#data").datagrid('getChecked');
                var id = rows[0]['ivt_id'];
                var code = $(rows[0]['ivt_code']).text();
                var url="<?=Url::to(['edit'])?>";
                url+='?id='+id;
                url+='&code='+code;
                location.href=url;
            });
            //盘点单明细表
            $("#detail_btn").click(function () {
                window.location.href="<?=Url::to('detail')?>"
            });
            //添加复盘信息
            $("#addmes_btn").click(function () {
                var rows = $("#data").datagrid('getChecked');
                var id = rows[0]['ivt_id'];
                var code = $(rows[0]['ivt_code']).text();
                var url="<?=Url::to(['add-msg'])?>";
                url+='?id='+id;
                url+='&code='+code;
                $.fancybox({
                    href: url,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 600
                });
//                var url="<?//=Url::to(['add-msg'])?>//";
//                url+='?id='+id;
//                url+='&code='+code;
//                location.href=url;
            });
            //盘点单明细表
            $("#detail_btn").click(function () {
                window.location.href="<?=Url::to('detail')?>"
            });
            //导出
            $('#export_btn').click(function () {
                var index = layer.confirm("确定要导出盘点单信息?",
                    {   fix:false,
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function(){
                        layer.closeAll();
                        var url="<?=Url::to(['index'])?>";
                        url+='?export=1';
                        url+='&ivt_code='+$("#ivt_code").val();
                        url+='&ivt_status='+$("#ivt_status").val();
                        url+='&stage='+$("#stage").val();
                        url+='&legal_code='+$("#legal_code").val();
                        url+='&wh_name='+$("#wh_name").val();
                        url+='&wh_code='+$("#wh_code").val();
                        url+='&start_date='+$("#start_date").val();
                        url+='&end_date='+$("#end_date").val();
                        location.href=url;
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });
        });
    </script>