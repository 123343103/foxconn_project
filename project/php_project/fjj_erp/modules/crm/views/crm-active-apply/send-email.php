<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/2/16
 * Time: 下午 03:44
 */

use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div id="emailbox">
    <h2 class="head-first">发邮件</h2>
    <div style="padding:10px;">
        <div style="max-height: 300px;">
            <div id="emailbox-custom-has-add-wrap">
                <p class="mb-10">
                    已选择的客户
                    <span class="pull-right">
<!--                        <button type="button" id="emailbox-custom-add-btn" class="button-blue">继续添加</button>-->
                        <button type="button" id="emailbox-custom-del-btn" class="button-white">删除行</button>
                    </span>
                </p>
                <div style="max-height: 250px;overflow-y:auto;overflow-x: hidden;">
                    <div style="width:100%;" id="emailbox-custom-has-add-data"></div>
                </div>
            </div>
            <div id="emailbox-custom-add-wrap" style="display: none;">
                <p class="mb-10">
                    添加客户
                    <span class="pull-right">
                        <button type="button" id="emailbox-custom-add-ensure" class="button-blue">确定</button>
                        <button type="button" id="emailbox-custom-add-cancel" class="button-white">返回</button>
                    </span>
                </p>
                <div style="max-height: 265px">
                    <div id="emailbox-custom-add-data"></div>
                </div>
            </div>
        </div>
        <?php $form=\yii\widgets\ActiveForm::begin([
                'id'=>'email-form'
        ]);?>
        <input id="customers" type="hidden" name="customers">
        <input  type="hidden" name="CrmActImessage[imesg_sentman]" value="<?=\Yii::$app->user->identity->staff->staff_id;?>">
        <input  type="hidden" name="CrmActImessage[imesg_type]" value="2">
        <p class="mt-20 mb-10">已选择客户的邮箱</p>
        <textarea name="Select" id="emailbox-has-add-email" style="height: 50px;"></textarea>
        <p class="mt-20 mb-10">可录入其它需要发送信息的邮箱</p>
        <textarea name="Other" id="" style="height: 50px;"></textarea>
        <p class="mt-20 mb-10">邮件主题</p>
        <textarea name="CrmActImessage[imesg_subject]" id=""  style="height: 30px;"></textarea>
        <p class="mt-20 mb-10">邮件内容</p>
        <textarea name="CrmActImessage[imesg_notes]" id="" style="height: 50px;"></textarea>
        <div class="text-center mt-20">
            <button type="submit" class="button-blue send">发邮件</button>
            <button type="button" class="button-white cancel">取消</button>
        </div>
        <?php $form->end(); ?>
    </div>
</div>
<script>
    $(function(){
        $("#email-form").ajaxForm(function(res){
            res=JSON.parse(res);
            parent.layer.alert(res.msg,{icon:2});
            parent.$.fancybox.close();
            parent.$("#message_info_tab").datagrid("reload");
        });
        $("#emailbox-custom-has-add-data").datagrid({
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns:[[
                {field:"cust_sname",title:"公司名称",width:185},
                {field:"member_name",title:"姓名",width:180},
                {field:"cust_position",title:"职位",width:180},
                {field:"cust_email",title:"邮箱",width:200}
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });
        var rows=parent.$("#apply_table").datagrid("getChecked");
        for(var x=0;x<rows.length;x++) {
            $("#emailbox-custom-has-add-data").datagrid("insertRow",{
                index:1,
                row:rows[x]
            });
        };

        updateCustomer();


        $("#emailbox-custom-add-data").datagrid({
            url: "<?=\yii\helpers\Url::to(['index'])?>",
            rownumbers: true,
            method: "get",
            idField: "acth_id",
            loadMsg: "加载数据请稍候。。。",
            pagination: true,
            pageSize:6,
            pageList:[6,12,18],
            singleSelect: true,
            checkOnSelect: false,
            selectOnCheck: false,
            columns:[[
                {field:"cust_sname",title:"公司名称",width:180},
                {field:"cust_shortname",title:"公司简称",width:180},
                {field:"member_name",title:"姓名",width:180},
                {field:"cust_email",title:"邮箱",width:180}
            ]],
            onLoadSuccess: function (data) {
                showEmpty($(this),data.total,0);
            }
        });

        $("#emailbox-custom-add-btn").click(function(){
            $("#emailbox-custom-has-add-wrap").slideUp();
            $("#emailbox-custom-add-wrap").slideDown();
            $("#emailbox-custom-add-data").datagrid("resize");
        });


        $("#emailbox-custom-add-ensure").click(function(){
            var newRows=$("#emailbox-custom-add-data").datagrid("getSelected");
            var oldRows=$("#emailbox-custom-has-add-data").datagrid("getRows");
            $("#emailbox-custom-has-add-wrap").slideDown();
            $("#emailbox-custom-add-wrap").slideUp();
            var flag=true;
            for(var x=0;x<oldRows.length;x++){
                if(oldRows[x].cust_sname==newRows.cust_sname){
                    var flag=false;
                }
            }
            if(flag==true){
                $("#emailbox-custom-has-add-data").datagrid("insertRow",{
                    index:1,
                    row:newRows
                });
            }

            updateCustomer();

            $("#emailbox-custom-has-add-wrap").slideDown();
            $("#emailbox-custom-add-wrap").slideUp();
        });

        $("#emailbox-custom-del-btn").click(function(){
            var row=$("#emailbox-custom-has-add-data").datagrid("getSelected");
            var index=$("#emailbox-custom-has-add-data").datagrid("getRowIndex",row);
            $("#emailbox-custom-has-add-data").datagrid("deleteRow",index);
            updateCustomer();
        });

        $("#emailbox-custom-add-cancel").click(function(){
            $("#emailbox-custom-has-add-wrap").slideDown();
            $("#emailbox-custom-add-wrap").slideUp();
        });


        $(".cancel,.exit").click(function(){
            parent.$.fancybox.close();
        });

        function updateCustomer(){
            var rows=$("#emailbox-custom-has-add-data").datagrid("getRows");
            var tmp=new Array();
            var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            for(var x=0;x<rows.length;x++){
                if(re.test(rows[x].cust_email)){
                    tmp.push(rows[x].cust_email);
                }
            }
            $("#emailbox-has-add-email").val(tmp.join(","));

            var tmp=new Array();
            for(var x=0;x<rows.length;x++){
                tmp.push(rows[x].cust_id);
            }
            $("#customers").val(tmp.join(","));
        }

    });
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