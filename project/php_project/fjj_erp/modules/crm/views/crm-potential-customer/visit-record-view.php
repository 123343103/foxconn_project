<?php
/**
 * User: F1677929
 * Date: 2017/4/1
 */
/* @var $this yii\web\View */
use yii\helpers\Url;
use app\classes\Menu;
use yii\helpers\Html;
use yii\widgets\Pjax;
$this->title='拜访记录详情';
$this->params['homeLike']=['label'=>'客户关系管理','url'=>Url::to(['/index/index'])];
$this->params['breadcrumbs'][]=['label'=>'客户拜访列表','url'=>Url::to(['index'])];
$this->params['breadcrumbs'][]=$this->title;
?>
<div class="content">
    <h1 class="head-first"><?=$this->title?><span style="font-size:12px;color:white;float:right;margin-right:15px;"><?='客户编号：'.$custInfo['cust_filernumber']?></span></h1>
    <div style="margin-bottom:5px;">
        <?= Menu::isAction('/crm/crm-potential-customer/edit')?Html::button("修改",["id"=>"edit","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/delete')?Html::button("删除",["id"=>"remove","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/index')?Html::button("切换列表",["id"=>"to-list","class"=>"button-blue","style"=>"width:80px;"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/switch-status')?Html::button("转招商",["id"=>"switch_investment","class"=>"button-blue"]):'' ?>
        <?= Menu::isAction('/crm/crm-potential-customer/switch-status')?Html::button("转销售",["id"=>"switch_sale","class"=>"button-blue"]):'' ?>
    </div>
    <div style="height:2px;background-color:#9acfea;margin-bottom:10px;"></div>
    <h2 class="head-second">客户基本信息</h2>
    <div class="mb-20">
        <input id="cust_id" name="cust_id"  type="hidden" value="<?=$custInfo['cust_id']?>">
        <label class="width-100" for="">客户名称</label>
        <span class="width-120"><?=$custInfo['cust_sname']?></span>
        <label class="width-100" for="">公司简称</label>
        <span class="width-120"><?=$custInfo['cust_shortname'];?></span>
        <label class="width-100" for="">公司简称</label>
        <span class="width-120"><?=$custInfo['cust_shortname'];?></span>
        <label class="width-100" for="">公司电话</label>
        <span class="width-120"><?=$custInfo['cust_tel1'];?></span>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">详细地址</label>
        <span><?=$custInfo["address"]?></span>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">联系人</label>
        <span class="width-120"><?=$custInfo['cust_contacts'];?></span>
        <label class="width-100" for="">职位</label>
        <span class="width-120"><?=$custInfo['cust_position'];?></span>
        <label class="width-100" for="">手机号码</label>
        <span class="width-120"><?=$custInfo['cust_tel2'];?></span>
        <label class="width-100" for="">邮箱</label>
        <span class="width-150"><?=$custInfo['cust_email'];?></span>
    </div>


    <div class="mb-20">
        <label class="width-100" for="">是否会员</label>
        <span class="width-120"><?=$custInfo['cust_ismember']?></span>
        <label class="width-100" for="">注册时间</label>
        <span class="width-120"><?=$custInfo['cust_regdate'];?></span>
        <label class="width-100" for="">会员类别</label>
        <span class="width-120"><?=$custInfo["member_type"];?></span>
        <label class="width-100" for="">会员名</label>
        <span class="width-120"><?=$custInfo['member_name'];?></span>
    </div>



    <div class="mb-20">
        <label class="width-100" for="">经营模式</label>
        <span class="width-120"><?=$custInfo['cust_businesstype']?></span>
        <label class="width-100" for="">经营范围</label>
        <span class="width-120"><?=$custInfo[member_businessarea];?></span>
        <label class="width-100" for="">需求类别</label>
        <span class="width-120"><?=$custInfo['member_reqdesription'];?></span>
    </div>

    <div class="mb-20">
        <label class="width-100" for="">需求类目</label>
        <span class="width-120"><?=$custInfo['member_reqitemclass']?></span>
        <label class="width-100" for="">潜在需求</label>
        <span class="width-120"><?=$custInfo['member_reqflag']?></span>
        <label class="width-100" for="">需求特征</label>
        <span class="width-120"><?=$custInfo['member_reqcharacter'];?></span>
    </div>













    <h2 class="head-second mt-20">拜访记录 <span class="pull-right mr-10"><a id="add-remind" href="javascript:void(0)">新增提醒</a></span></h2>
    <?php if(!empty($records)){?>
        <?php foreach($records as $key=>$record){?>
            <div class="easyui-panel" data-options="collapsible:true<?=$key==0?"":",collapsed:true"?>,onCollapse:function(){setMenuHeight()},onExpand:function(){setMenuHeight()}" title="<?="<span style='color:black;'>拜访时间：".$record['start'].'~'.$record['end']."</span>"?>&nbsp;&nbsp;&nbsp;&nbsp;<?=Menu::isAction('/crm/crm-visit-record/edit')&&$key==0?"<a href='".Url::to(['visit-record-edit','id'=>$record['sil_id']])."'>修改</a>":""?>&nbsp;&nbsp;&nbsp;&nbsp;<?=Menu::isAction('/crm/crm-visit-record/delete')&&$key==0?"<a onclick='deleteRecord(".$record['sil_id'].")'>删除</a>":""?>">
                <div class="space-10"></div>
                <div class="mb-10">
                    <label class="width-100">拜访人</label>
                    <span class="width-200"><?=$record['sil_staff_code']?></span>
                    <label class="width-100">拜访类型</label>
                    <span class="width-200"><?=$record['visitType']['visitTypeName']?></span>
                    <label class="width-100">拜访用时</label>
                    <span class="width-200"><?=$record['visitUseTime']?></span>
                </div>
                <div class="mb-10">
                    <label class="width-100">拜访总结</label>
                    <span class="text-top" style="width:880px;"><?=$record['sil_interview_conclus']?></span>
                </div>
                <div class="mb-10">
                    <label class="width-100">执行项目</label>
                    <span style="width:880px;"><?=$record['execute_project']?></span>
                </div>
                <div class="mb-10">
                    <label class="width-100">关联拜访计划</label>
                    <span style="width:880px;"><?=$record['visitPlan']['title']?></span>
                </div>
            </div>
            <div class="space-10"></div>
        <?php }?>
    <?php }?>
</div>
<script>
    $(function(){

        //修改潜在客户
        $("#edit").click(function(){
            window.location.href="<?=Url::to(['edit','id'=>\Yii::$app->request->get('cust_id')])?>";
        });

        //删除潜在客户
        $("#remove").click(function(){
            layer.confirm("确定要删除选中的记录吗？",{
                btn: ['確定', '取消'],
                icon: 2
            },function(){
                $.get("<?=Url::to(['delete','id'=>\Yii::$app->request->get('cust_id')])?>",function(res){
                    obj=JSON.parse(res);
                    if(obj.flag==1){
                        layer.alert("删除成功",{icon:1},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }else{
                        layer.alert("删除失败",{icon:2},function(){
                            window.location.href="<?=Url::to(['index'])?>";
                        });
                    }
                });
            });
        });

        //切换到列表页
        $("#to-list").click(function(){
            window.location.href="<?=Url::to(['index'])?>";
        });

        //转销售
        $("#switch_sale").click(function(){
            $.get("<?=Url::to(["switch-status"])?>?type=sale_status&customers=<?=\Yii::$app->request->get('cust_id');?>",function(res){
                obj=JSON.parse(res);
                if(obj.flag==1){
                    layer.alert("操作成功",{icon:1},function(){
                        window.location.href="<?=Url::to(['index'])?>";
                    });
                }else{
                    layer.alert("操作失败",{icon:2},function(){
                        window.location.href="<?=Url::to(['index'])?>";
                    });
                }
            });
        });


        //转招商
        $("#switch_investment").click(function(){
            $.get("<?=Url::to(["switch-status"])?>?type=investment_status&customers=<?=\Yii::$app->request->get('cust_id');?>",function(res) {
                obj=JSON.parse(res);
                if(obj.flag==1){
                    layer.alert("操作成功",{icon:1},function(){
                        window.location.href="<?=Url::to(['index'])?>";
                    });
                }else{
                    layer.alert("操作失败",{icon:2},function(){
                        window.location.href="<?=Url::to(['index'])?>";
                    });
                }
            });
        });

        //新增提醒
        $("#add-remind").click(function(){
            var cust_id=$("#cust_id").val();
            if(!cust_id){
                layer.alert("请选择客户",{icon:2,time:5000});
            }else{
                $.fancybox({
                    href:"<?=\yii\helpers\Url::to(['add-remind'])?>?id="+cust_id,
                    width:640,
                    height:400,
                    padding:0,
                    autoSize: false,
                    scrolling:false,
                    type : 'iframe'
                });
            }
        });
    });
    function deleteRecord(id){
        layer.confirm("确定删除吗?",{icon:2},
            function(){
                $.ajax({
                    url:"<?=Url::to(['visit-record-delete'])?>",
                    data:{"id":id},
                    dataType:"json",
                    success:function(data){
                        if(data.flag==1){
                            layer.alert(data.msg,{icon:1},function(){
                                location.reload();
                            });
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    }
                })
            },
            layer.closeAll()
        );
    }
</script>