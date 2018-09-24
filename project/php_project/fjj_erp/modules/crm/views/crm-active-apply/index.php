<?php
/**
 * User: f1677929
 * Date: 2017/2/23
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use \app\classes\Menu;
\app\assets\JeDateAsset::register($this);
$this->title='报名列表';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <?=$this->render('_search',[
        'params'=>$params,
        'activeType'=>$data['activeType'],
        'activeName'=>$data['activeName'],
    ])?>
    <div class="table-head mb-5">
        <p class="head">活动报名列表</p>
        <ul class="datagrid-menu">
            <?=Menu::isAction('/crm/crm-active-apply/add')?"<li id='add_btn'><a>新增</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-apply/edit')?"<li id='edit_btn'><a>修改</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-apply/view')?"<li id='view_btn'><a>详情</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-apply/delete')?"<li id='delete_btn'><a>删除</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-apply/check-in')?"<li id='check_in_btn'><a>签到</a></li>":""?>
            <?=Menu::isAction('/crm/crm-active-apply/pay')?"<li id='pay_btn'><a>缴费</a></li>":""?>
            <?php if(Menu::isAction('/crm/crm-active-apply/send-message')||Menu::isAction('/crm/crm-active-apply/send-email')){?>
            <li>
                <a>即时通讯</a>
                <ul class="datagrid-submenu">
                    <?=Menu::isAction('/crm/crm-active-apply/send-message')?"<li id='message_btn'><a>发信息</a></li>":""?>
                    <?=Menu::isAction('/crm/crm-active-apply/send-email')?"<li id='email_btn'><a>发邮件</a></li>":""?>
                </ul>
            </li>
            <?php }?>
            <?php if(Menu::isAction('/crm/crm-active-apply/import')||Menu::isAction('/crm/crm-active-apply/export')){?>
            <li>
                <a>数据处理</a>
                <ul class="datagrid-submenu">
                    <?=Menu::isAction('/crm/crm-active-apply/import')?"<li id='import_btn'><a>批量导入</a></li>":""?>
                    <?=Menu::isAction('/crm/crm-active-apply/export')?"<li id='export_btn'><a>批量导出</a></li>":""?>
                </ul>
            </li>
            <?php }?>
            <li><a href="<?=Url::to(['/index/index'])?>">返回</a></li>
        </ul>
    </div>
    <div id="apply_table" class="main-table" style="width:100%;"></div><!--活动报名表-->
    <div id="annex_tab" class="mt-20 easyui-tabs">
        <div title="签到信息">
            <div id="check_in_info_tab"></div><!--签到信息表-->
        </div>
        <div title="缴费信息">
            <div id="pay_info_tab"></div><!--缴费信息表-->
        </div>
        <div title="通讯信息">
            <div id="message_info_tab"></div><!--通讯记录表-->
        </div>
    </div>
</div>
<script>
    $(function(){
        $("#apply_table").datagrid({
            url:"<?=Yii::$app->request->getHostInfo().Yii::$app->request->url?>",
            rownumbers:true,
            method:"get",
            idField:"acth_id",
            singleSelect:true,
            pagination:true,
            checkOnSelect:true,
            selectOnCheck:false,
            columns:[[
                {field:"ck",checkbox:true},
                <?=$data['applyTable']?>
            ]],
            onLoadSuccess:function(data){
                datagridTip("#apply_table");
                showEmpty($(this),data.total,1);
                setMenuHeight();
            },
            onSelect:function(index,row){
                $("#apply_table").datagrid("uncheckAll").datagrid("checkRow",index);
                $("#check_in_info_tab").datagrid({
                    url:"<?=Yii::$app->request->getHostInfo().Url::to(['check-in-info'])?>?applyId="+row.acth_id,
                    rownumbers:true,
                    method:"get",
                    idField:"actcin_id",
                    singleSelect:true,
                    pagination:true,
                    pageSize:10,
                    pageList:[10,20,30],
                    columns:[[
                        {field:"isCheckIn",title:"是否签到",width:150},
                        {field:"actcin_name",title:"活动签到人",width:150},
                        {field:"actcin_position",title:"职位",width:150},
                        {field:"actcin_phone",title:"手机号码",width:150},
                        {field:"actcin_nocause",title:"未到原因",width:200},
                        {field:"actcin_remark",title:"备注",width:200}
                    ]],
                    onLoadSuccess:function(data){
                        datagridTip("#check_in_info_tab");
                        showEmpty($(this),data.total,0);
                        setMenuHeight();
                    }
                });
                $("#pay_info_tab").datagrid({
                    url:"<?=Yii::$app->request->getHostInfo().Url::to(['pay-info'])?>?applyId="+row.acth_id,
                    rownumbers:true,
                    method:"get",
                    idField:"actpaym_id",
                    singleSelect:true,
                    pagination:true,
                    pageSize:10,
                    pageList:[10,20,30],
                    columns:[[
                        {field:"actpaym_date",title:"缴费日期",width:200},
                        {field:"actpaym_amount",title:"缴费金额",width:200},
                        {field:"actpaym_paydesription",title:"缴费描述",width:200},
                        {field:"isBill",title:"是否开票",width:200},
                        {field:"actpaym_remark",title:"备注",width:300}
                    ]],
                    onLoadSuccess:function(data){
                        datagridTip("#pay_info_tab");
                        showEmpty($(this),data.total,0);
                        setMenuHeight();
                    }
                });
                $("#message_info_tab").datagrid({
                    url:"<?=Yii::$app->request->getHostInfo().Url::to(['message-info'])?>?id="+row.cust_id,
                    rownumbers:true,
                    method:"get",
                    idField:"imesg_id",
                    singleSelect:true,
                    pagination:true,
                    pageSize:10,
                    pageList:[10,20,30],
                    columns:[[
                        {field:"imesg_type",title:"通讯类型(邮件或信息)",width:200},
                        {field:"imesg_sentdate",title:"发送日期",width:200},
                        {field:"imesg_subject",title:"主题",width:200},
                        {field:"imesg_notes",title:"邮件或信息内容描述",width:200},
                        {field:"imesg_sentman",title:"发信人",width:200},
                        {field:"imesg_remark",title:"备注",width:200}
                    ]],
                    onLoadSuccess:function(data){
                        datagridTip("#message_info_tab");
                        showEmpty($(this),data.total,0);
                        setMenuHeight();
                    }
                });
            }
        });

        //新增
        $("#add_btn").click(function(){
            window.location.href="<?=Url::to(['add'])?>";
        });

        //修改
        $("#edit_btn").click(function(){
            var obj=$("#apply_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            window.location.href="<?=Url::to(['edit'])?>?id="+obj.acth_id;
        });

        //查看
        $("#view_btn").click(function(){
            var obj=$("#apply_table").datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            window.location.href="<?=Url::to(['view'])?>?id="+obj.acth_id;
        });

        //删除
        $("#delete_btn").click(function(){
            var obj=$("#apply_table").datagrid('getChecked');
            if(obj.length==0){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            var arrId='';
            var success=0;
            var failed=0;
            $.each(obj,function(i,n){
                var endDate=Date.parse((n.actbs_end_time).replace('-','/'));
                var currentDate=Date.parse(new Date());
                if((n.isCheckIn=='已签到')||(endDate<currentDate)){
                    failed++;
                }else{
                    success++;
                    arrId+=n.acth_id+'-';
                }
            });
            if(success==0){
                layer.alert('该客户不可删除(已签到或活动已结束)！',{icon:2,time:5000});
                return false;
            }
            arrId=arrId.substr(0,arrId.length-1);
            layer.confirm('确定删除吗？',{icon:2},
                function(){
                    $.ajax({
                        url:"<?=Url::to(['delete-apply']);?>",
                        data:{"arrId":arrId},
                        dataType:"json",
                        success:function(data){
                            if(data.flag==1){
                                layer.alert("选中"+obj.length+"条，删除成功"+success+"条，删除失败"+failed+"条(已签到或活动已结束不可删除)！",{icon:1},function(){
                                    layer.closeAll();
                                    $("#apply_table").datagrid('reload').datagrid('clearSelections');
                                    $(".tabs-panels > .panel").hide();
                                });
                            }else{
                                layer.alert(data.msg,{icon:2});
                            }
                        }
                    })
                },
                layer.closeAll()
            )
        });

        //签到
        $('#check_in_btn').click(function(){
            var obj=$('#apply_table').datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['check-in'])?>?id="+obj.acth_id,
                type:'iframe',
                padding:0,
                autoSize:false,
                width:800,
                height:500,
                fitToView:false,
            });
        });

        //缴费
        $('#pay_btn').click(function(){
            var obj=$('#apply_table').datagrid('getSelected');
            if(obj==null){
                layer.alert('请选择客户！',{icon:2,time:5000});
                return false;
            }
            if(obj.isPay=='否'){
                layer.alert('该客户不需要缴费！',{icon:2,time:5000});
                return false;
            }
            $.fancybox({
                href:"<?=Url::to(['pay'])?>?id="+obj.acth_id,
                type:'iframe',
                padding:0,
                autoSize:false,
                width:700,
                height:450,
                fitToView:false,
            });
        });

        //数据导出
        $("#export_btn").click(function(){
            var obj=$("#apply_table").datagrid('getData');
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
        });

        //导入
        $("#import_btn").click(function(){
            $.fancybox({
                type:"iframe",
                href:"<?=Url::to(['import'])?>",
                padding:0,
                autoSize:false,
                width:500,
                height:200
            });
        });

        //发邮件
        $("#email_btn").click(function(){
            $.fancybox({
                width:800,
                height:600,
                padding:0,
                autoSize:false,
                type:"iframe",
                href:"<?=Url::to(['send-message','type'=>2])?>"
            });
        });

        //发信息
        $("#message_btn").click(function(){
            $.fancybox({
                width:800,
                height:600,
                autoSize:false,
                padding:0,
                type:"iframe",
                href:"<?=Url::to(['send-message','type'=>1])?>"
            });
        });
    });
</script>