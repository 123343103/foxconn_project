<?php
/**
 * Created by PhpStorm.
 * User: F1677978
 * Date: 2017/6/9
 * Time: 上午 09:10
 */
use yii\helpers\Url;

$this->params['homeLike'] = ['label'=>'仓储物流管理','url'=>''];
$this->params['breadcrumbs'][] = ['label'=>'库存预警人员列表'];
$this->title = '库存预警人员查询列表';
?>
<div class="content">
    <?php  echo $this->render('_search', [
        'search' => $search,
        'StaffCode'=>$StaffCode,
        'opper'=>$opper,
        'params'=>$params
    ]); ?>
    <div class="table-content">
        <div class="table-head">
            <p class="head">库存预警/报废通知人员列表</p>
            <div class="float-right">
                <a id="add">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="add-item-bgc " style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;新增</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
                <a id="update">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="update-item-bgc " style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;修改</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
                <a id="check">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="submit-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;送审</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
                <a id="sendemail">
                    <div style="height: 23px;width: 67px;float: left;">
                        <p class="send-email-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;发邮件</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
                <a id="export">
                    <div style="height: 23px;width: 55px;float: left;">
                        <p class="export-item-bgc" style="float: left"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;导出</p>
                    </div>
                </a>
                <p style="float: left;">&nbsp;|&nbsp;</p>
                <a id="return">
                    <div style="height: 23px;width: 55px;float: left">
                        <p class="return-item-bgc" style="float: left;"></p>
                        <p style="font-size: 14px;margin-top: 2px;">&nbsp;返回</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="space-10"></div>
        <div style="clear: right;"></div>
        <?php  echo $this->render('_action',['search' => $search,'model'=>$model]); ?>
        <div id="data"></div>
    </div>
    <div class="space-20"></div>
    <div id="visit-data"></div>
</div>

<script>
    $(function() {
        var flag=true;
        $("#data").datagrid({
            url: "<?=Yii::$app->request->getHostInfo() . Yii::$app->request->url?>",
            rownumbers: true,
            method: "get",
            idField: "inv_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            singleSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            columns: [[
//                {field: 'ck',checkbox:true},
//                {field:"ck",checkbox:true,width:200},
                {field: "LIW_PKID", title: "关系ID", width: 120,hidden:'true'},
                {field: "staff_code", title: "工号", width: 120},
                {field: "staff_name", title: "姓名", width: 120},
                {field: "staff_mobile", title: "手机", width: 180},
                {field: "so_type", title: "状态", width: 100,formatter: function (value, row) {
                    if(row.so_type=="10"){
                      return "待提交"
                    }else if(row.so_type=="20"){
                        return "审核中"
                    }else if(row.so_type=="40"){
                        return "审核完成"
                    } else{
                        return "驳回"
                    }
                }},
                {field: "OPPER", title: "操作人", width: 80},
                {field: "staff_email", title: "邮箱", width: 180},
                {field: "OPP_DATE", title: "最后操作日期", width: 100},
                {field: "name", title: "操作", width: 150,formatter:function(val,row){
                    var deletestaff="<span class='width-30'></span>";
                    var updatestaff="<span class='width-30'></span>";
                    if(row.so_type=="10"||row.so_type=="40"){
                        deletestaff = "<a onclick='deleteRole()' class='ml-20'><i class='icon-trash icon-l' title='删除'></i></a>";
                        updatestaff= "<a class='ml-20 update' data-id='"+row.LIW_PKID+"'  title='修改'><i class='icon-edit icon-l'></i></a>"
                    }
                    return "<a class='ml-20 viweone'  title='查看'   data-id='"+row.LIW_PKID+"'><i class='icon-eye-open icon-l'></i></a>" +updatestaff
                       +deletestaff
                }},
            ]],
            //onclick='viewOne("+row.staff_code+")'
            onLoadSuccess: function (data) {
                $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');

                $("#data").datagrid("unselectAll");
                $("#data").datagrid("uncheckAll");
                datagridTip("#data");
                showEmpty($(this),data.total,1);
            },
            "onSelect":function(index,row){
                $("#data").datagrid("uncheckAll");
                $("#data").datagrid("checkRow",index);
                var status=row["so_type"];
                if(status == 20){//20为审核中
                    $("#update ").hide();
                    $("#update").prev().hide();
                    $("#check").hide();
                    $("#check").prev().hide();
                }else {
                    $("#update").show();
                    $("#update").prev().show();
                    $("#check").show();
                    $("#check").prev().show();
                }
                if(status==40){//审核通过
                    $("#sendemail").show();
                    $("#sendemail").prev().show();
                }else{
                    $("#sendemail").hide();
                    $("#sendemail").prev().hide();
                }
                if(flag==false){
                    return false;
                }
                $("#visit-data").css("visibility","visible");
                var row=$("#data").datagrid("getSelected");
                var LIW_PKID=row.LIW_PKID;
              // console.log(LIW_PKID);
                $("#visit-data").datagrid({
                    url: "<?=Url::to(['productinfo']);?>?SetInventoryWarningSearch[LIW_PKID]="+LIW_PKID,
                    rownumbers: true,
                    method: "get",
                    idField: "staff_code",
                    loadMsg: "加载数据请稍候。。。",
                    pagination:true,
                    pageSize:5,
                    pageList:[5,10,15],
                    singleSelect: true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    columns:[[
//                        {field:"sil_code",title:"编号",width:200,formatter:function(value,row,index){
//                            return "<a href='<?//=Url::to(['crm-member-develop/view'])?>//?id="+custId+"&childId="+row.sil_id+"&ctype=4'>"+row.sil_code+"</a>";
//                        }},
                        {field:"wh_name",title:"仓库",width:100},
                        {field:"category_sname",title:"商品类别",width:200},
                        {field:"part_no",title:"料号",width:200},
                        {field:"pdt_name",title:"商品名称",width:200},
                        {field:"BRAND_NAME_CN",title:"品牌",width:200},
                        {field:"pdt_model",title:"规格型号",width:200},
                        {field:"up_nums",title:"库存上限",width:200},
                        {field:"invt_num",title:"现有库存",width:200},
                        {field:"down_nums",title:"库存下限",width:200},

                    ]],
                    onLoadSuccess: function (data) {
                        $('.datagrid-header-row td').eq(0).html('&nbsp;序号&nbsp;').css('color','white');
                        showEmpty($(this),data.total,0);

                    }
                });
            }

        });

        //編輯
        $("#update").on("click",function(){
            var getSelected = $("#data").datagrid("getSelected");
            if(getSelected == null){
                layer.alert("请点击选择一条人员信息!",{icon:2,time:5000});
            }else{
                var id = getSelected['LIW_PKID'];
                window.location.href = "<?=Url::to(['update'])?>?id=" + id;
            }
        });
        $("#export").on("click",function () {
            var obj=$("#data").datagrid('getData');
            if(obj.total==0){
                layer.alert('不可执行导出！',{icon:2,time:5000});
                return false;
            }
            layer.confirm('确定导出吗？',{icon:2},
                function(){
                    layer.closeAll();
                    window.location.href="<?=Url::to(['export']).'?'.http_build_query(Yii::$app->request->queryParams)?>";
                },
                layer.close()
            );
        })

        //詳情
