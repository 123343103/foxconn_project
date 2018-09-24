<?php
use yii\helpers\Url;
use app\classes\Menu;

    $this->title = '商品请购单列表';
    $this->params['homeLike'] = ['label' => '采购管理'];
    $this->params['breadcrumbs'][] = ['label' => '商品请购单列表'];
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
    <?=$this->render('_search',['data'=>$data,'downlist'=>$downList])?>
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
            $("#censorship_btn").hide().next().hide();
            $("#cancel_btn").hide().next().hide();
            var id;
            var isCheck = false; //是否点击复选框
            var isSelect = false; //是否点击单条
            var onlyOne = false; //是否只选中单个复选框
            $("#data").datagrid({
                url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
                rownumbers: true,
                method: "get",
                idField: "req_id",
                loadMsg: "加载数据请稍候。。。",
                selectOnCheck:false,
                checkOnSelect:false,
                pagination: true,
                singleSelect:true,
                columns: [[
                    {field: 'ck', checkbox: true},
//                    {field: "req_no", title: "请购单号",width: 181},
//                    {field: "prch_name", title: "请购单状态", width: 100},
//                    {field: "company_name", title: "法人", width: 120},
//                    {field: "organization_name", title: "请购部門", width: 180},
//                    {field: "staff_name", title: "申请人", width: 100},
//                    {field: "dd", title: "采购部门", width: 100},
//                    {field: "ff", title: "总金额", width: 100},
//                    {field: "cur_code", title: "币别", width: 100},
//                    {field: "bsp_svalue", title: "单据类型", width: 180},
//                    {field: "aa", title: "请购形式", width: 180},
//                    {field: "cc", title: "采购方式", width: 150},
//                    {field: "bb", title: "采购区域", width: 100},
//                    {field: "app_date", title: "申请日期", width: 150},
//                    {field: "yn_can", title: "是否取消",hidden:true},
                    <?=$data['table2']?>
                    {field:'oper',title:'操作',width:60,align:'center',formatter: function (value, rowData, rowIndex) {
                        var str="<div style='text-align: left;margin-left: 9px'><i>";
                        if(rowData.req_status==30||rowData.req_status==34) {
                            str += "<a  class='operate icon-check-minus  icon-large' title='取消'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                        }else{
                            str += "";
                        }
                        if(rowData.req_status==30||rowData.req_status==34) {
                            str += "<a class='edit icon-edit icon-large'  title='修改'>&nbsp;&nbsp;&nbsp;&nbsp;</a>";
                        }else{
                            str += "";
                        }
                        str+="</i></div>";
                        return str;
                    }}
                ]],
//                onLoadSuccess: function (data) {
//                    datagridTip("#data");
////                    showEmpty($(this), data.total, 0);
//                    setMenuHeight();
//
//                },
                onCheck: function (rowIndex, rowData) {
                    var a1 = $("#data").datagrid("getChecked");
                    if (a1.length == 1) {
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            for(var d=0;d<a1.length;d++){
                                if((a1[d]['req_status']==30)||(a1[d]['req_status']==34)){
                                    $("#edit_btn").show().next().show();
                                    $("#censorship_btn").show().next().show();
                                    $("#cancel_btn").show().next().show();
                                }
//                                else if(a1[d]['req_status']==38){
//                                    $("#cancel_btn").show().next().show();
//                                }
                            }
                            isSelect = false;
                            onlyOne = true;
                            $('#data').datagrid('selectRow', rowIndex);
                        }
                    }
                    else {
                        for(var i=0;i<a1.length;i++) {
                            if ((a1[i]['req_status'] != 30) && (a1[i]['req_status'] != 34)) {
                                $("#edit_btn").hide().next().hide();
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#edit_btn").show().next().show();
                                $("#censorship_btn").hide().next().hide();
                            }
                        }
//                            for(var j=0;j<a1.length;j++) {
//                             if((a1[j]['req_status']!=38)&&(a1[j]['req_status']!=30) && (a1[j]['req_status']!=34)){
//                                $("#cancel_btn").hide().next().hide();
//                                break;
//                            }
//                            else{
//                                 $("#cancel_btn").show().next().show();
//                             }
//                            }
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
                            if((a[d]['req_status']==30)||(a[d]['req_status']==34)){
                                $("#edit_btn").show().next().show();
                                $("#censorship_btn").show().next().show();
                                $("#cancel_btn").show().next().show();
                            }
//                            else if(a[d]['req_status']==38){
//                                $("#cancel_btn").show().next().show();
//                            }
                        }
                        isCheck = true;
                        if (isCheck && !isSelect) {
                            isSelect = false;
                            onlyOne = true;
                            var b = $("#data").datagrid("getRowIndex", a[0].req_id);
                            $('#data').datagrid('selectRow', b);
                        }
                    } else if (a.length == 0) {
                        $("#edit_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
                        $("#cancel_btn").hide().next().hide();
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
                            if ((a1[i]['req_status'] != 30) && (a1[i]['req_status'] != 34)) {
                                $("#edit_btn").hide().next().hide();
                                $("#censorship_btn").hide().next().hide();
                                break;
                            }
                            else {
                                $("#edit_btn").show().next().show();
                                $("#censorship_btn").hide().next().hide();
                            }
                        }
//                        for(var j=0;j<a1.length;j++) {
//                            if((a1[j]['req_status']!=38)&&(a1[j]['req_status']!=30) && (a1[j]['req_status']!=34)){
//                                $("#cancel_btn").hide().next().hide();
//                                break;
//                            }
//                            else{
//                                $("#cancel_btn").show().next().show();
//                            }
//                        }
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
                    var index = $("#data").datagrid("getRowIndex", rowData.req_id);
//                    var id=rowData.req_id;
                    $('#data').datagrid("uncheckAll");
                    var a1 = $("#data").datagrid("getSelected");
                    var id=a1.req_id;
                    if((a1.req_status==30)||(a1.req_status==34)){
                        $("#edit_btn").show().next().show();
                        $("#censorship_btn").show().next().show();
                        $("#cancel_btn").show().next().show();
                    }
//                    else if(a1.req_status==38){
//                        $("#edit_btn").hide().next().hide();
//                        $("#censorship_btn").hide().next().hide();
//                        $("#cancel_btn").show().next().show();
//                    }
                    else{
                        $("#edit_btn").hide().next().hide();
                        $("#censorship_btn").hide().next().hide();
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
                        }).html("<p class='head'>商品信息</p>");
                        $("#messages").datagrid({
                            url: "<?= Url::to(['/purchase/purchase-apply/commodity']) ?>?id=" + id,
                            rownumbers: true,
                            method: "get",
                            idField: "req_dt_id",
                            loadMsg: "加载数据请稍候。。。",
                            pagination: true,
                            singleSelect: false,
                            columns: [[
//                                {field: "req_dt_id", title: "商品详情ID", hidden: true},
//                                {field: "part_no", title: "料号", width: 181},
//                                {field: "pdt_name", title: "品名", width: 181},
//                                {field: "tp_spec", title: "规格", width: 181},
//                                {field: "brand", title: "品牌", width: 181},
//                                {field: "unit", title: "单位", width: 181},
//                                {field: "req_nums", title: "请购量", width: 181},
////                                {field: "quote_price", title: "单价", width: 181},
////                                {field: "spp_id", title: "供应商代码", width: 181},
////                                {field: "total_amount", title: "金额", width: 181},
//                                {field: "bsp_svalue", title: "费用科目", width: 181},
//                                {field: "req_date", title: "需求日期", width: 181},
//                                {field: "prj_no", title: "专案编号", width: 181},
////                                {field: "11", title: "剩余预算", width: 181},
////                                {field: "org_price", title: "原币单价", width: 181},
////                                {field: "rebat_rate", title: "退税率", width: 181},
//                                {field: "prch_no", title: "采购单号", width: 181},
//                                {field: "state", title: "领用状态", width: 181},
//                                {field: "remarks", title: "备注", width: 181}
                                <?=$data['table3']?>
                            ]],
                            onLoadSuccess: function (messages) {
//                                var o= messages.rows.length;
//                                for(var l=0;l<o;l++) {
//                                var _aa=messages.rows[l].prch_no;
//                                    if (_aa!="" && _aa!=null) {
//                                        $("#cancel_btn").hide().next().hide();
//                                        $("#edit_btn").hide().next().hide();
//                                         break;
//                                    }
//                                }
//                            $('#data').datagrid("clearSelections");
//                            $('#data').datagrid("clearChecked");
                                datagridTip('#messages');
                                showEmpty($(this), data.total, 1);
                            }
//                        onLoadSuccess: function (data) {
//                            datagridTip("#messages");
//                    showEmpty($(this), data.total, 0);
//                            setMenuHeight();

//                        }
                        });
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
                    layer.alert("只能选择一条请购信息送审!",{icon:2,time:5000});
                }else {
                    for (var i = 0; i < rows.length; i++) {
                        cx.push(rows[i]['req_id']);
                    }
                    var id=cx;
                    var url="<?=Url::to(['index'],true)?>";
                    var type=54;
                    $.fancybox({
                        href: "<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
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
            //取消按钮
            var dd=[];
            var dd2=[];
            $("#cancel_btn").on("click",function(){
                var tt=[];
                var rows = $("#data").datagrid('getChecked');
                for (var h=0;h<rows.length;h++) {
                    tt.push(rows[h]['req_id']);
                }
                <?php foreach($data['com'] as $key=>$val){?>
                    dd2.push(<?=$val['req_id']?>);
                    dd1.push("<?=$val['prch_no']?>");
                <?php }?>
//                alert (dd);
                for(var s=0;s<rows.length;s++) {
                    for (var m = 0; m < dd2.length; m++) {
                        if (tt[s] == dd2[m]) {
//                            alert(dd2[m]);
//                            alert(dd1[m]);
                            if(dd1[m]!="") {
                                layer.alert("该请购存在未处理的采购信息!", {icon: 2, time: 5000});
                                return false;
                            }
                        }
                    }
                }
                if(rows=="") {
                    layer.alert("请选择一条或多条请购信息!",{icon:2,time:5000});
                }
                else{
                    for(var i=0;i<rows.length;i++){
                        dd.push(rows[i]['req_id']);
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
           //修改按钮
            var ff=[];
            $("#edit_btn").click(function () {
                var t=[];
                var rows = $("#data").datagrid('getChecked');
                for (var h=0;h<rows.length;h++) {
                    t.push(rows[h]['req_id']);
                }
                <?php foreach($data['com'] as $key=>$val){?>
                dd2.push(<?=$val['req_id']?>);
                dd1.push("<?=$val['prch_no']?>");
                <?php }?>
                for(var s=0;s<rows.length;s++) {
                    for (var m = 0; m < dd2.length; m++) {
                        if (t[s] == dd2[m]) {
                            if(dd1[m]!="") {
                                layer.alert("该请购存在未处理的采购信息!", {icon: 2, time: 5000});
                                return false;
                            }
                        }
                    }
                }
                if(rows=="") {
                    layer.alert("请选择一条请购信息!",{icon:2,time:5000});
                }
                else if (rows.length>1){
                    layer.alert("只能同时修改一个请购单!",{icon:2,time:5000});
                }
                else {
                    var id = rows[0]['req_id'];
                    window.location.href = "<?=Url::to('edit')?>?id="+id;
                }
            });
            //修改
            $(".content").delegate(".edit","click", function () {
                var row=$("#data").datagrid("getSelected");
                <?php foreach($data['com'] as $key=>$val){?>
                dd.push(<?=$val['req_id']?>);
                dd1.push("<?=$val['prch_no']?>");
                <?php }?>
//                alert (dd);
                for (var m=0;m<dd.length;m++){
                    if (row.req_id==dd[m]){
                        if (dd1[m]!=""){
                            layer.alert("存在未处理的采购信息!", {icon: 2, time: 5000});
                            return false;
                        }
                    }
                }
                if(row.req_status==30||row.req_status==34) {
                    var id = $("#data").datagrid("getSelected")['req_id'];
                    window.location.href = "<?=Url::to('edit')?>?id=" + id;
                }
            });

            //导出
            $('#export_btn').click(function () {
                var index = layer.confirm("确定要导出请购信息?",
                    {   fix:false,
                        btn: ['确定', '取消'],
                        icon: 2
                    },
                    function(){
                        layer.closeAll();
                        var url="<?=Url::to(['index'])?>";
                        url+='?export=1';
                        url+='&req_no='+$("#req_no").val();
                        url+='&req_dct='+$("#req_dct").val();
                        url+='&area_id='+$("#area_id").val();
                        url+='&leg_id='+$("#leg_id").val();
                        url+='&req_dpt_id='+$("#req_dpt_id").val();
                        url+='&req_status='+$("#req_status").val();
                        url+='&start_date='+$("#start_date").val();
                        url+='&end_date='+$("#end_date").val();
                        url+='&req_rqf='+$("#req_rqf").val();
                        location.href=url;
                    },
                    function () {
                        layer.closeAll();
                    }
                )
            });
            //取消
            var dd1=[];
            $(".content").delegate(".operate","click", function () {
                var id = $("#data").datagrid("getSelected");
                <?php foreach($data['com'] as $key=>$val){?>
                dd.push(<?=$val['req_id']?>);
                dd1.push("<?=$val['prch_no']?>");
                <?php }?>
//                alert (dd);
                for (var m=0;m<dd.length;m++){
                    if (id.req_id==dd[m]){
                            if (dd1[m]!=""){
                                layer.alert("该请购存在未处理的采购信息!", {icon: 2, time: 5000});
                                return false;
                            }
                    }
                        }
                if(id.req_status==30||id.req_status==34||id.req_status==38) {
                    if (id.yn_can == 1) {
                        layer.alert("该请购已经取消,无法重复取消!", {icon: 2, time: 5000});
                        return false;
                    } else if (id.req_status != 30 && id.req_status != 34 && id.req_status != 38) {
                        layer.alert("该请购无法取消!", {icon: 2, time: 5000});
                        return false;
                    }
                    else {
                        $.fancybox({
                            href: "<?=Url::to(['can-rsn'])?>?id=" + id.req_id,
                            type: "iframe",
                            padding: 0,
                            autoSize: false,
                            width: 800,
                            height: 520
                        });
                    }
                }
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
                    },
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