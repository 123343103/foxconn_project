<?php
/**
 * User: F1677929
 * Date: 2017/3/31
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\assets\JqueryUIAsset;
JqueryUIAsset::register($this);
?>
<div class="head-first">选择客户</div>
<div style="margin: 0 20px 20px;">
    <div class="mb-10">
        <?php ActiveForm::begin(['id'=>'customer_form','method'=>'get','action'=>Url::to(['select-customer'])]);?>
        <label class="width-60">关键词</label>
        <input type="text" class="width-200" name="searchKeyword" value="<?=$params['searchKeyword']?>">
        <button type="submit" class="button-blue">查询</button>
        <button type="button" class="button-white" onclick="window.location.href='<?=Url::to(['select-customer'])?>'">重置</button>
<!--        <a href="--><?//=Url::to(['/crm/crm-customer-info/create'])?><!--" target="_blank" class="float-right"><button type="button" class="button-blue" style="width:75px;">新增客户</button></a>-->
        <button type="button" class="button-blue float-right create-customer" style="width:75px;">新增客户</button>
        <?php ActiveForm::end();?>
    </div>
    <div id="customer_data" style="width:100%;"></div>
</div>
<div class="text-center mb-20">
    <button type="button" class="button-blue mr-20" id="confirm_customer">确定</button>
    <button type="button" class="button-white" onclick="parent.$.fancybox.close()">取消</button>
</div>
<a id="create-customer" ></a>
<script>
    $(function () {
        $("#customer_data").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"cust_id",
            singleSelect:true,
            pagination:true,
            pageSize:10,
            pageList:[10],
            columns:[[
                {field:"cust_sname",title:"客户名称",width:200},
//                {field:"cust_shortname",title:"客户简称",width:100},
                {field:"customerManager",title:"客户经理人",width:100},
                {field:"customerAddress",title:"客户地址",width:200},
                {field:"customerType",title:"客户类型",width:94},
            ]],
            onLoadSuccess:function(data){
                    showEmpty($(this),data.total,0);
            }
        });

        //确定
        $("#confirm_customer").click(function(){
            var data=$("#customer_data").datagrid('getSelected');
            if(data==null){
                parent.layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            if(data.customerManager==null){
                if("<?=Yii::$app->user->identity->is_supper?>"=="1"){
                    parent.layer.alert("该客户没有客户经理人，不可新增拜访记录！",{icon:2,time:5000});
                }else{
                    parent.layer.confirm("确认认领该客户吗？",{icon:2},
                        function(){
                            $.ajax({
                                url:"<?=Url::to(['/crm/crm-visit-record/claim-customer'])?>",
                                data:"customerId="+data.cust_id,
                                dataType:"json",
                                success:function(data){
                                    if(data.flag==1){
                                        parent.layer.alert(data.msg,{icon:1,time:5000},function(){
                                            parent.layer.closeAll();
                                            $("#customer_data").datagrid("reload");
                                        });
                                    }else{
                                        parent.layer.alert(data.msg,{icon:2,time:5000});
                                    }
                                }
                            });
                        },
                        layer.closeAll()
                    );
                }
                return false;
            }
            parent.$("#customer_info").show();
            parent.$(".cust_id").val(data.cust_id);
            parent.$(".cust_name").html(data.cust_sname);
            parent.$(".cust_contact").html(data.cust_contacts);
            parent.$(".cust_tel").html(data.cust_tel2);
            parent.$(".cust_address").html(data.customerAddress);
            parent.$("#svp_id").val('');
            parent.$("#svp_code").val('');
            parent.$.fancybox.close();
        });

        // 新增客户
        $(".create-customer").on('click', function () {
            $.fancybox({
                href: "<?= \yii\helpers\Url::to(['create-customer']) ?>",
                padding: 0,
                margin: 0,
                autoScale: true,
                width: 700,
                height: 540,
                autoSize: false,
                closeClick: false,
                openEffect: 'none',
                closeEffect: 'none',
                type: 'iframe',
            });
        })
    })
</script>