//        $("#viewOne").on("click",function(){
//            var a = $("#data").datagrid("getSelected");
//            if(a == null){
//                layer.alert("请点击选择一条人员信息!",{icon:2,time:5000});
//            }else{
//                var id = $("#data").datagrid("getSelected")['staff_code'];
//                window.location.href = "<?//=Url::to(['view'])?>//?id=" + id;
//            }
//        });
        //新增
        $("#add").on("click",function () {
            window.location.href = "<?=Url::to(['create'])?>";
        })
        $("#check").on("click",function () {
            var data = $("#data").datagrid("getSelected");
            if (data == null) {
                layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }
            var code=data['staff_code'];
            var id=data['LIW_PKID'];
            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {"code": code},
                url: "<?=Url::to(['/warehouse/set-inventory-warning/get-staff-check']) ?>",
                success: function (data) {
                    if (data.length>0) {
                        return layer.alert("该预警人员已有送审信息！", {icon: 2, time: 5000});
                    }else{
                        var url="<?=Url::to(['view'],true)?>?id="+id;
                        var type=46;
                        $.fancybox({
                            href:"<?=Url::to(['/system/verify-record/reviewer'])?>?type="+type+"&id="+id+"&url="+url,
                            type:"iframe",
                            padding:0,
                            autoSize:false,
                            width:750,
                            height:480
                        });
                    }
                }
            });
            if (data == null) {
                layer.alert("请点击选择一条申请信息", {icon: 2, time: 5000});
                return false;
            }

        });
        //返回
        $("#return").on("click",function () {
            window.location.href = "<?=Url::to(['/index/index'])?>";
        })
        //发邮件
        $("#sendemail").on("click",function () {
            var data = $("#data").datagrid("getSelected");
            if(data == null){
                layer.alert("请点击选择一条人员信息!",{icon:2,time:5000});
            }else if(data["staff_email"]==""||data["staff_email"]==null){
                layer.alert("请完善人员邮箱信息!",{icon:2,time:5000});
            }else {
                //var emaddress = data["staff_email"];
                var id=data["LIW_PKID"];
                $.fancybox({
                    width: 600,
                    height: 600,
                    padding: 0,
                    autoSize: false,
                    type: "iframe",
                    href: "<?=Url::to(['send-email'])?>?id="+id
                });
            }
        })
    });
    //返回
    //删除
function deleteRole() {
    var row = $('#data').datagrid('getSelected');
    if (row) {
        var rowIndex = $('#data').datagrid('getRowIndex', row);
        $('#data').datagrid('deleteRow', rowIndex);
    }
}
$(".table-content").delegate(".viweone","click",function () {
    window.location.href = "<?=Url::to(['view'])?>?id=" + $(this).data("id");
})
$(".table-content").delegate(".update","click",function () {
    window.location.href = "<?=Url::to(['update'])?>?id=" + $(this).data("id");
})


</script>

