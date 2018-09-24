<?php
use yii\helpers\Url;
use app\classes\Menu;

    $this->title = '采购单列表';
    $this->params['homeLike'] = ['label' => '采购管理'];
    $this->params['breadcrumbs'][] = ['label' => '采购单列表'];
?>
<style>
    .space-10{
        width: 100%;
        height: 10px;
    }
    .space-30{
        width: 100%;
        height: 30px;
    }
    .mb-5{
        margin-bottom: 5px;
    }
</style>
<div class="content">
    <?=$this->render('_search',['data'=>$data])?>
    <div class="space-20"></div>
    <?php echo $this->render('_action'); ?>
    <div class="table-content">
        <div class="space-10"></div>
        <div id="data"></div>
        <div id="child-title"></div>
        <div id="messages"></div>
    </div>
    <script>
        $(function () {
            $("#edit_btn").hide().next().hide();
            $("#censorship_btn").hide().next().hide();
            $("#cancel_btn").hide().next().hide();
            $("#notice_btn").hide().next().hide();
            var isCheck = false; //是否点击复选框
            var isSelect = false; //是否点击单条
            var onlyOne = false; //是否只选中单个复选框
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "prch_id",
                loadMsg: "加载数据请稍候。。。",
                selectOnCheck:false,
                checkOnSelect:false,
                pagination: true,
                singleSelect:true,
                columns: [[
                    {field: 'ck', checkbox: true},
//                    {field: "prch_no", title: "采购单号",width: 181},
//                    {field: "prch_id", title: "采购id",hidden:true},
//                    {field: "prch_name", title: "采购单状态", width: 100},
//                    {field: "app_date", title: "采购日期", width: 120},
//                    {field: "bsp_svalue", title: "单据类型", width: 100},
//                    {field: "organization_name", title: "采购部门", width: 100},
//                    {field: "staff_name", title: "采购员", width: 100},
//                    {field: "contact_info", title: "联系方式", width: 100},
//                    {field: "company_name", title: "法人", width: 100},
//                    {field: "wh_addr", title: "收货中心", width: 100},
//                    {field: "spp_fname", title: "供应商", width: 100},
//                    {field: "cur_sname", title: "币别", width: 180},
//                    {field: "11", title: "预结报单号", width: 150},
//                    {field: "total_amount", title: "总金额", width: 100},
//                    {field: "yn_three", title: "是否三方交易", width: 100,formatter:function (value,rowData,rowIndex) {
//                        if (value=='1'){
//                            return '是';
//                        }else if(value=='0'){
//                            return '否';
//                        }
//                    }},
                    <?=$data['table']?>
                    {field:'oper',title:'操作',width:60,align:'center',formatter: function (value, rowData, rowIndex) {
                        var str="<div style='text-align: left;margin-left: 10px'><i>";
                        if(rowData.prch_status==40||rowData.prch_status==44||rowData.prch_status==47) {
                            str += "<a class='operate icon-check-minus icon-large' title='取消'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                        }
                        else {
                            str += "";
                        }
                        if(rowData.prch_status==40||rowData.prch_status==44) {
                            str += "<a class='edit icon-edit icon-large'  title='修改'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                        }
                        else {
                            str += "";
                        }
                        str+="</i></div>";
                        return str;
                    }}
                ]],
                onCheck: function (rowIndex, rowData) {
                    var a1 = $("#data").datagrid("getChecked");
                    if (a1.length == 1) {
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            for(var d=0;d<a1.length;d++){
                                if((a1[d]['prch_status']==40)||(a1[d]['prch_status']==44)){
                                    $("#notice_btn").hide().next().hide();
                                    $("#edit_btn").hide().next().hide();
                                    $("#censorship_btn").show().next().show();
                                    $("#cancel_btn").show().next().show();
                                }
                                else if(a1[d]['prch_status']==47){
                                    $("#notice_btn").show().next().show();
                                    $("#cancel_btn").show().next().show();
                                }
                                else if(a1[d]['prch_status']==49){
                                    $("#notice_btn").show().next().show();
                                }
                            }
                            isSelect = false;
                            onlyOne = true;
                            $('#data').datagrid('selectRow', rowIndex);
                        }
                    }
                    else {
                        for(var i=0;i<a1.length;i++) {
                            if ((a1[i]['prch_status'] != 40) && (a1[i]['prch_status'] != 44)) {
                                $("#edit_btn").hide().next().hide();
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#edit_btn").hide().next().hide();
                                $("#censorship_btn").hide().next().hide();
                            }
                        }
                        for(var k=0;k<a1.length;k++) {
                            if((a1[k]['prch_status']!=47)&&(a1[k]['prch_status']!=49)){
                                $("#notice_btn").hide().next().hide();
                                break;
                            }
                            else{
                                $("#notice_btn").show().next().show();
                            }
                        }
                        for(var j=0;j<a1.length;j++) {
                            if((a1[j]['prch_status']!=40)&&(a1[j]['prch_status']!=44) && (a1[j]['prch_status']!=47)){
                                $("#cancel_btn").hide().next().hide();
//                                $("#notice_btn").hide().next().hide();
                                break;
                            }
                            else{
                                $("#cancel_btn").show().next().show();
                            }
                        }
//                        for(var v=0;v<a1.length;v++) {
//                            if((a1[v]['prch_status']!=40)&&(a1[v['prch_status']!=44) && (a1[v]['prch_status']!=47) &&(a1[v]['prch_status']!=49)){
//                                $("#notice_btn").hide().next().hide();
//                                break;
//                            }
//                            else{
//                                $("#notice_btn").show().next().show();
//                            }
//                        }
                        isCheck = true;
                        isSelect = false;
                        onlyOne = false;
                        $('#data').datagrid("unselectAll");
//                        $("#delete,.turn_investment,.turn_sales").removeClass('display-none');
//                        $("#update,#view,#backVisit,#reminders").addClass('display-none');
                    }
                },onUncheck: function (rowIndex, rowData) {
                    var a = $("#data").datagrid("getChecked");
                    if (a.length == 1) {
                        for(var d=0;d<a.length;d++){
                            if((a[d]['prch_status']==40)||(a[d]['prch_status']==44)){
                                $("#edit_btn").show().next().show();
                                $("#censorship_btn").show().next().show();
                                $("#cancel_btn").show().next().show();
                            }
                            else if(a[d]['prch_status']==47){
                                $("#notice_btn").show().next().show();
                                $("#cancel_btn").show().next().show();
                            }
                            else if(a[d]['prch_status']==49){
                                $("#notice_btn").show().next().show();
                            }
                        }
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            isSelect = false;
                            onlyOne = true;
                            var b = $("#data").datagrid("getRowIndex", a[0].prch_id);
                            $('#data').datagrid('selectRow', b);
                        }
                    } else if (a.length == 0) {
                        $("#edit_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
                        $("#cancel_btn").hide().next().hide();
                        $("#notice_btn").hide().next().hide();
                        isCheck = false;
                        isSelect = false;
                        onlyOne = false;
//                    $("#close").hide().next().hide();
//                    $("#delete").hide().next().hide();
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
                        for(var i=0;i<a1.length;i++) {
                            if ((a1[i]['prch_status'] != 40) && (a1[i]['prch_status'] != 44)) {
                                $("#edit_btn").hide().next().hide();
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#edit_btn").hide().next().hide();
                                $("#censorship_btn").hide().next().hide();
                            }
                        }
                        for(var p=0;p<a1.length;p++) {
                            if ((a1[p]['prch_status'] != 47) && (a1[p]['prch_status'] != 49)) {
                                $("#notice_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#notice_btn").show().next().show();
                            }
                        }
                        for(var j=0;j<a1.length;j++) {
                            if((a1[j]['prch_status']!=40)&&(a1[j]['prch_status']!=44) && (a1[j]['prch_status']!=47)){
                                $("#cancel_btn").hide().next().hide();
                                break;
                            }
                            else{
                                $("#cancel_btn").show().next().show();
                            }
                        }
                    }
                },
                onCheckAll: function (rowIndex, rowData) {
                    $('#data').datagrid("unselectAll");
                },
                onLoadSuccess: function (data) {
                    $('#data').datagrid("clearSelections");
                    $('#data').datagrid("clearChecked");
                    datagridTip('#data');
                    showEmpty($(this), data.total, 1);
                },
                onUnselectAll: function (rowIndex, rowData) {
                    $("#child-title").hide().next().hide();
                },
                onUncheckAll: function (rowIndex, rowData) {
                    isCheck = false;
                    isSelect = false;
                    onlyOne = false;
                    $("#child-title").hide().next().hide();
                },
                onSelect: function (rowIndex, rowData) {
                    $("#child-title").show().next().show();
                    var index = $("#data").datagrid("getRowIndex", rowData.prch_id);
//                    var id=rowData.req_id;
                    $('#data').datagrid("uncheckAll");
                    var a1 = $("#data").datagrid("getSelected");
                    var id=a1.prch_id;
                    if((a1.prch_status==40)||(a1.prch_status==44)){
                        $("#edit_btn").show().next().show();
                        $("#censorship_btn").show().next().show();
                        $("#cancel_btn").show().next().show();
                        $("#notice_btn").hide().next().hide();
                    }
                    else if(a1.prch_status==47){
                        $("#edit_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
                        $("#cancel_btn").show().next().show();
                        $("#notice_btn").show().next().show();
                    }
                    else if(a1.prch_status==49) {
                        $("#edit_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
                        $("#cancel_btn").hide().next().hide();
                        $("#notice_btn").show().next().show();
                    }
                    else{
                        $("#edit_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
                        $("#cancel_btn").hide().next().hide();
                        $("#notice_btn").hide().next().hide();
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
                    }).html("<p class='head'>商品信息</p>");
                    $("#messages").datagrid({
                        url: "<?= Url::to(['/purchase/purchase-notify/commodity']) ?>?id="+id,
                        rownumbers: true,
                        method: "get",
                        idField: "prch_dt_id",
                        loadMsg: "加载数据请稍候。。。",
                        pagination: true,
                        singleSelect: false,
                        columns: [[
//                            {field: "req_dt_id", title: "商品详情ID", hidden: true},
//                            {field: "part_no", title: "料号", width: 181},
//                            {field: "pdt_name", title: "品名", width: 181},
//                            {field: "tp_spec", title: "规格", width: 181},
//                            {field: "brand", title: "品牌", width: 181},
//                            {field: "unit", title: "单位", width: 181},
//                            {field: "spp_code", title: "供应商代码", width: 181},
//                            {field: "spp_fname", title: "供应商名称", width: 181},
//                            {field: "price", title: "单价", width: 181},
//                            {field: "goods_condition", title: "交货条件", width: 181},
//                            {field: "pay_condition", title: "付款条件", width: 181},
//                            {field: "prch_num", title: "采购数量", width: 181},
//                            {field: "total_amount", title: "金额", width: 181},
////                            {field: "bsp_svalue", title: "付款方式", width: 181},
//                            {field: "tax", title: "税别/税率", width: 181},
//                            {field: "cur_code", title: "币别", width: 181},
//                            {field: "deliv_date", title: "交货日期", width: 181},
//                            {field: "req_no", title: "关联单号", width: 181}
                            <?=$data['table1']?>
                        ]],
                        onLoadSuccess: function (data) {
                            datagridTip("#messages");
//                    showEmpty($(this), data.total, 0);
                            setMenuHeight();

                        }
                    })
                }
            });
            //生成收货通知单
            $("#notice_btn").on("click",function(){
                var arr = [];
               // var areaid=null;
                var obj = $("#data").datagrid("getChecked");
                for (var i = 0; i < obj.length; i++) {
                    arr.push(obj[i].prch_id);
                }
                var id = arr.join(',');
                    $.fancybox({
                        href:"<?=Url::to(['notice-new'])?>?id="+id,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:650,
                        height:520
                    });
            });
            //取消按钮
            var dd=[];
            $("#cancel_btn").on("click",function(){
                var rows = $("#data").datagrid('getChecked');
                if(rows=="") {
                    layer.alert("请选择一条或多条请购信息!",{icon:2,time:5000});
                } else{
                    for(var i=0;i<rows.length;i++){
                        dd.push(rows[i]['prch_id']);
                    }
                    $.fancybox({
                        href:"<?=Url::to(['can-rsn'])?>?id="+dd,
                        type:"iframe",
                        padding:0,
                        autoSize:false,
                        width:800,
                        height:520
                    });
                }
            });
            //取消操作
            $(".content").delegate(".operate","click", function () {
                var id = $("#data").datagrid("getSelected");
                if(id.prch_status==40||id.prch_status==44||id.prch_status==47) {
                    if (id.yn_can == 1) {
                        layer.alert("该请购已经取消,无法重复取消!", {icon: 2, time: 5000});
                        return false;
                    } else if (id.prch_status != 40 && id.prch_status != 44 && id.prch_status != 47) {
                        layer.alert("该请购无法取消!", {icon: 2, time: 5000});
                        return false;
                    }
                    else {
                        $.fancybox({
                            href: "<?=Url::to(['can-rsn'])?>?id=" + id.prch_id,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 520
                        });
                    }
                }
            });
            //修改按钮
            $("#edit_btn").click(function () {
                var rows = $("#data").datagrid('getChecked');
//                var id = $("#data").datagrid("getSelection")['req_id'];
                if(rows=="") {
                    layer.alert("请选择一条请购信息!",{icon:2,time:5000});
                }
                else if (rows.length>1){
                    layer.alert("只能同时修改一个请购单!",{icon:2,time:5000});
                }
                else {
                    var id = rows[0].prch_id;
                    window.location.href = "<?=Url::to('update')?>?id="+id;
                }
            });
            //修改操作
            $(".content").delegate(".edit","click", function () {
                var row=$("#data").datagrid("getSelected");
                if(row.prch_status==40||row.prch_status==44) {
                    var id = row.prch_id;
                    window.location.href = "<?=Url::to('update')?>?id=" + id;
                }
            });

            //导出
            $('#export_btn').click(function () {
                var index = layer.confirm("确定要导出采购信息?",
                    {   fix:false,
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function () {
                        layer.closeAll();
                        var url="<?=Url::to(['index'])?>";
                        url+='?export=1';
                        url+='&req_dct='+$("#req_dct").val();
                        url+='&yn_three='+$("#yn_three").val();
                        url+='&wh_addr='+$("#wh_addr").val();
                        url+='&leg_id='+$("#leg_id").val();
                        url+='&prch_no='+$("#prch_no").val();
                        url+='&spp_fname='+$("#spp_fname").val();
                        url+='&prch_status='+$("#prch_status").val();
                        url+='&start_date='+$("#start_date").val();
                        url+='&end_date='+$("#end_date").val();
                        location.href=url;
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });

            // 采购通知
            $("#purchase-note").click(function () {
                var a = $("#data").datagrid("getSelected");
                if (a == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                $.fancybox({
                    href: "<?=Url::to(['purchase-note?id='])?>" + a.soh_id,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 520,
                    'onCancel':function() {
                        layer.alert("该订单没有可发送的通知!", {icon: 2, time: 5000});
                    },
                });
            });

            // 出货通知
            $("#out-note").click(function () {
                var a = $("#data").datagrid("getSelected");
                if (a == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                $.fancybox({
                    href: "<?=Url::to(['out-note?id='])?>" + a.soh_id,
                    type: "iframe",
                    padding: 0,
                    autoSize: false,
                    width: 800,
                    height: 520,
                    'onCancel':function() {
                        layer.alert("该订单没有可发送的通知!", {icon: 2, time: 5000});
                    }
                });
            });

            // 生成拣货单
            $('#create-pick').click(function(){
                var a = $("#data").datagrid("getSelected");
                if (a == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                $.ajax({
                    type:'post',
                    dataType:'json',
//                    data:$("#note-form").serialize(),
                    url:"<?= \yii\helpers\Url::to(['create-pick?id='])?>" + a.sonh_id,
                    success:function(data){
                        if(data.status == 1){
                            layer.alert("生成拣货单成功！",{icon:1,end: function () {
                                parent.window.location.reload();
//                                $("#data").datagrid('reload');
                            }});
                        } else {
                            layer.alert(data.msg,{icon:1,end: function () {

                            }});
                        }
                    },
                    error: function (data) {
                    }
                })
            });

            // 点击取消通知按钮
            $('#cancel-notify').click(function(){
                var a = $("#data").datagrid("getSelected");
                if (a == null) {
                    layer.alert("请点击选择一条订单信息!", {icon: 2, time: 5000});
                    return false;
                }
                notifyCancel(a.ponh_id);
            })
        });

        // 取消通知
        function notifyCancel(id) {
            $.fancybox({
                type:"iframe",
                padding:0,
                width:480,
                height:300,
                href:"<?=Url::to(['cancel-notify'])?>?id=" + id,
            });
        }
    </script>