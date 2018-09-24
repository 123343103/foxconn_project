<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/16
 * Time: 下午 03:44
 */

use app\assets\JqueryUIAsset;
use yii\helpers\Url;
JqueryUIAsset::register($this);
?>
<?php $form=\yii\widgets\ActiveForm::begin([
        "id"=>"remind-form"
]);?>
<div id="remind-box">
    <h2 class="head-first">新增提醒</h2>
    <div style="padding:10px;">
        <input id="cust_id" type="hidden" name="CrmImessage[cust_id]">
        <input type="hidden" name="CrmImessage[imesg_sentman]" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
        <div class="mb-20">
            <label class="width-80" for="">公司名称</label>
            <input id="cust_sname" type="text" class="width-130" disabled>
            <a id="cust_selector" href="javascript:void(0)">选择客户</a>
            <label class="width-100 ml-140" for="">公司简称</label>
            <input id="cust_shortname" type="text" class="width-130" disabled>
        </div>
        <div class="mb-20">
            <label class="width-80" for="">联系人</label>
            <input id="cust_contacts" type="text" class="width-130" disabled>
            <label class="width-80" for="">手机号码</label>
            <input id="cust_tel2" type="text" class="width-130" disabled>
            <label class="width-80" for="">公司电话</label>
            <input id="cust_tel1" type="text" class="width-130" disabled>
        </div>
        <div class="mb-20">
            <label class="width-80" for="">提醒给</label>
            <select class="width-130 easyui-validatebox" name="CrmImessage[imesg_receiver]"
                    data-options="required:'true'">
                <option value="">请选择...</option>
                <?php foreach ($employee as $key => $val) { ?>
                    <option
                            value="<?= $val['staffName']['staff_id'] ?>"><?= $val['staffName']['staff_code'] ?>--<?= $val['staffName']['staff_name'] ?></option>
                <?php } ?>
            </select>

            <label class="width-80" for="">开始时间</label>
            <input id="startDate" name="CrmImessage[imesg_btime]" type="text" class="width-130 select-date-time easyui-validatebox" data-target="#endDate" data-type="le" data-options="required:'true',validType:'timeCompare'">
            <label class="width-80" for="">结束时间</label>
            <input id="endDate" name="CrmImessage[imesg_etime]" type="text" class="width-130 select-date-time easyui-validatebox" data-target="#startDate" data-type="ge" data-options="required:'true',validType:'timeCompare'">
        </div>
        <div class="mb-20">
            <label style="vertical-align: top;" class="width-80" for="">提醒内容</label>
            <textarea name="CrmImessage[imesg_notes]" style="width:450px;height:100px;" maxlength="400" class="easyui-validatebox" data-options="required:'true'"></textarea>
        </div>
        <div class="mb-20">
            <label class="width-80" for="">状态</label>
            <select class="width-100" name="CrmImessage[imesg_status]" id="">
                <option value="1">激活</option>
                <option value="2">结束</option>
            </select>
        </div>
        <div class="text-center mt-20">
            <button type="submit" class="button-blue ensure">确定</button>
            <button type="button" class="button-white cancel">取消</button>
        </div>
    </div>
</div>
<?php $form->end();?>
<div id="cust_select_box" style="display: none;">
    <h2 class="head-first">选择客户</h2>
    <div style="padding:10px;">
        <div class="mb-10">
            <input class="kwd" name="kwd" type="text" placeholder="请输入客户编号或公司名称">
            <button class="button-blue search">搜索</button>
        </div>
        <div id="cust_data"></div>
        <div class="mt-10 text-center">
            <button class="button-blue ensure">确定</button>
            <button class="button-white ml-10 cancel">取消</button>
        </div>
    </div>
</div>
<script>
    $(function(){
        var row=parent.$("#data").datagrid("getSelected");
        if(row){
            $("#cust_id").val(row.cust_id);
            $("#cust_sname").val(row.cust_sname.decode());
            $("#cust_shortname").val(row.cust_shortname.decode());
            $("#cust_contacts").val(row.cust_contacts.decode());
            $("#cust_tel2").val(row.cust_tel2);
            $("#cust_tel1").val(row.cust_tel1);
        }




        //新增提醒ajax表单
        ajaxSubmitForm($("#remind-form"),'',function(res){
            parent.layer.alert(res.msg,{icon:1});
            parent.$.fancybox.close();
            parent.$("#remind-data").datagrid("reload");
        });


        $("#cust_data").datagrid({
            url:"<?=Url::to(['index'])?>",
            pagination:true,
            pageSize:5,
            pageList:[5,10,15],
            method: "get",
            singleSelect:true,
            columns:[[
                {field:"ck",checkbox:true,width:50},
                {field:"cust_filernumber",title:"客户编号",width:200},
                {field:"cust_sname",title:"公司名称",width:200},
            ]],
            onLoadSuccess:function(data){
                datagridTip($("#cust_data"));
            }
        });

        $("#cust_selector").click(function(){
            $.fancybox({
                width:450,
                height:400,
                href:"#cust_select_box",
                padding:0,
                autoSize:false
            });
            $("#cust_data").datagrid("resize");
        });

        $("#cust_select_box .ensure").click(function(){
            var row=$("#cust_data").datagrid("getSelected");
            $("#cust_id").val(row.cust_id);
            $("#cust_sname").val(row.cust_sname.decode());
            $("#cust_shortname").val(row.cust_shortname);
            $("#cust_contacts").val(row.cust_contacts);
            $("#cust_tel2").val(row.cust_tel2);
            $("#cust_tel1").val(row.cust_tel1);
            $.fancybox.close();
        });
        $("#cust_select_box .cancel").click(function(){
            $.fancybox.close();
        });

        $("#cust_select_box .search").click(function(){
            $("#cust_data").datagrid("reload",{
                keywords:$("#cust_select_box .kwd").val()
            });
        });
        //关闭弹窗
        $("#remind-box .cancel").click(function(){
            parent.$.fancybox.close();
        });
    });

    //获取提醒人信息
    function setStaffInfo(obj) {
        var url = "<?= \yii\helpers\Url::to(['/hr/staff/get-staff-info'])?>";
        staffInfo(obj, url);
    }
</script>


<style type="text/css">
    textarea{
        width:100%;
    }
    button{
        font-size: 12px;
    }
    button:hover {
        cursor: pointer;
        border: 1px solid #0e0e0e;
    }
</style>